<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\ItemPembayaranEkstrakurikuler;
use App\Models\JenisEkstrakurikuler;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EkstrakurikulerController extends Controller
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

        $sort = $request->query('sort', 'nama');
        $direction = strtolower($request->query('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
        if (!in_array($sort, ['nis', 'nama', 'sisa'])) {
            $sort = 'nama';
            $direction = 'asc';
        }

        $query = Pembayaran::with(['siswa', 'detailPembayaran', 'tahunAjaran', 'jenisPembayaran', 'itemsEkskul.jenisEkstrakurikuler'])
            ->leftJoin('siswa', 'pembayaran.siswa_id', '=', 'siswa.id')
            ->select('pembayaran.*')
            ->whereHas('jenisPembayaran', function ($q) {
                $q->where('nama', 'Ekstrakurikuler');
            })
            ->when($tahunAjaranId, function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where(function ($sub) use ($tahunAjaranId, $tahunAjaranNama) {
                    $sub->where('pembayaran.tahun_ajaran_id', $tahunAjaranId)
                        ->orWhere(function ($s2) use ($tahunAjaranNama) {
                            $s2->whereNull('pembayaran.tahun_ajaran_id')
                               ->where('pembayaran.tahun_ajaran', $tahunAjaranNama);
                        });
                });
            });

        if ($sort === 'nis') {
            $query->orderBy('siswa.nis', $direction)->orderBy('siswa.nama', 'asc');
        } elseif ($sort === 'nama') {
            $query->orderBy('siswa.nama', $direction)->orderBy('siswa.nis', 'asc');
        } elseif ($sort === 'sisa') {
            $query->orderByRaw("(COALESCE(pembayaran.target, 0) + COALESCE(pembayaran.belum_lunas, 0) - COALESCE((SELECT SUM(nominal) FROM detail_pembayaran WHERE detail_pembayaran.pembayaran_id = pembayaran.id), 0)) {$direction}")
                  ->orderBy('siswa.nama', 'asc');
        }

        $data = $query->paginate(25)->withQueryString();

        // Master jenis ekstrakurikuler untuk modal manajemen & form tambah
        $daftarJenisEkskul = JenisEkstrakurikuler::withCount('items')->orderBy('id')->get();
        $jenisEkskulAktif  = JenisEkstrakurikuler::where('is_active', true)->orderBy('id')->get();
        $siswaList         = Siswa::orderBy('nama')->get();

        return view('ekstrakurikuler.index', compact(
            'data',
            'daftarTahunAjaran',
            'selectedTa',
            'tahunAjaranId',
            'tahunAjaranNama',
            'daftarJenisEkskul',
            'jenisEkskulAktif',
            'siswaList',
            'sort',
            'direction'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'    => 'required|exists:siswa,id',
            'ekskul_ids'  => 'nullable|array',
            'ekskul_ids.*'=> 'exists:jenis_ekstrakurikuler,id',
            'nominals'    => 'nullable|array',
            'target'      => 'nullable|numeric|min:0',
        ]);

        $jenis = JenisPembayaran::where('nama', 'Ekstrakurikuler')->firstOrFail();
        $tahunAktif = $this->getTahunAjaranAktif();
        $tahunAjaranId = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        // Ambil atau buat record pembayaran induk untuk siswa di tahun ajaran ini
        $pembayaran = Pembayaran::where('siswa_id', $request->siswa_id)
            ->where('jenis_id', $jenis->id)
            ->where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                if ($tahunAjaranId) {
                    $q->where('tahun_ajaran_id', $tahunAjaranId);
                } else {
                    $q->where('tahun_ajaran', $tahunAjaranNama);
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

        // Simpan item-item ekstrakurikuler yang dipilih
        if ($request->has('ekskul_ids') && is_array($request->ekskul_ids)) {
            $selectedMaster = JenisEkstrakurikuler::whereIn('id', $request->ekskul_ids)->get();

            foreach ($selectedMaster as $master) {
                $nominal = isset($request->nominals[$master->id]) && is_numeric($request->nominals[$master->id])
                    ? (float) $request->nominals[$master->id]
                    : (float) $master->nominal_default;

                ItemPembayaranEkstrakurikuler::updateOrCreate(
                    [
                        'pembayaran_id' => $pembayaran->id,
                        'nama_ekskul'   => $master->nama,
                    ],
                    [
                        'jenis_ekstrakurikuler_id' => $master->id,
                        'nominal'                  => $nominal,
                    ]
                );
            }
        }

        // Kalkulasi ulang total target pembayaran induk
        $totalTarget = (float) $pembayaran->itemsEkskul()->sum('nominal');
        $pembayaran->target = $totalTarget;
        $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $totalKewajiban = $totalTarget + (float) ($pembayaran->belum_lunas ?? 0);
        $pembayaran->status = ($totalKewajiban > 0 && $terbayarTotal >= $totalKewajiban) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Data tagihan ekstrakurikuler siswa berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::with(['itemsEkskul', 'detailPembayaran'])->findOrFail($id);

        $request->validate([
            'items'            => 'nullable|array',
            'new_ekskul_ids'   => 'nullable|array',
            'new_ekskul_ids.*' => 'exists:jenis_ekstrakurikuler,id',
            'new_nominals'     => 'nullable|array',
        ]);

        // 1. Update nominal item yang sudah ada
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemId => $itemData) {
                $nominal = is_array($itemData) ? ($itemData['nominal'] ?? null) : $itemData;
                if ($nominal === null) continue;

                $item = ItemPembayaranEkstrakurikuler::where('pembayaran_id', $pembayaran->id)
                    ->where('id', $itemId)
                    ->first();

                if ($item) {
                    $terbayarItem = $item->terbayar;
                    if ((float) $nominal < $terbayarItem) {
                        throw ValidationException::withMessages([
                            "items.{$itemId}" => "Nominal tagihan '{$item->nama_ekskul}' tidak boleh lebih kecil dari yang sudah dibayar (Rp " . number_format($terbayarItem, 0, ',', '.') . ").",
                        ]);
                    }
                    $item->nominal = (float) $nominal;
                    $item->save();
                }
            }
        }

        // 2. Tambah item ekstrakurikuler baru jika ada yang dipilih
        $newIds = $request->input('new_ekskul_ids', $request->input('new_iuran_ids', []));
        if (!empty($newIds) && is_array($newIds)) {
            $selectedMaster = JenisEkstrakurikuler::whereIn('id', $newIds)->get();

            foreach ($selectedMaster as $master) {
                $nominal = isset($request->new_nominals[$master->id]) && is_numeric($request->new_nominals[$master->id])
                    ? (float) $request->new_nominals[$master->id]
                    : (float) $master->nominal_default;

                ItemPembayaranEkstrakurikuler::updateOrCreate(
                    [
                        'pembayaran_id' => $pembayaran->id,
                        'nama_ekskul'   => $master->nama,
                    ],
                    [
                        'jenis_ekstrakurikuler_id' => $master->id,
                        'nominal'                  => $nominal,
                    ]
                );
            }
        }

        // 3. Kalkulasi ulang total target pembayaran induk
        $totalTarget = (float) $pembayaran->itemsEkskul()->sum('nominal');
        $pembayaran->target = $totalTarget;
        $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $totalKewajiban = $totalTarget + (float) ($pembayaran->belum_lunas ?? 0);
        $pembayaran->status = ($totalKewajiban > 0 && $terbayarTotal >= $totalKewajiban) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Data tagihan ekstrakurikuler siswa berhasil diperbarui.');
    }

    public function bayar(Request $request, $id)
    {
        $pembayaran = Pembayaran::with(['itemsEkskul', 'detailPembayaran'])->findOrFail($id);

        $request->validate([
            'nominal'  => 'required|numeric|min:1',
            'tanggal'  => 'required|date',
            'kategori' => 'nullable|string|max:100',
        ]);

        $nominalBayar = (float) $request->nominal;
        $kategori = trim($request->kategori ?? '');

        // Jika memilih kategori spesifik, validasi tidak melebihi sisa item tersebut
        if ($kategori !== '') {
            $sisaKat = $pembayaran->sisaEkskulKategori($kategori);
            if ($nominalBayar > $sisaKat) {
                throw ValidationException::withMessages([
                    'nominal' => "Nominal pembayaran melebihi sisa tagihan untuk '{$kategori}' (Sisa: Rp " . number_format($sisaKat, 0, ',', '.') . ").",
                ]);
            }
        } else {
            // Validasi umum terhadap total sisa tagihan
            $totalTagihan = (float) $pembayaran->target + (float) ($pembayaran->belum_lunas ?? 0);
            $totalTerbayar = (float) $pembayaran->detailPembayaran->sum('nominal');
            $sisaTotal = max($totalTagihan - $totalTerbayar, 0);

            if ($nominalBayar > $sisaTotal) {
                throw ValidationException::withMessages([
                    'nominal' => 'Nominal pembayaran melebihi sisa total tagihan siswa (Sisa: Rp ' . number_format($sisaTotal, 0, ',', '.') . ').',
                ]);
            }
        }

        // Simpan transaksi pembayaran
        $detail = DetailPembayaran::create([
            'pembayaran_id' => $pembayaran->id,
            'nominal'       => $nominalBayar,
            'tanggal'       => $request->tanggal,
            'kategori'      => $kategori !== '' ? $kategori : 'Ekstrakurikuler',
        ]);

        // Cek status pelunasan induk
        $totalTagihan = (float) $pembayaran->target + (float) ($pembayaran->belum_lunas ?? 0);
        $totalTerbayar = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $pembayaran->status = ($totalTagihan > 0 && $totalTerbayar >= $totalTagihan) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Pembayaran ekstrakurikuler sebesar Rp ' . number_format($nominalBayar, 0, ',', '.') . ' berhasil dicatat.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::with('detailPembayaran')->findOrFail($id);

        if ($pembayaran->detailPembayaran->count() > 0) {
            return redirect()->back()->with('warning', 'Data tidak dapat dihapus karena sudah memiliki riwayat pembayaran.');
        }

        $pembayaran->itemsEkskul()->delete();
        $pembayaran->delete();

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Data tagihan ekstrakurikuler berhasil dihapus.');
    }

    // =========================================================================
    // MASTER JENIS EKSTRAKURIKULER (MANAJEMEN DINAMIS OLEH ADMIN)
    // =========================================================================

    public function storeJenis(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:100|unique:jenis_ekstrakurikuler,nama',
            'nominal_default' => 'nullable|numeric|min:0',
            'is_active'       => 'nullable|boolean',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $item = JenisEkstrakurikuler::create([
            'nama'            => trim($request->nama),
            'nominal_default' => (float) ($request->nominal_default ?? 0),
            'is_active'       => $request->boolean('is_active', true),
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->back()->with('success', "Ekstrakurikuler '{$item->nama}' berhasil ditambahkan ke master.");
    }

    public function updateJenis(Request $request, $id)
    {
        $item = JenisEkstrakurikuler::findOrFail($id);

        $request->validate([
            'nama'            => 'required|string|max:100|unique:jenis_ekstrakurikuler,nama,' . $item->id,
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

        return redirect()->back()->with('success', "Ekstrakurikuler '{$item->nama}' berhasil diperbarui.");
    }

    public function toggleJenis($id)
    {
        $item = JenisEkstrakurikuler::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        $statusStr = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Ekstrakurikuler '{$item->nama}' berhasil {$statusStr}.");
    }

    public function destroyJenis($id)
    {
        $item = JenisEkstrakurikuler::withCount('items')->findOrFail($id);

        if ($item->items_count > 0) {
            $item->is_active = false;
            $item->save();

            return redirect()->back()->with('warning', "Ekstrakurikuler '{$item->nama}' sudah digunakan pada {$item->items_count} tagihan siswa. Status diubah menjadi non-aktif untuk menjaga keutuhan histori.");
        }

        $nama = $item->nama;
        $item->delete();

        return redirect()->back()->with('success', "Ekstrakurikuler '{$nama}' berhasil dihapus dari master.");
    }

    // =========================================================================
    // FITUR TERAPKAN TAGIHAN MASSAL KE SEMUA SISWA
    // =========================================================================

    public function terapkanMassal(Request $request)
    {
        $request->validate([
            'jenis_ekskul_id' => 'required|exists:jenis_ekstrakurikuler,id',
            'nominal'         => 'nullable|numeric|min:0',
        ]);

        $jenisEkskul = JenisEkstrakurikuler::findOrFail($request->jenis_ekskul_id);
        $jenisInduk  = JenisPembayaran::where('nama', 'Ekstrakurikuler')->firstOrFail();

        $tahunAktif      = $this->getTahunAjaranAktif();
        $tahunAjaranId   = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        $nominal = $request->filled('nominal')
            ? (float) $request->nominal
            : (float) $jenisEkskul->nominal_default;

        $allSiswa = Siswa::all();
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($allSiswa as $siswa) {
            $pembayaran = Pembayaran::firstOrCreate(
                [
                    'siswa_id'        => $siswa->id,
                    'jenis_id'        => $jenisInduk->id,
                    'tahun_ajaran_id' => $tahunAjaranId,
                ],
                [
                    'tahun_ajaran' => $tahunAjaranNama,
                    'target'       => 0,
                    'belum_lunas'  => 0,
                    'status'       => 'Belum Lunas',
                ]
            );

            $exists = ItemPembayaranEkstrakurikuler::where('pembayaran_id', $pembayaran->id)
                ->where(function ($q) use ($jenisEkskul) {
                    $q->where('jenis_ekstrakurikuler_id', $jenisEkskul->id)
                      ->orWhere('nama_ekskul', $jenisEkskul->nama);
                })
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            ItemPembayaranEkstrakurikuler::create([
                'pembayaran_id'            => $pembayaran->id,
                'jenis_ekstrakurikuler_id' => $jenisEkskul->id,
                'nama_ekskul'              => $jenisEkskul->nama,
                'nominal'                  => $nominal,
            ]);

            $totalTarget = (float) $pembayaran->itemsEkskul()->sum('nominal');
            $pembayaran->target = $totalTarget;
            $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
            $totalKewajiban = $totalTarget + (float) ($pembayaran->belum_lunas ?? 0);
            $pembayaran->status = ($totalKewajiban > 0 && $terbayarTotal >= $totalKewajiban) ? 'Lunas' : 'Belum Lunas';
            $pembayaran->save();

            $createdCount++;
        }

        return redirect()->route('ekstrakurikuler.index')->with(
            'success',
            "Tagihan Ekstrakurikuler '{$jenisEkskul->nama}' sebesar Rp " . number_format($nominal, 0, ',', '.') . " berhasil diterapkan ke {$createdCount} siswa aktif. ({$skippedCount} siswa dilewati karena sudah memiliki tagihan ini)."
        );
    }
}
