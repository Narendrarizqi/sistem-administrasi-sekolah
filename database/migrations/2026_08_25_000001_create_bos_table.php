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
        Schema::create('bos', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_anggaran', 20)->default(date('Y'));
            $table->unsignedBigInteger('tahun_ajaran_id')->nullable();
            $table->string('tahap', 20); // 'Tahap 1', 'Tahap 2'
            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('tahun_ajaran_id')
                ->references('id')
                ->on('tahun_ajaran')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bos');
    }
};
