<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPembayaran;

class JenisPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'IPP',
                'target' => 600000
            ],
            [
                'nama' => 'DU',
                'target' => 728000
            ],
            [
                'nama' => 'SARPAS',
                'target' => 0
            ],
            [
                'nama' => 'KI',
                'target' => 1000000
            ]
        ];

        foreach ($data as $item) {
            JenisPembayaran::create($item);
        }
    }
}