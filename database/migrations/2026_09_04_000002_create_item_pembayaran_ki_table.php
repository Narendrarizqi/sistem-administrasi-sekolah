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
        if (!Schema::hasTable('item_pembayaran_ki')) {
            Schema::create('item_pembayaran_ki', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pembayaran_id');
                $table->unsignedBigInteger('jenis_iuran_ki_id')->nullable();
                $table->string('nama_iuran', 100);
                $table->decimal('nominal', 12, 2)->default(0);
                $table->timestamps();

                $table->foreign('pembayaran_id')
                      ->references('id')
                      ->on('pembayaran')
                      ->onDelete('cascade');

                $table->foreign('jenis_iuran_ki_id')
                      ->references('id')
                      ->on('jenis_iuran_ki')
                      ->onDelete('set null');

                $table->unique(['pembayaran_id', 'nama_iuran']);
            });

            // Backfill data lama jika ada record di pembayaran dengan jenis_id = 4
            $kiJenis = DB::table('jenis_pembayaran')->where('nama', 'KI')->first();
            if ($kiJenis) {
                $existingRows = DB::table('pembayaran')->where('jenis_id', $kiJenis->id)->get();
                $now = now();

                $masterMap = DB::table('jenis_iuran_ki')->pluck('id', 'nama')->toArray();

                foreach ($existingRows as $row) {
                    $uts   = (float) ($row->target_uts ?? 0);
                    $uas   = (float) ($row->target_uas ?? 0);
                    $ujian = (float) ($row->target_ujian ?? 0);
                    $total = (float) ($row->target ?? 0);

                    if ($uts > 0) {
                        DB::table('item_pembayaran_ki')->insertOrIgnore([
                            'pembayaran_id'     => $row->id,
                            'jenis_iuran_ki_id' => $masterMap['UTS'] ?? null,
                            'nama_iuran'        => 'UTS',
                            'nominal'           => $uts,
                            'created_at'        => $now,
                            'updated_at'        => $now,
                        ]);
                    }

                    if ($uas > 0) {
                        DB::table('item_pembayaran_ki')->insertOrIgnore([
                            'pembayaran_id'     => $row->id,
                            'jenis_iuran_ki_id' => $masterMap['UAS'] ?? null,
                            'nama_iuran'        => 'UAS',
                            'nominal'           => $uas,
                            'created_at'        => $now,
                            'updated_at'        => $now,
                        ]);
                    }

                    if ($ujian > 0) {
                        DB::table('item_pembayaran_ki')->insertOrIgnore([
                            'pembayaran_id'     => $row->id,
                            'jenis_iuran_ki_id' => $masterMap['Ujian'] ?? null,
                            'nama_iuran'        => 'Ujian',
                            'nominal'           => $ujian,
                            'created_at'        => $now,
                            'updated_at'        => $now,
                        ]);
                    }

                    // Jika UTS/UAS/Ujian bernilai 0 tapi target > 0
                    if ($uts == 0 && $uas == 0 && $ujian == 0 && $total > 0) {
                        DB::table('item_pembayaran_ki')->insertOrIgnore([
                            'pembayaran_id'     => $row->id,
                            'jenis_iuran_ki_id' => $masterMap['ASAJ'] ?? null,
                            'nama_iuran'        => 'ASAJ',
                            'nominal'           => $total,
                            'created_at'        => $now,
                            'updated_at'        => $now,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pembayaran_ki');
    }
};
