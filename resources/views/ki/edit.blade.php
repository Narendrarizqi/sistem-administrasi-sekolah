@extends('adminlte::page')

@section('title', 'Edit Tagihan Asesmen')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-pen text-success me-2"></i>
                Form Edit Data Tagihan Asesmen
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
                    <label class="form-label fw-semibold">
                        Siswa
                    </label>
                    <input type="text" class="form-control bg-light" value="{{ $pembayaran->siswa->nis ?? '-' }} - {{ $pembayaran->siswa->nama ?? '-' }} ({{ $pembayaran->siswa->kelas ?? '-' }})" readonly style="border-radius: 8px;">
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

                {{-- Target Pembayaran Per Sub-Kategori Dinamis --}}
                <div class="border rounded p-3 mb-3 bg-light" style="border-radius: 10px;">
                    <div class="font-weight-bold text-dark mb-2" style="font-size: 13.5px;">
                        <i class="fas fa-list-check text-success mr-1"></i>
                        Nominal Item Tagihan Asesmen Siswa Ini:
                    </div>

                    @forelse($pembayaran->itemsKi as $itKi)
                        <div class="form-group row mb-2 align-items-center">
                            <label class="col-sm-4 col-form-label font-weight-semibold">
                                {{ $itKi->nama_iuran }} <span class="text-muted small">(Rp)</span>
                            </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-white">Rp</span>
                                    </div>
                                    <input type="number"
                                           name="items[{{ $itKi->id }}][nominal]"
                                           class="form-control font-weight-bold text-success font-num ki-page-item-input"
                                           value="{{ (int)$itKi->nominal }}"
                                           min="0"
                                           step="1000"
                                           style="border-radius: 0 8px 8px 0;">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small mb-2">Belum ada item spesifik. Menggunakan target global: Rp {{ number_format($pembayaran->target, 0, ',', '.') }}</div>
                    @endforelse

                    @php
                        $assignedNames = $pembayaran->itemsKi->pluck('nama_iuran')->toArray();
                        $unassignedActive = ($jenisIuranAktif ?? collect())->whereNotIn('nama', $assignedNames);
                    @endphp

                    @if($unassignedActive->isNotEmpty())
                        <hr class="my-3">
                        <div class="font-weight-bold text-primary mb-2" style="font-size: 12.5px;">
                            <i class="fas fa-plus-circle mr-1"></i> Tambahkan Jenis Iuran Lain ke Siswa Ini:
                        </div>
                        @foreach($unassignedActive as $uIuran)
                            <div class="form-group row mb-2 align-items-center">
                                <div class="col-sm-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               name="new_iuran_ids[]"
                                               value="{{ $uIuran->id }}"
                                               id="page_new_iuran_{{ $uIuran->id }}"
                                               class="custom-control-input"
                                               onchange="const inp = document.getElementById('page_new_nom_{{ $uIuran->id }}'); inp.disabled = !this.checked; if(this.checked) inp.focus();">
                                        <label class="custom-control-label font-weight-semibold" for="page_new_iuran_{{ $uIuran->id }}" style="cursor: pointer;">
                                            {{ $uIuran->nama }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light font-weight-bold">Rp</span>
                                        </div>
                                        <input type="number"
                                               name="new_nominals[{{ $uIuran->id }}]"
                                               id="page_new_nom_{{ $uIuran->id }}"
                                               class="form-control font-weight-bold text-primary font-num"
                                               value="{{ (int)$uIuran->nominal_default }}"
                                               min="0"
                                               step="1000"
                                               disabled>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
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
                            <div class="text-muted small">Total Target Tagihan Saat Ini</div>
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
    const summaryLabel = document.getElementById('editPageSummaryTotalLabel');

    function calculateEditPageTotal() {
        let total = 0;
        document.querySelectorAll('.ki-page-item-input').forEach(function (inp) {
            total += parseFloat(inp.value || 0);
        });
        if (summaryLabel) {
            summaryLabel.textContent = `Rp ${formatRupiah(total)}`;
        }
    }

    document.querySelectorAll('.ki-page-item-input').forEach(function (inp) {
        inp.addEventListener('input', calculateEditPageTotal);
    });
});
</script>
@stop
