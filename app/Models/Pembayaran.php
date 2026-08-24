<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'siswa_id',
        'jenis_id',
        'tahun_ajaran_id',
        'tahun_ajaran',
        'target',
        'status',
        'belum_lunas',
        'carryover_from_pembayaran_id',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPembayaran(): BelongsTo
    {
        return $this->belongsTo(JenisPembayaran::class, 'jenis_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class);
    }

    /**
     * Relasi ke pembayaran asal (untuk tracking histori carryover)
     */
    public function carryoverFrom(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'carryover_from_pembayaran_id');
    }

    /**
     * Total tagihan = target baru (tahun ini) + tagihan terbawa tahun lalu
     */
    public function totalTagihan(): float
    {
        return (float) $this->target + (float) ($this->belum_lunas ?? 0);
    }

    /**
     * Total nominal yang sudah dibayarkan untuk record pembayaran ini
     */
    public function totalTerbayar(): float
    {
        return (float) $this->detailPembayaran()->sum('nominal');
    }

    /**
     * Sisa tagihan keseluruhan yang belum dibayar
     */
    public function sisaTagihan(): float
    {
        return max($this->totalTagihan() - $this->totalTerbayar(), 0);
    }

    /**
     * Sisa tagihan terbawa (pembayaran mengurangi terbawa terlebih dahulu)
     */
    public function sisaTerbawa(): float
    {
        $terbayar = $this->totalTerbayar();
        return max((float) ($this->belum_lunas ?? 0) - $terbayar, 0);
    }

    /**
     * Sisa target baru tahun ini
     */
    public function sisaTarget(): float
    {
        $terbayar = $this->totalTerbayar();
        $kelebihanTerbawa = max($terbayar - (float) ($this->belum_lunas ?? 0), 0);
        return max((float) $this->target - $kelebihanTerbawa, 0);
    }

    /**
     * Hitung status bulanan dinamis untuk IPP
     * - Jika sudah lunas seluruh tahun: "Lunas"
     * - Jika pembayaran bulan berjalan terpenuhi: "Bulan Ini Lunas"
     * - Jika belum bayar 1 bulan (bulan ini): "Bulan Ini Belum Lunas"
     * - Jika belum bayar 2 bulan atau lebih: "{N} Bulan Belum Lunas"
     */
    public function statusIpp($now = null): array
    {
        $now = $now ? \Carbon\Carbon::parse($now) : \Carbon\Carbon::now();
        $target = (float) $this->target;
        $terbawa = (float) ($this->belum_lunas ?? 0);
        $totalTagihan = $target + $terbawa;
        
        $terbayar = (float) ($this->relationLoaded('detailPembayaran')
            ? $this->detailPembayaran->sum('nominal')
            : $this->detailPembayaran()->sum('nominal'));
            
        $sisa = max($totalTagihan - $terbayar, 0);

        if ($totalTagihan <= 0) {
            return [
                'status_text'     => 'Belum Ada Tagihan',
                'badge_class'     => 'badge-status-neutral',
                'icon'            => 'fas fa-minus-circle',
                'is_lunas'        => false,
                'tunggakan_bulan' => 0,
            ];
        }

        if ($sisa <= 0) {
            return [
                'status_text'     => 'Lunas',
                'badge_class'     => 'badge-status-lunas',
                'icon'            => 'fas fa-check-circle',
                'is_lunas'        => true,
                'tunggakan_bulan' => 0,
            ];
        }

        // Tentukan tanggal awal tahun ajaran (default Juli)
        $taMulai = null;
        if ($this->relationLoaded('tahunAjaran') && $this->tahunAjaran && $this->tahunAjaran->tanggal_mulai) {
            $taMulai = \Carbon\Carbon::parse($this->tahunAjaran->tanggal_mulai)->startOfMonth();
        } elseif (!empty($this->tahun_ajaran)) {
            $parts = explode('/', $this->tahun_ajaran);
            if (isset($parts[0]) && is_numeric($parts[0])) {
                $taMulai = \Carbon\Carbon::createFromDate((int)$parts[0], 7, 1)->startOfMonth();
            }
        }

        if (!$taMulai) {
            $taMulai = \Carbon\Carbon::createFromDate($now->year, 7, 1)->startOfMonth();
            if ($now->month < 7) {
                $taMulai->subYear();
            }
        }

        // Hitung berapa bulan telah berjalan dalam tahun ajaran sampai bulan saat ini
        if ($now->lessThan($taMulai)) {
            $bulanBerjalan = 0;
        } else {
            $diffMonths = ($now->year - $taMulai->year) * 12 + ($now->month - $taMulai->month) + 1;
            $bulanBerjalan = max(1, min(12, $diffMonths));
        }

        // Tarif bulanan = target tahunan / 12
        $tarifBulanan = $target > 0 ? ($target / 12) : 0;
        $terbayarUntukTahunIni = max(0, $terbayar - $terbawa);

        if ($tarifBulanan > 0) {
            $bulanTerbayar = (int) floor($terbayarUntukTahunIni / $tarifBulanan);
        } else {
            $bulanTerbayar = $bulanBerjalan;
        }

        $tunggakanBulan = max(0, $bulanBerjalan - $bulanTerbayar);

        if ($tunggakanBulan == 0) {
            return [
                'status_text'     => 'Bulan Ini Lunas',
                'badge_class'     => 'badge-status-lunas',
                'icon'            => 'fas fa-check-circle',
                'is_lunas'        => true,
                'tunggakan_bulan' => 0,
            ];
        } elseif ($tunggakanBulan == 1) {
            return [
                'status_text'     => 'Bulan Ini Belum Lunas',
                'badge_class'     => 'badge-status-belum',
                'icon'            => 'fas fa-clock',
                'is_lunas'        => false,
                'tunggakan_bulan' => 1,
            ];
        } else {
            return [
                'status_text'     => "{$tunggakanBulan} Bulan Belum Lunas",
                'badge_class'     => 'badge-status-belum',
                'icon'            => 'fas fa-exclamation-circle',
                'is_lunas'        => false,
                'tunggakan_bulan' => $tunggakanBulan,
            ];
        }
    }
}