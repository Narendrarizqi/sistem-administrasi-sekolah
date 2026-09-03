<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\JenisPembayaran;
use App\Models\TahunAjaran;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::first() ?? User::factory()->create();
    $this->actingAs($this->admin);

    $this->ta = TahunAjaran::firstOrCreate(
        ['nama' => '2026/2027'],
        ['is_active' => true, 'status' => 'Aktif']
    );

    JenisPembayaran::firstOrCreate(['nama' => 'KI']);
    JenisPembayaran::firstOrCreate(['nama' => 'IPP']);
    JenisPembayaran::firstOrCreate(['nama' => 'Sarpras']);
    JenisPembayaran::firstOrCreate(['nama' => 'DU']);

    $this->siswa = Siswa::firstOrCreate(
        ['nis' => 'TESTPAG1'],
        ['nama' => 'Siswa Pagination Test', 'kelas' => 'X RPL 1']
    );
});

test('all major table index routes render successfully with pagination structure', function () {
    $routes = [
        route('siswa.index'),
        route('ki.index'),
        route('ipp.index'),
        route('sarpras.index'),
        route('du.index'),
        route('pengeluaran.index'),
        route('rekap.index'),
        route('bos.index'),
    ];

    foreach ($routes as $url) {
        $response = $this->get($url);
        $response->assertOk();
    }
});

test('pagination links and query parameters persist across page numbers', function () {
    // Generate 55 dummy siswa to exceed 50 per page
    for ($i = 1; $i <= 55; $i++) {
        Siswa::create([
            'nis'   => 'DUMMY_' . $i,
            'nama'  => 'Siswa Dummy ' . $i,
            'kelas' => 'X TKJ 1',
        ]);
    }

    $responsePage1 = $this->get(route('siswa.index', ['page' => 1]));
    $responsePage1->assertOk();
    $responsePage1->assertSee('Menampilkan');

    $responsePage2 = $this->get(route('siswa.index', ['page' => 2]));
    $responsePage2->assertOk();
    $responsePage2->assertSee('Menampilkan');
});
