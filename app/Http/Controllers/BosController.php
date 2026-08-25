<?php

namespace App\Http\Controllers;

use App\Models\Bos;
use App\Models\Pengeluaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BosController extends Controller
{
    public function index(Request $request)
    {
        // 1. Daftar Pilihan Tahun Anggaran
        $activeTa = TahunAjaran::where('is_active', true)->first();
        $currentYear = date('Y');
        
        $yearsFromDb = Bos::pluck('tahun_anggaran')->toArray();
        $yearsFromPengeluaran = Pengeluaran::where('sumber_dana', 'BOS')
            ->get()
            ->map(fn($p) => Carbon::parse($p->tanggal)->format('Y'))
            ->toArray();

        $daftarTahunAnggaran = array_values(array_unique(array_filter(array_merge(
            [$currentYear],
            $activeTa ? [explode('/', $activeTa->nama)[0], $activeTa->nama] : [],
            $yearsFromDb,
            $yearsFromPengeluaran
        ))));
        rsort($daftarTahunAnggaran);

        // 2. Tahun Anggaran Terpilih
        $tahunAnggaran = $request->query('tahun_anggaran', $daftarTahunAnggaran[0] ?? $currentYear);

        // 3. Data Pemasukan BOS untuk Tahun Anggaran Terpilih
        $tahap1 = Bos::where('tahun_anggaran', $tahunAnggaran)
            ->where('tahap', 'Tahap 1')
            ->first();

        $tahap2 = Bos::where('tahun_anggaran', $tahunAnggaran)
            ->where('tahap', 'Tahap 2')
            ->first();

        $nominalTahap1 = (float) ($tahap1?->nominal ?? 0);
        $nominalTahap2 = (float) ($tahap2?->nominal ?? 0);
        $totalPemasukanBos = $nominalTahap1 + $nominalTahap2;

        // 4. Data Pengeluaran dari Dana BOS
        // Filter tahun pengeluaran sesuai tahun anggaran jika 4 digit, atau ambil berdasarkan tanggal
        $pengeluaranQuery = Pengeluaran::where('sumber_dana', 'BOS');

        if (is_numeric($tahunAnggaran) && strlen($tahunAnggaran) === 4) {
            $pengeluaranQuery->whereYear('tanggal', $tahunAnggaran);
        }

        $pengeluaranBos = $pengeluaranQuery->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $totalPengeluaranBos = (float) $pengeluaranBos->sum('nominal');
        $sisaSaldoBos = $totalPemasukanBos - $totalPengeluaranBos;

        return view('bos.index', compact(
            'daftarTahunAnggaran',
            'tahunAnggaran',
            'tahap1',
            'tahap2',
            'nominalTahap1',
            'nominalTahap2',
            'totalPemasukanBos',
            'pengeluaranBos',
            'totalPengeluaranBos',
            'sisaSaldoBos'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_anggaran' => 'required|string|max:20',
            'tahap'          => 'required|in:Tahap 1,Tahap 2',
            'tanggal'        => 'required|date',
            'nominal'        => 'required|numeric|min:1',
            'keterangan'     => 'nullable|string|max:500',
        ]);

        $bos = Bos::updateOrCreate(
            [
                'tahun_anggaran' => $validated['tahun_anggaran'],
                'tahap'          => $validated['tahap'],
            ],
            [
                'tanggal'    => $validated['tanggal'],
                'nominal'    => $validated['nominal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]
        );

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $validated['tahun_anggaran']])
            ->with('success', 'Data pemasukan BOS ' . $validated['tahap'] . ' berhasil disimpan.');
    }

    public function update(Request $request, Bos $bo)
    {
        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $bo->update($validated);

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $bo->tahun_anggaran])
            ->with('success', 'Data pemasukan BOS ' . $bo->tahap . ' berhasil diperbarui.');
    }

    public function destroy(Bos $bo)
    {
        $tahun = $bo->tahun_anggaran;
        $tahap = $bo->tahap;
        $bo->delete();

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $tahun])
            ->with('success', 'Data pemasukan BOS ' . $tahap . ' berhasil dihapus.');
    }

    public function storePengeluaran(Request $request)
    {
        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:500',
            'tahun_anggaran' => 'nullable|string|max:20',
        ]);

        Pengeluaran::create([
            'tanggal'     => $validated['tanggal'],
            'sumber_dana' => 'BOS',
            'nominal'     => $validated['nominal'],
            'keterangan'  => $validated['keterangan'],
        ]);

        $tahunRedirect = $validated['tahun_anggaran'] ?? Carbon::parse($validated['tanggal'])->format('Y');

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $tahunRedirect])
            ->with('success', 'Data pengeluaran dari Dana BOS berhasil ditambahkan.');
    }
}
