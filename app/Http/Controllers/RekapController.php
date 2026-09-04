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

        $kelasFilter = $request->query('kelas');
        $daftarKelas = Siswa::select('kelas')->whereNotNull('kelas')->where('kelas', '!=', '')->distinct()->orderBy('kelas')->pluck('kelas');

        if ($kelasFilter) {
            $queryPembayaran->whereHas('siswa', fn ($sq) => $sq->where('kelas', $kelasFilter));
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

        $sort = $request->query('sort', 'nama');
        $direction = strtolower($request->query('direction', 'asc')) === 'desc' ? 'desc' : 'asc';
        if (!in_array($sort, ['nis', 'nama', 'kelas', 'target', 'terbawa', 'total_tagihan', 'terbayar', 'sisa'])) {
            $sort = 'nama';
            $direction = 'asc';
        }

        if ($sort === 'nis') {
            $students = $direction === 'desc'
                ? $students->sortByDesc(fn ($s) => $s['siswa']->nis ?? '', SORT_NATURAL)
                : $students->sortBy(fn ($s) => $s['siswa']->nis ?? '', SORT_NATURAL);
        } elseif ($sort === 'nama') {
            $students = $direction === 'desc'
                ? $students->sortByDesc(fn ($s) => strtolower($s['siswa']->nama ?? ''), SORT_NATURAL)
                : $students->sortBy(fn ($s) => strtolower($s['siswa']->nama ?? ''), SORT_NATURAL);
        } elseif ($sort === 'kelas') {
            $students = $direction === 'desc'
                ? $students->sortByDesc(fn ($s) => strtolower($s['siswa']->kelas ?? ''), SORT_NATURAL)
                : $students->sortBy(fn ($s) => strtolower($s['siswa']->kelas ?? ''), SORT_NATURAL);
        } elseif (in_array($sort, ['target', 'terbawa', 'total_tagihan', 'terbayar', 'sisa'])) {
            $students = $direction === 'desc'
                ? $students->sortByDesc(fn ($s) => (float) ($s['jenis']['Total'][$sort] ?? 0))
                : $students->sortBy(fn ($s) => (float) ($s['jenis']['Total'][$sort] ?? 0));
        }
        $students = $students->values();

        // Paginasi 25 siswa per halaman
        $perPage = 25;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $currentItems = $students->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $students = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $students->count(),
            $perPage,
            $currentPage,
            [
                'path'  => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );

        return view('rekap.index', compact(
            'students',
            'jenisPembayaran',
            'daftarTahunAjaran',
            'selectedTa',
            'tahunAjaranId',
            'tahunAjaranNama',
            'daftarKelas',
            'kelasFilter',
            'sort',
            'direction'
        ));
    }

    /**
     * Cetak laporan rekap pembayaran (rincian per siswa dalam format landscape seperti Excel).
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
        $kelasFilter = $request->query('kelas');

        // Tentukan tahun awal untuk penanggalan bulan IPP (Juli s/d Juni)
        $startYear = 2026;
        if ($selectedTa && $selectedTa->tanggal_mulai) {
            $startYear = (int) \Carbon\Carbon::parse($selectedTa->tanggal_mulai)->year;
        } elseif (preg_match('/^(\d{4})/', $tahunAjaranNama, $m)) {
            $startYear = (int) $m[1];
        }

        $bulanList = [
            ['nama' => 'Juli', 'short' => 'Jul', 'tahun' => $startYear],
            ['nama' => 'Agustus', 'short' => 'Ags', 'tahun' => $startYear],
            ['nama' => 'September', 'short' => 'Sep', 'tahun' => $startYear],
            ['nama' => 'Oktober', 'short' => 'Okt', 'tahun' => $startYear],
            ['nama' => 'November', 'short' => 'Nov', 'tahun' => $startYear],
            ['nama' => 'Desember', 'short' => 'Des', 'tahun' => $startYear],
            ['nama' => 'Januari', 'short' => 'Jan', 'tahun' => $startYear + 1],
            ['nama' => 'Februari', 'short' => 'Feb', 'tahun' => $startYear + 1],
            ['nama' => 'Maret', 'short' => 'Mar', 'tahun' => $startYear + 1],
            ['nama' => 'April', 'short' => 'Apr', 'tahun' => $startYear + 1],
            ['nama' => 'Mei', 'short' => 'Mei', 'tahun' => $startYear + 1],
            ['nama' => 'Juni', 'short' => 'Jun', 'tahun' => $startYear + 1],
        ];

        // 4 Kolom Asesmen standar seperti di Excel: STS Gasal, SAS, STS Genap, SAT
        $asesmenItems = [
            ['key' => 'sts_gasal', 'label' => 'STS Gasal', 'aliases' => ['STS Gasal', 'STS 1', 'UTS', 'UTS 1']],
            ['key' => 'sas_gasal', 'label' => 'SAS',       'aliases' => ['SAS Gasal', 'SAS 1', 'SAS', 'UAS', 'UAS 1']],
            ['key' => 'sts_genap', 'label' => 'STS Genap', 'aliases' => ['STS Genap', 'STS 2', 'UTS 2']],
            ['key' => 'sat',       'label' => 'SAT',       'aliases' => ['SAT', 'SAS Genap', 'SAS 2', 'ASAJ', 'Ujian', 'UAS 2']],
        ];

        // Ambil seluruh siswa aktif
        $siswaQuery = Siswa::query();
        if ($kelasFilter) {
            $siswaQuery->where('kelas', $kelasFilter);
        }
        $siswaList = $siswaQuery->orderBy('kelas')->orderBy('nama')->get();
        $siswaIds = $siswaList->pluck('id')->toArray();

        // Query pembayaran untuk seluruh siswa terpilih pada tahun ajaran ini
        $queryPembayaran = Pembayaran::with([
            'siswa',
            'jenisPembayaran',
            'detailPembayaran',
            'itemsKi',
            'itemsEkskul',
            'itemsKokurikuler',
        ])
        ->whereIn('siswa_id', $siswaIds);

        if ($tahunAjaranId) {
            $queryPembayaran->where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                  ->orWhere(function ($s2) use ($tahunAjaranNama) {
                      $s2->whereNull('tahun_ajaran_id')
                         ->where('tahun_ajaran', $tahunAjaranNama);
                  });
            });
        }

        $allPembayaran = $queryPembayaran->get()->groupBy('siswa_id');

        $studentsData = [];
        $hasEkskul = false;
        $hasKoku = false;

        foreach ($siswaList as $siswa) {
            $pembayarans = $allPembayaran->get($siswa->id, collect());

            // 1. SARPRAS
            $sarprasModel = $pembayarans->first(fn($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'Sarpras');
            $sarprasTarget = $sarprasModel ? (float)$sarprasModel->totalTagihan() : 0;
            $sarprasTerbayar = $sarprasModel ? (float)$sarprasModel->detailPembayaran->sum('nominal') : 0;

            // 2. DAFTAR ULANG
            $duModel = $pembayarans->first(fn($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'DU');
            $duTarget = $duModel ? (float)$duModel->totalTagihan() : 0;
            $duTerbayar = $duModel ? (float)$duModel->detailPembayaran->sum('nominal') : 0;

            // 3. IPP (12 BULAN)
            $ippModel = $pembayarans->first(fn($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'IPP');
            $ippTarget = $ippModel ? (float)$ippModel->totalTagihan() : 0;
            $ippTerbayar = $ippModel ? (float)$ippModel->detailPembayaran->sum('nominal') : 0;
            $ippTerbawa = $ippModel ? (float)($ippModel->belum_lunas ?? 0) : 0;
            $ippPotongan = $ippModel ? $ippModel->totalPotongan() : 0;
            $tarifBulanan = $ippModel && max($ippModel->target - $ippPotongan, 0) > 0 ? round(max($ippModel->target - $ippPotongan, 0) / 12, 2) : 0;

            $danaUntukBulan = max(0, $ippTerbayar - $ippTerbawa);
            $ippBulanMap = [];
            foreach ($bulanList as $b) {
                $tagihanBulan = $tarifBulanan;
                $terbayarBulan = 0;
                if ($tagihanBulan > 0) {
                    $terbayarBulan = min($tagihanBulan, max(0, $danaUntukBulan));
                    $danaUntukBulan = max(0, $danaUntukBulan - $terbayarBulan);
                }
                $ippBulanMap[$b['nama']] = $terbayarBulan;
            }

            // 4. ASESMEN (KI)
            $kiModel = $pembayarans->first(fn($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'KI');
            $kiTarget = $kiModel ? (float)$kiModel->totalTagihan() : 0;
            $kiTerbayar = $kiModel ? (float)$kiModel->detailPembayaran->sum('nominal') : 0;

            $asesmenMap = [];
            foreach ($asesmenItems as $asm) {
                $val = 0;
                if ($kiModel) {
                    $val = (float)$kiModel->detailPembayaran
                        ->filter(function($d) use ($asm) {
                            foreach ($asm['aliases'] as $alias) {
                                if (stripos($d->kategori, $alias) !== false) return true;
                            }
                            return false;
                        })
                        ->sum('nominal');
                }
                $asesmenMap[$asm['key']] = $val;
            }

            // 5. EKSTRAKURIKULER
            $ekskulModel = $pembayarans->first(fn($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'Ekstrakurikuler');
            $ekskulTarget = $ekskulModel ? (float)$ekskulModel->totalTagihan() : 0;
            $ekskulTerbayar = $ekskulModel ? (float)$ekskulModel->detailPembayaran->sum('nominal') : 0;
            if ($ekskulTarget > 0 || $ekskulTerbayar > 0) $hasEkskul = true;

            // 6. KOKURIKULER
            $kokuModel = $pembayarans->first(fn($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'Kokurikuler');
            $kokuTarget = $kokuModel ? (float)$kokuModel->totalTagihan() : 0;
            $kokuTerbayar = $kokuModel ? (float)$kokuModel->detailPembayaran->sum('nominal') : 0;
            if ($kokuTarget > 0 || $kokuTerbayar > 0) $hasKoku = true;

            // TOTALS
            $totalTagihan = $sarprasTarget + $duTarget + $ippTarget + $kiTarget + $ekskulTarget + $kokuTarget;
            $totalTerbayar = $sarprasTerbayar + $duTerbayar + $ippTerbayar + $kiTerbayar + $ekskulTerbayar + $kokuTerbayar;
            $sisa = max($totalTagihan - $totalTerbayar, 0);
            $status = ($totalTagihan > 0 && $sisa <= 0) ? 'Lunas' : ($totalTerbayar > 0 ? 'Sebagian' : ($totalTagihan > 0 ? 'Belum Lunas' : '-'));

            $studentsData[] = [
                'siswa'          => $siswa,
                'kelas'          => $siswa->kelas ?: 'Tanpa Kelas',
                'sarpras'        => $sarprasTerbayar,
                'du'             => $duTerbayar,
                'ipp_bulan'      => $ippBulanMap,
                'asesmen'        => $asesmenMap,
                'ekskul'         => $ekskulTerbayar,
                'kokurikuler'    => $kokuTerbayar,
                'total_tagihan'  => $totalTagihan,
                'total_terbayar' => $totalTerbayar,
                'sisa'           => $sisa,
                'status'         => $status,
            ];
        }

        // Group by Kelas
        $groupedByKelas = collect($studentsData)->groupBy('kelas');

        // Subtotals per Kelas
        $classSubtotals = [];
        foreach ($groupedByKelas as $k => $students) {
            $classSubtotals[$k] = [
                'sarpras'        => $students->sum('sarpras'),
                'du'             => $students->sum('du'),
                'ipp_bulan'      => [],
                'asesmen'        => [],
                'ekskul'         => $students->sum('ekskul'),
                'kokurikuler'    => $students->sum('kokurikuler'),
                'total_tagihan'  => $students->sum('total_tagihan'),
                'total_terbayar' => $students->sum('total_terbayar'),
                'sisa'           => $students->sum('sisa'),
            ];
            foreach ($bulanList as $b) {
                $classSubtotals[$k]['ipp_bulan'][$b['nama']] = $students->sum(fn($s) => $s['ipp_bulan'][$b['nama']] ?? 0);
            }
            foreach ($asesmenItems as $asm) {
                $classSubtotals[$k]['asesmen'][$asm['key']] = $students->sum(fn($s) => $s['asesmen'][$asm['key']] ?? 0);
            }
        }

        // Grand Total across all classes
        $grandTotal = [
            'total_siswa'    => count($studentsData),
            'sarpras'        => collect($studentsData)->sum('sarpras'),
            'du'             => collect($studentsData)->sum('du'),
            'ipp_bulan'      => [],
            'asesmen'        => [],
            'ekskul'         => collect($studentsData)->sum('ekskul'),
            'kokurikuler'    => collect($studentsData)->sum('kokurikuler'),
            'total_tagihan'  => collect($studentsData)->sum('total_tagihan'),
            'total_terbayar' => collect($studentsData)->sum('total_terbayar'),
            'sisa'           => collect($studentsData)->sum('sisa'),
        ];
        foreach ($bulanList as $b) {
            $grandTotal['ipp_bulan'][$b['nama']] = collect($studentsData)->sum(fn($s) => $s['ipp_bulan'][$b['nama']] ?? 0);
        }
        foreach ($asesmenItems as $asm) {
            $grandTotal['asesmen'][$asm['key']] = collect($studentsData)->sum(fn($s) => $s['asesmen'][$asm['key']] ?? 0);
        }

        $pdf = Pdf::loadView('rekap.pdf', [
            'groupedByKelas'  => $groupedByKelas,
            'classSubtotals'  => $classSubtotals,
            'grandTotal'      => $grandTotal,
            'bulanList'       => $bulanList,
            'asesmenItems'    => $asesmenItems,
            'hasEkskul'       => $hasEkskul,
            'hasKoku'         => $hasKoku,
            'tahunAjaranNama' => $tahunAjaranNama,
            'kelasFilter'     => $kelasFilter,
            'tanggalCetak'    => \Carbon\Carbon::now()->translatedFormat('d F Y'),
        ])->setPaper('a4', 'landscape');

        $namaFile = 'Laporan-Rekap-Target-Pemasukan-' . str_replace('/', '-', $tahunAjaranNama);
        if ($kelasFilter) {
            $namaFile .= '-Kelas-' . str_replace(' ', '-', $kelasFilter);
        }

        return $pdf->stream($namaFile . '.pdf');
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
            $kiModel->loadMissing(['itemsKi', 'detailPembayaran']);
            $totalTargetKi = (float) $kiModel->target;
            $terbawaKi = (float) ($kiModel->belum_lunas ?? 0);
            $totalTagihanKi = $totalTargetKi + $terbawaKi;
            $totalTerbayarKi = (float) $kiModel->detailPembayaran->sum('nominal');
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

            $items = $kiModel->itemsKi;
            if ($items->isNotEmpty()) {
                $kategoriList = [];
                foreach ($items as $it) {
                    $terbayarItem = (float) $kiModel->detailPembayaran->where('kategori', $it->nama_iuran)->sum('nominal');
                    $sisaItem = max((float)$it->nominal - $terbayarItem, 0);
                    $kategoriList[] = [
                        'nama'     => $it->nama_iuran,
                        'kode'     => $it->nama_iuran,
                        'tagihan'  => (float) $it->nominal,
                        'terbayar' => $terbayarItem,
                        'sisa'     => $sisaItem,
                        'riwayat'  => $kiModel->detailPembayaran->where('kategori', $it->nama_iuran),
                    ];
                }
            } else {
                $targetUts = (float) $kiModel->target_uts;
                $targetUas = (float) $kiModel->target_uas;
                $targetUjian = (float) $kiModel->target_ujian;
                $terbayarUts = (float) $kiModel->detailPembayaran->where('kategori', 'UTS')->sum('nominal');
                $terbayarUas = (float) $kiModel->detailPembayaran->where('kategori', 'UAS')->sum('nominal');
                $terbayarUjian = (float) $kiModel->detailPembayaran->where('kategori', 'Ujian')->sum('nominal');

                $kategoriList = [
                    [
                        'nama'     => 'STS Gasal (UTS)',
                        'kode'     => 'UTS',
                        'tagihan'  => $targetUts,
                        'terbayar' => $terbayarUts,
                        'sisa'     => max($targetUts - $terbayarUts, 0),
                        'riwayat'  => $kiModel->detailPembayaran->where('kategori', 'UTS'),
                    ],
                    [
                        'nama'     => 'SAS (UAS)',
                        'kode'     => 'UAS',
                        'tagihan'  => $targetUas,
                        'terbayar' => $terbayarUas,
                        'sisa'     => max($targetUas - $terbayarUas, 0),
                        'riwayat'  => $kiModel->detailPembayaran->where('kategori', 'UAS'),
                    ],
                    [
                        'nama'     => 'ASAJ (Ujian)',
                        'kode'     => 'Ujian',
                        'tagihan'  => $targetUjian,
                        'terbayar' => $terbayarUjian,
                        'sisa'     => max($targetUjian - $terbayarUjian, 0),
                        'riwayat'  => $kiModel->detailPembayaran->where('kategori', 'Ujian'),
                    ],
                ];
            }

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

        // 5. DATA EKSTRAKURIKULER
        $ekskulModel = $pembayarans->first(fn ($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'Ekstrakurikuler');
        $ekskulData = [
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

        if ($ekskulModel) {
            $ekskulModel->loadMissing(['itemsEkskul', 'detailPembayaran']);
            $totalTargetEkskul = (float) $ekskulModel->target;
            $terbawaEkskul = (float) ($ekskulModel->belum_lunas ?? 0);
            $totalTagihanEkskul = $totalTargetEkskul + $terbawaEkskul;
            $totalTerbayarEkskul = (float) $ekskulModel->detailPembayaran->sum('nominal');
            $sisaEkskul = max($totalTagihanEkskul - $totalTerbayarEkskul, 0);

            $statusEkskul = 'Belum Ada Tagihan';
            if ($totalTagihanEkskul > 0) {
                if ($sisaEkskul <= 0) {
                    $statusEkskul = 'Lunas';
                } elseif ($totalTerbayarEkskul > 0) {
                    $statusEkskul = 'Sebagian';
                } else {
                    $statusEkskul = 'Belum Lunas';
                }
            }

            $itemsE = $ekskulModel->itemsEkskul;
            $kategoriListE = [];
            foreach ($itemsE as $it) {
                $terbayarItem = (float) $ekskulModel->detailPembayaran->where('kategori', $it->nama_ekskul)->sum('nominal');
                $sisaItem = max((float)$it->nominal - $terbayarItem, 0);
                $kategoriListE[] = [
                    'nama'     => $it->nama_ekskul,
                    'kode'     => $it->nama_ekskul,
                    'tagihan'  => (float) $it->nominal,
                    'terbayar' => $terbayarItem,
                    'sisa'     => $sisaItem,
                    'status'   => ((float)$it->nominal <= 0 ? '-' : ($sisaItem <= 0 ? 'Lunas' : ($terbayarItem > 0 ? 'Sebagian' : 'Belum Lunas'))),
                    'riwayat'  => $ekskulModel->detailPembayaran->where('kategori', $it->nama_ekskul),
                ];
            }

            $ekskulData = [
                'has_data'      => true,
                'target'        => $totalTargetEkskul,
                'terbawa'       => $terbawaEkskul,
                'total_tagihan' => $totalTagihanEkskul,
                'terbayar'      => $totalTerbayarEkskul,
                'sisa'          => $sisaEkskul,
                'status'        => $statusEkskul,
                'komponen'      => $kategoriListE,
                'riwayat'       => $ekskulModel->detailPembayaran,
            ];
        }

        // 6. DATA KOKURIKULER
        $kokurikulerModel = $pembayarans->first(fn ($p) => $p->jenisPembayaran && $p->jenisPembayaran->nama === 'Kokurikuler');
        $kokurikulerData = [
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

        if ($kokurikulerModel) {
            $kokurikulerModel->loadMissing(['itemsKokurikuler', 'detailPembayaran']);
            $totalTargetKoku = (float) $kokurikulerModel->target;
            $terbawaKoku = (float) ($kokurikulerModel->belum_lunas ?? 0);
            $totalTagihanKoku = $totalTargetKoku + $terbawaKoku;
            $totalTerbayarKoku = (float) $kokurikulerModel->detailPembayaran->sum('nominal');
            $sisaKoku = max($totalTagihanKoku - $totalTerbayarKoku, 0);

            $statusKoku = 'Belum Ada Tagihan';
            if ($totalTagihanKoku > 0) {
                if ($sisaKoku <= 0) {
                    $statusKoku = 'Lunas';
                } elseif ($totalTerbayarKoku > 0) {
                    $statusKoku = 'Sebagian';
                } else {
                    $statusKoku = 'Belum Lunas';
                }
            }

            $itemsK = $kokurikulerModel->itemsKokurikuler;
            $kategoriListK = [];
            foreach ($itemsK as $it) {
                $terbayarItem = (float) $kokurikulerModel->detailPembayaran->where('kategori', $it->nama_kegiatan)->sum('nominal');
                $sisaItem = max((float)$it->nominal - $terbayarItem, 0);
                $kategoriListK[] = [
                    'nama'     => $it->nama_kegiatan,
                    'kode'     => $it->nama_kegiatan,
                    'tagihan'  => (float) $it->nominal,
                    'terbayar' => $terbayarItem,
                    'sisa'     => $sisaItem,
                    'status'   => ((float)$it->nominal <= 0 ? '-' : ($sisaItem <= 0 ? 'Lunas' : ($terbayarItem > 0 ? 'Sebagian' : 'Belum Lunas'))),
                    'riwayat'  => $kokurikulerModel->detailPembayaran->where('kategori', $it->nama_kegiatan),
                ];
            }

            $kokurikulerData = [
                'has_data'      => true,
                'target'        => $totalTargetKoku,
                'terbawa'       => $terbawaKoku,
                'total_tagihan' => $totalTagihanKoku,
                'terbayar'      => $totalTerbayarKoku,
                'sisa'          => $sisaKoku,
                'status'        => $statusKoku,
                'komponen'      => $kategoriListK,
                'riwayat'       => $kokurikulerModel->detailPembayaran,
            ];
        }

        // 7. REKAPITULASI TAGIHAN TERBAWA TAHUN LALU (JIKA ADA)
        $terbawaSummary = [];
        $totalTerbawaSemua = 0;
        $totalTerbayarTerbawa = 0;
        $totalSisaTerbawa = 0;

        foreach ([
            'IPP'             => $ippData,
            'Daftar Ulang'    => $duData,
            'Sarana & Prasarana' => $sarprasData,
            'Asesmen'         => $kiData,
            'Ekstrakurikuler' => $ekskulData,
            'Kokurikuler'     => $kokurikulerData,
        ] as $jenisLabel => $d) {
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
                'jenis'         => 'Asesmen',
                'target'        => $kiData['target'],
                'potongan'      => $kiData['potongan'] ?? 0,
                'terbawa'       => $kiData['terbawa'],
                'total_tagihan' => $kiData['total_tagihan'],
                'terbayar'      => $kiData['terbayar'],
                'sisa'          => $kiData['sisa'],
                'status'        => $kiData['status'],
            ],
            [
                'no'            => 5,
                'jenis'         => 'Ekstrakurikuler',
                'target'        => $ekskulData['target'],
                'potongan'      => $ekskulData['potongan'] ?? 0,
                'terbawa'       => $ekskulData['terbawa'],
                'total_tagihan' => $ekskulData['total_tagihan'],
                'terbayar'      => $ekskulData['terbayar'],
                'sisa'          => $ekskulData['sisa'],
                'status'        => $ekskulData['status'],
            ],
            [
                'no'            => 6,
                'jenis'         => 'Kokurikuler',
                'target'        => $kokurikulerData['target'],
                'potongan'      => $kokurikulerData['potongan'] ?? 0,
                'terbawa'       => $kokurikulerData['terbawa'],
                'total_tagihan' => $kokurikulerData['total_tagihan'],
                'terbayar'      => $kokurikulerData['terbayar'],
                'sisa'          => $kokurikulerData['sisa'],
                'status'        => $kokurikulerData['status'],
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
            'ekskulData'        => $ekskulData,
            'kokurikulerData'   => $kokurikulerData,
            'terbawaSummary'       => $terbawaSummary,
            'totalTerbawaSemua'    => $totalTerbawaSemua,
            'totalTerbayarTerbawa' => $totalTerbayarTerbawa,
            'totalSisaTerbawa'     => $totalSisaTerbawa,
        ];
    }
}