<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSarpras extends Model
{
    protected $table = 'detail_sarpras';

    protected $fillable = [
        'sarpras_id',
        'tanggal',
        'nominal',
        'metode',
        'keterangan',
    ];

    public function sarpras()
    {
        return $this->belongsTo(Sarpras::class);
    }
}