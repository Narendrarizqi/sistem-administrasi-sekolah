<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pembayaran', 'carryover_from_pembayaran_id')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->unsignedBigInteger('carryover_from_pembayaran_id')
                    ->nullable()
                    ->after('belum_lunas');

                $table->foreign('carryover_from_pembayaran_id')
                    ->references('id')
                    ->on('pembayaran')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pembayaran', 'carryover_from_pembayaran_id')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->dropForeign(['carryover_from_pembayaran_id']);
                $table->dropColumn('carryover_from_pembayaran_id');
            });
        }
    }
};
