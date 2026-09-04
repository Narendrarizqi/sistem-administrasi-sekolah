@extends('adminlte::page')

@section('title', 'Pembayaran Ekstrakurikuler')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: PEMBAYARAN EKSTRAKURIKULER
           (SERAGAM DENGAN TEMA SISTEM & HALAMAN ASESMEN)
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

        /* Toolbar Action Buttons (Matching Asesmen) */
        .ki-toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
        }

        .btn-toolbar-master {
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

        .btn-toolbar-master i {
            color: #6366f1;
            transition: color 0.18s ease;
        }

        .btn-toolbar-master:hover,
        .btn-toolbar-master:focus {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #4338ca !important;
            box-shadow: 0 1px 3px rgba(99, 102, 241, 0.08);
            transform: translateY(-1px);
        }

        .btn-toolbar-master:hover i,
        .btn-toolbar-master:focus i {
            color: #4f46e5;
        }

        .btn-toolbar-master:active {
            background: #e0e7ff;
            border-color: #a5b4fc;
            color: #3730a3 !important;
            transform: translateY(1px);
        }

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

        .btn-toolbar-tambah-ki,
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-toolbar-tambah-ki i,
        .btn-toolbar-tambah i {
            color: #ffffff;
        }

        .btn-toolbar-tambah-ki:hover,
        .btn-toolbar-tambah-ki:focus,
        .btn-toolbar-tambah:hover,
        .btn-toolbar-tambah:focus {
            background: #15803d;
            border-color: #15803d;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
        }

        .btn-toolbar-tambah-ki:active,
        .btn-toolbar-tambah:active {
            transform: translateY(1px);
        }

        @media (max-width: 768px) {
            .ki-toolbar-actions {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }
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

        #ekskulTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
        }

        #ekskulTable thead th {
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

        #ekskulTable th.sortable {
            padding: 0 !important;
            user-select: none;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #ekskulTable th.sortable .sort-header-link {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            padding: 7px 3px;
            color: inherit !important;
            font-size: inherit;
            font-weight: inherit;
            letter-spacing: inherit;
            text-decoration: none !important;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        #ekskulTable th.sortable:hover .sort-header-link {
            background-color: #e6f9ed !important;
            color: #0f172a !important;
        }

        #ekskulTable .sort-icon {
            margin-left: 2px;
            font-size: 9.5px;
            opacity: 0.45;
        }

        #ekskulTable th.sort-active .sort-header-link {
            color: #15803d !important;
            background-color: #e6f9ed !important;
        }

        #ekskulTable th.sort-active .sort-icon {
            opacity: 1;
            color: #15803d;
        }

        #ekskulTable tbody td {
            padding: 6px 3px;
            vertical-align: middle;
            color: #1e293b;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
            line-height: 1.25;
            font-size: 12px;
            white-space: nowrap;
        }

        #ekskulTable tbody tr {
            transition: background-color 0.15s ease;
        }

        #ekskulTable tbody tr:hover {
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

        .badge-status-sebagian,
        .table .badge-status-sebagian {
            background-color: #fffbeb !important;
            color: #b45309 !important;
            border: 1px solid #fde68a !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            letter-spacing: 0.02em;
        }

        .badge-status-sebagian *,
        .table .badge-status-sebagian * {
            color: #b45309 !important;
        }

        .ki-status-card {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: 100%;
            min-width: 145px;
            max-width: 175px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 9px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .ki-status-row {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            gap: 8px;
            padding: 1.5px 0;
        }

        .ki-status-row:not(:last-child) {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 3.5px;
        }

        .ki-status-name {
            font-size: 11px;
            font-weight: 600;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .ki-status-pill {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 52px !important;
            width: 52px !important;
            height: 20px !important;
            font-size: 9.5px !important;
            font-weight: 600 !important;
            padding: 0 4px !important;
            border-radius: 999px !important;
            line-height: 1 !important;
            flex-shrink: 0 !important;
            letter-spacing: 0.01em !important;
            text-align: center !important;
        }

        /* 5. Action Buttons */
        .ki-action-group,
        .action-group-box {
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

        /* Standarisasi Tombol Hijau */
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
            cursor: pointer !important;
            text-decoration: none !important;
        }

        .btn-simpan-hijau:hover,
        .btn-simpan-hijau:focus {
            background-color: #15803d !important;
            border-color: #15803d !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.35) !important;
        }

        .btn-simpan-hijau:active {
            transform: translateY(1px) !important;
        }

        /* Action Buttons Tabel Master */
        .act-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            white-space: nowrap !important;
        }

        .btn-act-toggle-off {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 11.5px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #ea580c !important;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-act-toggle-off:hover {
            background: #ea580c;
            border-color: #ea580c;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(234, 88, 12, 0.25);
        }

        .btn-act-toggle-on {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 11.5px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a !important;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-act-toggle-on:hover {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.25);
        }

        .badge-aktif-pill {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-nonaktif-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .font-num {
            font-family: 'Inter', monospace;
            font-feature-settings: 'tnum' on, 'lnum' on;
        }

        /* Penegasan tampilan badge/prefix Rp */
        .input-group-text {
            background-color: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            color: #1e293b !important;
            font-weight: 700 !important;
            font-size: 13.5px !important;
            letter-spacing: -0.01em;
            padding: 0 14px !important;
        }

        .input-group-prepend .input-group-text {
            border-right: none !important;
        }

        .fee-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
        }
    </style>
@stop

@section('content')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #16a34a;">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #f59e0b;">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            {{ session('warning') }}
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
                    <span class="ki-title-icon"><i class="fas fa-futbol"></i></span>
                    Ekstrakurikuler
                </h1>
                <p class="ki-desc">
                    Kelola data tagihan dan iuran kegiatan ekstrakurikuler siswa (Pramuka, Futsal, PMR, Paskibra, dll.), verifikasi pembayaran, serta status pelunasan.
                </p>
            </div>
            <div class="ki-header-badges">
                <span class="ki-badge-ta">
                    <i class="far fa-calendar-alt text-success"></i>
                    Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
                </span>
                <span class="ki-badge-total">
                    <i class="fas fa-users text-muted"></i>
                    Total: {{ method_exists($data, 'total') ? $data->total() : $data->count() }} Siswa
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
                            id="ekskulSearchInput"
                            class="ki-search-input"
                            placeholder="Cari NIS atau nama siswa..."
                        >
                    </div>
                </div>

                <div class="ki-toolbar-actions">
                    {{-- Tombol 1: Kelola Master --}}
                    <button
                        type="button"
                        class="btn-toolbar-master"
                        data-toggle="modal"
                        data-target="#modalKelolaJenisEkskul"
                        title="Kelola Ekstrakurikuler"
                    >
                        <i class="fas fa-sliders-h"></i>
                        <span>Kelola Ekstrakurikuler</span>
                    </button>

                    {{-- Tombol 2: Terapkan Massal ke Semua Siswa --}}
                    <button
                        type="button"
                        class="btn-toolbar-massal"
                        data-toggle="modal"
                        data-target="#modalTerapkanMassalEkskul"
                        title="Terapkan Tagihan Massal ke Seluruh Siswa Aktif"
                    >
                        <i class="fas fa-users-cog"></i>
                        <span>Terapkan Massal</span>
                    </button>

                    {{-- Tombol 3: Tambah Tagihan Siswa Baru --}}
                    <button
                        type="button"
                        class="btn-toolbar-tambah-ki"
                        data-toggle="modal"
                        data-target="#modalTambahSiswaEkskul"
                        title="Tambah Data Tagihan Siswa"
                    >
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

        <div class="ki-table-responsive">
            <table class="table" id="ekskulTable">
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
                        <th style="width: 175px; min-width: 165px;" class="text-center">Status Ekstrakurikuler</th>
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
                            $subStatus = $item->statusEkskulSubtagihan();
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

                            {{-- 3. Nama Siswa & Kelas --}}
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

                            {{-- 9. Status Subtagihan Dinamis --}}
                            <td class="text-center" style="padding: 6px 8px; vertical-align: middle;">
                                <div class="ki-status-card">
                                    @forelse($subStatus as $katNama => $st)
                                        <div class="ki-status-row">
                                            <span class="ki-status-name" title="{{ $katNama }}">{{ $katNama }}</span>
                                            @if($st['is_lunas'])
                                                <span class="badge badge-status-lunas ki-status-pill">Lunas</span>
                                            @elseif($st['terbayar'] > 0)
                                                <span class="badge badge-status-sebagian ki-status-pill">Sebagian</span>
                                            @else
                                                <span class="badge badge-status-belum ki-status-pill">Belum</span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-muted small">-</span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- 10. Aksi --}}
                            <td class="text-center">
                                <div class="ki-action-group">
                                    {{-- Tombol Bayar --}}
                                    <button type="button"
                                            class="btn-act-bayar"
                                            title="Bayar Tagihan"
                                            data-toggle="modal"
                                            data-target="#modalBayarEkskul{{ $item->id }}"
                                            {{ $isLunasPenuh ? 'disabled' : '' }}>
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button type="button"
                                            class="btn-act-edit"
                                            title="Edit Tagihan"
                                            data-toggle="modal"
                                            data-target="#modalEditEkskul{{ $item->id }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button type="button"
                                            class="btn-act-hapus"
                                            title="Hapus Tagihan"
                                            data-toggle="modal"
                                            data-target="#modalHapusEkskul{{ $item->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data tagihan Ekstrakurikuler pada tahun ajaran ini.
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

    {{-- MODALS PER ITEM (BAYAR, EDIT, HAPUS) --}}
    @foreach($data as $item)
        @php
            $terbayarItem = (float) $item->detailPembayaran->sum('nominal');
            $terbawaItem = (float) ($item->belum_lunas ?? 0);
            $totalTagihanItem = (float) $item->target + $terbawaItem;
            $sisaItem = max($totalTagihanItem - $terbayarItem, 0);

            $subtagihanMap = [];
            foreach ($item->itemsEkskul as $sub) {
                $subTerbayar = (float) $sub->terbayar;
                $subNominal = (float) $sub->nominal;
                $subSisa = max($subNominal - $subTerbayar, 0);
                $subtagihanMap[$sub->nama_ekskul] = [
                    'id'       => $sub->id,
                    'nominal'  => $subNominal,
                    'terbayar' => $subTerbayar,
                    'sisa'     => $subSisa,
                    'is_lunas' => ($subSisa <= 0 && $subNominal > 0),
                ];
            }

            $defaultKat = '';
            foreach ($subtagihanMap as $kName => $kData) {
                if ($kData['sisa'] > 0) {
                    $defaultKat = $kName;
                    break;
                }
            }
            $activeSub = $subtagihanMap[$defaultKat] ?? ['sisa' => 0, 'target' => 0];
            $activeSisa = (float) ($activeSub['sisa'] ?? 0);
        @endphp

        {{-- MODAL BAYAR --}}
        <div class="modal fade" id="modalBayarEkskul{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <form action="{{ route('ekstrakurikuler.bayar', $item->id) }}" method="POST">
                        @csrf
                        <div class="modal-header modal-header-payment">
                            <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                                <i class="fas fa-cash-register mr-2"></i> Pembayaran Ekstrakurikuler
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="bg-light p-3 rounded border mb-3" style="border-color: #e2e8f0 !important;">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12.5px;">
                                    <span class="text-muted">Nama Siswa:</span>
                                    <strong>{{ $item->siswa->nama ?? '-' }} ({{ $item->siswa->nis ?? '-' }})</strong>
                                </div>
                                <div class="d-flex justify-content-between" style="font-size: 12.5px;">
                                    <span class="text-muted">Sisa Tagihan Total:</span>
                                    <strong class="text-danger">Rp {{ number_format($sisaItem, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-semibold small mb-1">Pilih Item Ekstrakurikuler (Opsional)</label>
                                <select name="kategori" class="form-control select-kategori-ekstrakurikuler" style="border-radius: 8px; height: 38px; font-size: 13px;">
                                    <option value="" data-sisa="{{ $sisaItem }}">-- Semua / Bebas (Sisa Total: Rp {{ number_format($sisaItem, 0, ',', '.') }}) --</option>
                                    @foreach($subtagihanMap as $kat => $st)
                                        <option value="{{ $kat }}" data-sisa="{{ $st['sisa'] }}" {{ $kat === $defaultKat ? 'selected' : '' }}>
                                            {{ $kat }} (Sisa: Rp {{ number_format($st['sisa'], 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-semibold small mb-1">Nominal Pembayaran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white" style="font-size: 13px; border-radius: 8px 0 0 8px;">Rp</span>
                                    </div>
                                    <input type="number" name="nominal" class="form-control font-num input-nominal-ekstrakurikuler"
                                           value="{{ $activeSisa > 0 ? $activeSisa : $sisaItem }}" min="1" max="{{ $sisaItem }}" required style="border-radius: 0 8px 8px 0; height: 38px; font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-semibold small mb-1">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius: 8px; height: 38px; font-size: 13px;">
                            </div>
                        </div>
                        <div class="modal-footer py-2.5 px-4 bg-light d-flex justify-content-end" style="gap: 8px;">
                            <button type="button" class="btn-batal-merah" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn-simpan-hijau">
                                <i class="fas fa-check-circle mr-1"></i> Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT DATA TAGIHAN EKSTRAKURIKULER --}}
        <div class="modal fade" id="modalEditEkskul{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditEkskulLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-clean">
                        <h5 class="modal-title font-weight-bold text-white" id="modalEditEkskulLabel{{ $item->id }}" style="font-size: 16px;">
                            <i class="fas fa-user-edit mr-2 text-white"></i>
                            Edit Data Tagihan Ekstrakurikuler
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('ekstrakurikuler.update', $item->id) }}" method="POST" id="formModalEditEkskul{{ $item->id }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-body p-4">

                            {{-- Section 1: Siswa --}}
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label font-weight-bold">
                                    Siswa
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-light" value="{{ $item->siswa->nis ?? '-' }} - {{ $item->siswa->nama ?? '-' }} ({{ $item->siswa->kelas ?? '-' }})" readonly>
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

                            {{-- Section 3: Item Tagihan Ekstrakurikuler yang Dimiliki --}}
                            <div class="border rounded p-3 mb-3 bg-light" style="border-radius: 10px;">
                                <div class="font-weight-bold text-dark mb-2" style="font-size: 13.5px;">
                                    <i class="fas fa-list-check text-success mr-1"></i>
                                    Nominal Item Tagihan Ekstrakurikuler Siswa Ini:
                                </div>

                                @forelse($item->itemsEkskul as $itEkskul)
                                    <div class="form-group row mb-2 align-items-center">
                                        <label class="col-sm-4 col-form-label font-weight-semibold">
                                            {{ $itEkskul->nama_ekskul }} <span class="text-muted small">(Rp)</span>
                                        </label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                                </div>
                                                <input type="number"
                                                       name="items[{{ $itEkskul->id }}][nominal]"
                                                       class="form-control font-weight-bold text-success font-num ekskul-edit-item-input"
                                                       data-item-id="{{ $item->id }}"
                                                       value="{{ (int)$itEkskul->nominal }}"
                                                       min="{{ (int)$itEkskul->terbayar }}"
                                                       step="1000"
                                                       style="border-radius: 0 8px 8px 0;">
                                            </div>
                                            @if($itEkskul->terbayar > 0)
                                                <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                                    Sudah dibayar: <strong>Rp {{ number_format($itEkskul->terbayar, 0, ',', '.') }}</strong> (nominal tidak boleh kurang dari terbayar)
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted small mb-2">Belum ada item ekstrakurikuler khusus untuk siswa ini.</div>
                                @endforelse

                                @php
                                    $assignedNames = $item->itemsEkskul->pluck('nama_ekskul')->toArray();
                                    $unassignedActive = $jenisEkskulAktif->whereNotIn('nama', $assignedNames);
                                @endphp

                                @if($unassignedActive->isNotEmpty())
                                    <hr class="my-3">
                                    <div class="font-weight-bold text-primary mb-2" style="font-size: 12.5px;">
                                        <i class="fas fa-plus-circle mr-1"></i> Tambahkan Jenis Ekstrakurikuler Lain ke Siswa Ini:
                                    </div>
                                    @foreach($unassignedActive as $uEkskul)
                                        <div class="form-group row mb-2 align-items-center">
                                            <div class="col-sm-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                           name="new_ekskul_ids[]"
                                                           value="{{ $uEkskul->id }}"
                                                           id="new_ekskul_{{ $item->id }}_{{ $uEkskul->id }}"
                                                           class="custom-control-input"
                                                           onchange="const inp = document.getElementById('new_nom_ekskul_{{ $item->id }}_{{ $uEkskul->id }}'); inp.disabled = !this.checked; if(this.checked) inp.focus();">
                                                    <label class="custom-control-label font-weight-semibold" for="new_ekskul_{{ $item->id }}_{{ $uEkskul->id }}" style="cursor: pointer;">
                                                        {{ $uEkskul->nama }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-light font-weight-bold">Rp</span>
                                                    </div>
                                                    <input type="number"
                                                           name="new_nominals[{{ $uEkskul->id }}]"
                                                           id="new_nom_ekskul_{{ $item->id }}_{{ $uEkskul->id }}"
                                                           class="form-control font-weight-bold text-primary font-num"
                                                           value="{{ (int)$uEkskul->nominal_default }}"
                                                           min="0"
                                                           step="1000"
                                                           disabled>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
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
                                        <div class="text-muted small">Total Target Saat Ini</div>
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
                            <button type="submit" class="btn-simpan-hijau px-4">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS TAGIHAN EKSTRAKURIKULER --}}
        <div class="modal fade" id="modalHapusEkskul{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                            Apakah Anda yakin ingin menghapus tagihan Ekstrakurikuler untuk siswa:
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
                        <form action="{{ route('ekstrakurikuler.destroy', $item->id) }}" method="POST" class="d-inline">
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

    {{-- MODAL TAMBAH TAGIHAN EKSTRAKURIKULER SISWA --}}
    <div class="modal fade" id="modalTambahSiswaEkskul" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-payment">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-book-open mr-2"></i>
                        Tambah Tagihan Ekstrakurikuler Siswa
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('ekstrakurikuler.store') }}" method="POST" id="formModalTambahEkskul">
                    @csrf
                    <div class="modal-body p-4">

                        @if($errors->any())
                            <div class="alert alert-danger mb-3 py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Error:</strong> {{ $errors->first() }}
                            </div>
                        @endif

                        {{-- Pilih Siswa --}}
                        <div class="form-group row mb-3">
                            <label for="modal_ekskul_siswa_id" class="col-sm-3 col-form-label font-weight-bold">
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select name="siswa_id"
                                        id="modal_ekskul_siswa_id"
                                        class="form-control @error('siswa_id') is-invalid @enderror"
                                        style="border-radius: 8px;"
                                        required>
                                    <option value="">-- Pilih Siswa yang Ditagihkan --</option>
                                    @foreach($siswaList as $itemSiswa)
                                        <option value="{{ $itemSiswa->id }}"
                                                data-nis="{{ $itemSiswa->nis }}"
                                                data-nama="{{ $itemSiswa->nama }}"
                                                data-kelas="{{ $itemSiswa->kelas }}"
                                                {{ old('siswa_id') == $itemSiswa->id ? 'selected' : '' }}>
                                            {{ $itemSiswa->nis }} - {{ $itemSiswa->nama }} ({{ $itemSiswa->kelas }})
                                        </option>
                                    @endforeach
                                </select>

                                <div id="modalEkskulSelectedSiswaBox" class="mt-2 p-2 bg-light border rounded small d-none" style="border-radius: 8px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-muted">Nama:</span> <strong id="modalEkskulPreviewNama">-</strong>
                                            <span class="mx-2">|</span>
                                            <span class="text-muted">NIS:</span> <span id="modalEkskulPreviewNis" class="font-weight-bold">-</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-light text-dark border" id="modalEkskulPreviewKelas">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rincian Tagihan Dinamis dari Master Ekstrakurikuler Aktif --}}
                        <div class="border rounded p-3 mb-3 bg-light" style="border-radius: 10px;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="font-weight-bold text-dark" style="font-size: 13.5px;">
                                    <i class="fas fa-list-check text-success mr-1"></i>
                                    Pilih Ekstrakurikuler yang Ditagihkan:
                                </div>
                                <span class="badge badge-info" style="font-size: 11px;">Centang untuk memilih</span>
                            </div>

                            <div class="table-responsive bg-white rounded border">
                                <table class="table table-sm table-hover mb-0" style="font-size: 13px;">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 40px;" class="text-center">Pilih</th>
                                            <th>Nama Ekstrakurikuler</th>
                                            <th style="width: 220px;">Nominal Tagihan (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jenisEkskulAktif as $ekskul)
                                            <tr class="ekskul-item-row">
                                                <td class="text-center align-middle">
                                                    <input type="checkbox"
                                                           name="ekskul_ids[]"
                                                           value="{{ $ekskul->id }}"
                                                           id="ekskul_check_{{ $ekskul->id }}"
                                                           class="ekskul-item-checkbox"
                                                           data-id="{{ $ekskul->id }}"
                                                           data-nama="{{ $ekskul->nama }}"
                                                           data-default="{{ (int)$ekskul->nominal_default }}"
                                                           style="cursor: pointer; width: 17px; height: 17px;">
                                                </td>
                                                <td class="align-middle">
                                                    <label for="ekskul_check_{{ $ekskul->id }}" class="mb-0 font-weight-semibold" style="cursor: pointer;">
                                                        {{ $ekskul->nama }}
                                                    </label>
                                                    @if($ekskul->keterangan)
                                                        <div class="text-muted" style="font-size: 11px;">{{ $ekskul->keterangan }}</div>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light font-weight-bold">Rp</span>
                                                        </div>
                                                        <input type="number"
                                                               name="nominals[{{ $ekskul->id }}]"
                                                               id="nominal_ekskul_input_{{ $ekskul->id }}"
                                                               class="form-control font-weight-bold text-success font-num ekskul-nominal-input"
                                                               value="{{ (int)$ekskul->nominal_default }}"
                                                               min="0"
                                                               step="1000"
                                                               disabled>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    Belum ada ekstrakurikuler aktif di master. Silakan klik tombol <strong>Kelola Ekstrakurikuler</strong> untuk menambahkan jenis baru.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Summary Fee Box --}}
                        <div class="fee-info-box my-3">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Item Terpilih:</div>
                                    <div class="font-weight-bold text-dark small" id="modalEkskulBreakdownLabel">
                                        Belum ada ekstrakurikuler dipilih
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="text-muted small">Total Tagihan Baru</div>
                                    <div class="font-weight-bold text-success font-num" style="font-size: 18px;" id="modalEkskulSummaryTotalLabel">
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
                        <button type="submit" id="btnSubmitTambahEkskul" class="btn-simpan-hijau px-4" disabled>
                            <i class="fas fa-save mr-1"></i>
                            Simpan Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL KELOLA MASTER EKSTRAKURIKULER --}}
    <div class="modal fade" id="modalKelolaJenisEkskul" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-payment">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-sliders-h mr-2"></i>
                        Kelola Master Ekstrakurikuler
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    {{-- Form Tambah Ekstrakurikuler Baru --}}
                    <div class="card border mb-3 shadow-none" style="border-radius: 10px; background: #f8fafc; border-color: #e2e8f0 !important;">
                        <div class="card-body p-3">
                            <h6 class="font-weight-bold text-dark mb-2" style="font-size: 13.5px;">
                                <i class="fas fa-plus-circle text-success mr-1"></i> Tambah Ekstrakurikuler Baru
                            </h6>
                            <form action="{{ route('ekstrakurikuler.jenis.store') }}" method="POST">
                                @csrf
                                <div class="row align-items-end g-2">
                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="font-weight-semibold small mb-1">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Pramuka, Futsal, PMR" style="border-radius: 8px; height: 38px; font-size: 13px;" required>
                                    </div>
                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="font-weight-semibold small mb-1">Nominal Default (Rp)</label>
                                        <input type="number" name="nominal_default" class="form-control font-num" placeholder="0" min="0" step="1000" value="0" style="border-radius: 8px; height: 38px; font-size: 13px;">
                                    </div>
                                    <div class="col-md-4 col-12 mb-2">
                                        <button type="submit" class="btn-simpan-hijau w-100">
                                            <i class="fas fa-save mr-1"></i> Simpan ke Master
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="keterangan" class="form-control" placeholder="Keterangan / catatan singkat (opsional)" style="border-radius: 8px; height: 38px; font-size: 13px;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Master Ekstrakurikuler --}}
                    <div class="table-responsive bg-white rounded border" style="border-color: #e2e8f0 !important;">
                        <table class="table table-sm table-hover mb-0" style="font-size: 12.5px;">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 32px;" class="text-center">No</th>
                                    <th>Nama Ekstrakurikuler</th>
                                    <th class="text-right">Nominal Default</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Terpakai</th>
                                    <th class="text-center" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($daftarJenisEkskul as $idx => $je)
                                    <tr>
                                        <td class="text-center align-middle font-num">{{ $idx + 1 }}</td>
                                        <td class="align-middle">
                                            <strong class="text-dark">{{ $je->nama }}</strong>
                                            @if($je->keterangan)
                                                <div class="text-muted" style="font-size: 11px;">{{ $je->keterangan }}</div>
                                            @endif
                                        </td>
                                        <td class="text-right align-middle font-num font-weight-semibold">
                                            Rp {{ number_format($je->nominal_default, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($je->is_active)
                                                <span class="badge-aktif-pill">
                                                    <i class="fas fa-check-circle" style="font-size: 9px;"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge-nonaktif-pill">
                                                    <i class="fas fa-times-circle" style="font-size: 9px;"></i> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle font-num">
                                            {{ $je->items_count }} siswa
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="act-group">
                                                {{-- Toggle Aktif/Nonaktif --}}
                                                <form action="{{ route('ekstrakurikuler.jenis.toggle', $je->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="{{ $je->is_active ? 'btn-act-toggle-off' : 'btn-act-toggle-on' }}" title="{{ $je->is_active ? 'Nonaktifkan Ekstrakurikuler' : 'Aktifkan Ekstrakurikuler' }}">
                                                        <i class="fas {{ $je->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>

                                                {{-- Tombol Edit --}}
                                                <button type="button" class="btn-act-edit" data-toggle="collapse" data-target="#editJeRow{{ $je->id }}" title="Edit Data Ekstrakurikuler">
                                                    <i class="fas fa-pen"></i>
                                                </button>

                                                {{-- Hapus (dilindungi jika sudah terpakai) --}}
                                                <form action="{{ route('ekstrakurikuler.jenis.destroy', $je->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ekstrakurikuler ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-act-hapus" title="Hapus Ekstrakurikuler">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- Collapse Edit Form --}}
                                    <tr class="collapse bg-light" id="editJeRow{{ $je->id }}">
                                        <td colspan="6" class="p-3" style="background: #f8fafc; border-top: 1px dashed #cbd5e1; border-bottom: 1px dashed #cbd5e1;">
                                            <form action="{{ route('ekstrakurikuler.jenis.update', $je->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-md-4 col-12 mb-1 mb-md-0">
                                                        <input type="text" name="nama" class="form-control" value="{{ $je->nama }}" placeholder="Nama Ekstrakurikuler" style="border-radius: 8px; height: 34px; font-size: 12.5px;" required>
                                                    </div>
                                                    <div class="col-md-3 col-12 mb-1 mb-md-0">
                                                        <input type="number" name="nominal_default" class="form-control font-num" value="{{ (int)$je->nominal_default }}" min="0" step="1000" placeholder="Nominal Default" style="border-radius: 8px; height: 34px; font-size: 12.5px;">
                                                    </div>
                                                    <div class="col-md-3 col-12 mb-1 mb-md-0">
                                                        <input type="text" name="keterangan" class="form-control" value="{{ $je->keterangan }}" placeholder="Keterangan singkat" style="border-radius: 8px; height: 34px; font-size: 12.5px;">
                                                    </div>
                                                    <div class="col-md-2 col-12 d-flex gap-1">
                                                        <button type="submit" class="btn-simpan-hijau px-3" style="height: 34px; font-size: 12px; padding: 4px 12px !important;">
                                                            <i class="fas fa-save mr-1"></i> Simpan
                                                        </button>
                                                        <button type="button" class="btn-batal-merah px-3" style="height: 34px; font-size: 12px; padding: 4px 12px !important;" data-toggle="collapse" data-target="#editJeRow{{ $je->id }}">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3 text-muted">Belum ada master ekstrakurikuler.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-4 justify-content-end" style="border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-tutup-merah px-4" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TERAPKAN MASSAL --}}
    <div class="modal fade" id="modalTerapkanMassalEkskul" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-payment">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-users-cog mr-2"></i>
                        <span id="labelHeaderTerapkanMassalEkskul">Terapkan Tagihan Massal ke Semua Siswa</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTerapkanMassalEkskul" action="{{ route('ekstrakurikuler.terapkan-massal') }}" method="POST">
                    @csrf

                    {{-- STEP 1: FORM INPUT --}}
                    <div id="stepMassalFormInputEkskul">
                        <div class="modal-body p-4">
                            <div class="alert alert-info py-2 px-3 mb-3 small" style="border-radius: 8px;">
                                <i class="fas fa-info-circle mr-1"></i>
                                Fitur ini akan membuat tagihan untuk <strong>seluruh siswa</strong> pada tahun ajaran <strong>{{ $selectedTa->nama ?? 'Aktif' }}</strong>. Siswa yang sudah memiliki tagihan ini akan <strong>dilewati secara otomatis</strong>.
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Pilih Ekstrakurikuler <span class="text-danger">*</span></label>
                                <select name="jenis_ekskul_id" id="massal_jenis_ekskul_id" class="form-control" style="border-radius: 8px; height: 38px; font-size: 13px;" required onchange="if(this.options[this.selectedIndex].dataset.default) document.getElementById('massal_nominal_ekskul').value = this.options[this.selectedIndex].dataset.default;">
                                    <option value="">-- Pilih Ekstrakurikuler --</option>
                                    @foreach($jenisEkskulAktif as $je)
                                        <option value="{{ $je->id }}" data-default="{{ (int)$je->nominal_default }}">
                                            {{ $je->nama }} (Default: Rp {{ number_format($je->nominal_default, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Nominal Tagihan untuk Semua Siswa (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light" style="border-radius: 8px 0 0 8px;">Rp</span>
                                    </div>
                                    <input type="number" name="nominal" id="massal_nominal_ekskul" class="form-control font-weight-bold font-num text-success" placeholder="0" min="0" step="any" style="border-radius: 0 8px 8px 0; height: 38px; font-size: 14px;" required>
                                </div>
                                <small class="text-muted">Nominal dapat disesuaikan sebelum diterapkan ke seluruh siswa.</small>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2 px-4 justify-content-end" style="border-top: 1px solid #e2e8f0;">
                            <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="button" id="btnTriggerKonfirmasiMassalEkskul" class="btn-simpan-hijau px-4">
                                Terapkan
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2: KONFIRMASI RINGKASAN (DALAM MODAL YANG SAMA) --}}
                    <div id="stepMassalKonfirmasiEkskul" style="display: none;">
                        <div class="modal-body p-4">
                            <p class="mb-3 text-dark font-weight-500" style="font-size: 14px;">
                                Apakah Anda yakin ingin menerapkan tagihan ini secara massal ke seluruh siswa aktif?
                            </p>

                            <div class="p-3 bg-light border rounded mb-0" style="border-radius: 8px;">
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 14px;">
                                    <span class="text-muted">Ekstrakurikuler:</span>
                                    <span class="font-weight-bold text-dark" id="konfirmasiMassalJenisEkskul">-</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 14px;">
                                    <span class="text-muted">Nominal Tagihan:</span>
                                    <span class="font-weight-bold text-success" id="konfirmasiMassalNominalEkskul">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2" style="font-size: 14px;">
                                    <span class="text-muted">Tahun Ajaran:</span>
                                    <span class="font-weight-bold text-dark">{{ $selectedTa->nama ?? 'Aktif' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2 px-4 justify-content-between" style="border-top: 1px solid #e2e8f0;">
                            <button type="button" class="btn btn-batal-merah px-4" id="btnBackToFormMassalEkskul" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-arrow-left mr-1"></i> Ubah
                            </button>
                            <button type="button" id="btnEksekusiTerapkanMassalEkskul" class="btn-simpan-hijau px-4">
                                Ya, Terapkan Sekarang
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Live Search
    const searchInput = document.getElementById('ekskulSearchInput');
    function updateNumber() {
        const rows = Array.from(document.querySelectorAll('#ekskulTable tbody tr[data-nis]'));
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
            const val = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#ekskulTable tbody tr[data-nis]');
            rows.forEach(function (row) {
                const nis = (row.getAttribute('data-nis') || '').toLowerCase();
                const nama = (row.getAttribute('data-nama') || '').toLowerCase();
                if (nis.includes(val) || nama.includes(val)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            updateNumber();
        });
    }

    // 2. Select kategori pada modal bayar -> otomatis isi sisa item
    document.querySelectorAll('.select-kategori-ekstrakurikuler').forEach(function (select) {
        select.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const sisa = opt.getAttribute('data-sisa');
            const form = this.closest('form');
            const inputNominal = form.querySelector('.input-nominal-ekstrakurikuler');
            if (sisa !== null && inputNominal) {
                inputNominal.value = sisa;
                inputNominal.setAttribute('max', sisa);
            }
        });
    });

    // 3. Terapkan massal auto-fill nominal
    const selectMassal = document.getElementById('massal_jenis_ekskul_id');
    const inputMassal = document.getElementById('massal_nominal_ekskul');
    if (selectMassal && inputMassal) {
        selectMassal.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const nom = opt.getAttribute('data-nominal');
            if (nom !== null) {
                inputMassal.value = nom;
            }
        });
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // 4. Handler Modal Tambah Tagihan Ekstrakurikuler Dinamis
    const modalEkskulSiswaSelect = document.getElementById('modal_ekskul_siswa_id');
    const modalEkskulSelectedSiswaBox = document.getElementById('modalEkskulSelectedSiswaBox');
    const modalEkskulPreviewNama = document.getElementById('modalEkskulPreviewNama');
    const modalEkskulPreviewNis = document.getElementById('modalEkskulPreviewNis');
    const modalEkskulPreviewKelas = document.getElementById('modalEkskulPreviewKelas');
    const modalEkskulSummaryTotalLabel = document.getElementById('modalEkskulSummaryTotalLabel');
    const modalEkskulBreakdownLabel = document.getElementById('modalEkskulBreakdownLabel');
    const modalEkskulSubmitBtn = document.getElementById('btnSubmitTambahEkskul');

    function calculateEkskulTambahTotal() {
        let total = 0;
        let selectedNames = [];

        document.querySelectorAll('.ekskul-item-checkbox:checked').forEach(function (chk) {
            const id = chk.dataset.id;
            const nama = chk.dataset.nama || '';
            const inp = document.getElementById('nominal_ekskul_input_' + id);
            const val = parseFloat(inp ? inp.value : 0) || 0;
            total += val;
            selectedNames.push(`${nama} (Rp ${formatRupiah(val)})`);
        });

        if (modalEkskulSummaryTotalLabel) {
            modalEkskulSummaryTotalLabel.textContent = `Rp ${formatRupiah(total)}`;
        }
        if (modalEkskulBreakdownLabel) {
            modalEkskulBreakdownLabel.textContent = selectedNames.length > 0 
                ? selectedNames.join(' + ') 
                : 'Belum ada ekstrakurikuler dipilih';
        }

        if (modalEkskulSubmitBtn) {
            modalEkskulSubmitBtn.disabled = !(modalEkskulSiswaSelect && modalEkskulSiswaSelect.value && selectedNames.length > 0);
        }
    }

    document.querySelectorAll('.ekskul-item-checkbox').forEach(function (chk) {
        chk.addEventListener('change', function () {
            const id = this.dataset.id;
            const inp = document.getElementById('nominal_ekskul_input_' + id);
            if (inp) {
                inp.disabled = !this.checked;
                if (this.checked && (!inp.value || inp.value == '0')) {
                    inp.value = this.dataset.default || 0;
                }
            }
            calculateEkskulTambahTotal();
        });
    });

    document.querySelectorAll('.ekskul-nominal-input').forEach(function (inp) {
        inp.addEventListener('input', calculateEkskulTambahTotal);
    });

    if (modalEkskulSiswaSelect) {
        modalEkskulSiswaSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                modalEkskulPreviewNama.textContent = opt.dataset.nama || '-';
                modalEkskulPreviewNis.textContent = opt.dataset.nis || '-';
                modalEkskulPreviewKelas.textContent = opt.dataset.kelas || '-';
                modalEkskulSelectedSiswaBox.classList.remove('d-none');
            } else {
                modalEkskulSelectedSiswaBox.classList.add('d-none');
            }
            calculateEkskulTambahTotal();
        });
    }

    // 5. Live Handler Modal Edit Ekstrakurikuler
    function updateEditEkskulTotal(itemId) {
        let sum = 0;
        document.querySelectorAll(`.ekskul-edit-item-input[data-item-id="${itemId}"]`).forEach(function (i) {
            sum += parseFloat(i.value || 0);
        });
        const modal = document.getElementById('modalEditEkskul' + itemId);
        if (modal) {
            modal.querySelectorAll('input[name="new_ekskul_ids[]"]:checked').forEach(function (chk) {
                const row = chk.closest('.form-group');
                if (row) {
                    const inp = row.querySelector('input[name^="new_nominals"]');
                    if (inp) sum += parseFloat(inp.value || 0);
                }
            });
        }
        const lbl = document.getElementById('modalEditSummaryTotalLabel_' + itemId);
        if (lbl) {
            lbl.textContent = `Rp ${formatRupiah(sum)}`;
        }
    }

    document.querySelectorAll('.ekskul-edit-item-input').forEach(function (inp) {
        inp.addEventListener('input', function () {
            updateEditEkskulTotal(this.dataset.itemId);
        });
    });

    document.querySelectorAll('input[name="new_ekskul_ids[]"]').forEach(function (chk) {
        chk.addEventListener('change', function () {
            const form = this.closest('form');
            if (form) {
                const idMatch = form.id.match(/\d+$/);
                if (idMatch) updateEditEkskulTotal(idMatch[0]);
            }
        });
    });

    document.querySelectorAll('input[name^="new_nominals"]').forEach(function (inp) {
        inp.addEventListener('input', function () {
            const form = this.closest('form');
            if (form) {
                const idMatch = form.id.match(/\d+$/);
                if (idMatch) updateEditEkskulTotal(idMatch[0]);
            }
        });
    });

    // 6. Modal Terapkan Massal Ekskul - Alur Bebas Stuck (Single Modal Step-Transition)
    const modalTerapkanMassalEkskul = $('#modalTerapkanMassalEkskul');
    const stepMassalFormInputEkskul = document.getElementById('stepMassalFormInputEkskul');
    const stepMassalKonfirmasiEkskul = document.getElementById('stepMassalKonfirmasiEkskul');
    const labelHeaderTerapkanMassalEkskul = document.getElementById('labelHeaderTerapkanMassalEkskul');
    const btnTriggerKonfirmasiMassalEkskul = document.getElementById('btnTriggerKonfirmasiMassalEkskul');
    const btnBackToFormMassalEkskul = document.getElementById('btnBackToFormMassalEkskul');
    const btnEksekusiTerapkanMassalEkskul = document.getElementById('btnEksekusiTerapkanMassalEkskul');
    const formTerapkanMassalEkskul = document.getElementById('formTerapkanMassalEkskul');

    function switchStepMassalEkskul(step) {
        if (step === 'confirm') {
            if (stepMassalFormInputEkskul) stepMassalFormInputEkskul.style.display = 'none';
            if (stepMassalKonfirmasiEkskul) stepMassalKonfirmasiEkskul.style.display = 'block';
            if (labelHeaderTerapkanMassalEkskul) labelHeaderTerapkanMassalEkskul.textContent = 'Konfirmasi Penerapan Tagihan Massal';
        } else {
            if (stepMassalKonfirmasiEkskul) stepMassalKonfirmasiEkskul.style.display = 'none';
            if (stepMassalFormInputEkskul) stepMassalFormInputEkskul.style.display = 'block';
            if (labelHeaderTerapkanMassalEkskul) labelHeaderTerapkanMassalEkskul.textContent = 'Terapkan Tagihan Massal ke Semua Siswa';
        }
    }

    if (btnTriggerKonfirmasiMassalEkskul && formTerapkanMassalEkskul) {
        btnTriggerKonfirmasiMassalEkskul.addEventListener('click', function () {
            if (!formTerapkanMassalEkskul.checkValidity()) {
                formTerapkanMassalEkskul.reportValidity();
                return;
            }

            const selectJe = document.getElementById('massal_jenis_ekskul_id');
            const inputNominal = document.getElementById('massal_nominal_ekskul');
            const rawText = selectJe.options[selectJe.selectedIndex]?.text || '';
            const namaJe = rawText.includes('(Default') ? rawText.split('(Default')[0].trim() : (rawText.includes('(Standar') ? rawText.split('(Standar')[0].trim() : rawText.trim());
            const valNominal = parseFloat(inputNominal.value) || 0;

            const elemJe = document.getElementById('konfirmasiMassalJenisEkskul');
            const elemNom = document.getElementById('konfirmasiMassalNominalEkskul');

            if (elemJe) elemJe.textContent = namaJe || '-';
            if (elemNom) elemNom.textContent = 'Rp ' + formatRupiah(valNominal);

            switchStepMassalEkskul('confirm');
        });
    }

    if (btnBackToFormMassalEkskul) {
        btnBackToFormMassalEkskul.addEventListener('click', function () {
            switchStepMassalEkskul('input');
        });
    }

    if (btnEksekusiTerapkanMassalEkskul && formTerapkanMassalEkskul) {
        btnEksekusiTerapkanMassalEkskul.addEventListener('click', function () {
            btnEksekusiTerapkanMassalEkskul.disabled = true;
            btnEksekusiTerapkanMassalEkskul.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menerapkan...';
            formTerapkanMassalEkskul.submit();
        });
    }

    modalTerapkanMassalEkskul.on('hidden.bs.modal', function () {
        switchStepMassalEkskul('input');
    });
});
</script>
@stop
