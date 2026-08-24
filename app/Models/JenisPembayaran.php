<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPembayaran extends Model
{
    protected $table = 'jenis_pembayaran';

    protected $fillable = [
        'nama',
        'target'
    ];

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'jenis_id');
    }
}