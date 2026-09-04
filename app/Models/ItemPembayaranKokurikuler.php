<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPembayaranKokurikuler extends Model
{
    protected $table = 'item_pembayaran_kokurikuler';

    protected $fillable = [
        'pembayaran_id',
        'jenis_kokurikuler_id',
        'nama_kegiatan',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'float',
    ];

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    public function jenisKokurikuler(): BelongsTo
    {
        return $this->belongsTo(JenisKokurikuler::class, 'jenis_kokurikuler_id');
    }

    /**
     * Hitung nominal yang sudah dibayar untuk item kokurikuler ini
     */
    public function getTerbayarAttribute(): float
    {
        if (!$this->pembayaran) {
            return 0;
        }

        if ($this->pembayaran->relationLoaded('detailPembayaran')) {
            return (float) $this->pembayaran->detailPembayaran
                ->where('kategori', $this->nama_kegiatan)
                ->sum('nominal');
        }

        return (float) $this->pembayaran->detailPembayaran()
            ->where('kategori', $this->nama_kegiatan)
            ->sum('nominal');
    }

    /**
     * Sisa tagihan untuk item kokurikuler ini
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

    /**
     * Alias nama_kokurikuler -> nama_kegiatan
     */
    public function getNamaKokurikulerAttribute(): string
    {
        return $this->nama_kegiatan ?? '';
    }
}
