<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama',
        'kelas'
    ];

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function sarpras(): HasMany
    {
        return $this->hasMany(Sarpras::class);
    }

    public function tahunAjaran(): BelongsToMany
    {
        return $this->belongsToMany(TahunAjaran::class, 'siswa_tahun_ajaran', 'siswa_id', 'tahun_ajaran_id')
            ->withPivot('kelas')
            ->withTimestamps();
    }

    /**
     * Dapatkan kelas siswa pada tahun ajaran tertentu
     */
    public function kelasPadaTahun($tahunAjaranId): string
    {
        $pivot = $this->tahunAjaran()->where('tahun_ajaran_id', $tahunAjaranId)->first();
        return $pivot ? $pivot->pivot->kelas : $this->kelas;
    }
}