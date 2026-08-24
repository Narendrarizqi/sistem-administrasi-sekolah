<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tahun_ajaran')) {
            Schema::create('tahun_ajaran', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 20)->unique();
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        // Seed default tahun ajaran jika tabel masih kosong
        if (DB::table('tahun_ajaran')->count() === 0) {
            $now = now();
            $currentYear = (int) $now->format('Y');
            $currentMonth = (int) $now->format('m');
            
            $startYear = $currentMonth >= 7 ? $currentYear : $currentYear - 1;
            $endYear = $startYear + 1;
            $namaAktif = "{$startYear}/{$endYear}";

            // Insert tahun lalu, tahun sekarang (aktif), dan tahun depan
            $tahunList = [
                [
                    'nama' => ($startYear - 1) . '/' . $startYear,
                    'tanggal_mulai' => ($startYear - 1) . '-07-01',
                    'tanggal_selesai' => $startYear . '-06-30',
                    'is_active' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'nama' => $namaAktif,
                    'tanggal_mulai' => "{$startYear}-07-01",
                    'tanggal_selesai' => "{$endYear}-06-30",
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'nama' => ($startYear + 1) . '/' . ($startYear + 2),
                    'tanggal_mulai' => ($startYear + 1) . '-07-01',
                    'tanggal_selesai' => ($startYear + 2) . '-06-30',
                    'is_active' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ];

            DB::table('tahun_ajaran')->insert($tahunList);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
