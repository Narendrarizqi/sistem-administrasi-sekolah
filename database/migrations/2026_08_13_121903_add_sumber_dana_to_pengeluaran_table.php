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
        Schema::table('pengeluaran', function (Blueprint $table) {
            // Nullable: data pengeluaran lama yang belum punya sumber dana
            // tetap aman, tidak error.
            $table->enum('sumber_dana', ['IPP', 'DU', 'Sarpras', 'KI'])
                ->nullable()
                ->after('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('sumber_dana');
        });
    }
};