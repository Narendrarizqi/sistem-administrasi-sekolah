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
            $table->dropColumn(['target_uts', 'target_uas', 'target_ujian']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('target_uts', 15, 2)->nullable();
            $table->decimal('target_uas', 15, 2)->nullable();
            $table->decimal('target_ujian', 15, 2)->nullable();
        });
    }
};
