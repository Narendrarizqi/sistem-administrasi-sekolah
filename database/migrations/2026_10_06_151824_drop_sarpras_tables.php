<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('detail_sarpras');
        Schema::dropIfExists('sarpras');
    }

    public function down(): void
    {
        // Not necessary to recreate for dropping deprecated tables, but here is a stub
    }
};
