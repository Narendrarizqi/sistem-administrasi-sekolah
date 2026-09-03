<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua Tahun Ajaran
        $daftarTahunAjaran = TahunAjaran::orderByDesc('nama')->get();

        // 2. Tentukan Tahun Ajaran terpilih
        $selectedId = $request->query('tahun_ajaran_id');
        $selectedNama = $request->query('tahun_ajaran');

        if ($selectedId) {
            $selectedTa = $daftarTahunAjaran->firstWhere('id', $selectedId);
        } elseif ($selectedNama) {
            $selectedTa = $daftarTahunAjaran->firstWhere('nama', $selectedNama);
        } else {
            $selectedTa = $daftarTahunAjaran->firstWhere('is_active', true) ?? $daftarTahunAjaran->first();
        }

        // Jika belum ada record tahun_ajaran sama sekali
        if (!$selectedTa && $daftarTahunAjaran->isEmpty()) {
            $currentYear = (int) date('Y');
            $start = date('n') >= 7 ? $currentYear : $currentYear - 1;
            $namaDefault = $start . '/' . ($start + 1);

            $selectedTa = TahunAjaran::create([
                'nama' => $namaDefault,
                'tanggal_mulai' => "{$start}-07-01",
                'tanggal_selesai' => ($start + 1) . "-06-30",
                'is_active' => true,
            ]);
            $daftarTahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        }

        $tahunAjaranId = $selectedTa->id;
        $tahunAjaranNama = $selectedTa->nama;
        $startYearSelected = (int) explode('/', $tahunAjaranNama)[0];

        // Tahun Sebelumnya (untuk label keterangan terbawa)
        $tahunLaluNama = ($startYearSelected - 1) . '/' . $startYearSelected;

        // 3. Jenis Pembayaran
        $jenisList = JenisPembayaran::orderBy('nama')->get();

        // 4. Target Tahun Ini (Tagihan Baru pada tahun berjalan: target)
        $pembayaranQuery = Pembayaran::where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
            $q->where('tahun_ajaran_id', $tahunAjaranId)
              ->orWhere('tahun_ajaran', $tahunAjaranNama);
        });

        $targetDitetapkan = (float) (clone $pembayaranQuery)->sum('target');
        $totalTerbawaAwal = (float) (clone $pembayaranQuery)->sum('belum_lunas');
        $totalPotongan = (float) (clone $pembayaranQuery)
            ->with('detailPembayaran')
            ->get()
            ->sum(fn ($pembayaran) => $pembayaran->totalPotongan());

        // Breakdown per Jenis Pembayaran untuk tahun berjalan
        $breakdownJenis = [];
        foreach ($jenisList as $jenis) {
            $pembayaranJenis = Pembayaran::where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                  ->orWhere('tahun_ajaran', $tahunAjaranNama);
            })->where('jenis_id', $jenis->id)->get();

            $targetPerJenis = (float) $pembayaranJenis->sum(fn ($p) => $p->totalTagihan());
            $dibayarPerJenis = (float) DetailPembayaran::whereIn('pembayaran_id', $pembayaranJenis->pluck('id'))->sum('nominal');

            $breakdownJenis[] = [
                'id' => $jenis->id,
                'nama' => $jenis->nama,
                'target' => $targetPerJenis,
                'dibayar' => $dibayarPerJenis,
            ];
        }

        // 5. Sisa Belum Lunas Tahun Lalu (Terbawa Tahun Lalu)
        $carryoverRows = Pembayaran::with(['siswa', 'jenisPembayaran', 'detailPembayaran'])
            ->where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                  ->orWhere('tahun_ajaran', $tahunAjaranNama);
            })
            ->where('belum_lunas', '>', 0)
            ->get();

        $siswaTerbawaGrouped = [];
        $totalTerbawaSisa = 0;

        foreach ($carryoverRows as $row) {
            $dibayarRow = (float) $row->detailPembayaran->sum('nominal');
            // Pembayaran melunasi terbawa terlebih dahulu
            $sisaTerbawaRow = max((float) $row->belum_lunas - $dibayarRow, 0);

            if ($sisaTerbawaRow > 0) {
                $totalTerbawaSisa += $sisaTerbawaRow;

                if ($row->siswa) {
                    $sId = $row->siswa->id;
                    if (!isset($siswaTerbawaGrouped[$sId])) {
                        $siswaTerbawaGrouped[$sId] = [
                            'id' => $sId,
                            'nis' => $row->siswa->nis,
                            'nama' => $row->siswa->nama,
                            'kelas' => $row->siswa->kelasPadaTahun($tahunAjaranId),
                            'tahun_asal' => $tahunLaluNama,
                            'tagihan' => [],
                            'total' => 0,
                        ];
                    }

                    $siswaTerbawaGrouped[$sId]['tagihan'][] = [
                        'jenis' => $row->jenisPembayaran ? $row->jenisPembayaran->nama : 'Tagihan',
                        'nominal' => $sisaTerbawaRow,
                    ];
                    $siswaTerbawaGrouped[$sId]['total'] += $sisaTerbawaRow;
                }
            }
        }

        $siswaTerbawa = collect(array_values($siswaTerbawaGrouped));
        $jumlahSiswaTerbawa = $siswaTerbawa->count();

        // 6. Total Siswa Aktif pada Tahun Ajaran Terpilih
        $totalSiswa = Siswa::whereHas('tahunAjaran', function ($q) use ($tahunAjaranId) {
            $q->where('tahun_ajaran_id', $tahunAjaranId);
        })->count();

        if ($totalSiswa === 0) {
            $totalSiswa = Pembayaran::where(function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
                $q->where('tahun_ajaran_id', $tahunAjaranId)
                  ->orWhere('tahun_ajaran', $tahunAjaranNama);
            })->distinct('siswa_id')->count('siswa_id');

            if ($totalSiswa === 0 && $jumlahSiswaTerbawa > 0) {
                $totalSiswa = $jumlahSiswaTerbawa;
            } elseif ($totalSiswa === 0) {
                $totalSiswa = Siswa::count();
            }
        }

        // 7. Target Efektif (Total Kewajiban) = Target Ditetapkan + Total Terbawa
        $targetEfektif = max($targetDitetapkan + $totalTerbawaAwal - $totalPotongan, 0);

        // 8. Sudah Dibayar pada Tahun Ajaran Ini
        $sudahDibayar = (float) DetailPembayaran::whereHas('pembayaran', function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
            $q->where('tahun_ajaran_id', $tahunAjaranId)
              ->orWhere('tahun_ajaran', $tahunAjaranNama);
        })->sum('nominal');

        // 9. Sisa Target
        $sisaTarget = max($targetEfektif - $sudahDibayar, 0);

        // 10. Persentase
        $persenDibayar = $targetEfektif > 0 ? round(($sudahDibayar / $targetEfektif) * 100, 2) : 0;
        $persenSisa = $targetEfektif > 0 ? round(max(100 - $persenDibayar, 0), 2) : 0;

        // 11. Pembayaran Terbaru pada Tahun Ajaran ini
        $pembayaranTerbaru = DetailPembayaran::whereHas('pembayaran', function ($q) use ($tahunAjaranId, $tahunAjaranNama) {
            $q->where('tahun_ajaran_id', $tahunAjaranId)
              ->orWhere('tahun_ajaran', $tahunAjaranNama);
        })
        ->with(['pembayaran.siswa', 'pembayaran.jenisPembayaran', 'pembayaran.tahunAjaran', 'pembayaran.detailPembayaran'])
        ->latest('tanggal')
        ->latest('id')
        ->take(10)
        ->get();

        $totalTerbawa = $totalTerbawaSisa;

        return view('dashboard.index', compact(
            'daftarTahunAjaran',
            'selectedTa',
            'tahunAjaranId',
            'tahunAjaranNama',
            'tahunLaluNama',
            'totalSiswa',
            'targetDitetapkan',
            'totalPotongan',
            'totalTerbawa',
            'targetEfektif',
            'sudahDibayar',
            'sisaTarget',
            'persenDibayar',
            'persenSisa',
            'breakdownJenis',
            'siswaTerbawa',
            'jumlahSiswaTerbawa',
            'pembayaranTerbaru'
        ));
    }
}