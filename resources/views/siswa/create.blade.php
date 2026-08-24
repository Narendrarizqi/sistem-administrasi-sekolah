@extends('adminlte::page')

@section('title', 'Tambah Siswa')


@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-user-plus text-success me-2"></i>
                Form Data Siswa
            </h5>
        </div>

        <form action="{{ route('siswa.store') }}" method="POST">

            @csrf

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Data belum dapat disimpan.</strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="nis" class="form-label fw-semibold">
                            NIS
                        </label>

                        <input
                            type="text"
                            name="nis"
                            id="nis"
                            class="form-control @error('nis') is-invalid @enderror"
                            value="{{ old('nis') }}"
                            placeholder="Masukkan NIS"
                            maxlength="30"
                            required
                        >

                        @error('nis')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kelas" class="form-label fw-semibold">
                            Kelas
                        </label>

                        <select
                            name="kelas"
                            id="kelas"
                            class="form-control @error('kelas') is-invalid @enderror"
                            required
                        >
                            <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>
                                Pilih kelas
                            </option>

                            <option value="X TKJ" {{ old('kelas') == 'X TKJ' ? 'selected' : '' }}>
                                X TKJ
                            </option>

                            <option value="X TKR 1" {{ old('kelas') == 'X TKR 1' ? 'selected' : '' }}>
                                X TKR 1
                            </option>
<option value="X TKR 2" {{ old('kelas') == 'X TKR 2' ? 'selected' : '' }}>
                                X TKR 2
                            </option>
                            <option value="XI TKJ" {{ old('kelas') == 'XI TKJ' ? 'selected' : '' }}>
                                XI TKJ
                            </option>

                            <option value="XI TKR 1" {{ old('kelas') == 'XI TKR 1' ? 'selected' : '' }}>
                                XI TKR 1
                            </option>
<option value="XI TKR 2" {{ old('kelas') == 'XI TKR 2' ? 'selected' : '' }}>
                                XI TKR 2
                            </option>

                            <option value="XII TKJ" {{ old('kelas') == 'XII TKJ' ? 'selected' : '' }}>
                                XII TKJ
                            </option>

                            <option value="XII TKR 1" {{ old('kelas') == 'XII TKR 1' ? 'selected' : '' }}>
                                XII TKR 1
                            </option>
<option value="XII TKR 2" {{ old('kelas') == 'XII TKR 2' ? 'selected' : '' }}>
                                XII TKR 2
                            </option>
                        </select>

                        @error('kelas')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}"
                        placeholder="Masukkan nama lengkap siswa"
                        maxlength="255"
                        required
                    >

                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    
                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-add">
                        <i class="fas fa-save me-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </div>

        </form>

    </div>

@stop