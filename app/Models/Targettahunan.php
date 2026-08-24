<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TargetTahunan extends Model
{
    protected $table = 'target_tahunan';

    protected $fillable = [
        'tahun_ajaran',
        'jenis_id',
        'target',
    ];

    public function jenisPembayaran(): BelongsTo
    {
        return $this->belongsTo(JenisPembayaran::class, 'jenis_id');
    }
}