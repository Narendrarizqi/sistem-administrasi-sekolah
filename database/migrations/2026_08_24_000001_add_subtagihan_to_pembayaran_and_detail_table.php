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
        Schema::table('pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran', 'target_uts')) {
                $table->decimal('target_uts', 12, 2)->default(0)->after('target');
            }
            if (!Schema::hasColumn('pembayaran', 'target_uas')) {
                $table->decimal('target_uas', 12, 2)->default(0)->after('target_uts');
            }
            if (!Schema::hasColumn('pembayaran', 'target_ujian')) {
                $table->decimal('target_ujian', 12, 2)->default(0)->after('target_uas');
            }
        });

        Schema::table('detail_pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_pembayaran', 'kategori')) {
                $table->string('kategori', 50)->nullable()->after('nominal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'target_uts')) {
                $table->dropColumn('target_uts');
            }
            if (Schema::hasColumn('pembayaran', 'target_uas')) {
                $table->dropColumn('target_uas');
            }
            if (Schema::hasColumn('pembayaran', 'target_ujian')) {
                $table->dropColumn('target_ujian');
            }
        });

        Schema::table('detail_pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('detail_pembayaran', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }
};
