@extends('adminlte::page')

@section('title','Pembayaran Sarpras')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <style>
        .card-header-payment {
            background: linear-gradient(135deg, #15803D, #16A34A);
            color: #ffffff;
        }
        .payment-mode-box {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .payment-mode-box label {
            margin-bottom: 0;
            cursor: pointer;
            font-weight: 500;
        }
        .fee-info-box {
            background: #f8f9fa;
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
            border-color: #16A34A !important;
            background: #f0fdf4;
        }
    </style>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-1"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

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

@php
$terbayar = (float) $pembayaran->detailPembayaran->sum('nominal');
$terbawa = (float) ($pembayaran->belum_lunas ?? 0);
$totalTagihan = (float) $pembayaran->target + $terbawa;
$sisa = max($totalTagihan - $terbayar, 0);
$persen = $totalTagihan > 0 ? min(($terbayar / $totalTagihan) * 100, 100) : 0;
$sisaTerbawa = max($terbawa - $terbayar, 0);
@endphp

<div class="row">

    {{-- INFORMASI SISWA & TAGIHAN --}}
    <div class="col-md-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header card-header-payment py-3">
                <h5 class="mb-0 font-weight-bold" style="font-size: 16px;">
                    <i class="fas fa-id-card mr-2"></i>
                    Informasi Tagihan Sarpras
                </h5>
            </div>

            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="140" class="text-muted">NIS</th>
                        <td class="font-weight-bold">{{ $pembayaran->siswa->nis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Nama Siswa</th>
                        <td class="font-weight-bold">{{ $pembayaran->siswa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Kelas</th>
                        <td><span class="badge badge-light border">{{ $pembayaran->siswa->kelas->nama_kelas ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tahun Ajaran</th>
                        <td><span class="badge badge-success">{{ $pembayaran->tahun_ajaran }}</span></td>
                    </tr>
                </table>

                <hr class="my-3">

                <div class="fee-info-box">
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Target Tahun Ini</span>
                        <span class="font-weight-bold">Rp {{ number_format($pembayaran->target, 0, ',', '.') }}</span>
                    </div>

                    @if($terbawa > 0)
                        <div class="d-flex justify-content-between mb-1 small text-muted">
                            <span>Terbawa Tahun Lalu</span>
                            <span class="font-weight-bold text-warning">Rp {{ number_format($terbawa, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Total Kewajiban</span>
                        <span class="font-weight-bold">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Total Terbayar</span>
                        <span class="font-weight-bold text-success">Rp {{ number_format($terbayar, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between pt-2 border-top">
                        <span class="font-weight-bold">Sisa Tagihan</span>
                        <span class="font-weight-bold text-danger" style="font-size: 16px;">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($terbawa > 0 && $sisaTerbawa > 0)
                    <div class="alert alert-warning py-2 px-3 mt-3 mb-0 small">
                        <i class="fas fa-info-circle mr-1"></i>
                        Siswa memiliki <strong>tagihan terbawa tahun lalu</strong> sebesar <strong>Rp {{ number_format($sisaTerbawa, 0, ',', '.') }}</strong>. Pembayaran yang dimasukkan akan melunasi tagihan terbawa terlebih dahulu.
                    </div>
                @endif

                <div class="mt-3">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Progres Pembayaran</span>
                        <span>{{ number_format($persen, 0) }}%</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persen }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM INPUT PEMBAYARAN --}}
    <div class="col-md-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header card-header-payment py-3">
                <h5 class="mb-0 font-weight-bold" style="font-size: 16px;">
                    <i class="fas fa-coins mr-2"></i>
                    Input Pembayaran Sarpras
                </h5>
            </div>

            <form method="POST" action="{{ route('sarpras.bayar', $pembayaran->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body p-4">

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold">Tanggal <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-light" value="{{ date('d/m/Y') }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 col-form-label font-weight-bold">Nominal Pembayaran <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="number"
                                       name="nominal"
                                       class="form-control font-weight-bold text-success"
                                       max="{{ $sisa }}"
                                       min="1"
                                       placeholder="Masukkan nominal pembayaran..."
                                       required
                                       {{ $sisa <= 0 ? 'disabled' : '' }}>
                            </div>
                            <small class="form-text text-muted">
                                Sisa tagihan saat ini: Rp {{ number_format($sisa, 0, ',', '.') }}
                            </small>
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
                                       id="buktiSarprasPage"
                                       class="file-input-hidden"
                                       accept="image/*,.pdf"
                                       onchange="if(this.files && this.files[0]) { document.getElementById('labelBuktiSarprasPage').innerHTML = '<i class=\'fas fa-file text-success mr-2\'></i><span class=\'font-weight-bold text-dark\'>' + this.files[0].name + '</span>'; }">
                                <label for="buktiSarprasPage" class="file-upload-label d-flex align-items-center justify-content-between px-3">
                                    <span id="labelBuktiSarprasPage" class="text-muted text-truncate" style="max-width: 78%; font-size: 13px;">
                                        <i class="fas fa-cloud-upload-alt text-success mr-2"></i> Pilih foto / file struk transfer...
                                    </span>
                                    <span class="btn btn-xs btn-outline-success font-weight-bold">
                                        <i class="fas fa-folder-open mr-1"></i> Browse
                                    </span>
                                </label>
                            </div>
                            <small class="form-text text-muted">Lampirkan bukti pembayaran jika melalui Bank Transfer / QRIS (opsional, maks 3MB)</small>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="2"
                                      placeholder="Catatan pembayaran (opsional)..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-2 border-top">
                        <a href="{{ route('sarpras.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>

                        <button type="submit" class="btn btn-add px-4" {{ $sisa <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-money-bill-wave mr-1"></i> Simpan Pembayaran
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

{{-- RIWAYAT PEMBAYARAN --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
            <i class="fas fa-history mr-2 text-primary"></i>
            Riwayat Pembayaran Sarpras
        </h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Bukti Transfer</th>
                        <th>Keterangan</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran->detailPembayaran as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($detail->tanggal)->format('d/m/Y') }}</td>
                            <td class="font-weight-bold text-success">
                                Rp {{ number_format($detail->nominal, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge badge-light border">{{ $detail->metode }}</span>
                            </td>
                            <td>
                                @if($detail->bukti)
                                    <a href="{{ asset($detail->bukti) }}" target="_blank" class="badge badge-info" title="Lihat Bukti Transfer">
                                        <i class="fas fa-image mr-1"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $detail->keterangan ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('bukti.cetak', $detail->id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Cetak Kuitansi">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">
                                Belum ada riwayat pembayaran untuk tagihan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(session('last_detail_id'))
<div class="modal fade" id="modalCetakBukti" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header card-header-payment">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle text-white mr-2"></i> Pembayaran Berhasil</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-receipt text-success fa-3x mb-3"></i>
                <h5>Pembayaran Sarpras berhasil dicatat!</h5>
                <p class="text-muted mb-0">Apakah Anda ingin mencetak bukti pembayaran sekarang?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4 mr-2" data-dismiss="modal">Tutup</button>
                <a href="{{ route('bukti.cetak', session('last_detail_id')) }}"
                   target="_blank"
                   class="btn btn-add px-4"
                   id="btnCetakBukti">
                    <i class="fas fa-print mr-1"></i> Cetak Bukti Pembayaran
                </a>
            </div>
        </div>
    </div>
</div>

@section('js')
<script>
    $(function () {
        $('#modalCetakBukti').modal('show');
        $('#btnCetakBukti').on('click', function () {
            setTimeout(function () {
                $('#modalCetakBukti').modal('hide');
            }, 500);
        });
    });
</script>
@stop
@endif

@stop