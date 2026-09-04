<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\ItemPembayaranKi;
use App\Models\JenisIuranKi;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KiController extends Controller
{
    public function index(Request $request)
    {
        $daftarTahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        $selectedId = $request->query('tahun_ajaran_id');

        if ($selectedId) {
            $selectedTa = $daftarTahunAjaran->firstWhere('id', $selectedId);
        } else {
            $selectedTa = $daftarTahunAjaran->firstWhere('is_active', true) ?? $daftarTahunAjaran->first();
        }

        $tahunAjaranId = $selectedTa?->id;
        $tahunAjaranNama = $selectedTa?->nama;

        $data = Pembayaran::with(['siswa', 'detailPembayaran', 'tahunAjaran', 'jenisPembayaran', 'itemsKi.jenisIuran'])
            ->whereHas('jenisPembayaran', function ($q) {
                $q->where('nama', 'KI');
            })
            ->when($tahunAjaranId, function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where(function ($sub) use ($tahunAjaranId, $tahunAjaranNama) {
                    $sub->where('tahun_ajaran_id', $tahunAjaranId)
                        ->orWhere(function ($s2) use ($tahunAjaranNama) {
                            $s2->whereNull('tahun_ajaran_id')
                               ->where('tahun_ajaran', $tahunAjaranNama);
                        });
                });
            })
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        // Master jenis iuran untuk modal manajemen & form tambah
        $daftarJenisIuran = JenisIuranKi::withCount('items')->orderBy('id')->get();
        $jenisIuranAktif  = JenisIuranKi::where('is_active', true)->orderBy('id')->get();
        $siswaList        = Siswa::orderBy('nama')->get();

        return view('ki.index', compact(
            'data',
            'daftarTahunAjaran',
            'selectedTa',
            'tahunAjaranId',
            'tahunAjaranNama',
            'daftarJenisIuran',
            'jenisIuranAktif',
            'siswaList'
        ));
    }

    public function create()
    {
        $siswa = Siswa::orderBy('nama')->get();
        $jenis = JenisPembayaran::where('nama', 'KI')->first();
        $tahunAktif = $this->getTahunAjaranAktif();
        $tahunAjaranId = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        $jenisIuranAktif = JenisIuranKi::where('is_active', true)->orderBy('id')->get();

        return view('ki.create', compact('siswa', 'jenisIuranAktif', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'       => 'required|exists:siswa,id',
            'iuran_ids'      => 'nullable|array',
            'iuran_ids.*'    => 'exists:jenis_iuran_ki,id',
            'nominals'       => 'nullable|array',
            // Fallback legacy support jika ada input target_uts / target_uas / target_ujian / target
            'target_uts'     => 'nullable|numeric|min:0',
            'target_uas'     => 'nullable|numeric|min:0',
            'target_ujian'   => 'nullable|numeric|min:0',
            'target'         => 'nullable|numeric|min:0',
        ]);

        $jenis = JenisPembayaran::where('nama', 'KI')->firstOrFail();
        $tahunAktif = $this->getTahunAjaranAktif();
        $tahunAjaranId = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        // Ambil atau buat record pembayaran induk untuk siswa di tahun ajaran ini
        $pembayaran = Pembayaran::where('siswa_id', $request->siswa_id)
            ->where('jenis_id', $jenis->id)
            ->where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                if ($tahunAjaranId) {
                    $q->where('tahun_ajaran_id', $tahunAjaranId)
                      ->orWhere('tahun_ajaran', $tahunAjaranNama);
                }
            })
            ->first();

        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'siswa_id'        => $request->siswa_id,
                'jenis_id'        => $jenis->id,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tahun_ajaran'    => $tahunAjaranNama,
                'target'          => 0,
                'belum_lunas'     => 0,
                'status'          => 'Belum Lunas',
            ]);
        }

        $siswa = Siswa::find($request->siswa_id);
        $addedItems = 0;
        $duplicates = [];

        // 1. Proses input dinamis jika ada iuran_ids
        if ($request->has('iuran_ids') && is_array($request->iuran_ids)) {
            $selectedMaster = JenisIuranKi::whereIn('id', $request->iuran_ids)->get();

            foreach ($selectedMaster as $master) {
                // Cek duplikasi pada pembayaran siswa ini
                $exists = ItemPembayaranKi::where('pembayaran_id', $pembayaran->id)
                    ->where('nama_iuran', $master->nama)
                    ->exists();

                if ($exists) {
                    $duplicates[] = $master->nama;
                    continue;
                }

                $nominal = (float) ($request->nominals[$master->id] ?? $master->nominal_default);

                ItemPembayaranKi::create([
                    'pembayaran_id'     => $pembayaran->id,
                    'jenis_iuran_ki_id' => $master->id,
                    'nama_iuran'        => $master->nama,
                    'nominal'           => $nominal,
                ]);

                $addedItems++;
            }
        }

        // 2. Fallback jika ada input legacy (target_uts, target_uas, target_ujian)
        if ($addedItems === 0 && empty($duplicates)) {
            $uts   = (float) ($request->target_uts ?? 0);
            $uas   = (float) ($request->target_uas ?? 0);
            $ujian = (float) ($request->target_ujian ?? 0);
            $total = (float) ($request->target ?? 0);

            if ($uts > 0) {
                $masterUts = JenisIuranKi::firstOrCreate(['nama' => 'STS Gasal'], ['nominal_default' => 0, 'is_active' => true]);
                ItemPembayaranKi::firstOrCreate(
                    ['pembayaran_id' => $pembayaran->id, 'nama_iuran' => 'STS Gasal'],
                    ['jenis_iuran_ki_id' => $masterUts->id, 'nominal' => $uts]
                );
                $addedItems++;
            }

            if ($uas > 0) {
                $masterUas = JenisIuranKi::firstOrCreate(['nama' => 'SAS'], ['nominal_default' => 0, 'is_active' => true]);
                ItemPembayaranKi::firstOrCreate(
                    ['pembayaran_id' => $pembayaran->id, 'nama_iuran' => 'SAS'],
                    ['jenis_iuran_ki_id' => $masterUas->id, 'nominal' => $uas]
                );
                $addedItems++;
            }

            if ($ujian > 0) {
                $masterUjian = JenisIuranKi::firstOrCreate(['nama' => 'ASAJ'], ['nominal_default' => 0, 'is_active' => true]);
                ItemPembayaranKi::firstOrCreate(
                    ['pembayaran_id' => $pembayaran->id, 'nama_iuran' => 'ASAJ'],
                    ['jenis_iuran_ki_id' => $masterUjian->id, 'nominal' => $ujian]
                );
                $addedItems++;
            }

            if ($addedItems === 0 && $total > 0) {
                $masterAsaj = JenisIuranKi::firstOrCreate(['nama' => 'ASAJ'], ['nominal_default' => 0, 'is_active' => true]);
                ItemPembayaranKi::firstOrCreate(
                    ['pembayaran_id' => $pembayaran->id, 'nama_iuran' => 'ASAJ'],
                    ['jenis_iuran_ki_id' => $masterAsaj->id, 'nominal' => $total]
                );
                $addedItems++;
            }
        }

        // Jika semua yang dipilih ternyata duplikat
        if ($addedItems === 0 && !empty($duplicates)) {
            $msg = "Siswa " . ($siswa?->nama ?? '') . " sudah memiliki tagihan: " . implode(', ', $duplicates) . " pada tahun ajaran ini!";
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['iuran_ids' => $msg])
                ->with('error', $msg);
        }

        // Update target pembayaran induk = total akumulasi item
        $totalTarget = (float) $pembayaran->itemsKi()->sum('nominal');
        $pembayaran->target = $totalTarget;

        $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $totalKewajiban = $totalTarget + (float) ($pembayaran->belum_lunas ?? 0);
        $pembayaran->status = ($totalKewajiban > 0 && $terbayarTotal >= $totalKewajiban) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        $successMsg = 'Tagihan Asesmen berhasil ditambahkan.';
        if (!empty($duplicates)) {
            $successMsg .= ' (Beberapa item dilewati karena sudah ada: ' . implode(', ', $duplicates) . ')';
        }

        return redirect()->route('ki.index')->with('success', $successMsg);
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa',
            'detailPembayaran',
            'jenisPembayaran',
            'tahunAjaran',
            'itemsKi.jenisIuran'
        ])->findOrFail($id);

        return view('ki.bayar', compact('pembayaran'));
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'kategori'   => 'required|string',
            'nominal'    => 'required|numeric|min:1',
            'metode'     => 'required|string',
            'keterangan' => 'nullable|string',
            'bukti'      => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:3072',
        ]);

        $pembayaran = Pembayaran::with(['detailPembayaran', 'itemsKi'])->findOrFail($id);
        $kategori   = trim($request->kategori);
        $nominal    = (float) $request->nominal;

        $sisaKategori = $pembayaran->sisaKiKategori($kategori);

        if ($sisaKategori <= 0) {
            throw ValidationException::withMessages([
                'nominal' => "Tagihan {$kategori} untuk siswa ini sudah lunas.",
            ]);
        }

        if ($nominal > $sisaKategori) {
            throw ValidationException::withMessages([
                'nominal' => "Nominal pembayaran (Rp " . number_format($nominal, 0, ',', '.') . ") melebihi sisa tagihan {$kategori} (Rp " . number_format($sisaKategori, 0, ',', '.') . ").",
            ]);
        }

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $destinationPath = public_path('uploads/bukti_pembayaran');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $buktiPath = 'uploads/bukti_pembayaran/' . $filename;
        }

        $detail = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'tanggal'       => now(),
            'nominal'       => $nominal,
            'kategori'      => $kategori,
            'metode'        => $request->metode ?? 'Cash',
            'keterangan'    => $request->keterangan,
            'bukti'         => $buktiPath,
        ]);

        // Update status pembayaran induk
        $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $totalTagihan  = (float) $pembayaran->target + (float) ($pembayaran->belum_lunas ?? 0);
        $pembayaran->status = ($totalTagihan > 0 && $terbayarTotal >= $totalTagihan) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        return redirect()
            ->back()
            ->with('success', "Pembayaran Asesmen ({$kategori}) berhasil disimpan.")
            ->with('last_detail_id', $detail->id);
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa',
            'jenisPembayaran',
            'tahunAjaran',
            'itemsKi.jenisIuran'
        ])->findOrFail($id);

        $siswa = Siswa::orderBy('nama')->get();
        $jenisIuranAktif = JenisIuranKi::where('is_active', true)->orderBy('id')->get();

        return view('ki.edit', compact('pembayaran', 'siswa', 'jenisIuranAktif'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::with('itemsKi')->findOrFail($id);

        $request->validate([
            'items'            => 'nullable|array',
            'items.*.id'       => 'nullable|exists:item_pembayaran_ki,id',
            'items.*.nominal'  => 'required|numeric|min:0',
            // Tambah item baru
            'new_iuran_ids'    => 'nullable|array',
            'new_iuran_ids.*'  => 'exists:jenis_iuran_ki,id',
            'new_nominals'     => 'nullable|array',
        ]);

        // 1. Update nominal item yang sudah ada
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemId => $itemData) {
                $item = ItemPembayaranKi::where('pembayaran_id', $pembayaran->id)->find($itemId);
                if ($item) {
                    $item->nominal = (float) ($itemData['nominal'] ?? $item->nominal);
                    $item->save();
                }
            }
        }

        // 2. Tambah item baru jika ada
        if ($request->has('new_iuran_ids') && is_array($request->new_iuran_ids)) {
            $selectedMaster = JenisIuranKi::whereIn('id', $request->new_iuran_ids)->get();

            foreach ($selectedMaster as $master) {
                $exists = ItemPembayaranKi::where('pembayaran_id', $pembayaran->id)
                    ->where('nama_iuran', $master->nama)
                    ->exists();

                if (!$exists) {
                    $nominal = (float) ($request->new_nominals[$master->id] ?? $master->nominal_default);
                    ItemPembayaranKi::create([
                        'pembayaran_id'     => $pembayaran->id,
                        'jenis_iuran_ki_id' => $master->id,
                        'nama_iuran'        => $master->nama,
                        'nominal'           => $nominal,
                    ]);
                }
            }
        }

        // Recalculate target
        $totalTarget = (float) $pembayaran->itemsKi()->sum('nominal');
        $pembayaran->target = $totalTarget;

        $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $totalTagihan  = $totalTarget + (float) ($pembayaran->belum_lunas ?? 0);
        $pembayaran->status = ($totalTagihan > 0 && $terbayarTotal >= $totalTagihan) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        return redirect()->route('ki.index')->with('success', 'Data tagihan Asesmen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->detailPembayaran()->delete();
        $pembayaran->itemsKi()->delete();
        $pembayaran->delete();

        return redirect()->route('ki.index')->with('success', 'Data tagihan berhasil dihapus.');
    }

    // =========================================================================
    // MASTER JENIS IURAN (MANAJEMEN DINAMIS OLEH ADMIN)
    // =========================================================================

    public function storeJenisIuran(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:100|unique:jenis_iuran_ki,nama',
            'nominal_default' => 'nullable|numeric|min:0',
            'is_active'       => 'nullable|boolean',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $item = JenisIuranKi::create([
            'nama'            => trim($request->nama),
            'nominal_default' => (float) ($request->nominal_default ?? 0),
            'is_active'       => $request->boolean('is_active', true),
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->back()->with('success', "Jenis iuran '{$item->nama}' berhasil ditambahkan ke master.");
    }

    public function updateJenisIuran(Request $request, $id)
    {
        $item = JenisIuranKi::findOrFail($id);

        $request->validate([
            'nama'            => 'required|string|max:100|unique:jenis_iuran_ki,nama,' . $item->id,
            'nominal_default' => 'nullable|numeric|min:0',
            'is_active'       => 'nullable|boolean',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $item->update([
            'nama'            => trim($request->nama),
            'nominal_default' => (float) ($request->nominal_default ?? 0),
            'is_active'       => $request->boolean('is_active', $item->is_active),
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->back()->with('success', "Jenis iuran '{$item->nama}' berhasil diperbarui.");
    }

    public function toggleJenisIuran($id)
    {
        $item = JenisIuranKi::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        $statusStr = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Jenis iuran '{$item->nama}' berhasil {$statusStr}.");
    }

    public function destroyJenisIuran($id)
    {
        $item = JenisIuranKi::withCount('items')->findOrFail($id);

        if ($item->items_count > 0) {
            // Lindungi histori: jangan hapus fisik jika sudah pernah digunakan
            $item->is_active = false;
            $item->save();

            return redirect()->back()->with('warning', "Jenis iuran '{$item->nama}' sudah digunakan pada {$item->items_count} tagihan siswa. Status diubah menjadi non-aktif untuk menjaga keutuhan histori.");
        }

        $nama = $item->nama;
        $item->delete();

        return redirect()->back()->with('success', "Jenis iuran '{$nama}' berhasil dihapus dari master.");
    }

    // =========================================================================
    // FITUR TAMBAHAN: TERAPKAN TAGIHAN MASSAL KE SEMUA SISWA DENGAN KONFIRMASI
    // =========================================================================

    public function terapkanMassal(Request $request)
    {
        $request->validate([
            'jenis_iuran_id' => 'required|exists:jenis_iuran_ki,id',
            'nominal'        => 'nullable|numeric|min:0',
        ]);

        $jenisIuran = JenisIuranKi::findOrFail($request->jenis_iuran_id);
        $jenisKi    = JenisPembayaran::where('nama', 'KI')->firstOrFail();

        $tahunAktif      = $this->getTahunAjaranAktif();
        $tahunAjaranId   = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        $nominal = $request->filled('nominal')
            ? (float) $request->nominal
            : (float) $jenisIuran->nominal_default;

        $allSiswa = Siswa::all();
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($allSiswa as $siswa) {
            // Ambil atau buat pembayaran induk
            $pembayaran = Pembayaran::firstOrCreate(
                [
                    'siswa_id'        => $siswa->id,
                    'jenis_id'        => $jenisKi->id,
                    'tahun_ajaran_id' => $tahunAjaranId,
                ],
                [
                    'tahun_ajaran' => $tahunAjaranNama,
                    'target'       => 0,
                    'belum_lunas'  => 0,
                    'status'       => 'Belum Lunas',
                ]
            );

            // Cek apakah siswa sudah punya tagihan ini
            $exists = ItemPembayaranKi::where('pembayaran_id', $pembayaran->id)
                ->where(function ($q) use ($jenisIuran) {
                    $q->where('jenis_iuran_ki_id', $jenisIuran->id)
                      ->orWhere('nama_iuran', $jenisIuran->nama);
                })
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            ItemPembayaranKi::create([
                'pembayaran_id'     => $pembayaran->id,
                'jenis_iuran_ki_id' => $jenisIuran->id,
                'nama_iuran'        => $jenisIuran->nama,
                'nominal'           => $nominal,
            ]);

            // Update target pembayaran induk
            $totalTarget = (float) $pembayaran->itemsKi()->sum('nominal');
            $pembayaran->target = $totalTarget;
            $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
            $totalKewajiban = $totalTarget + (float) ($pembayaran->belum_lunas ?? 0);
            $pembayaran->status = ($totalKewajiban > 0 && $terbayarTotal >= $totalKewajiban) ? 'Lunas' : 'Belum Lunas';
            $pembayaran->save();

            $createdCount++;
        }

        return redirect()->route('ki.index')->with(
            'success',
            "Tagihan '{$jenisIuran->nama}' sebesar Rp " . number_format($nominal, 0, ',', '.') . " berhasil diterapkan ke {$createdCount} siswa aktif. ({$skippedCount} siswa dilewati karena sudah memiliki tagihan ini)."
        );
    }
}