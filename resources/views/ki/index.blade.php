@extends('adminlte::page')

@section('title', 'Pembayaran Kegiatan Intrakurikuler')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: HALAMAN PEMBAYARAN KEGIATAN INTRAKURIKULER (KI)
           =============================================================== */

        /* 1. Header Box */
        .ki-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .ki-title-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ki-title {
            font-size: 17.5px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 2px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ki-title-icon {
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

        .ki-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        .ki-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ki-badge-ta {
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

        .ki-badge-total {
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
        .ki-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .ki-card-header {
            background: #ffffff;
            padding: 13px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .ki-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ki-toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .ki-search-box {
            position: relative;
            width: 290px;
            max-width: 100%;
        }

        .ki-search-box i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12.5px;
        }

        .ki-search-input {
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

        .ki-search-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .btn-ki-add {
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

        .btn-ki-add:hover {
            background: #15803d;
            color: #ffffff;
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.2);
            transform: translateY(-1px);
        }

        /* 3. Table Container */
        .ki-table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }

        .ki-table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .ki-table-responsive::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 999px;
        }

        .ki-table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .ki-table-responsive::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        #kiTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
        }

        #kiTable thead th {
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

        #kiTable th.sortable {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #kiTable th.sortable:hover {
            background-color: #e6f9ed !important;
            color: #0f172a !important;
        }

        #kiTable .sort-icon {
            margin-left: 2px;
            font-size: 9.5px;
            opacity: 0.45;
        }

        #kiTable th.sort-active {
            color: #15803d !important;
        }

        #kiTable th.sort-active .sort-icon {
            opacity: 1;
            color: #15803d;
        }

        #kiTable tbody td {
            padding: 6px 3px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.25;
            font-size: 12px;
            white-space: nowrap;
        }

        #kiTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #kiTable tbody tr:hover {
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

        /* 4. Status Badges (Text Only) */
        .badge-status-lunas,
        .table .badge-status-lunas {
            background-color: #dcfce7 !important;
            border: 1px solid #86efac !important;
            color: #15803d !important;
            font-size: 10px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 1.5px 6.5px !important;
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
            line-height: 1.15;
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
            padding: 1.5px 6.5px !important;
            display: inline-flex !important;
            align-items: center !important;
            white-space: nowrap !important;
            line-height: 1.15;
        }

        .badge-status-belum *,
        .table .badge-status-belum * {
            color: #dc2626 !important;
        }

        /* Sub-Tagihan 3-Status Group */
        .ki-status-group {
            display: flex;
            flex-direction: column;
            gap: 2.5px;
            align-items: flex-start;
            justify-content: center;
            padding: 1px 0;
        }

        .ki-status-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            white-space: nowrap !important;
        }

        .ki-status-label {
            font-weight: 700;
            color: #475569;
            min-width: 32px;
            font-size: 9.5px;
            letter-spacing: -0.01em;
        }

        /* 5. Action Buttons */
        .ki-action-group {
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

        .subtagihan-select-box {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 8px;
            padding: 10px 14px;
        }

        /* =========================================================================
           DEDICATED STYLES FOR MODAL PEMBAYARAN KI (Clean, Calm, School Admin)
           ========================================================================= */
        .modal-bayar-ki .modal-content {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            background: #ffffff;
        }

        /* 1. Light, Clean Header with Green Accent */
        .modal-bayar-ki .modal-header {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
        }

        .modal-bayar-ki .header-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-bayar-ki .header-icon-badge {
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

        .modal-bayar-ki .header-main-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a !important;
            line-height: 1.25;
            margin: 0;
        }

        .modal-bayar-ki .header-sub-info {
            font-size: 13px;
            color: #64748b !important;
            font-weight: 500;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .modal-bayar-ki .header-sub-info strong {
            color: #1e293b !important;
        }

        .modal-bayar-ki .modal-close-btn {
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

        .modal-bayar-ki .modal-close-btn:hover {
            background: #f1f5f9;
            color: #475569 !important;
            border-color: #e2e8f0;
        }

        /* 2. Ringkasan Tagihan 3 Sub-Kegiatan (Calm Unified Summary Box) */
        .modal-bayar-ki .summary-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 18px;
        }

        .modal-bayar-ki .summary-subtagihan-col {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding: 0 8px;
        }

        .modal-bayar-ki .summary-subtagihan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-bayar-ki .summary-sub-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11.5px;
            color: #64748b;
        }

        .modal-bayar-ki .summary-sub-line strong {
            font-weight: 600;
        }

        /* 3. Dropdown Pilih Tagihan */
        .modal-bayar-ki .kategori-select-container {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 9px;
            padding: 10px 14px;
            margin-bottom: 18px;
        }

        .modal-bayar-ki .kategori-select-container select {
            border: 1px solid #86efac;
            background: #ffffff;
            border-radius: 7px;
            height: 36px;
            font-size: 13px;
            font-weight: 600;
            color: #166534;
        }

        .modal-bayar-ki .kategori-select-container select:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
        }

        /* 4. Form Controls */
        .modal-bayar-ki .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0;
            padding-top: 8px;
        }

        .modal-bayar-ki .form-control-custom {
            height: 38px;
            font-size: 13.5px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
            color: #1e293b;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .modal-bayar-ki .form-control-custom:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
        }

        .modal-bayar-ki .nominal-input-group .input-group-text {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #475569;
            font-weight: 700;
            font-size: 14px;
            padding: 0 14px;
        }

        .modal-bayar-ki .nominal-input-group .form-control-custom {
            border-radius: 0 8px 8px 0;
            font-size: 15.5px;
            font-weight: 700;
            color: #15803d;
        }

        .modal-bayar-ki .quick-fill-btn {
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

        .modal-bayar-ki .quick-fill-btn:hover {
            background: #f0fdf4;
            border-color: #86efac;
            color: #166534;
        }

        /* 5. Payment Methods Segmented */
        .modal-bayar-ki .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .modal-bayar-ki .method-radio-card {
            position: relative;
            margin: 0;
            cursor: pointer;
        }

        .modal-bayar-ki .method-radio-card input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .modal-bayar-ki .method-card-box {
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

        .modal-bayar-ki .method-radio-card input:checked + .method-card-box {
            background: #f0fdf4;
            border-color: #16a34a;
            color: #15803d;
            box-shadow: 0 0 0 1px #16a34a;
        }

        .modal-bayar-ki .method-radio-card:hover .method-card-box {
            border-color: #94a3b8;
        }

        /* 6. Upload Bukti Box */
        .modal-bayar-ki .compact-upload-box {
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

        .modal-bayar-ki .compact-upload-box:hover {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        /* 7. Riwayat Accordion */
        .modal-bayar-ki .riwayat-section {
            margin-top: 18px;
            border-top: 1px solid #f1f5f9;
            padding-top: 14px;
        }

        .modal-bayar-ki .btn-riwayat-toggle {
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

        .modal-bayar-ki .btn-riwayat-toggle:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        /* 8. Footer Actions */
        .modal-bayar-ki .modal-footer-custom {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 12px 22px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-bayar-ki .btn-cancel-neutral {
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

        .modal-bayar-ki .btn-cancel-neutral:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #94a3b8;
        }

        .modal-bayar-ki .btn-submit-pay {
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

        .modal-bayar-ki .btn-submit-pay:hover:not(:disabled) {
            background: #15803d;
            border-color: #15803d;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
            transform: translateY(-1px);
        }

        .modal-bayar-ki .btn-submit-pay:disabled {
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
    <div class="ki-page-header">
        <div class="ki-title-wrapper">
            <div>
                <h1 class="ki-title">
                    <span class="ki-title-icon"><i class="fas fa-book-open"></i></span>
                    Kegiatan Intrakurikuler (KI)
                </h1>
                <p class="ki-desc">
                    Kelola data tagihan UTS, UAS, dan Ujian siswa, verifikasi kuitansi pembayaran, serta status pelunasan tiap kegiatan.
                </p>
            </div>
            <div class="ki-header-badges">
                <span class="ki-badge-ta">
                    <i class="far fa-calendar-alt text-success"></i>
                    Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
                </span>
                <span class="ki-badge-total">
                    <i class="fas fa-users text-muted"></i>
                    Total: {{ $data->count() }} Siswa
                </span>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CARD & TABLE --}}
    <div class="ki-card">
        <div class="ki-card-header">
            <div class="ki-toolbar">
                <div class="ki-toolbar-left">
                    <div class="ki-search-box">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            id="kiSearchInput"
                            class="ki-search-input"
                            placeholder="Cari NIS atau nama siswa..."
                        >
                    </div>
                </div>

                <button type="button" class="btn-ki-add" data-toggle="modal" data-target="#modalTambahKi">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Tagihan</span>
                </button>
            </div>
        </div>

        <div class="ki-table-responsive">
            <table class="table" id="kiTable">
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
                        <th style="min-width: 155px;" class="text-center">Status (UTS / UAS / Ujian)</th>
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

                            // Sub-tagihan KI: UTS, UAS, Ujian
                            $subStatus = $item->statusKiSubtagihan();
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

                            {{-- 9. Status 3 Kategori (UTS, UAS, Ujian) --}}
                            <td class="text-left" style="padding-left: 8px;">
                                <div class="ki-status-group">
                                    {{-- UTS --}}
                                    <div class="ki-status-item">
                                        <span class="ki-status-label">UTS:</span>
                                        @if($subStatus['UTS']['is_lunas'])
                                            <span class="badge badge-status-lunas">Lunas</span>
                                        @else
                                            <span class="badge badge-status-belum">Belum Lunas</span>
                                        @endif
                                    </div>
                                    {{-- UAS --}}
                                    <div class="ki-status-item">
                                        <span class="ki-status-label">UAS:</span>
                                        @if($subStatus['UAS']['is_lunas'])
                                            <span class="badge badge-status-lunas">Lunas</span>
                                        @else
                                            <span class="badge badge-status-belum">Belum Lunas</span>
                                        @endif
                                    </div>
                                    {{-- Ujian --}}
                                    <div class="ki-status-item">
                                        <span class="ki-status-label">Ujian:</span>
                                        @if($subStatus['Ujian']['is_lunas'])
                                            <span class="badge badge-status-lunas">Lunas</span>
                                        @else
                                            <span class="badge badge-status-belum">Belum Lunas</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- 10. Aksi --}}
                            <td class="text-center">
                                <div class="ki-action-group">
                                    {{-- Tombol 1: Bayar (Hijau) --}}
                                    <button type="button"
                                            class="btn-act-bayar"
                                            title="Bayar Tagihan KI"
                                            data-toggle="modal"
                                            data-target="#modalBayar{{ $item->id }}"
                                            {{ $isLunasPenuh ? 'disabled' : '' }}>
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>

                                    {{-- Tombol 2: Edit (Kuning) Modal Popup --}}
                                    <button type="button"
                                            class="btn-act-edit"
                                            title="Edit Data Tagihan KI"
                                            data-toggle="modal"
                                            data-target="#modalEditKi{{ $item->id }}">
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
                                Belum ada data tagihan Kegiatan Intrakurikuler pada tahun ajaran ini.
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

    {{-- MODAL TAMBAH TAGIHAN KI (UTS, UAS, UJIAN) --}}
    @php
        $allSiswaKi = \App\Models\Siswa::orderBy('nama')->get();
        $existingSiswaIds = $data->pluck('siswa_id')->toArray();
        $bulanIndoList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp
    <div class="modal fade" id="modalTambahKi" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-payment">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-book-open mr-2"></i>
                        Tambah Tagihan Kegiatan Intrakurikuler (UTS, UAS, Ujian)
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('ki.store') }}" method="POST" id="formModalTambahKi">
                    @csrf
                    <div class="modal-body p-4">

                        {{-- Alert Jika Siswa Duplikat --}}
                        <div id="modalKiDuplicateAlert" class="alert alert-danger d-none mb-3 py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <strong>Peringatan:</strong> Siswa ini sudah memiliki data tagihan Kegiatan Intrakurikuler (KI) pada tahun ajaran ini. Tidak dapat menambahkan tagihan ganda!
                        </div>
                        @if($errors->has('siswa_id'))
                            <div class="alert alert-danger mb-3 py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Error:</strong> {{ $errors->first('siswa_id') }}
                            </div>
                        @endif

                        {{-- Pilih Siswa --}}
                        <div class="form-group row mb-3">
                            <label for="modal_ki_siswa_id" class="col-sm-3 col-form-label font-weight-bold">
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select name="siswa_id"
                                        id="modal_ki_siswa_id"
                                        class="form-control @error('siswa_id') is-invalid @enderror"
                                        style="border-radius: 8px;"
                                        required>
                                    <option value="">-- Pilih Siswa yang Ditagihkan --</option>
                                    @foreach($allSiswaKi as $itemSiswa)
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

                                <div id="modalKiSelectedSiswaBox" class="mt-2 p-2 bg-light border rounded small d-none" style="border-radius: 8px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-muted">Nama:</span> <strong id="modalKiPreviewNama">-</strong>
                                            <span class="mx-2">|</span>
                                            <span class="text-muted">NIS:</span> <span id="modalKiPreviewNis" class="font-weight-bold">-</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-light text-dark border" id="modalKiPreviewKelas">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rincian Tagihan: UTS, UAS, Ujian --}}
                        <div class="border rounded p-3 mb-3 bg-light" style="border-radius: 10px;">
                            <div class="font-weight-bold text-dark mb-2" style="font-size: 13.5px;">
                                <i class="fas fa-list-check text-success mr-1"></i>
                                Tentukan Nominal Target Tiap Kegiatan:
                            </div>

                            {{-- 1. Target UTS --}}
                            <div class="form-group row mb-2">
                                <label for="modal_ki_target_uts" class="col-sm-3 col-form-label font-weight-semibold">
                                    Target UTS <span class="text-muted small">(Rp)</span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="target_uts"
                                               id="modal_ki_target_uts"
                                               class="form-control font-weight-bold text-success font-num ki-subtarget-input"
                                               placeholder="Nominal UTS..."
                                               min="0"
                                               step="1000"
                                               value="{{ old('target_uts') }}"
                                               style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>
                            </div>

                            {{-- 2. Target UAS --}}
                            <div class="form-group row mb-2">
                                <label for="modal_ki_target_uas" class="col-sm-3 col-form-label font-weight-semibold">
                                    Target UAS <span class="text-muted small">(Rp)</span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="target_uas"
                                               id="modal_ki_target_uas"
                                               class="form-control font-weight-bold text-success font-num ki-subtarget-input"
                                               placeholder="Nominal UAS..."
                                               min="0"
                                               step="1000"
                                               value="{{ old('target_uas') }}"
                                               style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>
                            </div>

                            {{-- 3. Target Ujian --}}
                            <div class="form-group row mb-0">
                                <label for="modal_ki_target_ujian" class="col-sm-3 col-form-label font-weight-semibold">
                                    Target Ujian <span class="text-muted small">(Rp)</span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="target_ujian"
                                               id="modal_ki_target_ujian"
                                               class="form-control font-weight-bold text-success font-num ki-subtarget-input"
                                               placeholder="Nominal Ujian..."
                                               min="0"
                                               step="1000"
                                               value="{{ old('target_ujian') }}"
                                               style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Summary Fee Box --}}
                        <div class="fee-info-box my-3">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Rincian Kegiatan:</div>
                                    <div class="font-weight-bold text-dark small" id="modalKiBreakdownLabel">
                                        UTS: Rp 0 | UAS: Rp 0 | Ujian: Rp 0
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="text-muted small">Total Tagihan KI</div>
                                    <div class="font-weight-bold text-success font-num" style="font-size: 18px;" id="modalKiSummaryTotalLabel">
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
                        <button type="submit" id="btnSubmitTambahKi" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Tagihan KI
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

            // Sub-tagihan per kategori
            $subUts = $item->statusKiSubtagihan()['UTS'];
            $subUas = $item->statusKiSubtagihan()['UAS'];
            $subUjian = $item->statusKiSubtagihan()['Ujian'];

            // Tentukan default kategori aktif (prioritaskan yang belum lunas dan punya target)
            $defaultKat = 'UTS';
            if ($subUts['sisa'] <= 0 && $subUas['sisa'] > 0) {
                $defaultKat = 'UAS';
            } elseif ($subUts['sisa'] <= 0 && $subUas['sisa'] <= 0 && $subUjian['sisa'] > 0) {
                $defaultKat = 'Ujian';
            }
            $activeSub = $item->statusKiSubtagihan()[$defaultKat];
            $activeSisa = (float) $activeSub['sisa'];
        @endphp

        {{-- MODAL BAYAR --}}
        <div class="modal fade modal-bayar-ki" id="modalBayar{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow">
                    
                    {{-- 1. HEADER --}}
                    <div class="modal-header">
                        <div class="header-title-wrap">
                            <div class="header-icon-badge">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div>
                                <h5 class="header-main-title">Pembayaran Kunjungan Industri (KI)</h5>
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

                    <form action="{{ route('ki.bayar', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">

                            {{-- 2. RINGKASAN 3 SUB-TAGIHAN (Calm Unified Summary Box) --}}
                            <div class="summary-container">
                                <div class="row">
                                    <div class="col-md-4 col-12 mb-2 mb-md-0 border-right">
                                        <div class="summary-subtagihan-col">
                                            <div class="summary-subtagihan-header">
                                                <span>1. UTS</span>
                                                <span class="badge {{ $subUts['sisa'] <= 0 && $subUts['target'] > 0 ? 'badge-success' : 'badge-light border' }}" style="font-size: 10px;">
                                                    {{ $subUts['sisa'] <= 0 && $subUts['target'] > 0 ? 'Lunas' : 'Belum Lunas' }}
                                                </span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Target</span>
                                                <span class="font-num font-weight-semibold">Rp {{ number_format($subUts['target'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Terbayar</span>
                                                <span class="text-success font-num font-weight-semibold">Rp {{ number_format($subUts['terbayar'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Sisa</span>
                                                <strong class="{{ $subUts['sisa'] > 0 ? 'text-danger' : 'text-success' }} font-num">Rp {{ number_format($subUts['sisa'], 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-2 mb-md-0 border-right">
                                        <div class="summary-subtagihan-col">
                                            <div class="summary-subtagihan-header">
                                                <span>2. UAS</span>
                                                <span class="badge {{ $subUas['sisa'] <= 0 && $subUas['target'] > 0 ? 'badge-success' : 'badge-light border' }}" style="font-size: 10px;">
                                                    {{ $subUas['sisa'] <= 0 && $subUas['target'] > 0 ? 'Lunas' : 'Belum Lunas' }}
                                                </span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Target</span>
                                                <span class="font-num font-weight-semibold">Rp {{ number_format($subUas['target'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Terbayar</span>
                                                <span class="text-success font-num font-weight-semibold">Rp {{ number_format($subUas['terbayar'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Sisa</span>
                                                <strong class="{{ $subUas['sisa'] > 0 ? 'text-danger' : 'text-success' }} font-num">Rp {{ number_format($subUas['sisa'], 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="summary-subtagihan-col">
                                            <div class="summary-subtagihan-header">
                                                <span>3. Ujian</span>
                                                <span class="badge {{ $subUjian['sisa'] <= 0 && $subUjian['target'] > 0 ? 'badge-success' : 'badge-light border' }}" style="font-size: 10px;">
                                                    {{ $subUjian['sisa'] <= 0 && $subUjian['target'] > 0 ? 'Lunas' : 'Belum Lunas' }}
                                                </span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Target</span>
                                                <span class="font-num font-weight-semibold">Rp {{ number_format($subUjian['target'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Terbayar</span>
                                                <span class="text-success font-num font-weight-semibold">Rp {{ number_format($subUjian['terbayar'], 0, ',', '.') }}</span>
                                            </div>
                                            <div class="summary-sub-line">
                                                <span>Sisa</span>
                                                <strong class="{{ $subUjian['sisa'] > 0 ? 'text-danger' : 'text-success' }} font-num">Rp {{ number_format($subUjian['sisa'], 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 3. PILIH SUB-TAGIHAN DIBAYAR --}}
                            <div class="kategori-select-container">
                                <div class="form-group row mb-0 align-items-center">
                                    <label class="col-sm-4 col-form-label font-weight-bold text-success small mb-0">
                                        <i class="fas fa-check-circle mr-1"></i> Pilih Tagihan Dibayar <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select name="kategori"
                                                id="selectKategoriKi{{ $item->id }}"
                                                class="form-control"
                                                required
                                                onchange="updateKiPaymentForm({{ $item->id }})">
                                            <option value="UTS"
                                                    data-sisa="{{ (int)$subUts['sisa'] }}"
                                                    data-target="{{ (int)$subUts['target'] }}"
                                                    {{ $defaultKat === 'UTS' ? 'selected' : '' }}
                                                    {{ $subUts['sisa'] <= 0 && $subUts['target'] > 0 ? 'disabled' : '' }}>
                                                UTS — Sisa: Rp {{ number_format($subUts['sisa'], 0, ',', '.') }} {{ $subUts['sisa'] <= 0 && $subUts['target'] > 0 ? '(Lunas)' : '' }}
                                            </option>
                                            <option value="UAS"
                                                    data-sisa="{{ (int)$subUas['sisa'] }}"
                                                    data-target="{{ (int)$subUas['target'] }}"
                                                    {{ $defaultKat === 'UAS' ? 'selected' : '' }}
                                                    {{ $subUas['sisa'] <= 0 && $subUas['target'] > 0 ? 'disabled' : '' }}>
                                                UAS — Sisa: Rp {{ number_format($subUas['sisa'], 0, ',', '.') }} {{ $subUas['sisa'] <= 0 && $subUas['target'] > 0 ? '(Lunas)' : '' }}
                                            </option>
                                            <option value="Ujian"
                                                    data-sisa="{{ (int)$subUjian['sisa'] }}"
                                                    data-target="{{ (int)$subUjian['target'] }}"
                                                    {{ $defaultKat === 'Ujian' ? 'selected' : '' }}
                                                    {{ $subUjian['sisa'] <= 0 && $subUjian['target'] > 0 ? 'disabled' : '' }}>
                                                Ujian — Sisa: Rp {{ number_format($subUjian['sisa'], 0, ',', '.') }} {{ $subUjian['sisa'] <= 0 && $subUjian['target'] > 0 ? '(Lunas)' : '' }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 4. FORM PEMBAYARAN --}}
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
                                               id="nominalInputModalKi{{ $item->id }}"
                                               class="form-control form-control-custom font-num"
                                               max="{{ $activeSisa > 0 ? (int)$activeSisa : 999999999 }}"
                                               min="1"
                                               value=""
                                               placeholder="Masukkan nominal..."
                                               required>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap gap-1">
                                        <small class="text-muted" id="maxNominalLabelKi{{ $item->id }}" style="font-size: 11.5px;">
                                            Maksimal pembayaran {{ $defaultKat }}: <strong>Rp {{ number_format($activeSisa, 0, ',', '.') }}</strong>
                                        </small>
                                        <button type="button"
                                                id="btnQuickFillKi{{ $item->id }}"
                                                class="quick-fill-btn"
                                                style="{{ $activeSisa <= 0 ? 'display: none;' : '' }}"
                                                onclick="quickFillKiNominal({{ $item->id }})">
                                            <i class="fas fa-coins"></i> <span id="labelQuickFillKi{{ $item->id }}">Bayar Lunas {{ $defaultKat }} (Rp {{ number_format($activeSisa, 0, ',', '.') }})</span>
                                        </button>
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
                                               id="buktiKi{{ $item->id }}"
                                               class="file-input-hidden"
                                               accept="image/*,.pdf"
                                               onchange="if(this.files && this.files[0]) { document.getElementById('labelBuktiKi{{ $item->id }}').innerHTML = '<i class=\'fas fa-file text-success mr-2\'></i><span class=\'font-weight-bold text-dark\'>' + this.files[0].name + '</span>'; }">
                                        <label for="buktiKi{{ $item->id }}" class="compact-upload-box mb-1">
                                            <span id="labelBuktiKi{{ $item->id }}" class="text-muted text-truncate" style="max-width: 78%; font-size: 12.5px;">
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

                            {{-- 5. RIWAYAT PEMBAYARAN --}}
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
                                                    <th class="text-center">Kegiatan</th>
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
                                                            <span class="badge badge-success px-2 py-1" style="font-size: 10px; border-radius: 6px;">
                                                                {{ $detail->kategori ?? 'KI' }}
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

                        {{-- 6. FOOTER --}}
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

        {{-- MODAL EDIT DATA TAGIHAN KI (UTS, UAS, UJIAN) --}}
        <div class="modal fade" id="modalEditKi{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditKiLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-clean">
                        <h5 class="modal-title font-weight-bold text-white" id="modalEditKiLabel{{ $item->id }}" style="font-size: 16px;">
                            <i class="fas fa-user-edit mr-2 text-white"></i>
                            Edit Data Tagihan Kegiatan Intrakurikuler (KI)
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('ki.update', $item->id) }}" method="POST" id="formModalEditKi{{ $item->id }}">
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
                                        @foreach($allSiswaKi as $itemSiswa)
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

                            {{-- Section 3: Sub-Target Nominal --}}
                            <div class="border rounded p-3 mb-3 bg-light" style="border-radius: 10px;">
                                <div class="font-weight-bold text-dark mb-2" style="font-size: 13.5px;">
                                    <i class="fas fa-list-check text-success mr-1"></i>
                                    Edit Nominal Target Kegiatan (Tahun Ini):
                                </div>

                                {{-- Target UTS --}}
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label font-weight-semibold">Target UTS (Rp)</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                            </div>
                                            <input type="number"
                                                   name="target_uts"
                                                   id="modal_edit_target_uts_{{ $item->id }}"
                                                   class="form-control font-weight-bold text-success font-num ki-edit-subtarget-input"
                                                   data-item-id="{{ $item->id }}"
                                                   value="{{ old('target_uts', (int)($item->target_uts ?? 0)) }}"
                                                   min="0"
                                                   step="1000"
                                                   style="border-radius: 0 8px 8px 0;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Target UAS --}}
                                <div class="form-group row mb-2">
                                    <label class="col-sm-3 col-form-label font-weight-semibold">Target UAS (Rp)</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                            </div>
                                            <input type="number"
                                                   name="target_uas"
                                                   id="modal_edit_target_uas_{{ $item->id }}"
                                                   class="form-control font-weight-bold text-success font-num ki-edit-subtarget-input"
                                                   data-item-id="{{ $item->id }}"
                                                   value="{{ old('target_uas', (int)($item->target_uas ?? 0)) }}"
                                                   min="0"
                                                   step="1000"
                                                   style="border-radius: 0 8px 8px 0;">
                                        </div>
                                    </div>
                                </div>

                                {{-- Target Ujian --}}
                                <div class="form-group row mb-0">
                                    <label class="col-sm-3 col-form-label font-weight-semibold">Target Ujian (Rp)</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                            </div>
                                            <input type="number"
                                                   name="target_ujian"
                                                   id="modal_edit_target_ujian_{{ $item->id }}"
                                                   class="form-control font-weight-bold text-success font-num ki-edit-subtarget-input"
                                                   data-item-id="{{ $item->id }}"
                                                   value="{{ old('target_ujian', (int)($item->target_ujian ?? 0)) }}"
                                                   min="0"
                                                   step="1000"
                                                   style="border-radius: 0 8px 8px 0;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Summary Edit Box --}}
                            <div class="fee-info-box my-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-12 mb-2 mb-md-0">
                                        <div class="text-muted small">Total Terbayar Saat Ini</div>
                                        <div class="font-weight-bold text-success font-num">
                                            Rp {{ number_format($terbayarItem, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="text-muted small">Total Target Baru</div>
                                        <div class="font-weight-bold text-dark font-num" style="font-size: 17px;" id="modalEditSummaryTotalLabel_{{ $item->id }}">
                                            Rp {{ number_format($item->target, 0, ',', '.') }}
                                        </div>
                                    </div>
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
                            Apakah Anda yakin ingin menghapus tagihan Kegiatan Intrakurikuler untuk siswa:
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
                        <form action="{{ route('ki.destroy', $item->id) }}" method="POST" class="d-inline">
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
                        <h5 class="font-weight-bold">Pembayaran Kegiatan Intrakurikuler berhasil dicatat!</h5>
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
function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID').format(value || 0);
}

function updateKiPaymentForm(itemId) {
    const select = document.getElementById('selectKategoriKi' + itemId);
    const input = document.getElementById('nominalInputModalKi' + itemId);
    const maxLabel = document.getElementById('maxNominalLabelKi' + itemId);
    const quickBtn = document.getElementById('btnQuickFillKi' + itemId);
    const quickLabel = document.getElementById('labelQuickFillKi' + itemId);

    if (!select || !input) return;

    const opt = select.options[select.selectedIndex];
    const sisa = opt ? (parseFloat(opt.dataset.sisa) || 0) : 0;
    const kategori = opt ? opt.value : 'KI';

    input.max = sisa > 0 ? sisa : 999999999;
    input.value = ''; // Selalu kosongkan saat ganti pilihan atau dibuka

    if (maxLabel) {
        maxLabel.innerHTML = `Maksimal pembayaran ${kategori}: <strong>Rp ${formatRupiah(sisa)}</strong>`;
    }

    if (quickBtn && quickLabel) {
        if (sisa > 0) {
            quickBtn.style.display = 'inline-flex';
            quickLabel.textContent = `Bayar Lunas ${kategori} (Rp ${formatRupiah(sisa)})`;
        } else {
            quickBtn.style.display = 'none';
        }
    }
}

function quickFillKiNominal(itemId) {
    const select = document.getElementById('selectKategoriKi' + itemId);
    const input = document.getElementById('nominalInputModalKi' + itemId);
    if (!select || !input) return;
    const opt = select.options[select.selectedIndex];
    const sisa = opt ? (parseFloat(opt.dataset.sisa) || 0) : 0;
    if (sisa > 0) {
        input.value = Math.floor(sisa);
    }
}

$(document).on('shown.bs.modal', '[id^="modalBayar"]', function () {
    const id = this.id.replace('modalBayar', '');
    updateKiPaymentForm(id);
});

document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('kiTable');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const searchInput = document.getElementById('kiSearchInput');
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
        document.querySelectorAll('#kiTable th.sortable').forEach(function (header) {
            header.classList.remove('sort-active');
            const icon = header.querySelector('.sort-icon');
            if (icon) icon.className = 'fas fa-sort sort-icon';
        });

        const activeHeader = document.querySelector('#kiTable th[data-sort="' + sortColumn + '"]');
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

    document.querySelectorAll('#kiTable th.sortable').forEach(function (header) {
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

    @if(session('open_modal_tambah') === 'ki' || $errors->has('siswa_id'))
        $('#modalTambahKi').modal('show');
    @endif

    // Handler Modal Tambah KI
    const modalKiSiswaSelect = document.getElementById('modal_ki_siswa_id');
    const modalKiSelectedSiswaBox = document.getElementById('modalKiSelectedSiswaBox');
    const modalKiPreviewNama = document.getElementById('modalKiPreviewNama');
    const modalKiPreviewNis = document.getElementById('modalKiPreviewNis');
    const modalKiPreviewKelas = document.getElementById('modalKiPreviewKelas');
    const modalKiSummaryTotalLabel = document.getElementById('modalKiSummaryTotalLabel');
    const modalKiBreakdownLabel = document.getElementById('modalKiBreakdownLabel');
    const modalKiDuplicateAlert = document.getElementById('modalKiDuplicateAlert');
    const modalKiSubmitBtn = document.getElementById('btnSubmitTambahKi');

    const inputUts = document.getElementById('modal_ki_target_uts');
    const inputUas = document.getElementById('modal_ki_target_uas');
    const inputUjian = document.getElementById('modal_ki_target_ujian');

    function calculateKiTambahTotal() {
        const uts = parseFloat(inputUts ? inputUts.value : 0) || 0;
        const uas = parseFloat(inputUas ? inputUas.value : 0) || 0;
        const ujian = parseFloat(inputUjian ? inputUjian.value : 0) || 0;
        const total = uts + uas + ujian;

        if (modalKiSummaryTotalLabel) {
            modalKiSummaryTotalLabel.textContent = `Rp ${formatRupiah(total)}`;
        }
        if (modalKiBreakdownLabel) {
            modalKiBreakdownLabel.textContent = `UTS: Rp ${formatRupiah(uts)} | UAS: Rp ${formatRupiah(uas)} | Ujian: Rp ${formatRupiah(ujian)}`;
        }
    }

    document.querySelectorAll('.ki-subtarget-input').forEach(function (inp) {
        inp.addEventListener('input', calculateKiTambahTotal);
    });

    if (modalKiSiswaSelect) {
        modalKiSiswaSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                modalKiPreviewNama.textContent = opt.dataset.nama || '-';
                modalKiPreviewNis.textContent = opt.dataset.nis || '-';
                modalKiPreviewKelas.textContent = opt.dataset.kelas || '-';
                modalKiSelectedSiswaBox.classList.remove('d-none');

                if (opt.dataset.exists === '1') {
                    if (modalKiDuplicateAlert) modalKiDuplicateAlert.classList.remove('d-none');
                    if (modalKiSubmitBtn) modalKiSubmitBtn.disabled = true;
                } else {
                    if (modalKiDuplicateAlert) modalKiDuplicateAlert.classList.add('d-none');
                    if (modalKiSubmitBtn) modalKiSubmitBtn.disabled = false;
                }
            } else {
                modalKiSelectedSiswaBox.classList.add('d-none');
                if (modalKiDuplicateAlert) modalKiDuplicateAlert.classList.add('d-none');
                if (modalKiSubmitBtn) modalKiSubmitBtn.disabled = false;
            }
        });
    }

    // Handler Edit Subtargets
    document.querySelectorAll('.ki-edit-subtarget-input').forEach(function (inp) {
        inp.addEventListener('input', function () {
            const itemId = this.dataset.itemId;
            const uts = parseFloat(document.getElementById('modal_edit_target_uts_' + itemId)?.value || 0) || 0;
            const uas = parseFloat(document.getElementById('modal_edit_target_uas_' + itemId)?.value || 0) || 0;
            const ujian = parseFloat(document.getElementById('modal_edit_target_ujian_' + itemId)?.value || 0) || 0;
            const total = uts + uas + ujian;
            const label = document.getElementById('modalEditSummaryTotalLabel_' + itemId);
            if (label) {
                label.textContent = `Rp ${formatRupiah(total)}`;
            }
        });
    });
});
</script>
@stop