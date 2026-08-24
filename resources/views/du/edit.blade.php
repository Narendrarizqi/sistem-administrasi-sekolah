@extends('adminlte::page')

@section('title','Edit Pembayaran Daftar Ulang')


@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Terjadi kesalahan:</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-pen text-success me-2"></i>
                Form Edit Tagihan Daftar Ulang
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('du.update', $pembayaran->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="siswa_id" class="form-label fw-semibold">
                        Siswa
                    </label>

                    <select name="siswa_id"
                            id="siswa_id"
                            class="form-control @error('siswa_id') is-invalid @enderror"
                            required>

                        @foreach($siswa as $item)
                            <option value="{{ $item->id }}"
                                {{ old('siswa_id', $pembayaran->siswa_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nis }} - {{ $item->nama }}
                            </option>
                        @endforeach

                    </select>

                    @error('siswa_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                @if((float) ($pembayaran->belum_lunas ?? 0) > 0)
                    <div class="alert alert-warning mb-3">
                        <div class="fw-bold">
                            <i class="fas fa-history me-1"></i> Tagihan Terbawa Tahun Lalu: Rp {{ number_format($pembayaran->belum_lunas, 0, ',', '.') }}
                        </div>
                        <div class="small text-muted mt-1">
                            Tagihan terbawa ini tetap tersimpan dan otomatis ditambahkan ke target baru. Total tagihan siswa akan menjadi <strong>Target Baru + Tagihan Terbawa</strong>.
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="target" class="form-label fw-semibold">
                        Target Pembayaran (Tahun Ini)
                    </label>

                    <input type="number"
                           name="target"
                           id="target"
                           class="form-control @error('target') is-invalid @enderror"
                           value="{{ old('target', $pembayaran->target) }}"
                           required>

                    @error('target')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('du.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-add">
                        <i class="fas fa-save me-1"></i>
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>

@stop