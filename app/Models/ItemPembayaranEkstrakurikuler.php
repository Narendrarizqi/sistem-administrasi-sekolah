<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPembayaranEkstrakurikuler extends Model
{
    protected $table = 'item_pembayaran_ekstrakurikuler';

    protected $fillable = [
        'pembayaran_id',
        'jenis_ekstrakurikuler_id',
        'nama_ekskul',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'float',
    ];

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    public function jenisEkstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(JenisEkstrakurikuler::class, 'jenis_ekstrakurikuler_id');
    }

    /**
     * Hitung nominal yang sudah dibayar untuk item ekstrakurikuler ini
     */
    public function getTerbayarAttribute(): float
    {
        if (!$this->pembayaran) {
            return 0;
        }

        if ($this->pembayaran->relationLoaded('detailPembayaran')) {
            return (float) $this->pembayaran->detailPembayaran
                ->where('kategori', $this->nama_ekskul)
                ->sum('nominal');
        }

        return (float) $this->pembayaran->detailPembayaran()
            ->where('kategori', $this->nama_ekskul)
            ->sum('nominal');
    }

    /**
     * Sisa tagihan untuk item ekstrakurikuler ini
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
