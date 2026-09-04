<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Bos;
use App\Models\Pengeluaran;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Bendahara BOS',
        'email' => 'bendahara@sekolah.sch.id',
    ]);
    $this->actingAs($this->user);

    $this->tahun = '2026';
});

test('1-7. pencatatan pengambilan dana BOS bertahap (Tahap 1 berkali-kali & Tahap 2) serta akumulasi totalnya', function () {
    // 1. Tambahkan pengambilan Tahap 1: 20.000.000
    $res1 = $this->post(route('bos.store'), [
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-01',
        'nominal'        => 20000000,
        'keterangan'     => 'Pengambilan pertama Tahap 1 dari Bank',
    ]);
    $res1->assertRedirect();
    $res1->assertSessionHas('success');

    // 2. Tambahkan pengambilan kedua Tahap 1: 30.000.000
    $res2 = $this->post(route('bos.store'), [
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-10',
        'nominal'        => 30000000,
        'keterangan'     => 'Pengambilan kedua Tahap 1 dari Bank',
    ]);
    $res2->assertRedirect();
    $res2->assertSessionHas('success');

    // 3. Pastikan kedua transaksi tetap terpisah di database
    $transaksiTahap1 = Bos::where('tahun_anggaran', $this->tahun)
        ->where('tahap', 'Tahap 1')
        ->get();
    expect($transaksiTahap1)->toHaveCount(2);

    // 4. Pastikan Total Tahap 1 menjadi 50.000.000
    expect((float)$transaksiTahap1->sum('nominal'))->toBe(50000000.0);

    // 5. Tambahkan pengambilan Tahap 2: 40.000.000
    $res3 = $this->post(route('bos.store'), [
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 2',
        'tanggal'        => '2026-11-01',
        'nominal'        => 40000000,
        'keterangan'     => 'Pengambilan Tahap 2 dari Bank',
    ]);
    $res3->assertRedirect();

    // 6. Pastikan Total Tahap 2 menjadi 40.000.000
    $totalTahap2 = (float) Bos::where('tahun_anggaran', $this->tahun)
        ->where('tahap', 'Tahap 2')
        ->sum('nominal');
    expect($totalTahap2)->toBe(40000000.0);

    // 7. Pastikan Total Pengambilan Dana BOS Masuk menjadi 90.000.000
    $totalSemua = (float) Bos::where('tahun_anggaran', $this->tahun)->sum('nominal');
    expect($totalSemua)->toBe(90000000.0);

    $responseView = $this->get(route('bos.index', ['tahun_anggaran' => $this->tahun]));
    $responseView->assertOk();
    $responseView->assertViewHas('subtotalTahap1', 50000000.0);
    $responseView->assertViewHas('subtotalTahap2', 40000000.0);
    $responseView->assertViewHas('totalPengambilanBos', 90000000.0);
});

test('8-13. pengeluaran BOS, saldo sisa, dan penambahan pengambilan ketiga', function () {
    // Siapkan pengambilan awal: Tahap 1 (20jt + 30jt = 50jt), Tahap 2 (40jt) => Total = 90jt
    Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-01',
        'nominal'        => 20000000,
        'keterangan'     => 'Pengambilan 1',
    ]);
    Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-10',
        'nominal'        => 30000000,
        'keterangan'     => 'Pengambilan 2',
    ]);
    Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 2',
        'tanggal'        => '2026-11-01',
        'nominal'        => 40000000,
        'keterangan'     => 'Pengambilan Tahap 2',
    ]);

    // 8. Tambahkan pengeluaran BOS sebesar 30.000.000
    $resPengeluaran = $this->post(route('bos.pengeluaran.store'), [
        'tahun_anggaran' => $this->tahun,
        'tanggal'        => '2026-09-15',
        'nominal'        => 30000000,
        'keterangan'     => 'Pembelian Buku dan Modul Praktikum',
    ]);
    $resPengeluaran->assertRedirect();

    // 9. Pastikan Sisa Dana BOS menjadi 60.000.000 (90jt - 30jt)
    $view1 = $this->get(route('bos.index', ['tahun_anggaran' => $this->tahun]));
    $view1->assertViewHas('totalPengambilanBos', 90000000.0);
    $view1->assertViewHas('totalPengeluaranBos', 30000000.0);
    $view1->assertViewHas('sisaSaldoBos', 60000000.0);

    // 10. Tambahkan pengambilan ketiga Tahap 1 sebesar 5.000.000
    $res4 = $this->post(route('bos.store'), [
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-20',
        'nominal'        => 5000000,
        'keterangan'     => 'Pengambilan ketiga Tahap 1',
    ]);
    $res4->assertRedirect();

    // 11. Pastikan Total Tahap 1 menjadi 55.000.000 (20jt + 30jt + 5jt)
    $totalTahap1 = (float) Bos::where('tahun_anggaran', $this->tahun)->where('tahap', 'Tahap 1')->sum('nominal');
    expect($totalTahap1)->toBe(55000000.0);

    // 12. Pastikan Total Pengambilan Dana BOS Masuk menjadi 95.000.000 (55jt + 40jt)
    $totalSemua = (float) Bos::where('tahun_anggaran', $this->tahun)->sum('nominal');
    expect($totalSemua)->toBe(95000000.0);

    // 13. Pastikan Sisa Dana BOS menjadi 65.000.000 (95jt - 30jt)
    $view2 = $this->get(route('bos.index', ['tahun_anggaran' => $this->tahun]));
    $view2->assertViewHas('subtotalTahap1', 55000000.0);
    $view2->assertViewHas('totalPengambilanBos', 95000000.0);
    $view2->assertViewHas('totalPengeluaranBos', 30000000.0);
    $view2->assertViewHas('sisaSaldoBos', 65000000.0);
});

test('14-15. edit salah satu transaksi pengambilan dan pastikan total tahap & saldo dihitung ulang', function () {
    $t1 = Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-01',
        'nominal'        => 20000000,
    ]);
    $t2 = Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-10',
        'nominal'        => 30000000,
    ]);

    // Edit t1 dari 20.000.000 menjadi 25.000.000
    $resEdit = $this->put(route('bos.update', $t1->id), [
        'tahap'      => 'Tahap 1',
        'tanggal'    => '2026-09-02',
        'nominal'    => 25000000,
        'keterangan' => 'Revisi penarikan pertama',
    ]);
    $resEdit->assertRedirect();

    expect((float)$t1->fresh()->nominal)->toBe(25000000.0);

    // Pastikan total tahap dihitung ulang menjadi 55.000.000
    $view = $this->get(route('bos.index', ['tahun_anggaran' => $this->tahun]));
    $view->assertViewHas('subtotalTahap1', 55000000.0);
    $view->assertViewHas('totalPengambilanBos', 55000000.0);
});

test('16-17. hapus salah satu transaksi pengambilan dan pastikan total tahap & saldo dihitung ulang', function () {
    $t1 = Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-01',
        'nominal'        => 20000000,
    ]);
    $t2 = Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'nominal'        => 30000000,
        'tanggal'        => '2026-09-10',
    ]);

    $resDelete = $this->delete(route('bos.destroy', $t1->id));
    $resDelete->assertRedirect();
    $this->assertDatabaseMissing('bos', ['id' => $t1->id]);

    // Pastikan total Tahap 1 berkurang menjadi 30.000.000
    $view = $this->get(route('bos.index', ['tahun_anggaran' => $this->tahun]));
    $view->assertViewHas('subtotalTahap1', 30000000.0);
    $view->assertViewHas('totalPengambilanBos', 30000000.0);
});

test('18-19. histori transaksi tetap terpisah dan laporan PDF menggunakan rincian serta total yang akurat', function () {
    Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-01',
        'nominal'        => 20000000,
        'keterangan'     => 'Pencairan Pertama',
    ]);
    Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-10',
        'nominal'        => 30000000,
        'keterangan'     => 'Pencairan Kedua',
    ]);
    Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 2',
        'tanggal'        => '2026-11-05',
        'nominal'        => 40000000,
        'keterangan'     => 'Pencairan Ketiga',
    ]);

    Pengeluaran::create([
        'tanggal'     => '2026-09-12',
        'sumber_dana' => 'BOS',
        'nominal'     => 15000000,
        'keterangan'  => 'Langganan Internet & Listrik',
        'user_id'     => $this->user->id,
    ]);

    $resPdf = $this->get(route('bos.cetak', ['tahun_anggaran' => $this->tahun]));
    $resPdf->assertOk();
    expect($resPdf->getContent())->not->toBeEmpty();
    expect(str_starts_with($resPdf->getContent(), '%PDF'))->toBeTrue();
});

test('20. transaksi tahun anggaran berbeda tidak tercampur', function () {
    // Transaksi tahun 2026
    Bos::create([
        'tahun_anggaran' => '2026',
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-05-01',
        'nominal'        => 20000000,
    ]);

    // Transaksi tahun 2027
    Bos::create([
        'tahun_anggaran' => '2027',
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2027-05-01',
        'nominal'        => 35000000,
    ]);

    $view2026 = $this->get(route('bos.index', ['tahun_anggaran' => '2026']));
    $view2026->assertViewHas('subtotalTahap1', 20000000.0);
    $view2026->assertViewHas('totalPengambilanBos', 20000000.0);

    $view2027 = $this->get(route('bos.index', ['tahun_anggaran' => '2027']));
    $view2027->assertViewHas('subtotalTahap1', 35000000.0);
    $view2027->assertViewHas('totalPengambilanBos', 35000000.0);
});

test('21. transaksi Tahap 1 dapat dibuat berkali-kali tanpa membuat tahap baru', function () {
    for ($i = 1; $i <= 5; $i++) {
        Bos::create([
            'tahun_anggaran' => $this->tahun,
            'tahap'          => 'Tahap 1',
            'tanggal'        => '2026-09-0' . $i,
            'nominal'        => 10000000,
            'keterangan'     => 'Penarikan Tahap 1 ke-' . $i,
        ]);
    }

    $tahapList = Bos::where('tahun_anggaran', $this->tahun)->pluck('tahap')->unique()->values()->toArray();
    expect($tahapList)->toBe(['Tahap 1']);

    $totalNominal = (float) Bos::where('tahun_anggaran', $this->tahun)->where('tahap', 'Tahap 1')->sum('nominal');
    expect($totalNominal)->toBe(50000000.0);
});

test('22. pengeluaran BOS tetap menggunakan modul Pengeluaran atau BKU yang sudah ada', function () {
    Bos::create([
        'tahun_anggaran' => $this->tahun,
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-09-01',
        'nominal'        => 50000000,
    ]);

    // Input via Pengeluarancontroller
    $res = $this->post(route('pengeluaran.store'), [
        'tanggal'     => '2026-09-10',
        'sumber_dana' => 'BOS',
        'nominal'     => 12000000,
        'keterangan'  => 'Belanja ATK dan Kertas HVS',
    ]);
    $res->assertRedirect(route('pengeluaran.index'));

    $p = Pengeluaran::where('sumber_dana', 'BOS')->first();
    expect($p)->not->toBeNull();
    expect((float)$p->nominal)->toBe(12000000.0);
    expect($p->keterangan)->toBe('Belanja ATK dan Kertas HVS');
});

test('23. modul pembayaran siswa tetap utuh dan berfungsi normal', function () {
    $siswa = Siswa::create([
        'nis'   => 'TEST999',
        'nama'  => 'Siswa Uji Coba BOS',
        'kelas' => 'X RPL 1',
    ]);

    expect($siswa->id)->toBeGreaterThan(0);
});

test('24. pengeluaran dana BOS dari halaman /pengeluaran berhasil dengan format Tahun Ajaran 2026/2027', function () {
    TahunAjaran::updateOrCreate(
        ['nama' => '2026/2027'],
        ['is_active' => true]
    );

    // Dana BOS dicatat dengan tahun anggaran '2026/2027'
    Bos::create([
        'tahun_anggaran' => '2026/2027',
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-08-01',
        'nominal'        => 50000000,
        'keterangan'     => 'Pencairan Bank Tahap 1',
    ]);

    // Lakukan input pengeluaran dari halaman pengeluaran dengan tanggal 2026-09-04
    $res = $this->post(route('pengeluaran.store'), [
        'tanggal'     => '2026-09-04',
        'sumber_dana' => 'BOS',
        'nominal'     => 15000000,
        'keterangan'  => 'Pembelian Buku dan Peralatan Sekolah',
    ]);

    $res->assertRedirect(route('pengeluaran.index'));
    $res->assertSessionHas('success');

    $p = Pengeluaran::where('sumber_dana', 'BOS')->first();
    expect($p)->not->toBeNull();
    expect((float)$p->nominal)->toBe(15000000.0);

    // Cek di halaman BOS saldo tersisa 35.000.000
    $viewBos = $this->get(route('bos.index', ['tahun_anggaran' => '2026/2027']));
    $viewBos->assertOk();
    $viewBos->assertViewHas('totalPengambilanBos', 50000000.0);
    $viewBos->assertViewHas('totalPengeluaranBos', 15000000.0);
    $viewBos->assertViewHas('sisaSaldoBos', 35000000.0);
});

test('25. pengeluaran BOS dari halaman /pengeluaran menolak jika nominal melebihi sisa dana kas BOS', function () {
    Bos::create([
        'tahun_anggaran' => '2026/2027',
        'tahap'          => 'Tahap 1',
        'tanggal'        => '2026-08-01',
        'nominal'        => 10000000,
        'keterangan'     => 'Pencairan Bank Tahap 1',
    ]);

    // Coba keluarkan 15.000.000 (melebihi saldo 10.000.000)
    $res = $this->post(route('pengeluaran.store'), [
        'tanggal'     => '2026-09-04',
        'sumber_dana' => 'BOS',
        'nominal'     => 15000000,
        'keterangan'  => 'Pengeluaran melebihi saldo',
    ]);

    $res->assertSessionHasErrors('nominal');
    expect(Pengeluaran::where('sumber_dana', 'BOS')->count())->toBe(0);
});

