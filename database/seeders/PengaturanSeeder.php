<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        if (Pengaturan::count() === 0) {
            Pengaturan::create([
                'nama_sekolah' => 'SMK Muhammadiyah Margasari',
                'nama_sistem' => 'Sistem Rekap Pembayaran',
                'logo' => 'logo.png'
            ]);
        }
    }
}