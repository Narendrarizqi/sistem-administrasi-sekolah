<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisEkstrakurikuler extends Model
{
    protected $table = 'jenis_ekstrakurikuler';

    protected $fillable = [
        'nama',
        'nominal_default',
        'is_active',
        'keterangan',
    ];

    protected $casts = [
        'nominal_default' => 'float',
        'is_active'       => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ItemPembayaranEkstrakurikuler::class, 'jenis_ekstrakurikuler_id');
    }

    /**
     * Hitung berapa kali ekstrakurikuler ini sudah dipakai dalam tagihan siswa
     */
    public function getJumlahPenggunaanAttribute(): int
    {
        return $this->items()->count();
    }
}
