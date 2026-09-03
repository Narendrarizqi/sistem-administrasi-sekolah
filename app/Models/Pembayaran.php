<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'siswa_id',
        'jenis_id',
        'tahun_ajaran_id',
        'tahun_ajaran',
        'target',
        'potongan',
        'target_uts',
        'target_uas',
        'target_ujian',
        'status',
        'belum_lunas',
        'carryover_from_pembayaran_id',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPembayaran(): BelongsTo
    {
        return $this->belongsTo(JenisPembayaran::class, 'jenis_id');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class);
    }

    /**
     * Relasi ke pembayaran asal (untuk tracking histori carryover)
     */
    public function carryoverFrom(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'carryover_from_pembayaran_id');
    }

    /**
     * Total tagihan bersih setelah potongan.
     */
    public function totalTagihan(): float
    {
        return max($this->totalTagihanAwal() - $this->potonganValue(), 0);
    }

    public function totalTagihanAwal(): float
    {
        return (float) $this->target + (float) ($this->belum_lunas ?? 0);
    }

    public function potonganValue(): float
    {
        return (float) ($this->potongan ?? 0);
    }

    public function totalTagihanBersih(): float
    {
        return $this->totalTagihan();
    }

    /**
     * Total nominal yang sudah dibayarkan untuk record pembayaran ini
     */
    public function totalTerbayar(): float
    {
        if ($this->relationLoaded('detailPembayaran')) {
            return (float) $this->detailPembayaran->sum('nominal');
        }

        return (float) $this->detailPembayaran()->sum('nominal');
    }

    /**
     * Sisa tagihan keseluruhan yang belum dibayar
     */
    public function sisaTagihan(): float
    {
        return max($this->totalTagihan() - $this->totalTerbayar(), 0);
    }

    /**
     * Sisa tagihan terbawa (pembayaran mengurangi terbawa terlebih dahulu)
     */
    public function sisaTerbawa(): float
    {
        $terbayar = $this->totalTerbayar();
        $potonganTerbawa = min((float) ($this->belum_lunas ?? 0), $this->potonganValue());
        return max((float) ($this->belum_lunas ?? 0) - $potonganTerbawa - $terbayar, 0);
    }

    /**
     * Sisa target baru tahun ini
     */
    public function sisaTarget(): float
    {
        $terbayar = $this->totalTerbayar();
        $potonganTerbawa = min((float) ($this->belum_lunas ?? 0), $this->potonganValue());
        $terbawaBersih = (float) ($this->belum_lunas ?? 0) - $potonganTerbawa;
        $targetBersih = max((float) $this->target - ($this->potonganValue() - $potonganTerbawa), 0);
        $kelebihanTerbawa = max($terbayar - $terbawaBersih, 0);
        return max($targetBersih - $kelebihanTerbawa, 0);
    }

    /**
     * Hitung status bulanan dinamis untuk IPP
     * - Jika sudah lunas seluruh tahun: "Lunas"
     * - Jika pembayaran bulan berjalan terpenuhi: "Bulan Ini Lunas"
     * - Jika belum bayar 1 bulan (bulan ini): "Bulan Ini Belum Lunas"
     * - Jika belum bayar 2 bulan atau lebih: "{N} Bulan Belum Lunas"
     */
    public function statusIpp($now = null): array
    {
        $now = $now ? \Carbon\Carbon::parse($now) : \Carbon\Carbon::now();
        $potonganTerbawa = min((float) ($this->belum_lunas ?? 0), $this->potonganValue());
        $terbawa = (float) ($this->belum_lunas ?? 0) - $potonganTerbawa;
        $target = max((float) $this->target - ($this->potonganValue() - $potonganTerbawa), 0);
        $totalTagihan = $target + $terbawa;
        
        $terbayar = (float) ($this->relationLoaded('detailPembayaran')
            ? $this->detailPembayaran->sum('nominal')
            : $this->detailPembayaran()->sum('nominal'));
            
        $sisa = max($totalTagihan - $terbayar, 0);

        if ($totalTagihan <= 0) {
            if ($this->totalTagihanAwal() > 0) {
                return [
                    'status_text'          => 'Lunas',
                    'badge_class'          => 'badge-status-lunas',
                    'icon'                 => 'fas fa-check-circle',
                    'is_lunas'             => true,
                    'tunggakan_bulan'      => 0,
                    'bulan_berjalan'       => 12,
                    'bulan_terbayar'       => 12,
                    'tarif_bulanan'        => 0,
                    'tagihan_bulan_ini'    => 0,
                    'keterangan_bulan_ini' => 'Lunas karena potongan penuh',
                ];
            }

            return [
                'status_text'          => 'Belum Ada Tagihan',
                'badge_class'          => 'badge-status-neutral',
                'icon'                 => 'fas fa-minus-circle',
                'is_lunas'             => false,
                'tunggakan_bulan'      => 0,
                'bulan_berjalan'       => 0,
                'bulan_terbayar'       => 0,
                'tarif_bulanan'        => 0,
                'tagihan_bulan_ini'    => 0,
                'keterangan_bulan_ini' => 'Belum ada tagihan',
            ];
        }

        if ($sisa <= 0) {
            return [
                'status_text'          => 'Lunas',
                'badge_class'          => 'badge-status-lunas',
                'icon'                 => 'fas fa-check-circle',
                'is_lunas'             => true,
                'tunggakan_bulan'      => 0,
                'bulan_berjalan'       => 12,
                'bulan_terbayar'       => 12,
                'tarif_bulanan'        => $target > 0 ? ($target / 12) : 0,
                'tagihan_bulan_ini'    => 0,
                'keterangan_bulan_ini' => 'Sudah lunas penuh 1 tahun',
            ];
        }

        // Tentukan tanggal awal tahun ajaran (default Juli)
        $taMulai = null;
        if ($this->relationLoaded('tahunAjaran') && $this->tahunAjaran && $this->tahunAjaran->tanggal_mulai) {
            $taMulai = \Carbon\Carbon::parse($this->tahunAjaran->tanggal_mulai)->startOfMonth();
        } elseif (!empty($this->tahun_ajaran)) {
            $parts = explode('/', $this->tahun_ajaran);
            if (isset($parts[0]) && is_numeric($parts[0])) {
                $taMulai = \Carbon\Carbon::createFromDate((int)$parts[0], 7, 1)->startOfMonth();
            }
        }

        if (!$taMulai) {
            $taMulai = \Carbon\Carbon::createFromDate($now->year, 7, 1)->startOfMonth();
            if ($now->month < 7) {
                $taMulai->subYear();
            }
        }

        // Hitung berapa bulan telah berjalan dalam tahun ajaran sampai bulan saat ini
        if ($now->lessThan($taMulai)) {
            $bulanBerjalan = 0;
        } else {
            $diffMonths = ($now->year - $taMulai->year) * 12 + ($now->month - $taMulai->month) + 1;
            $bulanBerjalan = max(1, min(12, $diffMonths));
        }

        // Tarif bulanan = target tahunan / 12
        $tarifBulanan = $target > 0 ? ($target / 12) : 0;
        $terbayarUntukTahunIni = max(0, $terbayar - $terbawa);

        if ($tarifBulanan > 0) {
            $bulanTerbayar = (int) floor($terbayarUntukTahunIni / $tarifBulanan);
        } else {
            $bulanTerbayar = $bulanBerjalan;
        }

        $tunggakanBulan = max(0, $bulanBerjalan - $bulanTerbayar);
        $kewajibanSdSekarang = ($bulanBerjalan * $tarifBulanan) + $terbawa;
        $tagihanBulanIni = max(0, min($sisa, $kewajibanSdSekarang - $terbayar));
        
        $bulanIndoList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = ($bulanIndoList[(int)$now->format('n')] ?? 'Agustus') . ' ' . $now->format('Y');

        if ($tunggakanBulan == 0) {
            return [
                'status_text'          => 'Bulan Ini Lunas',
                'badge_class'          => 'badge-status-lunas',
                'icon'                 => 'fas fa-check-circle',
                'is_lunas'             => true,
                'tunggakan_bulan'      => 0,
                'bulan_berjalan'       => $bulanBerjalan,
                'bulan_terbayar'       => $bulanTerbayar,
                'tarif_bulanan'        => $tarifBulanan,
                'tagihan_bulan_ini'    => $tagihanBulanIni,
                'keterangan_bulan_ini' => "Bulan {$namaBulan} sudah Lunas",
            ];
        } elseif ($tunggakanBulan == 1) {
            return [
                'status_text'          => 'Bulan Ini Belum Lunas',
                'badge_class'          => 'badge-status-belum',
                'icon'                 => 'fas fa-clock',
                'is_lunas'             => false,
                'tunggakan_bulan'      => 1,
                'bulan_berjalan'       => $bulanBerjalan,
                'bulan_terbayar'       => $bulanTerbayar,
                'tarif_bulanan'        => $tarifBulanan,
                'tagihan_bulan_ini'    => $tagihanBulanIni,
                'keterangan_bulan_ini' => 'Rp ' . number_format($tagihanBulanIni, 0, ',', '.') . " (Bulan {$namaBulan})",
            ];
        } else {
            return [
                'status_text'          => "{$tunggakanBulan} Bulan Belum Lunas",
                'badge_class'          => 'badge-status-belum',
                'icon'                 => 'fas fa-exclamation-circle',
                'is_lunas'             => false,
                'tunggakan_bulan'      => $tunggakanBulan,
                'bulan_berjalan'       => $bulanBerjalan,
                'bulan_terbayar'       => $bulanTerbayar,
                'tarif_bulanan'        => $tarifBulanan,
                'tagihan_bulan_ini'    => $tagihanBulanIni,
                'keterangan_bulan_ini' => 'Rp ' . number_format($tagihanBulanIni, 0, ',', '.') . " ({$tunggakanBulan} bulan s/d {$namaBulan})",
            ];
        }
    }

    /**
     * Accessor untuk target_uts: jika target > 0 dan subtagihan belum diset, bagi rata
     */
    public function getTargetUtsAttribute($value): float
    {
        $val = (float) ($value ?? 0);
        if ($val == 0 && (float) ($this->attributes['target_uas'] ?? 0) == 0 && (float) ($this->attributes['target_ujian'] ?? 0) == 0 && (float) ($this->attributes['target'] ?? 0) > 0) {
            return round((float) $this->attributes['target'] / 3, 2);
        }
        return $val;
    }

    public function getTargetUasAttribute($value): float
    {
        $val = (float) ($value ?? 0);
        if ($val == 0 && (float) ($this->attributes['target_uts'] ?? 0) == 0 && (float) ($this->attributes['target_ujian'] ?? 0) == 0 && (float) ($this->attributes['target'] ?? 0) > 0) {
            return round((float) $this->attributes['target'] / 3, 2);
        }
        return $val;
    }

    public function getTargetUjianAttribute($value): float
    {
        $val = (float) ($value ?? 0);
        if ($val == 0 && (float) ($this->attributes['target_uts'] ?? 0) == 0 && (float) ($this->attributes['target_uas'] ?? 0) == 0 && (float) ($this->attributes['target'] ?? 0) > 0) {
            $uts = round((float) $this->attributes['target'] / 3, 2);
            return (float) $this->attributes['target'] - ($uts * 2);
        }
        return $val;
    }

    /**
     * Hitung nominal terbayar untuk sub-kategori KI (UTS, UAS, Ujian)
     */
    public function terbayarKiKategori(string $kategori): float
    {
        if ($this->relationLoaded('detailPembayaran')) {
            return (float) $this->detailPembayaran->where('kategori', $kategori)->sum('nominal');
        }
        return (float) $this->detailPembayaran()->where('kategori', $kategori)->sum('nominal');
    }

    /**
     * Sisa tagihan per sub-kategori KI (UTS, UAS, Ujian)
     */
    public function sisaKiKategori(string $kategori): float
    {
        $target = 0;
        if ($kategori === 'UTS') {
            $target = (float) $this->target_uts;
        } elseif ($kategori === 'UAS') {
            $target = (float) $this->target_uas;
        } elseif ($kategori === 'Ujian') {
            $target = (float) $this->target_ujian;
        }
        $terbayar = $this->terbayarKiKategori($kategori);
        return max($target - $terbayar, 0);
    }

    /**
     * Status 3 sub-tagihan KI (UTS, UAS, Ujian)
     */
    public function statusKiSubtagihan(): array
    {
        $kategoriList = ['UTS', 'UAS', 'Ujian'];
        $results = [];

        foreach ($kategoriList as $kat) {
            $target = 0;
            if ($kat === 'UTS') {
                $target = (float) $this->target_uts;
            } elseif ($kat === 'UAS') {
                $target = (float) $this->target_uas;
            } elseif ($kat === 'Ujian') {
                $target = (float) $this->target_ujian;
            }

            $terbayar = $this->terbayarKiKategori($kat);
            $sisa = max($target - $terbayar, 0);
            $isLunas = ($target > 0 && $sisa <= 0);

            $results[$kat] = [
                'kategori'    => $kat,
                'target'      => $target,
                'terbayar'    => $terbayar,
                'sisa'        => $sisa,
                'is_lunas'    => $isLunas,
                'status_text' => $isLunas ? 'Lunas' : 'Belum Lunas',
                'badge_class' => $isLunas ? 'badge-status-lunas' : 'badge-status-belum',
            ];
        }

        return $results;
    }
}