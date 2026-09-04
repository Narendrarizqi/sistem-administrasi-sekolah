@extends('adminlte::page')

@section('title', 'Bantuan Operasional Sekolah (BOS)')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: HALAMAN BANTUAN OPERASIONAL SEKOLAH (BOS)
           =============================================================== */

        /* 1. Header Box */
        .bos-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 18px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .bos-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 3px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bos-title-icon {
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

        .bos-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        .bos-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .bos-select-tahun {
            height: 38px;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            font-size: 13px;
            font-weight: 600;
            outline: none;
            cursor: pointer;
            transition: border-color 0.15s ease;
        }

        .bos-select-tahun:focus {
            border-color: #16a34a;
        }

        .btn-cetak-bos {
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
        }

        .btn-cetak-bos:hover {
            background: #15803d;
            border-color: #15803d;
            transform: translateY(-1px);
        }

        /* 2. Stat Cards Grid (5 Cards) */
        .stat-card-clean {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
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
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .icon-circle-emerald {
            background: #16a34a;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
        }

        .icon-circle-indigo {
            background: #6366f1;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.25);
        }

        .icon-circle-blue {
            background: #0284c7;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
        }

        .icon-circle-red {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25);
        }

        .icon-circle-teal {
            background: #0d9488;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(13, 148, 136, 0.25);
        }

        .stat-card-info {
            flex: 1;
            min-width: 0;
        }

        .stat-card-label {
            font-size: 11.5px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 2px;
        }

        .stat-card-value {
            font-size: 17px;
            font-weight: 800;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #0f172a !important;
        }

        .stat-card-sub {
            font-size: 11.5px;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 3. Main Content Cards */
        .bos-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .bos-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .bos-card-title {
            font-size: 15.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-tambah-hijau {
            height: 36px;
            background: #16a34a;
            border: 1px solid #16a34a;
            color: #ffffff !important;
            font-size: 12.5px;
            font-weight: 600;
            padding: 0 14px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-tambah-hijau:hover {
            background: #15803d;
            border-color: #15803d;
            transform: translateY(-1px);
        }

        .btn-tambah-biru {
            height: 36px;
            background: #0284c7;
            border: 1px solid #0284c7;
            color: #ffffff !important;
            font-size: 12.5px;
            font-weight: 600;
            padding: 0 14px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-tambah-biru:hover {
            background: #0369a1;
            border-color: #0369a1;
            transform: translateY(-1px);
        }

        /* 4. Table Styles */
        .table-bos {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }

        .table-bos thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 10px 14px;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-bos tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #1e293b;
        }

        .table-bos tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-bos tfoot th {
            background: #f8fafc;
            color: #0f172a;
            font-size: 13px;
            font-weight: 800;
            padding: 12px 14px;
            border-top: 2px solid #cbd5e1;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Badges */
        .badge-tahap-1 {
            background: #eef2ff !important;
            border: 1px solid #c7d2fe !important;
            color: #4338ca !important;
            font-size: 11.5px !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
            padding: 3px 8px !important;
        }

        .badge-tahap-2 {
            background: #f0f9ff !important;
            border: 1px solid #bae6fd !important;
            color: #0369a1 !important;
            font-size: 11.5px !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
            padding: 3px 8px !important;
        }

        .badge-status-ada {
            background: #ecfdf5 !important;
            border: 1px solid #a7f3d0 !important;
            color: #047857 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 8px !important;
        }

        .badge-status-kosong {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #94a3b8 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2.5px 8px !important;
        }

        /* Action Buttons (Standar Seragam Halaman Siswa) */
        .bos-action-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            white-space: nowrap !important;
        }

        .btn-act,
        .btn-act-edit,
        .btn-act-hapus {
            width: 26px;
            height: 26px;
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

        /* Tombol Input Tahap 1 & 2 (Hijau Premium) */
        .btn-input-tahap {
            background-color: #16a34a !important;
            border: 1px solid #16a34a !important;
            color: #ffffff !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            border-radius: 7px !important;
            padding: 5px 12px !important;
            height: 32px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.25) !important;
            transition: all 0.15s ease !important;
            cursor: pointer !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            width: auto !important;
            line-height: 1 !important;
        }

        .btn-input-tahap:hover {
            background-color: #15803d !important;
            border-color: #15803d !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.35) !important;
        }

        .btn-input-tahap i {
            font-size: 11px !important;
            color: #ffffff !important;
        }

        .btn-input-tahap span {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        /* Modal Styles */
        .modal-header-clean {
            background: #16a34a !important;
            color: #ffffff !important;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
            padding: 16px 20px;
            border-bottom: none !important;
        }

        .modal-header-clean .modal-title,
        .modal-header-clean .modal-title * {
            color: #ffffff !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .modal-header-danger {
            background: #ef4444 !important;
            color: #ffffff !important;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
            padding: 16px 20px;
            border-bottom: none !important;
        }

        .modal-header-danger .modal-title,
        .modal-header-danger .modal-title * {
            color: #ffffff !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }
    </style>
@stop

@section('content')

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" style="border-radius: 10px;" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" style="border-radius: 10px;" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Terjadi kesalahan input:</strong>
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

    {{-- 1. HEADER BOX DENGAN FILTER TAHUN & CETAK PDF --}}
    <div class="bos-page-header">
        <div>
            <h1 class="bos-title">
                <span class="bos-title-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </span>
                Bantuan Operasional Sekolah (BOS)
            </h1>
            <p class="bos-desc">
                Pencatatan Pengambilan Dana BOS dan Realisasi Pengeluaran &bull; Tahun Anggaran {{ $tahunAnggaran }}
            </p>
        </div>

        <div class="bos-header-actions">
            {{-- Filter Tahun Anggaran --}}
            <form method="GET" action="{{ route('bos.index') }}" class="d-inline-flex align-items-center">
                <select name="tahun_anggaran" class="bos-select-tahun" onchange="this.form.submit()">
                    @foreach($daftarTahunAnggaran as $thn)
                        <option value="{{ $thn }}" {{ (string)$thn === (string)$tahunAnggaran ? 'selected' : '' }}>
                            Tahun Anggaran {{ $thn }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('bos.cetak', ['tahun_anggaran' => $tahunAnggaran]) }}" target="_blank" class="btn-cetak-bos">
                <i class="fas fa-print"></i>
                <span>Cetak Laporan</span>
            </a>
        </div>
    </div>

    {{-- 2. STAT CARDS: RINGKASAN & RINCIAN DANA BOS --}}
    <div class="row g-3 mb-3">
        {{-- Card 1: Total Pengambilan Dana BOS --}}
        <div class="col-12 col-md-4 mb-3">
            <div class="stat-card-clean">
                <div class="stat-card-icon-circle icon-circle-emerald">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Pengambilan Dana BOS</div>
                    <div class="stat-card-value font-num" style="color: #0f172a;">
                        Rp {{ number_format($totalPengambilanBos, 0, ',', '.') }}
                    </div>
                    <div class="stat-card-sub text-muted">Tahap 1 + Tahap 2</div>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Pengeluaran BOS --}}
        <div class="col-12 col-md-4 mb-3">
            <div class="stat-card-clean">
                <div class="stat-card-icon-circle icon-circle-red">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Total Pengeluaran BOS</div>
                    <div class="stat-card-value font-num" style="color: #0f172a;">
                        Rp {{ number_format($totalPengeluaranBos, 0, ',', '.') }}
                    </div>
                    <div class="stat-card-sub text-muted">{{ $pengeluaranBos->total() }} Transaksi Pengeluaran</div>
                </div>
            </div>
        </div>

        {{-- Card 3: Sisa Saldo Kas BOS --}}
        <div class="col-12 col-md-4 mb-3">
            <div class="stat-card-clean">
                <div class="stat-card-icon-circle icon-circle-teal">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-card-info">
                    <div class="stat-card-label">Sisa Saldo Kas BOS</div>
                    <div class="stat-card-value font-num" style="color: #0f172a;">
                        Rp {{ number_format($sisaSaldoBos, 0, ',', '.') }}
                    </div>
                    <div class="stat-card-sub text-muted">
                        {{ $sisaSaldoBos >= 0 ? 'Tersedia di Kas Sekolah' : 'Defisit' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. CARD 1: TABEL DETAIL PENGAMBILAN DANA BOS --}}
    <div class="bos-card">
        <div class="bos-card-header">
            <h5 class="bos-card-title">
                <i class="fas fa-hand-holding-usd text-primary mr-1"></i>
                Detail Pengambilan Dana BOS (Tahun {{ $tahunAnggaran }})
            </h5>

            <button type="button" class="btn-tambah-hijau" data-toggle="modal" data-target="#modalTambahPengambilanBos">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengambilan Dana BOS</span>
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bos" id="tablePengambilanBos">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th style="width: 100px;" class="text-center">Tahap</th>
                        <th>Keterangan</th>
                        <th style="width: 180px;" class="text-right">Nominal Ditarik (Rp)</th>
                        <th style="width: 90px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($pengambilanTahap1->isEmpty() && $pengambilanTahap2->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-folder-open fa-2x mb-2 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark mb-1">Belum ada transaksi pengambilan dana BOS di Tahun {{ $tahunAnggaran }}</h6>
                                <p class="text-muted small mb-0">Klik tombol "+ Tambah Pengambilan Dana BOS" untuk mencatat penarikan dana dari rekening BOS.</p>
                            </td>
                        </tr>
                    @else
                        {{-- GRUP TAHAP 1 --}}
                        <tr style="background-color: #f5f7ff; border-top: 2px solid #e0e7ff; border-bottom: 1px solid #c7d2fe;">
                            <td colspan="6" class="py-2 px-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="badge badge-tahap-1 mr-2"><i class="fas fa-layer-group mr-1"></i>Tahap 1</span>
                                        <strong class="text-dark" style="font-size: 13px;">Daftar Transaksi Pengambilan Tahap 1</strong>
                                    </div>
                                    <span class="text-muted small"><strong>{{ $pengambilanTahap1->count() }}</strong> transaksi</span>
                                </div>
                            </td>
                        </tr>
                        @forelse($pengambilanTahap1 as $item)
                            <tr>
                                <td class="text-center font-num text-muted">{{ $loop->iteration }}</td>
                                <td class="font-num">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                <td class="text-center"><span class="badge badge-tahap-1">Tahap 1</span></td>
                                <td><div class="font-weight-600 text-dark">{{ $item->keterangan ?: 'Pencairan Dana BOS Tahap 1' }}</div></td>
                                <td class="text-right font-weight-bold font-num" style="color: #4338ca; font-size: 14px;">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="bos-action-group">
                                        <button type="button" class="btn-act-edit" title="Edit Transaksi" data-toggle="modal" data-target="#modalEditPengambilan{{ $item->id }}">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn-act-hapus" title="Hapus Transaksi" data-toggle="modal" data-target="#modalHapusPengambilan{{ $item->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-2 small font-italic bg-white">
                                    Belum ada pengambilan dana pada Tahap 1
                                </td>
                            </tr>
                        @endforelse
                        <tr style="background: #f8fafc; font-weight: bold; border-top: 1px solid #e2e8f0; border-bottom: 2px solid #cbd5e1;">
                            <td colspan="4" class="text-right text-uppercase" style="font-size: 12px; color: #4338ca; letter-spacing: 0.02em;">
                                <i class="fas fa-calculator mr-1"></i> Subtotal Pengambilan Tahap 1
                            </td>
                            <td class="text-right font-num font-weight-bold" style="font-size: 14.5px; color: #4338ca;">
                                Rp {{ number_format($subtotalTahap1, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>

                        {{-- GRUP TAHAP 2 --}}
                        <tr style="background-color: #f0f9ff; border-top: 2px solid #e0f2fe; border-bottom: 1px solid #bae6fd;">
                            <td colspan="6" class="py-2 px-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="badge badge-tahap-2 mr-2"><i class="fas fa-layer-group mr-1"></i>Tahap 2</span>
                                        <strong class="text-dark" style="font-size: 13px;">Daftar Transaksi Pengambilan Tahap 2</strong>
                                    </div>
                                    <span class="text-muted small"><strong>{{ $pengambilanTahap2->count() }}</strong> transaksi</span>
                                </div>
                            </td>
                        </tr>
                        @forelse($pengambilanTahap2 as $item)
                            <tr>
                                <td class="text-center font-num text-muted">{{ $loop->iteration }}</td>
                                <td class="font-num">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                <td class="text-center"><span class="badge badge-tahap-2">Tahap 2</span></td>
                                <td><div class="font-weight-600 text-dark">{{ $item->keterangan ?: 'Pencairan Dana BOS Tahap 2' }}</div></td>
                                <td class="text-right font-weight-bold font-num" style="color: #0284c7; font-size: 14px;">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="bos-action-group">
                                        <button type="button" class="btn-act-edit" title="Edit Transaksi" data-toggle="modal" data-target="#modalEditPengambilan{{ $item->id }}">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn-act-hapus" title="Hapus Transaksi" data-toggle="modal" data-target="#modalHapusPengambilan{{ $item->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-2 small font-italic bg-white">
                                    Belum ada pengambilan dana pada Tahap 2
                                </td>
                            </tr>
                        @endforelse
                        <tr style="background: #f8fafc; font-weight: bold; border-top: 1px solid #e2e8f0; border-bottom: 2px solid #cbd5e1;">
                            <td colspan="4" class="text-right text-uppercase" style="font-size: 12px; color: #0284c7; letter-spacing: 0.02em;">
                                <i class="fas fa-calculator mr-1"></i> Subtotal Pengambilan Tahap 2
                            </td>
                            <td class="text-right font-num font-weight-bold" style="font-size: 14.5px; color: #0284c7;">
                                Rp {{ number_format($subtotalTahap2, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr style="background: #f1f5f9;">
                        <th colspan="4" class="text-uppercase font-weight-bold">Total Pengambilan Dana BOS (Tahap 1 + Tahap 2)</th>
                        <th class="text-right font-num font-weight-bold text-success" style="font-size: 15.5px;">
                            Rp {{ number_format($totalPengambilanBos, 0, ',', '.') }}
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- 4. CARD 2: TABEL RIWAYAT PENGELUARAN DARI DANA BOS --}}
    <div class="bos-card">
        <div class="bos-card-header">
            <h5 class="bos-card-title">
                <i class="fas fa-receipt text-danger mr-1"></i>
                Riwayat Pengeluaran dari Dana BOS (Tahun {{ $tahunAnggaran }})
            </h5>

            <button type="button" class="btn-tambah-hijau" data-toggle="modal" data-target="#modalTambahPengeluaranBos">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengeluaran BOS</span>
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bos" id="tablePengeluaranBos">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th>Keterangan / Keperluan Pengeluaran</th>
                        <th style="width: 140px;">Dicatat Oleh</th>
                        <th style="width: 180px;" class="text-right">Nominal Keluar (Rp)</th>
                        <th style="width: 90px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluaranBos as $item)
                        <tr>
                            <td class="text-center font-num text-muted">{{ method_exists($pengeluaranBos, 'firstItem') && $pengeluaranBos->firstItem() ? ($pengeluaranBos->firstItem() + $loop->index) : $loop->iteration }}</td>
                            <td class="font-num">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </td>
                            <td>
                                <div class="font-weight-600 text-dark">{{ $item->keterangan }}</div>
                            </td>
                            <td>
                                @if($item->user)
                                    <span class="badge badge-light border text-dark font-weight-normal py-1 px-2" style="border-radius: 6px;">
                                        <i class="fas fa-user-circle text-primary mr-1"></i>{{ $item->user->name }}
                                    </span>
                                @else
                                    <span class="text-muted small">&ndash;</span>
                                @endif
                            </td>
                            <td class="text-right font-weight-bold font-num text-danger" style="font-size: 14px;">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <div class="bos-action-group">
                                    <button type="button"
                                            class="btn-act-edit"
                                            title="Edit Pengeluaran"
                                            data-toggle="modal"
                                            data-target="#modalEditPengeluaran{{ $item->id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button type="button"
                                            class="btn-act-hapus"
                                            title="Hapus Pengeluaran"
                                            data-toggle="modal"
                                            data-target="#modalHapusPengeluaran{{ $item->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-folder-open fa-2x mb-2 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark mb-1">Belum ada pengeluaran dari Dana BOS</h6>
                                <p class="text-muted small mb-0">Klik tombol "+ Tambah Pengeluaran BOS" untuk mencatat pengeluaran baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($pengeluaranBos->isNotEmpty())
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-uppercase">Total Pengeluaran Dana BOS</th>
                            <th class="text-right font-num text-danger" style="font-size: 15px;">
                                Rp {{ number_format($totalPengeluaranBos, 0, ',', '.') }}
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Pagination Links (25 per halaman) --}}
        @if(method_exists($pengeluaranBos, 'hasPages') && ($pengeluaranBos->hasPages() || $pengeluaranBos->total() > 0))
            <div class="table-pagination-container px-3 pb-3">
                <div class="table-pagination-info">
                    Menampilkan <strong>{{ $pengeluaranBos->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $pengeluaranBos->lastItem() ?? 0 }}</strong> dari <strong>{{ $pengeluaranBos->total() }}</strong> transaksi
                </div>
                <div class="table-pagination-links">
                    {{ $pengeluaranBos->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>


    {{-- ===============================================================
         MODALS UNTUK PENGAMBILAN DANA BOS
         =============================================================== --}}

    {{-- MODAL TAMBAH PENGAMBILAN DANA BOS --}}
    <div class="modal fade" id="modalTambahPengambilanBos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-clean">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        Tambah Pengambilan Dana BOS
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('bos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun_anggaran" value="{{ $tahunAnggaran }}">

                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">
                                    Tahun Anggaran
                                </label>
                                <input type="text" class="form-control bg-light font-weight-bold" value="{{ $tahunAnggaran }}" readonly style="border-radius: 8px;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">
                                    Tahap BOS <span class="text-danger">*</span>
                                </label>
                                <select name="tahap" class="form-control font-weight-bold" style="border-radius: 8px;" required>
                                    <option value="Tahap 1" selected>Tahap 1</option>
                                    <option value="Tahap 2">Tahap 2</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">
                                Tanggal Pengambilan <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="tanggal"
                                   class="form-control"
                                   style="border-radius: 8px;"
                                   value="{{ date('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">
                                Nominal Pengambilan (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                </div>
                                <input type="number"
                                       name="nominal"
                                       class="form-control font-weight-bold text-success font-num"
                                       placeholder="Contoh: 20000000"
                                       min="1"
                                       step="1"
                                       style="border-radius: 0 8px 8px 0;"
                                       required>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold">
                                Keterangan / Keperluan Penarikan
                            </label>
                            <textarea name="keterangan"
                                      rows="2"
                                      class="form-control"
                                      style="border-radius: 8px;"
                                      placeholder="Contoh: Penarikan tunai dari rekening BOS untuk operasional sekolah..."
                                      maxlength="500"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Pengambilan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT & HAPUS PENGAMBILAN DANA BOS --}}
    @foreach($pengambilanTahap1->concat($pengambilanTahap2) as $item)
        {{-- MODAL EDIT --}}
        <div class="modal fade" id="modalEditPengambilan{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-clean">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Pengambilan Dana BOS
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('bos.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">
                                        Tahun Anggaran
                                    </label>
                                    <input type="text" class="form-control bg-light font-weight-bold" value="{{ $item->tahun_anggaran }}" readonly style="border-radius: 8px;">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">
                                        Tahap BOS <span class="text-danger">*</span>
                                    </label>
                                    <select name="tahap" class="form-control font-weight-bold" style="border-radius: 8px;" required>
                                        <option value="Tahap 1" {{ $item->tahap === 'Tahap 1' ? 'selected' : '' }}>Tahap 1</option>
                                        <option value="Tahap 2" {{ $item->tahap === 'Tahap 2' ? 'selected' : '' }}>Tahap 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">
                                    Tanggal Pengambilan <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="tanggal"
                                       class="form-control"
                                       style="border-radius: 8px;"
                                       value="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label font-weight-bold">
                                    Nominal Pengambilan (Rp) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                    </div>
                                    <input type="number"
                                           name="nominal"
                                           class="form-control font-weight-bold text-success font-num"
                                           min="1"
                                           step="1"
                                           value="{{ (int)$item->nominal }}"
                                           style="border-radius: 0 8px 8px 0;"
                                           required>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label font-weight-bold">
                                    Keterangan / Catatan
                                </label>
                                <textarea name="keterangan"
                                          rows="2"
                                          class="form-control"
                                          style="border-radius: 8px;"
                                          maxlength="500">{{ $item->keterangan }}</textarea>
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
        <div class="modal fade" id="modalHapusPengambilan{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-danger">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Konfirmasi Hapus Pengambilan Dana BOS
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-2 text-dark">
                            Apakah Anda yakin ingin menghapus transaksi pengambilan <strong>BOS {{ $item->tahap }}</strong> sebesar:
                        </p>
                        <div class="p-3 bg-light border rounded text-center mb-3" style="border-radius: 10px;">
                            <span class="text-success font-weight-bold font-num" style="font-size: 18px;">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </span>
                            <div class="text-muted small mt-1">
                                Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }} &bull; {{ $item->keterangan ?: 'Tanpa keterangan' }}
                            </div>
                        </div>
                        <div class="alert alert-danger mb-0 small" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Tindakan ini tidak dapat dibatalkan. Total tahap dan saldo akan otomatis dihitung ulang.
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <form action="{{ route('bos.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary px-4 mr-2" data-dismiss="modal" style="border-radius: 8px; height: 38px;">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-trash-alt mr-1"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    {{-- ===============================================================
         MODALS UNTUK PENGELUARAN DANA BOS
         =============================================================== --}}

    {{-- MODAL TAMBAH PENGELUARAN BOS --}}
    <div class="modal fade" id="modalTambahPengeluaranBos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-clean" style="background: #16a34a !important;">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Tambah Pengeluaran dari Dana BOS
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('bos.pengeluaran.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun_anggaran" value="{{ $tahunAnggaran }}">

                    <div class="modal-body p-4">
                        <div class="alert {{ $sisaSaldoBos > 0 ? 'alert-info' : 'alert-danger' }} py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: 8px;">
                            <div>
                                <i class="fas {{ $sisaSaldoBos > 0 ? 'fa-info-circle' : 'fa-exclamation-triangle' }} mr-1"></i>
                                <span>Sisa Dana BOS Tersedia (Tahun {{ $tahunAnggaran }}):</span>
                            </div>
                            <strong class="font-num font-weight-bold" style="font-size: 14px;">
                                Rp {{ number_format($sisaSaldoBos, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">
                                    Tanggal Pengeluaran <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="tanggal"
                                       class="form-control"
                                       style="border-radius: 8px;"
                                       value="{{ date('Y-m-d') }}"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">
                                    Sumber Dana
                                </label>
                                <input type="text"
                                       class="form-control font-weight-bold text-primary bg-light"
                                       value="BOS (Bantuan Operasional Sekolah)"
                                       readonly
                                       style="border-radius: 8px;">
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
                                       placeholder="Masukkan nominal..."
                                       min="1"
                                       max="{{ max(0, (int)$sisaSaldoBos) }}"
                                       step="1"
                                       style="border-radius: 0 8px 8px 0;"
                                       required>
                            </div>
                            <small class="text-muted">Maksimal pengeluaran yang diizinkan sesuai sisa saldo BOS: <strong>Rp {{ number_format(max(0, (int)$sisaSaldoBos), 0, ',', '.') }}</strong></small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold">
                                Keterangan / Keperluan Pengeluaran <span class="text-danger">*</span>
                            </label>
                            <textarea name="keterangan"
                                      rows="3"
                                      class="form-control"
                                      style="border-radius: 8px;"
                                      placeholder="Contoh: Pembelian buku pelajaran, alat peraga, langganan daya dan jasa..."
                                      maxlength="500"
                                      required></textarea>
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

    {{-- MODAL EDIT & HAPUS PENGELUARAN BOS --}}
    @foreach($pengeluaranBos as $item)
        {{-- MODAL EDIT --}}
        <div class="modal fade" id="modalEditPengeluaran{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                    <div class="modal-header modal-header-clean">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Pengeluaran Dana BOS
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('pengeluaran.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sumber_dana" value="BOS">

                        <div class="modal-body p-4">
                            @php
                                $maksEdit = $sisaSaldoBos + $item->nominal;
                            @endphp
                            <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: 8px;">
                                <div>
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span>Batas Maksimal Pengeluaran:</span>
                                </div>
                                <strong class="font-num font-weight-bold" style="font-size: 14px;">
                                    Rp {{ number_format($maksEdit, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">
                                        Tanggal Pengeluaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           name="tanggal"
                                           class="form-control"
                                           style="border-radius: 8px;"
                                           value="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">
                                        Sumber Dana
                                    </label>
                                    <input type="text"
                                           class="form-control font-weight-bold text-primary bg-light"
                                           value="BOS (Bantuan Operasional Sekolah)"
                                           readonly
                                           style="border-radius: 8px;">
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
                                           min="1"
                                           max="{{ (int)$maksEdit }}"
                                           step="1"
                                           value="{{ (int)$item->nominal }}"
                                           style="border-radius: 0 8px 8px 0;"
                                           required>
                                </div>
                                <small class="text-muted">Maksimal perubahan yang diizinkan: <strong>Rp {{ number_format($maksEdit, 0, ',', '.') }}</strong></small>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label font-weight-bold">
                                    Keterangan / Keperluan <span class="text-danger">*</span>
                                </label>
                                <textarea name="keterangan"
                                          rows="3"
                                          class="form-control"
                                          style="border-radius: 8px;"
                                          maxlength="500"
                                          required>{{ $item->keterangan }}</textarea>
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
                    <div class="modal-header modal-header-danger">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Konfirmasi Hapus Pengeluaran BOS
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-2 text-dark">
                            Apakah Anda yakin ingin menghapus data pengeluaran berikut:
                        </p>
                        <div class="p-3 bg-light border rounded mb-3" style="border-radius: 10px;">
                            <div class="font-weight-bold text-dark">{{ $item->keterangan }}</div>
                            <div class="text-muted small mt-1">
                                Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }} &bull; Sumber: <strong>BOS</strong>
                            </div>
                            <div class="text-danger font-weight-bold font-num mt-1">
                                Nominal: Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="alert alert-danger mb-0 small" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Data pengeluaran yang dihapus tidak dapat dikembalikan.
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-2 px-4 justify-content-end">
                        <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary px-4 mr-2" data-dismiss="modal" style="border-radius: 8px; height: 38px;">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                                <i class="fas fa-trash-alt mr-1"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@stop
