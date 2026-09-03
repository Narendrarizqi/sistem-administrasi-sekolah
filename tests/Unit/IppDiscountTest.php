<?php

use App\Models\DetailPembayaran;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Validator;

uses(Tests\TestCase::class);

function ippPayment(array $attributes, array $payments = []): Pembayaran
{
    $pembayaran = new Pembayaran($attributes);
    $pembayaran->setRelation('detailPembayaran', collect(array_map(
        fn (float|array $payment) => new DetailPembayaran(is_array($payment)
            ? $payment
            : ['nominal' => $payment]),
        $payments
    )));

    return $pembayaran;
}

test('IPP lama tanpa potongan tetap menghitung tagihan seperti sebelumnya', function () {
    $pembayaran = ippPayment([
        'target' => 1000000,
        'belum_lunas' => 100000,
    ], [300000]);

    expect($pembayaran->totalTagihanAwal())->toBe(1100000.0)
        ->and($pembayaran->totalPotongan())->toBe(0.0)
        ->and($pembayaran->totalTagihan())->toBe(1100000.0)
        ->and($pembayaran->totalTerbayar())->toBe(300000.0)
        ->and($pembayaran->sisaTagihan())->toBe(800000.0);
});

test('potongan IPP mengurangi kewajiban tetapi bukan pembayaran', function () {
    $pembayaran = ippPayment([
        'target' => 1000000,
        'belum_lunas' => 100000,
    ], [['nominal' => 300000, 'potongan' => 200000]]);

    expect($pembayaran->totalTagihanAwal())->toBe(1100000.0)
        ->and($pembayaran->totalTagihan())->toBe(900000.0)
        ->and($pembayaran->totalTerbayar())->toBe(300000.0)
        ->and($pembayaran->totalTerpenuhi())->toBe(500000.0)
        ->and($pembayaran->sisaTagihan())->toBe(600000.0);

});

test('pembayaran setelah potongan dapat melunasi tagihan efektif', function () {
    $pembayaran = ippPayment([
        'target' => 1000000,
    ], [['nominal' => 750000, 'potongan' => 250000]]);

    expect($pembayaran->totalTagihan())->toBe(750000.0)
        ->and($pembayaran->totalTerpenuhi())->toBe(1000000.0)
        ->and($pembayaran->sisaTagihan())->toBe(0.0)
        ->and($pembayaran->statusIpp()['status_text'])->toBe('Lunas');
});

test('IPP 100 ribu dengan potongan 50 ribu dan pembayaran 50 ribu menjadi lunas', function () {
    $pembayaran = ippPayment([
        'target' => 100000,
    ], [['nominal' => 50000, 'potongan' => 50000]]);

    expect($pembayaran->totalTagihanAwal())->toBe(100000.0)
        ->and($pembayaran->totalPotongan())->toBe(50000.0)
        ->and($pembayaran->totalTagihan())->toBe(50000.0)
        ->and($pembayaran->totalTerbayar())->toBe(50000.0)
        ->and($pembayaran->totalTerpenuhi())->toBe(100000.0)
        ->and($pembayaran->sisaTagihan())->toBe(0.0)
        ->and($pembayaran->statusIpp()['status_text'])->toBe('Lunas');
});

test('potongan IPP divalidasi tidak negatif dan tidak melebihi target', function () {
    $negative = Validator::make(
        ['target' => 1000000, 'potongan' => -1],
        ['target' => 'required|numeric|min:0', 'potongan' => ['nullable', 'numeric', 'min:0', 'max:1000000']]
    );
    $tooLarge = Validator::make(
        ['target' => 1000000, 'potongan' => 1000001],
        ['target' => 'required|numeric|min:0', 'potongan' => ['nullable', 'numeric', 'min:0', 'max:1000000']]
    );

    expect($negative->fails())->toBeTrue()
        ->and($tooLarge->fails())->toBeTrue();
});

test('pembayaran + potongan yang sesuai dengan tagihan bulanan membuat bulan tersebut Lunas', function () {
    // Target 1.200.000 (100.000 / bulan). Tahun ajaran 2026/2027 (mulai Juli 2026).
    // Tanggal uji: 15 Juli 2026 (Bulan ke-1, tarif = 100.000).
    $pembayaran = ippPayment([
        'target'       => 1200000,
        'tahun_ajaran' => '2026/2027',
        'belum_lunas'  => 0,
    ], [['nominal' => 60000, 'potongan' => 40000]]);

    $status = $pembayaran->statusIpp('2026-07-15');

    expect($pembayaran->totalTerbayar())->toBe(60000.0)
        ->and($pembayaran->totalPotongan())->toBe(40000.0)
        ->and($pembayaran->totalTerpenuhi())->toBe(100000.0)
        ->and($status['is_lunas'])->toBeTrue()
        ->and($status['status_text'])->toBe('Bulan Ini Lunas')
        ->and($status['tunggakan_bulan'])->toBe(0)
        ->and($status['tagihan_bulan_ini'])->toBe(0.0);
});

test('potongan 100 persen tanpa uang tunai (nominal 0) tetap membuat bulan tersebut Lunas', function () {
    $pembayaran = ippPayment([
        'target'       => 1200000,
        'tahun_ajaran' => '2026/2027',
        'belum_lunas'  => 0,
    ], [['nominal' => 0, 'potongan' => 100000]]);

    $status = $pembayaran->statusIpp('2026-07-15');

    expect($pembayaran->totalTerbayar())->toBe(0.0)
        ->and($pembayaran->totalPotongan())->toBe(100000.0)
        ->and($pembayaran->totalTerpenuhi())->toBe(100000.0)
        ->and($status['is_lunas'])->toBeTrue()
        ->and($status['status_text'])->toBe('Bulan Ini Lunas')
        ->and($status['tagihan_bulan_ini'])->toBe(0.0);
});

test('jika pembayaran + potongan belum mencapai tarif bulanan, bulan tersebut belum lunas', function () {
    $pembayaran = ippPayment([
        'target'       => 1200000,
        'tahun_ajaran' => '2026/2027',
        'belum_lunas'  => 0,
    ], [['nominal' => 40000, 'potongan' => 30000]]);

    $status = $pembayaran->statusIpp('2026-07-15');

    expect($pembayaran->totalTerpenuhi())->toBe(70000.0)
        ->and($status['is_lunas'])->toBeFalse()
        ->and($status['status_text'])->toBe('Bulan Ini Belum Lunas')
        ->and($status['tagihan_bulan_ini'])->toBe(30000.0);
});

