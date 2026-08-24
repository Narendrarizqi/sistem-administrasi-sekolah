<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('pembayaran', 'tahun_ajaran_id')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->foreignId('tahun_ajaran_id')
                    ->nullable()
                    ->after('jenis_id')
                    ->constrained('tahun_ajaran')
                    ->nullOnDelete();
            });
        }

        // Backfill data pembayaran
        $tahunAktif = DB::table('tahun_ajaran')->where('is_active', true)->first();
        if (!$tahunAktif) {
            $tahunAktif = DB::table('tahun_ajaran')->first();
        }

        $allPembayaran = DB::table('pembayaran')->get();
        foreach ($allPembayaran as $p) {
            $taId = null;
            $taNama = $p->tahun_ajaran;

            if ($taNama) {
                $ta = DB::table('tahun_ajaran')->where('nama', $taNama)->first();
                if (!$ta) {
                    $taId = DB::table('tahun_ajaran')->insertGetId([
                        'nama' => $taNama,
                        'is_active' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $taId = $ta->id;
                }
            } elseif ($tahunAktif) {
                $taId = $tahunAktif->id;
                $taNama = $tahunAktif->nama;
            }

            DB::table('pembayaran')->where('id', $p->id)->update([
                'tahun_ajaran_id' => $taId,
                'tahun_ajaran' => $taNama,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pembayaran', 'tahun_ajaran_id')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->dropForeign(['tahun_ajaran_id']);
                $table->dropColumn('tahun_ajaran_id');
            });
        }
    }
};
