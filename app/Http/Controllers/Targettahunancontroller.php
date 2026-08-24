<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\TargetTahunan;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TargetTahunanController extends Controller
{
    public function index(Request $request)
    {
        $jenisList = JenisPembayaran::orderBy('nama')->get();

        $daftarTahunAjaranModels = TahunAjaran::orderByDesc('nama')->get();
        $daftarTahunAjaran = $daftarTahunAjaranModels->pluck('nama');

        if ($daftarTahunAjaran->isNotEmpty()) {
            TargetTahunan::query()
                ->whereNotIn('tahun_ajaran', $daftarTahunAjaran->all())
                ->delete();
        }

        $tahunAjaran = $request->query(
            'tahun_ajaran',
            $daftarTahunAjaran->first()
        );

        if ($tahunAjaran && !$daftarTahunAjaran->contains($tahunAjaran)) {
            $tahunAjaran = $daftarTahunAjaran->first();
        }

        if ($tahunAjaran) {
            $this->bersihkanTargetTahunan($tahunAjaran);
        }

        $tahunAjaranModel = $tahunAjaran
            ? $daftarTahunAjaranModels->firstWhere('nama', $tahunAjaran)
            : null;

        $tahunAjaranId = $tahunAjaranModel?->id;

        $ringkasan = collect();

        $totalTarget = 0;
        $totalMasuk = 0;
        $totalBelumMasuk = 0;
        $totalTerbawa = 0;
        $totalPengeluaran = 0;
        $totalSaldoTersedia = 0;

        if ($tahunAjaran) {
            [$mulaiTahunAjaran, $akhirTahunAjaran] =
                $this->rentangTahunAjaran($tahunAjaran);

            foreach ($jenisList as $jenis) {
                $pembayaranJenis = Pembayaran::query()
                    ->where(function ($q) use ($tahunAjaranId, $tahunAjaran) {
                        $q->where('tahun_ajaran_id', $tahunAjaranId)
                          ->orWhere('tahun_ajaran', $tahunAjaran);
                    })
                    ->where('jenis_id', $jenis->id)
                    ->with('detailPembayaran')
                    ->get();

                $target = (float) $pembayaranJenis->sum('target');
                $tagihanTerbawa = (float) $pembayaranJenis->sum('belum_lunas');

                $sudahMasuk = 0;
                foreach ($pembayaranJenis as $pembayaran) {
                    $sudahMasuk += (float) $pembayaran->detailPembayaran->sum('nominal');
                }

                $totalKewajiban = $target + $tagihanTerbawa;
                $belumMasuk = max($totalKewajiban - $sudahMasuk, 0);

                $sumberDana = $this->sumberDanaUntukJenis($jenis->nama);
                $pengeluaranJenis = 0;

                if ($sumberDana) {
                    $pengeluaranQuery = Pengeluaran::where(
                        'sumber_dana',
                        $sumberDana
                    );

                    if ($mulaiTahunAjaran && $akhirTahunAjaran) {
                        $pengeluaranQuery->whereBetween(
                            'tanggal',
                            [$mulaiTahunAjaran, $akhirTahunAjaran]
                        );
                    }

                    $pengeluaranJenis = (float) $pengeluaranQuery->sum('nominal');
                }

                $saldoTersedia = $sudahMasuk - $pengeluaranJenis;

                $ringkasan->push([
                    'jenis' => $jenis,
                    'target' => $target,
                    'sudah_masuk' => $sudahMasuk,
                    'belum_masuk' => $belumMasuk,
                    'tagihan_terbawa' => $tagihanTerbawa,
                    'pengeluaran' => $pengeluaranJenis,
                    'saldo_tersedia' => $saldoTersedia,
                ]);

                $totalTarget += $target;
                $totalMasuk += $sudahMasuk;
                $totalBelumMasuk += $belumMasuk;
                $totalTerbawa += $tagihanTerbawa;
                $totalPengeluaran += $pengeluaranJenis;
                $totalSaldoTersedia += $saldoTersedia;
            }
        }

        return view('target-tahunan.index', compact(
            'jenisList',
            'daftarTahunAjaran',
            'tahunAjaran',
            'ringkasan',
            'totalTarget',
            'totalMasuk',
            'totalBelumMasuk',
            'totalTerbawa',
            'totalPengeluaran',
            'totalSaldoTersedia'
        ));
    }

    public function store(Request $request)
    {
        return redirect()
            ->route('target-tahunan.index')
            ->with('success', 'Target tahunan dihitung otomatis dari tagihan.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
        ]);

        $tahunAjaran = $request->input('tahun_ajaran');

        $jumlahTagihan = Pembayaran::query()
            ->where('tahun_ajaran', $tahunAjaran)
            ->count();

        if ($jumlahTagihan > 0) {
            return redirect()
                ->route('target-tahunan.index', ['tahun_ajaran' => $tahunAjaran])
                ->with(
                    'error',
                    'Tahun ajaran tidak dapat dihapus karena masih memiliki data tagihan atau pembayaran. Hapus tagihan terlebih dahulu.'
                );
        }

        TargetTahunan::query()
            ->where('tahun_ajaran', $tahunAjaran)
            ->delete();

        return redirect()
            ->route('target-tahunan.index')
            ->with(
                'success',
                "Tahun ajaran {$tahunAjaran} berhasil dihapus."
            );
    }

    private function bersihkanTargetTahunan(string $tahunAjaran): void
    {
        $jenisIds = Pembayaran::query()
            ->where('tahun_ajaran', $tahunAjaran)
            ->distinct()
            ->pluck('jenis_id')
            ->toArray();

        TargetTahunan::query()
            ->where('tahun_ajaran', $tahunAjaran)
            ->when(!empty($jenisIds), function ($q) use ($jenisIds) {
                $q->whereNotIn('jenis_id', $jenisIds);
            })
            ->delete();
    }

    private function rentangTahunAjaran(?string $tahunAjaran): array
    {
        if (
            !$tahunAjaran ||
            !preg_match('/^(\d{4})\/(\d{4})$/', $tahunAjaran, $m)
        ) {
            return [null, null];
        }

        $awal = Carbon::createFromDate(
            (int) $m[1],
            7,
            1
        )->startOfDay();

        $akhir = Carbon::createFromDate(
            (int) $m[2],
            6,
            30
        )->endOfDay();

        return [$awal, $akhir];
    }

    private function sumberDanaUntukJenis(string $namaJenis): ?string
    {
        $key = strtoupper(trim($namaJenis));

        $map = [
            'IPP' => 'IPP',
            'DU' => 'DU',
            'DAFTAR ULANG' => 'DU',
            'SARPAS' => 'Sarpras',
            'SARPRAS' => 'Sarpras',
            'SARANA DAN PRASARANA' => 'Sarpras',
            'SARANA & PRASARANA' => 'Sarpras',
            'KI' => 'KI',
            'KEGIATAN INTRAKURIKULER' => 'KI',
        ];

        return $map[$key] ?? null;
    }
}