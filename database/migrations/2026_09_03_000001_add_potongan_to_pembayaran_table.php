<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pembayaran', 'potongan')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->decimal('potongan', 14, 2)->default(0)->after('target');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pembayaran', 'potongan')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->dropColumn('potongan');
            });
        }
    }
};