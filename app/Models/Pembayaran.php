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
}