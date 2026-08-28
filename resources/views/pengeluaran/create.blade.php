@extends('adminlte::page')

@section('title', 'Tambah Pengeluaran')

@section('content')

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">

        <div class="card-header-clean d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold" style="font-size: 16px;">
                <i class="fas fa-plus-circle text-success mr-2"></i>
                Form Tambah Data Pengeluaran
            </h5>
        </div>

        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf

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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal" class="form-label font-weight-bold">
                            Tanggal Pengeluaran <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal"
                            id="tanggal"
                            class="form-control @error('tanggal') is-invalid @enderror"
                            style="border-radius: 8px;"
                            value="{{ old('tanggal', date('Y-m-d')) }}"
                            required
                        >
                        @error('tanggal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sumber_dana" class="form-label font-weight-bold">
                            Sumber Dana <span class="text-danger">*</span>
                        </label>
                        <select
                            name="sumber_dana"
                            id="sumber_dana"
                            class="form-control font-weight-semibold @error('sumber_dana') is-invalid @enderror"
                            style="border-radius: 8px;"
                            required
                        >
                            <option value="" disabled {{ old('sumber_dana') ? '' : 'selected' }}>
                                -- Pilih Sumber Dana --
                            </option>
                            <option value="IPP" {{ old('sumber_dana') === 'IPP' ? 'selected' : '' }}>IPP (Iuran Pembayaran Pendidikan)</option>
                            <option value="DU" {{ old('sumber_dana') === 'DU' ? 'selected' : '' }}>DU (Daftar Ulang)</option>
                            <option value="Sarpras" {{ old('sumber_dana') === 'Sarpras' ? 'selected' : '' }}>Sarpras (Sarana Prasarana)</option>
                            <option value="KI" {{ old('sumber_dana') === 'KI' ? 'selected' : '' }}>KI (Kegiatan Intrakurikuler)</option>
                            <option value="BOS" {{ old('sumber_dana') === 'BOS' ? 'selected' : '' }}>BOS (Bantuan Operasional Sekolah)</option>
                        </select>
                        @error('sumber_dana')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="nominal" class="form-label font-weight-bold">
                        Nominal Pengeluaran <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold bg-light">Rp</span>
                        </div>
                        <input
                            type="number"
                            name="nominal"
                            id="nominal"
                            class="form-control font-weight-bold text-danger font-num @error('nominal') is-invalid @enderror"
                            value="{{ old('nominal') }}"
                            placeholder="Masukkan nominal pengeluaran..."
                            min="1"
                            step="1"
                            style="border-radius: 0 8px 8px 0;"
                            required
                        >
                    </div>
                    @error('nominal')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-0">
                    <label for="keterangan" class="form-label font-weight-bold">
                        Keterangan / Keperluan <span class="text-danger">*</span>
                    </label>
                    <textarea
                        name="keterangan"
                        id="keterangan"
                        rows="3"
                        class="form-control @error('keterangan') is-invalid @enderror"
                        style="border-radius: 8px;"
                        placeholder="Contoh: Pembelian alat tulis kantor, biaya perbaikan sarana, konsumsi kegiatan..."
                        maxlength="500"
                        required
                    >{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="card-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('pengeluaran.index') }}" class="btn btn-batal-merah px-4">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali
                </a>

                <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;">
                    <i class="fas fa-save mr-1"></i>
                    Simpan Pengeluaran
                </button>
            </div>

        </form>

    </div>

@stop