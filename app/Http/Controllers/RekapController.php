<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\DetailPembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Daftar tahun ajaran untuk dropdown filter
        $daftarTahunAjaran = TahunAjaran::orderByDesc('nama')->get();

        // Tentukan tahun ajaran terpilih
        $selectedId = $request->query('tahun_ajaran_id');

        if ($selectedId) {
            $selectedTa = $daftarTahunAjaran->firstWhere('id', $selectedId);
        } else {
            $selectedTa = $daftarTahunAjaran->firstWhere('is_active', true)
                ?? $daftarTahunAjaran->first();
        }

        $tahunAjaranId = $selectedTa?->id;
        $tahunAjaranNama = $selectedTa?->nama;

        // Query pembayaran sesuai filter tahun ajaran
        $queryPembayaran = Pembayaran::with([
            'siswa',
            'jenisPembayaran',
            'detailPembayaran',
            'tahunAjaran',
        ]);

        if ($tahunAjaranId) {
            $queryPembayaran->where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                  ->orWhere(function ($s2) use ($tahunAjaranNama) {
                      $s2->whereNull('tahun_ajaran_id')
                         ->where('tahun_ajaran', $tahunAjaranNama);
                  });
            });
        }

        $pembayaran = $queryPembayaran
            ->orderBy('siswa_id')
            ->orderBy('jenis_id')
            ->orderBy('id')
            ->get();

        $jenisPembayaran = JenisPembayaran::orderBy('nama')
            ->pluck('nama')
            ->toArray();

        $currentMonthName  = \Carbon\Carbon::now()->translatedFormat('F Y');
        $currentMonthShort = \Carbon\Carbon::now()->translatedFormat('M Y');
        $currentMonthKey   = \Carbon\Carbon::now()->format('Y-m');

        $students = $pembayaran
            ->groupBy(fn ($item) => $item->siswa_id)
            ->map(function ($items) use ($jenisPembayaran, $currentMonthName, $currentMonthShort, $currentMonthKey) {
                $siswa   = $items->first()->siswa;
                $jenisData = [];

                $overall = [
                    'target'        => 0,
                    'terbawa'       => 0,
                    'total_tagihan' => 0,
                    'terbayar'      => 0,
                    'sisa'          => 0,
                    'status'        => 'Belum Ada Tagihan',
                    'bulan_bayar'   => [],
                    'notif_status'  => 'none',
                    'notif_text'    => '-',
                    'notif_sub'     => '',
                ];

                foreach ($jenisPembayaran as $jenis) {
                    $matching = $items->filter(
                        fn ($item) => $item->jenisPembayaran
                            && $item->jenisPembayaran->nama === $jenis
                    );

                    if ($matching->isEmpty()) {
                        $jenisData[$jenis] = [
                            'target'        => 0,
                            'terbawa'       => 0,
                            'total_tagihan' => 0,
                            'terbayar'      => 0,
                            'sisa'          => 0,
                            'status'        => 'Belum Ada Tagihan',
                            'bulan_bayar'   => [],
                            'notif_status'  => 'none',
                            'notif_text'    => '-',
                            'notif_sub'     => '',
                        ];

                        continue;
                    }

                    $target = (float) $matching->sum(fn ($item) => (float) $item->target);
                    $terbawa = (float) $matching->sum(fn ($item) => (float) ($item->belum_lunas ?? 0));
                    $totalTagihan = $target + $terbawa;
                    $terbayar = (float) $matching->sum(fn ($item) => (float) $item->detailPembayaran->sum('nominal'));
                    $sisa = max($totalTagihan - $terbayar, 0);

                    $bulanBayar = $matching->flatMap(fn ($item) => $item->detailPembayaran)
                        ->filter(fn ($d) => !empty($d->tanggal) && $d->nominal > 0)
                        ->map(fn ($d) => \Carbon\Carbon::parse($d->tanggal)->translatedFormat('F Y'))
                        ->unique()
                        ->values()
                        ->toArray();

                    $hasPaidThisMonth = $matching->flatMap(fn ($item) => $item->detailPembayaran)
                        ->contains(fn ($d) => !empty($d->tanggal) && $d->nominal > 0 && \Carbon\Carbon::parse($d->tanggal)->format('Y-m') === $currentMonthKey);

                    $lastPaidDetail = $matching->flatMap(fn ($item) => $item->detailPembayaran)
                        ->filter(fn ($d) => !empty($d->tanggal) && $d->nominal > 0)
                        ->sortByDesc('tanggal')
                        ->first();
                    $lastPaidMonthText = $lastPaidDetail ? \Carbon\Carbon::parse($lastPaidDetail->tanggal)->translatedFormat('M Y') : null;

                    $status = ($sisa <= 0 && $totalTagihan > 0)
                        ? 'Lunas'
                        : ($totalTagihan > 0 ? 'Belum Lunas' : 'Belum Ada Tagihan');

                    if ($totalTagihan <= 0) {
                        $notifStatus = 'none';
                        $notifText = '-';
                        $notifSub = '';
                    } elseif ($sisa <= 0) {
                        $notifStatus = 'lunas';
                        $notifText = 'Lunas Penuh';
                        $notifSub = '';
                    } elseif ($hasPaidThisMonth) {
                        $notifStatus = 'sudah';
                        $notifText = 'Sudah Bayar (' . \Carbon\Carbon::now()->translatedFormat('M') . ')';
                        $notifSub = $lastPaidMonthText ? 'Bulan ' . $lastPaidMonthText : '';
                    } else {
                        $notifStatus = 'belum';
                        $notifText = 'Belum Bayar (' . \Carbon\Carbon::now()->translatedFormat('M') . ')';
                        $notifSub = $lastPaidMonthText ? 'Terakhir: ' . $lastPaidMonthText : 'Belum Ada Pembayaran';
                    }

                    $jenisData[$jenis] = [
                        'target'        => $target,
                        'terbawa'       => $terbawa,
                        'total_tagihan' => $totalTagihan,
                        'terbayar'      => $terbayar,
                        'sisa'          => $sisa,
                        'status'        => $status,
                        'bulan_bayar'   => $bulanBayar,
                        'notif_status'  => $notifStatus,
                        'notif_text'    => $notifText,
                        'notif_sub'     => $notifSub,
                    ];

                    $overall['target']        += $target;
                    $overall['terbawa']       += $terbawa;
                    $overall['total_tagihan'] += $totalTagihan;
                    $overall['terbayar']      += $terbayar;
                    $overall['sisa']          += $sisa;
                }

                $overallBulan = $items->flatMap(fn ($item) => $item->detailPembayaran)
                    ->filter(fn ($d) => !empty($d->tanggal) && $d->nominal > 0)
                    ->map(fn ($d) => \Carbon\Carbon::parse($d->tanggal)->translatedFormat('F Y'))
                    ->unique()
                    ->values()
                    ->toArray();

                $overallHasPaidThisMonth = $items->flatMap(fn ($item) => $item->detailPembayaran)
                    ->contains(fn ($d) => !empty($d->tanggal) && $d->nominal > 0 && \Carbon\Carbon::parse($d->tanggal)->format('Y-m') === $currentMonthKey);

                $overallLastPaidDetail = $items->flatMap(fn ($item) => $item->detailPembayaran)
                    ->filter(fn ($d) => !empty($d->tanggal) && $d->nominal > 0)
                    ->sortByDesc('tanggal')
                    ->first();
                $overallLastPaidMonthText = $overallLastPaidDetail ? \Carbon\Carbon::parse($overallLastPaidDetail->tanggal)->translatedFormat('M Y') : null;

                $overall['bulan_bayar'] = $overallBulan;

                if ($overall['total_tagihan'] > 0) {
                    $overall['status'] = $overall['sisa'] <= 0
                        ? 'Lunas'
                        : 'Belum Lunas';
                }

                if ($overall['total_tagihan'] <= 0) {
                    $overall['notif_status'] = 'none';
                    $overall['notif_text'] = '-';
                    $overall['notif_sub'] = '';
                } elseif ($overall['sisa'] <= 0) {
                    $overall['notif_status'] = 'lunas';
                    $overall['notif_text'] = 'Lunas Penuh';
                    $overall['notif_sub'] = '';
                } elseif ($overallHasPaidThisMonth) {
                    $overall['notif_status'] = 'sudah';
                    $overall['notif_text'] = 'Sudah Bayar (' . \Carbon\Carbon::now()->translatedFormat('M') . ')';
                    $overall['notif_sub'] = $overallLastPaidMonthText ? 'Bulan ' . $overallLastPaidMonthText : '';
                } else {
                    $overall['notif_status'] = 'belum';
                    $overall['notif_text'] = 'Belum Bayar (' . \Carbon\Carbon::now()->translatedFormat('M') . ')';
                    $overall['notif_sub'] = $overallLastPaidMonthText ? 'Terakhir: ' . $overallLastPaidMonthText : 'Belum Ada Pembayaran';
                }

                $jenisData['Total'] = $overall;

                return [
                    'siswa' => $siswa,
                    'jenis' => $jenisData,
                    'pembayarans' => $items,
                ];
            })
            ->values();

        return view('rekap.index', compact(
            'students',
            'jenisPembayaran',
            'daftarTahunAjaran',
            'selectedTa',
            'tahunAjaranId',
            'tahunAjaranNama'
        ));
    }

    /**
     * Cetak laporan pembayaran berdasarkan rentang tanggal.
     */
    public function cetakPdf(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;

        $transaksi = DetailPembayaran::with([
            'pembayaran.siswa',
            'pembayaran.jenisPembayaran',
        ])
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $totalNominal = $transaksi->sum('nominal');

        $pdf = Pdf::loadView('rekap.pdf', [
            'transaksi'    => $transaksi,
            'tanggalMulai' => $tanggalMulai,
            'tanggalAkhir' => $tanggalAkhir,
            'totalNominal' => $totalNominal,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream(
            'Laporan-Pembayaran-' . $tanggalMulai . '-sd-' . $tanggalAkhir . '.pdf'
        );
    }

    /**
     * Cetak rekap tagihan satu siswa.
     */
    public function cetakPdfSiswa(Request $request, Siswa $siswa)
    {
        $pembayaran = Pembayaran::with([
            'jenisPembayaran',
            'detailPembayaran',
            'tahunAjaran',
        ])
            ->where('siswa_id', $siswa->id)
            ->orderBy('jenis_id')
            ->orderBy('id')
            ->get();

        $jenisPembayaran = JenisPembayaran::orderBy('nama')
            ->pluck('nama')
            ->toArray();

        $jenisData = [];
        $overall   = [
            'target'        => 0,
            'terbawa'       => 0,
            'total_tagihan' => 0,
            'terbayar'      => 0,
            'sisa'          => 0,
        ];

        foreach ($jenisPembayaran as $jenis) {
            $matching = $pembayaran->filter(
                fn ($item) => $item->jenisPembayaran
                    && $item->jenisPembayaran->nama === $jenis
            );

            if ($matching->isEmpty()) {
                continue;
            }

            $target = (float) $matching->sum(fn ($item) => (float) $item->target);
            $terbawa = (float) $matching->sum(fn ($item) => (float) ($item->belum_lunas ?? 0));
            $totalTagihan = $target + $terbawa;
            $terbayar = (float) $matching->sum(fn ($item) => (float) $item->detailPembayaran->sum('nominal'));
            $sisa = max($totalTagihan - $terbayar, 0);

            $status = $sisa <= 0
                ? 'Lunas'
                : 'Belum Lunas';

            $jenisData[] = [
                'nama'          => $jenis,
                'target'        => $target,
                'terbawa'       => $terbawa,
                'total_tagihan' => $totalTagihan,
                'terbayar'      => $terbayar,
                'sisa'          => $sisa,
                'status'        => $status,
            ];

            $overall['target']        += $target;
            $overall['terbawa']       += $terbawa;
            $overall['total_tagihan'] += $totalTagihan;
            $overall['terbayar']      += $terbayar;
            $overall['sisa']          += $sisa;
        }

        $overall['status'] = $overall['total_tagihan'] > 0
            ? ($overall['sisa'] <= 0 ? 'Lunas' : 'Belum Lunas')
            : 'Belum Ada Tagihan';

        $pdf = Pdf::loadView('rekap.pdf-siswa', [
            'siswa'     => $siswa,
            'jenisData' => $jenisData,
            'overall'   => $overall,
        ])->setPaper('a4', 'portrait');

        $namaFile = preg_replace(
            '/[^A-Za-z0-9._-]+/',
            '-',
            $siswa->nis . '-' . $siswa->nama
        );

        return $pdf->stream('Laporan-Tagihan-' . $namaFile . '.pdf');
    }
}