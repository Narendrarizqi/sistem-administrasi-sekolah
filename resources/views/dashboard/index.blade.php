@extends('adminlte::page')

@section('title', 'Dashboard — Sistem Rekap Pembayaran')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ time() }}">
    <style>
        /* Scoped Dashboard Modern SaaS Layout */
        .content-wrapper > .content {
            padding: 18px 24px 30px 24px !important;
        }

        /* Hero Banner */
        .welcome-hero-banner {
            background: #15803d !important;
            color: #ffffff;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 4px 16px rgba(13, 93, 45, 0.2);
            position: relative !important;
            z-index: 100 !important;
            overflow: visible !important;
        }

        /* Dropdown Tahun Ajaran (Ukuran Lebar Tombol & Menu Presisi Sama) */
        .custom-ta-hero-dropdown {
            position: relative !important;
            display: inline-block !important;
            width: 240px !important;
            z-index: 105 !important;
        }

        .custom-ta-hero-dropdown .ta-hero-btn {
            width: 100% !important;
            transition: all 0.15s ease;
            border: 1px solid transparent !important;
            outline: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .custom-ta-hero-dropdown .ta-hero-btn::after {
            display: none !important; /* Hapus panah duplikat bootstrap */
        }

        .custom-ta-hero-dropdown .ta-hero-btn:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12) !important;
            transform: translateY(-1px);
        }

        .custom-ta-hero-dropdown .dropdown-menu.ta-custom-menu {
            position: absolute !important;
            top: calc(100% + 6px) !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            transform: none !important;
            border-radius: 14px !important;
            border: 1px solid #e2e8f0 !important;
            padding: 8px 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.16) !important;
            z-index: 99999 !important;
            box-sizing: border-box !important;
        }

        .ta-custom-menu .dropdown-item {
            color: #475569;
            padding: 8px 16px;
            font-size: 13px;
            cursor: pointer;
        }

        .ta-custom-menu .dropdown-item:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .ta-custom-menu .active-ta-item {
            background: #f0fdf4 !important;
            color: #15803d !important;
            font-weight: 700 !important;
        }

        /* 4 Stat Cards */
        .saas-stat-grid {
            position: relative !important;
            z-index: 1 !important;
        }

        .modern-stat-card {
            position: relative !important;
            background: #ffffff !important;
            border: 1px solid #f1f5f9 !important;
            border-radius: 18px !important;
            padding: 18px 20px 16px 20px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.02) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
            height: 100% !important;
            min-height: 0 !important;
            transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.22s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.22s ease !important;
            overflow: hidden !important;
            text-decoration: none !important;
        }

        .modern-stat-card:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.03) !important;
            border-color: #e2e8f0 !important;
        }

        .stat-card-body {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 14px !important;
            width: 100% !important;
            margin-bottom: 12px;
        }

        .stat-icon-wrapper {
            flex-shrink: 0 !important;
            width: 48px !important;
            height: 48px !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 19px !important;
            transition: transform 0.2s ease !important;
        }

        .modern-stat-card:hover .stat-icon-wrapper {
            transform: scale(1.08) !important;
        }

        .icon-target {
            background: #ecfdf5 !important;
            color: #10b981 !important;
            border: 1px solid #bbf7d0 !important;
        }

        .icon-terkumpul {
            background: #f0fdf4 !important;
            color: #16a34a !important;
            border: 1px solid #bbf7d0 !important;
        }

        .icon-sisa {
            background: #fffbeb !important;
            color: #f59e0b !important;
            border: 1px solid #fef3c7 !important;
        }

        .icon-siswa {
            background: #f5f3ff !important;
            color: #8b5cf6 !important;
            border: 1px solid #ddd6fe !important;
        }

        .stat-info-wrapper {
            flex: 1 !important;
            min-width: 0 !important;
        }

        .stat-title-label {
            display: block !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #64748b !important;
            margin-bottom: 2px !important;
            line-height: 1.3 !important;
        }

        .stat-main-value {
            margin: 0 !important;
            font-size: 16.5px !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
            letter-spacing: -0.02em !important;
        }

        .value-sisa,
        .value-siswa {
            color: #0f172a !important;
        }

        .stat-card-footer {
            margin-top: auto !important;
            padding-top: 10px !important;
            border-top: 1px solid #f8fafc !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .stat-footer-text {
            font-size: 12px !important;
            font-weight: 500 !important;
            color: #64748b !important;
        }

        .stat-footer-chevron {
            font-size: 11px !important;
            color: #94a3b8 !important;
            transition: transform 0.15s ease, color 0.15s ease !important;
        }

        .modern-stat-card:hover .stat-footer-chevron {
            transform: translateX(3px) !important;
            color: #0f172a !important;
        }

        /* Panel Cards (Middle Section) */
        .modern-panel-card {
            background: #ffffff !important;
            border: 1px solid #eef2f6 !important;
            border-radius: 18px !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02) !important;
            overflow: hidden !important;
            transition: box-shadow 0.2s ease !important;
        }

        .modern-panel-card:hover {
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04) !important;
        }

        .panel-card-header {
            padding: 18px 22px !important;
            border-bottom: 1px solid #f8fafc !important;
            background: #ffffff !important;
        }

        .panel-card-title {
            font-size: 15px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            letter-spacing: -0.01em !important;
        }

        .panel-card-body {
            padding: 20px 22px !important;
        }

        .badge-light-pill {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #64748b !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 4px 12px !important;
        }

        .panel-list-item {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 12px 0 !important;
            border-bottom: 1px solid #f8fafc !important;
        }

        .panel-list-label {
            color: #475569 !important;
            font-weight: 500 !important;
            font-size: 13px !important;
        }

        .panel-list-value {
            font-weight: 700 !important;
            color: #0f172a !important;
            font-size: 14px !important;
        }

        .text-amber { color: #d97706 !important; }

        .btn-pill-warning {
            background: #fffbeb !important;
            border: 1px solid #fde68a !important;
            color: #b45309 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            border-radius: 999px !important;
            padding: 2px 9px !important;
            transition: all 0.15s ease !important;
        }

        .btn-pill-warning:hover {
            background: #fef3c7 !important;
            color: #92400e !important;
        }

        .btn-rincian-toggle {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            border-radius: 10px !important;
            font-size: 12.5px !important;
            transition: all 0.15s ease !important;
        }

        .btn-rincian-toggle:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
        }

        .collapse-rincian-box {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease, margin 0.3s ease;
            margin-top: 0;
        }

        .collapse-rincian-box.is-open {
            max-height: 400px;
            opacity: 1;
            margin-top: 6px;
        }

        #iconChevronRincian.rotate {
            transform: rotate(180deg);
        }

        .panel-total-box {
            background: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
            border-radius: 14px !important;
            padding: 14px 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .btn-filter-jenis {
            background: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
            color: #15803d !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            padding: 5px 12px !important;
            cursor: pointer !important;
        }

        .btn-filter-jenis::after {
            display: none !important; /* Hapus panah duplikat */
        }

        /* Donut Chart */
        .db-chart-container {
            position: relative;
            width: 165px;
            height: 165px;
            margin: 0 auto;
        }

        .db-chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
            width: 105px;
        }

        .db-chart-percent {
            font-size: 19px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
        }

        .db-chart-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .db-legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .db-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Modern Table */
        .modern-data-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            margin-bottom: 0 !important;
        }

        .modern-data-table thead th {
            background: #fcfcfd !important;
            color: #64748b !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            letter-spacing: -0.01em;
            padding: 12px 18px !important;
            border-top: none !important;
            border-bottom: 1px solid #f1f5f9 !important;
            white-space: nowrap !important;
        }

        .modern-data-table tbody td {
            padding: 13px 18px !important;
            vertical-align: middle !important;
            color: #1e293b !important;
            font-size: 13px !important;
            border-top: none !important;
            border-bottom: 1px solid #f8fafc !important;
        }

        .modern-data-table tbody tr:hover {
            background-color: #fbfcfe !important;
        }

        .badge-jenis-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            padding: 4px 10px;
            display: inline-block;
        }

        .badge-metode-transfer {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 999px;
            padding: 3px 10px;
            display: inline-block;
        }

        .badge-metode-tunai {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #059669;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 999px;
            padding: 3px 10px;
            display: inline-block;
        }

        .badge-status-lunas {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #15803d;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 999px;
            padding: 4px 12px;
            display: inline-flex;
            align-items: center;
        }

        .badge-status-pending {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #b45309;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 999px;
            padding: 4px 12px;
            display: inline-flex;
            align-items: center;
        }

        .btn-panel-more {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            padding: 7px 18px !important;
            transition: all 0.15s ease !important;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
        }

        .btn-panel-more:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04) !important;
        }
    </style>
@stop

@section('content')

{{-- 1. HERO BANNER HIJAU DENGAN SELECTOR TAHUN AJARAN RINGKAS --}}
<div class="welcome-hero-banner mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h3 class="font-weight-bold mb-1 text-white" style="font-size: 22px; letter-spacing: -0.01em;">
            Welcome back, {{ auth()->user()->name ?? 'Administrator' }}
        </h3>
        <p class="mb-0 text-white" style="font-size: 13.5px; opacity: 0.88;">
            Berikut ringkasan target kewajiban, realisasi pembayaran, dan performa keuangan sekolah.
        </p>
    </div>

    {{-- Pemilihan Tahun Ajaran Aktif (Ukuran Tombol & Dropdown Menu Sama Persis) --}}
    <div class="dropdown custom-ta-hero-dropdown">
        <button class="btn bg-white shadow-sm border-0 ta-hero-btn" type="button" id="dropdownHeroTA" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" style="border-radius: 12px; padding: 7px 12px 7px 10px; cursor: pointer;">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <div class="d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; border-radius: 9px; background: #ecfdf5; color: #16a34a; flex-shrink: 0;">
                    <i class="far fa-calendar-alt" style="font-size: 15px;"></i>
                </div>
                <div class="d-flex flex-column text-left" style="line-height: 1.15;">
                    <span class="text-uppercase font-weight-bold" style="font-size: 9.5px; color: #94a3b8; letter-spacing: 0.5px;">
                        TAHUN AJARAN
                    </span>
                    <div class="d-flex align-items-center" style="font-size: 13px; font-weight: 700; color: #0f172a;">
                        <span>{{ $tahunAjaranNama }}</span>
                        @if($selectedTa && $selectedTa->is_active)
                            <span class="ml-1" style="color: #16a34a; font-size: 11.5px; font-weight: 600;">(Aktif)</span>
                        @endif
                    </div>
                </div>
            </div>
            <i class="fas fa-chevron-down text-muted ml-2" style="font-size: 10px;"></i>
        </button>

        {{-- Dropdown Menu Tahun Ajaran (Lebar Sama 100% dengan Tombol) --}}
        <div class="dropdown-menu shadow-lg ta-custom-menu" aria-labelledby="dropdownHeroTA">
            <div class="px-3 py-1.5 text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.6px;">
                Pilih Tahun Ajaran
            </div>
            @foreach($daftarTahunAjaran as $ta)
                <a class="dropdown-item d-flex align-items-center justify-content-between {{ $ta->id == $tahunAjaranId ? 'active-ta-item' : '' }}" 
                   href="{{ route('dashboard', ['tahun_ajaran_id' => $ta->id]) }}">
                    <span class="d-flex align-items-center">
                        @if($ta->id == $tahunAjaranId)
                            <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i>
                        @else
                            <i class="far fa-circle text-muted mr-2" style="font-size: 12px; opacity: 0.4;"></i>
                        @endif
                        {{ $ta->nama }}
                    </span>
                    @if($ta->is_active)
                        <span class="badge badge-success ml-2" style="font-size: 9.5px; font-weight: 600; padding: 2px 7px; border-radius: 999px; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                            Aktif
                        </span>
                    @endif
                </a>
            @endforeach

            <div class="dropdown-divider my-1.5" style="border-color: #f1f5f9;"></div>

            {{-- Tombol Pengaturan di dalam Dropdown --}}
            <a class="dropdown-item d-flex align-items-center text-secondary" href="{{ route('tahun-ajaran.index') }}" style="font-size: 12.5px; font-weight: 600; color: #475569;">
                <i class="fas fa-cog text-muted mr-2" style="font-size: 13px;"></i>
                <span>Kelola Tahun Ajaran</span>
            </a>
        </div>
    </div>
</div>

{{-- 2. TOP 4 STAT CARDS (PERSIS SESUAI DESAIN REFERENSI) --}}
<div class="row g-3 mb-4 saas-stat-grid">
    {{-- Card 1: Target Tahunan --}}
    <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
        <a href="{{ route('target-tahunan.index') }}" class="modern-stat-card card-target text-decoration-none">
            <div class="stat-card-body">
                <div class="stat-icon-wrapper icon-target">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="stat-info-wrapper">
                    <span class="stat-title-label">Target Tahunan</span>
                    <h3 class="stat-main-value">Rp {{ number_format($targetEfektif, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-footer-text">Total target semua jenis</span>
                <i class="fas fa-chevron-right stat-footer-chevron"></i>
            </div>
        </a>
    </div>

    {{-- Card 2: Terbayar (Sudah Dibayar) --}}
    <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
        <a href="{{ route('rekap.index') }}" class="modern-stat-card card-terkumpul text-decoration-none">
            <div class="stat-card-body">
                <div class="stat-icon-wrapper icon-terkumpul">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-info-wrapper">
                    <span class="stat-title-label">Terbayar</span>
                    <h3 class="stat-main-value">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-footer-text">{{ $persenDibayar }}% dari target</span>
                <i class="fas fa-chevron-right stat-footer-chevron"></i>
            </div>
        </a>
    </div>

    {{-- Card 3: Belum Lunas (Sisa Tagihan) --}}
    <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
        <a href="{{ route('rekap.index') }}" class="modern-stat-card card-sisa text-decoration-none">
            <div class="stat-card-body">
                <div class="stat-icon-wrapper icon-sisa">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-info-wrapper">
                    <span class="stat-title-label">Belum Lunas</span>
                    <h3 class="stat-main-value">Rp {{ number_format($sisaTarget, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-footer-text">{{ $persenSisa }}% dari target</span>
                <i class="fas fa-chevron-right stat-footer-chevron"></i>
            </div>
        </a>
    </div>

    {{-- Card 4: Jumlah Siswa --}}
    <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
        <a href="{{ route('siswa.index') }}" class="modern-stat-card card-siswa text-decoration-none">
            <div class="stat-card-body">
                <div class="stat-icon-wrapper icon-siswa">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-info-wrapper">
                    <span class="stat-title-label">Jumlah Siswa</span>
                    <h3 class="stat-main-value">{{ number_format($totalSiswa, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-footer-text">Aktif tahun ini</span>
                <i class="fas fa-chevron-right stat-footer-chevron"></i>
            </div>
        </a>
    </div>
</div>

{{-- 3. MIDDLE SECTION: RINGKASAN TARGET TAHUNAN & PROGRESS PEMBAYARAN --}}
<div class="row g-3 mb-4">
    {{-- Kolom Kiri: Ringkasan Target Tahunan --}}
    <div class="col-12 col-lg-6 mb-3 mb-lg-0">
        <div class="modern-panel-card h-100">
            <div class="panel-card-header d-flex align-items-center justify-content-between">
                <h5 class="panel-card-title m-0">
                    Ringkasan Target Tahunan
                </h5>
                <span class="badge badge-light-pill">
                    Tahun {{ $tahunAjaranNama }}
                </span>
            </div>
            <div class="panel-card-body d-flex flex-column justify-content-between">
                <div>
                    {{-- Target Tagihan Baru --}}
                    <div class="panel-list-item">
                        <span class="panel-list-label">Target Ditetapkan (Tagihan Baru)</span>
                        <span class="panel-list-value">Rp {{ number_format($targetDitetapkan, 0, ',', '.') }}</span>
                    </div>

                    {{-- Sisa Belum Lunas Tahun Lalu --}}
                    <div class="panel-list-item">
                        <span class="panel-list-label">Sisa Belum Lunas Tahun Lalu ({{ $tahunLaluNama }})</span>
                        <div class="d-flex align-items-center">
                            <span class="panel-list-value text-amber font-weight-bold">Rp {{ number_format($totalTerbawa, 0, ',', '.') }}</span>
                            @if($jumlahSiswaTerbawa > 0)
                                <button type="button" class="btn btn-xs btn-pill-warning ml-2" data-toggle="modal" data-target="#modalTerbawa" data-bs-toggle="modal" data-bs-target="#modalTerbawa" title="Lihat Rincian Siswa Terbawa">
                                    {{ $jumlahSiswaTerbawa }} Siswa
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Dropdown Rincian per Jenis Pembayaran --}}
                    @if(count($breakdownJenis) > 0)
                        <div class="my-2.5">
                            <button class="btn btn-rincian-toggle w-100 d-flex align-items-center justify-content-between py-2 px-3" type="button" onclick="toggleRincianTagihan()">
                                <span class="font-weight-600">Lihat Rincian per Jenis Tagihan</span>
                                <i class="fas fa-chevron-down text-muted" id="iconChevronRincian" style="font-size: 11px;"></i>
                            </button>
                            <div id="collapseRincianJenis" class="collapse-rincian-box">
                                <div class="p-2.5 rounded-3 bg-light border mt-2">
                                    <div class="row g-2">
                                        @foreach($breakdownJenis as $b)
                                            <div class="col-6 mb-1.5">
                                                <div class="p-2 rounded-2 bg-white border d-flex justify-content-between align-items-center" style="font-size: 12px;">
                                                    <span class="text-secondary text-truncate mr-1 font-weight-500">{{ $b['nama'] }}</span>
                                                    <span class="font-weight-bold text-dark">Rp {{ number_format($b['target'], 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Target Efektif Total --}}
                    <div class="panel-total-box mt-3">
                        <span class="font-weight-bold text-success" style="font-size: 14.5px;">Target Efektif Tahun Ini</span>
                        <span class="font-weight-bold text-success fs-5">Rp {{ number_format($targetEfektif, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Progress Pembayaran Tahun Ini --}}
    <div class="col-12 col-lg-6">
        <div class="modern-panel-card h-100">
            <div class="panel-card-header d-flex align-items-center justify-content-between">
                <h5 class="panel-card-title m-0">
                    Progress Pembayaran Tahun Ini
                </h5>
                <div class="dropdown">
                    <button class="btn btn-filter-jenis dropdown-toggle" type="button" id="dropdownFilterJenis" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false">
                        <span id="filterJenisLabel">Semua Jenis</span>
                        <i class="fas fa-chevron-down ml-1" style="font-size: 9.5px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right shadow-sm nav-dropdown-box" aria-labelledby="dropdownFilterJenis" style="min-width: 180px;">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 active" href="#" data-jenis="all" onclick="filterChartJenis('all', this); return false;">
                                <span class="d-inline-block rounded-circle mr-2" style="width: 8px; height: 8px; background: #64748b;"></span>
                                Semua Jenis
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        @foreach($breakdownJenis as $idx => $bj)
                            @if($bj['target'] > 0)
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#"
                                   data-jenis="{{ $bj['id'] }}"
                                   onclick="filterChartJenis({{ $bj['id'] }}, this); return false;">
                                    <span class="d-inline-block rounded-circle mr-2 jenis-dot-{{ $bj['id'] }}" style="width: 8px; height: 8px;"></span>
                                    {{ $bj['nama'] }}
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="panel-card-body d-flex flex-column justify-content-center">
                <div class="row align-items-center g-3 my-auto">
                    {{-- Donut Chart --}}
                    <div class="col-sm-5 col-12 text-center">
                        <div class="db-chart-container">
                            <canvas id="chartProgressPembayaran"></canvas>
                            <div class="db-chart-center-text">
                                <div class="db-chart-percent" id="chartCenterPercent">{{ $persenDibayar }}%</div>
                                <div class="db-chart-sub" id="chartCenterSub">Tercapai</div>
                            </div>
                        </div>
                    </div>

                    {{-- Legend & Summary --}}
                    <div class="col-sm-7 col-12">
                        <div id="chartLegendContainer">
                            {{-- Legend populated by JS --}}
                        </div>

                        <div class="mt-3 pt-3 border-top" id="chartTotalSection">
                            <div class="text-secondary" style="font-size: 11.5px;" id="chartTotalLabel">Total Target Efektif</div>
                            <div class="font-weight-bold fs-5 text-dark" id="chartTotalValue">
                                Rp {{ number_format($targetEfektif, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 4. PEMBAYARAN TERBARU (TABEL SESUAI REFERENSI) --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="modern-panel-card">
            <div class="panel-card-header d-flex align-items-center justify-content-between">
                <h5 class="panel-card-title m-0">
                    Pembayaran Terbaru
                </h5>
                <span class="badge badge-light-pill">10 Transaksi Terakhir</span>
            </div>
            <div class="panel-card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-data-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Jenis Pembayaran</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($pembayaranTerbaru as $index => $item)
                            @php
                                $pembayaranObj = $item->pembayaran;
                                $siswaObj      = $pembayaranObj?->siswa;
                                $jenisObj      = $pembayaranObj?->jenisPembayaran;
                                $taNama        = $pembayaranObj?->tahunAjaran?->nama ?? $pembayaranObj?->tahun_ajaran ?? $tahunAjaranNama;

                                // Hitung status lunas berdasarkan tagihan asli siswa
                                $totalTagihan  = $pembayaranObj?->totalTagihan() ?? 0;

                                $totalTerbayar = (float) ($pembayaranObj?->detailPembayaran?->sum('nominal') ?? 0);
                                $sisaTagihan   = max($totalTagihan - $totalTerbayar, 0);

                                $isLunas = ($totalTagihan > 0 && $sisaTagihan <= 0) || ($pembayaranObj?->status === 'Lunas' && $sisaTagihan <= 0);
                                $metode = strtolower($item->metode ?? 'tunai');
                            @endphp
                            <tr>
                                <td class="text-center text-muted font-weight-500">{{ $index + 1 }}</td>
                                <td class="text-secondary font-weight-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $siswaObj?->nama ?? '-' }}</div>
                                    <div class="text-muted small" style="font-size: 11px;">NIS: {{ $siswaObj?->nis ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge-jenis-pill">
                                        {{ $jenisObj?->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="font-weight-bold text-dark font-tabular">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($metode === 'transfer')
                                        <span class="badge-metode-transfer">
                                            Transfer
                                        </span>
                                    @else
                                        <span class="badge-metode-tunai">
                                            Tunai
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($isLunas)
                                        <span class="badge-status-lunas">
                                            <i class="fas fa-check-circle mr-1"></i> Lunas
                                        </span>
                                    @else
                                        <span class="badge-status-pending">
                                            <i class="fas fa-clock mr-1"></i> Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-receipt fa-2x mb-2 d-block opacity-25"></i>
                                    Belum ada transaksi pembayaran pada tahun ajaran ini.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-card-footer text-center py-3 bg-white border-top">
                <a href="{{ route('rekap.index') }}" class="btn btn-panel-more">
                    <i class="fas fa-list-ul mr-1.5"></i> Lihat Semua Rekap Pembayaran
                </a>
            </div>
        </div>
    </div>
</div>

{{-- 5. MODAL RINCIAN TERBAWA TAHUN LALU --}}
<div class="modal fade" id="modalTerbawa" tabindex="-1" aria-labelledby="modalTerbawaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.12);">
            <div class="modal-header px-4 py-3 bg-light border-bottom">
                <div>
                    <h5 class="modal-title font-weight-bold text-dark" id="modalTerbawaLabel" style="font-size: 16px;">
                        Rincian Sisa Belum Lunas Tahun Lalu ({{ $tahunLaluNama }})
                    </h5>
                    <div class="text-muted" style="font-size: 12px;">
                        Daftar siswa yang memiliki tagihan belum lunas dari tahun ajaran sebelumnya
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 24px; line-height: 1; opacity: 0.7; cursor: pointer;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="max-height: 480px; overflow-y: auto;">
                @if($siswaTerbawa->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr style="font-size: 13px;">
                                    <th class="text-center" style="width: 40px;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Tagihan & Nominal</th>
                                    <th class="text-end text-right">Total Terbawa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaTerbawa as $idx => $st)
                                    <tr style="font-size: 13.5px;">
                                        <td class="text-center text-muted">{{ $idx + 1 }}</td>
                                        <td>
                                            <strong>{{ $st['nama'] }}</strong>
                                            <div class="text-muted" style="font-size: 11px;">NIS: {{ $st['nis'] }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light border">{{ $st['kelas'] ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @foreach($st['tagihan'] as $tag)
                                                <div class="d-flex justify-content-between gap-2 py-1" style="font-size: 12.5px; border-bottom: 1px dashed #eee;">
                                                    <span class="text-secondary">• {{ $tag['jenis'] }}</span>
                                                    <span class="font-weight-600">Rp {{ number_format($tag['nominal'], 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="text-end text-right font-weight-bold text-danger">
                                            Rp {{ number_format($st['total'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr class="font-weight-bold">
                                    <td colspan="4" class="text-end text-right text-dark">Total Seluruh Tagihan Terbawa:</td>
                                    <td class="text-end text-right text-danger fs-6">Rp {{ number_format($totalTerbawa, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle text-success fa-3x mb-2 d-block opacity-75"></i>
                        <h6 class="font-weight-bold text-dark">Tidak ada tagihan terbawa</h6>
                        <p class="small mb-0">Seluruh siswa sudah menyelesaikan kewajiban dari tahun ajaran {{ $tahunLaluNama }}.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#modalTerbawa').modal('hide');">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleRincianTagihan() {
            const el = document.getElementById('collapseRincianJenis');
            const icon = document.getElementById('iconChevronRincian');
            if (!el) return;

            if (el.classList.contains('is-open')) {
                el.classList.remove('is-open');
                if (icon) icon.classList.remove('rotate');
            } else {
                el.classList.add('is-open');
                if (icon) icon.classList.add('rotate');
            }
        }

        // ── Chart Data ──
        const jenisData = @json(
            collect($breakdownJenis)->filter(fn($b) => $b['target'] > 0)->values()
        );

        const totalDibayar = {{ $sudahDibayar }};
        const totalSisa    = {{ $sisaTarget }};
        const totalTarget  = {{ $targetEfektif }};

        // Distinct color palette for each jenis (Modern SaaS Palette)
        const jenisColors = [
            '#10b981', // emerald
            '#3b82f6', // blue
            '#f59e0b', // amber
            '#8b5cf6', // violet
            '#ec4899', // pink
            '#06b6d4', // cyan
            '#f97316', // orange
            '#6366f1', // indigo
            '#14b8a6', // teal
            '#ef4444', // red
            '#84cc16', // lime
            '#a855f7', // purple
        ];

        // Assign colors to jenis and set CSS dot backgrounds
        jenisData.forEach(function(j, i) {
            j._color = jenisColors[i % jenisColors.length];
            const dots = document.querySelectorAll('.jenis-dot-' + j.id);
            dots.forEach(function(dot) { dot.style.background = j._color; });
        });

        let progressChart = null;
        let defaultCenterPercent = '{{ $persenDibayar }}%';
        let defaultCenterSub = 'Tercapai';

        function formatRupiah(val) {
            return 'Rp ' + Number(val).toLocaleString('id-ID');
        }

        function buildLegendAll() {
            let html = '';
            jenisData.forEach(function(j) {
                const persen = j.target > 0 ? ((j.dibayar / j.target) * 100).toFixed(1) : 0;
                html += '<div class="db-legend-item">' +
                    '<div class="db-legend-dot" style="background:' + j._color + ';"></div>' +
                    '<div class="flex-grow-1 min-w-0">' +
                        '<div class="text-secondary text-truncate" style="font-size:12px;">' + j.nama + '</div>' +
                        '<div class="font-weight-bold" style="color:var(--color-text); font-size:13px;">' +
                            formatRupiah(j.dibayar) +
                            ' <span class="badge ms-1" style="font-size:10px; background:' + j._color + '22; color:' + j._color + ';">' + persen + '%</span>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            });
            document.getElementById('chartLegendContainer').innerHTML = html;
        }

        function buildLegendSingle(j) {
            const sisa = Math.max(j.target - j.dibayar, 0);
            const persen = j.target > 0 ? ((j.dibayar / j.target) * 100).toFixed(1) : 0;
            const persenSisa = j.target > 0 ? (100 - parseFloat(persen)).toFixed(1) : 0;

            let html = '<div class="db-legend-item">' +
                '<div class="db-legend-dot" style="background:' + j._color + ';"></div>' +
                '<div class="flex-grow-1">' +
                    '<div class="text-secondary" style="font-size:12px;">Sudah Dibayar</div>' +
                    '<div class="font-weight-bold" style="color:var(--color-text); font-size:13px;">' +
                        formatRupiah(j.dibayar) +
                        ' <span class="badge ms-1" style="font-size:10px; background:' + j._color + '22; color:' + j._color + ';">' + persen + '%</span>' +
                    '</div>' +
                '</div>' +
            '</div>';

            html += '<div class="db-legend-item">' +
                '<div class="db-legend-dot" style="background:#e2e8f0;"></div>' +
                '<div class="flex-grow-1">' +
                    '<div class="text-secondary" style="font-size:12px;">Belum Dibayar</div>' +
                    '<div class="font-weight-bold" style="color:var(--color-text); font-size:13px;">' +
                        formatRupiah(sisa) +
                        ' <span class="badge bg-secondary-subtle text-secondary ms-1" style="font-size:10px;">' + persenSisa + '%</span>' +
                    '</div>' +
                '</div>' +
            '</div>';

            document.getElementById('chartLegendContainer').innerHTML = html;
        }

        // Custom Floating Tooltip Handler
        function customTooltipHandler(context) {
            let tooltipEl = document.getElementById('chartjs-floating-tooltip');

            if (!tooltipEl) {
                tooltipEl = document.createElement('div');
                tooltipEl.id = 'chartjs-floating-tooltip';
                tooltipEl.style.background = '#ffffff';
                tooltipEl.style.borderRadius = '10px';
                tooltipEl.style.border = '1px solid #e2e8f0';
                tooltipEl.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.08)';
                tooltipEl.style.color = '#0f172a';
                tooltipEl.style.opacity = '0';
                tooltipEl.style.pointerEvents = 'none';
                tooltipEl.style.position = 'absolute';
                tooltipEl.style.transform = 'translate(-50%, -115%)';
                tooltipEl.style.transition = 'opacity 0.15s ease, transform 0.15s ease';
                tooltipEl.style.zIndex = '1000';
                tooltipEl.style.padding = '8px 14px';
                tooltipEl.style.minWidth = '150px';
                tooltipEl.style.whiteSpace = 'nowrap';
                tooltipEl.style.fontFamily = "'Plus Jakarta Sans', sans-serif";
                document.body.appendChild(tooltipEl);
            }

            const tooltipModel = context.tooltip;
            if (tooltipModel.opacity === 0) {
                tooltipEl.style.opacity = '0';
                const centerPercentEl = document.getElementById('chartCenterPercent');
                const centerSubEl = document.getElementById('chartCenterSub');
                if (centerPercentEl) centerPercentEl.innerHTML = defaultCenterPercent;
                if (centerSubEl) centerSubEl.textContent = defaultCenterSub;
                return;
            }

            if (tooltipModel.body) {
                const dataPoint = tooltipModel.dataPoints[0];
                const label = dataPoint.label || '';
                const value = dataPoint.raw || 0;
                const total = dataPoint.dataset.data.reduce((a, b) => a + b, 0);
                const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                const color = dataPoint.dataset.backgroundColor[dataPoint.dataIndex] || '#16a34a';

                const centerPercentEl = document.getElementById('chartCenterPercent');
                const centerSubEl = document.getElementById('chartCenterSub');
                if (centerPercentEl) {
                    centerPercentEl.innerHTML = '<span style="font-size: 13.5px; font-weight: 800; color: ' + (label === 'Belum Dibayar' ? '#64748b' : color) + ';">' + formatRupiah(value) + '</span>';
                }
                if (centerSubEl) {
                    centerSubEl.textContent = label;
                }

                tooltipEl.innerHTML = `
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:3px;">
                        <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:${color}; flex-shrink:0;"></span>
                        <span style="font-size:12px; font-weight:700; color:#0f172a;">${label}</span>
                    </div>
                    <div style="font-size:13.5px; font-weight:800; color:#0f172a; margin-bottom:2px;">
                        ${formatRupiah(value)}
                    </div>
                    <div style="font-size:11px; font-weight:600; color:#64748b;">
                        Porsi: <span style="color:#16a34a; font-weight:700;">${pct}%</span>
                    </div>
                `;
            }

            const position = context.chart.canvas.getBoundingClientRect();
            tooltipEl.style.opacity = '1';
            tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
            tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
        }

        function renderChart(labels, data, colors, centerPercent, centerSub) {
            const ctx = document.getElementById('chartProgressPembayaran');
            if (!ctx) return;

            if (progressChart) {
                progressChart.destroy();
            }

            defaultCenterPercent = centerPercent;
            defaultCenterSub = centerSub;

            document.getElementById('chartCenterPercent').innerHTML = centerPercent;
            document.getElementById('chartCenterSub').textContent = centerSub;

            const total = data.reduce(function(a, b) { return a + b; }, 0);

            progressChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: total > 0 ? data : [1],
                        backgroundColor: total > 0 ? colors : ['#e2e8f0'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6,
                        cutout: '72%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 400,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: false,
                            external: customTooltipHandler
                        }
                    }
                }
            });
        }

        function showAllJenis() {
            const labels = jenisData.map(function(j) { return j.nama; });
            const data = jenisData.map(function(j) { return j.dibayar; });
            const colors = jenisData.map(function(j) { return j._color; });

            // Sisa belum dibayar
            const totalDibayarJenis = data.reduce(function(a, b) { return a + b; }, 0);
            const sisaAll = Math.max(totalTarget - totalDibayarJenis, 0);
            if (sisaAll > 0) {
                labels.push('Belum Dibayar');
                data.push(sisaAll);
                colors.push('#e2e8f0');
            }

            const persen = totalTarget > 0 ? ((totalDibayar / totalTarget) * 100).toFixed(1) : 0;
            renderChart(labels, data, colors, persen + '%', 'Tercapai');
            buildLegendAll();

            document.getElementById('chartTotalLabel').textContent = 'Total Target Efektif';
            document.getElementById('chartTotalValue').textContent = formatRupiah(totalTarget);
        }

        function showSingleJenis(jenisId) {
            const j = jenisData.find(function(item) { return item.id === jenisId; });
            if (!j) return;

            const sisa = Math.max(j.target - j.dibayar, 0);
            const persen = j.target > 0 ? ((j.dibayar / j.target) * 100).toFixed(1) : 0;

            renderChart(
                ['Sudah Dibayar', 'Belum Dibayar'],
                [j.dibayar, sisa],
                [j._color, '#e2e8f0'],
                persen + '%',
                'Tercapai'
            );
            buildLegendSingle(j);

            document.getElementById('chartTotalLabel').textContent = 'Target ' + j.nama;
            document.getElementById('chartTotalValue').textContent = formatRupiah(j.target);
        }

        function filterChartJenis(jenisId, el) {
            document.querySelectorAll('#dropdownFilterJenis + .dropdown-menu .dropdown-item').forEach(function(item) {
                item.classList.remove('active');
            });
            if (el) el.classList.add('active');

            const label = document.getElementById('filterJenisLabel');
            if (jenisId === 'all') {
                label.textContent = 'Semua Jenis';
                showAllJenis();
            } else {
                const j = jenisData.find(function(item) { return item.id === jenisId; });
                label.textContent = j ? j.nama : 'Filter';
                showSingleJenis(jenisId);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            showAllJenis();
        });
    </script>
@stop
