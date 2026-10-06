<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah nama tabel
        Schema::rename('jenis_iuran_ki', 'jenis_iuran_asesmen');
        
        Schema::table('item_pembayaran_ki', function (Blueprint $table) {
            $table->dropForeign(['jenis_iuran_ki_id']);
        });

        Schema::rename('item_pembayaran_ki', 'item_pembayaran_asesmen');

        Schema::table('item_pembayaran_asesmen', function (Blueprint $table) {
            $table->renameColumn('jenis_iuran_ki_id', 'jenis_iuran_asesmen_id');
            $table->foreign('jenis_iuran_asesmen_id')
                  ->references('id')
                  ->on('jenis_iuran_asesmen')
                  ->onDelete('cascade');
        });

        // 2. Ubah data di jenis_pembayaran
        DB::table('jenis_pembayaran')->where('nama', 'KI')->update(['nama' => 'Asesmen']);
    }

    public function down(): void
    {
        DB::table('jenis_pembayaran')->where('nama', 'Asesmen')->update(['nama' => 'KI']);

        Schema::table('item_pembayaran_asesmen', function (Blueprint $table) {
            $table->dropForeign(['jenis_iuran_asesmen_id']);
            $table->renameColumn('jenis_iuran_asesmen_id', 'jenis_iuran_ki_id');
        });

        Schema::rename('item_pembayaran_asesmen', 'item_pembayaran_ki');
        Schema::rename('jenis_iuran_asesmen', 'jenis_iuran_ki');

        Schema::table('item_pembayaran_ki', function (Blueprint $table) {
            $table->foreign('jenis_iuran_ki_id')
                  ->references('id')
                  ->on('jenis_iuran_ki')
                  ->onDelete('cascade');
        });
    }
};
