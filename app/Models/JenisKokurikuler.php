<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisKokurikuler extends Model
{
    protected $table = 'jenis_kokurikuler';

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
        return $this->hasMany(ItemPembayaranKokurikuler::class, 'jenis_kokurikuler_id');
    }

    /**
     * Hitung berapa kali kegiatan kokurikuler ini sudah dipakai dalam tagihan siswa
     */
    public function getJumlahPenggunaanAttribute(): int
    {
        return $this->items()->count();
    }
}
