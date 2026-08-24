<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pembayaran', 'belum_lunas')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->decimal('belum_lunas', 14, 2)
                    ->default(0)
                    ->after('target');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pembayaran', 'belum_lunas')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->dropColumn('belum_lunas');
            });
        }
    }
};
