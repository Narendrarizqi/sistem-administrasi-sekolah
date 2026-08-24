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
    Schema::create('pembayaran', function (Blueprint $table) {

        $table->id();

        $table->foreignId('siswa_id')
              ->constrained('siswa')
              ->cascadeOnDelete();

        $table->foreignId('jenis_id')
              ->constrained('jenis_pembayaran')
              ->cascadeOnDelete();

        $table->decimal('target',12,2);

        $table->enum('status',[
            'Belum Lunas',
            'Lunas'
        ])->default('Belum Lunas');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
