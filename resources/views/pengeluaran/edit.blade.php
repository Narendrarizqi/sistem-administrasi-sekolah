@extends('adminlte::page')

@section('title', 'Edit Pengeluaran')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-pen text-success me-2"></i>
                Form Pengeluaran
            </h5>
        </div>

        <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST">

            @csrf
            @method('PUT')

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
                        <label for="tanggal" class="form-label fw-semibold">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            id="tanggal"
                            class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', \Carbon\Carbon::parse($pengeluaran->tanggal)->format('Y-m-d')) }}"
                            required
                        >

                        @error('tanggal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sumber_dana" class="form-label fw-semibold">
                            Sumber Dana
                        </label>

                        <select
                            name="sumber_dana"
                            id="sumber_dana"
                            class="form-control @error('sumber_dana') is-invalid @enderror"
                            required
                        >
                            <option value="" disabled {{ old('sumber_dana', $pengeluaran->sumber_dana) ? '' : 'selected' }}>
                                -- Pilih Sumber Dana --
                            </option>
                            @foreach(['IPP', 'DU', 'Sarpras', 'KI'] as $sumber)
                                <option value="{{ $sumber }}" {{ old('sumber_dana', $pengeluaran->sumber_dana) === $sumber ? 'selected' : '' }}>
                                    {{ $sumber === 'DU' ? 'Daftar Ulang (DU)' : ($sumber === 'KI' ? 'Kegiatan Intrakurikuler (KI)' : $sumber) }}
                                </option>
                            @endforeach
                        </select>

                        @error('sumber_dana')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label for="nominal" class="form-label fw-semibold">
                            Nominal (Rp)
                        </label>

                        <input
                            type="number"
                            name="nominal"
                            id="nominal"
                            class="form-control @error('nominal') is-invalid @enderror"
                            value="{{ old('nominal', $pengeluaran->nominal) }}"
                            min="1"
                            step="1"
                            required
                        >

                        @error('nominal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label fw-semibold">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        id="keterangan"
                        rows="3"
                        class="form-control @error('keterangan') is-invalid @enderror"
                        maxlength="500"
                        required
                    >{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>

                    @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="card-footer bg-white border-0 mt-2">

                <div style="display: flex; width: 100%; align-items: center;">

                    <a
                        href="{{ route('pengeluaran.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-add"
                        style="margin-left: auto;"
                    >
                        <i class="fas fa-save me-1"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </div>

        </form>

    </div>

@stop