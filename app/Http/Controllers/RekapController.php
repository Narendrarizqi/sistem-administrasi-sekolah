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
                    'potongan'      => 0,
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
                            'potongan'      => 0,
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
                    $potongan = (float) $matching->sum(fn ($item) => $item->totalPotongan());
                    $totalTagihan = max($target + $terbawa - $potongan, 0);
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
                        : ($totalTagihan > 0 ? ($terbayar > 0 ? 'Sebagian' : 'Belum Lunas') : 'Belum Ada Tagihan');

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
                        'potongan'      => $potongan,
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
                    $overall['potongan']      += $potongan;
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
                        : ($overall['terbayar'] > 0 ? 'Sebagian' : 'Belum Lunas');
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
     * Cetak laporan rekap pembayaran (seluruh siswa / periode).
     */
    public function cetakPdf(Request $request)
    {
        $selectedId = $request->query('tahun_ajaran_id');
        $daftarTahunAjaran = TahunAjaran::orderByDesc('nama')->get();

        if ($selectedId) {
            $selectedTa = $daftarTahunAjaran->firstWhere('id', $selectedId);
        } else {
            $selectedTa = $daftarTahunAjaran->firstWhere('is_active', true) ?? $daftarTahunAjaran->first();
        }

        $tahunAjaranId = $selectedTa?->id;
        $tahunAjaranNama = $selectedTa?->nama ?? '2026/2027';

        // Ambil seluruh siswa aktif beserta pembayarannya di tahun ajaran terpilih
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
            ->get();

        $students = $pembayaran
            ->groupBy(fn ($item) => $item->siswa_id)
            ->map(function ($items) {
                $siswa = $items->first()->siswa;
                $target = (float) $items->sum(fn ($i) => (float) $i->target);
                $terbawa = (float) $items->sum(fn ($i) => (float) ($i->belum_lunas ?? 0));
                $potongan = (float) $items->sum(fn ($i) => $i->totalPotongan());
                $totalTagihan = max($target + $terbawa - $potongan, 0);
                $terbayar = (float) $items->sum(fn ($i) => (float) $i->detailPembayaran->sum('nominal'));
                $sisa = max($totalTagihan - $terbayar, 0);

                $status = 'Belum Ada Tagihan';
                if ($totalTagihan > 0) {
                    if ($sisa <= 0) {
                        $status = 'Lunas';
                    } elseif ($terbayar > 0) {
                        $status = 'Sebagian';
                    } else {
                        $status = 'Belum Lunas';
                    }
                }

                return [
                    'siswa'         => $siswa,
                    'target'        => $target,
                    'terbawa'       => $terbawa,
                    'potongan'      => $potongan,
                    'total_tagihan' => $totalTagihan,
                    'terbayar'      => $terbayar,
                    'sisa'          => $sisa,
                    'status'        => $status,
                ];
            })
            ->values();

        $grandTotal = [
            'target'        => $students->sum('target'),
            'terbawa'       => $students->sum('terbawa'),
            'potongan'      => $students->sum('potongan'),
            'total_tagihan' => $students->sum('total_tagihan'),
            'terbayar'      => $students->sum('terbayar'),
            'sisa'          => $students->sum('sisa'),
        ];

        $pdf = Pdf::loadView('rekap.pdf', [
            'students'        => $students,
            'grandTotal'      => $grandTotal,
            'tahunAjaranNama' => $tahunAjaranNama,
            'tanggalCetak'    => \Carbon\Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Rekap-Pembayaran-' . str_replace('/', '-', $tahunAjaranNama) . '.pdf');
    }

    /**
     * Cetak laporan rekap tagihan dan rincian lengkap satu siswa.
     */
    public function cetakPdfSiswa(Request $request, Siswa $siswa)
    {
        $tahunAjaranId = $request->query('tahun_ajaran_id');
        $profil = $this->buildProfilKeuanganSiswa($siswa, $tahunAjaranId);

        $pdf = Pdf::loadView('rekap.pdf-siswa', $profil)
            ->setPaper('a4', 'portrait');

        $namaFile = preg_replace(
            '/[^A-Za-z0-9._-]+/',
            '-',
            $siswa->nis . '-' . $siswa->nama . '-' . str_replace('/', '-', $profil['tahunAjaranNama'])
        );

        return $pdf->stream('Laporan-Rekap-Pembayaran-' . $namaFile . '.pdf');
    }

    /**
     * Helper: Membangun struktur data keuangan lengkap siswa (100% data aktual)
     */
    public function buildProfilKeuanganSiswa(Siswa $siswa, ?int $tahunAjaranId = null): array
    {
        $daftarTahunAjaran = TahunAjaran::orderByDesc('nama')->get();

        if ($tahunAjaranId) {
            $selectedTa = $daftarTahunAjaran->firstWhere('id', $tahunAjaranId);
        } else {
            $selectedTa = $daftarTahunAjaran->firstWhere('is_active', true) ?? $daftarTahunAjaran->first();
        }

        $tahunAjaranNama = $selectedTa?->nama ?? '2026/2027';

        // Query pembayaran siswa untuk tahun ajaran terpilih
        $pembayarans = Pembayaran::with([
            'jenisPembayaran',
            'detailPembayaran' => function ($q) {
                $q->orderBy('tanggal', 'asc')->orderBy('id', 'asc');
            },
            'tahunAjaran',
        ])
            ->where('siswa_id', $siswa->id)
            ->where(function ($q) use ($selectedTa, $tahunAjaranNama) {
                if ($selectedTa) {
                    $q->where('tahun_ajaran_id', $selectedTa->id)
                      ->orWhere(function ($s2) use ($tahunAjaranNama) {
                          $s2->whereNull('tahun_ajaran_id')
                             ->where('tahun_ajaran', $tahunAjaranNama);
                      });
                }
            })
            ->get();

        // 1. DATA IPP
        $ippModel = $pembayarans->first(fn ($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'IPP');
        $ippData = [
            'has_data'      => false,
            'target'        => 0,
            'terbawa'       => 0,
            'potongan'      => 0,
            'total_tagihan' => 0,
            'terbayar'      => 0,
            'sisa'          => 0,
            'status'        => 'Belum Ada Tagihan',
            'tarif_bulanan' => 0,
            'bulan_list'    => [],
            'riwayat'       => collect(),
        ];

        if ($ippModel) {
            $ippTarget = (float) $ippModel->target;
            $ippTerbawa = (float) ($ippModel->belum_lunas ?? 0);
            $ippPotongan = $ippModel->totalPotongan();
            $ippTotalTagihan = max($ippTarget + $ippTerbawa - $ippPotongan, 0);
            $ippTerbayar = (float) $ippModel->detailPembayaran->sum('nominal');
            $ippSisa = max($ippTotalTagihan - $ippTerbayar, 0);

            $ippStatus = 'Belum Ada Tagihan';
            if ($ippTotalTagihan > 0) {
                if ($ippSisa <= 0) {
                    $ippStatus = 'Lunas';
                } elseif ($ippTerbayar > 0) {
                    $ippStatus = 'Sebagian';
                } else {
                    $ippStatus = 'Belum Lunas';
                }
            }

            $tarifBulanan = max($ippTarget - $ippPotongan, 0) > 0 ? round(max($ippTarget - $ippPotongan, 0) / 12, 2) : 0;

            // Tentukan tahun awal untuk penanggalan bulan Juli s/d Juni
            $startYear = 2026;
            if ($selectedTa && $selectedTa->tanggal_mulai) {
                $startYear = (int) \Carbon\Carbon::parse($selectedTa->tanggal_mulai)->year;
            } elseif (preg_match('/^(\d{4})/', $tahunAjaranNama, $m)) {
                $startYear = (int) $m[1];
            }

            $bulanTemplate = [
                ['nama' => 'Juli', 'tahun' => $startYear],
                ['nama' => 'Agustus', 'tahun' => $startYear],
                ['nama' => 'September', 'tahun' => $startYear],
                ['nama' => 'Oktober', 'tahun' => $startYear],
                ['nama' => 'November', 'tahun' => $startYear],
                ['nama' => 'Desember', 'tahun' => $startYear],
                ['nama' => 'Januari', 'tahun' => $startYear + 1],
                ['nama' => 'Februari', 'tahun' => $startYear + 1],
                ['nama' => 'Maret', 'tahun' => $startYear + 1],
                ['nama' => 'April', 'tahun' => $startYear + 1],
                ['nama' => 'Mei', 'tahun' => $startYear + 1],
                ['nama' => 'Juni', 'tahun' => $startYear + 1],
            ];

            // Alokasi dana terbayar ke bulan (setelah menutup terbawa)
            $danaUntukBulan = max(0, $ippTerbayar - $ippTerbawa);
            $bulanRows = [];

            foreach ($bulanTemplate as $b) {
                $tagihanBulan = $tarifBulanan;
                $terbayarBulan = 0;

                if ($tagihanBulan > 0) {
                    $terbayarBulan = min($tagihanBulan, max(0, $danaUntukBulan));
                    $danaUntukBulan = max(0, $danaUntukBulan - $terbayarBulan);
                }

                $sisaBulan = max($tagihanBulan - $terbayarBulan, 0);
                $statusBulan = 'Belum Lunas';
                if ($tagihanBulan <= 0) {
                    $statusBulan = '-';
                } elseif ($sisaBulan <= 0) {
                    $statusBulan = 'Lunas';
                } elseif ($terbayarBulan > 0) {
                    $statusBulan = 'Sebagian';
                }

                $bulanRows[] = [
                    'bulan'         => $b['nama'] . ' ' . $b['tahun'],
                    'bulan_murni'   => $b['nama'],
                    'tahun'         => $b['tahun'],
                    'tagihan'       => $tagihanBulan,
                    'terbayar'      => $terbayarBulan,
                    'sisa'          => $sisaBulan,
                    'status'        => $statusBulan,
                ];
            }

            $ippData = [
                'has_data'      => true,
                'target'        => $ippTarget,
                'terbawa'       => $ippTerbawa,
                'potongan'      => $ippPotongan,
                'total_tagihan' => $ippTotalTagihan,
                'terbayar'      => $ippTerbayar,
                'sisa'          => $ippSisa,
                'status'        => $ippStatus,
                'tarif_bulanan' => $tarifBulanan,
                'bulan_list'    => $bulanRows,
                'riwayat'       => $ippModel->detailPembayaran,
            ];
        }

        // 2. DATA KEGIATAN INTRAKURIKULER (KI)
        $kiModel = $pembayarans->first(fn ($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'KI');
        $kiData = [
            'has_data'      => false,
            'target'        => 0,
            'terbawa'       => 0,
            'total_tagihan' => 0,
            'terbayar'      => 0,
            'sisa'          => 0,
            'status'        => 'Belum Ada Tagihan',
            'komponen'      => [],
            'riwayat'       => collect(),
        ];

        if ($kiModel) {
            $targetUts = (float) $kiModel->target_uts;
            $targetUas = (float) $kiModel->target_uas;
            $targetUjian = (float) $kiModel->target_ujian;
            $totalTargetKi = (float) $kiModel->target;
            $terbawaKi = (float) ($kiModel->belum_lunas ?? 0);
            $totalTagihanKi = $totalTargetKi + $terbawaKi;

            $terbayarUts = (float) $kiModel->detailPembayaran->where('kategori', 'UTS')->sum('nominal');
            $terbayarUas = (float) $kiModel->detailPembayaran->where('kategori', 'UAS')->sum('nominal');
            $terbayarUjian = (float) $kiModel->detailPembayaran->where('kategori', 'Ujian')->sum('nominal');
            $terbayarLain = (float) $kiModel->detailPembayaran->filter(fn ($d) => !in_array($d->kategori, ['UTS', 'UAS', 'Ujian']))->sum('nominal');

            $totalTerbayarKi = $terbayarUts + $terbayarUas + $terbayarUjian + $terbayarLain;
            $sisaKi = max($totalTagihanKi - $totalTerbayarKi, 0);

            $statusKi = 'Belum Ada Tagihan';
            if ($totalTagihanKi > 0) {
                if ($sisaKi <= 0) {
                    $statusKi = 'Lunas';
                } elseif ($totalTerbayarKi > 0) {
                    $statusKi = 'Sebagian';
                } else {
                    $statusKi = 'Belum Lunas';
                }
            }

            $kategoriList = [
                [
                    'nama'     => 'UTS (Ujian Tengah Semester)',
                    'kode'     => 'UTS',
                    'tagihan'  => $targetUts,
                    'terbayar' => $terbayarUts,
                    'sisa'     => max($targetUts - $terbayarUts, 0),
                    'riwayat'  => $kiModel->detailPembayaran->where('kategori', 'UTS'),
                ],
                [
                    'nama'     => 'UAS (Ujian Akhir Semester)',
                    'kode'     => 'UAS',
                    'tagihan'  => $targetUas,
                    'terbayar' => $terbayarUas,
                    'sisa'     => max($targetUas - $terbayarUas, 0),
                    'riwayat'  => $kiModel->detailPembayaran->where('kategori', 'UAS'),
                ],
                [
                    'nama'     => 'Ujian (Praktik / Sekolah / Lainnya)',
                    'kode'     => 'Ujian',
                    'tagihan'  => $targetUjian,
                    'terbayar' => $terbayarUjian,
                    'sisa'     => max($targetUjian - $terbayarUjian, 0),
                    'riwayat'  => $kiModel->detailPembayaran->where('kategori', 'Ujian'),
                ],
            ];

            foreach ($kategoriList as &$komp) {
                if ($komp['tagihan'] <= 0) {
                    $komp['status'] = '-';
                } elseif ($komp['sisa'] <= 0) {
                    $komp['status'] = 'Lunas';
                } elseif ($komp['terbayar'] > 0) {
                    $komp['status'] = 'Sebagian';
                } else {
                    $komp['status'] = 'Belum Lunas';
                }
            }

            $kiData = [
                'has_data'      => true,
                'target'        => $totalTargetKi,
                'terbawa'       => $terbawaKi,
                'total_tagihan' => $totalTagihanKi,
                'terbayar'      => $totalTerbayarKi,
                'sisa'          => $sisaKi,
                'status'        => $statusKi,
                'komponen'      => $kategoriList,
                'riwayat'       => $kiModel->detailPembayaran,
            ];
        }

        // 3. DATA DAFTAR ULANG (DU)
        $duModel = $pembayarans->first(fn ($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'DU');
        $duData = [
            'has_data'      => false,
            'target'        => 0,
            'terbawa'       => 0,
            'total_tagihan' => 0,
            'terbayar'      => 0,
            'sisa'          => 0,
            'status'        => 'Belum Ada Tagihan',
            'riwayat'       => collect(),
        ];

        if ($duModel) {
            $duTarget = (float) $duModel->target;
            $duTerbawa = (float) ($duModel->belum_lunas ?? 0);
            $duTotalTagihan = $duTarget + $duTerbawa;
            $duTerbayar = (float) $duModel->detailPembayaran->sum('nominal');
            $duSisa = max($duTotalTagihan - $duTerbayar, 0);

            $duStatus = 'Belum Ada Tagihan';
            if ($duTotalTagihan > 0) {
                if ($duSisa <= 0) {
                    $duStatus = 'Lunas';
                } elseif ($duTerbayar > 0) {
                    $duStatus = 'Sebagian';
                } else {
                    $duStatus = 'Belum Lunas';
                }
            }

            $duData = [
                'has_data'      => true,
                'target'        => $duTarget,
                'terbawa'       => $duTerbawa,
                'total_tagihan' => $duTotalTagihan,
                'terbayar'      => $duTerbayar,
                'sisa'          => $duSisa,
                'status'        => $duStatus,
                'riwayat'       => $duModel->detailPembayaran,
            ];
        }

        // 4. DATA SARANA & PRASARANA (SARPRAS)
        $sarprasModel = $pembayarans->first(fn ($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'Sarpras');
        $sarprasData = [
            'has_data'      => false,
            'target'        => 0,
            'terbawa'       => 0,
            'total_tagihan' => 0,
            'terbayar'      => 0,
            'sisa'          => 0,
            'status'        => 'Belum Ada Tagihan',
            'riwayat'       => collect(),
        ];

        if ($sarprasModel) {
            $sarprasTarget = (float) $sarprasModel->target;
            $sarprasTerbawa = (float) ($sarprasModel->belum_lunas ?? 0);
            $sarprasTotalTagihan = $sarprasTarget + $sarprasTerbawa;
            $sarprasTerbayar = (float) $sarprasModel->detailPembayaran->sum('nominal');
            $sarprasSisa = max($sarprasTotalTagihan - $sarprasTerbayar, 0);

            $sarprasStatus = 'Belum Ada Tagihan';
            if ($sarprasTotalTagihan > 0) {
                if ($sarprasSisa <= 0) {
                    $sarprasStatus = 'Lunas';
                } elseif ($sarprasTerbayar > 0) {
                    $sarprasStatus = 'Sebagian';
                } else {
                    $sarprasStatus = 'Belum Lunas';
                }
            }

            $sarprasData = [
                'has_data'      => true,
                'target'        => $sarprasTarget,
                'terbawa'       => $sarprasTerbawa,
                'total_tagihan' => $sarprasTotalTagihan,
                'terbayar'      => $sarprasTerbayar,
                'sisa'          => $sarprasSisa,
                'status'        => $sarprasStatus,
                'riwayat'       => $sarprasModel->detailPembayaran,
            ];
        }

        // 5. REKAPITULASI TAGIHAN TERBAWA TAHUN LALU (JIKA ADA)
        $terbawaSummary = [];
        $totalTerbawaSemua = 0;
        $totalTerbayarTerbawa = 0;
        $totalSisaTerbawa = 0;

        foreach (['IPP' => $ippData, 'Daftar Ulang' => $duData, 'Sarana & Prasarana' => $sarprasData, 'Kegiatan Intrakurikuler' => $kiData] as $jenisLabel => $d) {
            if ($d['terbawa'] > 0) {
                $terbayarUntukTerbawa = min($d['terbawa'], $d['terbayar']);
                $sisaTerbawa = max($d['terbawa'] - $terbayarUntukTerbawa, 0);
                $statusTerbawa = $sisaTerbawa <= 0 ? 'Lunas' : ($terbayarUntukTerbawa > 0 ? 'Sebagian' : 'Belum Lunas');

                $terbawaSummary[] = [
                    'jenis'    => $jenisLabel,
                    'terbawa'  => $d['terbawa'],
                    'terbayar' => $terbayarUntukTerbawa,
                    'sisa'     => $sisaTerbawa,
                    'status'   => $statusTerbawa,
                ];

                $totalTerbawaSemua += $d['terbawa'];
                $totalTerbayarTerbawa += $terbayarUntukTerbawa;
                $totalSisaTerbawa += $sisaTerbawa;
            }
        }

        // 6. RINGKASAN UTAMA (REKAP KESELURUHAN SISWA)
        $ringkasanSiswa = [
            [
                'no'            => 1,
                'jenis'         => 'IPP (Iuran Pengembangan Pendidikan)',
                'target'        => $ippData['target'],
                'potongan'      => $ippData['potongan'],
                'terbawa'       => $ippData['terbawa'],
                'total_tagihan' => $ippData['total_tagihan'],
                'terbayar'      => $ippData['terbayar'],
                'sisa'          => $ippData['sisa'],
                'status'        => $ippData['status'],
            ],
            [
                'no'            => 2,
                'jenis'         => 'Daftar Ulang (DU)',
                'target'        => $duData['target'],
                'potongan'      => $duData['potongan'] ?? 0,
                'terbawa'       => $duData['terbawa'],
                'total_tagihan' => $duData['total_tagihan'],
                'terbayar'      => $duData['terbayar'],
                'sisa'          => $duData['sisa'],
                'status'        => $duData['status'],
            ],
            [
                'no'            => 3,
                'jenis'         => 'Sarana & Prasarana',
                'target'        => $sarprasData['target'],
                'potongan'      => $sarprasData['potongan'] ?? 0,
                'terbawa'       => $sarprasData['terbawa'],
                'total_tagihan' => $sarprasData['total_tagihan'],
                'terbayar'      => $sarprasData['terbayar'],
                'sisa'          => $sarprasData['sisa'],
                'status'        => $sarprasData['status'],
            ],
            [
                'no'            => 4,
                'jenis'         => 'Kegiatan Intrakurikuler (KI)',
                'target'        => $kiData['target'],
                'potongan'      => $kiData['potongan'] ?? 0,
                'terbawa'       => $kiData['terbawa'],
                'total_tagihan' => $kiData['total_tagihan'],
                'terbayar'      => $kiData['terbayar'],
                'sisa'          => $kiData['sisa'],
                'status'        => $kiData['status'],
            ],
        ];

        $grandTotal = [
            'target'        => array_sum(array_column($ringkasanSiswa, 'target')),
            'potongan'      => array_sum(array_column($ringkasanSiswa, 'potongan')),
            'terbawa'       => array_sum(array_column($ringkasanSiswa, 'terbawa')),
            'total_tagihan' => array_sum(array_column($ringkasanSiswa, 'total_tagihan')),
            'terbayar'      => array_sum(array_column($ringkasanSiswa, 'terbayar')),
            'sisa'          => array_sum(array_column($ringkasanSiswa, 'sisa')),
        ];

        $grandStatus = 'Belum Ada Tagihan';
        if ($grandTotal['total_tagihan'] > 0) {
            if ($grandTotal['sisa'] <= 0) {
                $grandStatus = 'Lunas';
            } elseif ($grandTotal['terbayar'] > 0) {
                $grandStatus = 'Sebagian';
            } else {
                $grandStatus = 'Belum Lunas';
            }
        }
        $grandTotal['status'] = $grandStatus;

        return [
            'siswa'             => $siswa,
            'tahunAjaranNama'   => $tahunAjaranNama,
            'selectedTa'        => $selectedTa,
            'tanggalCetak'      => \Carbon\Carbon::now()->translatedFormat('d F Y'),
            'ringkasanSiswa'    => $ringkasanSiswa,
            'grandTotal'        => $grandTotal,
            'ippData'           => $ippData,
            'kiData'            => $kiData,
            'duData'            => $duData,
            'sarprasData'       => $sarprasData,
            'terbawaSummary'       => $terbawaSummary,
            'totalTerbawaSemua'    => $totalTerbawaSemua,
            'totalTerbayarTerbawa' => $totalTerbayarTerbawa,
            'totalSisaTerbawa'     => $totalSisaTerbawa,
        ];
    }
}