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
        Schema::table('detail_sarpras', function (Blueprint $table) {
            $table->foreign('sarpras_id')->references('id')->on('sarpras')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_sarpras', function (Blueprint $table) {
            $table->dropForeign(['sarpras_id']);
        });
    }
};
