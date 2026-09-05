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
        $hasForeignKey = collect(\Illuminate\Support\Facades\DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'detail_sarpras' AND CONSTRAINT_NAME = 'detail_sarpras_sarpras_id_foreign'"
        ))->isNotEmpty();

        if (!$hasForeignKey) {
            Schema::table('detail_sarpras', function (Blueprint $table) {
                $table->foreign('sarpras_id')->references('id')->on('sarpras')->cascadeOnDelete();
            });
        }
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
