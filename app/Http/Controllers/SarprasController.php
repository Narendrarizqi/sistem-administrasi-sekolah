<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SarprasController extends Controller
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
                $q->where('nama', 'Sarpras');
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
            ->paginate(50)
            ->withQueryString();

        return view('sarpras.index', compact(
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
        $jenis = JenisPembayaran::where('nama', 'Sarpras')->first();
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

        return view('sarpras.create', compact('siswa', 'existingSiswaIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'target'   => 'required|numeric|min:0',
        ]);

        $jenis = JenisPembayaran::where('nama', 'Sarpras')->firstOrFail();
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
                    'siswa_id' => "Siswa " . ($siswa?->nama ?? '') . " (NIS: " . ($siswa?->nis ?? '') . ") sudah memiliki data tagihan Sarpras pada tahun ajaran ini!",
                ])
                ->with('open_modal_tambah', 'sarpras')
                ->with('error', "Siswa " . ($siswa?->nama ?? '') . " (NIS: " . ($siswa?->nis ?? '') . ") sudah memiliki data tagihan Sarpras pada tahun ajaran ini!");
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

        return redirect()->route('sarpras.index')
            ->with('success', 'Tagihan Sarpras berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with([
            'siswa',
            'detailPembayaran',
            'jenisPembayaran',
            'tahunAjaran'
        ])->findOrFail($id);

        return view('sarpras.bayar', compact('pembayaran'));
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
            ->with('success', 'Pembayaran Sarpras berhasil disimpan.')
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

        return view('sarpras.edit', compact('pembayaran', 'siswa'));
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

        return redirect()->route('sarpras.index')
            ->with('success', 'Data berhasil diubah.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->detailPembayaran()->delete();
        $pembayaran->delete();

        return redirect()->route('sarpras.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}