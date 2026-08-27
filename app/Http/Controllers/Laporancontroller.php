<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\Pengeluaran;
use App\Models\Bos;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Kategori sumber dana yang dipakai di seluruh Laporan (IPP, DU, Sarpras, KI, BOS).
     * 'aliases' menampung variasi penulisan yang mungkin ada di data lama
     * (misal 'SARPAS' vs 'Sarpras') supaya tetap terhitung dalam 1 baris.
     */
    private function kategoriSumberDana(): array
    {
        return [
            'IPP'     => ['IPP'],
            'DU'      => ['DU'],
            'Sarpras' => ['Sarpras', 'SARPAS'],
            'KI'      => ['KI'],
            'BOS'     => ['BOS'],
        ];
    }

    /**
     * Hitung rincian Pemasukan, Pengeluaran, dan Saldo per sumber dana
     * (IPP/DU/Sarpras/KI/BOS). Dipakai bareng oleh halaman Laporan dan PDF
     * Rincian Saldo Akhir, supaya angkanya selalu konsisten.
     */
    private function hitungRincianSumberDana()
    {
        $kategori = $this->kategoriSumberDana();

        // Pemasukan aktual per jenis pembayaran (direct SQL aggregate)
        $pemasukanPerJenis = \Illuminate\Support\Facades\DB::table('detail_pembayaran')
            ->join('pembayaran', 'detail_pembayaran.pembayaran_id', '=', 'pembayaran.id')
            ->join('jenis_pembayaran', 'pembayaran.jenis_id', '=', 'jenis_pembayaran.id')
            ->groupBy('jenis_pembayaran.nama')
            ->pluck(\Illuminate\Support\Facades\DB::raw('SUM(detail_pembayaran.nominal) as total'), 'jenis_pembayaran.nama')
            ->map(fn($v) => (float) $v);

        // Pemasukan BOS (dari tabel bos)
        $bosPemasukan = (float) Bos::sum('nominal');

        // Pengeluaran aktual per sumber dana (direct SQL aggregate)
        $pengeluaranPerSumber = \Illuminate\Support\Facades\DB::table('pengeluaran')
            ->whereNotNull('sumber_dana')
            ->groupBy('sumber_dana')
            ->pluck(\Illuminate\Support\Facades\DB::raw('SUM(nominal) as total'), 'sumber_dana')
            ->map(fn($v) => (float) $v);

        $rincian = collect();

        foreach ($kategori as $label => $aliases) {
            $pemasukan = 0;
            $pengeluaran = 0;

            if ($label === 'BOS') {
                $pemasukan = $bosPemasukan;
            } else {
                foreach ($aliases as $alias) {
                    $pemasukan += $pemasukanPerJenis[$alias] ?? 0;
                }
            }

            foreach ($aliases as $alias) {
                $pengeluaran += $pengeluaranPerSumber[$alias] ?? 0;
            }

            $rincian->push([
                'jenis' => $label,
                'pemasukan' => (float) $pemasukan,
                'pengeluaran' => (float) $pengeluaran,
                'saldo' => (float) ($pemasukan - $pengeluaran),
            ]);
        }

        return $rincian;
    }

    public function index()
    {
        $transaksi = collect();

        // Pemasukan dari IPP / Daftar Ulang / Kegiatan Intrakurikuler
        DetailPembayaran::with([
            'pembayaran.siswa',
            'pembayaran.jenisPembayaran'
        ])
            ->get()
            ->each(function ($detail) use ($transaksi) {

                $siswa = $detail->pembayaran->siswa ?? null;

                $jenis = $detail->pembayaran->jenisPembayaran->nama
                    ?? 'Pembayaran';

                $transaksi->push([
                    'tanggal' => $detail->tanggal,
                    'created_at' => $detail->created_at,

                    'uraian' => 'Diterima pembayaran '
                        . $jenis
                        . ' - '
                        . ($siswa->nama ?? '-')
                        . ' ('
                        . ($siswa->kelas ?? '-')
                        . ')',

                    'masuk' => (float) $detail->nominal,
                    'keluar' => 0,
                ]);
            });

        // Pemasukan dari Bantuan Operasional Sekolah (BOS)
        Bos::all()->each(function ($item) use ($transaksi) {
            $transaksi->push([
                'tanggal' => $item->tanggal,
                'created_at' => $item->created_at,

                'uraian' => 'Penerimaan Dana BOS '
                    . $item->tahap
                    . ' (' . ($item->tahun_anggaran ?? '-') . ')'
                    . ($item->keterangan ? ' - ' . $item->keterangan : ''),

                'masuk' => (float) $item->nominal,
                'keluar' => 0,
            ]);
        });

        // Pengeluaran
        Pengeluaran::all()->each(function ($item) use ($transaksi) {

            $transaksi->push([
                'tanggal' => $item->tanggal,
                'created_at' => $item->created_at,

                'uraian' => $item->keterangan . ($item->sumber_dana ? ' [' . $item->sumber_dana . ']' : ''),

                'masuk' => 0,
                'keluar' => (float) $item->nominal,
            ]);
        });

        // Urutkan berdasarkan tanggal dan waktu input
        $transaksi = $transaksi
            ->sortBy([
                fn ($a, $b) =>
                    strcmp(
                        (string) $a['tanggal'],
                        (string) $b['tanggal']
                    ),

                fn ($a, $b) =>
                    $a['created_at'] <=> $b['created_at'],
            ])
            ->values();

        // Hitung saldo berjalan
        $saldo = 0;

        $laporan = $transaksi->map(function ($row) use (&$saldo) {

            $saldo += $row['masuk'] - $row['keluar'];

            $row['saldo'] = $saldo;

            return $row;
        });

        $totalMasuk = $transaksi->sum('masuk');

        $totalKeluar = $transaksi->sum('keluar');

        $rincianSumberDana = $this->hitungRincianSumberDana();

        $totalRincianPemasukan = $rincianSumberDana->sum('pemasukan');
        $totalRincianPengeluaran = $rincianSumberDana->sum('pengeluaran');
        $totalRincianSaldo = $rincianSumberDana->sum('saldo');

        return view('laporan.index', [
            'laporan' => $laporan,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoAkhir' => $saldo,
            'rincianSumberDana' => $rincianSumberDana,
            'totalRincianPemasukan' => $totalRincianPemasukan,
            'totalRincianPengeluaran' => $totalRincianPengeluaran,
            'totalRincianSaldo' => $totalRincianSaldo,
        ]);
    }


    /**
     * Cetak laporan Buku Kas Umum ke PDF
     */
    public function cetakPdf()
    {
        $transaksi = collect();

        // ==========================================
        // PEMASUKAN
        // ==========================================

        DetailPembayaran::with([
            'pembayaran.siswa',
            'pembayaran.jenisPembayaran'
        ])
            ->get()
            ->each(function ($detail) use ($transaksi) {

                $siswa = $detail->pembayaran->siswa ?? null;

                $jenis = $detail->pembayaran->jenisPembayaran->nama
                    ?? 'Pembayaran';

                $transaksi->push([
                    'tanggal' => $detail->tanggal,
                    'created_at' => $detail->created_at,

                    'uraian' => 'Diterima pembayaran '
                        . $jenis
                        . ' - '
                        . ($siswa->nama ?? '-')
                        . ' ('
                        . ($siswa->kelas ?? '-')
                        . ')',

                    'masuk' => (float) $detail->nominal,
                    'keluar' => 0,
                ]);
            });

        // Pemasukan dari Bantuan Operasional Sekolah (BOS)
        Bos::all()->each(function ($item) use ($transaksi) {
            $transaksi->push([
                'tanggal' => $item->tanggal,
                'created_at' => $item->created_at,

                'uraian' => 'Penerimaan Dana BOS '
                    . $item->tahap
                    . ' (' . ($item->tahun_anggaran ?? '-') . ')'
                    . ($item->keterangan ? ' - ' . $item->keterangan : ''),

                'masuk' => (float) $item->nominal,
                'keluar' => 0,
            ]);
        });

        // ==========================================
        // PENGELUARAN
        // ==========================================

        Pengeluaran::all()->each(function ($item) use ($transaksi) {

            $transaksi->push([
                'tanggal' => $item->tanggal,
                'created_at' => $item->created_at,

                'uraian' => $item->keterangan . ($item->sumber_dana ? ' [' . $item->sumber_dana . ']' : ''),

                'masuk' => 0,
                'keluar' => (float) $item->nominal,
            ]);
        });


        // ==========================================
        // URUTKAN TRANSAKSI
        // ==========================================

        $transaksi = $transaksi
            ->sortBy([
                fn ($a, $b) =>
                    strcmp(
                        (string) $a['tanggal'],
                        (string) $b['tanggal']
                    ),

                fn ($a, $b) =>
                    $a['created_at'] <=> $b['created_at'],
            ])
            ->values();


        // ==========================================
        // HITUNG SALDO BERJALAN
        // ==========================================

        $saldo = 0;

        $laporan = $transaksi->map(function ($row) use (&$saldo) {

            $saldo += $row['masuk'] - $row['keluar'];

            $row['saldo'] = $saldo;

            return $row;
        });


        // ==========================================
        // TOTAL
        // ==========================================

        $totalMasuk = $transaksi->sum('masuk');

        $totalKeluar = $transaksi->sum('keluar');

        $saldoAkhir = $saldo;


        // ==========================================
        // BUAT PDF
        // ==========================================

        $pdf = Pdf::loadView('laporan.pdf', [
            'laporan' => $laporan,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoAkhir' => $saldoAkhir,
        ]);


        // Ukuran kertas
        $pdf->setPaper('A4', 'landscape');


        // Tampilkan sebagai PDF di browser
        return $pdf->stream('laporan-buku-kas-umum.pdf');
    }

    /**
     * Cetak PDF Rincian Saldo Akhir (per sumber dana: IPP/DU/Sarpras/KI).
     * Ini tabel yang sama seperti dropdown "Rincian Saldo Akhir" di halaman
     * Laporan — dibuat method & PDF terpisah, tidak menyentuh cetakPdf()
     * (Buku Kas Umum) yang sudah ada.
     */
    public function cetakRincianSaldo()
    {
        $rincianSumberDana = $this->hitungRincianSumberDana();

        $totalRincianPemasukan = $rincianSumberDana->sum('pemasukan');
        $totalRincianPengeluaran = $rincianSumberDana->sum('pengeluaran');
        $totalRincianSaldo = $rincianSumberDana->sum('saldo');

        $pdf = Pdf::loadView('laporan.pdf-rincian-saldo', [
            'rincianSumberDana' => $rincianSumberDana,
            'totalRincianPemasukan' => $totalRincianPemasukan,
            'totalRincianPengeluaran' => $totalRincianPengeluaran,
            'totalRincianSaldo' => $totalRincianSaldo,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Rincian-Saldo-Akhir.pdf');
    }
}