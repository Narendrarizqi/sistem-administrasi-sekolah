<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom sumber_dana menjadi VARCHAR(50) agar fleksibel mendukung Ekstrakurikuler, Kokurikuler, dll.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `pengeluaran` MODIFY COLUMN `sumber_dana` VARCHAR(50) NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `pengeluaran` MODIFY COLUMN `sumber_dana` ENUM('IPP', 'DU', 'Sarpras', 'KI', 'BOS') NULL");
        }
    }
};
