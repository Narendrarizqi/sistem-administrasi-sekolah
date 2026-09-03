<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    protected $table='detail_pembayaran';

    protected $fillable = [
        'pembayaran_id',
        'tanggal',
        'nominal',
        'potongan',
        'kategori',
        'metode',
        'keterangan',
        'bukti',
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}