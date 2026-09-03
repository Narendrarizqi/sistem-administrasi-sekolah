@extends('adminlte::page')

@section('title', 'Rekap Pembayaran')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* 1. Header Area */
        .rekap-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .rekap-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .rekap-header-info {
            flex: 1;
            min-width: 260px;
        }

        .rekap-page-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rekap-page-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            border-radius: 8px;
            font-size: 14.5px;
            flex-shrink: 0;
        }

        .rekap-page-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
        }

        .rekap-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .rekap-badge-ta {
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

        .rekap-badge-bulan {
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
        .rekap-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .rekap-card-header {
            background: #ffffff;
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .rekap-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .rekap-toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .rekap-search-box {
            position: relative;
            width: 290px;
            max-width: 100%;
        }

        .rekap-search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12.5px;
        }

        .rekap-search-input {
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

        .rekap-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .rekap-select-ta {
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

        .rekap-select-ta:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .btn-rekap-pdf {
            height: 38px;
            background: #16a34a;
            border: 1px solid #15803d;
            color: #ffffff !important;
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
            text-decoration: none !important;
        }

        .btn-rekap-pdf:hover {
            background: #15803d;
            color: #ffffff !important;
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.2);
            transform: translateY(-1px);
        }

        /* 3. Table Container & Scrollbar */
        .rekap-table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }

        .rekap-table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .rekap-table-responsive::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 999px;
        }

        .rekap-table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .rekap-table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #rekapTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
        }

        /* Table Header */
        #rekapTable thead th {
            background: #f0fdf4 !important;
            color: #14532d !important;
            font-weight: 650 !important;
            font-size: 11.5px !important;
            letter-spacing: -0.01em;
            padding: 7px 4px;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap !important;
            vertical-align: middle;
        }

        #rekapTable th.sortable {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #rekapTable th.sortable:hover {
            background-color: #e6f9ed !important;
            color: #0f172a !important;
        }

        #rekapTable .sort-icon {
            margin-left: 2px;
            font-size: 9.5px;
            opacity: 0.45;
        }

        #rekapTable th.sort-active,
        #rekapTable .sortable.active {
            color: #15803d !important;
        }

        #rekapTable th.sort-active .sort-icon,
        #rekapTable .sortable.active .sort-icon {
            opacity: 1;
            color: #15803d;
        }

        #rekapTable tbody td {
            padding: 6px 4px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.25;
            font-size: 12px;
            white-space: nowrap;
        }

        #rekapTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #rekapTable tbody tr:hover {
            background-color: #f6fcf8;
        }

        #rekapTable tbody tr:last-child td {
            border-bottom: none;
        }

        /* Column Specific Formatting */
        .row-number {
            font-size: 11px !important;
            white-space: nowrap !important;
            padding-left: 1px !important;
            padding-right: 1px !important;
            text-align: center;
            color: #64748b;
            font-weight: 500;
        }

        .nama-siswa-wrap {
            max-width: 140px;
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

        .badge-kelas-simple {
            display: inline-block;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 10.5px;
            font-weight: 600;
            padding: 1.5px 6px;
            border-radius: 4px;
            white-space: nowrap;
        }

        #rekapTable .jenis-select {
            display: block;
            width: 100%;
            min-width: 135px;
            height: 28px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 2px 20px 2px 8px !important;
            border-radius: 6px;
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            box-shadow: none !important;
            cursor: pointer;
            transition: all 0.15s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 6px center !important;
            background-size: 8px 6px !important;
        }

        #rekapTable .jenis-select:hover,
        #rekapTable .jenis-select:focus,
        #rekapTable .jenis-select:active,
        #rekapTable tbody tr:hover .jenis-select,
        #rekapTable tbody tr:hover .jenis-select:hover {
            background-color: #ffffff !important;
            border-color: #16a34a !important;
            color: #0f172a !important;
            box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.12) !important;
        }

        #rekapTable .jenis-select option {
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-weight: 500;
            padding: 3px 6px;
        }

        /* Action Buttons (Standar Seragam Halaman Pembayaran) */
        .btn-act-pdf,
        .btn-act-bukti {
            width: 26px;
            height: 26px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 11px;
            box-shadow: none;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-act-pdf {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626 !important;
        }

        .btn-act-pdf:hover,
        .btn-act-pdf:focus {
            background: #dc2626;
            border-color: #dc2626;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220, 38, 38, 0.25);
        }

        .btn-act-bukti {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #15803d !important;
        }

        .btn-act-bukti:hover,
        .btn-act-bukti:focus {
            background: #15803d;
            border-color: #15803d;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.25);
        }

        /* Financial Numbers */
        .font-num {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            white-space: nowrap !important;
            font-size: 11.5px;
        }

        .val-num-target {
            color: #334155;
            font-weight: 500;
        }

        .val-num-terbawa {
            color: #b45309;
            font-weight: 600;
        }

        .val-num-total {
            color: #0f172a;
            font-weight: 700;
        }

        .val-num-terbayar {
            color: #16a34a;
            font-weight: 700;
        }

        .val-num-sisa {
            color: #dc2626 !important;
            font-weight: 700;
        }

        .val-num-sisa-lunas {
            color: #16a34a !important;
            font-weight: 700;
        }

        .val-num-zero {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Badges */
        .badge-status-lunas,
        .table .badge-status-lunas {
            background-color: #dcfce7 !important;
            border: 1px solid #86efac !important;
            color: #15803d !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2px 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
        }

        .badge-status-belum,
        .table .badge-status-belum {
            background-color: #fef2f2 !important;
            border: 1px solid #fecaca !important;
            color: #dc2626 !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2px 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
        }

        .badge-status-none {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 10px;
            font-weight: 500;
            border-radius: 999px;
            padding: 2px 8px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .badge-notif-lunas,
        .badge-notif-sudah {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 10.5px;
            font-weight: 600;
            border-radius: 5px;
            padding: 2.5px 7px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .badge-notif-belum {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #b45309;
            font-size: 10.5px;
            font-weight: 600;
            border-radius: 5px;
            padding: 2.5px 7px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        /* Modal Solid Header */
        .modal-header-solid {
            background: #16a34a !important;
            color: #ffffff !important;
            padding: 14px 18px;
            border-top-left-radius: calc(0.5rem - 1px);
            border-top-right-radius: calc(0.5rem - 1px);
        }

        .modal-header-solid .modal-title,
        .modal-header-solid .modal-title * {
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
        }

        .modal-header-solid .close {
            color: #ffffff !important;
            opacity: 1;
            text-shadow: none;
        }

        @media (max-width: 768px) {
            .rekap-page-header {
                padding: 14px 16px;
            }
            .rekap-card-header {
                padding: 12px 14px;
            }
            .rekap-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .rekap-toolbar-left {
                flex-direction: column;
                align-items: stretch;
            }
            .rekap-search-box {
                width: 100%;
                max-width: 100%;
            }
            .btn-rekap-pdf {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@stop

@section('content')

    {{-- PAGE HEADER & BREADCRUMB INDICATOR --}}
    <div class="rekap-page-header">
        <div class="rekap-header-top">
            <div class="rekap-header-info">
                <h1 class="rekap-page-title">
                    <span class="rekap-page-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                    Rekapitulasi Tagihan & Pembayaran
                </h1>
                <p class="rekap-page-desc">
                    Pantau status kewajiban, pembayaran masuk, sisa tagihan, serta pengingat status pembayaran bulanan siswa.
                </p>
            </div>
            <div class="rekap-header-badges">
                <span class="rekap-badge-ta">
                    <i class="far fa-calendar-alt"></i>
                    Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
                </span>
                <span class="rekap-badge-bulan">
                    <i class="far fa-clock text-muted"></i>
                    Bulan Aktif: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="rekap-card">
        <div class="rekap-card-header">
            @if($students->count() > 0)
                {{-- Toolbar: Search & Year Filter & Cetak PDF --}}
                <div class="rekap-toolbar">
                    <div class="rekap-toolbar-left">
                        <div class="rekap-search-box">
                            <i class="fas fa-search"></i>
                            <input
                                type="text"
                                id="rekapSearchInput"
                                class="rekap-search-input"
                                placeholder="Cari NIS, nama, atau kelas..."
                                autocomplete="off"
                            >
                        </div>

                        @if($daftarTahunAjaran->isNotEmpty())
                            <form action="{{ route('rekap.index') }}" method="GET" class="d-flex align-items-center mb-0">
                                <select
                                    name="tahun_ajaran_id"
                                    class="rekap-select-ta"
                                    onchange="this.form.submit()"
                                    title="Pilih Tahun Ajaran"
                                >
                                    @foreach($daftarTahunAjaran as $ta)
                                        <option value="{{ $ta->id }}" {{ $selectedTa && $selectedTa->id == $ta->id ? 'selected' : '' }}>
                                            {{ $ta->nama }}{{ $ta->is_active ? ' (Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                    </div>

                    <div class="rekap-toolbar-right">
                        <a
                            href="{{ route('rekap.cetak', ['tahun_ajaran_id' => $tahunAjaranId]) }}"
                            target="_blank"
                            class="btn-rekap-pdf"
                            title="Cetak Laporan Rekap Seluruh Siswa (PDF)"
                        >
                            <i class="fas fa-file-pdf"></i>
                            <span>Cetak Rekap PDF</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if($students->count() > 0)
            {{-- Table Container with Native Smooth Horizontal Scroll --}}
            <div class="rekap-table-responsive">
                <table class="table" id="rekapTable">
                    <thead>
                        <tr>
                            <th width="35" class="text-center col-no">No</th>
                                <th class="sortable col-nis" data-sort="nis" onclick="sortTable('nis')" title="Klik untuk mengurutkan berdasarkan NIS">
                                    NIS <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="sortable col-nama" data-sort="nama" onclick="sortTable('nama')" title="Klik untuk mengurutkan berdasarkan Nama">
                                    Nama Siswa <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="sortable col-kelas" data-sort="kelas" onclick="sortTable('kelas')" title="Klik untuk mengurutkan berdasarkan Kelas">
                                    Kelas <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="col-jenis">Jenis Tagihan</th>
                                <th class="sortable col-uang" data-sort="target" onclick="sortTable('target')" title="Klik untuk mengurutkan berdasarkan Target">
                                    Target (Rp) <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="sortable col-uang" data-sort="terbawa" onclick="sortTable('terbawa')" title="Klik untuk mengurutkan berdasarkan Terbawa">
                                    Terbawa (Rp) <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="sortable col-uang" data-sort="total_tagihan" onclick="sortTable('total_tagihan')" title="Klik untuk mengurutkan berdasarkan Total Tagihan">
                                    Total Tagihan (Rp) <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="sortable col-uang" data-sort="terbayar" onclick="sortTable('terbayar')" title="Klik untuk mengurutkan berdasarkan Terbayar">
                                    Terbayar (Rp) <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="sortable col-uang" data-sort="sisa" onclick="sortTable('sisa')" title="Klik untuk mengurutkan berdasarkan Sisa">
                                    Sisa (Rp) <i class="fas fa-sort sort-icon"></i>
                                </th>
                                <th class="col-bulan text-center">
                                    <i class="fas fa-bell mr-1 text-warning"></i> Pengingat Bulan Ini
                                </th>
                                <th class="col-status">Status</th>
                                <th class="text-center col-aksi">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($students as $student)
                                @php
                                    $jenisKeys = array_keys($student['jenis']);
                                    $siswa = $student['siswa'];

                                    $orderedJenisKeys = array_merge(
                                         ['Total'],
                                         array_values(
                                             array_filter(
                                                 $jenisKeys,
                                                 fn ($key) => $key !== 'Total'
                                             )
                                         )
                                     );
                                @endphp

                                <tr
                                    data-nis="{{ strtolower($siswa->nis) }}"
                                    data-nama="{{ strtolower($siswa->nama) }}"
                                    data-kelas="{{ strtolower($siswa->kelas) }}"
                                >
                                    <td class="text-center nomor-cell col-no">
                                        {{ method_exists($students, 'firstItem') && $students->firstItem() ? ($students->firstItem() + $loop->index) : $loop->iteration }}
                                    </td>

                                    <td class="col-nis">
                                        {{ $siswa->nis }}
                                    </td>

                                    <td class="col-nama">
                                        {{ $siswa->nama }}
                                    </td>

                                    <td class="col-kelas">
                                        <span class="badge-kelas-simple">
                                            {{ $siswa->kelas }}
                                        </span>
                                    </td>

                                    <td class="col-jenis">
                                        <select
                                            class="jenis-select"
                                            data-summary='@json($student['jenis'], JSON_HEX_APOS | JSON_HEX_QUOT)'
                                        >
                                            @foreach($orderedJenisKeys as $jenis)
                                                <option value="{{ $jenis }}" @if($jenis === 'Total') selected @endif>
                                                    {{ $jenis === 'Total' ? 'Total Keseluruhan' : $jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="target-cell col-uang val-num-target">
                                    </td>

                                    <td class="terbawa-cell col-uang">
                                    </td>

                                    <td class="total-tagihan-cell col-uang val-num-total">
                                    </td>

                                    <td class="terbayar-cell col-uang val-num-terbayar">
                                    </td>

                                    <td
                                        class="sisa-cell col-uang"
                                        data-sisa="0"
                                    >
                                    </td>

                                    <td class="bulan-cell text-center col-bulan">
                                    </td>

                                    <td class="status-cell col-status">
                                    </td>

                                    <td class="text-center col-aksi">
                                        <div class="d-inline-flex align-items-center" style="gap: 4px;">
                                            <a
                                                href="{{ route('rekap.cetak-siswa', $siswa->id) }}?tahun_ajaran_id={{ $tahunAjaranId }}"
                                                target="_blank"
                                                class="btn-act-pdf"
                                                title="Cetak Laporan Tagihan (PDF)"
                                            >
                                                <i class="fas fa-file-pdf"></i>
                                            </a>

                                            <button
                                                type="button"
                                                class="btn-act-bukti"
                                                title="Lihat Riwayat & Bukti Pembayaran"
                                                data-toggle="modal"
                                                data-target="#modalRekapBukti{{ $siswa->id }}"
                                            >
                                                <i class="fas fa-receipt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links (50 per halaman) --}}
                @if(method_exists($students, 'hasPages') && ($students->hasPages() || $students->total() > 0))
                    <div class="table-pagination-container px-3 pb-3">
                        <div class="table-pagination-info">
                            Menampilkan <strong>{{ $students->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $students->lastItem() ?? 0 }}</strong> dari <strong>{{ $students->total() }}</strong> siswa
                        </div>
                        <div class="table-pagination-links">
                            {{ $students->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif

                {{-- MODALS RIWAYAT & BUKTI PEMBAYARAN PER SISWA --}}
                @foreach($students as $student)
                    @php
                        $siswa = $student['siswa'];
                        $pembayarans = $student['pembayarans'] ?? collect();
                    @endphp
                    <div class="modal fade" id="modalRekapBukti{{ $siswa->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                                <div class="modal-header modal-header-solid">
                                    <h5 class="modal-title font-weight-bold">
                                        <i class="fas fa-receipt mr-2 text-white"></i>
                                        Riwayat & Bukti Pembayaran: {{ $siswa->nama }} ({{ $siswa->nis }})
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body p-3">
                                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <table class="table table-hover mb-0" style="font-size: 12.5px;">
                                            <thead style="background: #f0fdf4; color: #14532d;">
                                                <tr>
                                                    <th width="35" class="text-center">No</th>
                                                    <th>Jenis Tagihan</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-center" style="background:#e8f5e9; color:#1b5e20;">Bulan Dibayar</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th>Metode</th>
                                                    <th class="text-center">Bukti Transfer</th>
                                                    <th>Keterangan</th>
                                                    <th width="50" class="text-center">Kuitansi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $rowNum = 1; @endphp
                                                @forelse($pembayarans as $pb)
                                                    @foreach($pb->detailPembayaran as $detail)
                                                        <tr>
                                                            <td class="text-center text-muted">{{ $rowNum++ }}</td>
                                                            <td><span class="badge-kelas-simple">{{ $pb->jenisPembayaran->nama ?? 'Tagihan' }}</span></td>
                                                            <td class="text-muted">{{ \Carbon\Carbon::parse($detail->tanggal)->format('d/m/Y') }}</td>
                                                            <td class="text-center">
                                                                <span class="badge-bulan-bayar">
                                                                    <i class="fas fa-calendar-check mr-1 text-success"></i>
                                                                    {{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('F Y') }}
                                                                </span>
                                                            </td>
                                                            <td class="text-right font-weight-bold text-success">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                                                            <td><span class="badge bg-light text-dark border">{{ $detail->metode }}</span></td>
                                                            <td class="text-center">
                                                                @if($detail->bukti)
                                                                    <a href="{{ asset($detail->bukti) }}" target="_blank" class="btn btn-xs btn-outline-success font-weight-bold" title="Buka Bukti Transfer">
                                                                        <i class="fas fa-image mr-1"></i> Lihat Bukti
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted small">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-muted">{{ $detail->keterangan ?? '-' }}</td>
                                                            <td class="text-center">
                                                                <a href="{{ route('bukti.cetak', $detail->id) }}" target="_blank" class="btn btn-xs btn-outline-success" title="Cetak Kuitansi">
                                                                    <i class="fas fa-print"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center py-4 text-muted">Belum ada riwayat transaksi pembayaran.</td>
                                                    </tr>
                                                @endforelse
                                                @if($rowNum === 1)
                                                    <tr>
                                                        <td colspan="9" class="text-center py-4 text-muted">Belum ada riwayat transaksi pembayaran.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                                    <button type="button" class="btn btn-tutup-merah px-4" data-dismiss="modal">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else

                <div class="text-center py-5 border rounded" style="background: #f8fafc; border-color: #e2e8f0 !important; border-radius: 10px;">
                    <i class="fas fa-receipt fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="font-weight-bold text-dark mb-1">Belum Ada Data Pembayaran</h5>
                    <p class="text-muted small mb-0">
                        Data pembayaran siswa pada tahun ajaran ini akan muncul di sini.
                    </p>
                </div>

            @endif

    </div>

@stop

@section('js')
<script>
    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value || 0);
    }

    function updateRecapRow(row, selectedJenis, summaryData) {
        const data = summaryData[selectedJenis] || null;

        const targetCell = row.querySelector('.target-cell');
        const terbawaCell = row.querySelector('.terbawa-cell');
        const totalTagihanCell = row.querySelector('.total-tagihan-cell');
        const terbayarCell = row.querySelector('.terbayar-cell');
        const sisaCell = row.querySelector('.sisa-cell');
        const bulanCell = row.querySelector('.bulan-cell');
        const statusCell = row.querySelector('.status-cell');

        if (!data) {
            targetCell.textContent = '0';
            targetCell.className = 'target-cell col-uang val-num-zero';

            terbawaCell.textContent = '0';
            terbawaCell.className = 'terbawa-cell col-uang val-num-zero';

            totalTagihanCell.textContent = '0';
            totalTagihanCell.className = 'total-tagihan-cell col-uang val-num-zero';

            terbayarCell.textContent = '0';
            terbayarCell.className = 'terbayar-cell col-uang val-num-zero';

            sisaCell.textContent = '0';
            sisaCell.className = 'sisa-cell col-uang val-num-zero';
            sisaCell.dataset.sisa = 0;

            if (bulanCell) {
                bulanCell.innerHTML = '<span class="text-muted small">-</span>';
            }

            statusCell.innerHTML = '<span class="badge-status-none">Tidak Ada</span>';
            return;
        }

        // Target
        targetCell.textContent = formatRupiah(data.target);
        targetCell.className = 'target-cell col-uang val-num-target';

        // Terbawa
        terbawaCell.textContent = formatRupiah(data.terbawa);
        if (Number(data.terbawa) > 0) {
            terbawaCell.className = 'terbawa-cell col-uang val-num-terbawa';
        } else {
            terbawaCell.className = 'terbawa-cell col-uang val-num-zero';
        }

        // Total Tagihan
        totalTagihanCell.textContent = formatRupiah(data.total_tagihan);
        totalTagihanCell.className = 'total-tagihan-cell col-uang val-num-total';

        // Terbayar
        terbayarCell.textContent = formatRupiah(data.terbayar);
        if (Number(data.terbayar) > 0) {
            terbayarCell.className = 'terbayar-cell col-uang val-num-terbayar';
        } else {
            terbayarCell.className = 'terbayar-cell col-uang val-num-zero';
        }

        // Sisa
        sisaCell.textContent = formatRupiah(data.sisa);
        sisaCell.dataset.sisa = Number(data.sisa) || 0;
        if (Number(data.sisa) > 0) {
            sisaCell.className = 'sisa-cell col-uang val-num-sisa';
        } else {
            sisaCell.className = 'sisa-cell col-uang val-num-sisa-lunas';
        }

        // Bulan Notif
        if (bulanCell) {
            if (data.notif_status === 'lunas') {
                bulanCell.innerHTML = `<span class="badge-notif-lunas"><i class="fas fa-check-double mr-1"></i> ${data.notif_text}</span>`;
            } else if (data.notif_status === 'sudah') {
                bulanCell.innerHTML = `<span class="badge-notif-sudah"><i class="fas fa-check-circle mr-1"></i> ${data.notif_text}</span>` +
                    (data.notif_sub ? `<div class="text-success font-weight-bold mt-1" style="font-size:9.5px; line-height: 1.1;">${data.notif_sub}</div>` : '');
            } else if (data.notif_status === 'belum') {
                bulanCell.innerHTML = `<span class="badge-notif-belum"><i class="fas fa-exclamation-circle mr-1"></i> ${data.notif_text}</span>` +
                    (data.notif_sub ? `<div class="text-muted font-weight-bold mt-1" style="font-size:9.5px; line-height: 1.1;">${data.notif_sub}</div>` : '');
            } else {
                bulanCell.innerHTML = '<span class="text-muted small">-</span>';
            }
        }

        // Status Badge
        if (data.status === 'Lunas') {
            statusCell.innerHTML = `<span class="badge-status-lunas">${data.status}</span>`;
        } else if (data.status === 'Belum Lunas') {
            statusCell.innerHTML = `<span class="badge-status-belum">${data.status}</span>`;
        } else {
            statusCell.innerHTML = `<span class="badge-status-none">${data.status}</span>`;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Init Jenis Select listeners
        document.querySelectorAll('.jenis-select').forEach((select) => {
            const row = select.closest('tr');
            const summary = JSON.parse(select.dataset.summary);

            select.addEventListener('change', () => {
                updateRecapRow(row, select.value, summary);
                select.blur();
            });

            updateRecapRow(row, select.value, summary);
        });

        // Live Search
        const searchInput = document.getElementById('rekapSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('#rekapTable tbody tr').forEach(function (row) {
                    row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
                });
                updateNumbering();
            });
        }

        // Default sort by Nama
        sortTable('nama');
    });

    let currentSort = {
        column: 'nama',
        direction: 'asc'
    };

    function sortTable(column) {
        const table = document.getElementById('rekapTable');
        if (!table) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        rows.sort(function (a, b) {
            let valueA;
            let valueB;

            if (column === 'nis') {
                valueA = a.dataset.nis || '';
                valueB = b.dataset.nis || '';
                const numberA = Number(valueA);
                const numberB = Number(valueB);
                if (!isNaN(numberA) && !isNaN(numberB)) {
                    valueA = numberA;
                    valueB = numberB;
                }
            } else if (column === 'nama') {
                valueA = a.dataset.nama || '';
                valueB = b.dataset.nama || '';
            } else if (column === 'kelas') {
                valueA = a.dataset.kelas || '';
                valueB = b.dataset.kelas || '';
            } else if (column === 'sisa') {
                valueA = Number(a.querySelector('.sisa-cell')?.dataset.sisa || 0);
                valueB = Number(b.querySelector('.sisa-cell')?.dataset.sisa || 0);
            } else if (column === 'target') {
                valueA = Number(a.querySelector('.target-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
                valueB = Number(b.querySelector('.target-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
            } else if (column === 'terbawa') {
                valueA = Number(a.querySelector('.terbawa-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
                valueB = Number(b.querySelector('.terbawa-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
            } else if (column === 'total_tagihan') {
                valueA = Number(a.querySelector('.total-tagihan-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
                valueB = Number(b.querySelector('.total-tagihan-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
            } else if (column === 'terbayar') {
                valueA = Number(a.querySelector('.terbayar-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
                valueB = Number(b.querySelector('.terbayar-cell')?.textContent.replace(/[^0-9]/g, '') || 0);
            }

            let comparison = 0;
            if (typeof valueA === 'number') {
                comparison = valueA - valueB;
            } else {
                comparison = String(valueA).localeCompare(String(valueB), 'id', { numeric: true, sensitivity: 'base' });
            }

            return currentSort.direction === 'asc' ? comparison : -comparison;
        });

        rows.forEach(function (row) {
            tbody.appendChild(row);
        });

        updateSortIcons(column);
        updateNumbering();
    }

    function updateSortIcons(activeColumn) {
        document.querySelectorAll('.sortable').forEach(function (header) {
            const icon = header.querySelector('.sort-icon');
            header.classList.remove('active');
            if (icon) {
                icon.className = 'fas fa-sort sort-icon';
            }
        });

        const activeHeader = document.querySelector(`.sortable[data-sort="${activeColumn}"]`);
        if (!activeHeader) return;

        const icon = activeHeader.querySelector('.sort-icon');
        activeHeader.classList.add('active');
        if (icon) {
            icon.className = currentSort.direction === 'asc' ? 'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
        }
    }

    function updateNumbering() {
        const rows = document.querySelectorAll('#rekapTable tbody tr');
        let nomor = 1;
        rows.forEach(function (row) {
            const nomorCell = row.querySelector('.nomor-cell');
            if (nomorCell && row.style.display !== 'none') {
                nomorCell.textContent = nomor++;
            }
        });
    }
</script>
@stop