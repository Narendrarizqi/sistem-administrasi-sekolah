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
            padding: 14px 18px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .pengeluaran-title-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pengeluaran-title {
            font-size: 17.5px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 2px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pengeluaran-title-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14.5px;
            flex-shrink: 0;
        }

        .pengeluaran-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        .pengeluaran-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pengeluaran-badge-total {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .pengeluaran-badge-count {
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
        .pengeluaran-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .pengeluaran-card-header {
            background: #ffffff;
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .pengeluaran-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pengeluaran-toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .pengeluaran-search-box {
            position: relative;
            width: 290px;
            max-width: 100%;
        }

        .pengeluaran-search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12.5px;
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
            transition: all 0.15s ease;
            outline: none;
        }

        .pengeluaran-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .btn-pengeluaran-add {
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

        .btn-pengeluaran-add:hover {
            background: #15803d;
            color: #ffffff !important;
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.2);
            transform: translateY(-1px);
        }

        /* 3. Table Container */
        .pengeluaran-table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }

        .pengeluaran-table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .pengeluaran-table-responsive::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 999px;
        }

        .pengeluaran-table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .pengeluaran-table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #pengeluaranTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12.5px;
        }

        #pengeluaranTable thead th {
            background: #f0fdf4 !important;
            color: #14532d !important;
            font-weight: 650 !important;
            font-size: 12px !important;
            letter-spacing: -0.01em;
            padding: 8px 10px;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap !important;
            vertical-align: middle;
        }

        #pengeluaranTable th.sortable {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #pengeluaranTable th.sortable:hover {
            background-color: #e6f9ed !important;
            color: #0f172a !important;
        }

        #pengeluaranTable .sort-icon {
            margin-left: 3px;
            font-size: 10px;
            opacity: 0.45;
        }

        #pengeluaranTable th.sort-active {
            color: #15803d !important;
        }

        #pengeluaranTable th.sort-active .sort-icon {
            opacity: 1;
            color: #15803d;
        }

        #pengeluaranTable tbody td {
            padding: 9px 10px;
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
            background-color: #f6fcf8;
        }

        .row-number {
            font-size: 11.5px !important;
            white-space: nowrap !important;
            text-align: center;
        }

        .font-num {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            white-space: nowrap !important;
        }

        .keterangan-wrap {
            max-width: 380px;
            word-break: break-word;
            font-weight: 500;
            color: #0f172a;
            line-height: 1.35;
        }

        .val-nominal-out {
            color: #dc2626 !important;
            font-weight: 700 !important;
            font-size: 13px;
        }

        /* 4. Badges Sumber Dana */
        .badge-sumber-ipp {
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            color: #1d4ed8 !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            padding: 2px 7px !important;
        }

        .badge-sumber-du {
            background: #f5f3ff !important;
            border: 1px solid #ddd6fe !important;
            color: #6d28d9 !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            padding: 2px 7px !important;
        }

        .badge-sumber-sarpras {
            background: #fffbeb !important;
            border: 1px solid #fde68a !important;
            color: #b45309 !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            padding: 2px 7px !important;
        }

        .badge-sumber-ki {
            background: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
            color: #15803d !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            padding: 2px 7px !important;
        }

        /* 5. Action Buttons */
        .pengeluaran-action-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            white-space: nowrap !important;
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
            font-size: 12px;
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

        /* 6. Modals Scoped */
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

    {{-- 1. HEADER SECTION --}}
    <div class="pengeluaran-page-header">
        <div class="pengeluaran-title-wrapper">
            <div>
                <h1 class="pengeluaran-title">
                    <span class="pengeluaran-title-icon"><i class="fas fa-arrow-up-from-bracket"></i></span>
                    Manajemen Pengeluaran
                </h1>
                <p class="pengeluaran-desc">
                    Catat dan pantau seluruh transaksi pengeluaran operasional, sarana prasarana, serta alokasi anggaran sekolah.
                </p>
            </div>
            <div class="pengeluaran-header-badges">
                <span class="pengeluaran-badge-total">
                    <i class="fas fa-money-bill-wave text-danger"></i>
                    Total: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </span>
                <span class="pengeluaran-badge-count">
                    <i class="fas fa-file-invoice text-muted"></i>
                    {{ $pengeluaran->count() }} Transaksi
                </span>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CARD & TABLE --}}
    <div class="pengeluaran-card">
        <div class="pengeluaran-card-header">
            <div class="pengeluaran-toolbar">
                <div class="pengeluaran-toolbar-left">
                    <div class="pengeluaran-search-box">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            id="pengeluaranSearchInput"
                            class="pengeluaran-search-input"
                            placeholder="Cari keterangan atau sumber dana..."
                        >
                    </div>
                </div>

                <button type="button" class="btn-pengeluaran-add" data-toggle="modal" data-target="#modalTambahPengeluaran">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Pengeluaran</span>
                </button>
            </div>
        </div>

        <div class="pengeluaran-table-responsive">
            <table class="table" id="pengeluaranTable">
                <thead>
                    <tr>
                        <th style="width: 32px;" class="text-center">No</th>
                        <th style="width: 105px;" class="sortable" data-sort="tanggal" title="Klik untuk mengurutkan berdasarkan Tanggal">
                            Tanggal
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th style="width: 120px;" class="sortable" data-sort="sumber" title="Klik untuk mengurutkan berdasarkan Sumber Dana">
                            Sumber Dana
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th style="min-width: 200px;" class="sortable" data-sort="keterangan" title="Klik untuk mengurutkan berdasarkan Keterangan">
                            Keterangan
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th class="text-right text-end sortable" data-sort="nominal" style="width: 130px;" title="Klik untuk mengurutkan berdasarkan Nominal">
                            Nominal (Rp)
                            <i class="fas fa-sort sort-icon"></i>
                        </th>
                        <th style="width: 80px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluaran as $item)
                        @php
                            $sumber = $item->sumber_dana;
                            $badgeClass = 'badge-sumber-ipp';
                            if ($sumber === 'DU') $badgeClass = 'badge-sumber-du';
                            elseif ($sumber === 'Sarpras') $badgeClass = 'badge-sumber-sarpras';
                            elseif ($sumber === 'KI') $badgeClass = 'badge-sumber-ki';
                        @endphp
                        <tr
                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                            data-sumber="{{ strtolower($item->sumber_dana ?? '') }}"
                            data-keterangan="{{ strtolower($item->keterangan ?? '') }}"
                            data-nominal="{{ (float)$item->nominal }}"
                        >
                            {{-- 1. No --}}
                            <td class="text-center text-muted row-number font-num font-weight-500">
                                {{ $loop->iteration }}
                            </td>

                            {{-- 2. Tanggal --}}
                            <td class="font-num text-secondary">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </td>

                            {{-- 3. Sumber Dana --}}
                            <td>
                                @if($item->sumber_dana)
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $item->sumber_dana === 'DU' ? 'Daftar Ulang (DU)' : ($item->sumber_dana === 'KI' ? 'Kegiatan (KI)' : $item->sumber_dana) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- 4. Keterangan --}}
                            <td>
                                <div class="keterangan-wrap" title="{{ $item->keterangan }}">
                                    {{ $item->keterangan }}
                                </div>
                            </td>

                            {{-- 5. Nominal (Rp) --}}
                            <td class="text-right text-end font-num val-nominal-out">
                                {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>

                            {{-- 6. Aksi --}}
                            <td class="text-center">
                                <div class="pengeluaran-action-group">
                                    {{-- Tombol Edit (Kuning) Modal Popup --}}
                                    <button type="button"
                                            class="btn-act-edit"
                                            title="Edit Data Pengeluaran"
                                            data-toggle="modal"
                                            data-target="#modalEditPengeluaran{{ $item->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    {{-- Tombol Hapus (Merah) --}}
                                    <button type="button"
                                            class="btn-act-hapus"
                                            title="Hapus Pengeluaran"
                                            data-toggle="modal"
                                            data-target="#modalHapusPengeluaran{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data transaksi pengeluaran yang dicatat.
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

    {{-- MODAL TAMBAH PENGELUARAN --}}
    <div class="modal fade" id="modalTambahPengeluaran" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-clean">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Tambah Data Pengeluaran Baru
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
                                      placeholder="Contoh: Pembelian alat tulis kantor, perbaikan printer, honor narasumber..."
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

    {{-- MODAL PER BARIS: EDIT & HAPUS --}}
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
                                {{-- Tanggal --}}
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

                                {{-- Sumber Dana --}}
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
                                    </select>
                                </div>
                            </div>

                            {{-- Nominal --}}
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

                            {{-- Keterangan --}}
                            <div class="form-group mb-0">
                                <label class="form-label font-weight-bold">
                                    Keterangan / Keperluan <span class="text-danger">*</span>
                                </label>
                                <textarea name="keterangan"
                                          rows="3"
                                          class="form-control"
                                          style="border-radius: 8px;"
                                          placeholder="Contoh: Pembelian alat tulis kantor, perbaikan printer, honor narasumber..."
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
                            <i class="fas fa-trash text-danger mr-2"></i>
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
                                Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }} |
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
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>
                        <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-trash mr-1"></i>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('pengeluaranTable');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const searchInput = document.getElementById('pengeluaranSearchInput');
    let sortColumn = '';
    let sortDirection = 'asc';

    function getRows() {
        return Array.from(tbody.querySelectorAll('tr[data-tanggal]'));
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
        document.querySelectorAll('#pengeluaranTable th.sortable').forEach(function (header) {
            header.classList.remove('sort-active');
            const icon = header.querySelector('.sort-icon');
            if (icon) icon.className = 'fas fa-sort sort-icon';
        });

        const activeHeader = document.querySelector('#pengeluaranTable th[data-sort="' + sortColumn + '"]');
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
            if (column === 'tanggal') {
                valueA = a.dataset.tanggal || '';
                valueB = b.dataset.tanggal || '';
                return sortDirection === 'asc' ? valueA.localeCompare(valueB) : valueB.localeCompare(valueA);
            }
            if (column === 'sumber') {
                valueA = a.dataset.sumber || '';
                valueB = b.dataset.sumber || '';
                return sortDirection === 'asc' ? valueA.localeCompare(valueB) : valueB.localeCompare(valueA);
            }
            if (column === 'keterangan') {
                valueA = a.dataset.keterangan || '';
                valueB = b.dataset.keterangan || '';
                return sortDirection === 'asc' ? valueA.localeCompare(valueB, 'id') : valueB.localeCompare(valueA, 'id');
            }
            if (column === 'nominal') {
                valueA = Number(a.dataset.nominal || 0);
                valueB = Number(b.dataset.nominal || 0);
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

    document.querySelectorAll('#pengeluaranTable th.sortable').forEach(function (header) {
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

    // Default sort: Tanggal Descending
    sortColumn = 'tanggal';
    sortDirection = 'desc';
    sortTable('tanggal');
});
</script>
@stop