@extends('adminlte::page')

@section('title', 'Pengeluaran')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('content')

<style>
    .modal-footer .btn {
        height: 38px;
        width: 110px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-circle-check me-2"></i>
            {{ session('success') }}

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
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
                <span class="text-dark font-weight-bold">Pengeluaran</span>
            </div>
            <h1 class="page-header-title">
                <span class="page-header-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"><i class="fas fa-money-bill-wave"></i></span>
                Manajemen Pengeluaran
            </h1>
            <p class="page-header-desc">
                Catat dan pantau seluruh transaksi pengeluaran operasional, sarana prasarana, serta anggaran sekolah.
            </p>
        </div>
        <div class="page-header-badges">
            <span class="badge-page-indicator" style="background:#fff1f2; color:#be123c; border-color:#fecdd3;">
                <i class="fas fa-arrow-up"></i>
                Total: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </span>
            <span class="badge bg-light text-secondary border px-3 py-2 font-weight-semibold" style="border-radius: 20px; font-size: 12px;">
                <i class="fas fa-file-invoice text-muted mr-1"></i> {{ $pengeluaran->count() }} Transaksi
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-12 col-md-4">

            <div class="stat-card stat-pastel-red">

                <div class="stat-top">

                    <div>

                        <span class="stat-label">
                            Total Pengeluaran
                        </span>

                        <h3 class="stat-value">
                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </h3>

                        <span class="stat-desc">
                            Akumulasi seluruh pengeluaran
                        </span>

                    </div>

                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="card">

        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold" style="font-size: 15px;">
                Data Pengeluaran
            </h5>
        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                <div class="table-search-box">

                    <i class="fas fa-search"></i>

                    <input type="text"
                           id="pengeluaranSearchInput"
                           placeholder="Cari keterangan...">

                </div>


                <a href="{{ route('pengeluaran.create') }}"
                   class="btn btn-add">

                    <i class="fas fa-plus me-1"></i>

                    Tambah Pengeluaran

                </a>

            </div>


            @if($pengeluaran->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle"
                           id="pengeluaranTable">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Tanggal</th>

                                <th>Sumber Dana</th>

                                <th>Keterangan</th>

                                <th>Nominal (Rp)</th>

                                <th width="150">Aksi</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($pengeluaran as $item)

                                <tr>

                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                                    </td>


                                    <td>
                                        @if($item->sumber_dana)
                                            <span class="badge bg-secondary">{{ $item->sumber_dana }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>


                                    <td>
                                        {{ $item->keterangan }}
                                    </td>


                                    <td>
                                        {{ number_format($item->nominal, 0, ',', '.') }}
                                    </td>


                                    <td class="text-center">

                                        <a href="{{ route('pengeluaran.edit', $item->id) }}"
                                           class="btn btn-sm btn-outline-warning action-btn me-1"
                                           title="Edit">

                                            <i class="fas fa-pen"></i>

                                        </a>


                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger action-btn"
                                                title="Hapus"
                                                data-toggle="modal"
                                                data-target="#modalHapusPengeluaran{{ $item->id }}">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>


                                {{-- MODAL HAPUS --}}
                                <div class="modal fade"
                                     id="modalHapusPengeluaran{{ $item->id }}"
                                     tabindex="-1"
                                     role="dialog"
                                     aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered"
                                         role="document">

                                        <div class="modal-content">


                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    <i class="fas fa-trash text-danger mr-2"></i>

                                                    Konfirmasi Hapus

                                                </h5>


                                                <button type="button"
                                                        class="close"
                                                        data-dismiss="modal"
                                                        aria-label="Close">

                                                    <span aria-hidden="true">
                                                        &times;
                                                    </span>

                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                <p class="mb-3">
                                                    Apakah kamu yakin ingin menghapus data pengeluaran ini?
                                                </p>


                                                <div class="alert alert-danger mb-0">

                                                    <div>
                                                        <strong>
                                                            {{ $item->keterangan }}
                                                        </strong>
                                                    </div>


                                                    <div class="small mt-1">

                                                        Tanggal:
                                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}

                                                    </div>


                                                    <div class="small">

                                                        Nominal:
                                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}

                                                    </div>

                                                </div>


                                                <p class="text-muted small mt-3 mb-0">

                                                    Data pengeluaran yang sudah dihapus
                                                    tidak dapat dikembalikan.

                                                </p>

                                            </div>


                                            <div class="modal-footer">


                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-dismiss="modal">

                                                    Batal

                                                </button>


                                                <form action="{{ route('pengeluaran.destroy', $item->id) }}"
                                                      method="POST"
                                                      class="d-inline">

                                                    @csrf

                                                    @method('DELETE')


                                                    <button type="submit"
                                                            class="btn btn-outline-danger">

                                                        <i class="fas fa-trash mr-1"></i>

                                                        Ya, Hapus

                                                    </button>

                                                </form>


                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-center py-5">

                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>


                    <h5 class="fw-bold">
                        Belum ada data pengeluaran
                    </h5>


                    <p class="text-muted">
                        Silakan tambahkan data pengeluaran terlebih dahulu.
                    </p>


                    <a href="{{ route('pengeluaran.create') }}"
                       class="btn btn-add">

                        <i class="fas fa-plus me-1"></i>

                        Tambah Pengeluaran

                    </a>

                </div>

            @endif

        </div>

    </div>


@section('js')

<script>

    document.getElementById('pengeluaranSearchInput')?.addEventListener('keyup', function () {

        const q = this.value.toLowerCase();

        document.querySelectorAll('#pengeluaranTable tbody tr').forEach(function (row) {

            row.style.display =
                row.innerText.toLowerCase().includes(q)
                    ? ''
                    : 'none';

        });

    });

</script>

@stop


@stop