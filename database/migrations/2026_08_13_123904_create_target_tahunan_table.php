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
        Schema::create('target_tahunan', function (Blueprint $table) {

            $table->id();

            $table->string('tahun_ajaran', 9);

            $table->foreignId('jenis_id')
                  ->constrained('jenis_pembayaran')
                  ->cascadeOnDelete();

            $table->decimal('target', 14, 2)->default(0);

            $table->timestamps();

            // 1 jenis pembayaran hanya boleh punya 1 target per tahun ajaran
            $table->unique(['tahun_ajaran', 'jenis_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_tahunan');
    }
};