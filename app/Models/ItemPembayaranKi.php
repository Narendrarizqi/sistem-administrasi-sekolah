<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPembayaranKi extends Model
{
    protected $table = 'item_pembayaran_ki';

    protected $fillable = [
        'pembayaran_id',
        'jenis_iuran_ki_id',
        'nama_iuran',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'float',
    ];

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    public function jenisIuran(): BelongsTo
    {
        return $this->belongsTo(JenisIuranKi::class, 'jenis_iuran_ki_id');
    }

    /**
     * Hitung nominal yang sudah dibayar untuk item ini
     */
    public function getTerbayarAttribute(): float
    {
        if (!$this->pembayaran) {
            return 0;
        }

        if ($this->pembayaran->relationLoaded('detailPembayaran')) {
            return (float) $this->pembayaran->detailPembayaran
                ->where('kategori', $this->nama_iuran)
                ->sum('nominal');
        }

        return (float) $this->pembayaran->detailPembayaran()
            ->where('kategori', $this->nama_iuran)
            ->sum('nominal');
    }

    /**
     * Sisa tagihan untuk item ini
     */
    public function getSisaAttribute(): float
    {
        return max((float) $this->nominal - $this->terbayar, 0);
    }

    /**
     * Status apakah item ini sudah lunas
     */
    public function getIsLunasAttribute(): bool
    {
        return ((float) $this->nominal > 0 && $this->sisa <= 0);
    }

    /**
     * Text status
     */
    public function getStatusTextAttribute(): string
    {
        if ((float) $this->nominal <= 0) {
            return '-';
        }
        if ($this->is_lunas) {
            return 'Lunas';
        }
        if ($this->terbayar > 0) {
            return 'Sebagian';
        }
        return 'Belum Lunas';
    }
}
