<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasIndex('siswa', 'siswa_nama_index')) {
                $table->index('nama', 'siswa_nama_index');
            }
            if (!Schema::hasIndex('siswa', 'siswa_kelas_index')) {
                $table->index('kelas', 'siswa_kelas_index');
            }
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            if (Schema::hasColumn('pengeluaran', 'tanggal') && !Schema::hasIndex('pengeluaran', 'pengeluaran_tanggal_index')) {
                $table->index('tanggal', 'pengeluaran_tanggal_index');
            }
            if (Schema::hasColumn('pengeluaran', 'kategori') && !Schema::hasIndex('pengeluaran', 'pengeluaran_kategori_index')) {
                $table->index('kategori', 'pengeluaran_kategori_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex('siswa_nama_index');
            $table->dropIndex('siswa_kelas_index');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropIndex('pengeluaran_tanggal_index');
            $table->dropIndex('pengeluaran_kategori_index');
        });
    }
};
