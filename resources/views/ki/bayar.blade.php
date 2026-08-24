@extends('adminlte::page')

@section('title', 'Pembayaran Kegiatan Intrakurikuler')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .card-header-payment {
            background: #16a34a !important;
            color: #ffffff !important;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .card-header-payment .modal-title,
        .card-header-payment h5 {
            color: #ffffff !important;
        }
        .payment-mode-box {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .payment-mode-box label {
            margin-bottom: 0;
            cursor: pointer;
            font-weight: 500;
        }
        .fee-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .custom-file-upload {
            position: relative;
            width: 100%;
        }
        .file-input-hidden {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }
        .file-upload-label {
            margin-bottom: 0;
            cursor: pointer;
            border: 1.5px dashed #cbd5e1 !important;
            border-radius: 8px;
            background: #ffffff;
            transition: all 0.2s ease;
            height: 40px;
        }
        .file-upload-label:hover {
            border-color: #16a34a !important;
            background: #f0fdf4;
        }
        .subtagihan-select-box {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 8px;
            padding: 10px 14px;
        }
    </style>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px;">
        <i class="fas fa-check-circle mr-1"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px;">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-1 pl-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@php
$terbayar = (float) $pembayaran->detailPembayaran->sum('nominal');
$terbawa = (float) ($pembayaran->belum_lunas ?? 0);
$totalTagihan = (float) $pembayaran->target + $terbawa;
$sisa = max($totalTagihan - $terbayar, 0);
$persen = $totalTagihan > 0 ? min(($terbayar / $totalTagihan) * 100, 100) : 0;
$sisaTerbawa = max($terbawa - $terbayar, 0);
$bulanIndoList = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$subUts = $pembayaran->statusKiSubtagihan()['UTS'];
$subUas = $pembayaran->statusKiSubtagihan()['UAS'];
$subUjian = $pembayaran->statusKiSubtagihan()['Ujian'];

// Tentukan default kategori aktif (prioritaskan yang belum lunas dan punya target)
$defaultKat = 'UTS';
if ($subUts['sisa'] <= 0 && $subUas['sisa'] > 0) {
    $defaultKat = 'UAS';
} elseif ($subUts['sisa'] <= 0 && $subUas['sisa'] <= 0 && $subUjian['sisa'] > 0) {
    $defaultKat = 'Ujian';
}
$activeSub = $pembayaran->statusKiSubtagihan()[$defaultKat];
$activeSisa = (float) $activeSub['sisa'];
@endphp

<div class="row">

    {{-- INFORMASI SISWA & TAGIHAN --}}
    <div class="col-lg-5 col-12 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
                    <i class="fas fa-user text-success mr-2"></i>
                    Informasi Siswa & Tagihan KI
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 140px;">Nama Siswa</td>
                        <td class="font-weight-bold text-dark">{{ $pembayaran->siswa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">NIS</td>
                        <td class="font-weight-bold font-num">{{ $pembayaran->siswa->nis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kelas</td>
                        <td><span class="badge bg-light text-dark border">{{ $pembayaran->siswa->kelas ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tahun Ajaran</td>
                        <td>
                            <span class="badge bg-light text-success border">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $pembayaran->tahunAjaran->nama ?? ($pembayaran->tahun_ajaran ?? 'Aktif') }}
                            </span>
                        </td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted">1. Target UTS</td>
                        <td class="font-weight-bold font-num">
                            Rp {{ number_format($subUts['target'], 0, ',', '.') }}
                            <small class="text-muted">({{ $subUts['is_lunas'] ? 'Lunas' : 'Sisa: Rp ' . number_format($subUts['sisa'], 0, ',', '.') }})</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">2. Target UAS</td>
                        <td class="font-weight-bold font-num">
                            Rp {{ number_format($subUas['target'], 0, ',', '.') }}
                            <small class="text-muted">({{ $subUas['is_lunas'] ? 'Lunas' : 'Sisa: Rp ' . number_format($subUas['sisa'], 0, ',', '.') }})</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">3. Target Ujian</td>
                        <td class="font-weight-bold font-num">
                            Rp {{ number_format($subUjian['target'], 0, ',', '.') }}
                            <small class="text-muted">({{ $subUjian['is_lunas'] ? 'Lunas' : 'Sisa: Rp ' . number_format($subUjian['sisa'], 0, ',', '.') }})</small>
                        </td>
                    </tr>
                    @if($terbawa > 0)
                        <tr>
                            <td class="text-muted">Tagihan Terbawa</td>
                            <td class="font-weight-bold text-warning font-num">
                                Rp {{ number_format($terbawa, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                    <tr class="border-top">
                        <td class="text-muted font-weight-bold">Total Tagihan KI</td>
                        <td class="font-weight-bold text-dark font-num" style="font-size: 16px;">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted font-weight-bold">Total Terbayar</td>
                        <td class="font-weight-bold text-success font-num" style="font-size: 16px;">
                            Rp {{ number_format($terbayar, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted font-weight-bold">Sisa Tagihan Total</td>
                        <td class="font-weight-bold text-danger font-num" style="font-size: 18px;">
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                {{-- Status Badges 3 Kategori --}}
                <div class="mt-3 pt-3 border-top">
                    <div class="small font-weight-bold text-muted mb-2">Status Pelunasan Tiap Kegiatan:</div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small font-weight-bold text-dark">UTS:</span>
                        <span class="badge {{ $subUts['badge_class'] }}">{{ $subUts['status_text'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small font-weight-bold text-dark">UAS:</span>
                        <span class="badge {{ $subUas['badge_class'] }}">{{ $subUas['status_text'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small font-weight-bold text-dark">Ujian:</span>
                        <span class="badge {{ $subUjian['badge_class'] }}">{{ $subUjian['status_text'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM INPUT PEMBAYARAN --}}
    <div class="col-lg-7 col-12 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header card-header-payment py-3">
                <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    Form Pembayaran Kegiatan Intrakurikuler
                </h5>
            </div>

            <form method="POST" action="{{ route('ki.bayar', $pembayaran->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body p-4">

                    {{-- Pilihan Dropdown Tagihan yang Dibayar --}}
                    <div class="subtagihan-select-box mb-3">
                        <div class="form-group row mb-0 align-items-center">
                            <label class="col-sm-4 col-form-label font-weight-bold text-success">
                                <i class="fas fa-check-to-slot mr-1"></i> Pilih Tagihan Dibayar <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <select name="kategori"
                                        id="selectKategoriKiPage"
                                        class="form-control font-weight-bold"
                                        style="border-radius: 8px; border: 1.5px solid #16a34a;"
                                        required
                                        onchange="updateKiPagePaymentForm()">
                                    <option value="UTS"
                                            data-sisa="{{ (int)$subUts['sisa'] }}"
                                            data-target="{{ (int)$subUts['target'] }}"
                                            {{ $defaultKat === 'UTS' ? 'selected' : '' }}
                                            {{ $subUts['sisa'] <= 0 && $subUts['target'] > 0 ? 'disabled' : '' }}>
                                        UTS — Sisa: Rp {{ number_format($subUts['sisa'], 0, ',', '.') }} {{ $subUts['sisa'] <= 0 && $subUts['target'] > 0 ? '(Lunas)' : '' }}
                                    </option>
                                    <option value="UAS"
                                            data-sisa="{{ (int)$subUas['sisa'] }}"
                                            data-target="{{ (int)$subUas['target'] }}"
                                            {{ $defaultKat === 'UAS' ? 'selected' : '' }}
                                            {{ $subUas['sisa'] <= 0 && $subUas['target'] > 0 ? 'disabled' : '' }}>
                                        UAS — Sisa: Rp {{ number_format($subUas['sisa'], 0, ',', '.') }} {{ $subUas['sisa'] <= 0 && $subUas['target'] > 0 ? '(Lunas)' : '' }}
                                    </option>
                                    <option value="Ujian"
                                            data-sisa="{{ (int)$subUjian['sisa'] }}"
                                            data-target="{{ (int)$subUjian['target'] }}"
                                            {{ $defaultKat === 'Ujian' ? 'selected' : '' }}
                                            {{ $subUjian['sisa'] <= 0 && $subUjian['target'] > 0 ? 'disabled' : '' }}>
                                        Ujian — Sisa: Rp {{ number_format($subUjian['sisa'], 0, ',', '.') }} {{ $subUjian['sisa'] <= 0 && $subUjian['target'] > 0 ? '(Lunas)' : '' }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold">Tanggal <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-light font-num" value="{{ date('d/m/Y') }}" readonly style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold">Nominal Pembayaran <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold bg-light">Rp</span>
                                </div>
                                <input type="number"
                                       name="nominal"
                                       id="nominalInputKiPage"
                                       class="form-control font-weight-bold text-success font-num"
                                       max="{{ $activeSisa > 0 ? (int)$activeSisa : 999999999 }}"
                                       min="1"
                                       value=""
                                       placeholder="Masukkan nominal pembayaran..."
                                       style="border-radius: 0 8px 8px 0;"
                                       required
                                       {{ $sisa <= 0 ? 'disabled' : '' }}>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap gap-1">
                                <small class="form-text text-muted mb-0" id="maxNominalLabelKiPage">
                                    Maksimal pembayaran {{ $defaultKat }}: <strong>Rp {{ number_format($activeSisa, 0, ',', '.') }}</strong>
                                </small>
                                <button type="button"
                                        id="btnQuickFillKiPage"
                                        class="btn btn-xs btn-outline-success font-weight-bold mt-1"
                                        style="border-radius: 6px; font-size: 11px; {{ $activeSisa <= 0 ? 'display: none;' : '' }}"
                                        onclick="quickFillKiNominalPage()">
                                    <i class="fas fa-coins mr-1"></i> <span id="labelQuickFillKiPage">Bayar Lunas {{ $defaultKat }} (Rp {{ number_format($activeSisa, 0, ',', '.') }})</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="payment-mode-box">
                                <label class="d-inline-flex align-items-center mr-3">
                                    <input type="radio" name="metode" value="Cash" checked class="mr-2"> Cash / Tunai
                                </label>
                                <label class="d-inline-flex align-items-center mr-3">
                                    <input type="radio" name="metode" value="Transfer" class="mr-2"> Bank Transfer
                                </label>
                                <label class="d-inline-flex align-items-center">
                                    <input type="radio" name="metode" value="QRIS" class="mr-2"> QRIS / E-Wallet
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold">Bukti Transfer</label>
                        <div class="col-sm-9">
                            <div class="custom-file-upload">
                                <input type="file"
                                       name="bukti"
                                       id="buktiFilePage"
                                       class="file-input-hidden"
                                       accept="image/*,.pdf"
                                       onchange="if(this.files && this.files[0]) { document.getElementById('labelBuktiPage').innerHTML = '<i class=\'fas fa-file text-success mr-2\'></i><span class=\'font-weight-bold text-dark\'>' + this.files[0].name + '</span>'; }">
                                <label for="buktiFilePage" class="file-upload-label d-flex align-items-center justify-content-between px-3">
                                    <span id="labelBuktiPage" class="text-muted text-truncate" style="max-width: 78%; font-size: 13px;">
                                        <i class="fas fa-cloud-upload-alt text-success mr-2"></i> Pilih foto / file struk transfer...
                                    </span>
                                    <span class="btn btn-xs btn-outline-success font-weight-bold">
                                        <i class="fas fa-folder-open mr-1"></i> Browse
                                    </span>
                                </label>
                            </div>
                            <small class="form-text text-muted">Lampirkan foto/struk transfer jika pembayaran via Bank / QRIS (opsional, maks 3MB)</small>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <label class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="2"
                                      style="border-radius: 8px;"
                                      placeholder="Catatan tambahan pembayaran (opsional)..."></textarea>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('ki.index') }}" class="btn btn-batal-merah px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success px-4 font-weight-bold" style="border-radius: 8px; height: 38px;" {{ $sisa <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-money-bill-wave mr-1"></i> Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- TABEL RIWAYAT TRANSAKSI SISWA INI --}}
<div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
            <i class="fas fa-history text-success mr-2"></i>
            Riwayat Pembayaran Kegiatan Intrakurikuler Siswa Ini
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive mb-0">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 40px;">No</th>
                        <th>Tanggal Bayar</th>
                        <th class="text-center" style="background:#e8f5e9; color:#1b5e20;">Kegiatan</th>
                        <th>Nominal (Rp)</th>
                        <th>Metode</th>
                        <th>Bukti Transfer</th>
                        <th>Keterangan</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran->detailPembayaran as $detail)
                        <tr>
                            <td class="text-center text-muted font-num">{{ $loop->iteration }}</td>
                            <td class="font-num">{{ \Carbon\Carbon::parse($detail->tanggal)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge badge-success px-2 py-1" style="font-size: 11px; border-radius: 6px;">
                                    {{ $detail->kategori ?? 'KI' }}
                                </span>
                            </td>
                            <td class="font-weight-bold text-success font-num">{{ number_format($detail->nominal, 0, ',', '.') }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $detail->metode }}</span></td>
                            <td>
                                @if($detail->bukti)
                                    <a href="{{ asset($detail->bukti) }}" target="_blank" class="badge badge-info text-white" title="Buka Bukti Transfer">
                                        <i class="fas fa-file-image mr-1"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $detail->keterangan ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('bukti.cetak', $detail->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Kuitansi" style="border-radius: 6px;">
                                    <i class="fas fa-print mr-1"></i> Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Belum ada riwayat transaksi pembayaran untuk tagihan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(session('last_detail_id'))
    <div class="modal fade" id="modalCetakBuktiPage" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header card-header-payment">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-check-circle text-white mr-2"></i> Pembayaran Berhasil</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center py-4 px-4">
                    <i class="fas fa-receipt text-success fa-3x mb-3"></i>
                    <h5 class="font-weight-bold">Pembayaran Kegiatan Intrakurikuler berhasil dicatat!</h5>
                    <p class="text-muted mb-0">Apakah Anda ingin mencetak kuitansi pembayaran sekarang?</p>
                </div>
                <div class="modal-footer bg-light justify-content-center py-3">
                    <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">Tutup</button>
                    <a href="{{ route('bukti.cetak', session('last_detail_id')) }}"
                       target="_blank"
                       class="btn btn-success px-4 font-weight-bold"
                       style="border-radius: 8px; height: 38px; color: #ffffff !important;"
                       id="btnCetakBuktiPage">
                        <i class="fas fa-print mr-1" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Cetak Kuitansi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@stop

@section('js')
<script>
function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID').format(value || 0);
}

function updateKiPagePaymentForm() {
    const select = document.getElementById('selectKategoriKiPage');
    const input = document.getElementById('nominalInputKiPage');
    const maxLabel = document.getElementById('maxNominalLabelKiPage');
    const quickBtn = document.getElementById('btnQuickFillKiPage');
    const quickLabel = document.getElementById('labelQuickFillKiPage');

    if (!select || !input) return;

    const opt = select.options[select.selectedIndex];
    const sisa = opt ? (parseFloat(opt.dataset.sisa) || 0) : 0;
    const kategori = opt ? opt.value : 'KI';

    input.max = sisa > 0 ? sisa : 999999999;
    input.value = '';

    if (maxLabel) {
        maxLabel.innerHTML = `Maksimal pembayaran ${kategori}: <strong>Rp ${formatRupiah(sisa)}</strong>`;
    }

    if (quickBtn && quickLabel) {
        if (sisa > 0) {
            quickBtn.style.display = 'inline-flex';
            quickLabel.textContent = `Bayar Lunas ${kategori} (Rp ${formatRupiah(sisa)})`;
        } else {
            quickBtn.style.display = 'none';
        }
    }
}

function quickFillKiNominalPage() {
    const select = document.getElementById('selectKategoriKiPage');
    const input = document.getElementById('nominalInputKiPage');
    if (!select || !input) return;
    const opt = select.options[select.selectedIndex];
    const sisa = opt ? (parseFloat(opt.dataset.sisa) || 0) : 0;
    if (sisa > 0) {
        input.value = Math.floor(sisa);
    }
}

$(document).ready(function () {
    @if(session('last_detail_id'))
        $('#modalCetakBuktiPage').modal('show');
        $('#btnCetakBuktiPage').on('click', function () {
            setTimeout(function () {
                $('#modalCetakBuktiPage').modal('hide');
            }, 500);
        });
    @endif
});
</script>
@stop