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
    Schema::create('detail_pembayaran', function (Blueprint $table) {

        $table->id();

        $table->foreignId('pembayaran_id')
              ->constrained('pembayaran')
              ->cascadeOnDelete();

        $table->date('tanggal');

        $table->decimal('nominal',12,2);

        $table->enum('metode',[
            'Cash',
            'Transfer'
        ])->default('Cash');

        $table->text('keterangan')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayaran');
    }
};
