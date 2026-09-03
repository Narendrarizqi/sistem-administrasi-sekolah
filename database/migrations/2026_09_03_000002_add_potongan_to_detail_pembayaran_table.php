<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('detail_pembayaran', 'potongan')) {
            Schema::table('detail_pembayaran', function (Blueprint $table) {
                $table->decimal('potongan', 14, 2)->default(0)->after('nominal');
            });
        }

        if (Schema::hasColumn('pembayaran', 'potongan')) {
            DB::table('pembayaran')
                ->where('potongan', '>', 0)
                ->orderBy('id')
                ->eachById(function ($pembayaran) {
                    DB::table('detail_pembayaran')->insert([
                        'pembayaran_id' => $pembayaran->id,
                        'tanggal' => now(),
                        'nominal' => 0,
                        'potongan' => $pembayaran->potongan,
                        'metode' => 'Cash',
                        'keterangan' => 'Migrasi potongan IPP sebelumnya',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    DB::table('pembayaran')->where('id', $pembayaran->id)->update(['potongan' => 0]);
                });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('detail_pembayaran', 'potongan')) {
            Schema::table('detail_pembayaran', function (Blueprint $table) {
                $table->dropColumn('potongan');
            });
        }
    }
};
