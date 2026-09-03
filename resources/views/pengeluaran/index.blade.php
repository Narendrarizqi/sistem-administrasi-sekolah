@extends('adminlte::page')

@section('title', 'Manajemen Pengeluaran')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: HALAMAN MANAJEMEN PENGELUARAN
           =============================================================== */

        /* 1. Header Box */
        .pengeluaran-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .pengeluaran-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 3px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pengeluaran-title-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #ef4444;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.25);
        }

        .pengeluaran-desc {
            font-size: 13px;
            color: #64748b;
            margin: 0;
            padding-left: 44px;
        }

        /* 2. Stat Cards Grid (4 Cards) */
        .stat-card-clean {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 14px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            gap: 12px;
            height: 100%;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .stat-card-clean:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }

        .stat-card-icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .icon-circle-red {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25);
        }

        .icon-circle-gray {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .icon-circle-green {
            background: #16a34a;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
        }

        .icon-circle-orange {
            background: #f97316;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(249, 115, 22, 0.25);
        }

        .stat-card-info {
            flex: 1;
            min-width: 0;
        }

        .stat-card-label {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-card-value {
            font-size: 16.5px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-card-sub {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.25;
            white-space: normal;
            word-break: break-word;
        }

        /* 3. Main Card & Toolbar */
        .pengeluaran-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .pengeluaran-card-title {
            font-size: 15.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 14px 0;
        }

        .pengeluaran-filter-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .pengeluaran-filter-left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            flex: 1;
        }

        .pengeluaran-search-wrapper {
            position: relative;
            width: 240px;
            max-width: 100%;
        }

        .pengeluaran-search-wrapper i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
        }

        .pengeluaran-search-input {
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

        .pengeluaran-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .pengeluaran-select-filter {
            height: 38px;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #1e293b;
            font-size: 13px;
            font-weight: 500;
            outline: none;
            transition: border-color 0.15s ease;
            cursor: pointer;
        }

        .pengeluaran-select-filter:focus {
            border-color: #16a34a;
        }

        .btn-tambah-pengeluaran {
            height: 38px;
            background: #16a34a;
            border: 1.5px solid #16a34a;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 600;
            padding: 0 16px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            white-space: nowrap;
        }

        .btn-tambah-pengeluaran:hover {
            background: #15803d;
            border-color: #15803d;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
            transform: translateY(-1px);
        }

        /* 4. Table Layout (Scroll Vertikal di dalam tabel, ~20 data terlihat) */
        .pengeluaran-table-wrap {
            width: 100%;
            max-height: 720px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
        }

        .pengeluaran-table-wrap::-webkit-scrollbar {
            width: 6px;
        }

        .pengeluaran-table-wrap::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 4px;
        }

        .pengeluaran-table-wrap::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .pengeluaran-table-wrap::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #pengeluaranTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12.5px;
            table-layout: auto;
        }

        #pengeluaranTable thead th {
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

        #pengeluaranTable th.sortable {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #pengeluaranTable th.sortable:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }

        #pengeluaranTable .sort-icon {
            margin-left: 4px;
            font-size: 10px;
            opacity: 0.4;
        }

        #pengeluaranTable th.sort-active {
            color: #16a34a !important;
        }

        #pengeluaranTable th.sort-active .sort-icon {
            opacity: 1;
            color: #16a34a;
        }

        #pengeluaranTable tbody td {
            padding: 10px 12px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.35;
            font-size: 12.5px;
        }

        #pengeluaranTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #pengeluaranTable tbody tr:hover {
            background-color: #f8fafc;
        }

        .row-number {
            font-size: 12px;
            color: #64748b;
            text-align: center;
            width: 36px;
        }

        .font-num {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            white-space: nowrap;
        }

        .keterangan-text {
            font-weight: 500;
            color: #0f172a;
            word-break: break-word;
        }

        .val-nominal-cell {
            color: #dc2626 !important;
            font-weight: 700 !important;
            font-size: 13px;
        }

        /* 5. Sumber Dana Badges */
        .badge-sumber-ipp {
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            color: #1d4ed8 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 8px !important;
        }

        .badge-sumber-du {
            background: #f5f3ff !important;
            border: 1px solid #ddd6fe !important;
            color: #6d28d9 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 8px !important;
        }

        .badge-sumber-sarpras {
            background: #fffbeb !important;
            border: 1px solid #fde68a !important;
            color: #b45309 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 8px !important;
        }

        .badge-sumber-ki {
            background: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
            color: #15803d !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 8px !important;
        }

        .badge-sumber-bos {
            background: #ecfeff !important;
            border: 1px solid #a5f3fc !important;
            color: #0e7490 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 8px !important;
        }

        /* 6. Action Buttons */
        .pengeluaran-actions {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-act-edit,
        .btn-act-hapus {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 11.5px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-act-edit {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #d97706 !important;
        }

        .btn-act-edit:hover {
            background: #d97706;
            border-color: #d97706;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(217, 119, 6, 0.25);
        }

        .btn-act-hapus {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626 !important;
        }

        .btn-act-hapus:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220, 38, 38, 0.25);
        }

        /* 7. Pagination Footer */
        .pengeluaran-table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 14px;
            margin-top: 4px;
            border-top: 1px solid #f1f5f9;
        }

        .pagination-custom {
            display: inline-flex;
            gap: 4px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination-custom .page-item .page-link {
            min-width: 28px;
            height: 28px;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            background: #ffffff;
            transition: all 0.15s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .pagination-custom .page-item.active .page-link {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
        }

        .pagination-custom .page-item.disabled .page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }

        .pagination-custom .page-item:not(.active):not(.disabled) .page-link:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* 8. Bottom Analytics Cards */
        .analytics-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        .analytics-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .analytics-card-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .source-breakdown-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .source-name-label {
            width: 145px;
            font-size: 12.5px;
            font-weight: 500;
            color: #334155;
            flex-shrink: 0;
        }

        .source-progress-track {
            flex: 1;
            height: 8px;
            background: #f1f5f9;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .source-progress-fill {
            height: 100%;
            background: #dc2626;
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .source-val-label {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
            text-align: right;
            white-space: nowrap;
            min-width: 130px;
        }

        .source-total-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            margin-top: 14px;
            border-top: 1px solid #f1f5f9;
        }

        /* 9. Modal Common Styles */
        .modal-header-clean {
            background: #16a34a !important;
            color: #ffffff !important;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
            padding: 16px 20px;
            border-bottom: none !important;
        }

        .modal-header-clean .modal-title,
        .modal-header-clean .modal-title *,
        .modal-header-clean h5,
        .modal-header-clean i,
        .modal-header-clean span,
        .modal-header-clean button,
        .modal-header-clean .close {
            color: #ffffff !important;
            opacity: 1 !important;
            text-shadow: none !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .btn-batal-merah {
            background-color: #dc2626 !important;
            border: 1px solid #dc2626 !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 8px 22px !important;
            height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.2) !important;
            transition: transform 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease !important;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-batal-merah:hover {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35) !important;
        }
    </style>
@stop

@section('content')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #15803d;">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #b91c1c;">
            <strong><i class="fas fa-exclamation-triangle mr-1"></i> Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1 pl-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- 1. HEADER SECTION (Cukup Judul & Deskripsi, tanpa badge berulang) --}}
    <div class="pengeluaran-page-header">
        <h1 class="pengeluaran-title">
            <span class="pengeluaran-title-icon"><i class="fas fa-wallet"></i></span>
            Manajemen Pengeluaran
        </h1>
        <p class="pengeluaran-desc">
            Catat dan pantau seluruh transaksi pengeluaran operasional, sarana prasarana, serta anggaran sekolah.
        </p>
    </div>

    {{-- 2. STAT CARDS (4 Summary Cards Sesuai Desain Sistem) --}}
    <div class="row mb-4">
        {{-- Card 1: Total Pengeluaran --}}
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="stat-card-clean">
                <div class="stat-card-icon-circle icon-circle-red">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Pengeluaran</div>
                    <div class="stat-card-value text-danger font-num">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </div>
                    <div class="stat-card-sub text-danger opacity-75">
                        Akumulasi seluruh pengeluaran
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Pengeluaran Bulan Ini --}}
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="stat-card-clean">
                <div class="stat-card-icon-circle icon-circle-gray">
                    <i class="far fa-calendar-alt"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Pengeluaran Bulan Ini</div>
                    <div class="stat-card-value text-dark font-num">
                        Rp {{ number_format($totalBulanIni, 0, ',', '.') }}
                    </div>
                    <div class="stat-card-sub text-muted">
                        {{ $namaBulanIni }} &bull; {{ $jumlahBulanIni }} transaksi
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Jumlah Transaksi --}}
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-sm-0">
            <div class="stat-card-clean">
                <div class="stat-card-icon-circle icon-circle-green">
                    <i class="far fa-file-alt"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Jumlah Transaksi</div>
                    <div class="stat-card-value text-success font-num">
                        {{ $jumlahTransaksi }} Transaksi
                    </div>
                    <div class="stat-card-sub text-muted">
                        Seluruh pengeluaran tercatat
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Pengeluaran Terbesar --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-clean">
                <div class="stat-card-icon-circle icon-circle-orange">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Pengeluaran Terbesar</div>
                    <div class="stat-card-value font-num" style="color: #f97316;">
                        Rp {{ number_format($nominalTerbesar, 0, ',', '.') }}
                    </div>
                    <div class="stat-card-sub text-muted" title="{{ $tanggalTerbesar }} - {{ $keteranganTerbesar }}">
                        {{ $tanggalTerbesar }} &bull; {{ Str::limit($keteranganTerbesar, 12) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. MAIN CARD: DATA PENGELUARAN (Search, Filter, Table, Pagination) --}}
    <div class="pengeluaran-card">
        <h5 class="pengeluaran-card-title">Data Pengeluaran</h5>

        {{-- Filter Toolbar --}}
        <div class="pengeluaran-filter-toolbar">
            <div class="pengeluaran-filter-left">
                {{-- Search Keterangan --}}
                <div class="pengeluaran-search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text"
                           id="pengeluaranSearchInput"
                           class="pengeluaran-search-input"
                           placeholder="Cari keterangan...">
                </div>

                {{-- Filter Sumber Dana --}}
                <select id="filterSumberDana" class="pengeluaran-select-filter">
                    <option value="">Semua Sumber Dana</option>
                    <option value="IPP">IPP</option>
                    <option value="DU">Daftar Ulang (DU)</option>
                    <option value="Sarpras">Sarana & Prasarana</option>
                    <option value="KI">Kegiatan Intrakurikuler</option>
                    <option value="BOS">Bantuan Operasional Sekolah (BOS)</option>
                </select>

                {{-- Filter Bulan --}}
                <select id="filterBulan" class="pengeluaran-select-filter">
                    <option value="">Semua Bulan</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                {{-- Filter Tahun --}}
                <select id="filterTahun" class="pengeluaran-select-filter">
                    <option value="">Semua Tahun</option>
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}">{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Tambah Pengeluaran --}}
            <button type="button" class="btn-tambah-pengeluaran" data-toggle="modal" data-target="#modalTambahPengeluaran">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengeluaran</span>
            </button>
        </div>

        {{-- Tabel Pengeluaran (Fit 100%, No Horizontal Scroll, No Kolom Metode) --}}
        <div class="pengeluaran-table-wrap">
            <table class="table" id="pengeluaranTable">
                <thead>
                    <tr>
                        <th style="width: 42px;" class="text-center">No</th>
                        <th style="width: 120px;" class="sortable" data-sort="tanggal" title="Urutkan Tanggal">
                            Tanggal
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th style="width: 140px;" class="sortable" data-sort="sumber" title="Urutkan Sumber Dana">
                            Sumber Dana
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th class="sortable" data-sort="keterangan" title="Urutkan Keterangan">
                            Keterangan
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th class="text-right text-end sortable" data-sort="nominal" style="width: 145px;" title="Urutkan Nominal">
                            Nominal (Rp)
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th style="width: 75px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengeluaranTableBody">
                    @forelse($pengeluaran as $item)
                        @php
                            $sumber = $item->sumber_dana;
                            $badgeClass = 'badge-sumber-ipp';
                            $sumberLabel = 'IPP';
                            if ($sumber === 'DU') {
                                $badgeClass = 'badge-sumber-du';
                                $sumberLabel = 'Daftar Ulang';
                            } elseif ($sumber === 'Sarpras') {
                                $badgeClass = 'badge-sumber-sarpras';
                                $sumberLabel = 'Sarpras';
                            } elseif ($sumber === 'KI') {
                                $badgeClass = 'badge-sumber-ki';
                                $sumberLabel = 'KI';
                            } elseif ($sumber === 'BOS') {
                                $badgeClass = 'badge-sumber-bos';
                                $sumberLabel = 'BOS';
                            }
                            $parsedDate = \Carbon\Carbon::parse($item->tanggal);
                        @endphp
                        <tr class="pengeluaran-row"
                            data-tanggal="{{ $parsedDate->format('Y-m-d') }}"
                            data-bulan="{{ $parsedDate->format('m') }}"
                            data-tahun="{{ $parsedDate->format('Y') }}"
                            data-sumber="{{ $item->sumber_dana }}"
                            data-keterangan="{{ strtolower($item->keterangan ?? '') }}"
                            data-nominal="{{ (float)$item->nominal }}"
                        >
                            {{-- 1. No --}}
                            <td class="text-center row-number font-num">
                                {{ method_exists($pengeluaran, 'firstItem') && $pengeluaran->firstItem() ? ($pengeluaran->firstItem() + $loop->index) : $loop->iteration }}
                            </td>

                            {{-- 2. Tanggal --}}
                            <td class="font-num text-secondary">
                                {{ $parsedDate->format('d-m-Y') }}
                            </td>

                            {{-- 3. Sumber Dana --}}
                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ $sumberLabel }}
                                </span>
                            </td>

                            {{-- 4. Keterangan --}}
                            <td>
                                <div class="keterangan-text">
                                    {{ $item->keterangan }}
                                </div>
                            </td>

                            {{-- 5. Nominal (Rp) --}}
                            <td class="text-right text-end font-num val-nominal-cell">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>

                            {{-- 6. Aksi --}}
                            <td class="text-center">
                                <div class="pengeluaran-actions">
                                    <button type="button"
                                            class="btn-act-edit"
                                            title="Edit"
                                            data-toggle="modal"
                                            data-target="#modalEditPengeluaran{{ $item->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button type="button"
                                            class="btn-act-hapus"
                                            title="Hapus"
                                            data-toggle="modal"
                                            data-target="#modalHapusPengeluaran{{ $item->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyStateRowOriginal">
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-folder-open fa-2x mb-2 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark mb-1">Belum ada data pengeluaran</h6>
                                <p class="text-muted small mb-0">Klik "Tambah Pengeluaran" untuk menambahkan data.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Empty State Placeholder for Filtering --}}
            <div id="filterEmptyState" class="text-center py-5 d-none">
                <i class="fas fa-search fa-2x mb-2 text-muted opacity-50 d-block"></i>
                <h6 class="font-weight-bold text-dark mb-1">Data tidak ditemukan</h6>
                <p class="text-muted small mb-0">Tidak ada transaksi pengeluaran yang cocok dengan filter pencarian.</p>
            </div>
        </div>

        {{-- Pagination Links (50 per halaman) --}}
        @if(method_exists($pengeluaran, 'hasPages') && ($pengeluaran->hasPages() || $pengeluaran->total() > 0))
            <div class="table-pagination-container px-3 pb-3">
                <div class="table-pagination-info">
                    Menampilkan <strong>{{ $pengeluaran->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $pengeluaran->lastItem() ?? 0 }}</strong> dari <strong>{{ $pengeluaran->total() }}</strong> transaksi
                </div>
                <div class="table-pagination-links">
                    {{ $pengeluaran->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>

    {{-- 4. BOTTOM ANALYTICS (Ringkasan Sumber Dana & Tren 6 Bulan Terakhir) --}}
    <div class="row">
        {{-- Left Box: Ringkasan Pengeluaran Berdasarkan Sumber Dana --}}
        <div class="col-12 col-lg-6 mb-4">
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <h6 class="analytics-card-title">Ringkasan Pengeluaran Berdasarkan Sumber Dana</h6>
                    <i class="far fa-clock text-muted"></i>
                </div>

                <div class="analytics-card-body">
                    @php
                        $sumberDisplay = [
                            'IPP'     => 'IPP',
                            'DU'      => 'Daftar Ulang',
                            'Sarpras' => 'Sarana & Prasarana',
                            'KI'      => 'Kegiatan Intrakurikuler',
                            'BOS'     => 'Dana BOS',
                        ];
                    @endphp

                    @foreach($sumberDisplay as $key => $title)
                        @php
                            $rowNominal = (float)($ringkasanSumber[$key]['nominal'] ?? 0);
                            $rowPersen = (float)($ringkasanSumber[$key]['persen'] ?? 0);
                        @endphp
                        <div class="source-breakdown-row">
                            <div class="source-name-label">{{ $title }}</div>
                            <div class="source-progress-track">
                                <div class="source-progress-fill" style="width: {{ $rowPersen }}%;"></div>
                            </div>
                            <div class="source-val-label font-num">
                                Rp {{ number_format($rowNominal, 0, ',', '.') }} ({{ $rowPersen }}%)
                            </div>
                        </div>
                    @endforeach

                    <div class="source-total-row">
                        <span class="font-weight-bold text-dark" style="font-size: 13px;">Total Pengeluaran</span>
                        <span class="font-weight-bold text-danger font-num" style="font-size: 14.5px;">
                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Box: Tren Pengeluaran (6 Bulan Terakhir) --}}
        <div class="col-12 col-lg-6 mb-4">
            <div class="analytics-card">
                <div class="analytics-card-header">
                    <h6 class="analytics-card-title" id="trenChartTitle">Tren Pengeluaran (6 Bulan Terakhir)</h6>
                    <select id="trenRangeSelect" class="custom-select custom-select-sm" style="width: 115px; height: 36px; padding: 4px 28px 4px 12px; font-size: 13px; font-weight: 600; border-radius: 8px; border: 1.5px solid #16a34a; color: #15803d; background-color: #ffffff; cursor: pointer; outline: none;">
                        <option value="6" selected>6 Bulan</option>
                        <option value="12">12 Bulan</option>
                    </select>
                </div>

                <div class="analytics-card-body position-relative" style="height: 180px;">
                    <canvas id="trenPengeluaranChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         MODALS SECTION
         ========================================================= --}}

    {{-- MODAL TAMBAH PENGELUARAN --}}
    <div class="modal fade" id="modalTambahPengeluaran" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-clean">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Tambah Data Pengeluaran
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('pengeluaran.store') }}" method="POST" id="formModalTambahPengeluaran">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            {{-- Tanggal --}}
                            <div class="col-md-6 mb-3">
                                <label for="modal_tambah_tanggal" class="form-label font-weight-bold">
                                    Tanggal Pengeluaran <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="tanggal"
                                       id="modal_tambah_tanggal"
                                       class="form-control"
                                       style="border-radius: 8px;"
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       required>
                            </div>

                            {{-- Sumber Dana --}}
                            <div class="col-md-6 mb-3">
                                <label for="modal_tambah_sumber_dana" class="form-label font-weight-bold">
                                    Sumber Dana <span class="text-danger">*</span>
                                </label>
                                <select name="sumber_dana"
                                        id="modal_tambah_sumber_dana"
                                        class="form-control font-weight-semibold"
                                        style="border-radius: 8px;"
                                        required>
                                    <option value="" disabled {{ old('sumber_dana') ? '' : 'selected' }}>-- Pilih Sumber Dana --</option>
                                    <option value="IPP" {{ old('sumber_dana') === 'IPP' ? 'selected' : '' }}>IPP (Iuran Pembayaran Pendidikan)</option>
                                    <option value="DU" {{ old('sumber_dana') === 'DU' ? 'selected' : '' }}>DU (Daftar Ulang)</option>
                                    <option value="Sarpras" {{ old('sumber_dana') === 'Sarpras' ? 'selected' : '' }}>Sarpras (Sarana Prasarana)</option>
                                    <option value="KI" {{ old('sumber_dana') === 'KI' ? 'selected' : '' }}>KI (Kegiatan Intrakurikuler)</option>
                                    <option value="BOS" {{ old('sumber_dana') === 'BOS' ? 'selected' : '' }}>BOS (Bantuan Operasional Sekolah)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Nominal --}}
                        <div class="form-group mb-3">
                            <label for="modal_tambah_nominal" class="form-label font-weight-bold">
                                Nominal Pengeluaran <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                </div>
                                <input type="number"
                                       name="nominal"
                                       id="modal_tambah_nominal"
                                       class="form-control font-weight-bold text-danger font-num"
                                       placeholder="Masukkan nominal pengeluaran..."
                                       min="1"
                                       step="1"
                                       value="{{ old('nominal') }}"
                                       style="border-radius: 0 8px 8px 0;"
                                       required>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="form-group mb-0">
                            <label for="modal_tambah_keterangan" class="form-label font-weight-bold">
                                Keterangan / Keperluan <span class="text-danger">*</span>
                            </label>
                            <textarea name="keterangan"
                                      id="modal_tambah_keterangan"
                                      rows="3"
                                      class="form-control"
                                      style="border-radius: 8px;"
                                      placeholder="Contoh: Pembelian alat tulis kantor, perbaikan sarana sekolah..."
                                      maxlength="500"
                                      required>{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT & HAPUS PER BARIS --}}
    @foreach($pengeluaran as $item)
        {{-- MODAL EDIT --}}
        <div class="modal fade" id="modalEditPengeluaran{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-clean">
                        <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Data Pengeluaran
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('pengeluaran.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">
                                        Tanggal Pengeluaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           name="tanggal"
                                           class="form-control"
                                           style="border-radius: 8px;"
                                           value="{{ old('tanggal', \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d')) }}"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">
                                        Sumber Dana <span class="text-danger">*</span>
                                    </label>
                                    <select name="sumber_dana"
                                            class="form-control font-weight-semibold"
                                            style="border-radius: 8px;"
                                            required>
                                        <option value="IPP" {{ old('sumber_dana', $item->sumber_dana) === 'IPP' ? 'selected' : '' }}>IPP (Iuran Pembayaran Pendidikan)</option>
                                        <option value="DU" {{ old('sumber_dana', $item->sumber_dana) === 'DU' ? 'selected' : '' }}>DU (Daftar Ulang)</option>
                                        <option value="Sarpras" {{ old('sumber_dana', $item->sumber_dana) === 'Sarpras' ? 'selected' : '' }}>Sarpras (Sarana Prasarana)</option>
                                        <option value="KI" {{ old('sumber_dana', $item->sumber_dana) === 'KI' ? 'selected' : '' }}>KI (Kegiatan Intrakurikuler)</option>
                                        <option value="BOS" {{ old('sumber_dana', $item->sumber_dana) === 'BOS' ? 'selected' : '' }}>BOS (Bantuan Operasional Sekolah)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">
                                    Nominal Pengeluaran <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                    </div>
                                    <input type="number"
                                           name="nominal"
                                           class="form-control font-weight-bold text-danger font-num"
                                           placeholder="Masukkan nominal pengeluaran..."
                                           min="1"
                                           step="1"
                                           value="{{ old('nominal', (int)$item->nominal) }}"
                                           style="border-radius: 0 8px 8px 0;"
                                           required>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label font-weight-bold">
                                    Keterangan / Keperluan <span class="text-danger">*</span>
                                </label>
                                <textarea name="keterangan"
                                          rows="3"
                                          class="form-control"
                                          style="border-radius: 8px;"
                                          placeholder="Keterangan pengeluaran..."
                                          maxlength="500"
                                          required>{{ old('keterangan', $item->keterangan) }}</textarea>
                            </div>
                        </div>

                        <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                            <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        <div class="modal fade" id="modalHapusPengeluaran{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header py-3 px-4 bg-light border-bottom">
                        <h5 class="modal-title font-weight-bold text-danger" style="font-size: 16px;">
                            <i class="fas fa-trash-alt text-danger mr-2"></i>
                            Konfirmasi Hapus Pengeluaran
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-2 text-dark" style="font-size: 14px;">
                            Apakah Anda yakin ingin menghapus data pengeluaran berikut:
                        </p>
                        <div class="p-3 bg-light border rounded mb-3" style="border-radius: 10px;">
                            <div class="font-weight-bold text-dark" style="font-size: 14.5px;">{{ $item->keterangan }}</div>
                            <div class="text-muted small mt-1">
                                Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }} &bull;
                                Sumber: <span class="font-weight-bold text-dark">{{ $item->sumber_dana }}</span>
                            </div>
                            <div class="text-danger font-weight-bold font-num mt-1" style="font-size: 14px;">
                                Nominal: Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="alert alert-danger mb-0 small" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Data pengeluaran yang sudah dihapus tidak dapat dikembalikan.
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-trash-alt mr-1"></i>
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('pengeluaranTable');
    const tbody = document.getElementById('pengeluaranTableBody');
    const searchInput = document.getElementById('pengeluaranSearchInput');
    const filterSumber = document.getElementById('filterSumberDana');
    const filterBulan = document.getElementById('filterBulan');
    const filterTahun = document.getElementById('filterTahun');
    const paginationControls = document.getElementById('paginationControls');
    const paginationInfo = document.getElementById('paginationInfo');
    const emptyStateFilter = document.getElementById('filterEmptyState');

    const PAGE_SIZE = 10;
    let currentPage = 1;
    let sortColumn = 'tanggal';
    let sortDirection = 'desc';

    function getAllRows() {
        return Array.from(tbody.querySelectorAll('tr.pengeluaran-row'));
    }

    function getFilteredRows() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        const sumber = filterSumber ? filterSumber.value : '';
        const bulan = filterBulan ? filterBulan.value : '';
        const tahun = filterTahun ? filterTahun.value : '';

        return getAllRows().filter(function (row) {
            const rowKet = (row.dataset.keterangan || '').toLowerCase();
            const rowSumber = row.dataset.sumber || '';
            const rowBulan = row.dataset.bulan || '';
            const rowTahun = row.dataset.tahun || '';

            const matchQuery = !query || rowKet.includes(query);
            const matchSumber = !sumber || rowSumber === sumber;
            const matchBulan = !bulan || rowBulan === bulan;
            const matchTahun = !tahun || rowTahun === tahun;

            return matchQuery && matchSumber && matchBulan && matchTahun;
        });
    }

    function renderTable() {
        const filteredRows = getFilteredRows();
        const totalItems = filteredRows.length;

        // Sort filtered rows
        filteredRows.sort(function (a, b) {
            let valA, valB;
            if (sortColumn === 'tanggal') {
                valA = a.dataset.tanggal || '';
                valB = b.dataset.tanggal || '';
                return sortDirection === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
            }
            if (sortColumn === 'sumber') {
                valA = a.dataset.sumber || '';
                valB = b.dataset.sumber || '';
                return sortDirection === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
            }
            if (sortColumn === 'keterangan') {
                valA = a.dataset.keterangan || '';
                valB = b.dataset.keterangan || '';
                return sortDirection === 'asc' ? valA.localeCompare(valB, 'id') : valB.localeCompare(valA, 'id');
            }
            if (sortColumn === 'nominal') {
                valA = Number(a.dataset.nominal || 0);
                valB = Number(b.dataset.nominal || 0);
                return sortDirection === 'asc' ? valA - valB : valB - valA;
            }
            return 0;
        });

        // Hide all rows initially
        getAllRows().forEach(function (row) {
            row.style.display = 'none';
        });

        // Display all filtered rows inside scrollable table container
        filteredRows.forEach(function (row, idx) {
            row.style.display = '';
            const numCell = row.querySelector('.row-number');
            if (numCell) {
                numCell.textContent = idx + 1;
            }
            tbody.appendChild(row);
        });

        // Toggle Empty State
        if (totalItems === 0) {
            table.style.display = 'none';
            if (emptyStateFilter) emptyStateFilter.classList.remove('d-none');
            if (paginationInfo) paginationInfo.textContent = 'Menampilkan 0 data';
        } else {
            table.style.display = '';
            if (emptyStateFilter) emptyStateFilter.classList.add('d-none');
            if (paginationInfo) {
                paginationInfo.textContent = `Menampilkan total ${totalItems} data pengeluaran (scroll di dalam tabel untuk melihat lainnya)`;
            }
        }

        updateSortIcons();
    }

    function updateSortIcons() {
        document.querySelectorAll('#pengeluaranTable th.sortable').forEach(function (th) {
            th.classList.remove('sort-active');
            const icon = th.querySelector('.sort-icon');
            if (icon) icon.className = 'fas fa-sort sort-icon';
        });

        const activeTh = document.querySelector(`#pengeluaranTable th[data-sort="${sortColumn}"]`);
        if (activeTh) {
            activeTh.classList.add('sort-active');
            const icon = activeTh.querySelector('.sort-icon');
            if (icon) {
                icon.className = (sortDirection === 'asc') ? 'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
            }
        }
    }

    // Event Listeners for Filters
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            currentPage = 1;
            renderTable();
        });
    }

    if (filterSumber) {
        filterSumber.addEventListener('change', function () {
            currentPage = 1;
            renderTable();
        });
    }

    if (filterBulan) {
        filterBulan.addEventListener('change', function () {
            currentPage = 1;
            renderTable();
        });
    }

    if (filterTahun) {
        filterTahun.addEventListener('change', function () {
            currentPage = 1;
            renderTable();
        });
    }

    // Event Listeners for Sort
    document.querySelectorAll('#pengeluaranTable th.sortable').forEach(function (th) {
        th.addEventListener('click', function () {
            const col = this.dataset.sort;
            if (sortColumn === col) {
                sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';
            } else {
                sortColumn = col;
                sortDirection = 'asc';
            }
            renderTable();
        });
    });

    // Initial Table Render
    renderTable();

    // ===============================================================
    // CHART JS: TREN PENGELUARAN
    // ===============================================================
    const ctx = document.getElementById('trenPengeluaranChart');
    if (ctx) {
        const data6 = @json($tren6Bulan);
        const data12 = @json($tren12Bulan);

        const labels6 = data6.map(item => item.bulan);
        const values6 = data6.map(item => item.nominal);

        const labels12 = data12.map(item => item.bulan);
        const values12 = data12.map(item => item.nominal);

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 160);
        gradient.addColorStop(0, 'rgba(239, 68, 68, 0.28)');
        gradient.addColorStop(1, 'rgba(239, 68, 68, 0.00)');

        const trenChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels6,
                datasets: [{
                    label: 'Pengeluaran',
                    data: values6,
                    borderColor: '#ef4444',
                    borderWidth: 2.2,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4.5,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#dc2626',
                    pointHoverBorderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y || 0);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11.5 },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#94a3b8',
                            callback: function (value) {
                                if (value >= 1000000) {
                                    return (value / 1000000) + ' jt';
                                } else if (value >= 1000) {
                                    return (value / 1000) + ' rb';
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // Switcher 6 Bulan vs 12 Bulan
        const rangeSelect = document.getElementById('trenRangeSelect');
        const chartTitle = document.getElementById('trenChartTitle');

        if (rangeSelect) {
            rangeSelect.addEventListener('change', function () {
                if (this.value === '12') {
                    trenChart.data.labels = labels12;
                    trenChart.data.datasets[0].data = values12;
                    if (chartTitle) chartTitle.textContent = 'Tren Pengeluaran (12 Bulan Terakhir)';
                } else {
                    trenChart.data.labels = labels6;
                    trenChart.data.datasets[0].data = values6;
                    if (chartTitle) chartTitle.textContent = 'Tren Pengeluaran (6 Bulan Terakhir)';
                }
                trenChart.update();
            });
        }
    }
});
</script>
@stop