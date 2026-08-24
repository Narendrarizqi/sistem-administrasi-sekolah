@extends('adminlte::page')

@section('title', 'Laporan')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

<style>
    .laporan-stat-column {
        display: flex;
        flex-direction: column;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 18px !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        overflow: hidden;
        transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.22s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.22s ease !important;
    }

    .laporan-stat-column:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.03) !important;
        border-color: #cbd5e1 !important;
    }

    .laporan-stat-column .stat-card {
        margin-bottom: 0;
        border-radius: 18px 18px 0 0 !important;
        box-shadow: none !important;
        border: none !important;
        background: #ffffff !important;
        padding: 18px 20px 14px 20px !important;
    }

    .laporan-stat-column .stat-top {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 12px !important;
        width: 100% !important;
    }

    .laporan-stat-column .stat-content {
        flex: 1 !important;
        min-width: 0 !important;
    }

    .laporan-stat-column .stat-label {
        display: block !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        margin-bottom: 2px !important;
        line-height: 1.3 !important;
    }

    .stat-label-masuk { color: #15803d !important; }
    .stat-label-keluar { color: #be123c !important; }
    .stat-label-saldo { color: #1d4ed8 !important; }

    .laporan-stat-column .stat-value {
        margin: 0 !important;
        font-size: 18px !important;
        font-weight: 800 !important;
        color: #0f172a !important;
        line-height: 1.2 !important;
        white-space: nowrap !important;
        letter-spacing: -0.02em !important;
    }

    .laporan-stat-column .stat-desc {
        display: block !important;
        font-size: 11.5px !important;
        color: #64748b !important;
        margin-top: 3px !important;
        line-height: 1.3 !important;
    }

    .laporan-stat-column .stat-icon {
        flex-shrink: 0 !important;
        width: 50px !important;
        height: 50px !important;
        border-radius: 50% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 20px !important;
        color: #ffffff !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }

    .laporan-stat-column:hover .stat-icon {
        transform: scale(1.08) !important;
    }

    .stat-icon-masuk {
        background: #16a34a !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25) !important;
    }

    .stat-icon-masuk i {
        color: #ffffff !important;
    }

    .stat-icon-keluar {
        background: #e11d48 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25) !important;
    }

    .stat-icon-keluar i {
        color: #ffffff !important;
    }

    .stat-icon-saldo {
        background: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
    }

    .stat-icon-saldo i {
        color: #ffffff !important;
    }

    .laporan-detail-dropdown {
        border-top: 1px solid #f1f5f9 !important;
        overflow: hidden;
        border-radius: 0 0 18px 18px;
        background: #ffffff !important;
    }

    .laporan-detail-button {
        width: 100%;
        padding: 12px 18px;
        border: 0;
        background: #ffffff !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .laporan-detail-button:hover {
        background: #f8fafc !important;
    }

    .laporan-detail-button i:first-child {
        font-size: 13px;
        margin-right: 8px;
    }

    .laporan-detail-content {
        border-top: 1px solid #f1f5f9;
        background: #ffffff;
        transition: opacity 0.3s ease;
        opacity: 0;
        overflow-x: hidden !important;
        width: 100% !important;
    }

    .laporan-detail-content.show {
        opacity: 1;
    }

    .laporan-detail-content.collapsing {
        transition: height 0.3s ease, opacity 0.3s ease;
        opacity: 0.6;
    }

    .laporan-detail-content .table-wrap {
        width: 100% !important;
        overflow: hidden !important;
    }

    .laporan-detail-content .table {
        margin-bottom: 0;
        width: 100% !important;
    }

    .laporan-detail-content .table th {
        background: #f8fafc;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        white-space: nowrap;
        padding: 8px 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .laporan-detail-content .table td {
        font-size: 12.5px;
        vertical-align: middle;
        padding: 8px 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .laporan-saldo-item {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .laporan-saldo-item:last-child {
        border-bottom: none;
    }

    .laporan-detail-summary {
        padding: 10px 14px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .laporan-detail-summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .laporan-detail-summary-item:last-child {
        margin-bottom: 0;
    }

    .laporan-chevron {
        transition: transform 0.25s ease;
        font-size: 11px;
    }

    .laporan-detail-button[aria-expanded="true"] .laporan-chevron {
        transform: rotate(180deg);
    }

    @media (max-width: 767.98px) {
        .laporan-stat-column {
            margin-bottom: 20px;
        }
    }
</style>
@stop

@section('content')

{{-- PAGE HEADER & BREADCRUMB INDICATOR --}}
<div class="page-header-box">
    <div>
        <div class="page-header-breadcrumb">
            <i class="fas fa-home text-success"></i>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i>
            <span class="text-dark font-weight-bold">Laporan Keuangan</span>
        </div>
        <h1 class="page-header-title">
            <span class="page-header-icon"><i class="fas fa-file-invoice"></i></span>
            Laporan Keuangan & Kas
        </h1>
        <p class="page-header-desc">
            Ringkasan penerimaan kas, realisasi pengeluaran, saldo berjalan, serta ekspor cetak laporan PDF periode.
        </p>
    </div>
    <div class="page-header-badges">
        <span class="badge-page-indicator">
            <i class="fas fa-calendar-check"></i>
            Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
        </span>
        <span class="badge bg-light text-secondary border px-3 py-2 font-weight-semibold" style="border-radius: 20px; font-size: 12px;">
            <i class="fas fa-wallet text-muted mr-1"></i> Kas Masuk: Rp {{ number_format($totalMasuk, 0, ',', '.') }}
        </span>
    </div>
</div>

{{-- FILTER CETAK --}}
<div class="card mb-3">
    <div class="card-body">
        <form
            action="{{ route('laporan.cetak') }}"
            method="GET"
            target="_blank"
            class="row g-2 align-items-end"
        >

            <div class="col-md-4">
                <label for="tanggal_mulai" class="form-label mb-1">
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    id="tanggal_mulai"
                    class="form-control"
                    required
                >
            </div>

            <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label mb-1">
                    Tanggal Akhir
                </label>

                <input
                    type="date"
                    name="tanggal_akhir"
                    id="tanggal_akhir"
                    class="form-control"
                    required
                >
            </div>

            <div class="col-md-4">
                <button
                    type="submit"
                    class="btn btn-add w-100"
                >
                    <i class="fas fa-file-pdf me-1"></i>
                    Cetak Laporan
                </button>
            </div>

        </form>
    </div>
</div>


{{-- ========================================================= --}}
{{-- CARD TOTAL + DROPDOWN --}}
{{-- ========================================================= --}}

<div class="row align-items-start g-4 mb-4">

    {{-- TOTAL MASUK --}}
    <div class="col-12 col-md-4">
        <div class="laporan-stat-column">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-content">
                        <span class="stat-label stat-label-masuk">Total Masuk</span>
                        <h3 class="stat-value">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h3>
                        <span class="stat-desc">Akumulasi seluruh kas masuk</span>
                    </div>
                    <div class="stat-icon stat-icon-masuk">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
            </div>

            <div class="laporan-detail-dropdown">
                <button
                    type="button"
                    class="laporan-detail-button"
                    data-toggle="collapse"
                    data-target="#rincianPemasukan"
                    aria-expanded="false"
                    aria-controls="rincianPemasukan"
                    style="color: #15803d;"
                >
                    <span class="d-inline-flex align-items-center">
                        <i class="fas fa-list text-success"></i>
                        <span>Rincian Pemasukan</span>
                    </span>
                    <i class="fas fa-chevron-down laporan-chevron text-success"></i>
                </button>

                <div id="rincianPemasukan" class="collapse laporan-detail-content">
                    <div class="table-wrap">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sumber</th>
                                    <th class="text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rincianSumberDana as $rincian)
                                    <tr>
                                        <td>{{ $rincian['jenis'] }}</td>
                                        <td class="text-right text-success font-weight-bold">
                                            Rp {{ number_format($rincian['pemasukan'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Belum ada pemasukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="laporan-detail-summary">
                        <div class="laporan-detail-summary-item">
                            <span>Total Pemasukan</span>
                            <strong class="text-success">Rp {{ number_format($totalRincianPemasukan, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOTAL KELUAR --}}
    <div class="col-12 col-md-4">
        <div class="laporan-stat-column">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-content">
                        <span class="stat-label stat-label-keluar">Total Keluar</span>
                        <h3 class="stat-value">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h3>
                        <span class="stat-desc">Akumulasi seluruh pengeluaran</span>
                    </div>
                    <div class="stat-icon stat-icon-keluar">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
            </div>

            <div class="laporan-detail-dropdown">
                <button
                    type="button"
                    class="laporan-detail-button"
                    data-toggle="collapse"
                    data-target="#rincianPengeluaran"
                    aria-expanded="false"
                    aria-controls="rincianPengeluaran"
                    style="color: #be123c;"
                >
                    <span class="d-inline-flex align-items-center">
                        <i class="fas fa-list text-danger"></i>
                        <span>Rincian Pengeluaran</span>
                    </span>
                    <i class="fas fa-chevron-down laporan-chevron text-danger"></i>
                </button>

                <div id="rincianPengeluaran" class="collapse laporan-detail-content">
                    <div class="table-wrap">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sumber</th>
                                    <th class="text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rincianSumberDana as $rincian)
                                    <tr>
                                        <td>{{ $rincian['jenis'] }}</td>
                                        <td class="text-right text-danger font-weight-bold">
                                            Rp {{ number_format($rincian['pengeluaran'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Belum ada pengeluaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="laporan-detail-summary">
                        <div class="laporan-detail-summary-item">
                            <span>Total Pengeluaran</span>
                            <strong class="text-danger">Rp {{ number_format($totalRincianPengeluaran, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SALDO AKHIR --}}
    <div class="col-12 col-md-4">
        <div class="laporan-stat-column">
            <div class="stat-card">
                <div class="stat-top">
                    <div class="stat-content">
                        <span class="stat-label stat-label-saldo">Saldo Akhir</span>
                        <h3 class="stat-value">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                        <span class="stat-desc">Total kas masuk dikurangi keluar</span>
                    </div>
                    <div class="stat-icon stat-icon-saldo">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>

            <div class="laporan-detail-dropdown">
                <button
                    type="button"
                    class="laporan-detail-button"
                    data-toggle="collapse"
                    data-target="#rincianSaldo"
                    aria-expanded="false"
                    aria-controls="rincianSaldo"
                    style="color: #1d4ed8;"
                >
                    <span class="d-inline-flex align-items-center">
                        <i class="fas fa-wallet text-primary"></i>
                        <span>Rincian Saldo Akhir</span>
                    </span>
                    <i class="fas fa-chevron-down laporan-chevron text-primary"></i>
                </button>

                <div id="rincianSaldo" class="collapse laporan-detail-content">
                    <div class="table-wrap">
                        <table class="table table-sm mb-0" style="width: 100%; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="width: 22%; padding: 6px 4px 6px 10px; font-size: 11px;">Sumber</th>
                                    <th class="text-right" style="width: 26%; padding: 6px 4px; font-size: 11px;">Masuk</th>
                                    <th class="text-right" style="width: 26%; padding: 6px 4px; font-size: 11px;">Keluar</th>
                                    <th class="text-right" style="width: 26%; padding: 6px 10px 6px 4px; font-size: 11px;">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rincianSumberDana as $rincian)
                                    <tr>
                                        <td class="font-weight-bold text-dark" style="padding: 6px 4px 6px 10px; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $rincian['jenis'] }}
                                        </td>
                                        <td class="text-right text-success font-weight-bold" style="padding: 6px 4px; font-size: 11px; white-space: nowrap;">
                                            {{ number_format($rincian['pemasukan'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right text-danger" style="padding: 6px 4px; font-size: 11px; white-space: nowrap;">
                                            {{ number_format($rincian['pengeluaran'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-right font-weight-bold {{ $rincian['saldo'] >= 0 ? 'text-primary' : 'text-danger' }}" style="padding: 6px 10px 6px 4px; font-size: 11px; white-space: nowrap;">
                                            {{ $rincian['saldo'] >= 0 ? number_format($rincian['saldo'], 0, ',', '.') : '-' . number_format(abs($rincian['saldo']), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted" style="font-size: 11px; padding: 8px;">
                                            Belum ada data.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="laporan-detail-summary">
                        <div class="laporan-detail-summary-item">
                            <span>Total Pemasukan</span>
                            <strong class="text-success">Rp {{ number_format($totalRincianPemasukan, 0, ',', '.') }}</strong>
                        </div>
                        <div class="laporan-detail-summary-item">
                            <span>Total Pengeluaran</span>
                            <strong class="text-danger">Rp {{ number_format($totalRincianPengeluaran, 0, ',', '.') }}</strong>
                        </div>
                        <div class="laporan-detail-summary-item">
                            <span>Saldo Akhir</span>
                            @if($totalRincianSaldo >= 0)
                                <strong class="text-success">Rp {{ number_format($totalRincianSaldo, 0, ',', '.') }}</strong>
                            @else
                                <strong class="text-danger">-Rp {{ number_format(abs($totalRincianSaldo), 0, ',', '.') }}</strong>
                            @endif
                        </div>

                        <a href="{{ route('laporan.cetak-rincian-saldo') }}"
                           target="_blank"
                           class="btn btn-add-success btn-sm w-100 mt-3">
                            <i class="fas fa-file-pdf me-1"></i>
                            Cetak PDF Rincian Saldo Akhir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- BUKU KAS UMUM --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
            Buku Kas Umum
        </h5>
    </div>


    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

            <div class="table-search-box">

                <i class="fas fa-search"></i>

                <input
                    type="text"
                    id="laporanSearchInput"
                    placeholder="Cari uraian..."
                >

            </div>

        </div>


        @if($laporan->count() > 0)

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle"
                    id="laporanTable"
                >

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Uraian
                            </th>

                            <th class="text-right">
                                Masuk (Rp)
                            </th>

                            <th class="text-right">
                                Keluar (Rp)
                            </th>

                            <th class="text-right">
                                Saldo (Rp)
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($laporan as $row)

                            <tr>

                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>


                                <td>
                                    {{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}
                                </td>


                                <td>
                                    {{ $row['uraian'] }}
                                </td>


                                <td class="text-right">

                                    @if($row['masuk'] > 0)

                                        <span class="text-success fw-semibold">
                                            {{ number_format($row['masuk'], 0, ',', '.') }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                <td class="text-right">

                                    @if($row['keluar'] > 0)

                                        <span class="text-danger fw-semibold">
                                            {{ number_format($row['keluar'], 0, ',', '.') }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                <td class="text-right fw-semibold">

                                    {{ number_format($row['saldo'], 0, ',', '.') }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="text-center py-5">

                <i class="fas fa-book fa-3x text-muted mb-3"></i>

                <h5 class="fw-bold">
                    Belum ada transaksi
                </h5>

                <p class="text-muted">
                    Laporan akan otomatis terisi begitu ada pembayaran siswa
                    atau pengeluaran yang dicatat.
                </p>

            </div>

        @endif

    </div>

</div>


@stop


@section('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('laporanSearchInput');

    if (searchInput) {

        searchInput.addEventListener('keyup', function () {

            const query =
                this.value.toLowerCase().trim();

            document
                .querySelectorAll('#laporanTable tbody tr')
                .forEach(function (row) {

                    row.style.display =
                        row.innerText
                            .toLowerCase()
                            .includes(query)
                            ? ''
                            : 'none';

                });

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Ikon dropdown
    |--------------------------------------------------------------------------
    */

    $('[data-toggle="collapse"]').on(
        'show.bs.collapse',
        function () {

            const target =
                $(this).attr('data-target');

            $(this)
                .find('.laporan-chevron')
                .css('transform', 'rotate(180deg)');

        }
    );


    $('[data-toggle="collapse"]').on(
        'hide.bs.collapse',
        function () {

            $(this)
                .find('.laporan-chevron')
                .css('transform', 'rotate(0deg)');

        }
    );

});

</script>

@stop