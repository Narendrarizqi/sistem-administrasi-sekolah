@extends('adminlte::page')

@section('title', 'Data Siswa')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ file_exists(public_path('css/custom.css')) ? filemtime(public_path('css/custom.css')) : time() }}">
    <style>
        /* Scoped Data Siswa Page Styling */
        .siswa-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .siswa-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .siswa-header-info {
            flex: 1;
            min-width: 260px;
        }

        .siswa-page-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .siswa-page-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #f0fdf4;
            color: #16a34a;
            border-radius: 8px;
            font-size: 15px;
        }

        .siswa-page-desc {
            font-size: 12.5px;
            color: #64748b;
            margin-bottom: 0;
            line-height: 1.4;
        }

        .siswa-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-info-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-info-pill i {
            font-size: 12px;
        }

        .badge-info-pill.badge-ta {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #15803d;
        }

        /* Card Container */
        .siswa-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .siswa-card-body {
            padding: 18px 20px;
        }

        /* Toolbar (Search & Actions) */
        .siswa-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .siswa-search-box {
            position: relative;
            flex: 1;
            min-width: 220px;
            max-width: 480px;
        }

        .siswa-search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
            pointer-events: none;
        }

        .siswa-search-input {
            width: 100%;
            height: 38px;
            padding: 8px 12px 8px 36px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            color: #0f172a;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .siswa-search-input:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .siswa-toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
        }

        /* Action Buttons Toolbar */
        .btn-toolbar-naik {
            height: 38px;
            padding: 0 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #1e293b !important;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.18s ease;
            cursor: pointer;
            white-space: nowrap;
            text-decoration: none !important;
        }

        .btn-toolbar-naik i {
            color: #d97706;
            transition: color 0.18s ease;
        }

        .btn-toolbar-naik:hover,
        .btn-toolbar-naik:focus {
            background: #fffbeb;
            border-color: #fde68a;
            color: #0f172a !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .btn-toolbar-naik:hover i,
        .btn-toolbar-naik:focus i {
            color: #b45309;
        }

        .btn-toolbar-naik:active {
            background: #fef3c7;
            border-color: #fcd34d;
            color: #0f172a !important;
            transform: translateY(1px);
        }

        .btn-toolbar-import {
            height: 38px;
            padding: 0 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #1e293b !important;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.18s ease;
            cursor: pointer;
            white-space: nowrap;
            text-decoration: none !important;
        }

        .btn-toolbar-import i {
            color: #16a34a;
            transition: color 0.18s ease;
        }

        .btn-toolbar-import:hover,
        .btn-toolbar-import:focus {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #15803d !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        .btn-toolbar-import:active {
            background: #dcfce7;
            border-color: #86efac;
            color: #15803d !important;
            transform: translateY(1px);
        }

        /* Import Modal Specific Styles */
        .import-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 26px 20px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .import-dropzone:hover,
        .import-dropzone.dragover {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        .import-dropzone i.upload-icon {
            font-size: 36px;
            color: #16a34a;
            margin-bottom: 8px;
            display: block;
        }

        .import-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .import-stat-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .preview-table-container {
            max-height: 340px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .preview-table-container table {
            margin-bottom: 0;
            font-size: 12.5px;
        }

        .preview-table-container thead th {
            position: sticky;
            top: 0;
            background: #f8fafc !important;
            z-index: 2;
            border-top: none;
            padding: 10px 12px;
        }

        .preview-table-container tbody td {
            padding: 9px 12px;
            vertical-align: middle;
        }

        .status-badge-ready {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-badge-duplicate {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-badge-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .filter-tab-btn {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .filter-tab-btn.active {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff;
        }

        .btn-toolbar-tambah {
            height: 38px;
            padding: 0 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #16a34a;
            border: 1px solid #16a34a;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.18s ease;
            cursor: pointer;
            white-space: nowrap;
            text-decoration: none !important;
        }

        .btn-toolbar-tambah:hover,
        .btn-toolbar-tambah:focus {
            background: #15803d;
            border-color: #15803d;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
        }

        /* Table Design */
        .siswa-table-wrapper {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            background: #ffffff;
        }

        #siswaTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }

        #siswaTable thead th {
            background: #f8fafc !important;
            color: #334155 !important;
            font-weight: 650 !important;
            font-size: 12.5px;
            padding: 10px 14px;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }

        #siswaTable tbody td {
            padding: 10px 14px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.35;
        }

        #siswaTable tbody tr:last-child td {
            border-bottom: none;
        }

        #siswaTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #siswaTable tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Table Column Cells */
        .col-no-cell {
            width: 45px;
            text-align: center;
            color: #64748b;
            font-weight: 500;
            font-size: 12.5px;
        }

        .col-nis-cell {
            width: 130px;
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            color: #334155;
            font-weight: 500;
            white-space: nowrap;
        }

        .col-nama-cell {
            min-width: 180px;
        }

        .student-name-text {
            font-weight: 600;
            color: #0f172a;
            font-size: 13.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .student-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: #f1f5f9;
            color: #64748b;
            border-radius: 50%;
            font-size: 11px;
            flex-shrink: 0;
        }

        .col-kelas-cell {
            width: 120px;
            text-align: center;
        }

        .badge-kelas-simple {
            display: inline-block;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 11.5px;
            font-weight: 600;
            padding: 2.5px 9px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .col-aksi-cell {
            width: 90px;
            text-align: center;
            white-space: nowrap;
        }

        /* Sortable Column Headers */
        #siswaTable th.sortable {
            padding: 0 !important;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #siswaTable th.sortable .sort-header-link {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            padding: 10px 14px;
            color: #334155 !important;
            font-weight: 650 !important;
            font-size: 12.5px;
            text-decoration: none !important;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        #siswaTable th.col-kelas-cell.sortable .sort-header-link {
            justify-content: center;
        }

        #siswaTable th.sortable:hover .sort-header-link {
            background-color: #f0fdf4 !important;
            color: #15803d !important;
        }

        #siswaTable .sort-icon {
            margin-left: 6px;
            font-size: 11px;
            opacity: 0.45;
            transition: opacity 0.15s ease, color 0.15s ease;
        }

        #siswaTable th.sort-active .sort-header-link {
            color: #15803d !important;
            background-color: #f0fdf4 !important;
        }

        #siswaTable th.sort-active .sort-icon {
            opacity: 1;
            color: #15803d;
        }

        /* Table Row Action Buttons (Standar Seragam Sistem) */
        .siswa-action-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            white-space: nowrap !important;
        }

        .btn-act-edit,
        .btn-act-hapus {
            width: 24px;
            height: 24px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            font-size: 11px;
            box-shadow: none;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-act-edit {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #d97706 !important;
        }

        .btn-act-edit:hover,
        .btn-act-edit:focus {
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

        .btn-act-hapus:hover,
        .btn-act-hapus:focus {
            background: #dc2626;
            border-color: #dc2626;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220, 38, 38, 0.25);
        }

        /* Modal Custom Header & Buttons */
        .modal-header-clean {
            background: #16a34a;
            color: #ffffff;
            padding: 14px 18px;
            border-top-left-radius: calc(0.5rem - 1px);
            border-top-right-radius: calc(0.5rem - 1px);
        }

        .modal-header-clean .modal-title,
        .modal-header-clean .modal-title * {
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
        }

        .modal-header-clean .close {
            color: #ffffff !important;
            opacity: 1;
            text-shadow: none;
        }

        .modal-header-danger {
            background: #ffffff;
            border-bottom: 1px solid #fee2e2;
            padding: 14px 18px;
        }

        .modal-header-danger .modal-title {
            color: #dc2626;
            font-size: 15px;
            font-weight: 700;
        }

        .btn-clear-search {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            text-decoration: none !important;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
            transition: color 0.15s ease;
        }

        .btn-clear-search:hover {
            color: #ef4444;
        }

        /* Pagination Styling */
        .siswa-pagination-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
        }

        .siswa-pagination-info {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .siswa-pagination-links nav {
            display: flex;
            align-items: center;
        }

        .siswa-pagination-links .pagination {
            margin-bottom: 0;
            gap: 4px;
        }

        .siswa-pagination-links .page-item .page-link {
            color: #334155;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px !important;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 13px;
            line-height: 1.4;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .siswa-pagination-links .page-item.active .page-link {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.28);
        }

        .siswa-pagination-links .page-item .page-link:hover {
            background-color: #f0fdf4;
            border-color: #86efac;
            color: #15803d;
            transform: translateY(-1px);
        }

        .siswa-pagination-links .page-item.disabled .page-link {
            color: #94a3b8;
            background-color: #f8fafc;
            border-color: #e2e8f0;
            box-shadow: none;
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .siswa-page-header {
                padding: 14px 16px;
            }
            .siswa-card-body {
                padding: 14px 14px;
            }
            .siswa-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .siswa-search-box {
                max-width: 100%;
            }
            .siswa-toolbar-actions {
                justify-content: flex-end;
            }
            .siswa-pagination-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
@stop

@section('content')

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px;" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px;" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @php
        $tahunAjaranNama = $tahunAjaranNama ?? date('Y') . '/' . (date('Y') + 1);
    @endphp

    {{-- 1. PAGE HEADER (Compact, Natural, Informative) --}}
    <div class="siswa-page-header">
        <div class="siswa-header-top">
            <div class="siswa-header-info">
                <h1 class="siswa-page-title">
                    <span class="siswa-page-icon"><i class="fas fa-user-graduate"></i></span>
                    Master Data Siswa
                </h1>
                <p class="siswa-page-desc">
                    Kelola basis data siswa aktif, nomor induk siswa (NIS), kelas, serta manajemen kenaikan kelas otomatis.
                </p>
            </div>
            <div class="siswa-header-badges">
                <span class="badge-info-pill">
                    <i class="fas fa-users text-muted"></i>
                    Total Siswa: {{ $totalSiswa ?? $siswa->total() }} Orang
                </span>
                <span class="badge-info-pill badge-ta">
                    <i class="far fa-calendar-alt"></i>
                    Tahun Ajaran: {{ $tahunAjaranNama }}
                </span>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CARD & TABLE --}}
    <div class="siswa-card">
        <div class="siswa-card-body">

            {{-- Toolbar: Search on Left, Action Buttons on Right --}}
            <div class="siswa-toolbar">
                <form method="GET" action="{{ route('siswa.index') }}" class="siswa-search-box">
                    <i class="fas fa-search"></i>
                    <input type="hidden" name="sort" value="{{ request('sort', 'nama') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                    <input
                        type="text"
                        name="search"
                        id="siswaSearchInput"
                        class="siswa-search-input"
                        placeholder="Cari NIS, nama, atau kelas..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    @if(request('search'))
                        <a href="{{ route('siswa.index', ['sort' => request('sort', 'nama'), 'direction' => request('direction', 'asc')]) }}" class="btn-clear-search" title="Hapus Pencarian">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    @endif
                </form>

                <div class="siswa-toolbar-actions">
                    <button
                        type="button"
                        class="btn-toolbar-import"
                        data-toggle="modal"
                        data-target="#modalImportSiswa"
                        title="Import Data Siswa dari Berkas (.xlsx, .xls, .csv, .docx, .txt, .pdf)"
                    >
                        <i class="fas fa-file-import"></i>
                        <span>Import Data</span>
                    </button>

                    <button
                        type="button"
                        class="btn-toolbar-naik"
                        data-toggle="modal"
                        data-target="#modalNaikKelas"
                        title="Naikkan seluruh siswa ke tingkat kelas berikutnya"
                    >
                        <i class="fas fa-arrow-up"></i>
                        <span>Naik Kelas</span>
                    </button>

                    <button
                        type="button"
                        class="btn-toolbar-tambah"
                        data-toggle="modal"
                        data-target="#modalTambahSiswa"
                        title="Tambah Data Siswa Baru"
                    >
                        <i class="fas fa-plus"></i>
                        <span>Tambah Siswa</span>
                    </button>
                </div>
            </div>

            {{-- Indikator Pencarian Aktif --}}
            @if(request('search'))
                <div class="mb-3 d-flex align-items-center justify-content-between p-2 px-3 bg-light border rounded" style="border-radius: 8px; font-size: 13px;">
                    <span>
                        <i class="fas fa-search text-success mr-1"></i>
                        Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong> &mdash; Ditemukan {{ $siswa->total() }} siswa
                    </span>
                    <a href="{{ route('siswa.index') }}" class="text-danger font-weight-bold" style="font-size: 12.5px; text-decoration: none;">
                        <i class="fas fa-times mr-1"></i> Reset Pencarian
                    </a>
                </div>
            @endif

            @php
                $currentSort = $sort ?? request('sort', 'nama');
                $currentDir = $direction ?? request('direction', 'asc');
                $sortUrl = function($column) use ($currentSort, $currentDir) {
                    $nextDir = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';
                    return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDir, 'page' => 1]);
                };
            @endphp

            {{-- Table Container --}}
            @if($siswa->count() > 0)
                <div class="siswa-table-wrapper">
                    <table class="table" id="siswaTable">
                        <thead>
                            <tr>
                                <th class="col-no-cell">No</th>
                                <th class="col-nis-cell sortable {{ $currentSort === 'nis' ? 'sort-active' : '' }}">
                                    <a href="{{ $sortUrl('nis') }}" class="sort-header-link" title="Klik untuk mengurutkan berdasarkan NIS ({{ $currentSort === 'nis' && $currentDir === 'asc' ? 'Terbesar ke Terkecil' : 'Terkecil ke Terbesar' }})">
                                        <span>NIS</span>
                                        @if($currentSort === 'nis')
                                            <i class="fas fa-sort-{{ $currentDir === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                        @else
                                            <i class="fas fa-sort sort-icon"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="col-nama-cell sortable {{ $currentSort === 'nama' ? 'sort-active' : '' }}">
                                    <a href="{{ $sortUrl('nama') }}" class="sort-header-link" title="Klik untuk mengurutkan berdasarkan Nama Siswa ({{ $currentSort === 'nama' && $currentDir === 'asc' ? 'Z ke A' : 'A ke Z' }})">
                                        <span>Nama Siswa</span>
                                        @if($currentSort === 'nama')
                                            <i class="fas fa-sort-{{ $currentDir === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                        @else
                                            <i class="fas fa-sort sort-icon"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="col-kelas-cell sortable {{ $currentSort === 'kelas' ? 'sort-active' : '' }}">
                                    <a href="{{ $sortUrl('kelas') }}" class="sort-header-link" title="Klik untuk mengurutkan berdasarkan Kelas ({{ $currentSort === 'kelas' && $currentDir === 'asc' ? 'Tingkat Tinggi ke Rendah' : 'Tingkat Rendah ke Tinggi' }})">
                                        <span>Kelas</span>
                                        @if($currentSort === 'kelas')
                                            <i class="fas fa-sort-{{ $currentDir === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                        @else
                                            <i class="fas fa-sort sort-icon"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="col-aksi-cell">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $item)
                                <tr data-nis="{{ $item->nis ?? '' }}" data-nama="{{ $item->nama ?? '' }}" data-kelas="{{ $item->kelas ?? '' }}">
                                    <td class="col-no-cell">
                                        {{ $siswa->firstItem() ? ($siswa->firstItem() + $loop->index) : $loop->iteration }}
                                    </td>

                                    <td class="col-nis-cell">
                                        {{ $item->nis ?? '-' }}
                                    </td>

                                    <td class="col-nama-cell">
                                        <div class="student-name-text">
                                            <span class="student-icon">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <span>{{ $item->nama ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td class="col-kelas-cell">
                                        <span class="badge-kelas-simple">
                                            {{ $item->kelas ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="col-aksi-cell">
                                         <div class="siswa-action-group">
                                             <button
                                                 type="button"
                                                 class="btn-act-edit"
                                                 title="Edit Data Siswa"
                                                 data-toggle="modal"
                                                 data-target="#modalEditSiswa{{ $item->id }}"
                                             >
                                                 <i class="fas fa-pen"></i>
                                             </button>

                                             <button
                                                 type="button"
                                                 class="btn-act-hapus"
                                                 title="Hapus Data Siswa"
                                                 data-toggle="modal"
                                                 data-target="#modalHapusSiswa{{ $item->id }}"
                                             >
                                                 <i class="fas fa-trash-alt"></i>
                                             </button>
                                         </div>
                                     </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links (25 per halaman) --}}
                @if($siswa->hasPages() || $siswa->total() > 0)
                    <div class="siswa-pagination-container">
                        <div class="siswa-pagination-info">
                            Menampilkan <strong>{{ $siswa->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $siswa->lastItem() ?? 0 }}</strong> dari <strong>{{ $siswa->total() }}</strong> siswa
                        </div>
                        <div class="siswa-pagination-links">
                            {{ $siswa->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5 border rounded" style="background: #f8fafc; border-color: #e2e8f0 !important; border-radius: 10px;">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Belum Ada Data Siswa</h5>
                    <p class="text-muted small mb-3">
                        Silakan tambahkan data siswa aktif terlebih dahulu.
                    </p>
                    <button type="button" class="btn-toolbar-tambah" data-toggle="modal" data-target="#modalTambahSiswa">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Siswa Baru</span>
                    </button>
                </div>
            @endif

        </div>
    </div>

    {{-- MODAL EDIT DATA SISWA (Identical style to Modal Tambah Siswa) --}}
    @foreach($siswa as $item)
        <div class="modal fade" id="modalEditSiswa{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditSiswaLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-clean">
                        <h5 class="modal-title font-weight-bold text-white" id="modalEditSiswaLabel{{ $item->id }}" style="font-size: 16px;">
                            <i class="fas fa-user-edit mr-2 text-white"></i>
                            Edit Data Siswa
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('siswa.update', $item->id) }}" method="POST" id="formModalEditSiswa{{ $item->id }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-body p-4">

                            {{-- Section 1: Input NIS --}}
                            <div class="form-group row mb-3">
                                <label for="modal_edit_nis_{{ $item->id }}" class="col-sm-3 col-form-label font-weight-bold">
                                    NIS <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input
                                        type="text"
                                        name="nis"
                                        id="modal_edit_nis_{{ $item->id }}"
                                        class="form-control @error('nis') is-invalid @enderror"
                                        value="{{ old('nis', $item->nis) }}"
                                        placeholder="Masukkan Nomor Induk Siswa"
                                        maxlength="30"
                                        required
                                        style="border-radius: 8px;"
                                    >
                                    @error('nis')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Section 2: Input Nama Siswa --}}
                            <div class="form-group row mb-3">
                                <label for="modal_edit_nama_{{ $item->id }}" class="col-sm-3 col-form-label font-weight-bold">
                                    Nama Siswa <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input
                                        type="text"
                                        name="nama"
                                        id="modal_edit_nama_{{ $item->id }}"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $item->nama) }}"
                                        placeholder="Masukkan nama lengkap siswa"
                                        maxlength="255"
                                        required
                                        style="border-radius: 8px;"
                                    >
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Section 3: Pilih Kelas --}}
                            <div class="form-group row mb-2">
                                <label for="modal_edit_kelas_{{ $item->id }}" class="col-sm-3 col-form-label font-weight-bold">
                                    Kelas <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select
                                        name="kelas"
                                        id="modal_edit_kelas_{{ $item->id }}"
                                        class="form-control @error('kelas') is-invalid @enderror"
                                        required
                                        style="border-radius: 8px;"
                                    >
                                        <option value="" disabled>-- Pilih Kelas --</option>
                                        <option value="X TKJ" {{ old('kelas', $item->kelas) == 'X TKJ' ? 'selected' : '' }}>X TKJ</option>
                                        <option value="X TKR 1" {{ old('kelas', $item->kelas) == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                                        <option value="X TKR 2" {{ old('kelas', $item->kelas) == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                                        <option value="XI TKJ" {{ old('kelas', $item->kelas) == 'XI TKJ' ? 'selected' : '' }}>XI TKJ</option>
                                        <option value="XI TKR 1" {{ old('kelas', $item->kelas) == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                                        <option value="XI TKR 2" {{ old('kelas', $item->kelas) == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                                        <option value="XII TKJ" {{ old('kelas', $item->kelas) == 'XII TKJ' ? 'selected' : '' }}>XII TKJ</option>
                                        <option value="XII TKR 1" {{ old('kelas', $item->kelas) == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                                        <option value="XII TKR 2" {{ old('kelas', $item->kelas) == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                                        <option value="Lulus" {{ old('kelas', $item->kelas) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    </select>
                                    @error('kelas')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
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
    @endforeach

    {{-- MODAL HAPUS SISWA (Cleanly Separated Outside Table) --}}
    @foreach($siswa as $item)
        <div class="modal fade" id="modalHapusSiswa{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-header modal-header-danger">
                        <h5 class="modal-title">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Konfirmasi Hapus Siswa
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-4">
                        <p class="mb-2 text-dark" style="font-size: 13.5px;">
                            Apakah Anda yakin ingin menghapus data siswa berikut?
                        </p>

                        <div class="p-3 bg-light border rounded mb-3" style="border-radius: 8px;">
                            <div class="font-weight-bold text-dark" style="font-size: 14px;">{{ $item->nama ?? '-' }}</div>
                            <div class="text-muted small mt-1">NIS: {{ $item->nis ?? '-' }} | Kelas: {{ $item->kelas ?? '-' }}</div>
                        </div>

                        <div class="alert alert-danger mb-0 small py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Data siswa beserta seluruh riwayat pembayarannya yang terhapus tidak dapat dikembalikan.
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger font-weight-bold px-3" style="height: 38px; border-radius: 8px;">
                                <i class="fas fa-trash-alt mr-1"></i> Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL TAMBAH DATA SISWA (Identical style to Modal Tambah Tagihan IPP) --}}
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1" role="dialog" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-clean">
                    <h5 class="modal-title font-weight-bold" id="modalTambahSiswaLabel" style="font-size: 16px;">
                        <i class="fas fa-user-plus mr-2 text-white"></i>
                        Tambah Data Siswa
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('siswa.store') }}" method="POST" id="formModalTambahSiswa">
                    @csrf
                    <div class="modal-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3 py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Data belum dapat disimpan:</strong>
                                <ul class="mb-0 mt-1 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Section 1: Input NIS --}}
                        <div class="form-group row mb-3">
                            <label for="modal_nis" class="col-sm-3 col-form-label font-weight-bold">
                                NIS <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    name="nis"
                                    id="modal_nis"
                                    class="form-control @error('nis') is-invalid @enderror"
                                    value="{{ old('nis') }}"
                                    placeholder="Masukkan Nomor Induk Siswa"
                                    maxlength="30"
                                    required
                                    style="border-radius: 8px;"
                                >
                                @error('nis')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Section 2: Input Nama Siswa --}}
                        <div class="form-group row mb-3">
                            <label for="modal_nama" class="col-sm-3 col-form-label font-weight-bold">
                                Nama Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    name="nama"
                                    id="modal_nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}"
                                    placeholder="Masukkan nama lengkap siswa"
                                    maxlength="255"
                                    required
                                    style="border-radius: 8px;"
                                >
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Section 3: Pilih Kelas --}}
                        <div class="form-group row mb-2">
                            <label for="modal_kelas" class="col-sm-3 col-form-label font-weight-bold">
                                Kelas <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select
                                    name="kelas"
                                    id="modal_kelas"
                                    class="form-control @error('kelas') is-invalid @enderror"
                                    required
                                    style="border-radius: 8px;"
                                >
                                    <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>
                                        -- Pilih Kelas --
                                    </option>
                                    <option value="X TKJ" {{ old('kelas') == 'X TKJ' ? 'selected' : '' }}>X TKJ</option>
                                    <option value="X TKR 1" {{ old('kelas') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                                    <option value="X TKR 2" {{ old('kelas') == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                                    <option value="XI TKJ" {{ old('kelas') == 'XI TKJ' ? 'selected' : '' }}>XI TKJ</option>
                                    <option value="XI TKR 1" {{ old('kelas') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                                    <option value="XI TKR 2" {{ old('kelas') == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                                    <option value="XII TKJ" {{ old('kelas') == 'XII TKJ' ? 'selected' : '' }}>XII TKJ</option>
                                    <option value="XII TKR 1" {{ old('kelas') == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                                    <option value="XII TKR 2" {{ old('kelas') == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                                </select>
                                @error('kelas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" id="btnSubmitTambahSiswa" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL NAIK KELAS (Clean Confirmation) --}}
    <div class="modal fade" id="modalNaikKelas" tabindex="-1" role="dialog" aria-labelledby="modalNaikKelasLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header modal-header-clean">
                    <h5 class="modal-title font-weight-bold" id="modalNaikKelasLabel">
                        <i class="fas fa-arrow-up mr-2 text-white"></i>
                        Konfirmasi Kenaikan Kelas Otomatis
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <p class="mb-3 text-dark" style="font-size: 14px;">
                        Apakah Anda yakin ingin menaikkan seluruh siswa ke tingkat kelas berikutnya untuk tahun ajaran baru?
                    </p>

                    <div class="p-3 bg-light border rounded mb-3" style="border-radius: 8px;">
                        <div class="font-weight-bold text-dark mb-2" style="font-size: 14px;">
                            <i class="fas fa-sync-alt text-success mr-1"></i> Alur Kenaikan Kelas Otomatis:
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="font-size: 14px;">
                            <span class="text-dark font-weight-500">Kelas X (Semua Jurusan)</span>
                            <span class="font-weight-bold text-success">
                                <i class="fas fa-arrow-right mr-1 text-success" style="font-size: 12px;"></i> Kelas XI
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="font-size: 14px;">
                            <span class="text-dark font-weight-500">Kelas XI (Semua Jurusan)</span>
                            <span class="font-weight-bold text-success">
                                <i class="fas fa-arrow-right mr-1 text-success" style="font-size: 12px;"></i> Kelas XII
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2" style="font-size: 14px;">
                            <span class="text-dark font-weight-500">Kelas XII (Semua Jurusan)</span>
                            <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 13px; border-radius: 6px;">
                                Lulus
                            </span>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0 py-2 px-3" style="border-radius: 8px; font-size: 13px; line-height: 1.4;">
                        <i class="fas fa-info-circle mr-1"></i>
                        Tagihan yang belum lunas pada tahun ini akan otomatis terbawa sebagai tunggakan pada tahun ajaran baru.
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                    <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                        Batal
                    </button>

                    <form action="{{ route('siswa.naik-kelas') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success font-weight-bold px-3" style="height: 38px; border-radius: 8px;">
                            <i class="fas fa-arrow-up mr-1"></i> Ya, Naikkan Kelas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL IMPORT DATA SISWA (Multi-step: Upload, Mapping, Preview & Validation) --}}
    <div class="modal fade" id="modalImportSiswa" tabindex="-1" role="dialog" aria-labelledby="modalImportSiswaLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-clean">
                    <h5 class="modal-title font-weight-bold text-white" id="modalImportSiswaLabel" style="font-size: 16px;">
                        <i class="fas fa-file-import mr-2 text-white"></i>
                        Import Data Siswa
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- STEP 1: UPLOAD FILE & DOWNLOAD TEMPLATE --}}
                <div id="importStepUpload" class="import-step-container">
                    <div class="modal-body p-4">
                        {{-- Info Banner & Template Download --}}
                        <div class="p-3 mb-3" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="font-weight-bold text-success mb-1" style="font-size: 14px;">
                                        <i class="fas fa-info-circle mr-1"></i> Format Berkas & Data yang Dibutuhkan
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Sistem hanya mengambil 3 data utama: <strong>Nama</strong>, <strong>NIS</strong>, dan <strong>Kelas</strong>. Kolom lain dalam file akan diabaikan secara otomatis.
                                    </p>
                                    <div class="mt-2 d-flex align-items-center gap-1 flex-wrap">
                                        <span class="badge badge-light border text-dark">.xlsx</span>
                                        <span class="badge badge-light border text-dark">.xls</span>
                                        <span class="badge badge-light border text-dark">.csv</span>
                                        <span class="badge badge-light border text-dark">.docx</span>
                                        <span class="badge badge-light border text-dark">.txt</span>
                                        <span class="badge badge-light border text-dark">.pdf (berisi teks)</span>
                                    </div>
                                </div>
                                <div class="mt-2 mt-sm-0">
                                    <a href="{{ route('siswa.import.template') }}" class="btn btn-sm btn-outline-success font-weight-semibold px-3 py-2" style="border-radius: 8px; white-space: nowrap;">
                                        <i class="fas fa-download mr-1"></i> Download Template Excel
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Alert Error Container --}}
                        <div id="importUploadError" class="alert alert-danger alert-dismissible fade show d-none" role="alert" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span id="importUploadErrorMessage">Terjadi kesalahan.</span>
                        </div>

                        {{-- Drag & Drop Upload Zone --}}
                        <div class="import-dropzone" id="importDropzone">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h6 class="font-weight-bold text-dark mb-1" style="font-size: 14px;">
                                Seret & Lepas Berkas ke Sini atau Klik untuk Memilih
                            </h6>
                            <p class="text-muted small mb-2">
                                Mendukung format Excel (.xlsx, .xls), CSV, Word (.docx), Teks (.txt), dan PDF
                            </p>
                            <input type="file" id="importFileInput" class="d-none" accept=".xlsx,.xls,.csv,.docx,.txt,.pdf">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1 font-weight-semibold" style="border-radius: 6px;" onclick="document.getElementById('importFileInput').click();">
                                <i class="fas fa-folder-open mr-1"></i> Pilih Berkas dari Komputer
                            </button>
                        </div>

                        {{-- Selected File Info --}}
                        <div id="selectedFileInfo" class="mt-3 p-3 bg-light border rounded d-none" style="border-radius: 8px;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-file-alt text-success fa-2x"></i>
                                    <div>
                                        <strong id="selectedFileName" class="text-dark d-block" style="font-size: 13px;">nama-file.xlsx</strong>
                                        <small id="selectedFileSize" class="text-muted">0 KB</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-danger" id="btnRemoveFile" style="border-radius: 4px;">
                                    <i class="fas fa-times"></i> Ganti
                                </button>
                            </div>
                        </div>

                        {{-- Loading Indicator --}}
                        <div id="importUploadLoading" class="text-center py-4 d-none">
                            <div class="spinner-border text-success mb-2" role="status" style="width: 2.2rem; height: 2.2rem;">
                                <span class="sr-only">Memuat...</span>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-0" style="font-size: 13.5px;">Membaca dan Menganalisis Berkas...</h6>
                            <small class="text-muted">Mohon tunggu sebentar, data sedang diproses dan divalidasi.</small>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" class="btn btn-success font-weight-bold px-4" id="btnSubmitParse" style="border-radius: 8px; height: 38px;" disabled>
                            <i class="fas fa-search mr-1"></i> Baca & Analisis Berkas
                        </button>
                    </div>
                </div>

                {{-- STEP 2: COLUMN MAPPING (HANYA DITAMPILKAN JIKA KOLOM BELUM OTOMATIS COCOK) --}}
                <div id="importStepMapping" class="import-step-container d-none">
                    <div class="modal-body p-4">
                        <div class="alert alert-warning mb-3" style="border-radius: 8px; font-size: 13px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Nama kolom pada berkas tidak dapat dikenali secara pasti. Silakan tentukan pemetaan kolom berikut:
                        </div>

                        <div class="card border mb-0" style="border-radius: 10px;">
                            <div class="card-header bg-white font-weight-bold py-2" style="font-size: 13.5px;">
                                <i class="fas fa-columns text-success mr-1"></i> Pemetaan Kolom Berkas ke Data Siswa
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="mappingColNama" class="font-weight-bold small text-dark">
                                            Kolom Nama Siswa <span class="text-danger">*</span>
                                        </label>
                                        <select id="mappingColNama" class="form-control" style="border-radius: 8px;"></select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="mappingColNis" class="font-weight-bold small text-dark">
                                            Kolom NIS <span class="text-danger">*</span>
                                        </label>
                                        <select id="mappingColNis" class="form-control" style="border-radius: 8px;"></select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="mappingColKelas" class="font-weight-bold small text-dark">
                                            Kolom Kelas <span class="text-danger">*</span>
                                        </label>
                                        <select id="mappingColKelas" class="form-control" style="border-radius: 8px;"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-between">
                        <button type="button" class="btn btn-batal-merah px-4" id="btnBackToUploadFromMapping">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Unggah
                        </button>
                        <button type="button" class="btn btn-success font-weight-bold px-4" id="btnApplyMapping" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-arrow-right mr-1"></i> Terapkan & Lanjutkan ke Pratinjau
                        </button>
                    </div>
                </div>

                {{-- STEP 3: PREVIEW & VALIDATION (KONFIRMASI SEBELUM DATABASE BERUBAH) --}}
                <div id="importStepPreview" class="import-step-container d-none">
                    <div class="modal-body p-4">
                        {{-- Ringkasan Statistik Hasil Analisis --}}
                        <div class="row mb-3">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="import-stat-card border-success" style="background: #f0fdf4;">
                                    <div class="import-stat-icon bg-success text-white">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted font-weight-semibold">Siap Diimport</div>
                                        <div class="font-weight-bold text-success" style="font-size: 18px;">
                                            <span id="statCountReady">0</span> Siswa
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <div class="import-stat-card border-warning" style="background: #fffbeb;">
                                    <div class="import-stat-icon bg-warning text-white">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted font-weight-semibold">NIS Sudah Ada (Duplikat)</div>
                                        <div class="font-weight-bold text-warning" style="font-size: 18px;">
                                            <span id="statCountDuplicate">0</span> Siswa
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="import-stat-card border-danger" style="background: #fef2f2;">
                                    <div class="import-stat-icon bg-danger text-white">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted font-weight-semibold">Data Bermasalah (Error)</div>
                                        <div class="font-weight-bold text-danger" style="font-size: 18px;">
                                            <span id="statCountError">0</span> Siswa
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi Penanganan Data Duplikat --}}
                        <div class="p-3 bg-light border rounded mb-3" style="border-radius: 8px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <strong class="text-dark small d-block mb-1">
                                        <i class="fas fa-cog text-muted mr-1"></i> Opsi Penanganan untuk NIS yang Sudah Ada di Database:
                                    </strong>
                                    <div class="custom-control custom-radio custom-control-inline mr-3">
                                        <input type="radio" id="duplicateActionSkip" name="duplicateAction" class="custom-control-input" value="skip" checked>
                                        <label class="custom-control-label small font-weight-semibold" for="duplicateActionSkip" style="cursor: pointer;">
                                            Lewati data yang sudah ada (Rekomendasi &mdash; Data lama tetap aman)
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="duplicateActionUpdate" name="duplicateAction" class="custom-control-input" value="update">
                                        <label class="custom-control-label small font-weight-semibold" for="duplicateActionUpdate" style="cursor: pointer;">
                                            Perbarui data nama &amp; kelas pada NIS tersebut
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Filter Tabs & Search --}}
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <div class="d-flex align-items-center gap-1" id="previewFilterTabs">
                                <button type="button" class="filter-tab-btn active" data-filter="all">Semua (<span id="tabCountAll">0</span>)</button>
                                <button type="button" class="filter-tab-btn" data-filter="ready">Siap (<span id="tabCountReady">0</span>)</button>
                                <button type="button" class="filter-tab-btn" data-filter="duplicate">Duplikat (<span id="tabCountDuplicate">0</span>)</button>
                                <button type="button" class="filter-tab-btn" data-filter="error">Error (<span id="tabCountError">0</span>)</button>
                            </div>
                            <small class="text-muted font-italic">Periksa kembali data sebelum menekan konfirmasi import.</small>
                        </div>

                        {{-- Tabel Pratinjau Data --}}
                        <div class="preview-table-container">
                            <table class="table table-hover align-middle mb-0" id="previewTable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center">No</th>
                                        <th style="width: 140px;">NIS</th>
                                        <th>Nama Siswa</th>
                                        <th style="width: 140px;">Kelas</th>
                                        <th style="width: 220px;">Status &amp; Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody">
                                    {{-- Baris diisi dinamis via JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-between">
                        <button type="button" class="btn btn-batal-merah px-4" id="btnBackToUploadFromPreview">
                            <i class="fas fa-arrow-left mr-1"></i> Ganti Berkas
                        </button>
                        <button type="button" class="btn btn-success font-weight-bold px-4" id="btnConfirmExecuteImport" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-check-circle mr-1"></i> Konfirmasi &amp; Import (<span id="countImportFinal">0</span> Siswa)
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI IMPORT DATA SISWA --}}
    <div class="modal fade" id="modalKonfirmasiImportSiswa" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1065;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header modal-header-clean">
                    <h5 class="modal-title font-weight-bold text-white" style="font-size: 15px;">
                        <i class="fas fa-file-import mr-2 text-white"></i>
                        Konfirmasi Import Data Siswa
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <p class="mb-3 text-dark" style="font-size: 14px;">
                        Apakah Anda yakin ingin mengimport dan memproses data siswa ini ke dalam database?
                    </p>

                    <div class="p-3 bg-light border rounded mb-0" style="border-radius: 8px;">
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 14px;">
                            <span class="text-muted">Total Data Diproses:</span>
                            <span class="font-weight-bold text-success" id="konfirmasiJumlahSiswa">0 Siswa</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2" style="font-size: 14px;">
                            <span class="text-muted">Penanganan Duplikasi:</span>
                            <span class="font-weight-bold text-dark" id="konfirmasiOpsiDuplikasi">Lewati data lama</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2 px-4 justify-content-end" style="border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-batal-merah px-4 mr-2" id="btnBatalModalKonfirmasiImport" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-success font-weight-bold px-4" id="btnEksekusiImportModal" style="height: 38px; border-radius: 8px;">
                        <i class="fas fa-check-circle mr-1"></i> Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Tooltip init
    if (window.jQuery && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Auto-open modal Tambah Siswa if errors returned
    @if ($errors->any())
        if (window.jQuery) {
            $('#modalTambahSiswa').modal('show');
        }
    @endif

    // Client-side live search
    const searchInput = document.getElementById('siswaSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#siswaTable tbody tr');

            rows.forEach(function (row) {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    /* =========================================================================
       IMPORT DATA SISWA AJAX WORKFLOW
       ========================================================================= */
    let currentRawRows = [];
    let currentParsedRows = [];
    let currentSummary = { total: 0, ready: 0, duplicate: 0, error: 0 };
    let selectedFile = null;

    const modalImport = $('#modalImportSiswa');
    const fileInput = document.getElementById('importFileInput');
    const dropzone = document.getElementById('importDropzone');
    const btnSubmitParse = document.getElementById('btnSubmitParse');
    const selectedFileInfo = document.getElementById('selectedFileInfo');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');
    const uploadError = document.getElementById('importUploadError');
    const uploadErrorMessage = document.getElementById('importUploadErrorMessage');
    const uploadLoading = document.getElementById('importUploadLoading');

    // Drag & drop handlers
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                handleFileSelected(files[0]);
            }
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                handleFileSelected(this.files[0]);
            }
        });
    }

    function handleFileSelected(file) {
        selectedFile = file;
        selectedFileName.textContent = file.name;
        selectedFileSize.textContent = formatBytes(file.size);
        selectedFileInfo.classList.remove('d-none');
        uploadError.classList.add('d-none');
        btnSubmitParse.disabled = false;
    }

    const btnRemoveFile = document.getElementById('btnRemoveFile');
    if (btnRemoveFile) {
        btnRemoveFile.addEventListener('click', function () {
            selectedFile = null;
            if (fileInput) fileInput.value = '';
            selectedFileInfo.classList.add('d-none');
            btnSubmitParse.disabled = true;
        });
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function switchStep(stepName) {
        document.getElementById('importStepUpload').classList.add('d-none');
        document.getElementById('importStepMapping').classList.add('d-none');
        document.getElementById('importStepPreview').classList.add('d-none');

        if (stepName === 'upload') {
            document.getElementById('importStepUpload').classList.remove('d-none');
        } else if (stepName === 'mapping') {
            document.getElementById('importStepMapping').classList.remove('d-none');
        } else if (stepName === 'preview') {
            document.getElementById('importStepPreview').classList.remove('d-none');
        }
    }

    // Tombol: Baca & Analisis Berkas
    if (btnSubmitParse) {
        btnSubmitParse.addEventListener('click', function () {
            if (!selectedFile) return;

            const formData = new FormData();
            formData.append('file', selectedFile);
            formData.append('_token', '{{ csrf_token() }}');

            uploadLoading.classList.remove('d-none');
            uploadError.classList.add('d-none');
            btnSubmitParse.disabled = true;

            fetch('{{ route("siswa.import.parse") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                uploadLoading.classList.add('d-none');
                btnSubmitParse.disabled = false;

                if (!data.success) {
                    uploadErrorMessage.textContent = data.message || 'Gagal membaca berkas.';
                    uploadError.classList.remove('d-none');
                    return;
                }

                if (data.status === 'need_mapping') {
                    renderMappingStep(data.headers, data.mapping, data.raw_rows);
                    switchStep('mapping');
                } else if (data.status === 'preview') {
                    renderPreviewStep(data.summary, data.rows);
                    switchStep('preview');
                }
            })
            .catch(err => {
                uploadLoading.classList.add('d-none');
                btnSubmitParse.disabled = false;
                uploadErrorMessage.textContent = 'Terjadi kesalahan saat mengunggah berkas: ' + err.message;
                uploadError.classList.remove('d-none');
            });
        });
    }

    // Render Step 2: Mapping
    function renderMappingStep(headers, currentMapping, rawRows) {
        currentRawRows = rawRows;
        const colNama = document.getElementById('mappingColNama');
        const colNis = document.getElementById('mappingColNis');
        const colKelas = document.getElementById('mappingColKelas');

        [colNama, colNis, colKelas].forEach(select => {
            select.innerHTML = '<option value="">-- Pilih Kolom Berkas --</option>';
            headers.forEach((h, idx) => {
                const opt = document.createElement('option');
                opt.value = idx;
                opt.textContent = `${h} (Kolom ${idx + 1})`;
                select.appendChild(opt);
            });
        });

        if (currentMapping.nama !== null) colNama.value = currentMapping.nama;
        if (currentMapping.nis !== null) colNis.value = currentMapping.nis;
        if (currentMapping.kelas !== null) colKelas.value = currentMapping.kelas;
    }

    // Submit Mapping
    const btnApplyMapping = document.getElementById('btnApplyMapping');
    if (btnApplyMapping) {
        btnApplyMapping.addEventListener('click', function () {
            const colNama = document.getElementById('mappingColNama').value;
            const colNis = document.getElementById('mappingColNis').value;
            const colKelas = document.getElementById('mappingColKelas').value;

            if (colNama === '' || colNis === '' || colKelas === '') {
                alert('Silakan pilih semua pemetaan kolom (Nama, NIS, dan Kelas).');
                return;
            }

            btnApplyMapping.disabled = true;
            btnApplyMapping.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menerapkan...';

            fetch('{{ route("siswa.import.mapping") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    raw_rows: currentRawRows,
                    mapping: {
                        nama: parseInt(colNama),
                        nis: parseInt(colNis),
                        kelas: parseInt(colKelas),
                    }
                })
            })
            .then(res => res.json())
            .then(data => {
                btnApplyMapping.disabled = false;
                btnApplyMapping.innerHTML = '<i class="fas fa-arrow-right mr-1"></i> Terapkan & Lanjutkan ke Pratinjau';

                if (!data.success) {
                    alert(data.message || 'Gagal menerapkan pemetaan.');
                    return;
                }

                renderPreviewStep(data.summary, data.rows);
                switchStep('preview');
            })
            .catch(err => {
                btnApplyMapping.disabled = false;
                btnApplyMapping.innerHTML = '<i class="fas fa-arrow-right mr-1"></i> Terapkan & Lanjutkan ke Pratinjau';
                alert('Terjadi kesalahan: ' + err.message);
            });
        });
    }

    // Render Step 3: Preview
    function renderPreviewStep(summary, rows) {
        currentSummary = summary;
        currentParsedRows = rows;

        document.getElementById('statCountReady').textContent = summary.ready;
        document.getElementById('statCountDuplicate').textContent = summary.duplicate;
        document.getElementById('statCountError').textContent = summary.error;

        document.getElementById('tabCountAll').textContent = summary.total;
        document.getElementById('tabCountReady').textContent = summary.ready;
        document.getElementById('tabCountDuplicate').textContent = summary.duplicate;
        document.getElementById('tabCountError').textContent = summary.error;

        updateFinalImportCount();
        renderTableRows('all');
    }

    function updateFinalImportCount() {
        const dupAction = document.querySelector('input[name="duplicateAction"]:checked')?.value || 'skip';
        let count = currentSummary.ready;
        if (dupAction === 'update') {
            count += currentSummary.duplicate;
        }
        document.getElementById('countImportFinal').textContent = count;
    }

    // Radio duplicate action change
    document.querySelectorAll('input[name="duplicateAction"]').forEach(radio => {
        radio.addEventListener('change', updateFinalImportCount);
    });

    function renderTableRows(filter) {
        const tbody = document.getElementById('previewTableBody');
        tbody.innerHTML = '';

        const filtered = currentParsedRows.filter(row => {
            if (filter === 'all') return true;
            return row.status === filter;
        });

        if (filtered.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        Tidak ada data pada kategori ini.
                    </td>
                </tr>
            `;
            return;
        }

        filtered.forEach((row, idx) => {
            const tr = document.createElement('tr');

            let badgeHtml = '';
            if (row.status === 'ready') {
                badgeHtml = '<span class="status-badge-ready"><i class="fas fa-check-circle"></i> Siap Diimport</span>';
            } else if (row.status === 'duplicate') {
                badgeHtml = `<span class="status-badge-duplicate" title="Data di database: ${escapeHtml(row.existing_nama || '')}"><i class="fas fa-exclamation-triangle"></i> NIS Sudah Ada</span>`;
            } else {
                const errText = escapeHtml(row.errors.join(', '));
                badgeHtml = `<span class="status-badge-error" title="${errText}"><i class="fas fa-times-circle"></i> ${errText}</span>`;
            }

            tr.innerHTML = `
                <td class="text-center text-muted">${row.index}</td>
                <td><strong>${escapeHtml(row.nis || '-')}</strong></td>
                <td>${escapeHtml(row.nama || '-')}</td>
                <td><span class="badge badge-light border text-dark">${escapeHtml(row.kelas || '-')}</span></td>
                <td>${badgeHtml}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Filter tab clicks
    document.querySelectorAll('#previewFilterTabs .filter-tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#previewFilterTabs .filter-tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderTableRows(this.getAttribute('data-filter'));
        });
    });

    // Navigation back buttons
    const btnBackToUploadFromMapping = document.getElementById('btnBackToUploadFromMapping');
    if (btnBackToUploadFromMapping) {
        btnBackToUploadFromMapping.addEventListener('click', () => switchStep('upload'));
    }

    const btnBackToUploadFromPreview = document.getElementById('btnBackToUploadFromPreview');
    if (btnBackToUploadFromPreview) {
        btnBackToUploadFromPreview.addEventListener('click', () => switchStep('upload'));
    }

    // Eksekusi Konfirmasi Import via Modal Bersih Bebas Stuck
    const btnConfirmExecuteImport = document.getElementById('btnConfirmExecuteImport');
    const modalKonfirmasiImport = $('#modalKonfirmasiImportSiswa');
    const btnBatalModalKonfirmasiImport = document.getElementById('btnBatalModalKonfirmasiImport');
    const btnEksekusiImportModal = document.getElementById('btnEksekusiImportModal');

    if (btnBatalModalKonfirmasiImport) {
        btnBatalModalKonfirmasiImport.addEventListener('click', function () {
            modalKonfirmasiImport.modal('hide');
            setTimeout(function () {
                modalImport.modal('show');
            }, 300);
        });
    }

    modalKonfirmasiImport.on('hidden.bs.modal', function () {
        if (!$('.modal.show').length) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css({'padding-right': '', 'overflow': ''});
        }
    });

    if (btnConfirmExecuteImport) {
        btnConfirmExecuteImport.addEventListener('click', function () {
            const dupAction = document.querySelector('input[name="duplicateAction"]:checked')?.value || 'skip';
            const finalCount = parseInt(document.getElementById('countImportFinal').textContent) || 0;

            if (finalCount === 0) {
                alert('Tidak ada data siswa yang dapat diimport. Pastikan terdapat data yang valid atau aktifkan opsi perbarui data.');
                return;
            }

            const countElem = document.getElementById('konfirmasiJumlahSiswa');
            const dupElem = document.getElementById('konfirmasiOpsiDuplikasi');
            if (countElem) countElem.textContent = `${finalCount} Siswa`;
            if (dupElem) {
                dupElem.textContent = dupAction === 'update' 
                    ? 'Perbarui data nama & kelas jika NIS sama' 
                    : 'Lewati data lama (hanya tambah data baru)';
            }

            modalImport.modal('hide');
            setTimeout(function () {
                modalKonfirmasiImport.modal('show');
            }, 300);
        });
    }

    if (btnEksekusiImportModal) {
        btnEksekusiImportModal.addEventListener('click', function () {
            const dupAction = document.querySelector('input[name="duplicateAction"]:checked')?.value || 'skip';

            btnEksekusiImportModal.disabled = true;
            btnEksekusiImportModal.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
            if (btnConfirmExecuteImport) {
                btnConfirmExecuteImport.disabled = true;
                btnConfirmExecuteImport.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
            }

            fetch('{{ route("siswa.import.confirm") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    rows: currentParsedRows,
                    duplicate_action: dupAction,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    btnEksekusiImportModal.disabled = false;
                    btnEksekusiImportModal.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Ya, Lanjutkan';
                    if (btnConfirmExecuteImport) {
                        btnConfirmExecuteImport.disabled = false;
                        btnConfirmExecuteImport.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Konfirmasi & Import';
                    }
                    alert(data.message || 'Gagal mengimport data.');
                    return;
                }

                // Sukses -> tutup modal dan reload halaman agar data terbaru langsung tampil
                modalKonfirmasiImport.modal('hide');
                modalImport.modal('hide');
                window.location.reload();
            })
            .catch(err => {
                btnEksekusiImportModal.disabled = false;
                btnEksekusiImportModal.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Ya, Lanjutkan';
                if (btnConfirmExecuteImport) {
                    btnConfirmExecuteImport.disabled = false;
                    btnConfirmExecuteImport.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Konfirmasi & Import';
                }
                alert('Terjadi kesalahan saat menyimpan data: ' + err.message);
            });
        });
    }

    // Reset modal saat ditutup
    modalImport.on('hidden.bs.modal', function () {
        switchStep('upload');
        selectedFile = null;
        if (fileInput) fileInput.value = '';
        selectedFileInfo.classList.add('d-none');
        btnSubmitParse.disabled = true;
        uploadError.classList.add('d-none');
    });
});
</script>
@stop