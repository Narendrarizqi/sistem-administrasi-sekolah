@extends('adminlte::page')

@section('title', 'Pembayaran Daftar Ulang')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: HALAMAN PEMBAYARAN DAFTAR ULANG (DU)
           =============================================================== */

        /* 1. Header Box */
        .du-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .du-title-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .du-title {
            font-size: 17.5px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 2px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .du-title-icon {
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

        .du-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        .du-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .du-badge-ta {
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

        .du-badge-total {
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
        .du-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .du-card-header {
            background: #ffffff;
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .du-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .du-toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .du-search-box {
            position: relative;
            width: 290px;
            max-width: 100%;
        }

        .du-search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12.5px;
        }

        .du-search-input {
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

        .du-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .btn-du-add {
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

        .btn-du-add:hover {
            background: #15803d;
            color: #ffffff;
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.2);
            transform: translateY(-1px);
        }

        /* Terapkan Massal (Base Accent: Sky Blue) */
        .btn-toolbar-massal {
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

        .btn-toolbar-massal i {
            color: #0284c7;
            transition: color 0.18s ease;
        }

        .btn-toolbar-massal:hover,
        .btn-toolbar-massal:focus {
            background: #f0f9ff;
            border-color: #bae6fd;
            color: #0369a1 !important;
            box-shadow: 0 1px 3px rgba(2, 132, 199, 0.08);
            transform: translateY(-1px);
        }

        .btn-toolbar-massal:hover i,
        .btn-toolbar-massal:focus i {
            color: #0284c7;
        }

        .btn-toolbar-massal:active {
            background: #e0f2fe;
            border-color: #7dd3fc;
            color: #075985 !important;
            transform: translateY(1px);
        }

        .btn-simpan-hijau {
            background-color: #16a34a !important;
            border: 1px solid #16a34a !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 8px 20px !important;
            height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.2) !important;
            transition: transform 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease !important;
            cursor: pointer;
            text-decoration: none !important;
            white-space: nowrap !important;
        }

        .btn-simpan-hijau:hover,
        .btn-simpan-hijau:focus {
            background-color: #15803d !important;
            border-color: #15803d !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.28) !important;
        }

        .btn-simpan-hijau:active {
            transform: translateY(1px) !important;
        }

        /* 3. Table Container */
        .du-table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }

        .du-table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .du-table-responsive::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 999px;
        }

        .du-table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .du-table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #duTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
        }

        #duTable thead th {
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

        #duTable th.sortable {
            padding: 0 !important;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #duTable th.sortable .sort-header-link {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            padding: 7px 4px;
            color: inherit !important;
            font-size: inherit;
            font-weight: inherit;
            letter-spacing: inherit;
            text-decoration: none !important;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #duTable th.sortable:hover .sort-header-link {
            background-color: #e6f9ed !important;
            color: #0f172a !important;
        }

        #duTable .sort-icon {
            margin-left: 2px;
            font-size: 9.5px;
            opacity: 0.45;
        }

        #duTable th.sort-active .sort-header-link {
            color: #15803d !important;
            background-color: #e6f9ed !important;
        }

        #duTable th.sort-active .sort-icon {
            opacity: 1;
            color: #15803d;
        }

        #duTable tbody td {
            padding: 6px 3px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.25;
            font-size: 12px;
            white-space: nowrap;
        }

        #duTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #duTable tbody tr:hover {
            background-color: #f6fcf8;
        }

        .row-number {
            font-size: 11px !important;
            white-space: nowrap !important;
            padding-left: 1px !important;
            padding-right: 1px !important;
            text-align: center;
        }

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
            color: #16a34a !important;
            font-weight: 700 !important;
        }

        .val-sisa-active {
            color: #dc2626;
            font-weight: 700;
        }

        .val-sisa-zero {
            color: #94a3b8;
            font-weight: 400;
        }

        /* 4. Status Badges */
        .badge-status-lunas,
        .table .badge-status-lunas {
            background-color: #dcfce7 !important;
            border: 1px solid #86efac !important;
            color: #15803d !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 7px !important;
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
            gap: 3.5px !important;
        }

        .badge-status-lunas *,
        .table .badge-status-lunas * {
            color: #15803d !important;
        }

        .badge-status-belum,
        .table .badge-status-belum {
            background-color: #fef2f2 !important;
            border: 1px solid #fecaca !important;
            color: #dc2626 !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 7px !important;
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
            gap: 3.5px !important;
        }

        .badge-status-belum *,
        .table .badge-status-belum * {
            color: #dc2626 !important;
        }

        /* 5. Action Buttons */
        .du-action-group {
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
        .modal-header-payment,
        .modal-header-clean {
            background: #16a34a !important;
            color: #ffffff !important;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
            padding: 16px 20px;
            border-bottom: none !important;
        }

        .modal-header-payment .modal-title,
        .modal-header-payment .modal-title *,
        .modal-header-payment h5,
        .modal-header-payment i,
        .modal-header-payment span,
        .modal-header-payment button,
        .modal-header-payment .close,
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

        .payment-mode-box {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .fee-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
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

        /* =========================================================================
           DEDICATED STYLES FOR MODAL PEMBAYARAN DU (Clean, Calm, School Admin)
           ========================================================================= */
        .modal-bayar-du .modal-content {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            background: #ffffff;
        }

        /* 1. Light, Clean Header with Green Accent */
        .modal-bayar-du .modal-header {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
        }

        .modal-bayar-du .header-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-bayar-du .header-icon-badge {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .modal-bayar-du .header-main-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a !important;
            line-height: 1.25;
            margin: 0;
        }

        .modal-bayar-du .header-sub-info {
            font-size: 13px;
            color: #64748b !important;
            font-weight: 500;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .modal-bayar-du .header-sub-info strong {
            color: #1e293b !important;
        }

        .modal-bayar-du .modal-close-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid transparent;
            background: transparent;
            color: #94a3b8 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.15s ease;
            padding: 0;
            line-height: 1;
            box-shadow: none;
            opacity: 1 !important;
        }

        .modal-bayar-du .modal-close-btn:hover {
            background: #f1f5f9;
            color: #475569 !important;
            border-color: #e2e8f0;
        }

        /* 2. Ringkasan Tagihan (Calm Unified Summary Box) */
        .modal-bayar-du .summary-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 18px;
        }

        .modal-bayar-du .summary-col {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .modal-bayar-du .summary-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b;
        }

        .modal-bayar-du .summary-value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .modal-bayar-du .summary-value.text-terbayar {
            color: #16a34a;
        }

        .modal-bayar-du .summary-value.text-sisa {
            color: #dc2626;
            font-size: 16px;
        }

        .modal-bayar-du .tagihan-lunas-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* 3. Form Controls */
        .modal-bayar-du .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0;
            padding-top: 8px;
        }

        .modal-bayar-du .form-control-custom {
            height: 38px;
            font-size: 13.5px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
            color: #1e293b;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .modal-bayar-du .form-control-custom:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .modal-bayar-du .nominal-input-group .input-group-text {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #475569;
            font-weight: 700;
            font-size: 14px;
            padding: 0 14px;
        }

        .modal-bayar-du .nominal-input-group .form-control-custom {
            border-radius: 0 8px 8px 0;
            font-size: 15.5px;
            font-weight: 700;
            color: #15803d;
        }

        .modal-bayar-du .quick-fill-btn {
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 6px;
            background: #ffffff;
            border: 1px solid #bbf7d0;
            color: #15803d;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .modal-bayar-du .quick-fill-btn:hover {
            background: #f0fdf4;
            border-color: #86efac;
            color: #166534;
        }

        /* 4. Payment Methods Segmented */
        .modal-bayar-du .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .modal-bayar-du .method-radio-card {
            position: relative;
            margin: 0;
            cursor: pointer;
        }

        .modal-bayar-du .method-radio-card input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .modal-bayar-du .method-card-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            height: 38px;
            padding: 0 10px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .modal-bayar-du .method-radio-card input:checked + .method-card-box {
            background: #f0fdf4;
            border-color: #16a34a;
            color: #15803d;
            box-shadow: 0 0 0 1px #16a34a;
        }

        .modal-bayar-du .method-radio-card:hover .method-card-box {
            border-color: #94a3b8;
        }

        /* 5. Upload Bukti Box */
        .modal-bayar-du .compact-upload-box {
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 8px 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .modal-bayar-du .compact-upload-box:hover {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        /* 6. Riwayat Accordion */
        .modal-bayar-du .riwayat-section {
            margin-top: 18px;
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
        }

        .modal-bayar-du .btn-riwayat-toggle {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 12.5px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .modal-bayar-du .btn-riwayat-toggle:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        /* 7. Footer Actions */
        .modal-bayar-du .modal-footer-custom {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 12px 22px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-bayar-du .btn-cancel-neutral {
            height: 38px;
            padding: 0 18px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .modal-bayar-du .btn-cancel-neutral:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #94a3b8;
        }

        .modal-bayar-du .btn-submit-pay {
            height: 38px;
            padding: 0 20px;
            border-radius: 8px;
            background: #16a34a;
            border: 1px solid #16a34a;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .modal-bayar-du .btn-submit-pay:hover:not(:disabled) {
            background: #15803d;
            border-color: #15803d;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
            transform: translateY(-1px);
        }

        .modal-bayar-du .btn-submit-pay:disabled {
            opacity: 0.55;
            cursor: not-allowed;
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
    <div class="du-page-header">
        <div class="du-title-wrapper">
            <div>
                <h1 class="du-title">
                    <span class="du-title-icon"><i class="fas fa-user-check"></i></span>
                    Daftar Ulang Siswa (DU)
                </h1>
                <p class="du-desc">
                    Kelola data tagihan daftar ulang siswa baru dan kenaikan kelas, verifikasi kuitansi, dan pelunasan tahun ajaran aktif.
                </p>
            </div>
            <div class="du-header-badges">
                <span class="du-badge-ta">
                    <i class="far fa-calendar-alt text-success"></i>
                    Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
                </span>
                <span class="du-badge-total">
                    <i class="fas fa-users text-muted"></i>
                    Total: {{ method_exists($data, 'total') ? $data->total() : $data->count() }} Siswa
                </span>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CARD & TABLE --}}
    <div class="du-card">
        <div class="du-card-header">
            <div class="du-toolbar">
                <div class="du-toolbar-left">
                    <div class="du-search-box">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            id="duSearchInput"
                            class="du-search-input"
                            placeholder="Cari NIS atau nama siswa..."
                        >
                    </div>
                </div>

                <div class="d-flex align-items-center" style="gap: 8px;">
                    <button
                        type="button"
                        class="btn-toolbar-massal"
                        data-toggle="modal"
                        data-target="#modalTerapkanMassalDu"
                        title="Terapkan Tagihan Massal ke Seluruh Siswa Aktif"
                    >
                        <i class="fas fa-users-cog"></i>
                        <span>Terapkan Massal</span>
                    </button>

                    <button type="button" class="btn-du-add" data-toggle="modal" data-target="#modalTambahDu">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Tagihan</span>
                    </button>
                </div>
            </div>
        </div>

        @php
            $currentSort = $sort ?? request('sort', 'nama');
            $currentDir = $direction ?? request('direction', 'asc');
            $sortUrl = function($column) use ($currentSort, $currentDir) {
                $nextDir = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';
                return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDir, 'page' => 1]);
            };
        @endphp

        <div class="du-table-responsive">
            <table class="table" id="duTable">
                <thead>
                    <tr>
                        <th style="width: 26px; padding-left: 1px; padding-right: 1px;" class="text-center">No</th>
                        <th style="width: 70px;" class="sortable {{ $currentSort === 'nis' ? 'sort-active' : '' }}">
                            <a href="{{ $sortUrl('nis') }}" class="sort-header-link" title="Klik untuk mengurutkan berdasarkan NIS ({{ $currentSort === 'nis' && $currentDir === 'asc' ? 'Terbesar ke Terkecil' : 'Terkecil ke Terbesar' }})">
                                <span>NIS</span>
                                @if($currentSort === 'nis')
                                    <i class="fas fa-sort-{{ $currentDir === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </a>
                        </th>
                        <th style="min-width: 100px; max-width: 125px;" class="sortable {{ $currentSort === 'nama' ? 'sort-active' : '' }}">
                            <a href="{{ $sortUrl('nama') }}" class="sort-header-link" title="Klik untuk mengurutkan berdasarkan Nama Siswa ({{ $currentSort === 'nama' && $currentDir === 'asc' ? 'Z ke A' : 'A ke Z' }})">
                                <span>Nama Siswa</span>
                                @if($currentSort === 'nama')
                                    <i class="fas fa-sort-{{ $currentDir === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </a>
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
                        <th class="text-right text-end sortable {{ $currentSort === 'sisa' ? 'sort-active' : '' }}" style="width: 74px;">
                            <a href="{{ $sortUrl('sisa') }}" class="sort-header-link justify-content-end text-right" title="Klik untuk mengurutkan berdasarkan Sisa Tagihan ({{ $currentSort === 'sisa' && $currentDir === 'asc' ? 'Terbesar ke Terkecil' : 'Terkecil ke Terbesar' }})">
                                <span>Sisa (Rp)</span>
                                @if($currentSort === 'sisa')
                                    <i class="fas fa-sort-{{ $currentDir === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                @else
                                    <i class="fas fa-sort sort-icon"></i>
                                @endif
                            </a>
                        </th>
                        <th style="min-width: 125px;" class="text-center">Status</th>
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
                            $isLunasPenuh = ($sisa <= 0 && $totalTagihan > 0);
                        @endphp
                        <tr
                            data-nis="{{ $item->siswa->nis ?? '' }}"
                            data-nama="{{ $item->siswa->nama ?? '' }}"
                            data-sisa="{{ $sisa }}"
                        >
                            {{-- 1. No --}}
                            <td class="text-center text-muted row-number font-num font-weight-500">
                                {{ method_exists($data, 'firstItem') && $data->firstItem() ? ($data->firstItem() + $loop->index) : $loop->iteration }}
                            </td>

                            {{-- 2. NIS --}}
                            <td class="font-num text-secondary">
                                {{ $item->siswa->nis ?? '-' }}
                            </td>

                            {{-- 3. Nama Siswa --}}
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

                            {{-- 4. Target (Rp) --}}
                            <td class="text-right text-end font-num val-target">
                                {{ number_format($item->target, 0, ',', '.') }}
                            </td>

                            {{-- 5. Terbawa (Rp) --}}
                            <td class="text-right text-end font-num">
                                @if($sisaTerbawa > 0)
                                    <div class="val-terbawa-active">
                                        {{ number_format($sisaTerbawa, 0, ',', '.') }}
                                    </div>
                                    <span class="sub-terbawa-tag" title="Sisa tagihan belum lunas dari tahun sebelumnya">
                                        Thn lalu
                                    </span>
                                @else
                                    <span class="val-terbawa-zero">0</span>
                                @endif
                            </td>

                            {{-- 6. Total Tagihan (Rp) --}}
                            <td class="text-right text-end font-num val-total-tagihan">
                                {{ number_format($totalTagihan, 0, ',', '.') }}
                            </td>

                            {{-- 7. Terbayar (Rp) --}}
                            <td class="text-right text-end font-num val-terbayar">
                                {{ number_format($terbayar, 0, ',', '.') }}
                            </td>

                            {{-- 8. Sisa (Rp) --}}
                            <td class="text-right text-end font-num">
                                @if($sisa > 0)
                                    <span class="val-sisa-active">
                                        {{ number_format($sisa, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="val-sisa-zero">0</span>
                                @endif
                            </td>

                            {{-- 9. Status --}}
                            <td class="text-center">
                                @if($isLunasPenuh)
                                    <span class="badge badge-status-lunas">Lunas</span>
                                @else
                                    <span class="badge badge-status-belum">Belum Lunas</span>
                                @endif
                            </td>

                            {{-- 10. Aksi --}}
                            <td class="text-center">
                                <div class="du-action-group">
                                    {{-- Tombol 1: Bayar (Hijau) --}}
                                    <button type="button"
                                            class="btn-act-bayar"
                                            title="Bayar Tagihan DU"
                                            data-toggle="modal"
                                            data-target="#modalBayar{{ $item->id }}"
                                            {{ $isLunasPenuh ? 'disabled' : '' }}>
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>

                                    {{-- Tombol 2: Edit (Kuning) Modal Popup --}}
                                    <button type="button"
                                            class="btn-act-edit"
                                            title="Edit Data Tagihan"
                                            data-toggle="modal"
                                            data-target="#modalEditDu{{ $item->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    {{-- Tombol 3: Hapus (Merah) --}}
                                    <button type="button"
                                            class="btn-act-hapus"
                                            title="Hapus Tagihan"
                                            data-toggle="modal"
                                            data-target="#modalHapus{{ $item->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data tagihan Daftar Ulang pada tahun ajaran ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links (25 per halaman) --}}
        @if(method_exists($data, 'hasPages') && ($data->hasPages() || $data->total() > 0))
            <div class="table-pagination-container px-3 pb-3">
                <div class="table-pagination-info">
                    Menampilkan <strong>{{ $data->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $data->lastItem() ?? 0 }}</strong> dari <strong>{{ $data->total() }}</strong> siswa
                </div>
                <div class="table-pagination-links">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>

    {{-- =========================================================
         MODALS SECTION
         ========================================================= --}}

    {{-- MODAL TERAPKAN MASSAL DU --}}
    <div class="modal fade" id="modalTerapkanMassalDu" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-payment">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-users-cog mr-2"></i>
                        <span id="labelHeaderTerapkanMassalDu">Terapkan Tagihan Massal Daftar Ulang</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTerapkanMassalDu" action="{{ route('du.terapkan-massal') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">

                    {{-- STEP 1: FORM INPUT --}}
                    <div id="stepMassalDuFormInput">
                        <div class="modal-body p-4">
                            <div class="alert alert-info py-2 px-3 mb-3 small" style="border-radius: 8px;">
                                <i class="fas fa-info-circle mr-1"></i>
                                Fitur ini akan membuat tagihan <strong>Daftar Ulang (DU)</strong> untuk <strong>seluruh siswa</strong> pada tahun ajaran <strong>{{ $selectedTa->nama ?? 'Aktif' }}</strong>. Siswa yang sudah memiliki tagihan ini akan <strong>dilewati secara otomatis</strong>.
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nominal Tagihan untuk Semua Siswa (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light" style="border-radius: 8px 0 0 8px;">Rp</span>
                                    </div>
                                    <input type="number" name="nominal" id="massal_du_nominal" class="form-control font-weight-bold font-num text-success" placeholder="0" min="0" step="any" style="border-radius: 0 8px 8px 0; height: 38px; font-size: 14px;" required>
                                </div>
                                <small class="text-muted">Nominal tagihan Daftar Ulang yang akan diterapkan ke seluruh siswa.</small>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2 px-4 justify-content-end" style="border-top: 1px solid #e2e8f0;">
                            <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="button" id="btnTriggerKonfirmasiMassalDu" class="btn-simpan-hijau px-4">
                                Terapkan
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2: KONFIRMASI RINGKASAN --}}
                    <div id="stepMassalDuKonfirmasi" style="display: none;">
                        <div class="modal-body p-4">
                            <p class="mb-3 text-dark font-weight-500" style="font-size: 14px;">
                                Apakah Anda yakin ingin menerapkan tagihan Daftar Ulang ini secara massal ke seluruh siswa aktif?
                            </p>

                            <div class="p-3 bg-light border rounded mb-0" style="border-radius: 8px;">
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 14px;">
                                    <span class="text-muted">Jenis Tagihan:</span>
                                    <span class="font-weight-bold text-dark">Daftar Ulang (DU)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 14px;">
                                    <span class="text-muted">Nominal Tagihan:</span>
                                    <span class="font-weight-bold text-success" id="konfirmasiMassalDuNominal">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2" style="font-size: 14px;">
                                    <span class="text-muted">Tahun Ajaran:</span>
                                    <span class="font-weight-bold text-dark">{{ $selectedTa->nama ?? 'Aktif' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2 px-4 justify-content-between" style="border-top: 1px solid #e2e8f0;">
                            <button type="button" class="btn btn-batal-merah px-4" id="btnBackToFormMassalDu" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-arrow-left mr-1"></i> Ubah
                            </button>
                            <button type="button" id="btnEksekusiTerapkanMassalDu" class="btn-simpan-hijau px-4">
                                Ya, Terapkan Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH TAGIHAN DU --}}
    @php
        $allSiswaDu = \App\Models\Siswa::orderBy('nama')->get();
        $existingSiswaIds = \App\Models\Pembayaran::whereHas('jenisPembayaran', fn($q) => $q->where('nama', 'DU'))
            ->when($tahunAjaranId, fn($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->pluck('siswa_id')
            ->toArray();
        $bulanIndoList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp
    <div class="modal fade" id="modalTambahDu" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-payment">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-user-check mr-2"></i>
                        Tambah Tagihan Daftar Ulang (DU)
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('du.store') }}" method="POST" id="formModalTambahDu">
                    @csrf
                    <div class="modal-body p-4">

                        {{-- Alert Jika Siswa Duplikat --}}
                        <div id="modalDuDuplicateAlert" class="alert alert-danger d-none mb-3 py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <strong>Peringatan:</strong> Siswa ini sudah memiliki data tagihan Daftar Ulang (DU) pada tahun ajaran ini. Tidak dapat menambahkan tagihan ganda!
                        </div>
                        @if($errors->has('siswa_id'))
                            <div class="alert alert-danger mb-3 py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Error:</strong> {{ $errors->first('siswa_id') }}
                            </div>
                        @endif

                        {{-- Pilih Siswa --}}
                        <div class="form-group row mb-3">
                            <label for="modal_du_siswa_id" class="col-sm-3 col-form-label font-weight-bold">
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select name="siswa_id"
                                        id="modal_du_siswa_id"
                                        class="form-control @error('siswa_id') is-invalid @enderror"
                                        style="border-radius: 8px;"
                                        required>
                                    <option value="">-- Pilih Siswa yang Ditagihkan --</option>
                                    @foreach($allSiswaDu as $itemSiswa)
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

                                <div id="modalDuSelectedSiswaBox" class="mt-2 p-2 bg-light border rounded small d-none" style="border-radius: 8px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-muted">Nama:</span> <strong id="modalDuPreviewNama">-</strong>
                                            <span class="mx-2">|</span>
                                            <span class="text-muted">NIS:</span> <span id="modalDuPreviewNis" class="font-weight-bold">-</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-light text-dark border" id="modalDuPreviewKelas">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Target Nominal --}}
                        <div class="form-group row mb-3">
                            <label for="modal_du_target" class="col-sm-3 col-form-label font-weight-bold">
                                Target Tagihan <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light">Rp</span>
                                    </div>
                                    <input type="number"
                                           name="target"
                                           id="modal_du_target"
                                           class="form-control font-weight-bold text-success font-num"
                                           placeholder="Masukkan nominal target pembayaran..."
                                           min="0"
                                           step="1000"
                                           style="border-radius: 0 8px 8px 0;"
                                           required>
                                </div>
                                <small class="form-text text-muted">
                                    Total kewajiban tagihan Daftar Ulang untuk siswa pada tahun ajaran ini.
                                </small>
                            </div>
                        </div>

                        {{-- Summary Fee Box --}}
                        <div class="fee-info-box my-3">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Jenis Tagihan</div>
                                    <div class="font-weight-bold text-dark">
                                        Daftar Ulang (DU)
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="text-muted small">Total Target Disimpan</div>
                                    <div class="font-weight-bold text-success font-num" style="font-size: 18px;" id="modalDuSummaryTotalLabel">
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
                        <button type="submit" id="btnSubmitTambahDu" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Tagihan DU
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODALS PER BARIS: BAYAR, EDIT, HAPUS --}}
    @foreach($data as $item)
        @php
            $terbayarItem = (float) $item->detailPembayaran->sum('nominal');
            $terbawaItem = (float) ($item->belum_lunas ?? 0);
            $totalTagihanItem = (float) $item->target + $terbawaItem;
            $sisaItem = max($totalTagihanItem - $terbayarItem, 0);
            $sisaTerbawaItem = max($terbawaItem - $terbayarItem, 0);
        @endphp

        {{-- MODAL BAYAR --}}
        <div class="modal fade modal-bayar-du" id="modalBayar{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow">
                    
                    {{-- 1. HEADER --}}
                    <div class="modal-header">
                        <div class="header-title-wrap">
                            <div class="header-icon-badge">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div>
                                <h5 class="header-main-title">Pembayaran Daftar Ulang</h5>
                                <div class="header-sub-info">
                                    <strong>{{ $item->siswa->nama ?? '-' }}</strong>
                                    <span>·</span>
                                    <span>NIS: {{ $item->siswa->nis ?? '-' }}</span>
                                    <span>·</span>
                                    <span>Kelas: {{ $item->siswa->kelas ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('du.bayar', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">

                            {{-- 2. RINGKASAN TAGIHAN (Calm Unified Summary Box) --}}
                            <div class="summary-container">
                                <div class="row align-items-center">
                                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                                        <div class="summary-col">
                                            <span class="summary-label">Target Tahun Ini</span>
                                            <span class="summary-value font-num">Rp {{ number_format($item->target, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-2 mb-md-0">
                                        <div class="summary-col">
                                            <span class="summary-label">Terbawa Tahun Lalu</span>
                                            <span class="summary-value font-num {{ $sisaTerbawaItem > 0 ? 'text-danger' : '' }}">
                                                Rp {{ number_format($sisaTerbawaItem, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="summary-col">
                                            <span class="summary-label">Total Terbayar</span>
                                            <span class="summary-value font-num text-terbayar">Rp {{ number_format($terbayarItem, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="summary-col">
                                            <span class="summary-label">Sisa Tagihan Total</span>
                                            <span class="summary-value font-num text-sisa">Rp {{ number_format($sisaItem, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($sisaItem <= 0)
                                <div class="tagihan-lunas-card">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>
                                        <span class="text-dark font-weight-semibold small">Tagihan Daftar Ulang siswa ini sudah <strong>LUNAS</strong>.</span>
                                    </div>
                                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 11px;">Lunas</span>
                                </div>
                            @elseif($sisaTerbawaItem > 0)
                                <div class="alert alert-warning py-2 px-3 mb-3 small d-flex align-items-center gap-2" style="border-radius: 8px; font-size: 12px;">
                                    <i class="fas fa-info-circle text-warning"></i>
                                    <div>
                                        Siswa memiliki <strong>sisa tagihan terbawa tahun lalu</strong> sebesar <strong>Rp {{ number_format($sisaTerbawaItem, 0, ',', '.') }}</strong>. Pembayaran akan otomatis melunasi tagihan terbawa terlebih dahulu.
                                    </div>
                                </div>
                            @endif

                            {{-- 3. FORM PEMBAYARAN --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 form-label-custom">Tanggal <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-custom bg-light font-num" value="{{ date('d/m/Y') }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 form-label-custom">Nominal Pembayaran <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="input-group nominal-input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="nominal"
                                               id="nominalInputModalDu{{ $item->id }}"
                                               class="form-control form-control-custom font-num"
                                               max="{{ $sisaItem }}"
                                               min="1"
                                               value=""
                                               placeholder="Masukkan nominal..."
                                               required>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap gap-1">
                                        <small class="text-muted" style="font-size: 11.5px;">
                                            Maksimal pembayaran: <strong>Rp {{ number_format($sisaItem, 0, ',', '.') }}</strong>
                                        </small>
                                        @if($sisaItem > 0)
                                            <button type="button"
                                                    class="quick-fill-btn"
                                                    onclick="document.getElementById('nominalInputModalDu{{ $item->id }}').value = '{{ (int)$sisaItem }}';">
                                                <i class="fas fa-coins"></i> Bayar Lunas (Rp {{ number_format($sisaItem, 0, ',', '.') }})
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 form-label-custom">Metode Pembayaran <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="payment-methods-grid">
                                        <label class="method-radio-card">
                                            <input type="radio" name="metode" value="Cash" checked>
                                            <div class="method-card-box">
                                                <i class="fas fa-money-bill-wave text-success"></i>
                                                <span>Cash / Tunai</span>
                                            </div>
                                        </label>
                                        <label class="method-radio-card">
                                            <input type="radio" name="metode" value="Transfer">
                                            <div class="method-card-box">
                                                <i class="fas fa-university text-primary"></i>
                                                <span>Bank Transfer</span>
                                            </div>
                                        </label>
                                        <label class="method-radio-card">
                                            <input type="radio" name="metode" value="QRIS">
                                            <div class="method-card-box">
                                                <i class="fas fa-qrcode text-dark"></i>
                                                <span>QRIS / E-Wallet</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 form-label-custom">Bukti Transfer</label>
                                <div class="col-sm-9">
                                    <div class="custom-file-upload">
                                        <input type="file"
                                               name="bukti"
                                               id="buktiDu{{ $item->id }}"
                                               class="file-input-hidden"
                                               accept="image/*,.pdf"
                                               onchange="if(this.files && this.files[0]) { document.getElementById('labelBuktiDu{{ $item->id }}').innerHTML = '<i class=\'fas fa-file text-success mr-2\'></i><span class=\'font-weight-bold text-dark\'>' + this.files[0].name + '</span>'; }">
                                        <label for="buktiDu{{ $item->id }}" class="compact-upload-box mb-1">
                                            <span id="labelBuktiDu{{ $item->id }}" class="text-muted text-truncate" style="max-width: 78%; font-size: 12.5px;">
                                                <i class="fas fa-cloud-upload-alt text-success mr-2"></i> Pilih foto / file struk transfer...
                                            </span>
                                            <span class="btn btn-xs btn-outline-success font-weight-bold" style="border-radius: 6px; font-size: 11px;">
                                                <i class="fas fa-folder-open mr-1"></i> Browse
                                            </span>
                                        </label>
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 11.5px;">Lampirkan bukti jika melalui Bank Transfer / QRIS (opsional, maks 3MB)</small>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label class="col-sm-3 form-label-custom">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea name="keterangan"
                                              class="form-control form-control-custom"
                                              rows="2"
                                              style="height: auto;"
                                              placeholder="Catatan pembayaran (opsional)..."></textarea>
                                </div>
                            </div>

                            {{-- 4. RIWAYAT PEMBAYARAN --}}
                            <div class="riwayat-section">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <button class="btn-riwayat-toggle" type="button" data-toggle="collapse" data-target="#riwayatCollapse{{ $item->id }}" aria-expanded="false">
                                        <i class="fas fa-history text-muted"></i>
                                        <span>Lihat Riwayat Pembayaran ({{ $item->detailPembayaran->count() }})</span>
                                        <i class="fas fa-chevron-down ml-1 text-muted" style="font-size: 10px;"></i>
                                    </button>
                                    <span class="small text-muted font-num">{{ $item->detailPembayaran->count() }} Transaksi</span>
                                </div>

                                <div class="collapse" id="riwayatCollapse{{ $item->id }}">
                                    <div class="card card-body p-2 bg-light border shadow-none mb-0" style="max-height: 200px; overflow-y: auto; border-radius: 8px;">
                                        <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 12px;">
                                            <thead class="bg-light text-dark">
                                                <tr>
                                                    <th width="30" class="text-center">No</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-center">Bulan Dibayar</th>
                                                    <th>Nominal (Rp)</th>
                                                    <th>Metode</th>
                                                    <th>Bukti</th>
                                                    <th>Keterangan</th>
                                                    <th width="45" class="text-center">Cetak</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($item->detailPembayaran as $detail)
                                                    <tr>
                                                        <td class="text-center font-num text-muted">{{ $loop->iteration }}</td>
                                                        <td class="font-num">{{ \Carbon\Carbon::parse($detail->tanggal)->format('d/m/Y') }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-status-lunas" style="font-size: 10.5px;">
                                                                <i class="fas fa-calendar-check mr-1 text-success"></i>
                                                                {{ ($bulanIndoList[(int)\Carbon\Carbon::parse($detail->tanggal)->format('n')] ?? \Carbon\Carbon::parse($detail->tanggal)->format('F')) . ' ' . \Carbon\Carbon::parse($detail->tanggal)->format('Y') }}
                                                            </span>
                                                        </td>
                                                        <td class="font-weight-bold text-success font-num">{{ number_format($detail->nominal, 0, ',', '.') }}</td>
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
                                                        <td colspan="8" class="text-center py-3 text-muted">Belum ada riwayat pembayaran.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- 5. FOOTER --}}
                        <div class="modal-footer-custom">
                            <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn-submit-pay" {{ $sisaItem <= 0 ? 'disabled' : '' }}>
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT DATA TAGIHAN DU --}}
        <div class="modal fade" id="modalEditDu{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditDuLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-clean">
                        <h5 class="modal-title font-weight-bold text-white" id="modalEditDuLabel{{ $item->id }}" style="font-size: 16px;">
                            <i class="fas fa-user-edit mr-2 text-white"></i>
                            Edit Data Tagihan Daftar Ulang (DU)
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('du.update', $item->id) }}" method="POST" id="formModalEditDu{{ $item->id }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-body p-4">

                            {{-- Section 1: Siswa --}}
                            <div class="form-group row mb-3">
                                <label for="modal_edit_siswa_{{ $item->id }}" class="col-sm-3 col-form-label font-weight-bold">
                                    Siswa <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select name="siswa_id"
                                            id="modal_edit_siswa_{{ $item->id }}"
                                            class="form-control @error('siswa_id') is-invalid @enderror"
                                            style="border-radius: 8px;"
                                            required>
                                        @foreach($allSiswaDu as $itemSiswa)
                                            <option value="{{ $itemSiswa->id }}" {{ old('siswa_id', $item->siswa_id) == $itemSiswa->id ? 'selected' : '' }}>
                                                {{ $itemSiswa->nis }} - {{ $itemSiswa->nama }} ({{ $itemSiswa->kelas }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('siswa_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Section 2: Info Terbawa Tahun Lalu --}}
                            @if((float) ($item->belum_lunas ?? 0) > 0)
                                <div class="alert alert-warning py-2 px-3 mb-3 small" style="border-radius: 8px;">
                                    <div class="font-weight-bold">
                                        <i class="fas fa-history mr-1"></i> Tagihan Terbawa Tahun Lalu: Rp {{ number_format($item->belum_lunas, 0, ',', '.') }}
                                    </div>
                                    <div class="text-muted mt-1" style="font-size: 11.5px;">
                                        Tagihan terbawa ini tetap tersimpan dan otomatis ditambahkan ke target baru. Total tagihan siswa = Target Baru + Tagihan Terbawa.
                                    </div>
                                </div>
                            @endif

                            {{-- Section 3: Target Pembayaran --}}
                            <div class="form-group row mb-3">
                                <label for="modal_edit_target_{{ $item->id }}" class="col-sm-3 col-form-label font-weight-bold">
                                    Target Tagihan (Tahun Ini) <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-light">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="target"
                                               id="modal_edit_target_{{ $item->id }}"
                                               class="form-control font-weight-bold text-success font-num"
                                               value="{{ old('target', (int)$item->target) }}"
                                               min="0"
                                               step="1000"
                                               placeholder="Masukkan target pembayaran..."
                                               style="border-radius: 0 8px 8px 0;"
                                               required>
                                    </div>
                                    @error('target')
                                        <div class="invalid-feedback d-block">
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

        {{-- MODAL HAPUS --}}
        <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header py-3 px-4 bg-light border-bottom">
                        <h5 class="modal-title font-weight-bold text-danger" style="font-size: 16px;">
                            <i class="fas fa-trash-alt text-danger mr-2"></i>
                            Konfirmasi Hapus Tagihan
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-2 text-dark" style="font-size: 14px;">
                            Apakah Anda yakin ingin menghapus tagihan Daftar Ulang untuk siswa:
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
                        <form action="{{ route('du.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-trash-alt mr-1"></i>
                                Ya, Hapus Tagihan
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
                    <div class="modal-header modal-header-payment">
                        <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-check-circle text-white mr-2"></i> Pembayaran Berhasil</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                    </div>
                    <div class="modal-body text-center py-4 px-4">
                        <i class="fas fa-receipt text-success fa-3x mb-3"></i>
                        <h5 class="font-weight-bold">Pembayaran Daftar Ulang berhasil dicatat!</h5>
                        <p class="text-muted mb-0">Apakah Anda ingin mencetak kuitansi pembayaran sekarang?</p>
                    </div>
                    <div class="modal-footer bg-light justify-content-center py-3">
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">Tutup</button>
                        <a href="{{ route('bukti.cetak', session('last_detail_id')) }}"
                           target="_blank"
                           class="btn btn-success px-4 font-weight-bold"
                           style="border-radius: 8px; height: 38px; color: #ffffff !important;"
                           id="btnCetakBukti">
                            <i class="fas fa-print mr-1" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Cetak Kuitansi</span>
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
    const table = document.getElementById('duTable');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const searchInput = document.getElementById('duSearchInput');
    let sortColumn = '';
    let sortDirection = 'asc';

    function getRows() {
        return Array.from(tbody.querySelectorAll('tr[data-nis]'));
    }

    function updateNumber() {
        const rows = getRows();
        const pageStartIndex = {{ method_exists($data, 'firstItem') && $data->firstItem() ? $data->firstItem() : 1 }};
        let number = pageStartIndex;
        rows.forEach(function (row) {
            const numberCell = row.querySelector('.row-number');
            if (numberCell && row.style.display !== 'none') {
                numberCell.textContent = number;
                number++;
            }
        });
    }

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

    @if(session('last_detail_id'))
        $('#modalCetakBukti').modal('show');
        $('#btnCetakBukti').on('click', function () {
            setTimeout(function () {
                $('#modalCetakBukti').modal('hide');
            }, 500);
        });
    @endif

    @if(session('open_modal_tambah') === 'du' || $errors->has('siswa_id'))
        $('#modalTambahDu').modal('show');
    @endif

    // Handler Modal Tambah DU
    const modalDuSiswaSelect = document.getElementById('modal_du_siswa_id');
    const modalDuSelectedSiswaBox = document.getElementById('modalDuSelectedSiswaBox');
    const modalDuPreviewNama = document.getElementById('modalDuPreviewNama');
    const modalDuPreviewNis = document.getElementById('modalDuPreviewNis');
    const modalDuPreviewKelas = document.getElementById('modalDuPreviewKelas');
    const modalDuTargetInput = document.getElementById('modal_du_target');
    const modalDuSummaryTotalLabel = document.getElementById('modalDuSummaryTotalLabel');
    const modalDuDuplicateAlert = document.getElementById('modalDuDuplicateAlert');
    const modalDuSubmitBtn = document.getElementById('btnSubmitTambahDu');

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value || 0);
    }

    if (modalDuSiswaSelect) {
        modalDuSiswaSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                modalDuPreviewNama.textContent = opt.dataset.nama || '-';
                modalDuPreviewNis.textContent = opt.dataset.nis || '-';
                modalDuPreviewKelas.textContent = opt.dataset.kelas || '-';
                modalDuSelectedSiswaBox.classList.remove('d-none');

                if (opt.dataset.exists === '1') {
                    if (modalDuDuplicateAlert) modalDuDuplicateAlert.classList.remove('d-none');
                    if (modalDuSubmitBtn) modalDuSubmitBtn.disabled = true;
                } else {
                    if (modalDuDuplicateAlert) modalDuDuplicateAlert.classList.add('d-none');
                    if (modalDuSubmitBtn) modalDuSubmitBtn.disabled = false;
                }
            } else {
                modalDuSelectedSiswaBox.classList.add('d-none');
                if (modalDuDuplicateAlert) modalDuDuplicateAlert.classList.add('d-none');
                if (modalDuSubmitBtn) modalDuSubmitBtn.disabled = false;
            }
        });
    }

    if (modalDuTargetInput && modalDuSummaryTotalLabel) {
        modalDuTargetInput.addEventListener('input', function () {
            const val = parseFloat(this.value) || 0;
            modalDuSummaryTotalLabel.textContent = `Rp ${formatRupiah(val)}`;
        });
    }

    // Modal Terapkan Massal DU
    const modalTerapkanMassalDu = $('#modalTerapkanMassalDu');
    const stepMassalDuFormInput = document.getElementById('stepMassalDuFormInput');
    const stepMassalDuKonfirmasi = document.getElementById('stepMassalDuKonfirmasi');
    const labelHeaderTerapkanMassalDu = document.getElementById('labelHeaderTerapkanMassalDu');
    const btnTriggerKonfirmasiMassalDu = document.getElementById('btnTriggerKonfirmasiMassalDu');
    const btnBackToFormMassalDu = document.getElementById('btnBackToFormMassalDu');
    const btnEksekusiTerapkanMassalDu = document.getElementById('btnEksekusiTerapkanMassalDu');
    const formTerapkanMassalDu = document.getElementById('formTerapkanMassalDu');

    function switchStepMassalDu(step) {
        if (step === 'confirm') {
            if (stepMassalDuFormInput) stepMassalDuFormInput.style.display = 'none';
            if (stepMassalDuKonfirmasi) stepMassalDuKonfirmasi.style.display = 'block';
            if (labelHeaderTerapkanMassalDu) labelHeaderTerapkanMassalDu.textContent = 'Konfirmasi Penerapan Massal DU';
        } else {
            if (stepMassalDuKonfirmasi) stepMassalDuKonfirmasi.style.display = 'none';
            if (stepMassalDuFormInput) stepMassalDuFormInput.style.display = 'block';
            if (labelHeaderTerapkanMassalDu) labelHeaderTerapkanMassalDu.textContent = 'Terapkan Tagihan Massal Daftar Ulang';
        }
    }

    if (btnTriggerKonfirmasiMassalDu && formTerapkanMassalDu) {
        btnTriggerKonfirmasiMassalDu.addEventListener('click', function () {
            const inputNominal = document.getElementById('massal_du_nominal');
            const valNominal = parseFloat(inputNominal ? inputNominal.value : 0) || 0;

            if (!formTerapkanMassalDu.checkValidity()) {
                formTerapkanMassalDu.reportValidity();
                return;
            }

            if (valNominal <= 0) {
                if (inputNominal) {
                    inputNominal.setCustomValidity('Nominal tagihan harus lebih dari 0.');
                    inputNominal.reportValidity();
                    inputNominal.setCustomValidity('');
                }
                return;
            }

            const elemNom = document.getElementById('konfirmasiMassalDuNominal');
            if (elemNom) elemNom.textContent = 'Rp ' + formatRupiah(valNominal);

            switchStepMassalDu('confirm');
        });
    }

    if (btnBackToFormMassalDu) {
        btnBackToFormMassalDu.addEventListener('click', function () {
            switchStepMassalDu('input');
        });
    }

    if (btnEksekusiTerapkanMassalDu && formTerapkanMassalDu) {
        btnEksekusiTerapkanMassalDu.addEventListener('click', function () {
            btnEksekusiTerapkanMassalDu.disabled = true;
            btnEksekusiTerapkanMassalDu.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menerapkan...';
            formTerapkanMassalDu.submit();
        });
    }

    modalTerapkanMassalDu.on('hidden.bs.modal', function () {
        switchStepMassalDu('input');
        if (!$('.modal.show').length) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css({'padding-right': '', 'overflow': ''});
        }
    });
});
</script>
@stop