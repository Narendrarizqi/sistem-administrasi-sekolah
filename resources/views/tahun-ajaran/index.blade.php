@extends('adminlte::page')

@section('title', 'Kelola Tahun Ajaran')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

{{-- PAGE HEADER & BREADCRUMB INDICATOR --}}
<div class="page-header-box">
    <div>
        <div class="page-header-breadcrumb">
            <i class="fas fa-home text-success"></i>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i>
            <span class="text-dark font-weight-bold">Tahun Ajaran</span>
        </div>
        <h1 class="page-header-title">
            <span class="page-header-icon"><i class="fas fa-calendar-alt"></i></span>
            Kelola Tahun Ajaran
        </h1>
        <p class="page-header-desc">
            Atur kalender akademik, status tahun ajaran aktif, dan arsip periode sekolah.
        </p>
    </div>
    <div class="page-header-badges">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary font-weight-semibold px-3 py-2" style="border-radius: 8px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
        </a>
        <button type="button" class="btn btn-sm btn-add px-3 py-2" data-toggle="modal" data-target="#modalTambahTA" style="border-radius: 8px;">
            <i class="fas fa-plus mr-1"></i> Tambah Tahun Ajaran
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Tabel Daftar Tahun Ajaran --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold">
            Daftar Tahun Ajaran
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-success">
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>Tahun Ajaran</th>
                        <th>Periode</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Data Terkait</th>
                        <th class="text-center" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($daftarTahunAjaran as $index => $item)
                    <tr class="{{ $item->is_active ? 'table-light fw-medium' : '' }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong style="font-size: 15px;">{{ $item->nama }}</strong>
                            @if($item->is_active)
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->tanggal_mulai && $item->tanggal_selesai)
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }} - 
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->is_active)
                                <span class="badge bg-success">Tahun Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark me-1" title="Jumlah Tagihan">
                                <i class="fas fa-receipt me-1 text-primary"></i>{{ $item->pembayaran_count }} Tagihan
                            </span>
                            <span class="badge bg-light text-dark" title="Jumlah Siswa Terdaftar">
                                <i class="fas fa-user-graduate me-1 text-success"></i>{{ $item->siswa_count }} Siswa
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                @if(!$item->is_active)
                                    <form action="{{ route('tahun-ajaran.activate', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Jadikan Tahun Aktif">
                                            <i class="fas fa-toggle-on me-1"></i> Aktifkan
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-success disabled" disabled>
                                        <i class="fas fa-check me-1"></i> Sedang Aktif
                                    </button>
                                @endif

                                @if($item->pembayaran_count == 0 && !$item->is_active)
                                    <form action="{{ route('tahun-ajaran.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran {{ $item->nama }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Tahun Ajaran">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-xmark fa-2x mb-2 d-block opacity-25"></i>
                            Belum ada data tahun ajaran.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Tahun Ajaran --}}
<div class="modal fade" id="modalTambahTA" tabindex="-1" aria-labelledby="modalTambahTALabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalTambahTALabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Tahun Ajaran Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama" 
                               id="nama" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               placeholder="Contoh: 2026/2027" 
                               value="{{ old('nama') }}" 
                               required>
                        <small class="text-muted">Gunakan format 4 digit tahun / 4 digit tahun (contoh: 2026/2027).</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" 
                                   name="tanggal_mulai" 
                                   id="tanggal_mulai" 
                                   class="form-control" 
                                   value="{{ old('tanggal_mulai') }}">
                            <small class="text-muted">Default: 1 Juli</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" 
                                   name="tanggal_selesai" 
                                   id="tanggal_selesai" 
                                   class="form-control" 
                                   value="{{ old('tanggal_selesai') }}">
                            <small class="text-muted">Default: 30 Juni</small>
                        </div>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Jadikan sebagai Tahun Ajaran Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
