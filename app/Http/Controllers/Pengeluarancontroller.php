<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

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
            'IPP'     => 'IPP',
            'DU'      => 'Daftar Ulang',
            'Sarpras' => 'Sarana & Prasarana',
            'KI'      => 'Kegiatan Intrakurikuler',
            'BOS'     => 'Dana BOS',
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
        $pengeluaran = Pengeluaran::orderByDesc('tanggal')
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
            'sumber_dana' => 'required|in:IPP,DU,Sarpras,KI,BOS',
            'keterangan'  => 'required|string|max:500',
            'nominal'     => 'required|numeric|min:1',
        ]);

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
            'sumber_dana' => 'required|in:IPP,DU,Sarpras,KI,BOS',
            'keterangan'  => 'required|string|max:500',
            'nominal'     => 'required|numeric|min:1',
        ]);

        $pengeluaran->update($validated);

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}