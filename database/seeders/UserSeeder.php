<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'kepsek'],
            [
                'name' => 'kepala sekolah',
                'email' => 'kepsek@smkmuhmargasari.sch.id',
                'password' => 'kepsek123',
            ]
        );

        User::updateOrCreate(
            ['username' => 'TU'],
            [
                'name' => 'TU',
                'email' => 'tusmkmuhmargasari@gmail.com',
                'password' => 'tatausaha',
            ]
        );
    }
}
