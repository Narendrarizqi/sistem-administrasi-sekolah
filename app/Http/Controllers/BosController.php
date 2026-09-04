<?php

namespace App\Http\Controllers;

use App\Models\Bos;
use App\Models\Pengeluaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class BosController extends Controller
{
    public function index(Request $request)
    {
        // 1. Daftar Pilihan Tahun Anggaran
        $activeTa = TahunAjaran::where('is_active', true)->first();
        $currentYear = date('Y');
        
        $yearsFromDb = Bos::pluck('tahun_anggaran')->toArray();
        $yearsFromPengeluaran = Pengeluaran::where('sumber_dana', 'BOS')
            ->whereNotNull('tanggal')
            ->pluck('tanggal')
            ->map(fn($t) => substr((string)$t, 0, 4))
            ->unique()
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

        // 3. Data Pengambilan Dana BOS per Tahap untuk Tahun Anggaran Terpilih
        $pengambilanTahap1 = Bos::where('tahun_anggaran', $tahunAnggaran)
            ->where('tahap', 'Tahap 1')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $pengambilanTahap2 = Bos::where('tahun_anggaran', $tahunAnggaran)
            ->where('tahap', 'Tahap 2')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $subtotalTahap1 = (float) $pengambilanTahap1->sum('nominal');
        $subtotalTahap2 = (float) $pengambilanTahap2->sum('nominal');
        $totalPengambilanBos = $subtotalTahap1 + $subtotalTahap2;
        $totalPemasukanBos = $totalPengambilanBos; // Alias untuk kompatibilitas

        // 4. Data Pengeluaran dari Dana BOS
        $pengeluaranQuery = Pengeluaran::where('sumber_dana', 'BOS');
        $this->applyTahunAnggaranFilter($pengeluaranQuery, $tahunAnggaran);

        $allPengeluaranBos = (clone $pengeluaranQuery)->orderByDesc('tanggal')->orderByDesc('id')->get();
        $totalPengeluaranBos = (float) $allPengeluaranBos->sum('nominal');
        $sisaSaldoBos = $totalPengambilanBos - $totalPengeluaranBos;

        $pengeluaranBos = $pengeluaranQuery->with('user')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('bos.index', compact(
            'daftarTahunAnggaran',
            'tahunAnggaran',
            'pengambilanTahap1',
            'pengambilanTahap2',
            'subtotalTahap1',
            'subtotalTahap2',
            'totalPengambilanBos',
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

        // Simpan setiap penarikan sebagai satu transaksi baru tersendiri
        Bos::create($validated);

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $validated['tahun_anggaran']])
            ->with('success', 'Transaksi pengambilan Dana BOS ' . $validated['tahap'] . ' sebesar Rp ' . number_format($validated['nominal'], 0, ',', '.') . ' berhasil dicatat.');
    }

    public function update(Request $request, Bos $bo)
    {
        $validated = $request->validate([
            'tahap'      => 'sometimes|required|in:Tahap 1,Tahap 2',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $bo->update($validated);

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $bo->tahun_anggaran])
            ->with('success', 'Transaksi pengambilan Dana BOS ' . $bo->tahap . ' berhasil diperbarui.');
    }

    public function destroy(Bos $bo)
    {
        $tahun = $bo->tahun_anggaran;
        $tahap = $bo->tahap;
        $nominal = $bo->nominal;
        $bo->delete();

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $tahun])
            ->with('success', 'Transaksi pengambilan Dana BOS ' . $tahap . ' sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' berhasil dihapus.');
    }

    public function storePengeluaran(Request $request)
    {
        $validated = $request->validate([
            'tanggal'        => 'required|date',
            'nominal'        => 'required|numeric|min:1',
            'keterangan'     => 'required|string|max:500',
            'tahun_anggaran' => 'nullable|string|max:20',
        ]);

        $tahunAnggaran = $validated['tahun_anggaran'] ?? null;
        if (!$tahunAnggaran || !Bos::where('tahun_anggaran', $tahunAnggaran)->exists()) {
            $date = Carbon::parse($validated['tanggal']);
            $year = $date->format('Y');
            $month = (int) $date->format('n');
            $taTahun = $month >= 7 ? $year . '/' . ((int)$year + 1) : ((int)$year - 1) . '/' . $year;
            $activeTa = TahunAjaran::where('is_active', true)->value('nama');

            if ($tahunAnggaran && Bos::where('tahun_anggaran', $tahunAnggaran)->exists()) {
                // already matched
            } elseif (Bos::where('tahun_anggaran', $taTahun)->exists()) {
                $tahunAnggaran = $taTahun;
            } elseif (Bos::where('tahun_anggaran', $year)->exists()) {
                $tahunAnggaran = $year;
            } elseif ($activeTa && Bos::where('tahun_anggaran', $activeTa)->exists()) {
                $tahunAnggaran = $activeTa;
            } else {
                $tahunAnggaran = $tahunAnggaran ?: ($taTahun ?: $year);
            }
        }

        // 1. Hitung total dana BOS yang sudah diambil dari bank di tahun anggaran ini
        $totalPengambilanBos = (float) Bos::where('tahun_anggaran', $tahunAnggaran)->sum('nominal');

        if ($totalPengambilanBos <= 0) {
            throw ValidationException::withMessages([
                'nominal' => 'Belum ada transaksi pengambilan dana BOS untuk Tahun Anggaran ' . $tahunAnggaran . '. Silakan catat pengambilan dana BOS terlebih dahulu.',
            ]);
        }

        // 2. Hitung total pengeluaran BOS yang sudah dicatat sebelumnya
        $pengeluaranQuery = Pengeluaran::where('sumber_dana', 'BOS');
        $this->applyTahunAnggaranFilter($pengeluaranQuery, $tahunAnggaran);
        $totalPengeluaranBos = (float) $pengeluaranQuery->sum('nominal');

        // 3. Hitung sisa saldo
        $sisaSaldoBos = $totalPengambilanBos - $totalPengeluaranBos;

        // 4. Validasi tidak boleh melebihi sisa dana yang tersedia di kas sekolah
        if ((float) $validated['nominal'] > $sisaSaldoBos) {
            throw ValidationException::withMessages([
                'nominal' => 'Nominal pengeluaran (Rp ' . number_format($validated['nominal'], 0, ',', '.') . ') melebihi sisa kas dana BOS yang tersedia (Rp ' . number_format($sisaSaldoBos, 0, ',', '.') . ').',
            ]);
        }

        Pengeluaran::create([
            'tanggal'     => $validated['tanggal'],
            'sumber_dana' => 'BOS',
            'nominal'     => $validated['nominal'],
            'keterangan'  => $validated['keterangan'],
            'user_id'     => auth()->id(),
        ]);

        return redirect()
            ->route('bos.index', ['tahun_anggaran' => $tahunAnggaran])
            ->with('success', 'Pengeluaran dana BOS sebesar Rp ' . number_format($validated['nominal'], 0, ',', '.') . ' berhasil dicatat.');
    }

    public function cetakPdf(Request $request)
    {
        $tahunAnggaran = $request->query('tahun_anggaran', date('Y'));

        $pengambilanTahap1 = Bos::where('tahun_anggaran', $tahunAnggaran)
            ->where('tahap', 'Tahap 1')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $pengambilanTahap2 = Bos::where('tahun_anggaran', $tahunAnggaran)
            ->where('tahap', 'Tahap 2')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $subtotalTahap1 = (float) $pengambilanTahap1->sum('nominal');
        $subtotalTahap2 = (float) $pengambilanTahap2->sum('nominal');
        $totalPengambilanBos = $subtotalTahap1 + $subtotalTahap2;

        $pengeluaranQuery = Pengeluaran::where('sumber_dana', 'BOS');
        $this->applyTahunAnggaranFilter($pengeluaranQuery, $tahunAnggaran);

        $pengeluaranBos = $pengeluaranQuery->with('user')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $totalPengeluaranBos = (float) $pengeluaranBos->sum('nominal');
        $sisaSaldoBos = $totalPengambilanBos - $totalPengeluaranBos;

        $pdf = Pdf::loadView('bos.pdf', compact(
            'tahunAnggaran',
            'pengambilanTahap1',
            'pengambilanTahap2',
            'subtotalTahap1',
            'subtotalTahap2',
            'totalPengambilanBos',
            'pengeluaranBos',
            'totalPengeluaranBos',
            'sisaSaldoBos'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Dana_BOS_' . str_replace('/', '-', $tahunAnggaran) . '.pdf');
    }

    /**
     * Memfilter query Pengeluaran berdasarkan format Tahun Anggaran (Kalender maupun Tahun Ajaran)
     */
    private function applyTahunAnggaranFilter($query, string $tahunAnggaran)
    {
        if (str_contains($tahunAnggaran, '/')) {
            $parts = explode('/', $tahunAnggaran);
            $startYear = trim($parts[0]);
            $endYear = trim($parts[1]);
            if (is_numeric($startYear) && is_numeric($endYear)) {
                return $query->whereBetween('tanggal', ["$startYear-07-01", "$endYear-06-30"]);
            }
        }

        if (is_numeric($tahunAnggaran) && strlen($tahunAnggaran) === 4) {
            return $query->whereYear('tanggal', $tahunAnggaran);
        }

        return $query;
    }
}
