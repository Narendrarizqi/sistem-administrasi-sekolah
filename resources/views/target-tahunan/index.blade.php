@extends('adminlte::page')

@section('title', 'Target Tahunan')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        .target-year-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .06);
        }

        .target-year-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .target-year-select {
            min-width: 190px;
            border-radius: 10px;
        }

        .btn-delete-year {
            border-radius: 10px;
        }

        .target-table th,
        .target-table td {
            vertical-align: middle;
        }

        .target-table-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .target-table {
            min-width: 1050px;
        }

        .target-table th.text-right,
        .target-table td.text-right {
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .target-year-select {
                width: 100%;
            }
        }
    </style>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-circle-check mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-circle-exclamation mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{-- ========================================================= --}}
    {{-- PILIH TAHUN AJARAN --}}
    {{-- ========================================================= --}}

    {{-- PAGE HEADER & BREADCRUMB INDICATOR --}}
    <div class="page-header-box">
        <div>
            <div class="page-header-breadcrumb">
                <i class="fas fa-home text-success"></i>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i>
                <span class="text-dark font-weight-bold">Target Tahunan</span>
            </div>
            <h1 class="page-header-title">
                <span class="page-header-icon"><i class="fas fa-bullseye"></i></span>
                Target Pendapatan Tahunan
            </h1>
            <p class="page-header-desc">
                Rekap target penerimaan sekolah berdasarkan tagihan siswa yang terdaftar aktif di sistem.
            </p>
        </div>
        <div class="page-header-badges">
            <span class="badge-page-indicator">
                <i class="fas fa-calendar-check"></i>
                Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
            </span>
            <span class="badge bg-light text-secondary border px-3 py-2 font-weight-semibold" style="border-radius: 20px; font-size: 12px;">
                <i class="fas fa-coins text-muted mr-1"></i> Target: Rp {{ number_format($totalTarget, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <div class="card target-year-card mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
                Tahun Ajaran
            </h5>
        </div>

        <div class="card-body">
            @if($daftarTahunAjaran->isEmpty())
                <div class="text-muted">
                    Belum ada tahun ajaran. Tahun ajaran akan muncul otomatis ketika tagihan dibuat.
                </div>
            @else
                <div class="target-year-actions">
                    <form action="{{ route('target-tahunan.index') }}" method="GET" class="d-flex align-items-center">
                        <select
                            name="tahun_ajaran"
                            class="form-control target-year-select"
                            onchange="this.form.submit()"
                        >
                            @foreach($daftarTahunAjaran as $ta)
                                <option value="{{ $ta }}" {{ $tahunAjaran === $ta ? 'selected' : '' }}>
                                    {{ $ta }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    @if($tahunAjaran)
                        <form
                            action="{{ route('target-tahunan.destroy') }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Hapus registrasi tahun ajaran {{ $tahunAjaran }}? Jika tahun ajaran masih memiliki tagihan atau pembayaran, penghapusan akan ditolak.');"
                        >
                            @csrf
                            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
                            <button type="submit" class="btn btn-outline-danger btn-delete-year">
                                <i class="fas fa-trash mr-1"></i>
                                Hapus Tahun Ajaran
                            </button>
                        </form>
                    @endif
                </div>

                <small class="text-muted d-block mt-2">
                    Target dihitung otomatis dari total tagihan yang benar-benar terdaftar pada tahun ajaran yang dipilih.
                </small>
            @endif
        </div>
    </div>

    @if($tahunAjaran)

        {{-- ========================================================= --}}
        {{-- CARD RINGKASAN --}}
        {{-- ========================================================= --}}

        <div class="row g-4 mb-4">

            <div class="col-12 col-md-3">
                <div class="stat-card stat-pastel-blue">
                    <div class="stat-top">
                        <div>
                            <span class="stat-label">Target Tahunan</span>
                            <h3 class="stat-value">
                                Rp {{ number_format($totalTarget, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Total seluruh tagihan tahun ini</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="stat-card stat-pastel-green">
                    <div class="stat-top">
                        <div>
                            <span class="stat-label">Sudah Masuk</span>
                            <h3 class="stat-value">
                                Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Pembayaran untuk tagihan tahun ini</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="stat-card stat-pastel-orange">
                    <div class="stat-top">
                        <div>
                            <span class="stat-label">Belum Masuk</span>
                            <h3 class="stat-value">
                                Rp {{ number_format($totalBelumMasuk, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Sisa target dari tagihan tahun ini</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="stat-card stat-pastel-red">
                    <div class="stat-top">
                        <div>
                            <span class="stat-label">Terbawa Tahun Lalu</span>
                            <h3 class="stat-value">
                                Rp {{ number_format($totalTerbawa, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Kewajiban tahun sebelumnya yang belum lunas</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-rotate-left"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- PENGELUARAN DAN SALDO --}}
        {{-- ========================================================= --}}

        <div class="row g-4 mb-4">

            <div class="col-12 col-md-3">
                <div class="stat-card stat-pastel-red">
                    <div class="stat-top">
                        <div>
                            <span class="stat-label">Pengeluaran Tahun Ini</span>
                            <h3 class="stat-value">
                                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Pengeluaran berdasarkan sumber dana</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="stat-card stat-pastel-blue">
                    <div class="stat-top">
                        <div>
                            <span class="stat-label">Saldo Tersedia</span>
                            <h3 class="stat-value">
                                Rp {{ number_format($totalSaldoTersedia, 0, ',', '.') }}
                            </h3>
                            <span class="stat-desc">Sudah masuk dikurangi pengeluaran</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- TABEL RINCIAN --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
                    Rincian per Jenis Pembayaran — {{ $tahunAjaran }}
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive target-table-wrap">
                    <table class="table table-hover align-middle target-table">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th class="text-right">Target Tahunan (Rp)</th>
                                <th class="text-right">Sudah Masuk (Rp)</th>
                                <th class="text-right">Belum Masuk (Rp)</th>
                                <th class="text-right">Terbawa Tahun Lalu (Rp)</th>
                                <th class="text-right">Pengeluaran (Rp)</th>
                                <th class="text-right">Saldo Tersedia (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ringkasan as $r)
                                <tr>
                                    <td class="fw-semibold">{{ $r['jenis']->nama }}</td>
                                    <td class="text-right">
                                        {{ number_format($r['target'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right text-success fw-semibold">
                                        {{ number_format($r['sudah_masuk'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right text-warning">
                                        {{ number_format($r['belum_masuk'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right text-danger">
                                        {{ number_format($r['tagihan_terbawa'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right text-danger">
                                        {{ number_format($r['pengeluaran'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right fw-semibold {{ $r['saldo_tersedia'] < 0 ? 'text-danger' : 'text-primary' }}">
                                        {{ number_format($r['saldo_tersedia'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif

@stop