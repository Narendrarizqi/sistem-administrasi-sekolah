<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Pengeluaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::first() ?? User::factory()->create();
    $this->actingAs($this->admin);
});

test('pengeluaran index renders 3 stat cards including pengeluaran terbesar berdasarkan kategori', function () {
    Pengeluaran::create([
        'tanggal' => now()->format('Y-m-d'),
        'sumber_dana' => 'IPP',
        'keterangan' => 'Beli ATK IPP',
        'nominal' => 1000000,
        'user_id' => $this->admin->id,
    ]);

    Pengeluaran::create([
        'tanggal' => now()->format('Y-m-d'),
        'sumber_dana' => 'Sarpras',
        'keterangan' => 'Perbaikan Meja Sarpras',
        'nominal' => 5000000,
        'user_id' => $this->admin->id,
    ]);

    $response = $this->get(route('pengeluaran.index'));
    $response->assertOk();

    // Pastikan card Total Pengeluaran dan Pengeluaran Bulan Ini ada
    $response->assertSee('Total Pengeluaran');
    $response->assertSee('Pengeluaran Bulan Ini');

    // Pastikan card Pengeluaran Terbesar Berdasarkan Kategori ada
    $response->assertSee('Pengeluaran Terbesar Berdasarkan Kategori');
    $response->assertSee('Sarana & Prasarana');
    $response->assertSee('Rp 5.000.000');
    $response->assertSee('83.3% dari total');

    // Pastikan card Pengeluaran Terbesar transaksi tunggal dan card Jumlah Transaksi yang lama tidak lagi digunakan sebagai stat card
    $response->assertDontSee('Seluruh pengeluaran tercatat');
});
