<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\Pembayaran;
use App\Models\TahunAjaran;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    /**
     * Dapatkan Tahun Ajaran yang sedang aktif
     */
    protected function getTahunAjaranAktif(): ?TahunAjaran
    {
        return TahunAjaran::where('is_active', true)->first()
            ?? TahunAjaran::orderByDesc('nama')->first();
    }

    /**
     * Dapatkan nama Tahun Ajaran saat ini (string)
     */
    protected function tahunAjaranSaatIni(): string
    {
        $ta = $this->getTahunAjaranAktif();
        if ($ta) {
            return $ta->nama;
        }

        $now = now();
        $startYear = $now->month >= 7 ? $now->year : $now->year - 1;
        return $startYear . '/' . ($startYear + 1);
    }

    /**
     * Dapatkan nama Tahun Ajaran berikutnya (string)
     */
    protected function tahunAjaranBerikutnya(string $tahunLama): string
    {
        if (preg_match('/^(\d{4})\/(\d{4})$/', $tahunLama, $matches)) {
            $nextStart = (int) $matches[2];
            return $nextStart . '/' . ($nextStart + 1);
        }

        $year = (int) date('Y');
        return $year . '/' . ($year + 1);
    }

    /**
     * Menyalurkan pembayaran dengan prioritas:
     * - Pembayaran otomatis melunasi tagihan terbawa tahun lalu terlebih dahulu
     * - Sisa pembayaran setelah terbawa lunas akan mengurangi target tahun berjalan
     */
    protected function prosesPembayaranPrioritas(
        Pembayaran $dipilih,
        float $nominal,
        ?string $metode = null,
        ?string $keterangan = null,
        ?string $bukti = null
    ) {
        $terbayar = (float) $dipilih->detailPembayaran()->sum('nominal');
        $totalTagihan = $dipilih->totalTagihan();
        $sisaTagihan = max($totalTagihan - $terbayar, 0);

        if ($sisaTagihan <= 0) {
            throw ValidationException::withMessages([
                'nominal' => 'Tagihan ini sudah lunas.',
            ]);
        }

        if ($nominal > $sisaTagihan) {
            throw ValidationException::withMessages([
                'nominal' => 'Nominal pembayaran (Rp ' . number_format($nominal, 0, ',', '.') . ') melebihi sisa tagihan (Rp ' . number_format($sisaTagihan, 0, ',', '.') . ').',
            ]);
        }

        $detail = DetailPembayaran::create([
            'pembayaran_id' => $dipilih->id,
            'tanggal'       => now(),
            'nominal'       => $nominal,
            'metode'        => $metode ?? 'Cash',
            'keterangan'    => $keterangan,
            'bukti'         => $bukti,
        ]);

        $totalTerbayarBaru = $terbayar + $nominal;
        $dipilih->status = ($totalTerbayarBaru >= $totalTagihan)
            ? 'Lunas'
            : 'Belum Lunas';
        $dipilih->save();

        return $detail;
    }
}