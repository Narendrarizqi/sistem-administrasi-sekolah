@extends('adminlte::page')

@section('title', 'Edit Siswa')


@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-pen me-2"></i>
            Edit Data Siswa
        </h5>
    </div>

    <div class="card-body">

        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    NIS
                </label>

                <input
                    type="text"
                    name="nis"
                    class="form-control @error('nis') is-invalid @enderror"
                    value="{{ old('nis', $siswa->nis) }}"
                    maxlength="30"
                    required
                >

                @error('nis')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Nama Siswa
                </label>

                <input
                    type="text"
                    name="nama"
                    class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama', $siswa->nama) }}"
                    maxlength="255"
                    required
                >

                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Kelas
                </label>

                <select
                    name="kelas"
                    class="form-control @error('kelas') is-invalid @enderror"
                    required
                >
                    <option value="" disabled {{ old('kelas', $siswa->kelas) ? '' : 'selected' }}>
                        Pilih kelas
                    </option>
                    <option value="X TKJ" {{ old('kelas', $siswa->kelas) == 'X TKJ' ? 'selected' : '' }}>X TKJ</option>
                    <option value="X TKR 1" {{ old('kelas', $siswa->kelas) == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
<option value="X TKR 2" {{ old('kelas', $siswa->kelas) == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                    <option value="XI TKJ" {{ old('kelas', $siswa->kelas) == 'XI TKJ' ? 'selected' : '' }}>XI TKJ</option>
                    <option value="XI TKR 1" {{ old('kelas', $siswa->kelas) == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
<option value="XI TKR 2" {{ old('kelas', $siswa->kelas) == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                    <option value="XII TKJ" {{ old('kelas', $siswa->kelas) == 'XII TKJ' ? 'selected' : '' }}>XII TKJ</option>
                    <option value="XII TKR 1" {{ old('kelas', $siswa->kelas) == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
<option value="XII TKR 2" {{ old('kelas', $siswa->kelas) == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                </select>

                @error('kelas')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">

                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali
                </a>

                <button type="submit" class="btn btn-add">
                    <i class="fas fa-save me-1"></i>
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@stop