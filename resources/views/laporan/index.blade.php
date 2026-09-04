@extends('adminlte::page')

@section('title', 'Laporan Keuangan')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: HALAMAN LAPORAN KEUANGAN
           =============================================================== */

        /* 1. Header Box */
        .laporan-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .laporan-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 3px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .laporan-title-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14.5px;
            flex-shrink: 0;
        }

        .laporan-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        /* 2. Filter & Export Card */
        .laporan-filter-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .btn-cetak-pdf {
            height: 38px;
            background: #16a34a;
            border: 1.5px solid #16a34a;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 600;
            padding: 0 18px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            width: 100%;
        }

        .btn-cetak-pdf:hover {
            background: #15803d;
            border-color: #15803d;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
            transform: translateY(-1px);
        }

        /* 3. Stat Cards with Collapsible Dropdown (DESAIN ASLI YANG DISUKAI USER) */
        .laporan-stat-column {
            display: flex;
            flex-direction: column;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease !important;
        }

        .laporan-stat-column:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05) !important;
            border-color: #cbd5e1 !important;
        }

        .laporan-stat-column .stat-card {
            margin-bottom: 0;
            border-radius: 14px 14px 0 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: #ffffff !important;
            padding: 18px 20px 14px 20px !important;
        }

        .laporan-stat-column .stat-top {
            display: flex !important;
            align-items: center !important;
            gap: 14px !important;
            width: 100% !important;
        }

        .laporan-stat-column .stat-icon {
            flex-shrink: 0 !important;
            width: 46px !important;
            height: 46px !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 18px !important;
            color: #ffffff !important;
            transition: transform 0.2s ease !important;
        }

        .laporan-stat-column:hover .stat-icon {
            transform: scale(1.05) !important;
        }

        .stat-icon-masuk {
            background: #16a34a !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(22, 163, 74, 0.25) !important;
        }

        .stat-icon-keluar {
            background: #ef4444 !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(239, 68, 68, 0.25) !important;
        }

        .stat-icon-saldo {
            background: #2563eb !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.25) !important;
        }

        .laporan-stat-column .stat-content {
            flex: 1 !important;
            min-width: 0 !important;
        }

        .laporan-stat-column .stat-label {
            display: block !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            margin-bottom: 2px !important;
            line-height: 1.3 !important;
        }

        .stat-label-masuk { color: #64748b !important; }
        .stat-label-keluar { color: #64748b !important; }
        .stat-label-saldo { color: #64748b !important; }

        .laporan-stat-column .stat-value {
            margin: 0 !important;
            font-size: 18.5px !important;
            font-weight: 800 !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
            letter-spacing: -0.01em !important;
        }

        .laporan-stat-column .stat-desc {
            display: block !important;
            font-size: 11.5px !important;
            color: #94a3b8 !important;
            margin-top: 2px !important;
            line-height: 1.3 !important;
        }

        .laporan-detail-dropdown {
            border-top: 1px solid #f1f5f9 !important;
            overflow: hidden;
            border-radius: 0 0 14px 14px;
            background: #ffffff !important;
        }

        .laporan-detail-button {
            width: 100%;
            padding: 11px 18px;
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

        .laporan-chevron {
            transition: transform 0.25s ease;
            font-size: 11px;
        }

        .laporan-detail-button[aria-expanded="true"] .laporan-chevron {
            transform: rotate(180deg);
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
            font-size: 12px;
        }

        .laporan-detail-content .table th {
            background: #f8fafc;
            font-size: 11.5px;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
            padding: 7px 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .laporan-detail-content .table td {
            font-size: 12px;
            vertical-align: middle;
            padding: 8px 14px;
            border-bottom: 1px solid #f1f5f9;
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
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .laporan-detail-summary-item:last-child {
            margin-bottom: 0;
        }

        /* 4. Table Card (Buku Kas Umum) */
        .kas-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            padding: 18px 20px;
            margin-bottom: 24px;
        }

        .kas-card-title {
            font-size: 15.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 14px 0;
        }

        .kas-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
        }

        .kas-search-box {
            position: relative;
            width: 290px;
            max-width: 100%;
        }

        .kas-search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
        }

        .kas-search-input {
            width: 100%;
            height: 38px;
            padding: 6px 12px 6px 34px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #1e293b;
            font-size: 13px;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .kas-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        /* 5. Table Layout (Scroll Vertikal di dalam tabel, ~20 data terlihat) */
        .kas-table-wrap {
            width: 100%;
            max-height: 720px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
        }

        .kas-table-wrap::-webkit-scrollbar {
            width: 6px;
        }

        .kas-table-wrap::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 4px;
        }

        .kas-table-wrap::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .kas-table-wrap::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #laporanTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12.5px;
            table-layout: auto;
        }

        #laporanTable thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #f8fafc !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            color: #334155 !important;
            font-weight: 650 !important;
            font-size: 12px !important;
            padding: 10px 12px;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }

        #laporanTable tbody td {
            padding: 10px 12px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.35;
            font-size: 12.5px;
        }

        #laporanTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #laporanTable tbody tr:hover {
            background-color: #f8fafc;
        }

        .font-num {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            white-space: nowrap;
        }

        .uraian-wrap {
            font-weight: 500;
            color: #0f172a;
            word-break: break-word;
        }

        /* 6. Pagination Footer */
        .kas-table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 14px;
            margin-top: 4px;
            border-top: 1px solid #f1f5f9;
        }
    </style>
@stop

@section('content')

    {{-- 1. HEADER SECTION --}}
    <div class="laporan-page-header">
        <h1 class="laporan-title">
            <span class="laporan-title-icon"><i class="fas fa-file-invoice"></i></span>
            Laporan Keuangan & Kas
        </h1>
        <p class="laporan-desc">
            Ringkasan penerimaan kas, realisasi pengeluaran, saldo berjalan, serta ekspor cetak laporan PDF periode.
        </p>
    </div>

    {{-- 2. FILTER & CETAK PDF CARD --}}
    <div class="laporan-filter-card">
        <form action="{{ route('laporan.cetak') }}" method="GET" target="_blank" class="row align-items-end g-3">
            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <label for="tanggal_mulai" class="form-label font-weight-bold small text-dark mb-1">
                    Tanggal Mulai <span class="text-danger">*</span>
                </label>
                <input type="date"
                       name="tanggal_mulai"
                       id="tanggal_mulai"
                       class="form-control"
                       style="border-radius: 8px; height: 38px; font-size: 13px;"
                       value="{{ request('tanggal_mulai', date('Y-m-01')) }}"
                       required>
            </div>

            <div class="col-12 col-md-4 mb-2 mb-md-0">
                <label for="tanggal_akhir" class="form-label font-weight-bold small text-dark mb-1">
                    Tanggal Akhir <span class="text-danger">*</span>
                </label>
                <input type="date"
                       name="tanggal_akhir"
                       id="tanggal_akhir"
                       class="form-control"
                       style="border-radius: 8px; height: 38px; font-size: 13px;"
                       value="{{ request('tanggal_akhir', date('Y-m-d')) }}"
                       required>
            </div>

            <div class="col-12 col-md-4">
                <button type="submit" class="btn-cetak-pdf">
                    <i class="fas fa-file-pdf"></i>
                    <span>Cetak Laporan PDF</span>
                </button>
            </div>
        </form>
    </div>

    {{-- 3. 3 STAT CARDS + DROPDOWN RINCIAN (DESAIN YANG DISUKAI USER) --}}
    <div class="row align-items-start g-4 mb-4">
        {{-- Card 1: Total Masuk --}}
        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="laporan-stat-column">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon stat-icon-masuk">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label stat-label-masuk">Total Masuk</span>
                            <h3 class="stat-value text-success font-num">
                                Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Akumulasi seluruh kas masuk</span>
                        </div>
                    </div>
                </div>

                {{-- Dropdown Rincian Pemasukan --}}
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
                                            <td class="font-weight-500">{{ $rincian['jenis'] }}</td>
                                            <td class="text-right text-success font-weight-bold font-num">
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
                                <strong class="text-success font-num">Rp {{ number_format($totalRincianPemasukan, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Keluar --}}
        <div class="col-12 col-md-4 mb-3 mb-md-0">
            <div class="laporan-stat-column">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon stat-icon-keluar">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label stat-label-keluar">Total Keluar</span>
                            <h3 class="stat-value text-danger font-num">
                                Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Akumulasi seluruh pengeluaran</span>
                        </div>
                    </div>
                </div>

                {{-- Dropdown Rincian Pengeluaran --}}
                <div class="laporan-detail-dropdown">
                    <button
                        type="button"
                        class="laporan-detail-button"
                        data-toggle="collapse"
                        data-target="#rincianPengeluaran"
                        aria-expanded="false"
                        aria-controls="rincianPengeluaran"
                        style="color: #dc2626;"
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
                                            <td class="font-weight-500">{{ $rincian['jenis'] }}</td>
                                            <td class="text-right text-danger font-weight-bold font-num">
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
                                <strong class="text-danger font-num">Rp {{ number_format($totalRincianPengeluaran, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Saldo Akhir --}}
        <div class="col-12 col-md-4">
            <div class="laporan-stat-column">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon stat-icon-saldo">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label stat-label-saldo">Saldo Akhir</span>
                            <h3 class="stat-value font-num" style="color: #2563eb;">
                                Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Total kas masuk dikurangi keluar</span>
                        </div>
                    </div>
                </div>

                {{-- Dropdown Rincian Saldo Akhir --}}
                <div class="laporan-detail-dropdown">
                    <button
                        type="button"
                        class="laporan-detail-button"
                        data-toggle="collapse"
                        data-target="#rincianSaldo"
                        aria-expanded="false"
                        aria-controls="rincianSaldo"
                        style="color: #2563eb;"
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
                                            <td class="text-right text-success font-weight-bold font-num" style="padding: 6px 4px; font-size: 11px; white-space: nowrap;">
                                                {{ number_format($rincian['pemasukan'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-right text-danger font-num" style="padding: 6px 4px; font-size: 11px; white-space: nowrap;">
                                                {{ number_format($rincian['pengeluaran'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-right font-weight-bold font-num {{ $rincian['saldo'] >= 0 ? 'text-primary' : 'text-danger' }}" style="padding: 6px 10px 6px 4px; font-size: 11px; white-space: nowrap;">
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
                                <strong class="text-success font-num">Rp {{ number_format($totalRincianPemasukan, 0, ',', '.') }}</strong>
                            </div>
                            <div class="laporan-detail-summary-item">
                                <span>Total Pengeluaran</span>
                                <strong class="text-danger font-num">Rp {{ number_format($totalRincianPengeluaran, 0, ',', '.') }}</strong>
                            </div>
                            <div class="laporan-detail-summary-item">
                                <span>Saldo Akhir</span>
                                @if($totalRincianSaldo >= 0)
                                    <strong class="text-success font-num">Rp {{ number_format($totalRincianSaldo, 0, ',', '.') }}</strong>
                                @else
                                    <strong class="text-danger font-num">-Rp {{ number_format(abs($totalRincianSaldo), 0, ',', '.') }}</strong>
                                @endif
                            </div>

                            <a href="{{ route('laporan.cetak-rincian-saldo') }}"
                               target="_blank"
                               class="btn btn-success btn-sm w-100 mt-2 font-weight-bold"
                               style="border-radius: 6px; font-size: 11.5px; height: 32px; display: inline-flex; align-items: center; justify-content: center; color: #ffffff !important;">
                                <i class="fas fa-file-pdf mr-1" style="color: #ffffff !important;"></i> Cetak PDF Rincian Saldo Akhir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. BUKU KAS UMUM TABLE CARD --}}
    <div class="kas-card">
        <h5 class="kas-card-title">Buku Kas Umum</h5>

        {{-- Toolbar: Search --}}
        <div class="kas-toolbar">
            <div class="kas-search-box">
                <i class="fas fa-search"></i>
                <input type="text"
                       id="laporanSearchInput"
                       class="kas-search-input"
                       placeholder="Cari uraian transaksi...">
            </div>
        </div>

        {{-- Table (Scroll Vertikal di dalam tabel) --}}
        <div class="kas-table-wrap">
            <table class="table" id="laporanTable">
                <thead>
                    <tr>
                        <th style="width: 42px;" class="text-center">No</th>
                        <th style="width: 115px;">Tanggal</th>
                        <th>Uraian Transaksi</th>
                        <th class="text-right text-end" style="width: 140px;">Masuk (Rp)</th>
                        <th class="text-right text-end" style="width: 140px;">Keluar (Rp)</th>
                        <th class="text-right text-end" style="width: 150px;">Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody id="laporanTableBody">
                    @forelse($laporan as $row)
                        <tr class="kas-row" data-uraian="{{ strtolower($row['uraian'] ?? '') }}">
                            {{-- 1. No --}}
                            <td class="text-center text-muted row-number font-num">
                                {{ $loop->iteration }}
                            </td>

                            {{-- 2. Tanggal --}}
                            <td class="font-num text-secondary">
                                {{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}
                            </td>

                            {{-- 3. Uraian --}}
                            <td>
                                <div class="uraian-wrap">
                                    {{ $row['uraian'] }}
                                </div>
                            </td>

                            {{-- 4. Masuk --}}
                            <td class="text-right text-end font-num">
                                @if($row['masuk'] > 0)
                                    <span class="text-success font-weight-bold">
                                        Rp {{ number_format($row['masuk'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- 5. Keluar --}}
                            <td class="text-right text-end font-num">
                                @if($row['keluar'] > 0)
                                    <span class="text-danger font-weight-bold">
                                        Rp {{ number_format($row['keluar'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- 6. Saldo --}}
                            <td class="text-right text-end font-num font-weight-bold text-dark">
                                Rp {{ number_format($row['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyStateOriginal">
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-book-open fa-2x mb-2 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark mb-1">Belum ada transaksi</h6>
                                <p class="text-muted small mb-0">Laporan akan otomatis terisi saat pembayaran siswa atau pengeluaran dicatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Empty Filter State --}}
            <div id="filterEmptyState" class="text-center py-5 d-none">
                <i class="fas fa-search fa-2x mb-2 text-muted opacity-50 d-block"></i>
                <h6 class="font-weight-bold text-dark mb-1">Transaksi tidak ditemukan</h6>
                <p class="text-muted small mb-0">Tidak ada uraian transaksi yang cocok dengan kata kunci pencarian.</p>
            </div>
        </div>

    </div>

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('laporanTable');
    const tbody = document.getElementById('laporanTableBody');
    const searchInput = document.getElementById('laporanSearchInput');
    const paginationInfo = document.getElementById('paginationInfo');
    const emptyStateFilter = document.getElementById('filterEmptyState');

    function getAllRows() {
        return Array.from(tbody.querySelectorAll('tr.kas-row'));
    }

    function getFilteredRows() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        return getAllRows().filter(function (row) {
            const uraian = (row.dataset.uraian || '').toLowerCase();
            return !query || uraian.includes(query);
        });
    }

    function renderTable() {
        const filteredRows = getFilteredRows();
        const totalItems = filteredRows.length;

        getAllRows().forEach(function (row) {
            row.style.display = 'none';
        });

        filteredRows.forEach(function (row, idx) {
            row.style.display = '';
            const numCell = row.querySelector('.row-number');
            if (numCell) {
                numCell.textContent = idx + 1;
            }
            tbody.appendChild(row);
        });

        if (totalItems === 0) {
            table.style.display = 'none';
            if (emptyStateFilter) emptyStateFilter.classList.remove('d-none');
            if (paginationInfo) paginationInfo.textContent = 'Menampilkan 0 data';
        } else {
            table.style.display = '';
            if (emptyStateFilter) emptyStateFilter.classList.add('d-none');
            if (paginationInfo) {
                paginationInfo.textContent = `Menampilkan total ${totalItems} data transaksi (scroll di dalam tabel untuk melihat lainnya)`;
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            renderTable();
        });
    }

    /* Chevron rotation on collapse */
    $('[data-toggle="collapse"]').on('show.bs.collapse', function () {
        $(this).find('.laporan-chevron').css('transform', 'rotate(180deg)');
    });

    $('[data-toggle="collapse"]').on('hide.bs.collapse', function () {
        $(this).find('.laporan-chevron').css('transform', 'rotate(0deg)');
    });

    // Initialize
    renderTable();
});
</script>
@stop