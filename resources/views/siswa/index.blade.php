@extends('adminlte::page')

@section('title', 'Data Siswa')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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

        /* Table Row Action Buttons */
        .siswa-action-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .btn-table-edit {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #d97706 !important;
            border-radius: 6px;
            font-size: 11.5px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-table-edit:hover,
        .btn-table-edit:focus {
            background: #d97706;
            border-color: #d97706;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(217, 119, 6, 0.25);
        }

        .btn-table-hapus {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626 !important;
            border-radius: 6px;
            font-size: 11.5px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-table-hapus:hover,
        .btn-table-hapus:focus {
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
        $tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first()
            ?? \App\Models\TahunAjaran::orderByDesc('nama')->first();
        $tahunAjaranNama = $tahunAktif?->nama ?? date('Y') . '/' . (date('Y') + 1);
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
                    Total Siswa: {{ $siswa->count() }} Orang
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
                <div class="siswa-search-box">
                    <i class="fas fa-search"></i>
                    <input
                        type="text"
                        id="siswaSearchInput"
                        class="siswa-search-input"
                        placeholder="Cari NIS, nama, atau kelas..."
                        autocomplete="off"
                    >
                </div>

                <div class="siswa-toolbar-actions">
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

            {{-- Table Container --}}
            @if($siswa->count() > 0)
                <div class="siswa-table-wrapper">
                    <table class="table" id="siswaTable">
                        <thead>
                            <tr>
                                <th class="col-no-cell">No</th>
                                <th class="col-nis-cell">NIS</th>
                                <th class="col-nama-cell">Nama Siswa</th>
                                <th class="col-kelas-cell">Kelas</th>
                                <th class="col-aksi-cell">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $item)
                                <tr>
                                    <td class="col-no-cell">
                                        {{ $loop->iteration }}
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
                                            <a
                                                href="{{ route('siswa.edit', $item->id) }}"
                                                class="btn-table-edit"
                                                title="Edit Data Siswa"
                                                data-toggle="tooltip"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <button
                                                type="button"
                                                class="btn-table-hapus"
                                                title="Hapus Data Siswa"
                                                data-toggle="modal"
                                                data-target="#modalHapusSiswa{{ $item->id }}"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 border rounded" style="background: #f8fafc; border-color: #e2e8f0 !important; border-radius: 10px;">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Belum Ada Data Siswa</h5>
                    <p class="text-muted small mb-3">
                        Silakan tambahkan data siswa aktif terlebih dahulu.
                    </p>
                    <a href="{{ route('siswa.create') }}" class="btn-toolbar-tambah">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Siswa Baru</span>
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- MODAL HAPUS SISWA (Cleanly Separated Outside Table) --}}
    @foreach($siswa as $item)
        <div class="modal fade" id="modalHapusSiswa{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-header modal-header-danger">
                        <h5 class="modal-title">
                            <i class="fas fa-trash mr-2"></i>
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
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>

                        <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger font-weight-bold px-3" style="height: 38px; border-radius: 8px;">
                                <i class="fas fa-trash mr-1"></i> Ya, Hapus
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
});
</script>
@stop