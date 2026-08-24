<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pembayaran', 'tahun_ajaran')) {
            return;
        }

        DB::table('pembayaran')
            ->whereNull('tahun_ajaran')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $tanggal = $row->created_at
                        ? new DateTime($row->created_at)
                        : new DateTime();

                    $tahunAwal = (int) $tanggal->format('m') >= 7
                        ? (int) $tanggal->format('Y')
                        : (int) $tanggal->format('Y') - 1;

                    DB::table('pembayaran')
                        ->where('id', $row->id)
                        ->update([
                            'tahun_ajaran' => $tahunAwal . '/' . ($tahunAwal + 1),
                        ]);
                }
            });
    }

    public function down(): void
    {
        // Data historis tidak dihapus saat rollback.
    }
};