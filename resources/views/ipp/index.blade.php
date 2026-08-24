@extends('adminlte::page')

@section('title', 'Pembayaran IPP — Sistem Pembayaran')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: HALAMAN PEMBAYARAN IPP
           Aplikasi Administrasi Sekolah Profesional, Rapi, & Matang
           =============================================================== */

        /* 1. Header Box (Clean White, Compact Height, Vertically Centered) */
        .ipp-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .ipp-title-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ipp-title {
            font-size: 17.5px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 2px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Icon IPP: Soft green background with darker green icon */
        .ipp-title-icon {
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

        .ipp-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        .ipp-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Badges: Green-tinted for TA, Neutral Gray for Total Siswa */
        .ipp-badge-ta {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .ipp-badge-total {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* 2. Card Container & Toolbar */
        .ipp-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .ipp-card-header {
            background: #ffffff;
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .ipp-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ipp-toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .ipp-search-box {
            position: relative;
            width: 290px;
            max-width: 100%;
        }

        .ipp-search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12.5px;
        }

        .ipp-search-input {
            width: 100%;
            height: 38px;
            padding: 6px 12px 6px 34px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #1e293b;
            font-size: 13px;
            transition: all 0.15s ease;
            outline: none;
        }

        .ipp-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .ipp-select-ta {
            height: 38px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #1e293b;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 12px;
            min-width: 185px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .ipp-select-ta:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .btn-ipp-add {
            height: 38px;
            background: #16a34a;
            border: 1px solid #15803d;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            padding: 0 15px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.15s ease;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-ipp-add:hover {
            background: #15803d;
            color: #ffffff;
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.2);
            transform: translateY(-1px);
        }

        /* 3. Table Container & Subtle Horizontal Scrollbar */
        .ipp-table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }

        .ipp-table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .ipp-table-responsive::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 999px;
        }

        .ipp-table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .ipp-table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #ippTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
        }

        /* Table Header: Very Light Soft Green (#f0fdf4) with Dark Green Text (#14532d) */
        #ippTable thead th {
            background: #f0fdf4 !important;
            color: #14532d !important;
            font-weight: 650 !important;
            font-size: 11.5px !important;
            letter-spacing: -0.01em;
            padding: 7px 3px;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap !important;
            vertical-align: middle;
        }

        #ippTable th.sortable {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #ippTable th.sortable:hover {
            background-color: #e6f9ed !important;
            color: #0f172a !important;
        }

        #ippTable .sort-icon {
            margin-left: 2px;
            font-size: 9.5px;
            opacity: 0.45;
        }

        #ippTable th.sort-active {
            color: #15803d !important;
        }

        #ippTable th.sort-active .sort-icon {
            opacity: 1;
            color: #15803d;
        }

        #ippTable tbody td {
            padding: 6px 3px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.25;
            font-size: 12px;
            white-space: nowrap;
        }

        #ippTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #ippTable tbody tr:hover {
            background-color: #f6fcf8;
        }

        /* Kolom No Kompak 1 Baris */
        .row-number {
            font-size: 11px !important;
            white-space: nowrap !important;
            padding-left: 1px !important;
            padding-right: 1px !important;
            text-align: center;
        }

        /* Nama Siswa Maks 2 Baris Kompak (Satu-satunya kolom yang boleh 2 baris) */
        .nama-siswa-wrap {
            max-width: 125px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.25;
            font-size: 11.5px;
            font-weight: 650;
            color: #0f172a;
            word-break: break-word;
            white-space: normal !important;
        }

        .kelas-siswa-sub {
            font-size: 9.5px;
            color: #64748b;
            line-height: 1.1;
            margin-top: 1.5px;
            white-space: nowrap !important;
        }

        /* 4. Financial Hierarchy & Numbers (Tetap 1 Baris) */
        .font-num {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            white-space: nowrap !important;
            font-size: 11.5px;
        }

        .val-target {
            color: #334155;
            font-weight: 500;
        }

        .val-terbawa-zero {
            color: #94a3b8;
            font-weight: 400;
        }

        .val-terbawa-active {
            color: #b45309;
            font-weight: 600;
            line-height: 1.1;
        }

        .sub-terbawa-tag {
            display: inline-block;
            font-size: 8.5px;
            color: #b45309;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 2px;
            padding: 0 2.5px;
            margin-top: 1px;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap !important;
        }

        .val-total-tagihan {
            color: #0f172a;
            font-weight: 700;
        }

        .val-terbayar {
            color: #16a34a;
            font-weight: 700;
        }

        .val-sisa-active {
            color: #dc2626;
            font-weight: 700;
        }

        .val-sisa-zero {
            color: #94a3b8;
            font-weight: 400;
        }

        /* 5. Status Badges (Kompak Tetap 1 Baris) */
        .badge-status-lunas {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #15803d;
            font-size: 9.5px;
            font-weight: 600;
            border-radius: 999px;
            padding: 1.5px 4.5px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap !important;
        }

        .badge-status-belum {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-size: 9.5px;
            font-weight: 600;
            border-radius: 999px;
            padding: 1.5px 4.5px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap !important;
        }

        /* 6. Action Buttons */
        .ipp-action-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 2.5px;
            white-space: nowrap !important;
        }

        .btn-act-bayar,
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

        /* 1. Tombol Bayar (Hijau) */
        .btn-act-bayar {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d !important;
        }

        .btn-act-bayar:hover:not(:disabled),
        .btn-act-bayar:focus:not(:disabled) {
            background: #15803d;
            border-color: #15803d;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(21, 128, 61, 0.25);
        }

        .btn-act-bayar:disabled {
            opacity: 0.35;
            cursor: not-allowed;
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8 !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* 2. Tombol Edit (Kuning) */
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

        /* 3. Tombol Hapus (Merah) */
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

        /* 7. Modals Scoped */
        .modal-header-payment {
            background: #16a34a !important; /* Solid hijau tanpa gradasi */
            color: #ffffff !important;
            border-top-left-radius: calc(0.5rem - 1px);
            border-top-right-radius: calc(0.5rem - 1px);
            padding: 16px 20px;
            border-bottom: none !important;
        }

        .modal-header-payment .modal-title,
        .modal-header-payment .modal-title *,
        .modal-header-payment h5,
        .modal-header-payment i,
        .modal-header-payment span,
        .modal-header-payment button,
        .modal-header-payment .close {
            color: #ffffff !important;
            opacity: 1 !important;
            text-shadow: none !important;
        }

        /* Tombol Tutup / Batal / Kembali Solid Merah dengan Animasi Interaktif */
        .btn-tutup-merah,
        .btn-batal-merah,
        .btn-kembali-merah {
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

        .btn-tutup-merah:hover,
        .btn-tutup-merah:focus,
        .btn-batal-merah:hover,
        .btn-batal-merah:focus,
        .btn-kembali-merah:hover,
        .btn-kembali-merah:focus {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.35) !important;
        }

        .btn-tutup-merah:active,
        .btn-batal-merah:active,
        .btn-kembali-merah:active {
            background-color: #991b1b !important;
            border-color: #991b1b !important;
            color: #ffffff !important;
            transform: translateY(1px) !important;
            box-shadow: 0 1px 3px rgba(220, 38, 38, 0.25) !important;
        }

        /* Tombol Cetak Bukti Modal */
        #btnCetakBukti,
        .btn-cetak-bukti-modal {
            background-color: #16a34a !important;
            border: 1px solid #16a34a !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 8px 22px !important;
            height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.2) !important;
            transition: transform 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease !important;
            cursor: pointer;
            text-decoration: none !important;
        }

        #btnCetakBukti *,
        .btn-cetak-bukti-modal * {
            color: #ffffff !important;
        }

        #btnCetakBukti:hover,
        #btnCetakBukti:focus,
        .btn-cetak-bukti-modal:hover,
        .btn-cetak-bukti-modal:focus {
            background-color: #15803d !important;
            border-color: #15803d !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.35) !important;
        }

        #btnCetakBukti:active,
        .btn-cetak-bukti-modal:active {
            background-color: #166534 !important;
            border-color: #166534 !important;
            color: #ffffff !important;
            transform: translateY(1px) !important;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.25) !important;
        }

        .payment-mode-box {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
            padding: 10px 14px;
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .payment-mode-box label {
            margin-bottom: 0;
            cursor: pointer;
            font-weight: 500;
            font-size: 13px;
        }

        .fee-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
        }

        .custom-file-upload {
            position: relative;
            width: 100%;
        }

        .file-input-hidden {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-label {
            margin-bottom: 0;
            cursor: pointer;
            border: 1.5px dashed #cbd5e1 !important;
            border-radius: 8px;
            background: #ffffff;
            transition: all 0.2s ease;
            height: 40px;
        }

        .file-upload-label:hover {
            border-color: #16a34a !important;
            background: #f0fdf4;
        }
    </style>
@stop

@section('content')

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px;">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px;">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- 1. PAGE HEADER (Clean White, Vertically Centered, Green as Accent) --}}
    <div class="ipp-page-header">
        <div class="ipp-title-wrapper">
            <div>
                <h1 class="ipp-title">
                    <span class="ipp-title-icon"><i class="fas fa-graduation-cap"></i></span>
                    Iuran Pembayaran Pendidikan (IPP)
                </h1>
                <p class="ipp-desc">
                    Kelola data tagihan bulanan IPP siswa, riwayat pembayaran, serta pelunasan tahun ajaran aktif.
                </p>
            </div>
            <div class="ipp-header-badges">
                <span class="ipp-badge-ta">
                    <i class="far fa-calendar-alt text-success"></i>
                    Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
                </span>
                <span class="ipp-badge-total">
                    <i class="fas fa-users text-muted"></i>
                    Total: {{ $data->count() }} Siswa
                </span>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CARD & TABLE --}}
    <div class="ipp-card">
        <div class="ipp-card-header">
            {{-- Toolbar: Search, Filter TA, Button Tambah --}}
            <div class="ipp-toolbar">
                <div class="ipp-toolbar-left">
                    {{-- Search Input (290px lebar presisi) --}}
                    <div class="ipp-search-box">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            id="ippSearchInput"
                            class="ipp-search-input"
                            placeholder="Cari NIS atau nama siswa..."
                        >
                    </div>

                    {{-- Filter Tahun Ajaran --}}
                    @if(isset($daftarTahunAjaran) && $daftarTahunAjaran->isNotEmpty())
                        <form action="{{ route('ipp.index') }}" method="GET" class="d-inline-flex m-0">
                            <select
                                name="tahun_ajaran_id"
                                class="ipp-select-ta"
                                onchange="this.form.submit()"
                                title="Pilih Tahun Ajaran"
                            >
                                @foreach($daftarTahunAjaran as $ta)
                                    <option value="{{ $ta->id }}" {{ $selectedTa && $selectedTa->id == $ta->id ? 'selected' : '' }}>
                                        T.A. {{ $ta->nama }}{{ $ta->is_active ? ' (Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

                {{-- Button Tambah Tagihan --}}
                <button type="button" class="btn-ipp-add" data-toggle="modal" data-target="#modalTambahIpp">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Tagihan</span>
                </button>
            </div>
        </div>

        {{-- Table Container with Subtle Horizontal Scroll --}}
        <div class="ipp-table-responsive">
            <table class="table" id="ippTable">
                <thead>
                    <tr>
                        <th style="width: 26px; padding-left: 1px; padding-right: 1px;" class="text-center">No</th>
                        <th style="width: 70px;" class="sortable" data-sort="nis" title="Klik untuk mengurutkan berdasarkan NIS">
                            NIS
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th style="min-width: 100px; max-width: 125px;" class="sortable" data-sort="nama" title="Klik untuk mengurutkan berdasarkan Nama">
                            Nama Siswa
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th class="text-right text-end" style="width: 74px;" title="Kewajiban tagihan tahun berjalan">
                            Target (Rp)
                        </th>
                        <th class="text-right text-end" style="width: 74px;" title="Tagihan belum lunas dari tahun sebelumnya">
                            Terbawa (Rp)
                        </th>
                        <th class="text-right text-end" style="width: 76px;" title="Total Kewajiban = Target + Terbawa">
                            Total Tagihan (Rp)
                        </th>
                        <th class="text-right text-end" style="width: 74px;" title="Total pembayaran yang sudah diterima">
                            Terbayar (Rp)
                        </th>
                        <th class="text-right text-end sortable" data-sort="sisa" style="width: 74px;" title="Klik untuk mengurutkan berdasarkan Sisa Tagihan">
                            Sisa (Rp)
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th style="width: 66px;" class="text-center">Status</th>
                        <th style="width: 82px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        @php
                            $terbayar = (float) $item->detailPembayaran->sum('nominal');
                            $terbawaAwal = (float) ($item->belum_lunas ?? 0);
                            $sisaTerbawa = max($terbawaAwal - $terbayar, 0);
                            $totalTagihan = (float) $item->target + $terbawaAwal;
                            $sisa = max($totalTagihan - $terbayar, 0);
                            $isLunas = ($sisa <= 0 && $totalTagihan > 0);
                        @endphp
                        <tr
                            data-nis="{{ $item->siswa->nis ?? '' }}"
                            data-nama="{{ $item->siswa->nama ?? '' }}"
                            data-sisa="{{ $sisa }}"
                        >
                            {{-- 1. No --}}
                            <td class="text-center text-muted row-number font-num font-weight-500">
                                {{ $loop->iteration }}
                            </td>

                            {{-- 2. NIS --}}
                            <td class="font-num text-secondary">
                                {{ $item->siswa->nis ?? '-' }}
                            </td>

                            {{-- 3. Nama Siswa (Maks 2 Baris Kompak) --}}
                            <td>
                                <div class="nama-siswa-wrap" title="{{ $item->siswa->nama ?? '-' }}">
                                    {{ $item->siswa->nama ?? '-' }}
                                </div>
                                @if(isset($item->siswa->kelas))
                                    <div class="kelas-siswa-sub">
                                        Kelas: {{ $item->siswa->kelas }}
                                    </div>
                                @endif
                            </td>

                            {{-- 4. Target (Netral Dark) --}}
                            <td class="text-right text-end font-num val-target">
                                Rp {{ number_format($item->target, 0, ',', '.') }}
                            </td>

                            {{-- 5. Terbawa (Tahun Lalu - berkurang saat dibayar) --}}
                            <td class="text-right text-end font-num">
                                @if($sisaTerbawa > 0)
                                    <div class="val-terbawa-active">
                                        Rp {{ number_format($sisaTerbawa, 0, ',', '.') }}
                                    </div>
                                    <span class="sub-terbawa-tag" title="Sisa tagihan belum lunas dari tahun sebelumnya">
                                        Thn lalu
                                    </span>
                                @else
                                    <span class="val-terbawa-zero">Rp 0</span>
                                @endif
                            </td>

                            {{-- 6. Total Tagihan (Prominent Bold Dark) --}}
                            <td class="text-right text-end font-num val-total-tagihan">
                                Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                            </td>

                            {{-- 7. Terbayar (Hijau) --}}
                            <td class="text-right text-end font-num val-terbayar">
                                Rp {{ number_format($terbayar, 0, ',', '.') }}
                            </td>

                            {{-- 8. Sisa (Merah bila ada sisa) --}}
                            <td class="text-right text-end font-num">
                                @if($sisa > 0)
                                    <span class="val-sisa-active">
                                        Rp {{ number_format($sisa, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="val-sisa-zero">Rp 0</span>
                                @endif
                            </td>

                            {{-- 9. Status --}}
                            <td class="text-center">
                                @if($isLunas)
                                    <span class="badge-status-lunas">
                                        <i class="fas fa-check-circle mr-1"></i> Lunas
                                    </span>
                                @else
                                    <span class="badge-status-belum">
                                        <i class="fas fa-clock mr-1"></i> Belum Lunas
                                    </span>
                                @endif
                            </td>

                            {{-- 10. Aksi (Interactive Soft Tonal: Hijau, Kuning, Merah) --}}
                            <td class="text-center">
                                <div class="ipp-action-group justify-content-center">
                                    {{-- Tombol 1: Bayar (Hijau) --}}
                                    <button type="button"
                                            class="btn-act-bayar"
                                            title="Bayar Tagihan IPP"
                                            data-toggle="tooltip"
                                            data-target="#modalBayar{{ $item->id }}"
                                            onclick="$('#modalBayar{{ $item->id }}').modal('show')"
                                            {{ $isLunas ? 'disabled' : '' }}>
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>

                                    {{-- Tombol 2: Edit (Kuning) --}}
                                    <a href="{{ route('ipp.edit', $item->id) }}"
                                       class="btn-act-edit"
                                       title="Edit Data Tagihan"
                                       data-toggle="tooltip">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    {{-- Tombol 3: Hapus (Merah) --}}
                                    <button type="button"
                                            class="btn-act-hapus"
                                            title="Hapus Tagihan"
                                            data-toggle="tooltip"
                                            onclick="$('#modalHapus{{ $item->id }}').modal('show')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data tagihan IPP pada tahun ajaran ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- =========================================================
         MODALS SECTION
         ========================================================= --}}

    {{-- MODAL TAMBAH TAGIHAN IPP --}}
    @php
        $allSiswaIpp = \App\Models\Siswa::orderBy('nama')->get();
        $existingSiswaIds = $data->pluck('siswa_id')->toArray();
    @endphp
    <div class="modal fade" id="modalTambahIpp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-payment">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-wallet mr-2"></i>
                        Tambah Tagihan IPP (Iuran Pembayaran Pendidikan)
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('ipp.store') }}" method="POST" id="formModalTambahIpp">
                    @csrf
                    <div class="modal-body p-4">

                        {{-- Alert Jika Siswa Duplikat --}}
                        <div id="modalSiswaDuplicateAlert" class="alert alert-danger d-none mb-3 py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <strong>Peringatan:</strong> Siswa ini sudah memiliki data tagihan IPP pada tahun ajaran ini. Tidak dapat menambahkan tagihan ganda!
                        </div>
                        @if($errors->has('siswa_id'))
                            <div class="alert alert-danger mb-3 py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Error:</strong> {{ $errors->first('siswa_id') }}
                            </div>
                        @endif

                        {{-- Section 1: Pilih Siswa --}}
                        <div class="form-group row mb-3">
                            <label for="modal_siswa_id" class="col-sm-3 col-form-label font-weight-bold">
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select name="siswa_id"
                                        id="modal_siswa_id"
                                        class="form-control @error('siswa_id') is-invalid @enderror"
                                        style="border-radius: 8px;"
                                        required>
                                    <option value="">-- Pilih Siswa yang Ditagihkan --</option>
                                    @foreach($allSiswaIpp as $itemSiswa)
                                        @php $isAlreadyTagged = in_array($itemSiswa->id, $existingSiswaIds); @endphp
                                        <option value="{{ $itemSiswa->id }}"
                                                data-nis="{{ $itemSiswa->nis }}"
                                                data-nama="{{ $itemSiswa->nama }}"
                                                data-kelas="{{ $itemSiswa->kelas }}"
                                                data-exists="{{ $isAlreadyTagged ? '1' : '0' }}"
                                                {{ $isAlreadyTagged ? 'disabled class=text-muted' : '' }}
                                                {{ old('siswa_id') == $itemSiswa->id ? 'selected' : '' }}>
                                            {{ $itemSiswa->nis }} - {{ $itemSiswa->nama }} ({{ $itemSiswa->kelas }}) {{ $isAlreadyTagged ? '— [Sudah Ada Tagihan]' : '' }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="modalSelectedSiswaBox" class="mt-2 p-2 bg-light border rounded small d-none">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-muted">Nama:</span> <strong id="modalPreviewNama">-</strong>
                                            <span class="mx-2">|</span>
                                            <span class="text-muted">NIS:</span> <span id="modalPreviewNis" class="font-weight-bold">-</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-light text-dark border" id="modalPreviewKelas">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Input Nominal Tagihan Bulanan --}}
                        <div class="form-group row mb-3">
                            <label for="modal_nominal_per_bulan" class="col-sm-3 col-form-label font-weight-bold">
                                Nominal per Bulan <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light">Rp</span>
                                    </div>
                                    <input type="number"
                                           id="modal_nominal_per_bulan"
                                           class="form-control font-weight-bold text-success font-num"
                                           placeholder="Masukkan nominal per bulan (contoh: 100000)..."
                                           min="0"
                                           step="1000"
                                           style="border-radius: 0 8px 8px 0;"
                                           required>
                                </div>
                                <small class="form-text text-muted">
                                    Masukkan tarif tagihan 1 bulan, sistem akan otomatis menghitung total berdasarkan durasi.
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="modal_jumlah_bulan" class="col-sm-3 col-form-label font-weight-bold">
                                Durasi Tagihan
                            </label>
                            <div class="col-sm-9">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                    <select id="modal_jumlah_bulan" class="form-control" style="width: auto; min-width: 220px; border-radius: 8px;">
                                        <option value="12" selected>12 Bulan (1 Tahun / 2 Semester)</option>
                                        <option value="6">6 Bulan (1 Semester)</option>
                                        <option value="1">1 Bulan</option>
                                        <option value="2">2 Bulan</option>
                                        <option value="3">3 Bulan (1 Triwulan)</option>
                                        <option value="4">4 Bulan</option>
                                        <option value="5">5 Bulan</option>
                                    </select>
                                    <span class="badge badge-status-lunas" style="font-size: 11px;">
                                        Standar IPP: 1 Tahun (12 Bulan)
                                    </span>
                                </div>

                                {{-- Visual Breakdown 12 Bulan --}}
                                <div class="p-3 bg-light border rounded mb-2" style="border-radius: 10px;">
                                    <div class="small font-weight-bold text-muted mb-2">
                                        <i class="fas fa-list-ol mr-1"></i> Rincian Tiap Bulan:
                                    </div>
                                    <div id="modalBreakdownContainer" class="d-flex flex-wrap gap-2" style="gap: 6px;">
                                        <!-- Injected by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Target Input to be sent to backend --}}
                        <input type="hidden" name="target" id="modalFinalTarget" value="0">

                        {{-- Summary Fee Box --}}
                        <div class="fee-info-box my-3">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Nominal per Bulan</div>
                                    <div class="font-weight-bold text-dark font-num" id="modalSummaryNominalBulan">
                                        Rp 0
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Kalkulasi Durasi</div>
                                    <div class="font-weight-bold text-dark font-num" id="modalSummaryKalkulasi">
                                        Rp 0 × 12 Bulan
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="text-muted small">Total Tagihan Disimpan</div>
                                    <div class="font-weight-bold text-success font-num" style="font-size: 18px;" id="modalSummaryTotalLabel">
                                        Rp 0
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" id="btnSubmitTambahIpp" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Tagihan IPP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODALS PER BARIS: MODAL BAYAR & MODAL HAPUS --}}
    @foreach($data as $item)
        @php
            $terbayarItem = (float) $item->detailPembayaran->sum('nominal');
            $terbawaItem = (float) ($item->belum_lunas ?? 0);
            $totalTagihanItem = (float) $item->target + $terbawaItem;
            $sisaItem = max($totalTagihanItem - $terbayarItem, 0);
            $sisaTerbawaItem = max($terbawaItem - $terbayarItem, 0);
        @endphp

        {{-- MODAL BAYAR --}}
        <div class="modal fade" id="modalBayar{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-payment">
                        <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                            <i class="fas fa-wallet mr-2"></i>
                            Pembayaran IPP: {{ $item->siswa->nama ?? '-' }} ({{ $item->siswa->nis ?? '-' }})
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('ipp.bayar', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">

                            {{-- Rincian Tagihan Box --}}
                            <div class="fee-info-box mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">Target Tahun Ini</div>
                                        <div class="font-weight-bold font-num">Rp {{ number_format($item->target, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">Terbawa Tahun Lalu</div>
                                        <div class="font-weight-bold font-num {{ $sisaTerbawaItem > 0 ? 'text-warning' : '' }}">
                                            Rp {{ number_format($sisaTerbawaItem, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">Total Terbayar</div>
                                        <div class="font-weight-bold text-success font-num">Rp {{ number_format($terbayarItem, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">Sisa Tagihan</div>
                                        <div class="font-weight-bold text-danger font-num" style="font-size: 17px;">
                                            Rp {{ number_format($sisaItem, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($sisaTerbawaItem > 0)
                                <div class="alert alert-warning py-2 px-3 mb-3 small" style="border-radius: 8px;">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Siswa memiliki <strong>sisa tagihan terbawa tahun lalu</strong> sebesar <strong>Rp {{ number_format($sisaTerbawaItem, 0, ',', '.') }}</strong>. Pembayaran akan otomatis melunasi tagihan terbawa terlebih dahulu.
                                </div>
                            @endif

                            {{-- Form Inputs --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">Tanggal <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-light font-num" value="{{ date('d/m/Y') }}" readonly style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">Nominal Pembayaran <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="nominal"
                                               class="form-control font-weight-bold text-success font-num"
                                               max="{{ $sisaItem }}"
                                               min="1"
                                               placeholder="Masukkan nominal pembayaran..."
                                               style="border-radius: 0 8px 8px 0;"
                                               required>
                                    </div>
                                    <small class="form-text text-muted">
                                        Maksimal pembayaran: Rp {{ number_format($sisaItem, 0, ',', '.') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="payment-mode-box">
                                        <label class="d-inline-flex align-items-center mr-3">
                                            <input type="radio" name="metode" value="Cash" checked class="mr-2"> Cash / Tunai
                                        </label>
                                        <label class="d-inline-flex align-items-center mr-3">
                                            <input type="radio" name="metode" value="Transfer" class="mr-2"> Bank Transfer
                                        </label>
                                        <label class="d-inline-flex align-items-center">
                                            <input type="radio" name="metode" value="QRIS" class="mr-2"> QRIS / E-Wallet
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">Bukti Transfer</label>
                                <div class="col-sm-9">
                                    <div class="custom-file-upload">
                                        <input type="file"
                                               name="bukti"
                                               id="buktiIpp{{ $item->id }}"
                                               class="file-input-hidden"
                                               accept="image/*,.pdf"
                                               onchange="if(this.files && this.files[0]) { document.getElementById('labelBuktiIpp{{ $item->id }}').innerHTML = '<i class=\'fas fa-file text-success mr-2\'></i><span class=\'font-weight-bold text-dark\'>' + this.files[0].name + '</span>'; }">
                                        <label for="buktiIpp{{ $item->id }}" class="file-upload-label d-flex align-items-center justify-content-between px-3">
                                            <span id="labelBuktiIpp{{ $item->id }}" class="text-muted text-truncate" style="max-width: 78%; font-size: 13px;">
                                                <i class="fas fa-cloud-upload-alt text-success mr-2"></i> Pilih foto / file struk transfer...
                                            </span>
                                            <span class="btn btn-xs btn-outline-success font-weight-bold" style="border-radius: 6px;">
                                                <i class="fas fa-folder-open mr-1"></i> Browse
                                            </span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Lampirkan foto/struk transfer jika melalui Bank Transfer / QRIS (opsional, maks 3MB)</small>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea name="keterangan"
                                              class="form-control"
                                              rows="2"
                                              placeholder="Catatan pembayaran (opsional)..."
                                              style="border-radius: 8px;"></textarea>
                                </div>
                            </div>

                            {{-- DROPDOWN RIWAYAT PEMBAYARAN SCROLLABLE --}}
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <button class="btn btn-sm btn-outline-success font-weight-semibold" type="button" data-toggle="collapse" data-target="#riwayatCollapse{{ $item->id }}" aria-expanded="false" style="border-radius: 8px;">
                                        <i class="fas fa-history mr-1"></i> Lihat Riwayat Pembayaran ({{ $item->detailPembayaran->count() }}) <i class="fas fa-chevron-down ml-1"></i>
                                    </button>
                                    <span class="small text-muted font-num">{{ $item->detailPembayaran->count() }} Transaksi</span>
                                </div>

                                <div class="collapse" id="riwayatCollapse{{ $item->id }}">
                                    <div class="card card-body p-2 bg-light border shadow-none mb-0" style="max-height: 200px; overflow-y: auto; border-radius: 8px;">
                                        <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 12px;">
                                            <thead class="table-success">
                                                <tr>
                                                    <th width="30" class="text-center">No</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-center" style="background:#e8f5e9; color:#1b5e20;">Bulan Dibayar</th>
                                                    <th>Nominal</th>
                                                    <th>Metode</th>
                                                    <th>Bukti</th>
                                                    <th>Keterangan</th>
                                                    <th width="45" class="text-center">Cetak</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($item->detailPembayaran as $detail)
                                                    <tr>
                                                        <td class="text-center font-num">{{ $loop->iteration }}</td>
                                                        <td class="font-num">{{ \Carbon\Carbon::parse($detail->tanggal)->format('d/m/Y') }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-status-lunas" style="font-size: 10.5px;">
                                                                <i class="fas fa-calendar-check mr-1 text-success"></i>
                                                                {{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('F Y') }}
                                                            </span>
                                                        </td>
                                                        <td class="font-weight-bold text-success font-num">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                                                        <td><span class="badge badge-light border">{{ $detail->metode }}</span></td>
                                                        <td class="text-center">
                                                            @if($detail->bukti)
                                                                <a href="{{ asset($detail->bukti) }}" target="_blank" class="badge badge-info" title="Lihat Bukti Transfer">
                                                                    <i class="fas fa-image mr-1"></i> Bukti
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $detail->keterangan ?? '-' }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ route('bukti.cetak', $detail->id) }}" target="_blank" class="btn btn-xs btn-outline-primary" title="Cetak Kuitansi" style="border-radius: 4px;">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-2 text-muted">Belum ada riwayat pembayaran.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer bg-light px-4 py-3 justify-content-end">
                            <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;" {{ $sisaItem <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-money-bill-wave mr-1"></i> Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header py-3 px-4 bg-light border-bottom">
                        <h5 class="modal-title font-weight-bold text-danger" style="font-size: 16px;">
                            <i class="fas fa-trash text-danger mr-2"></i>
                            Konfirmasi Hapus Tagihan
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-2 text-dark" style="font-size: 14px;">
                            Apakah Anda yakin ingin menghapus tagihan IPP untuk siswa:
                        </p>
                        <div class="p-3 bg-light border rounded mb-3" style="border-radius: 10px;">
                            <div class="font-weight-bold text-dark" style="font-size: 15px;">{{ $item->siswa->nama ?? '-' }}</div>
                            <div class="text-muted small">NIS: {{ $item->siswa->nis ?? '-' }} | Kelas: {{ $item->siswa->kelas ?? '-' }}</div>
                        </div>
                        <div class="alert alert-danger mb-0 small" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Data tagihan beserta seluruh riwayat pembayarannya yang terhapus tidak dapat dikembalikan.
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>
                        <form action="{{ route('ipp.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-3 font-weight-bold" style="border-radius: 8px;">
                                <i class="fas fa-trash mr-1"></i> Ya, Hapus Tagihan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL CETAK BUKTI SETELAH PEMBAYARAN BERHASIL --}}
    @if(session('last_detail_id'))
        <div class="modal fade" id="modalCetakBukti" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-payment py-3">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle text-white mr-2"></i> Pembayaran Berhasil</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                    </div>
                    <div class="modal-body text-center py-4 px-4">
                        <i class="fas fa-receipt text-success fa-3x mb-3"></i>
                        <h5 class="font-weight-bold text-dark">Pembayaran IPP berhasil dicatat!</h5>
                        <p class="text-muted mb-0">Apakah Anda ingin mencetak bukti kuitansi pembayaran sekarang?</p>
                    </div>
                    <div class="modal-footer justify-content-center bg-light py-3">
                        <button type="button"
                                class="btn btn-tutup-merah px-4 mr-2"
                                data-dismiss="modal">
                            Tutup
                        </button>
                        <a href="{{ route('bukti.cetak', session('last_detail_id')) }}"
                           target="_blank"
                           class="btn btn-success font-weight-bold px-4"
                           id="btnCetakBukti">
                            <i class="fas fa-print mr-1"></i> Cetak Bukti Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    if (window.jQuery && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    const table = document.getElementById('ippTable');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const searchInput = document.getElementById('ippSearchInput');
    let sortColumn = '';
    let sortDirection = 'asc';

    function getRows() {
        return Array.from(tbody.querySelectorAll('tr[data-nis]'));
    }

    function updateNumber() {
        const rows = getRows();
        let number = 1;
        rows.forEach(function (row) {
            const numberCell = row.querySelector('.row-number');
            if (numberCell && row.style.display !== 'none') {
                numberCell.textContent = number;
                number++;
            }
        });
    }

    function updateSortIcon() {
        document.querySelectorAll('#ippTable th.sortable').forEach(function (header) {
            header.classList.remove('sort-active');
            const icon = header.querySelector('.sort-icon');
            if (icon) icon.className = 'fas fa-sort sort-icon';
        });

        const activeHeader = document.querySelector('#ippTable th[data-sort="' + sortColumn + '"]');
        if (!activeHeader) return;
        activeHeader.classList.add('sort-active');
        const icon = activeHeader.querySelector('.sort-icon');
        if (icon) {
            icon.className = (sortDirection === 'asc') ? 'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
        }
    }

    function sortTable(column) {
        if (sortColumn === column) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortColumn = column;
            sortDirection = 'asc';
        }

        const rows = getRows();
        rows.sort(function (a, b) {
            let valueA, valueB;
            if (column === 'nis') {
                valueA = a.dataset.nis || '';
                valueB = b.dataset.nis || '';
                return sortDirection === 'asc'
                    ? valueA.localeCompare(valueB, undefined, { numeric: true, sensitivity: 'base' })
                    : valueB.localeCompare(valueA, undefined, { numeric: true, sensitivity: 'base' });
            }
            if (column === 'nama') {
                valueA = (a.dataset.nama || '').toLowerCase();
                valueB = (b.dataset.nama || '').toLowerCase();
                return sortDirection === 'asc' ? valueA.localeCompare(valueB, 'id') : valueB.localeCompare(valueA, 'id');
            }
            if (column === 'sisa') {
                valueA = Number(a.dataset.sisa || 0);
                valueB = Number(b.dataset.sisa || 0);
                return sortDirection === 'asc' ? valueA - valueB : valueB - valueA;
            }
            return 0;
        });

        rows.forEach(function (row) {
            tbody.appendChild(row);
        });

        updateNumber();
        updateSortIcon();
    }

    document.querySelectorAll('#ippTable th.sortable').forEach(function (header) {
        header.addEventListener('click', function () {
            sortTable(this.dataset.sort);
        });
    });

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const query = this.value.toLowerCase().trim();
            getRows().forEach(function (row) {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            updateNumber();
        });
    }

    // Default sort Nama A-Z
    sortColumn = '';
    sortDirection = 'asc';
    sortTable('nama');

    @if(session('last_detail_id'))
        $('#modalCetakBukti').modal('show');
        $('#btnCetakBukti').on('click', function () {
            setTimeout(function () {
                $('#modalCetakBukti').modal('hide');
            }, 500);
        });
    @endif

    // Handler Modal Tambah Tagihan IPP
    const modalSiswaSelect = document.getElementById('modal_siswa_id');
    const modalSelectedSiswaBox = document.getElementById('modalSelectedSiswaBox');
    const modalPreviewNama = document.getElementById('modalPreviewNama');
    const modalPreviewNis = document.getElementById('modalPreviewNis');
    const modalPreviewKelas = document.getElementById('modalPreviewKelas');

    const modalInputNominalPerBulan = document.getElementById('modal_nominal_per_bulan');
    const modalSelectJumlahBulan = document.getElementById('modal_jumlah_bulan');
    const modalBreakdownContainer = document.getElementById('modalBreakdownContainer');
    const modalFinalTarget = document.getElementById('modalFinalTarget');

    const modalSummaryNominalBulan = document.getElementById('modalSummaryNominalBulan');
    const modalSummaryKalkulasi = document.getElementById('modalSummaryKalkulasi');
    const modalSummaryTotalLabel = document.getElementById('modalSummaryTotalLabel');

    const bulanList = ['Bulan 1 (Juli)', 'Bulan 2 (Agustus)', 'Bulan 3 (September)', 'Bulan 4 (Oktober)', 'Bulan 5 (November)', 'Bulan 6 (Desember)', 'Bulan 7 (Januari)', 'Bulan 8 (Februari)', 'Bulan 9 (Maret)', 'Bulan 10 (April)', 'Bulan 11 (Mei)', 'Bulan 12 (Juni)'];

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value || 0);
    }

    @if(session('open_modal_tambah') === 'ipp' || $errors->has('siswa_id'))
        $('#modalTambahIpp').modal('show');
    @endif

    const modalDuplicateAlert = document.getElementById('modalSiswaDuplicateAlert');
    const modalSubmitBtn = document.getElementById('btnSubmitTambahIpp');

    if (modalSiswaSelect) {
        modalSiswaSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                modalPreviewNama.textContent = opt.dataset.nama || '-';
                modalPreviewNis.textContent = opt.dataset.nis || '-';
                modalPreviewKelas.textContent = opt.dataset.kelas || '-';
                modalSelectedSiswaBox.classList.remove('d-none');

                if (opt.dataset.exists === '1') {
                    if (modalDuplicateAlert) modalDuplicateAlert.classList.remove('d-none');
                    if (modalSubmitBtn) modalSubmitBtn.disabled = true;
                } else {
                    if (modalDuplicateAlert) modalDuplicateAlert.classList.add('d-none');
                    if (modalSubmitBtn) modalSubmitBtn.disabled = false;
                }
            } else {
                modalSelectedSiswaBox.classList.add('d-none');
                if (modalDuplicateAlert) modalDuplicateAlert.classList.add('d-none');
                if (modalSubmitBtn) modalSubmitBtn.disabled = false;
            }
        });
    }

    function calculateModalTotal() {
        if (!modalFinalTarget) return;
        const nominalBulan = parseFloat(modalInputNominalPerBulan ? modalInputNominalPerBulan.value : 0) || 0;
        const jumlahBulan = parseInt(modalSelectJumlahBulan ? modalSelectJumlahBulan.value : 12) || 12;
        const total = nominalBulan * jumlahBulan;

        modalFinalTarget.value = total;

        if (modalSummaryNominalBulan) {
            modalSummaryNominalBulan.textContent = nominalBulan > 0 ? `Rp ${formatRupiah(nominalBulan)}` : 'Rp 0';
        }
        if (modalSummaryKalkulasi) {
            modalSummaryKalkulasi.textContent = nominalBulan > 0 ? `Rp ${formatRupiah(nominalBulan)} × ${jumlahBulan} Bulan` : `Rp 0 × ${jumlahBulan} Bulan`;
        }
        if (modalSummaryTotalLabel) {
            modalSummaryTotalLabel.textContent = total > 0 ? `Rp ${formatRupiah(total)}` : 'Rp 0';
        }

        if (modalBreakdownContainer) {
            modalBreakdownContainer.innerHTML = '';
            for (let i = 0; i < jumlahBulan; i++) {
                const chip = document.createElement('div');
                chip.className = 'badge badge-light border p-2 mr-1 mb-1';
                chip.style.fontSize = '11.5px';
                chip.innerHTML = `
                    <span class="text-secondary mr-1">${bulanList[i] || `Bulan ${i + 1}`}:</span>
                    <strong class="text-success font-num">${nominalBulan > 0 ? 'Rp ' + formatRupiah(nominalBulan) : 'Rp 0'}</strong>
                `;
                modalBreakdownContainer.appendChild(chip);
            }
        }
    }

    if (modalInputNominalPerBulan) modalInputNominalPerBulan.addEventListener('input', calculateModalTotal);
    if (modalSelectJumlahBulan) modalSelectJumlahBulan.addEventListener('change', calculateModalTotal);

    calculateModalTotal();
});
</script>
@stop