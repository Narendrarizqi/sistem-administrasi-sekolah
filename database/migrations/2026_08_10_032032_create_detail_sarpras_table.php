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
        if (!Schema::hasTable('detail_sarpras')) {
            Schema::create('detail_sarpras', function (Blueprint $table) {
                $table->id();

                $table->foreignId('sarpras_id')->constrained('sarpras')->cascadeOnDelete();

                $table->date('tanggal');

                $table->decimal('nominal', 12, 2);

                $table->string('metode')->nullable();

                $table->text('keterangan')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sarpras');
    }
};
