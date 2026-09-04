<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\IppController;
use App\Http\Controllers\DaftarUlangController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\KiController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\KokurikulerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\BuktiPembayaranController;
use App\Http\Controllers\TargetTahunanController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\BosController;

Route::middleware('auth')->group(function () {

    //Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/siswa/naik-kelas', [SiswaController::class, 'naikKelas'])
        ->name('siswa.naik-kelas');

    // Import Siswa
    Route::get('/siswa/import/template', [SiswaController::class, 'downloadTemplate'])
        ->name('siswa.import.template');
    Route::post('/siswa/import/parse', [SiswaController::class, 'parseImport'])
        ->name('siswa.import.parse');
    Route::post('/siswa/import/mapping', [SiswaController::class, 'applyMapping'])
        ->name('siswa.import.mapping');
    Route::post('/siswa/import/confirm', [SiswaController::class, 'confirmImport'])
        ->name('siswa.import.confirm');

    Route::resource('siswa', SiswaController::class)->except(['show']);
    Route::get('/siswa', [SiswaController::class, 'index'])
        ->name('siswa.index');

    Route::get('/siswa/create', [SiswaController::class, 'create'])
        ->name('siswa.create');

    Route::post('/siswa', [SiswaController::class, 'store'])
        ->name('siswa.store');

    Route::get('/rekap', [RekapController::class, 'index'])
        ->name('rekap.index');

    Route::get('/rekap/cetak', [RekapController::class, 'cetakPdf'])
        ->name('rekap.cetak');

    Route::get('/rekap/siswa/{siswa}/cetak', [RekapController::class, 'cetakPdfSiswa'])
        ->name('rekap.cetak-siswa');

    Route::resource('ipp', IppController::class);

    Route::post('/ipp/{id}/bayar', [IppController::class,'bayar'])->name('ipp.bayar');

    Route::get('/ipp/create', [IppController::class, 'create'])->name('ipp.create');
    Route::post('/ipp', [IppController::class, 'store'])->name('ipp.store');
    Route::post('/ipp/store', [IppController::class, 'store'])->name('ipp.store');

    Route::resource('ki', KiController::class);
    Route::post('/ki/{id}/bayar', [KiController::class,'bayar'])->name('ki.bayar');
    Route::post('/ki/jenis-iuran', [KiController::class, 'storeJenisIuran'])->name('ki.jenis-iuran.store');
    Route::put('/ki/jenis-iuran/{id}', [KiController::class, 'updateJenisIuran'])->name('ki.jenis-iuran.update');
    Route::delete('/ki/jenis-iuran/{id}', [KiController::class, 'destroyJenisIuran'])->name('ki.jenis-iuran.destroy');
    Route::post('/ki/jenis-iuran/{id}/toggle', [KiController::class, 'toggleJenisIuran'])->name('ki.jenis-iuran.toggle');
    Route::post('/ki/terapkan-massal', [KiController::class, 'terapkanMassal'])->name('ki.terapkan-massal');

    // Ekstrakurikuler
    Route::resource('ekstrakurikuler', EkstrakurikulerController::class);
    Route::post('/ekstrakurikuler/{id}/bayar', [EkstrakurikulerController::class, 'bayar'])->name('ekstrakurikuler.bayar');
    Route::post('/ekstrakurikuler/jenis', [EkstrakurikulerController::class, 'storeJenis'])->name('ekstrakurikuler.jenis.store');
    Route::put('/ekstrakurikuler/jenis/{id}', [EkstrakurikulerController::class, 'updateJenis'])->name('ekstrakurikuler.jenis.update');
    Route::delete('/ekstrakurikuler/jenis/{id}', [EkstrakurikulerController::class, 'destroyJenis'])->name('ekstrakurikuler.jenis.destroy');
    Route::post('/ekstrakurikuler/jenis/{id}/toggle', [EkstrakurikulerController::class, 'toggleJenis'])->name('ekstrakurikuler.jenis.toggle');
    Route::post('/ekstrakurikuler/terapkan-massal', [EkstrakurikulerController::class, 'terapkanMassal'])->name('ekstrakurikuler.terapkan-massal');

    // Kokurikuler
    Route::resource('kokurikuler', KokurikulerController::class);
    Route::post('/kokurikuler/{id}/bayar', [KokurikulerController::class, 'bayar'])->name('kokurikuler.bayar');
    Route::post('/kokurikuler/jenis', [KokurikulerController::class, 'storeJenis'])->name('kokurikuler.jenis.store');
    Route::put('/kokurikuler/jenis/{id}', [KokurikulerController::class, 'updateJenis'])->name('kokurikuler.jenis.update');
    Route::delete('/kokurikuler/jenis/{id}', [KokurikulerController::class, 'destroyJenis'])->name('kokurikuler.jenis.destroy');
    Route::post('/kokurikuler/jenis/{id}/toggle', [KokurikulerController::class, 'toggleJenis'])->name('kokurikuler.jenis.toggle');
    Route::post('/kokurikuler/terapkan-massal', [KokurikulerController::class, 'terapkanMassal'])->name('kokurikuler.terapkan-massal');

    Route::get('/pengaturan', function () {
        return view('dashboard.index');
    })->name('pengaturan.index');

    Route::resource('pembayaran', PembayaranController::class)
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ]);

    Route::prefix('du')->name('du.')->group(function () {

        Route::get('/', [DaftarUlangController::class,'index'])->name('index');

        Route::get('/create', [DaftarUlangController::class,'create'])->name('create');

        Route::post('/', [DaftarUlangController::class,'store'])->name('store');

        Route::get('/{id}', [DaftarUlangController::class,'show'])->name('show');

        Route::post('/{id}/bayar', [DaftarUlangController::class,'bayar'])->name('bayar');

        Route::get('/{id}/edit', [DaftarUlangController::class,'edit'])->name('edit');

        Route::put('/{id}', [DaftarUlangController::class,'update'])->name('update');

        Route::delete('/{id}', [DaftarUlangController::class,'destroy'])->name('destroy');

    });

    Route::resource('du', DaftarUlangController::class);

    Route::post('/du/{id}/bayar', [DaftarUlangController::class,'bayar'])->name('du.bayar');

    Route::get('/du/create', [DaftarUlangController::class, 'create'])->name('du.create');
    Route::post('/du', [DaftarUlangController::class, 'store'])->name('du.store');
    Route::post('/du/store', [DaftarUlangController::class, 'store'])->name('du.store');
    Route::post('/du/terapkan-massal', [DaftarUlangController::class, 'terapkanMassal'])->name('du.terapkan-massal');

    Route::resource('sarpras', SarprasController::class);

    Route::post('/sarpras/{id}/bayar', [SarprasController::class,'bayar'])
        ->name('sarpras.bayar');
    Route::post('/sarpras/terapkan-massal', [SarprasController::class, 'terapkanMassal'])->name('sarpras.terapkan-massal');

    // Bantuan Operasional Sekolah (BOS)
    Route::get('/bos', [BosController::class, 'index'])->name('bos.index');
    Route::get('/bos/cetak', [BosController::class, 'cetakPdf'])->name('bos.cetak');
    Route::post('/bos', [BosController::class, 'store'])->name('bos.store');
    Route::put('/bos/{bo}', [BosController::class, 'update'])->name('bos.update');
    Route::delete('/bos/{bo}', [BosController::class, 'destroy'])->name('bos.destroy');
    Route::post('/bos/pengeluaran', [BosController::class, 'storePengeluaran'])->name('bos.pengeluaran.store');

    Route::resource('pengeluaran', PengeluaranController::class)
        ->except(['show']);

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/bukti-pembayaran/{id}/cetak', [BuktiPembayaranController::class, 'cetak'])
        ->name('bukti.cetak');

    Route::get('/target-tahunan', [TargetTahunanController::class, 'index'])
        ->name('target-tahunan.index');

    Route::post('/target-tahunan/hapus', [TargetTahunanController::class, 'destroy'])
        ->name('target-tahunan.destroy');

    Route::resource('tahun-ajaran', TahunAjaranController::class)
        ->only(['index', 'store', 'destroy']);

    Route::post('/tahun-ajaran/{id}/activate', [TahunAjaranController::class, 'activate'])
        ->name('tahun-ajaran.activate');
});

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware('guest')
        ->name('password.email');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/laporan/cetak', [LaporanController::class, 'cetakPdf'])
        ->name('laporan.cetak');
        
    Route::get('/laporan/cetak-rincian-saldo', [LaporanController::class, 'cetakRincianSaldo'])
    ->name('laporan.cetak-rincian-saldo');