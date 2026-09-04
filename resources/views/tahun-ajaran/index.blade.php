@extends('adminlte::page')

@section('title', 'Kelola Tahun Ajaran — Sistem Pembayaran')

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        /* ===============================================================
           SCOPED STYLES: HALAMAN KELOLA TAHUN AJARAN
           Tema Modern SaaS Sekolah Sesuai Standar Aplikasi
           =============================================================== */

        /* 1. Header Box */
        .ta-page-header {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .ta-header-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ta-header-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            margin-bottom: 4px;
        }

        .ta-header-breadcrumb a {
            color: #16a34a;
            font-weight: 600;
            text-decoration: none;
        }

        .ta-header-breadcrumb a:hover {
            color: #15803d;
            text-decoration: underline;
        }

        .ta-title {
            font-size: 17.5px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 0 0 2px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ta-title-icon {
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

        .ta-desc {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        .ta-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* 2. Tombol Aksi Utama (Sesuai Kesepakatan) */
        .btn-kembali-merah {
            background-color: #dc2626 !important;
            border: 1px solid #dc2626 !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 0 18px !important;
            height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.2) !important;
            transition: all 0.18s ease !important;
            cursor: pointer;
            text-decoration: none !important;
            white-space: nowrap;
        }

        .btn-kembali-merah:hover,
        .btn-kembali-merah:focus {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35) !important;
        }

        .btn-kembali-merah:active {
            background-color: #991b1b !important;
            border-color: #991b1b !important;
            color: #ffffff !important;
            transform: translateY(1px) !important;
            box-shadow: 0 1px 3px rgba(220, 38, 38, 0.25) !important;
        }

        .btn-tambah-hijau {
            background-color: #16a34a !important;
            border: 1px solid #16a34a !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 0 18px !important;
            height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.2) !important;
            transition: all 0.18s ease !important;
            cursor: pointer;
            text-decoration: none !important;
            white-space: nowrap;
        }

        .btn-tambah-hijau:hover,
        .btn-tambah-hijau:focus {
            background-color: #15803d !important;
            border-color: #15803d !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.35) !important;
        }

        .btn-tambah-hijau:active {
            background-color: #166534 !important;
            border-color: #166534 !important;
            color: #ffffff !important;
            transform: translateY(1px) !important;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.25) !important;
        }

        /* 3. Card & Table Container */
        .ta-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .ta-card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ta-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ta-badge-count {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 6px;
        }

        .ta-table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #taTable {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }

        #taTable thead th {
            background: #f8fafc !important;
            color: #334155 !important;
            font-weight: 650 !important;
            font-size: 12.5px;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0 !important;
            border-top: none;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }

        #taTable tbody td {
            padding: 13px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            font-size: 13px;
        }

        #taTable tbody tr:last-child td {
            border-bottom: none;
        }

        #taTable tbody tr:hover {
            background-color: #f8fafc;
        }

        #taTable tbody tr.row-active-ta {
            background-color: #f0fdf4 !important;
        }

        /* 4. Badges Sesuai Desain */
        .badge-status-aktif {
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

        .badge-status-inaktif {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 11.5px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-pill-tagihan {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 10.5px;
            font-weight: 600;
            padding: 2.5px 7px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap !important;
            gap: 4px;
        }

        .badge-pill-siswa {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 10.5px;
            font-weight: 600;
            padding: 2.5px 7px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap !important;
            gap: 4px;
        }

        /* 5. Tombol Aksi Tabel */
        .btn-act-aktifkan {
            height: 32px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d !important;
            font-size: 12px;
            font-weight: 600;
            border-radius: 7px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-act-aktifkan:hover {
            background: #16a34a;
            border-color: #16a34a;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(22, 163, 74, 0.25);
        }

        .btn-act-sedang-aktif {
            height: 32px;
            padding: 0 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #16a34a;
            border: 1px solid #16a34a;
            color: #ffffff !important;
            font-size: 12px;
            font-weight: 600;
            border-radius: 7px;
            cursor: default;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.2);
        }

        .btn-act-hapus {
            height: 32px;
            width: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626 !important;
            font-size: 12px;
            border-radius: 7px;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .btn-act-hapus:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220, 38, 38, 0.25);
        }

        /* 6. Modal Scoped */
        .modal-ta-content {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header-ta {
            background: #16a34a !important;
            color: #ffffff !important;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
            padding: 16px 20px;
            border-bottom: none !important;
        }

        .modal-header-ta .modal-title,
        .modal-header-ta h5,
        .modal-header-ta i {
            color: #ffffff !important;
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .modal-header-ta .close,
        .modal-header-ta .btn-close {
            color: #ffffff !important;
            opacity: 0.85 !important;
            text-shadow: none !important;
        }

        .modal-header-ta .close:hover,
        .modal-header-ta .btn-close:hover {
            opacity: 1 !important;
        }

        .form-label-custom {
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control-custom {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            padding: 9px 12px;
            transition: all 0.15s ease;
        }

        .form-control-custom:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
            outline: none;
        }
    </style>
@stop

@section('content')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #15803d; margin-bottom: 16px;">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #b91c1c; margin-bottom: 16px;">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border-left: 4px solid #b91c1c; margin-bottom: 16px;">
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

    {{-- 1. PAGE HEADER --}}
    <div class="ta-page-header">
        <div class="ta-header-wrapper">
            <div>
                <div class="ta-header-breadcrumb">
                    <i class="fas fa-home text-success"></i>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <i class="fas fa-chevron-right text-muted" style="font-size: 8.5px;"></i>
                    <span class="text-dark font-weight-bold">Tahun Ajaran</span>
                </div>
                <h1 class="ta-title">
                    <span class="ta-title-icon"><i class="fas fa-calendar-alt"></i></span>
                    Kelola Tahun Ajaran
                </h1>
                <p class="ta-desc">
                    Atur kalender akademik, status tahun ajaran aktif, dan arsip periode sekolah.
                </p>
            </div>
            <div class="ta-header-actions">
                <a href="{{ route('dashboard') }}" class="btn-kembali-merah">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="button" class="btn-tambah-hijau" data-toggle="modal" data-target="#modalTambahTA">
                    <i class="fas fa-plus mr-1"></i> Tambah Tahun Ajaran
                </button>
            </div>
        </div>
    </div>

    {{-- 2. CARD DAFTAR TAHUN AJARAN --}}
    <div class="ta-card">
        <div class="ta-card-header">
            <h5 class="ta-card-title">
                <i class="fas fa-list-ul text-success"></i>
                Daftar Tahun Ajaran
            </h5>
            <span class="ta-badge-count">
                <i class="far fa-calendar-check text-success mr-1"></i> Total {{ count($daftarTahunAjaran) }} Periode
            </span>
        </div>

        <div class="ta-table-wrap">
            <table class="table" id="taTable">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Tahun Ajaran</th>
                        <th>Periode</th>
                        <th class="text-center" style="width: 130px; white-space: nowrap;">Status</th>
                        <th class="text-center" style="width: 220px; white-space: nowrap;">Data Terkait</th>
                        <th class="text-center" style="width: 190px; white-space: nowrap;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($daftarTahunAjaran as $index => $item)
                    <tr class="{{ $item->is_active ? 'row-active-ta' : '' }}">
                        <td class="text-center text-muted font-weight-semibold">{{ $index + 1 }}</td>
                        <td>
                            <strong style="font-size: 14px; color: #0f172a;">{{ $item->nama }}</strong>
                            @if($item->is_active)
                                <span class="badge-status-aktif ml-2" style="font-size: 10.5px; padding: 2px 7px;">
                                    <i class="fas fa-check-circle" style="font-size: 10px;"></i> Aktif
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->tanggal_mulai && $item->tanggal_selesai)
                                <span style="color: #475569;">
                                    <i class="far fa-calendar-alt text-muted mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }} &mdash; 
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_active)
                                <span class="badge-status-aktif">
                                    <i class="fas fa-check-circle"></i> Tahun Aktif
                                </span>
                            @else
                                <span class="badge-status-inaktif">
                                    <i class="far fa-circle text-muted"></i> Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center" style="white-space: nowrap;">
                            <div class="d-inline-flex align-items-center justify-content-center" style="gap: 4px; flex-wrap: nowrap; white-space: nowrap;">
                                <span class="badge-pill-tagihan" title="Jumlah Tagihan Terdaftar">
                                    <i class="fas fa-receipt text-primary" style="font-size: 9.5px;"></i> {{ $item->pembayaran_count }} Tagihan
                                </span>
                                <span class="badge-pill-siswa" title="Jumlah Siswa Terdaftar">
                                    <i class="fas fa-user-graduate text-success" style="font-size: 9.5px;"></i> {{ $item->siswa_count }} Siswa
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                @if(!$item->is_active)
                                    <form action="{{ route('tahun-ajaran.activate', $item->id) }}" method="POST" class="d-inline mb-0">
                                        @csrf
                                        <button type="submit" class="btn-act-aktifkan" title="Jadikan Tahun Ajaran Aktif">
                                            <i class="fas fa-toggle-on"></i> Aktifkan
                                        </button>
                                    </form>
                                @else
                                    <span class="btn-act-sedang-aktif" title="Tahun Ajaran ini Sedang Aktif Digunakan">
                                        <i class="fas fa-check"></i> Sedang Aktif
                                    </span>
                                @endif

                                @if($item->pembayaran_count == 0 && !$item->is_active)
                                    <form action="{{ route('tahun-ajaran.destroy', $item->id) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran {{ $item->nama }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-act-hapus" title="Hapus Tahun Ajaran">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3 d-block" style="color: #cbd5e1;"></i>
                            <span style="font-size: 13.5px; font-weight: 500;">Belum ada data tahun ajaran. Silakan tambahkan tahun ajaran baru.</span>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. MODAL TAMBAH TAHUN AJARAN --}}
    <div class="modal fade" id="modalTambahTA" tabindex="-1" role="dialog" aria-labelledby="modalTambahTALabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-ta-content">
                <div class="modal-header modal-header-ta">
                    <h5 class="modal-title" id="modalTambahTALabel">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Tahun Ajaran Baru
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="form-group mb-3">
                            <label for="nama" class="form-label-custom">
                                Tahun Ajaran <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   class="form-control form-control-custom @error('nama') is-invalid @enderror" 
                                   placeholder="Contoh: 2026/2027" 
                                   value="{{ old('nama') }}" 
                                   required>
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle mr-1"></i> Format 4 digit tahun / 4 digit tahun (contoh: <strong>2026/2027</strong>).
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="tanggal_mulai" class="form-label-custom">Tanggal Mulai</label>
                                <input type="date" 
                                       name="tanggal_mulai" 
                                       id="tanggal_mulai" 
                                       class="form-control form-control-custom" 
                                       value="{{ old('tanggal_mulai') }}">
                                <small class="text-muted mt-1 d-block">Default: 1 Juli</small>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="tanggal_selesai" class="form-label-custom">Tanggal Selesai</label>
                                <input type="date" 
                                       name="tanggal_selesai" 
                                       id="tanggal_selesai" 
                                       class="form-control form-control-custom" 
                                       value="{{ old('tanggal_selesai') }}">
                                <small class="text-muted mt-1 d-block">Default: 30 Juni</small>
                            </div>
                        </div>

                        <div class="form-group mb-0 p-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-semibold text-dark" for="is_active" style="cursor: pointer; font-size: 13px;">
                                    Jadikan sebagai Tahun Ajaran Aktif
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1 pl-4" style="font-size: 11.5px;">
                                Status tahun ajaran lain akan otomatis diset menjadi tidak aktif.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top" style="border-bottom-left-radius: 14px; border-bottom-right-radius: 14px; display: flex; justify-content: flex-end; gap: 8px;">
                        <button type="button" class="btn-kembali-merah" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn-tambah-hijau">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
