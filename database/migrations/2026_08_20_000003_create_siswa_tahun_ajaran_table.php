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
        if (!Schema::hasTable('siswa_tahun_ajaran')) {
            Schema::create('siswa_tahun_ajaran', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
                $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
                $table->string('kelas', 30);
                $table->timestamps();

                $table->unique(['siswa_id', 'tahun_ajaran_id']);
            });
        }

        // Backfill: daftarkan siswa ke tahun ajaran aktif & tahun ajaran dari pembayarannya
        $tahunAktif = DB::table('tahun_ajaran')->where('is_active', true)->first();
        if (!$tahunAktif) {
            $tahunAktif = DB::table('tahun_ajaran')->first();
        }

        $allSiswa = DB::table('siswa')->get();
        foreach ($allSiswa as $s) {
            // Daftarkan di tahun ajaran aktif
            if ($tahunAktif) {
                DB::table('siswa_tahun_ajaran')->updateOrInsert(
                    ['siswa_id' => $s->id, 'tahun_ajaran_id' => $tahunAktif->id],
                    ['kelas' => $s->kelas, 'created_at' => now(), 'updated_at' => now()]
                );
            }

            // Cari jika ada tahun ajaran lain di pembayaran siswa ini
            $taIds = DB::table('pembayaran')
                ->where('siswa_id', $s->id)
                ->whereNotNull('tahun_ajaran_id')
                ->distinct()
                ->pluck('tahun_ajaran_id');

            foreach ($taIds as $taId) {
                DB::table('siswa_tahun_ajaran')->updateOrInsert(
                    ['siswa_id' => $s->id, 'tahun_ajaran_id' => $taId],
                    ['kelas' => $s->kelas, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_tahun_ajaran');
    }
};
