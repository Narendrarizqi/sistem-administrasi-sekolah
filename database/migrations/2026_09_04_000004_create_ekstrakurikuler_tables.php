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
        // 1. Pastikan jenis_pembayaran memiliki record Ekstrakurikuler
        if (Schema::hasTable('jenis_pembayaran')) {
            $exists = DB::table('jenis_pembayaran')->where('nama', 'Ekstrakurikuler')->exists();
            if (!$exists) {
                DB::table('jenis_pembayaran')->insert([
                    'nama'       => 'Ekstrakurikuler',
                    'target'     => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Tabel Master Jenis Ekstrakurikuler
        if (!Schema::hasTable('jenis_ekstrakurikuler')) {
            Schema::create('jenis_ekstrakurikuler', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100)->unique();
                $table->decimal('nominal_default', 12, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabel Item Tagihan Ekstrakurikuler per Siswa
        if (!Schema::hasTable('item_pembayaran_ekstrakurikuler')) {
            Schema::create('item_pembayaran_ekstrakurikuler', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pembayaran_id');
                $table->unsignedBigInteger('jenis_ekstrakurikuler_id')->nullable();
                $table->string('nama_ekskul', 100);
                $table->decimal('nominal', 12, 2)->default(0);
                $table->timestamps();

                $table->foreign('pembayaran_id')
                      ->references('id')
                      ->on('pembayaran')
                      ->onDelete('cascade');

                $table->foreign('jenis_ekstrakurikuler_id')
                      ->references('id')
                      ->on('jenis_ekstrakurikuler')
                      ->onDelete('set null');

                $table->unique(['pembayaran_id', 'nama_ekskul']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pembayaran_ekstrakurikuler');
        Schema::dropIfExists('jenis_ekstrakurikuler');
    }
};
