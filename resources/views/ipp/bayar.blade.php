@extends('adminlte::page')

@section('title','Pembayaran IPP')

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
$tagihanAwal = $pembayaran->totalTagihanAwal();
$totalTagihan = $pembayaran->totalTagihan();
$sisa = max($totalTagihan - $terbayar, 0);
$persen = $totalTagihan > 0 ? min(($terbayar / $totalTagihan) * 100, 100) : 0;
$sisaTerbawa = max($terbawa - $terbayar, 0);
$ippStatus = $pembayaran->statusIpp();
$tagihanBulanIni = $ippStatus['tagihan_bulan_ini'] ?? 0;
$tunggakanBulan = $ippStatus['tunggakan_bulan'] ?? 0;
$tarifBulanan = $ippStatus['tarif_bulanan'] ?? 0;
$bulanIndoList = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$namaBulanSekarang = ($bulanIndoList[(int)\Carbon\Carbon::now()->format('n')] ?? 'Agustus') . ' ' . \Carbon\Carbon::now()->format('Y');
@endphp

<div class="row">

    {{-- INFORMASI SISWA & TAGIHAN --}}
    <div class="col-md-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header card-header-payment py-3">
                <h5 class="mb-0 font-weight-bold" style="font-size: 16px;">
                    <i class="fas fa-id-card mr-2"></i>
                    Informasi Tagihan IPP
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
                        <td><span class="badge badge-light border">{{ $pembayaran->siswa->kelas->nama_kelas ?? $pembayaran->siswa->kelas ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Tahun Ajaran</th>
                        <td><span class="badge badge-success">{{ $pembayaran->tahun_ajaran }}</span></td>
                    </tr>
                </table>

                <hr class="my-3">

                <div class="fee-info-box">
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Tagihan Awal</span>
                        <span class="font-weight-bold">Rp {{ number_format($tagihanAwal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Tagihan Setelah Potongan</span>
                        <span class="font-weight-bold">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
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
                        <span class="font-weight-bold">Sisa Tagihan Total</span>
                        <span class="font-weight-bold text-danger" style="font-size: 16px;">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Alert Tagihan Bulan Ini --}}
                @if($sisa <= 0)
                    <div class="alert alert-success py-2 px-3 mt-3 mb-0 small">
                        <i class="fas fa-check-circle mr-1"></i>
                        Seluruh tagihan IPP tahun ajaran ini sudah <strong>LUNAS</strong>.
                    </div>
                @elseif($tagihanBulanIni > 0)
                    <div class="alert alert-info py-2 px-3 mt-3 mb-0 small" style="background: #f0fdf4; border: 1.5px solid #86efac; color: #166534;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <span><i class="fas fa-calendar-alt mr-1 text-success"></i> <strong>Tagihan Bulan Ini ({{ $namaBulanSekarang }}):</strong></span>
                            <strong class="text-success font-num" style="font-size: 15px;">Rp {{ number_format($tagihanBulanIni, 0, ',', '.') }}</strong>
                        </div>
                        <div class="mt-1" style="font-size: 11.5px;">
                            💡 Perlu membayar <strong>Rp {{ number_format($tagihanBulanIni, 0, ',', '.') }}</strong> untuk melunasi kewajiban IPP hingga bulan ini.
                        </div>
                    </div>
                @else
                    <div class="alert alert-success py-2 px-3 mt-3 mb-0 small" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;">
                        <i class="fas fa-check-circle mr-1"></i> Tagihan bulan <strong>{{ $namaBulanSekarang }}</strong> sudah <strong>Lunas</strong>.
                    </div>
                @endif

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
                    Input Pembayaran IPP
                </h5>
            </div>

            <form method="POST" action="{{ route('ipp.bayar', $pembayaran->id) }}" enctype="multipart/form-data">
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
                                       id="nominalInputPage"
                                       class="form-control font-weight-bold text-success font-num"
                                       max="{{ $sisa }}"
                                       min="0"
                                       value=""
                                       placeholder="Masukkan nominal pembayaran..."
                                       oninput="updateKalkulasiPage()"
                                       {{ $sisa <= 0 ? 'disabled' : '' }}>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap gap-1">
                                <small class="form-text text-muted mb-0">
                                    Sisa tagihan total saat ini: <strong>Rp {{ number_format($sisa, 0, ',', '.') }}</strong>
                                </small>
                                @if($tagihanBulanIni > 0)
                                    <button type="button"
                                            class="btn btn-xs btn-outline-success font-weight-bold mt-1"
                                            style="border-radius: 6px; font-size: 11px;"
                                            onclick="isiTagihanBulanIniPage()">
                                        <i class="fas fa-coins mr-1"></i> Isi Sisa Tagihan Bulan Ini (Rp {{ number_format($tagihanBulanIni, 0, ',', '.') }})
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="potonganBayar" class="col-sm-3 col-form-label font-weight-bold">Potongan IPP</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="number"
                                       name="potongan"
                                       id="potonganBayar"
                                       class="form-control font-weight-bold text-dark"
                                       value="{{ old('potongan', '') }}"
                                       min="0"
                                       max="{{ (int) $sisa }}"
                                       step="1000"
                                       placeholder="Masukkan potongan / diskon..."
                                       oninput="updateKalkulasiPage()">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap gap-1">
                                <small class="form-text text-muted mb-0">Potongan mengurangi tagihan, tetapi tidak dicatat sebagai kas masuk.</small>
                                <span id="badgePemenuhanPage" class="small font-weight-bold" style="font-size: 12px;"></span>
                            </div>
                            @error('potongan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                                       id="buktiIppPage"
                                       class="file-input-hidden"
                                       accept="image/*,.pdf"
                                       onchange="if(this.files && this.files[0]) { document.getElementById('labelBuktiIppPage').innerHTML = '<i class=\'fas fa-file text-success mr-2\'></i><span class=\'font-weight-bold text-dark\'>' + this.files[0].name + '</span>'; }">
                                <label for="buktiIppPage" class="file-upload-label d-flex align-items-center justify-content-between px-3">
                                    <span id="labelBuktiIppPage" class="text-muted text-truncate" style="max-width: 78%; font-size: 13px;">
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
                        <a href="{{ route('ipp.index') }}" class="btn btn-batal-merah px-4">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>

                        <button type="submit" class="btn btn-add px-4" {{ $sisa <= 0 ? 'disabled' : '' }}>
                            Simpan Pembayaran
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
            Riwayat Pembayaran IPP
        </h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>Nominal (Rp)</th>
                        <th>Potongan (Rp)</th>
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
                                {{ number_format($detail->nominal, 0, ',', '.') }}
                            </td>
                            <td class="font-weight-bold font-num {{ $detail->potongan > 0 ? 'text-primary' : 'text-muted' }}">
                                {{ $detail->potongan > 0 ? number_format($detail->potongan, 0, ',', '.') : '-' }}
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
                            <td colspan="8" class="text-center py-3 text-muted">
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
                <h5>Pembayaran IPP berhasil dicatat!</h5>
                <p class="text-muted mb-0">Apakah Anda ingin mencetak kuitansi pembayaran sekarang?</p>
            </div>
            <div class="modal-footer bg-light justify-content-center py-3">
                <button type="button" class="btn btn-batal-merah px-4 mr-2" data-dismiss="modal">Tutup</button>
                <a href="{{ route('bukti.cetak', session('last_detail_id')) }}"
                   target="_blank"
                   class="btn btn-success px-4 font-weight-bold"
                   style="border-radius: 8px; height: 38px; color: #ffffff !important;"
                   id="btnCetakBukti">
                    <i class="fas fa-print mr-1" style="color: #ffffff !important;"></i> <span style="color: #ffffff !important;">Cetak Kuitansi</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@section('js')
<script>
    const tagihanBulanIniValue = {{ (float) $tagihanBulanIni }};
    const sisaTotalValue = {{ (float) $sisa }};

    function updateKalkulasiPage() {
        const nomInput = document.getElementById('nominalInputPage');
        const potInput = document.getElementById('potonganBayar');
        const badge = document.getElementById('badgePemenuhanPage');
        if (!nomInput || !potInput || !badge) return;

        const nom = parseFloat(nomInput.value) || 0;
        const pot = parseFloat(potInput.value) || 0;
        const total = nom + pot;

        if (total <= 0) {
            badge.innerHTML = '';
            return;
        }

        if (tagihanBulanIniValue > 0) {
            if (total >= (tagihanBulanIniValue - 1)) {
                badge.innerHTML = '<span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Tagihan bulan ini akan LUNAS! (Total: Rp ' + total.toLocaleString('id-ID') + ')</span>';
            } else {
                const sisaBulan = Math.max(tagihanBulanIniValue - total, 0);
                badge.innerHTML = '<span class="text-warning font-weight-bold"><i class="fas fa-clock mr-1"></i> Kurang Rp ' + sisaBulan.toLocaleString('id-ID') + ' lagi untuk melunasi bulan ini</span>';
            }
        } else {
            badge.innerHTML = '<span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Total pemenuhan: Rp ' + total.toLocaleString('id-ID') + '</span>';
        }
    }

    function isiTagihanBulanIniPage() {
        const potInput = document.getElementById('potonganBayar');
        const nomInput = document.getElementById('nominalInputPage');
        const pot = parseFloat(potInput ? potInput.value : 0) || 0;
        const butuh = Math.max(tagihanBulanIniValue - pot, 0);
        if (nomInput) {
            nomInput.value = butuh;
            updateKalkulasiPage();
        }
    }

    $(function () {
        @if(session('last_detail_id'))
            $('#modalCetakBukti').modal('show');
            $('#btnCetakBukti').on('click', function () {
                setTimeout(function () {
                    $('#modalCetakBukti').modal('hide');
                }, 500);
            });
        @endif
    });
</script>
@stop

@stop