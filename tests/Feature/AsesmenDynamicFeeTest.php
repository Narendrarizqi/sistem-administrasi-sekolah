<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\JenisPembayaran;
use App\Models\TahunAjaran;
use App\Models\Pembayaran;
use App\Models\JenisIuranKi;
use App\Models\ItemPembayaranKi;
use App\Models\DetailPembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::first() ?? User::factory()->create();
    $this->actingAs($this->admin);

    $this->ta = TahunAjaran::firstOrCreate(
        ['nama' => '2026/2027'],
        ['is_active' => true, 'status' => 'Aktif']
    );
    $this->jpKi = JenisPembayaran::firstOrCreate(['nama' => 'KI']);

    $this->siswa1 = Siswa::firstOrCreate(
        ['nis' => 'TEST901'],
        ['nama' => 'Siswa Test Satu', 'kelas' => 'X RPL 1']
    );

    $this->siswa2 = Siswa::firstOrCreate(
        ['nis' => 'TEST902'],
        ['nama' => 'Siswa Test Dua', 'kelas' => 'X RPL 2']
    );
});

test('initial baseline iuran master exists with STS Gasal, STS Genap, SAS, SAT, ASAJ, Prakerin', function () {
    $expected = ['STS Gasal', 'STS Genap', 'SAS', 'SAT', 'ASAJ', 'Prakerin'];
    foreach ($expected as $nama) {
        expect(JenisIuranKi::where('nama', $nama)->where('is_active', true)->exists())->toBeTrue();
    }
});

test('admin can add new jenis iuran dynamically without source code change', function () {
    $response = $this->post(route('ki.jenis-iuran.store'), [
        'nama'            => 'Hawe Test',
        'nominal_default' => 50000,
        'keterangan'      => 'Iuran Hawe Lapangan',
    ]);

    $response->assertRedirect();
    $hawe = JenisIuranKi::where('nama', 'Hawe Test')->first();
    expect($hawe)->not->toBeNull()
        ->and((float)$hawe->nominal_default)->toBe(50000.0)
        ->and((bool)$hawe->is_active)->toBeTrue();
});

test('admin can update and toggle jenis iuran', function () {
    $ji = JenisIuranKi::create([
        'nama'            => 'Study Tour Test',
        'nominal_default' => 150000,
        'is_active'       => true,
    ]);

    // Toggle to inactive
    $this->post(route('ki.jenis-iuran.toggle', $ji->id));
    expect((bool) $ji->fresh()->is_active)->toBeFalse();

    // Update
    $this->put(route('ki.jenis-iuran.update', $ji->id), [
        'nama'            => 'Study Tour Bali',
        'nominal_default' => 200000,
        'keterangan'      => 'Revisi tujuan',
    ]);

    $jiFresh = $ji->fresh();
    expect($jiFresh->nama)->toBe('Study Tour Bali')
        ->and((float)$jiFresh->nominal_default)->toBe(200000.0);
});

test('admin can add custom bill for student with custom nominal and separate from master', function () {
    $hawe = JenisIuranKi::firstOrCreate(
        ['nama' => 'Hawe Khusus'],
        ['nominal_default' => 50000, 'is_active' => true]
    );
    $stsGasal = JenisIuranKi::where('nama', 'STS Gasal')->first();

    // Assign to Siswa 1
    $response = $this->post(route('ki.store'), [
        'siswa_id'  => $this->siswa1->id,
        'iuran_ids' => [$stsGasal->id, $hawe->id],
        'nominals'  => [
            $stsGasal->id => 100000,
            $hawe->id     => 40000, // Custom nominal
        ],
    ]);

    $response->assertRedirect(route('ki.index'));

    $pembayaran1 = Pembayaran::where('siswa_id', $this->siswa1->id)
        ->where('jenis_id', $this->jpKi->id)
        ->where('tahun_ajaran_id', $this->ta->id)
        ->first();

    expect($pembayaran1)->not->toBeNull()
        ->and((float)$pembayaran1->target)->toBe(140000.0);

    // Verify items in item_pembayaran_ki
    expect((float) ItemPembayaranKi::where('pembayaran_id', $pembayaran1->id)->where('nama_iuran', 'STS Gasal')->first()->nominal)->toBe(100000.0);
    expect((float) ItemPembayaranKi::where('pembayaran_id', $pembayaran1->id)->where('nama_iuran', 'Hawe Khusus')->first()->nominal)->toBe(40000.0);

    // Siswa 2 does NOT have Hawe Khusus
    $hasHawe2 = ItemPembayaranKi::whereHas('pembayaran', function ($q) {
        $q->where('siswa_id', $this->siswa2->id);
    })->where('nama_iuran', 'Hawe Khusus')->exists();

    expect($hasHawe2)->toBeFalse();
});

test('duplicate iuran for same student is prevented', function () {
    $hawe = JenisIuranKi::firstOrCreate(
        ['nama' => 'Hawe Duplikat'],
        ['nominal_default' => 50000, 'is_active' => true]
    );

    // First assignment
    $this->post(route('ki.store'), [
        'siswa_id'  => $this->siswa1->id,
        'iuran_ids' => [$hawe->id],
        'nominals'  => [$hawe->id => 50000],
    ]);

    $pembayaran = Pembayaran::where('siswa_id', $this->siswa1->id)
        ->where('jenis_id', $this->jpKi->id)
        ->first();

    $countBefore = ItemPembayaranKi::where('pembayaran_id', $pembayaran->id)
        ->where('nama_iuran', 'Hawe Duplikat')
        ->count();
    expect($countBefore)->toBe(1);

    // Try assigning again
    $this->post(route('ki.store'), [
        'siswa_id'  => $this->siswa1->id,
        'iuran_ids' => [$hawe->id],
        'nominals'  => [$hawe->id => 60000],
    ]);

    $countAfter = ItemPembayaranKi::where('pembayaran_id', $pembayaran->id)
        ->where('nama_iuran', 'Hawe Duplikat')
        ->count();
    expect($countAfter)->toBe(1);
});

test('bulk apply creates bills across students and skips duplicates', function () {
    $prakerin = JenisIuranKi::where('nama', 'Prakerin')->first();

    $response = $this->post(route('ki.terapkan-massal'), [
        'jenis_iuran_id' => $prakerin->id,
        'nominal'        => 75000,
    ]);

    $response->assertRedirect(route('ki.index'));

    $prakerinCountSiswa1 = ItemPembayaranKi::whereHas('pembayaran', function ($q) {
        $q->where('siswa_id', $this->siswa1->id);
    })->where('nama_iuran', 'Prakerin')->count();

    $prakerinCountSiswa2 = ItemPembayaranKi::whereHas('pembayaran', function ($q) {
        $q->where('siswa_id', $this->siswa2->id);
    })->where('nama_iuran', 'Prakerin')->count();

    expect($prakerinCountSiswa1)->toBe(1)
        ->and($prakerinCountSiswa2)->toBe(1);

    // Re-applying bulk skips without duplicating
    $this->post(route('ki.terapkan-massal'), [
        'jenis_iuran_id' => $prakerin->id,
        'nominal'        => 75000,
    ]);

    $prakerinCountSiswa1After = ItemPembayaranKi::whereHas('pembayaran', function ($q) {
        $q->where('siswa_id', $this->siswa1->id);
    })->where('nama_iuran', 'Prakerin')->count();

    expect($prakerinCountSiswa1After)->toBe(1);
});

test('student can pay specific asesmen item and balance updates properly', function () {
    $hawe = JenisIuranKi::firstOrCreate(
        ['nama' => 'Hawe Bayar'],
        ['nominal_default' => 50000, 'is_active' => true]
    );

    $this->post(route('ki.store'), [
        'siswa_id'  => $this->siswa1->id,
        'iuran_ids' => [$hawe->id],
        'nominals'  => [$hawe->id => 50000],
    ]);

    $pembayaran = Pembayaran::where('siswa_id', $this->siswa1->id)
        ->where('jenis_id', $this->jpKi->id)
        ->first();

    // Partial payment 30000
    $resPay1 = $this->post(route('ki.bayar', $pembayaran->id), [
        'kategori' => 'Hawe Bayar',
        'nominal'  => 30000,
        'metode'   => 'Cash',
    ]);
    $resPay1->assertRedirect();

    $pembayaran->refresh();
    expect($pembayaran->sisaKiKategori('Hawe Bayar'))->toBe(20000.0);

    $sub = $pembayaran->statusKiSubtagihan()['Hawe Bayar'];
    expect($sub['is_lunas'])->toBeFalse()
        ->and($sub['sisa'])->toBe(20000.0)
        ->and($sub['terbayar'])->toBe(30000.0);
});

test('full payment sets item and pembayaran to lunas', function () {
    $stsGasal = JenisIuranKi::where('nama', 'STS Gasal')->first();

    $this->post(route('ki.store'), [
        'siswa_id'  => $this->siswa1->id,
        'iuran_ids' => [$stsGasal->id],
        'nominals'  => [$stsGasal->id => 80000],
    ]);

    $pembayaran = Pembayaran::where('siswa_id', $this->siswa1->id)
        ->where('jenis_id', $this->jpKi->id)
        ->first();

    $this->post(route('ki.bayar', $pembayaran->id), [
        'kategori' => 'STS Gasal',
        'nominal'  => 80000,
        'metode'   => 'Cash',
    ]);

    $pembayaran->refresh();
    expect($pembayaran->sisaKiKategori('STS Gasal'))->toBe(0.0)
        ->and($pembayaran->status)->toBe('Lunas')
        ->and($pembayaran->statusKiSubtagihan()['STS Gasal']['is_lunas'])->toBeTrue();
});

test('rekapitulasi siswa calculates dynamic asesmen items breakdown correctly', function () {
    $hawe = JenisIuranKi::firstOrCreate(
        ['nama' => 'Hawe Rekap'],
        ['nominal_default' => 50000, 'is_active' => true]
    );

    $this->post(route('ki.store'), [
        'siswa_id'  => $this->siswa1->id,
        'iuran_ids' => [$hawe->id],
        'nominals'  => [$hawe->id => 60000],
    ]);

    $pembayaran = Pembayaran::where('siswa_id', $this->siswa1->id)
        ->where('jenis_id', $this->jpKi->id)
        ->first();

    $this->post(route('ki.bayar', $pembayaran->id), [
        'kategori' => 'Hawe Rekap',
        'nominal'  => 60000,
        'metode'   => 'Cash',
    ]);

    // Test RekapController::buildProfilKeuanganSiswa
    $rekapController = new \App\Http\Controllers\RekapController();
    $profil = $rekapController->buildProfilKeuanganSiswa($this->siswa1, $this->ta->id);
    $kiData = $profil['kiData'];

    expect($kiData['has_data'])->toBeTrue()
        ->and((float)$kiData['target'])->toBe(60000.0)
        ->and((float)$kiData['terbayar'])->toBe(60000.0)
        ->and((float)$kiData['sisa'])->toBe(0.0)
        ->and($kiData['status'])->toBe('Lunas');

    $haweKomponen = collect($kiData['komponen'])->firstWhere('nama', 'Hawe Rekap');
    expect($haweKomponen)->not->toBeNull()
        ->and((float)$haweKomponen['tagihan'])->toBe(60000.0)
        ->and((float)$haweKomponen['terbayar'])->toBe(60000.0)
        ->and((float)$haweKomponen['sisa'])->toBe(0.0);
});

test('legacy data without itemsKi falls back smoothly to legacy fields', function () {
    $legacyPembayaran = Pembayaran::create([
        'siswa_id'        => $this->siswa2->id,
        'jenis_id'        => $this->jpKi->id,
        'tahun_ajaran_id' => $this->ta->id,
        'tahun_ajaran'    => $this->ta->nama,
        'target_uts'      => 50000,
        'target_uas'      => 60000,
        'target_ujian'    => 70000,
        'target'          => 180000,
        'belum_lunas'     => 0,
        'status'          => 'Belum Lunas',
    ]);

    // statusKiSubtagihan should fallback to UTS, UAS, Ujian
    $sub = $legacyPembayaran->statusKiSubtagihan();
    expect($sub)->toHaveKeys(['UTS', 'UAS', 'Ujian'])
        ->and($sub['UTS']['target'])->toBe(50000.0)
        ->and($sub['UAS']['target'])->toBe(60000.0)
        ->and($sub['Ujian']['target'])->toBe(70000.0);
});

test('ki index renders without error when students have only dynamic non-legacy fees', function () {
    $prakerin = JenisIuranKi::where('nama', 'Prakerin')->first();

    // Terapkan massal
    $this->post(route('ki.terapkan-massal'), [
        'jenis_iuran_id' => $prakerin->id,
        'nominal'        => 50000,
    ]);

    // Open index page
    $response = $this->get(route('ki.index'));
    $response->assertOk();
    $response->assertSee('Prakerin');
});
