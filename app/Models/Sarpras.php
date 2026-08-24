<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sarpras extends Model
{
    protected $table = 'sarpras';

    protected $fillable = [
        'siswa_id',
        'target',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function detailPembayaran()
    {
        return $this->hasMany(DetailSarpras::class);
    }
}