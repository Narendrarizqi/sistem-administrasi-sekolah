@extends('adminlte::page')

@section('title', 'Edit Tagihan Kegiatan Intrakurikuler')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-pen text-success me-2"></i>
                Form Edit Data Tagihan Kegiatan Intrakurikuler (KI)
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

            <form action="{{ route('ki.update', $pembayaran->id) }}" method="POST">

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

                {{-- Target Pembayaran Per Sub-Kategori --}}
                <div class="border rounded p-3 mb-3 bg-light" style="border-radius: 10px;">
                    <div class="font-weight-bold text-dark mb-2" style="font-size: 13.5px;">
                        <i class="fas fa-list-check text-success mr-1"></i>
                        Edit Nominal Target Tiap Kegiatan:
                    </div>

                    {{-- 1. Target UTS --}}
                    <div class="form-group row mb-2">
                        <label for="edit_page_target_uts" class="col-sm-3 col-form-label font-weight-semibold">
                            Target UTS (Rp)
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                </div>
                                <input type="number"
                                       name="target_uts"
                                       id="edit_page_target_uts"
                                       class="form-control font-weight-bold text-success font-num ki-page-edit-input"
                                       value="{{ old('target_uts', (int)($pembayaran->target_uts ?? 0)) }}"
                                       min="0"
                                       step="1000"
                                       style="border-radius: 0 8px 8px 0;">
                            </div>
                        </div>
                    </div>

                    {{-- 2. Target UAS --}}
                    <div class="form-group row mb-2">
                        <label for="edit_page_target_uas" class="col-sm-3 col-form-label font-weight-semibold">
                            Target UAS (Rp)
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                </div>
                                <input type="number"
                                       name="target_uas"
                                       id="edit_page_target_uas"
                                       class="form-control font-weight-bold text-success font-num ki-page-edit-input"
                                       value="{{ old('target_uas', (int)($pembayaran->target_uas ?? 0)) }}"
                                       min="0"
                                       step="1000"
                                       style="border-radius: 0 8px 8px 0;">
                            </div>
                        </div>
                    </div>

                    {{-- 3. Target Ujian --}}
                    <div class="form-group row mb-0">
                        <label for="edit_page_target_ujian" class="col-sm-3 col-form-label font-weight-semibold">
                            Target Ujian (Rp)
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                </div>
                                <input type="number"
                                       name="target_ujian"
                                       id="edit_page_target_ujian"
                                       class="form-control font-weight-bold text-success font-num ki-page-edit-input"
                                       value="{{ old('target_ujian', (int)($pembayaran->target_ujian ?? 0)) }}"
                                       min="0"
                                       step="1000"
                                       style="border-radius: 0 8px 8px 0;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Summary Edit Box --}}
                <div class="fee-info-box my-3 p-3 bg-light border" style="border-radius: 8px;">
                    <div class="row align-items-center">
                        <div class="col-md-6 col-12 mb-2 mb-md-0">
                            <div class="text-muted small">Total Terbayar Saat Ini</div>
                            <div class="font-weight-bold text-success font-num">
                                Rp {{ number_format($pembayaran->detailPembayaran->sum('nominal'), 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="text-muted small">Total Target Tagihan Baru</div>
                            <div class="font-weight-bold text-dark font-num" style="font-size: 17px;" id="editPageSummaryTotalLabel">
                                Rp {{ number_format($pembayaran->target, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('ki.index') }}" class="btn btn-batal-merah px-4">
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

@section('js')
<script>
function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID').format(value || 0);
}

document.addEventListener('DOMContentLoaded', function () {
    const inputUts = document.getElementById('edit_page_target_uts');
    const inputUas = document.getElementById('edit_page_target_uas');
    const inputUjian = document.getElementById('edit_page_target_ujian');
    const summaryLabel = document.getElementById('editPageSummaryTotalLabel');

    function calculateEditPageTotal() {
        const uts = parseFloat(inputUts ? inputUts.value : 0) || 0;
        const uas = parseFloat(inputUas ? inputUas.value : 0) || 0;
        const ujian = parseFloat(inputUjian ? inputUjian.value : 0) || 0;
        const total = uts + uas + ujian;
        if (summaryLabel) {
            summaryLabel.textContent = `Rp ${formatRupiah(total)}`;
        }
    }

    document.querySelectorAll('.ki-page-edit-input').forEach(function (inp) {
        inp.addEventListener('input', calculateEditPageTotal);
    });
});
</script>
@stop
