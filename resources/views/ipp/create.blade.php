@extends('adminlte::page')

@section('title', 'Tambah Tagihan IPP')

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
        <div class="col-lg-9 col-md-11">

            <div class="card shadow-sm border-0">

                <div class="card-header card-header-payment py-3 px-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 font-weight-bold" style="font-size: 16px;">
                            <i class="fas fa-wallet mr-2"></i>
                            Tambah Tagihan IPP (Iuran Pembayaran Pendidikan)
                        </h5>
                        <span class="badge badge-light px-3 py-1 font-weight-bold text-success" style="font-size: 12px;">
                            SPP / Semester
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('ipp.store') }}" method="POST" id="formTambahIpp">
                        @csrf

                        {{-- Alert Jika Siswa Duplikat --}}
                        <div id="siswaDuplicateAlert" class="alert alert-danger d-none mb-3 py-2 px-3">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <strong>Peringatan:</strong> Siswa ini sudah memiliki data tagihan IPP pada tahun ajaran ini. Tidak dapat menambahkan tagihan ganda!
                        </div>

                        {{-- Section 1: Pilih Siswa --}}
                        <div class="form-group row mb-4">
                            <label for="siswa_id" class="col-sm-3 col-form-label font-weight-bold">
                                Pilih Siswa <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select name="siswa_id"
                                        id="siswa_id"
                                        class="form-control select2 @error('siswa_id') is-invalid @enderror"
                                        required>
                                    <option value="">-- Pilih Siswa yang Ditagihkan --</option>
                                    @foreach($siswa as $item)
                                        @php $isAlreadyTagged = in_array($item->id, $existingSiswaIds ?? []); @endphp
                                        <option value="{{ $item->id }}"
                                                data-nis="{{ $item->nis }}"
                                                data-nama="{{ $item->nama }}"
                                                data-kelas="{{ $item->kelas }}"
                                                data-exists="{{ $isAlreadyTagged ? '1' : '0' }}"
                                                {{ $isAlreadyTagged ? 'disabled class=text-muted' : '' }}
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

                        {{-- Section 2: Input Nominal Tagihan Bulanan --}}
                        <div class="form-group row mb-4">
                            <label for="nominal_per_bulan" class="col-sm-3 col-form-label font-weight-bold">
                                Nominal per Bulan <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light">Rp</span>
                                    </div>
                                    <input type="number"
                                           id="nominal_per_bulan"
                                           class="form-control font-weight-bold text-success"
                                           placeholder="Masukkan nominal per bulan (contoh: 100.000)..."
                                           min="0"
                                           step="1000"
                                           required>
                                </div>
                                <small class="form-text text-muted">
                                    Masukkan tarif tagihan 1 bulan, sistem akan otomatis menghitung total 6 bulan (1 semester).
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="jumlah_bulan" class="col-sm-3 col-form-label font-weight-bold">
                                Durasi Tagihan
                            </label>
                            <div class="col-sm-9">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                    <select id="jumlah_bulan" class="form-control" style="width: auto; min-width: 220px;">
                                        <option value="12" selected>12 Bulan (1 Tahun / 2 Semester)</option>
                                        <option value="6">6 Bulan (1 Semester)</option>
                                        <option value="1">1 Bulan</option>
                                        <option value="2">2 Bulan</option>
                                        <option value="3">3 Bulan (1 Triwulan)</option>
                                        <option value="4">4 Bulan</option>
                                        <option value="5">5 Bulan</option>
                                    </select>
                                </div>

                                {{-- Visual Breakdown Tiap Bulan --}}
                                <div class="p-3 bg-light border rounded mb-2">
                                    <div class="small font-weight-bold text-muted mb-2">
                                        <i class="fas fa-list-ol mr-1"></i> Rincian Nominal Tiap Bulan:
                                    </div>
                                    <div id="breakdownBulanContainer" class="d-flex flex-wrap gap-2" style="gap: 8px;">
                                        <!-- Injected by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden Target Input to be sent to backend --}}
                        <input type="hidden" name="target" id="finalTarget" value="{{ old('target', 0) }}">

                        <div class="form-group row mb-4">
                            <label for="potongan" class="col-sm-3 col-form-label font-weight-bold">Potongan IPP (Rp)</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold bg-light">Rp</span></div>
                                    <input type="number" name="potongan" id="potongan"
                                           class="form-control font-weight-bold text-warning @error('potongan') is-invalid @enderror"
                                           value="{{ old('potongan', 0) }}" min="0" step="1000" placeholder="0">
                                </div>
                                <small class="form-text text-muted">Potongan bukan pembayaran tunai dan tidak boleh melebihi tagihan.</small>
                                @error('potongan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Section 3: Summary Fee Box --}}
                        <div class="fee-info-box my-4">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Nominal per Bulan</div>
                                    <div class="font-weight-bold text-dark" id="summaryNominalBulan">
                                        Rp 0
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 mb-2 mb-md-0">
                                    <div class="text-muted small">Kalkulasi Durasi</div>
                                    <div class="font-weight-bold text-dark" id="summaryKalkulasi">
                                        Rp 0 × 12 Bulan
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="text-muted small">Total Tagihan Disimpan</div>
                                    <div class="font-weight-bold text-success" style="font-size: 20px;" id="summaryTotalLabel">
                                        Rp 0
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section 4: Action Footer --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('ipp.index') }}" class="btn btn-secondary px-3">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>

                            <button type="submit" id="btnSubmitIpp" class="btn btn-success px-4 font-weight-bold">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Tagihan IPP
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
            const submitBtn = document.getElementById('btnSubmitIpp');

            const inputNominalPerBulan = document.getElementById('nominal_per_bulan');
            const selectJumlahBulan = document.getElementById('jumlah_bulan');
            const breakdownContainer = document.getElementById('breakdownBulanContainer');
            const inputFinalTarget = document.getElementById('finalTarget');

            const summaryNominalBulan = document.getElementById('summaryNominalBulan');
            const summaryKalkulasi = document.getElementById('summaryKalkulasi');
            const summaryTotalLabel = document.getElementById('summaryTotalLabel');

            const bulanList = ['Bulan 1 (Juli)', 'Bulan 2 (Agustus)', 'Bulan 3 (September)', 'Bulan 4 (Oktober)', 'Bulan 5 (November)', 'Bulan 6 (Desember)', 'Bulan 7 (Januari)', 'Bulan 8 (Februari)', 'Bulan 9 (Maret)', 'Bulan 10 (April)', 'Bulan 11 (Mei)', 'Bulan 12 (Juni)'];

            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID').format(value || 0);
            }

            // Student selection preview
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

            // Calculate total and update summary
            function calculateTotal() {
                const nominalBulan = parseFloat(inputNominalPerBulan ? inputNominalPerBulan.value : 0) || 0;
                const jumlahBulan = parseInt(selectJumlahBulan ? selectJumlahBulan.value : 12) || 12;
                const total = nominalBulan * jumlahBulan;

                inputFinalTarget.value = total;

                if (summaryNominalBulan) {
                    summaryNominalBulan.textContent = nominalBulan > 0 ? `Rp ${formatRupiah(nominalBulan)}` : 'Rp 0';
                }
                if (summaryKalkulasi) {
                    summaryKalkulasi.textContent = nominalBulan > 0 ? `Rp ${formatRupiah(nominalBulan)} × ${jumlahBulan} Bulan` : `Rp 0 × ${jumlahBulan} Bulan`;
                }
                if (summaryTotalLabel) {
                    summaryTotalLabel.textContent = total > 0 ? `Rp ${formatRupiah(total)}` : 'Rp 0';
                }

                if (breakdownContainer) {
                    breakdownContainer.innerHTML = '';
                    for (let i = 0; i < jumlahBulan; i++) {
                        const chip = document.createElement('div');
                        chip.className = 'month-chip';
                        chip.innerHTML = `
                            <span class="month-name">${bulanList[i] || `Bulan ${i + 1}`}</span>
                            <span class="month-amount">${nominalBulan > 0 ? 'Rp ' + formatRupiah(nominalBulan) : 'Rp 0'}</span>
                        `;
                        breakdownContainer.appendChild(chip);
                    }
                }
            }

            if (inputNominalPerBulan) inputNominalPerBulan.addEventListener('input', calculateTotal);
            if (selectJumlahBulan) selectJumlahBulan.addEventListener('change', calculateTotal);

            // Initial calculation
            calculateTotal();
        });
    </script>
@stop