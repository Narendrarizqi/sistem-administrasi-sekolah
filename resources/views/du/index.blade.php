@extends('adminlte::page')

@section('title', 'Pembayaran Daftar Ulang')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        #duTable th.sortable {
            cursor: pointer;
            user-select: none;
        }

        #duTable th.sortable:hover {
            background-color: #f8f9fa;
        }

        #duTable .sort-icon {
            margin-left: 5px;
            font-size: 11px;
            opacity: 0.5;
        }

        #duTable th.sort-active .sort-icon {
            opacity: 1;
        }

        .modal-header-payment {
            background: linear-gradient(135deg, #15803D, #16A34A) !important;
            color: #ffffff !important;
            border-top-left-radius: calc(0.3rem - 1px);
            border-top-right-radius: calc(0.3rem - 1px);
        }

        .modal-header-payment .modal-title,
        .modal-header-payment .modal-title *,
        .modal-header-payment h5,
        .modal-header-payment i,
        .modal-header-payment span,
        .modal-header-payment button,
        .modal-header-payment .close {
            color: #ffffff !important;
            opacity: 1 !important;
            text-shadow: none !important;
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

    {{-- PAGE HEADER & BREADCRUMB INDICATOR --}}
    <div class="page-header-box">
        <div>
            <div class="page-header-breadcrumb">
                <i class="fas fa-home text-success"></i>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <i class="fas fa-chevron-right text-muted" style="font-size: 9px;"></i>
                <span class="text-dark font-weight-bold">Daftar Ulang</span>
            </div>
            <h1 class="page-header-title">
                <span class="page-header-icon"><i class="fas fa-user-check"></i></span>
                Daftar Ulang Siswa (DU)
            </h1>
            <p class="page-header-desc">
                Kelola target tagihan daftar ulang siswa baru dan kenaikan kelas, verifikasi kuitansi, dan monitoring pelunasan.
            </p>
        </div>
        <div class="page-header-badges">
            <span class="badge-page-indicator">
                <i class="fas fa-calendar-check"></i>
                Tahun Ajaran: {{ $selectedTa->nama ?? 'Aktif' }}
            </span>
            <span class="badge bg-light text-secondary border px-3 py-2 font-weight-semibold" style="border-radius: 20px; font-size: 12px;">
                <i class="fas fa-users text-muted mr-1"></i> Total: {{ $data->count() }} Data Tagihan
            </span>
        </div>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
                Data Pembayaran Daftar Ulang
            </h5>
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="table-search-box">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            id="duSearchInput"
                            placeholder="Cari NIS atau nama..."
                        >
                    </div>

                    @if(isset($daftarTahunAjaran) && $daftarTahunAjaran->isNotEmpty())
                        <form action="{{ route('du.index') }}" method="GET" class="d-flex align-items-center">
                            <select
                                name="tahun_ajaran_id"
                                class="form-control"
                                style="min-width: 170px; border-radius: 10px;"
                                onchange="this.form.submit()"
                            >
                                @foreach($daftarTahunAjaran as $ta)
                                    <option value="{{ $ta->id }}" {{ $selectedTa && $selectedTa->id == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->nama }}{{ $ta->is_active ? ' (Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

                <button type="button" class="btn btn-add" data-toggle="modal" data-target="#modalTambahDu">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah Tagihan
                </button>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle" id="duTable">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="sortable" data-sort="nis" title="Klik untuk mengurutkan berdasarkan NIS">
                                NIS
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th class="sortable" data-sort="nama" title="Klik untuk mengurutkan berdasarkan nama">
                                Nama
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th>Target (Rp)</th>
                            <th>Terbawa (Rp)</th>
                            <th>Total Tagihan (Rp)</th>
                            <th>Terbayar (Rp)</th>
                            <th class="sortable" data-sort="sisa" title="Klik untuk mengurutkan berdasarkan sisa tagihan">
                                Sisa (Rp)
                                <i class="fas fa-sort sort-icon"></i>
                            </th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $item)

                            @php
                                $terbayar = (float) $item->detailPembayaran->sum('nominal');
                                $terbawa = (float) ($item->belum_lunas ?? 0);
                                $totalTagihan = (float) $item->target + $terbawa;
                                $sisa = max($totalTagihan - $terbayar, 0);
                            @endphp

                            <tr
                                data-nis="{{ $item->siswa->nis ?? '' }}"
                                data-nama="{{ $item->siswa->nama ?? '' }}"
                                data-sisa="{{ $sisa }}"
                            >
                                <td class="row-number">{{ $loop->iteration }}</td>
                                <td>{{ $item->siswa->nis ?? '-' }}</td>
                                <td class="font-weight-semibold">{{ $item->siswa->nama ?? '-' }}</td>
                                <td>{{ number_format($item->target, 0, ',', '.') }}</td>
                                <td class="{{ $terbawa > 0 ? 'text-warning font-weight-bold' : '' }}">
                                    {{ number_format($terbawa, 0, ',', '.') }}
                                </td>
                                <td class="font-weight-bold">{{ number_format($totalTagihan, 0, ',', '.') }}</td>
                                <td class="text-success font-weight-semibold">{{ number_format($terbayar, 0, ',', '.') }}</td>
                                <td class="text-danger font-weight-bold">{{ number_format($sisa, 0, ',', '.') }}</td>
                                <td>
                                    @if($sisa <= 0 && $totalTagihan > 0)
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-add me-1 mb-1"
                                            title="Bayar Tagihan"
                                            data-toggle="modal"
                                            data-target="#modalBayar{{ $item->id }}"
                                            {{ $sisa <= 0 && $totalTagihan > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>

                                    <a href="{{ route('du.edit', $item->id) }}"
                                       class="btn btn-sm btn-outline-warning me-1 mb-1"
                                       title="Edit Tagihan">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger mb-1"
                                            title="Hapus Tagihan"
                                            data-toggle="modal"
                                            data-target="#modalHapus{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    Belum ada data tagihan Daftar Ulang pada tahun ajaran ini.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- MODALS SECTION --}}

    {{-- MODAL TAMBAH TAGIHAN DU --}}
    @php
        $allSiswaDu = \App\Models\Siswa::orderBy('nama')->get();
        $existingSiswaIds = $data->pluck('siswa_id')->toArray();
    @endphp
    <div class="modal fade" id="modalTambahDu" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header modal-header-payment py-3">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-id-card mr-2"></i>
                        Tambah Tagihan Daftar Ulang (DU)
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('du.store') }}" method="POST" id="formModalTambahDu">
                    @csrf
                    <div class="modal-body p-4">

                        {{-- Alert Jika Siswa Duplikat --}}
                        <div id="modalDuDuplicateAlert" class="alert alert-danger d-none mb-3 py-2 px-3">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <strong>Peringatan:</strong> Siswa ini sudah memiliki data tagihan Daftar Ulang (DU) pada tahun ajaran ini. Tidak dapat menambahkan tagihan ganda!
                        </div>
                        @if($errors->has('siswa_id'))
                            <div class="alert alert-danger mb-3 py-2 px-3">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Error:</strong> {{ $errors->first('siswa_id') }}
                            </div>
                        @endif

                        {{-- Pilih Siswa --}}
                        <div class="form-group row mb-3">
                            <label for="modal_du_siswa_id" class="col-sm-3 col-form-label font-weight-bold">
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select name="siswa_id"
                                        id="modal_du_siswa_id"
                                        class="form-control @error('siswa_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Siswa yang Ditagihkan --</option>
                                    @foreach($allSiswaDu as $itemSiswa)
                                        @php $isAlreadyTagged = in_array($itemSiswa->id, $existingSiswaIds); @endphp
                                        <option value="{{ $itemSiswa->id }}"
                                                data-nis="{{ $itemSiswa->nis }}"
                                                data-nama="{{ $itemSiswa->nama }}"
                                                data-kelas="{{ $itemSiswa->kelas }}"
                                                data-exists="{{ $isAlreadyTagged ? '1' : '0' }}"
                                                {{ $isAlreadyTagged ? 'disabled class=text-muted' : '' }}
                                                {{ old('siswa_id') == $itemSiswa->id ? 'selected' : '' }}>
                                            {{ $itemSiswa->nis }} - {{ $itemSiswa->nama }} ({{ $itemSiswa->kelas }}) {{ $isAlreadyTagged ? '— [Sudah Ada Tagihan]' : '' }}
                                        </option>
                                    @endforeach
                                </select>

                                <div id="modalDuSelectedSiswaBox" class="mt-2 p-2 bg-light border rounded small d-none">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-muted">Nama:</span> <strong id="modalDuPreviewNama">-</strong>
                                            <span class="mx-2">|</span>
                                            <span class="text-muted">NIS:</span> <span id="modalDuPreviewNis" class="font-weight-bold">-</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-light text-dark border" id="modalDuPreviewKelas">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Target Nominal --}}
                        <div class="form-group row mb-3">
                            <label for="modal_du_target" class="col-sm-3 col-form-label font-weight-bold">
                                Target Tagihan <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light">Rp</span>
                                    </div>
                                    <input type="number"
                                           name="target"
                                           id="modal_du_target"
                                           class="form-control font-weight-bold text-success"
                                           placeholder="Masukkan nominal target pembayaran..."
                                           min="0"
                                           step="1000"
                                           required>
                                </div>
                                <small class="form-text text-muted">
                                    Total kewajiban tagihan Daftar Ulang untuk siswa pada tahun ajaran ini.
                                </small>
                            </div>
                        </div>

                        {{-- Summary Fee Box --}}
                        <div class="fee-info-box my-3">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Jenis Tagihan</div>
                                    <div class="font-weight-bold text-dark">
                                        Daftar Ulang (DU)
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="text-muted small">Total Target Disimpan</div>
                                    <div class="font-weight-bold text-success" style="font-size: 18px;" id="modalDuSummaryTotalLabel">
                                        Rp 0
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" id="btnSubmitTambahDu" class="btn btn-success px-4 font-weight-bold">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Tagihan DU
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach($data as $item)
        @php
            $terbayarItem = (float) $item->detailPembayaran->sum('nominal');
            $terbawaItem = (float) ($item->belum_lunas ?? 0);
            $totalTagihanItem = (float) $item->target + $terbawaItem;
            $sisaItem = max($totalTagihanItem - $terbayarItem, 0);
            $sisaTerbawaItem = max($terbawaItem - $terbayarItem, 0);
        @endphp

        {{-- MODAL BAYAR (SMART SCHOOL FEE COLLECTION STYLE) --}}
        <div class="modal fade" id="modalBayar{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header modal-header-payment py-3">
                        <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                            <i class="fas fa-id-card mr-2"></i>
                            Pembayaran Daftar Ulang: {{ $item->siswa->nama ?? '-' }} ({{ $item->siswa->nis ?? '-' }})
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('du.bayar', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">

                            {{-- Rincian Tagihan Box --}}
                            <div class="fee-info-box mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">Target Tahun Ini</div>
                                        <div class="font-weight-bold">Rp {{ number_format($item->target, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-2 mb-md-0">
                                        <div class="text-muted small">Terbawa Tahun Lalu</div>
                                        <div class="font-weight-bold {{ $terbawaItem > 0 ? 'text-warning' : '' }}">
                                            Rp {{ number_format($terbawaItem, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">Total Terbayar</div>
                                        <div class="font-weight-bold text-success">Rp {{ number_format($terbayarItem, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="text-muted small">Sisa Tagihan</div>
                                        <div class="font-weight-bold text-danger" style="font-size: 17px;">
                                            Rp {{ number_format($sisaItem, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($terbawaItem > 0 && $sisaTerbawaItem > 0)
                                <div class="alert alert-warning py-2 px-3 mb-3 small">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Siswa memiliki <strong>tagihan terbawa tahun lalu</strong> sebesar <strong>Rp {{ number_format($sisaTerbawaItem, 0, ',', '.') }}</strong>. Pembayaran akan otomatis melunasi tagihan terbawa terlebih dahulu.
                                </div>
                            @endif

                            {{-- Form Inputs --}}
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
                                               max="{{ $sisaItem }}"
                                               min="1"
                                               placeholder="Masukkan nominal pembayaran..."
                                               required>
                                    </div>
                                    <small class="form-text text-muted">
                                        Maksimal pembayaran: Rp {{ number_format($sisaItem, 0, ',', '.') }}
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
                                               id="buktiDu{{ $item->id }}"
                                               class="file-input-hidden"
                                               accept="image/*,.pdf"
                                               onchange="if(this.files && this.files[0]) { document.getElementById('labelBuktiDu{{ $item->id }}').innerHTML = '<i class=\'fas fa-file text-success mr-2\'></i><span class=\'font-weight-bold text-dark\'>' + this.files[0].name + '</span>'; }">
                                        <label for="buktiDu{{ $item->id }}" class="file-upload-label d-flex align-items-center justify-content-between px-3">
                                            <span id="labelBuktiDu{{ $item->id }}" class="text-muted text-truncate" style="max-width: 78%; font-size: 13px;">
                                                <i class="fas fa-cloud-upload-alt text-success mr-2"></i> Pilih foto / file struk transfer...
                                            </span>
                                            <span class="btn btn-xs btn-outline-success font-weight-bold">
                                                <i class="fas fa-folder-open mr-1"></i> Browse
                                            </span>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Lampirkan foto/struk transfer jika melalui Bank Transfer / QRIS (opsional, maks 3MB)</small>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea name="keterangan"
                                              class="form-control"
                                              rows="2"
                                              placeholder="Catatan pembayaran (opsional)..."></textarea>
                                </div>
                            </div>

                            {{-- DROPDOWN RIWAYAT PEMBAYARAN SCROLLABLE --}}
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <button class="btn btn-sm btn-outline-success font-weight-semibold" type="button" data-toggle="collapse" data-target="#riwayatCollapse{{ $item->id }}" aria-expanded="false">
                                        <i class="fas fa-history mr-1"></i> Lihat Riwayat Pembayaran ({{ $item->detailPembayaran->count() }}) <i class="fas fa-chevron-down ml-1"></i>
                                    </button>
                                    <span class="small text-muted">{{ $item->detailPembayaran->count() }} Transaksi</span>
                                </div>

                                <div class="collapse" id="riwayatCollapse{{ $item->id }}">
                                    <div class="card card-body p-2 bg-light border shadow-none mb-0" style="max-height: 190px; overflow-y: auto;">
                                        <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 12px;">
                                            <thead class="table-success">
                                                <tr>
                                                    <th width="30" class="text-center">No</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-center" style="background:#e8f5e9; color:#1b5e20;">Bulan Dibayar</th>
                                                    <th>Nominal</th>
                                                    <th>Metode</th>
                                                    <th>Bukti</th>
                                                    <th>Keterangan</th>
                                                    <th width="45" class="text-center">Cetak</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($item->detailPembayaran as $detail)
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($detail->tanggal)->format('d/m/Y') }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-bulan-bayar">
                                                                <i class="fas fa-calendar-check mr-1 text-success"></i>
                                                                {{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('F Y') }}
                                                            </span>
                                                        </td>
                                                        <td class="font-weight-bold text-success">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                                                        <td><span class="badge badge-light border">{{ $detail->metode }}</span></td>
                                                        <td class="text-center">
                                                            @if($detail->bukti)
                                                                <a href="{{ asset($detail->bukti) }}" target="_blank" class="badge badge-info" title="Lihat Bukti Transfer">
                                                                    <i class="fas fa-image mr-1"></i> Bukti
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $detail->keterangan ?? '-' }}</td>
                                                        <td class="text-center">
                                                            <a href="{{ route('bukti.cetak', $detail->id) }}" target="_blank" class="btn btn-xs btn-outline-primary" title="Cetak Kuitansi">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-2 text-muted">Belum ada riwayat pembayaran.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer bg-light px-4 py-3 justify-content-end">
                            <button type="button" class="btn btn-secondary px-3 mr-2" data-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-add px-4" {{ $sisaItem <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-money-bill-wave mr-1"></i> Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">
                            <i class="fas fa-trash text-danger mr-2"></i>
                            Konfirmasi Hapus
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">
                            Apakah Anda yakin ingin menghapus tagihan Daftar Ulang untuk siswa <strong>{{ $item->siswa->nama ?? '-' }}</strong>?
                        </p>
                        <div class="alert alert-danger mb-0 small">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Data pembayaran yang dihapus tidak dapat dikembalikan.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Batal
                        </button>
                        <form action="{{ route('du.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash mr-1"></i> Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- MODAL CETAK BUKTI SETELAH PEMBAYARAN BERHASIL --}}
    @if(session('last_detail_id'))
        <div class="modal fade" id="modalCetakBukti" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header modal-header-payment">
                        <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle text-white mr-2"></i> Pembayaran Berhasil</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-receipt text-success fa-3x mb-3"></i>
                        <h5>Pembayaran Daftar Ulang berhasil dicatat!</h5>
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
    @endif

@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('duTable');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const searchInput = document.getElementById('duSearchInput');
    let sortColumn = '';
    let sortDirection = 'asc';

    function getRows() {
        return Array.from(tbody.querySelectorAll('tr[data-nis]'));
    }

    function updateNumber() {
        const rows = getRows();
        let number = 1;
        rows.forEach(function (row) {
            const numberCell = row.querySelector('.row-number');
            if (numberCell && row.style.display !== 'none') {
                numberCell.textContent = number;
                number++;
            }
        });
    }

    function updateSortIcon() {
        document.querySelectorAll('#duTable th.sortable').forEach(function (header) {
            header.classList.remove('sort-active');
            const icon = header.querySelector('.sort-icon');
            if (icon) icon.className = 'fas fa-sort sort-icon';
        });

        const activeHeader = document.querySelector('#duTable th[data-sort="' + sortColumn + '"]');
        if (!activeHeader) return;
        activeHeader.classList.add('sort-active');
        const icon = activeHeader.querySelector('.sort-icon');
        if (icon) {
            icon.className = (sortDirection === 'asc') ? 'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
        }
    }

    function sortTable(column) {
        if (sortColumn === column) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortColumn = column;
            sortDirection = 'asc';
        }

        const rows = getRows();
        rows.sort(function (a, b) {
            let valueA, valueB;
            if (column === 'nis') {
                valueA = a.dataset.nis || '';
                valueB = b.dataset.nis || '';
                return sortDirection === 'asc'
                    ? valueA.localeCompare(valueB, undefined, { numeric: true, sensitivity: 'base' })
                    : valueB.localeCompare(valueA, undefined, { numeric: true, sensitivity: 'base' });
            }
            if (column === 'nama') {
                valueA = (a.dataset.nama || '').toLowerCase();
                valueB = (b.dataset.nama || '').toLowerCase();
                return sortDirection === 'asc' ? valueA.localeCompare(valueB, 'id') : valueB.localeCompare(valueA, 'id');
            }
            if (column === 'sisa') {
                valueA = Number(a.dataset.sisa || 0);
                valueB = Number(b.dataset.sisa || 0);
                return sortDirection === 'asc' ? valueA - valueB : valueB - valueA;
            }
            return 0;
        });

        rows.forEach(function (row) {
            tbody.appendChild(row);
        });

        updateNumber();
        updateSortIcon();
    }

    document.querySelectorAll('#duTable th.sortable').forEach(function (header) {
        header.addEventListener('click', function () {
            sortTable(this.dataset.sort);
        });
    });

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const query = this.value.toLowerCase().trim();
            getRows().forEach(function (row) {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
            updateNumber();
        });
    }

    // Default sort Nama A-Z
    sortColumn = '';
    sortDirection = 'asc';
    sortTable('nama');

    @if(session('last_detail_id'))
        $('#modalCetakBukti').modal('show');
        $('#btnCetakBukti').on('click', function () {
            setTimeout(function () {
                $('#modalCetakBukti').modal('hide');
            }, 500);
        });
    @endif

    @if(session('open_modal_tambah') === 'du' || $errors->has('siswa_id'))
        $('#modalTambahDu').modal('show');
    @endif

    // Handler Modal Tambah DU
    const modalDuSiswaSelect = document.getElementById('modal_du_siswa_id');
    const modalDuSelectedSiswaBox = document.getElementById('modalDuSelectedSiswaBox');
    const modalDuPreviewNama = document.getElementById('modalDuPreviewNama');
    const modalDuPreviewNis = document.getElementById('modalDuPreviewNis');
    const modalDuPreviewKelas = document.getElementById('modalDuPreviewKelas');
    const modalDuTargetInput = document.getElementById('modal_du_target');
    const modalDuSummaryTotalLabel = document.getElementById('modalDuSummaryTotalLabel');
    const modalDuDuplicateAlert = document.getElementById('modalDuDuplicateAlert');
    const modalDuSubmitBtn = document.getElementById('btnSubmitTambahDu');

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value || 0);
    }

    if (modalDuSiswaSelect) {
        modalDuSiswaSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                modalDuPreviewNama.textContent = opt.dataset.nama || '-';
                modalDuPreviewNis.textContent = opt.dataset.nis || '-';
                modalDuPreviewKelas.textContent = opt.dataset.kelas || '-';
                modalDuSelectedSiswaBox.classList.remove('d-none');

                if (opt.dataset.exists === '1') {
                    if (modalDuDuplicateAlert) modalDuDuplicateAlert.classList.remove('d-none');
                    if (modalDuSubmitBtn) modalDuSubmitBtn.disabled = true;
                } else {
                    if (modalDuDuplicateAlert) modalDuDuplicateAlert.classList.add('d-none');
                    if (modalDuSubmitBtn) modalDuSubmitBtn.disabled = false;
                }
            } else {
                modalDuSelectedSiswaBox.classList.add('d-none');
                if (modalDuDuplicateAlert) modalDuDuplicateAlert.classList.add('d-none');
                if (modalDuSubmitBtn) modalDuSubmitBtn.disabled = false;
            }
        });
    }

    if (modalDuTargetInput && modalDuSummaryTotalLabel) {
        modalDuTargetInput.addEventListener('input', function () {
            const val = parseFloat(this.value) || 0;
            modalDuSummaryTotalLabel.textContent = `Rp ${formatRupiah(val)}`;
        });
    }
});
</script>
@stop