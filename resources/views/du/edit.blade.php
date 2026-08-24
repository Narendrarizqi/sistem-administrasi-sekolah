@extends('adminlte::page')

@section('title', 'Edit Tagihan Daftar Ulang')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-pen text-success me-2"></i>
                Form Edit Data Tagihan Daftar Ulang (DU)
            </h5>
        </div>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger mb-3 py-2 px-3" style="border-radius: 8px;">
                    <strong>Data belum dapat disimpan:</strong>
                    <ul class="mb-0 mt-1 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('du.update', $pembayaran->id) }}" method="POST">

                @csrf
                @method('PUT')

                {{-- Siswa --}}
                <div class="mb-3">
                    <label for="siswa_id" class="form-label fw-semibold">
                        Siswa <span class="text-danger">*</span>
                    </label>

                    <select name="siswa_id"
                            id="siswa_id"
                            class="form-control @error('siswa_id') is-invalid @enderror"
                            style="border-radius: 8px;"
                            required>

                        <option value="" disabled>-- Pilih Siswa --</option>

                        @foreach($siswa as $item)
                            <option value="{{ $item->id }}"
                                {{ old('siswa_id', $pembayaran->siswa_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nis }} - {{ $item->nama }} ({{ $item->kelas }})
                            </option>
                        @endforeach

                    </select>

                    @error('siswa_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Info Terbawa Tahun Lalu --}}
                @if((float) ($pembayaran->belum_lunas ?? 0) > 0)
                    <div class="alert alert-warning mb-3 py-2 px-3 small" style="border-radius: 8px;">
                        <div class="fw-bold">
                            <i class="fas fa-history me-1"></i> Tagihan Terbawa Tahun Lalu: Rp {{ number_format($pembayaran->belum_lunas, 0, ',', '.') }}
                        </div>
                        <div class="text-muted mt-1" style="font-size: 11.5px;">
                            Tagihan terbawa ini tetap tersimpan dan otomatis ditambahkan ke target baru. Total tagihan siswa akan menjadi <strong>Target Baru + Tagihan Terbawa</strong>.
                        </div>
                    </div>
                @endif

                {{-- Target Pembayaran --}}
                <div class="mb-4">
                    <label for="target" class="form-label fw-semibold">
                        Target Tagihan (Tahun Ini) <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold bg-light">Rp</span>
                        </div>
                        <input type="number"
                               name="target"
                               id="target"
                               class="form-control font-weight-bold text-success @error('target') is-invalid @enderror"
                               value="{{ old('target', (int)$pembayaran->target) }}"
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

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center mt-4">

                    <a href="{{ route('du.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-add px-4">
                        <i class="fas fa-save me-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>
    </div>

@stop