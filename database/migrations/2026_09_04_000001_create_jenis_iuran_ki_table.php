<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('jenis_iuran_ki')) {
            Schema::create('jenis_iuran_ki', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100)->unique();
                $table->decimal('nominal_default', 12, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });

            // Seed jenis iuran standar awal
            $now = now();
            $initialData = [
                ['nama' => 'STS Gasal',   'nominal_default' => 0, 'is_active' => true,  'keterangan' => 'Sumatif Tengah Semester Gasal'],
                ['nama' => 'STS Genap',   'nominal_default' => 0, 'is_active' => true,  'keterangan' => 'Sumatif Tengah Semester Genap'],
                ['nama' => 'SAS',         'nominal_default' => 0, 'is_active' => true,  'keterangan' => 'Sumatif Akhir Semester'],
                ['nama' => 'SAT',         'nominal_default' => 0, 'is_active' => true,  'keterangan' => 'Sumatif Akhir Tahun'],
                ['nama' => 'ASAJ',        'nominal_default' => 0, 'is_active' => true,  'keterangan' => 'Asesmen Sumatif Akhir Jenjang'],
                ['nama' => 'Prakerin',    'nominal_default' => 0, 'is_active' => true,  'keterangan' => 'Praktik Kerja Industri'],
                ['nama' => 'UTS',         'nominal_default' => 0, 'is_active' => false, 'keterangan' => 'Legacy UTS (Nonaktif)'],
                ['nama' => 'UAS',         'nominal_default' => 0, 'is_active' => false, 'keterangan' => 'Legacy UAS (Nonaktif)'],
                ['nama' => 'Ujian',       'nominal_default' => 0, 'is_active' => false, 'keterangan' => 'Legacy Ujian (Nonaktif)'],
            ];

            foreach ($initialData as $item) {
                DB::table('jenis_iuran_ki')->insert(array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_iuran_ki');
    }
};
