@extends('adminlte::page')

@section('title', 'Tambah Tagihan Daftar Ulang (DU)')

@section('content')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="card shadow-sm border-0">

                <div class="card-header card-header-payment py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 font-weight-bold" style="font-size: 16px;">
                            <i class="fas fa-id-card mr-2"></i>
                            Tambah Tagihan Daftar Ulang (DU)
                        </h5>
                        <span class="badge badge-light px-3 py-1 font-weight-bold text-success" style="font-size: 12px;">
                            Daftar Ulang
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('du.store') }}" method="POST">
                        @csrf

                        {{-- Alert Jika Siswa Duplikat --}}
                        <div id="siswaDuplicateAlert" class="alert alert-danger d-none mb-3 py-2 px-3">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <strong>Peringatan:</strong> Siswa ini sudah memiliki data tagihan Daftar Ulang (DU) pada tahun ajaran ini. Tidak dapat menambahkan tagihan ganda!
                        </div>

                        {{-- Siswa --}}
                        <div class="form-group row mb-4">
                            <label for="siswa_id" class="col-sm-3 col-form-label font-weight-bold">
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select name="siswa_id"
                                        id="siswa_id"
                                        class="form-control @error('siswa_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Siswa yang Ditagihkan --</option>
                                    @foreach($siswa as $item)
                                        @php $isAlreadyTagged = in_array($item->id, $existingSiswaIds ?? []); @endphp
                                        <option value="{{ $item->id }}"
                                                data-nis="{{ $item->nis }}"
                                                data-nama="{{ $item->nama }}"
                                                data-kelas="{{ $item->kelas }}"
                                                data-exists="{{ $isAlreadyTagged ? '1' : '0' }}"
                                                {{ $isAlreadyTagged ? 'disabled' : '' }}
                                                {{ old('siswa_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nis }} - {{ $item->nama }} ({{ $item->kelas }}) {{ $isAlreadyTagged ? '— [Sudah Ada Tagihan]' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('siswa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="selectedSiswaBox" class="mt-2 p-2 bg-light border rounded small d-none">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-muted">Nama:</span> <strong id="previewNama">-</strong>
                                            <span class="mx-2">|</span>
                                            <span class="text-muted">NIS:</span> <span id="previewNis" class="font-weight-bold">-</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-light text-dark border" id="previewKelas">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Target Nominal --}}
                        <div class="form-group row mb-4">
                            <label for="target" class="col-sm-3 col-form-label font-weight-bold">
                                Target Tagihan <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light">Rp</span>
                                    </div>
                                    <input type="number"
                                           name="target"
                                           id="target"
                                           class="form-control @error('target') is-invalid @enderror"
                                           value="{{ old('target', '') }}"
                                           placeholder="Masukkan nominal target tagihan..."
                                           min="0"
                                           step="1000"
                                           required>
                                </div>
                                @error('target')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Total target biaya Daftar Ulang yang harus dibayarkan.</small>
                            </div>
                        </div>

                        {{-- Ringkasan Box --}}
                        <div class="fee-info-box p-3 mb-4 rounded border">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Jenis Tagihan</div>
                                    <div class="font-weight-bold text-dark">
                                        Daftar Ulang Siswa
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="text-muted small">Total Target Disimpan</div>
                                    <div class="font-weight-bold text-success" style="font-size: 18px;" id="summaryTotalLabel">
                                        Rp 0
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Footer --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('du.index') }}" class="btn btn-secondary px-3">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>

                            <button type="submit" id="btnSubmitDu" class="btn btn-success px-4 font-weight-bold">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Tagihan DU
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const siswaSelect = document.getElementById('siswa_id');
            const selectedSiswaBox = document.getElementById('selectedSiswaBox');
            const previewNama = document.getElementById('previewNama');
            const previewNis = document.getElementById('previewNis');
            const previewKelas = document.getElementById('previewKelas');
            const duplicateAlert = document.getElementById('siswaDuplicateAlert');
            const submitBtn = document.getElementById('btnSubmitDu');

            const inputTarget = document.getElementById('target');
            const summaryTotalLabel = document.getElementById('summaryTotalLabel');

            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID').format(value || 0);
            }

            function updateStudentPreview() {
                const selectedOption = siswaSelect.options[siswaSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    previewNama.textContent = selectedOption.dataset.nama || '-';
                    previewNis.textContent = selectedOption.dataset.nis || '-';
                    previewKelas.textContent = selectedOption.dataset.kelas || '-';
                    selectedSiswaBox.classList.remove('d-none');

                    if (selectedOption.dataset.exists === '1') {
                        if (duplicateAlert) duplicateAlert.classList.remove('d-none');
                        if (submitBtn) submitBtn.disabled = true;
                    } else {
                        if (duplicateAlert) duplicateAlert.classList.add('d-none');
                        if (submitBtn) submitBtn.disabled = false;
                    }
                } else {
                    selectedSiswaBox.classList.add('d-none');
                    if (duplicateAlert) duplicateAlert.classList.add('d-none');
                    if (submitBtn) submitBtn.disabled = false;
                }
            }

            siswaSelect.addEventListener('change', updateStudentPreview);
            updateStudentPreview();

            inputTarget.addEventListener('input', function () {
                const val = parseFloat(this.value) || 0;
                summaryTotalLabel.textContent = `Rp ${formatRupiah(val)}`;
            });
        });
    </script>
@stop