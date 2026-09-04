<?php

namespace App\Http\Controllers;

use App\Models\Bos;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $allPengeluaran = Pengeluaran::orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $totalPengeluaran = (float) $allPengeluaran->sum('nominal');

        // 1. Pengeluaran Bulan Ini
        $currentYm = now()->format('Y-m');
        $pengeluaranBulanIni = $allPengeluaran->filter(function ($item) use ($currentYm) {
            return \Carbon\Carbon::parse($item->tanggal)->format('Y-m') === $currentYm;
        });
        $totalBulanIni = (float) $pengeluaranBulanIni->sum('nominal');
        $jumlahBulanIni = $pengeluaranBulanIni->count();
        $namaBulanIni = now()->translatedFormat('F Y');

        // 2. Jumlah Transaksi
        $jumlahTransaksi = $allPengeluaran->count();

        // 3. Pengeluaran Terbesar
        $pengeluaranTerbesar = $allPengeluaran->sortByDesc('nominal')->first();
        $nominalTerbesar = (float) ($pengeluaranTerbesar?->nominal ?? 0);
        $tanggalTerbesar = $pengeluaranTerbesar ? \Carbon\Carbon::parse($pengeluaranTerbesar->tanggal)->translatedFormat('d M Y') : '-';
        $keteranganTerbesar = $pengeluaranTerbesar?->keterangan ?? '-';

        // 4. Ringkasan Pengeluaran Berdasarkan Sumber Dana
        $sumberList = [
            'IPP'             => 'IPP',
            'DU'              => 'Daftar Ulang',
            'Sarpras'         => 'Sarana & Prasarana',
            'KI'              => 'Asesmen',
            'Ekstrakurikuler' => 'Ekstrakurikuler',
            'Kokurikuler'     => 'Kokurikuler',
            'BOS'             => 'Dana BOS',
        ];

        $ringkasanSumber = [];
        foreach ($sumberList as $key => $label) {
            $nominal = (float) $allPengeluaran->where('sumber_dana', $key)->sum('nominal');
            $persen = $totalPengeluaran > 0 ? round(($nominal / $totalPengeluaran) * 100, 1) : 0;
            $ringkasanSumber[$key] = [
                'label'   => $label,
                'nominal' => $nominal,
                'persen'  => $persen,
            ];
        }

        // Kategori Terbesar Berdasarkan Sumber Dana
        $kategoriTerbesarItem = collect($ringkasanSumber)->sortByDesc('nominal')->first();
        $namaKategoriTerbesar = ($kategoriTerbesarItem && $kategoriTerbesarItem['nominal'] > 0) ? $kategoriTerbesarItem['label'] : '-';
        $nominalKategoriTerbesar = (float) ($kategoriTerbesarItem['nominal'] ?? 0);
        $persenKategoriTerbesar = (float) ($kategoriTerbesarItem['persen'] ?? 0);

        // 5. Tren 6 Bulan Terakhir
        $tren6Bulan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->copy()->subMonths($i);
            $ym = $date->format('Y-m');
            $label = $date->translatedFormat('M');
            $nominal = (float) $allPengeluaran->filter(fn($p) => \Carbon\Carbon::parse($p->tanggal)->format('Y-m') === $ym)->sum('nominal');
            $tren6Bulan[] = [
                'bulan'   => $label,
                'ym'      => $ym,
                'nominal' => $nominal,
            ];
        }

        // Tren 12 Bulan Terakhir
        $tren12Bulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->copy()->subMonths($i);
            $ym = $date->format('Y-m');
            $label = $date->translatedFormat('M y');
            $nominal = (float) $allPengeluaran->filter(fn($p) => \Carbon\Carbon::parse($p->tanggal)->format('Y-m') === $ym)->sum('nominal');
            $tren12Bulan[] = [
                'bulan'   => $label,
                'ym'      => $ym,
                'nominal' => $nominal,
            ];
        }

        // Daftar Tahun untuk filter
        $daftarTahun = $allPengeluaran->map(fn($p) => \Carbon\Carbon::parse($p->tanggal)->format('Y'))->unique()->sortDesc()->values()->toArray();
        if (empty($daftarTahun)) {
            $daftarTahun = [date('Y')];
        }

        // Data Tabel Paginated (25 data per halaman)
        $pengeluaran = Pengeluaran::with('user')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('pengeluaran.index', compact(
            'pengeluaran',
            'totalPengeluaran',
            'totalBulanIni',
            'jumlahBulanIni',
            'namaBulanIni',
            'jumlahTransaksi',
            'nominalTerbesar',
            'tanggalTerbesar',
            'keteranganTerbesar',
            'namaKategoriTerbesar',
            'nominalKategoriTerbesar',
            'persenKategoriTerbesar',
            'ringkasanSumber',
            'tren6Bulan',
            'tren12Bulan',
            'daftarTahun'
        ));
    }

    public function create()
    {
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'sumber_dana' => 'required|in:IPP,DU,Sarpras,KI,BOS,Ekstrakurikuler,Kokurikuler',
            'keterangan'  => 'required|string|max:500',
            'nominal'     => 'required|numeric|min:1',
        ]);

        if ($validated['sumber_dana'] === 'BOS') {
            $bosBudget = $this->getBosBudgetForDate($validated['tanggal']);

            if ($bosBudget['total_pengambilan_bos'] <= 0) {
                throw ValidationException::withMessages([
                    'nominal' => 'Belum ada pengambilan dana BOS untuk Tahun Anggaran ' . $bosBudget['tahun_anggaran'] . '. Silakan catat pengambilan dana BOS terlebih dahulu.',
                ]);
            }

            if ((float) $validated['nominal'] > $bosBudget['sisa_saldo_bos']) {
                throw ValidationException::withMessages([
                    'nominal' => 'Nominal pengeluaran (Rp ' . number_format($validated['nominal'], 0, ',', '.') . ') melebihi sisa kas dana BOS yang tersedia (Rp ' . number_format($bosBudget['sisa_saldo_bos'], 0, ',', '.') . ').',
                ]);
            }
        }

        $validated['user_id'] = auth()->id();
        Pengeluaran::create($validated);

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'tanggal'     => 'required|date',
            'sumber_dana' => 'required|in:IPP,DU,Sarpras,KI,BOS,Ekstrakurikuler,Kokurikuler',
            'keterangan'  => 'required|string|max:500',
            'nominal'     => 'required|numeric|min:1',
        ]);

        $targetTahunAnggaran = null;
        if ($validated['sumber_dana'] === 'BOS') {
            $bosBudget = $this->getBosBudgetForDate($validated['tanggal'], $pengeluaran->id);
            $targetTahunAnggaran = $bosBudget['tahun_anggaran'];

            if ($bosBudget['total_pengambilan_bos'] <= 0) {
                throw ValidationException::withMessages([
                    'nominal' => 'Belum ada pengambilan dana BOS untuk Tahun Anggaran ' . $bosBudget['tahun_anggaran'] . '.',
                ]);
            }

            if ((float) $validated['nominal'] > $bosBudget['sisa_saldo_bos']) {
                throw ValidationException::withMessages([
                    'nominal' => 'Nominal pengeluaran (Rp ' . number_format($validated['nominal'], 0, ',', '.') . ') melebihi sisa dana BOS yang tersedia (Rp ' . number_format($bosBudget['sisa_saldo_bos'], 0, ',', '.') . ').',
                ]);
            }
        }

        $validated['user_id'] = auth()->id();
        $pengeluaran->update($validated);

        if ($pengeluaran->sumber_dana === 'BOS' && str_contains(url()->previous(), '/bos')) {
            $redirectYear = $targetTahunAnggaran ?: Carbon::parse($validated['tanggal'])->format('Y');
            return redirect()->route('bos.index', ['tahun_anggaran' => $redirectYear])
                ->with('success', 'Data pengeluaran Dana BOS berhasil diperbarui.');
        }

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $isBos = $pengeluaran->sumber_dana === 'BOS';
        $bosBudget = $isBos ? $this->getBosBudgetForDate($pengeluaran->tanggal) : null;
        $pengeluaran->delete();

        if ($isBos && str_contains(url()->previous(), '/bos')) {
            $redirectYear = $bosBudget ? $bosBudget['tahun_anggaran'] : Carbon::parse($pengeluaran->tanggal)->format('Y');
            return redirect()->route('bos.index', ['tahun_anggaran' => $redirectYear])
                ->with('success', 'Data pengeluaran Dana BOS berhasil dihapus.');
        }

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    /**
     * Resolusi budget BOS dan sisa saldo yang fleksibel mendukung Tahun Kalender (2026) maupun Tahun Ajaran (2026/2027)
     */
    private function getBosBudgetForDate(string $tanggal, ?int $excludePengeluaranId = null): array
    {
        $date = Carbon::parse($tanggal);
        $year = $date->format('Y');
        $month = (int) $date->format('n');
        $taTahun = $month >= 7 
            ? $year . '/' . ((int)$year + 1) 
            : ((int)$year - 1) . '/' . $year;
        
        $activeTa = \App\Models\TahunAjaran::where('is_active', true)->value('nama');

        // Prioritas pencarian Tahun Anggaran BOS:
        // 1. Tahun Ajaran berdasarkan tanggal (misal: 2026/2027)
        // 2. Tahun Kalender berdasarkan tanggal (misal: 2026)
        // 3. Tahun Ajaran Aktif di master data sekolah
        // 4. Record BOS apapun yang relevan dengan tahun ini
        $targetTahunAnggaran = null;
        if (Bos::where('tahun_anggaran', $taTahun)->exists()) {
            $targetTahunAnggaran = $taTahun;
        } elseif (Bos::where('tahun_anggaran', $year)->exists()) {
            $targetTahunAnggaran = $year;
        } elseif ($activeTa && Bos::where('tahun_anggaran', $activeTa)->exists()) {
            $targetTahunAnggaran = $activeTa;
        } else {
            $matchingBos = Bos::where('tahun_anggaran', 'LIKE', $year . '/%')
                ->orWhere('tahun_anggaran', 'LIKE', '%/' . $year)
                ->first();
            if ($matchingBos) {
                $targetTahunAnggaran = $matchingBos->tahun_anggaran;
            } else {
                $targetTahunAnggaran = $taTahun ?: $year;
            }
        }

        // Ambil data pengambilan BOS khusus untuk target tahun anggaran ini
        $bosRecords = Bos::where('tahun_anggaran', $targetTahunAnggaran)->get();
        $totalPengambilanBos = (float) $bosRecords->sum('nominal');

        // Filter pengeluaran yang sesuai periode tahun anggaran
        $pengeluaranQuery = Pengeluaran::where('sumber_dana', 'BOS');
        if ($excludePengeluaranId) {
            $pengeluaranQuery->where('id', '!=', $excludePengeluaranId);
        }

        if (str_contains($targetTahunAnggaran, '/')) {
            $parts = explode('/', $targetTahunAnggaran);
            $startYear = trim($parts[0]);
            $endYear = trim($parts[1]);
            if (is_numeric($startYear) && is_numeric($endYear)) {
                $pengeluaranQuery->whereBetween('tanggal', ["$startYear-07-01", "$endYear-06-30"]);
            }
        } elseif (is_numeric($targetTahunAnggaran) && strlen($targetTahunAnggaran) === 4) {
            $pengeluaranQuery->whereYear('tanggal', $targetTahunAnggaran);
        }

        $totalPengeluaranBos = (float) $pengeluaranQuery->sum('nominal');
        $sisaSaldoBos = $totalPengambilanBos - $totalPengeluaranBos;

        return [
            'tahun_anggaran'        => $targetTahunAnggaran,
            'total_pengambilan_bos' => $totalPengambilanBos,
            'total_pengeluaran_bos' => $totalPengeluaranBos,
            'sisa_saldo_bos'        => $sisaSaldoBos,
        ];
    }
}