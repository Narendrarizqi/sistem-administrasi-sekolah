<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DaftarUlangController extends Controller
{
    public function index(Request $request)
    {
        $jenis = JenisPembayaran::where('nama', 'DU')->firstOrFail();

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

        $query = Pembayaran::with([
            'siswa',
            'detailPembayaran',
            'jenisPembayaran',
            'tahunAjaran'
        ])
            ->leftJoin('siswa', 'pembayaran.siswa_id', '=', 'siswa.id')
            ->select('pembayaran.*')
            ->where('pembayaran.jenis_id', $jenis->id)
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

        return view('du.index', compact(
            'data',
            'daftarTahunAjaran',
            'selectedTa',
            'tahunAjaranId',
            'tahunAjaranNama',
            'sort',
            'direction'
        ));
    }

    public function create()
    {
        $siswa = Siswa::orderBy('nama')->get();
        $jenis = JenisPembayaran::where('nama', 'DU')->first();
        $tahunAktif = $this->getTahunAjaranAktif();
        $tahunAjaranId = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        $existingSiswaIds = Pembayaran::where('jenis_id', $jenis?->id)
            ->when($tahunAjaranId, function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where(function ($sub) use ($tahunAjaranId, $tahunAjaranNama) {
                    $sub->where('tahun_ajaran_id', $tahunAjaranId)
                        ->orWhere(function ($s2) use ($tahunAjaranNama) {
                            $s2->whereNull('tahun_ajaran_id')
                               ->where('tahun_ajaran', $tahunAjaranNama);
                        });
                });
            })
            ->pluck('siswa_id')
            ->toArray();

        return view('du.create', compact('siswa', 'existingSiswaIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'target'   => 'required|numeric|min:0',
        ]);

        $jenis = JenisPembayaran::where('nama', 'DU')->firstOrFail();
        $tahunAktif = $this->getTahunAjaranAktif();
        $tahunAjaranId = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        // Cek apakah siswa sudah memiliki record tagihan di tahun ajaran aktif (misal dari carryover saat naik kelas)
        $pembayaran = Pembayaran::where('siswa_id', $request->siswa_id)
            ->where('jenis_id', $jenis->id)
            ->where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                if ($tahunAjaranId) {
                    $q->where('tahun_ajaran_id', $tahunAjaranId)
                      ->orWhere('tahun_ajaran', $tahunAjaranNama);
                }
            })
            ->first();

        if ($pembayaran) {
            $siswa = Siswa::find($request->siswa_id);
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'siswa_id' => "Siswa " . ($siswa?->nama ?? '') . " (NIS: " . ($siswa?->nis ?? '') . ") sudah memiliki data tagihan Daftar Ulang (DU) pada tahun ajaran ini!",
                ])
                ->with('open_modal_tambah', 'du')
                ->with('error', "Siswa " . ($siswa?->nama ?? '') . " (NIS: " . ($siswa?->nis ?? '') . ") sudah memiliki data tagihan Daftar Ulang (DU) pada tahun ajaran ini!");
        }

        Pembayaran::create([
            'siswa_id'        => $request->siswa_id,
            'jenis_id'        => $jenis->id,
            'tahun_ajaran_id' => $tahunAjaranId,
            'tahun_ajaran'    => $tahunAjaranNama,
            'target'          => $request->target,
            'belum_lunas'     => 0,
            'status'          => 'Belum Lunas',
        ]);

        return redirect()
            ->route('du.index')
            ->with('success', 'Tagihan Daftar Ulang berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa',
            'detailPembayaran',
            'jenisPembayaran',
            'tahunAjaran'
        ])->findOrFail($id);

        return view('du.bayar', compact('pembayaran'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa',
            'jenisPembayaran',
            'tahunAjaran'
        ])->findOrFail($id);

        $siswa = Siswa::orderBy('nama')->get();

        return view('du.edit', compact('pembayaran', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'target' => 'required|numeric|min:0',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($request->has('siswa_id')) {
            $pembayaran->siswa_id = $request->siswa_id;
        }

        $pembayaran->target = $request->target;
        // belum_lunas (terbawa) tetap terjaga utuh!

        $terbayar = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $totalTagihan = (float) $pembayaran->target + (float) ($pembayaran->belum_lunas ?? 0);
        $pembayaran->status = ($totalTagihan > 0 && $terbayar >= $totalTagihan) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        return redirect()
            ->route('du.index')
            ->with('success', 'Data Daftar Ulang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->detailPembayaran()->delete();
        $pembayaran->delete();

        return redirect()
            ->route('du.index')
            ->with('success', 'Data Daftar Ulang berhasil dihapus.');
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'nominal'    => 'required|numeric|min:1',
            'metode'     => 'required|string',
            'keterangan' => 'nullable|string',
            'bukti'      => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:3072',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $nominal    = (float) $request->nominal;

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

        $detail = $this->prosesPembayaranPrioritas(
            $pembayaran,
            $nominal,
            $request->metode,
            $request->keterangan,
            $buktiPath
        );

        return redirect()
            ->back()
            ->with('success', 'Pembayaran Daftar Ulang berhasil disimpan.')
            ->with('last_detail_id', $detail->id);
    }

    /**
     * Terapkan tagihan Daftar Ulang secara massal ke seluruh siswa aktif.
     * DU hanya punya 1 tagihan per siswa per tahun ajaran, jadi tidak perlu pilih jenis iuran.
     */
    public function terapkanMassal(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
        ]);

        $jenis = JenisPembayaran::where('nama', 'DU')->firstOrFail();
        $tahunAktif = $request->filled('tahun_ajaran_id')
            ? TahunAjaran::find($request->tahun_ajaran_id)
            : $this->getTahunAjaranAktif();

        if (!$tahunAktif) {
            $tahunAktif = TahunAjaran::where('is_active', true)->first() ?? TahunAjaran::first();
        }

        $tahunAjaranId   = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        $nominal = (float) $request->nominal;
        $allSiswa = Siswa::all();
        $createdCount = 0;
        $skippedCount = 0;

        foreach ($allSiswa as $siswa) {
            // Cek apakah siswa sudah punya tagihan DU di tahun ajaran ini
            $pembayaran = Pembayaran::where('siswa_id', $siswa->id)
                ->where('jenis_id', $jenis->id)
                ->where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                    if ($tahunAjaranId) {
                        $q->where('tahun_ajaran_id', $tahunAjaranId)
                          ->orWhere('tahun_ajaran', $tahunAjaranNama);
                    }
                })
                ->first();

            if ($pembayaran) {
                if ($pembayaran->target > 0) {
                    $skippedCount++;
                    continue;
                }
                // Jika sudah ada record tapi target masih 0 (misal carryover), update targetnya
                $pembayaran->target = $nominal;
                $pembayaran->save();
                $createdCount++;
                continue;
            }

            Pembayaran::create([
                'siswa_id'        => $siswa->id,
                'jenis_id'        => $jenis->id,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tahun_ajaran'    => $tahunAjaranNama,
                'target'          => $nominal,
                'belum_lunas'     => 0,
                'status'          => 'Belum Lunas',
            ]);

            $createdCount++;
        }

        return redirect()->route('du.index', array_filter(['tahun_ajaran_id' => $tahunAjaranId]))->with(
            'success',
            "Tagihan Daftar Ulang sebesar Rp " . number_format($nominal, 0, ',', '.') . " berhasil diterapkan ke {$createdCount} siswa. ({$skippedCount} siswa dilewati karena sudah memiliki tagihan)."
        );
    }
}