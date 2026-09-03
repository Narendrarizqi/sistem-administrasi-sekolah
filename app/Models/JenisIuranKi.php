<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisIuranKi extends Model
{
    protected $table = 'jenis_iuran_ki';

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
        return $this->hasMany(ItemPembayaranKi::class, 'jenis_iuran_ki_id');
    }

    /**
     * Hitung berapa kali jenis iuran ini sudah dipakai dalam tagihan siswa
     */
    public function getJumlahPenggunaanAttribute(): int
    {
        return $this->items()->count();
    }
}
