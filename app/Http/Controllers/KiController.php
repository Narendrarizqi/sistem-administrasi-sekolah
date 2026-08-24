<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
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

        $data = Pembayaran::with(['siswa', 'detailPembayaran', 'tahunAjaran', 'jenisPembayaran'])
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
            ->get();

        return view('ki.index', compact(
            'data',
            'daftarTahunAjaran',
            'selectedTa',
            'tahunAjaranId',
            'tahunAjaranNama'
        ));
    }

    public function create()
    {
        $siswa = Siswa::orderBy('nama')->get();
        $jenis = JenisPembayaran::where('nama', 'KI')->first();
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

        return view('ki.create', compact('siswa', 'existingSiswaIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'     => 'required|exists:siswa,id',
            'target_uts'   => 'nullable|numeric|min:0',
            'target_uas'   => 'nullable|numeric|min:0',
            'target_ujian' => 'nullable|numeric|min:0',
            'target'       => 'nullable|numeric|min:0',
        ]);

        $jenis = JenisPembayaran::where('nama', 'KI')->firstOrFail();
        $tahunAktif = $this->getTahunAjaranAktif();
        $tahunAjaranId = $tahunAktif?->id;
        $tahunAjaranNama = $tahunAktif?->nama;

        // Cek apakah siswa sudah memiliki record tagihan di tahun ajaran aktif
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
                    'siswa_id' => "Siswa " . ($siswa?->nama ?? '') . " (NIS: " . ($siswa?->nis ?? '') . ") sudah memiliki data tagihan Kegiatan Intrakurikuler (KI) pada tahun ajaran ini!",
                ])
                ->with('open_modal_tambah', 'ki')
                ->with('error', "Siswa " . ($siswa?->nama ?? '') . " (NIS: " . ($siswa?->nis ?? '') . ") sudah memiliki data tagihan Kegiatan Intrakurikuler (KI) pada tahun ajaran ini!");
        }

        $targetUts   = (float) ($request->target_uts ?? 0);
        $targetUas   = (float) ($request->target_uas ?? 0);
        $targetUjian = (float) ($request->target_ujian ?? 0);
        $totalTarget = $targetUts + $targetUas + $targetUjian;

        // Fallback jika hanya input target global
        if ($totalTarget <= 0 && $request->filled('target')) {
            $totalTarget = (float) $request->target;
        }

        Pembayaran::create([
            'siswa_id'        => $request->siswa_id,
            'jenis_id'        => $jenis->id,
            'tahun_ajaran_id' => $tahunAjaranId,
            'tahun_ajaran'    => $tahunAjaranNama,
            'target'          => $totalTarget,
            'target_uts'      => $targetUts,
            'target_uas'      => $targetUas,
            'target_ujian'    => $targetUjian,
            'belum_lunas'     => 0,
            'status'          => 'Belum Lunas',
        ]);

        return redirect()->route('ki.index')
            ->with('success', 'Tagihan Kegiatan Intrakurikuler berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa',
            'detailPembayaran',
            'jenisPembayaran',
            'tahunAjaran'
        ])->findOrFail($id);

        return view('ki.bayar', compact('pembayaran'));
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'kategori'   => 'required|in:UTS,UAS,Ujian',
            'nominal'    => 'required|numeric|min:1',
            'metode'     => 'required|string',
            'keterangan' => 'nullable|string',
            'bukti'      => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:3072',
        ]);

        $pembayaran = Pembayaran::with('detailPembayaran')->findOrFail($id);
        $kategori   = $request->kategori;
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
            ->with('success', "Pembayaran KI ({$kategori}) berhasil disimpan.")
            ->with('last_detail_id', $detail->id);
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa',
            'jenisPembayaran',
            'tahunAjaran'
        ])->findOrFail($id);

        $siswa = Siswa::orderBy('nama')->get();

        return view('ki.edit', compact('pembayaran', 'siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'target_uts'   => 'nullable|numeric|min:0',
            'target_uas'   => 'nullable|numeric|min:0',
            'target_ujian' => 'nullable|numeric|min:0',
            'target'       => 'nullable|numeric|min:0',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($request->has('siswa_id')) {
            $pembayaran->siswa_id = $request->siswa_id;
        }

        $targetUts   = (float) ($request->target_uts ?? 0);
        $targetUas   = (float) ($request->target_uas ?? 0);
        $targetUjian = (float) ($request->target_ujian ?? 0);
        $totalTarget = $targetUts + $targetUas + $targetUjian;

        if ($totalTarget <= 0 && $request->filled('target')) {
            $totalTarget = (float) $request->target;
        }

        $pembayaran->target_uts   = $targetUts;
        $pembayaran->target_uas   = $targetUas;
        $pembayaran->target_ujian = $targetUjian;
        $pembayaran->target       = $totalTarget;
        // belum_lunas tetap terjaga utuh

        $terbayarTotal = (float) $pembayaran->detailPembayaran()->sum('nominal');
        $totalTagihan  = (float) $pembayaran->target + (float) ($pembayaran->belum_lunas ?? 0);
        $pembayaran->status = ($totalTagihan > 0 && $terbayarTotal >= $totalTagihan) ? 'Lunas' : 'Belum Lunas';
        $pembayaran->save();

        return redirect()->route('ki.index')
            ->with('success', 'Data tagihan KI berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->detailPembayaran()->delete();
        $pembayaran->delete();

        return redirect()->route('ki.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}