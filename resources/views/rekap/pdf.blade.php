<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Target Pemasukan Keuangan Sekolah - {{ $tahunAjaranNama }}</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 6mm 6mm 6mm 6mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7.5px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }

        /* 1. KOP SURAT SEKOLAH RESMI */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #16a34a;
            margin-bottom: 6px;
            padding-bottom: 4px;
        }
        .kop-logo {
            width: 40px;
            vertical-align: middle;
            text-align: left;
        }
        .kop-logo img {
            width: 36px;
            height: 36px;
        }
        .kop-text {
            vertical-align: middle;
            text-align: center;
            padding-right: 40px;
        }
        .kop-instansi {
            font-size: 7.5px;
            font-weight: bold;
            color: #475569;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-sekolah {
            font-size: 12px;
            font-weight: bold;
            color: #15803d;
            margin: 1px 0;
            letter-spacing: 0.5px;
        }
        .kop-alamat {
            font-size: 7px;
            color: #64748b;
            margin: 0;
        }

        /* 2. JUDUL DOKUMEN */
        .doc-title {
            margin-bottom: 6px;
        }
        .doc-title h1 {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 8px;
            color: #475569;
            margin: 1px 0 0 0;
        }

        /* 3. SECTION HEADER KELAS */
        .class-header {
            font-size: 8.5px;
            font-weight: bold;
            background: #f1f5f9;
            color: #0f172a;
            padding: 3px 6px;
            margin-top: 6px;
            margin-bottom: 3px;
            border-left: 3px solid #16a34a;
            border-top: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
        }

        /* 4. TABEL REKAPITULASI */
        table.table-rekap {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 8px;
        }
        table.table-rekap th, table.table-rekap td {
            border: 1px solid #cbd5e1;
            padding: 2.5px 1.5px;
            font-size: 6.8px;
            line-height: 1.15;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        table.table-rekap th {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        table.table-rekap th.th-group-ipp {
            background-color: #f0fdf4;
            color: #166534;
            border-bottom: 1px solid #86efac;
        }
        table.table-rekap th.th-group-asesmen {
            background-color: #eff6ff;
            color: #1e40af;
            border-bottom: 1px solid #93c5fd;
        }
        table.table-rekap td.text-left {
            text-align: left;
            padding-left: 3px;
        }
        table.table-rekap td.col-nama-cell {
            text-align: left;
            padding-left: 3px;
            padding-right: 2px;
            white-space: normal !important;
            word-wrap: break-word;
            line-height: 1.2;
        }
        table.table-rekap td.text-center {
            text-align: center;
        }
        table.table-rekap td.text-right {
            text-align: right;
            padding-right: 3px;
        }

        /* Format Nilai Angka */
        .val-zero {
            color: #94a3b8;
            font-weight: normal;
        }
        .val-paid {
            color: #15803d;
            font-weight: 600;
        }
        .val-sisa {
            color: #dc2626;
            font-weight: bold;
        }
        .val-target {
            color: #0f172a;
            font-weight: 600;
        }

        tr.row-subtotal td {
            background-color: #f8fafc;
            font-weight: bold;
            border-top: 1.5px solid #94a3b8;
        }

        /* Lebar Kolom */
        .w-no { width: 14px; }
        .w-nama { width: 160px; }
        .w-sarpras { width: 33px; }
        .w-du { width: 33px; }
        .w-bln { width: 25px; }
        .w-asm { width: 29px; }
        .w-ekskul { width: 29px; }
        .w-koku { width: 29px; }
        .w-total { width: 40px; }

        /* 5. TABEL RINGKASAN REKAPITULASI PER KELAS */
        table.table-summary-kelas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        table.table-summary-kelas th, table.table-summary-kelas td {
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
            font-size: 8px;
            line-height: 1.25;
        }
        table.table-summary-kelas th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
        }
        table.table-summary-kelas td.text-left { text-align: left; }
        table.table-summary-kelas td.text-center { text-align: center; }
        table.table-summary-kelas td.text-right { text-align: right; }

        /* 6. KARTU RINGKASAN KEUANGAN */
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        .summary-box td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            font-size: 7.5px;
        }
        .summary-card-blue { background-color: #eff6ff; }
        .summary-card-green { background-color: #f0fdf4; }
        .summary-card-red { background-color: #fef2f2; }
        .summary-val {
            font-size: 10px;
            font-weight: bold;
            margin-top: 1px;
        }

        /* 6. LEMBAR TANDA TANGAN */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            width: 50%;
            text-align: center;
            font-size: 7.5px;
            vertical-align: top;
        }
        .ttd-space {
            height: 35px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        /* 7. FOOTER NOTE */
        .footer-note {
            font-size: 6.5px;
            color: #94a3b8;
            margin-top: 8px;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI SEKOLAH --}}
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            </td>
            <td class="kop-text">
                <p class="kop-instansi">Pimpinan Cabang Muhammadiyah Margasari &bull; Majelis Dikdasmen</p>
                <h2 class="kop-sekolah">SMK MUHAMMADIYAH MARGASARI</h2>
                <p class="kop-alamat">Jl. Raya Margasari, Kec. Margasari, Kab. Tegal, Jawa Tengah 52463</p>
            </td>
        </tr>
    </table>

    {{-- JUDUL LAPORAN --}}
    <div class="doc-title">
        <h1>TARGET PEMASUKAN KEUANGAN SEKOLAH</h1>
        <p>
            Tahun Ajaran {{ $tahunAjaranNama }}
            @if($kelasFilter) &bull; Kelas: {{ $kelasFilter }} @endif
            &bull; Tanggal Cetak: {{ $tanggalCetak }}
            &bull; Total: {{ $grandTotal['total_siswa'] }} Siswa
        </p>
    </div>

    {{-- DAFTAR TABEL PER KELAS --}}
    @forelse($groupedByKelas as $namaKelas => $students)
        <div class="class-header">Kelas: {{ $namaKelas }}</div>
        <table class="table-rekap">
            <thead>
                <tr>
                    <th rowspan="2" class="w-no">No</th>
                    <th rowspan="2" class="w-nama">Nama</th>
                    <th rowspan="2" class="w-sarpras">Sarpras</th>
                    <th rowspan="2" class="w-du">Daftar<br>Ulang</th>
                    <th colspan="12" class="th-group-ipp">Ipp</th>
                    <th colspan="4" class="th-group-asesmen">Asesmen</th>
                    @if($hasEkskul)
                        <th rowspan="2" class="w-ekskul">Ekskul</th>
                    @endif
                    @if($hasKoku)
                        <th rowspan="2" class="w-koku">Koku</th>
                    @endif
                    <th rowspan="2" class="w-total">Target</th>
                    <th rowspan="2" class="w-total">Terbayar</th>
                    <th rowspan="2" class="w-total">Sisa</th>
                </tr>
                <tr>
                    {{-- 12 Bulan IPP --}}
                    @foreach($bulanList as $b)
                        <th class="w-bln">{{ $b['short'] }}</th>
                    @endforeach

                    {{-- 4 Komponen Asesmen --}}
                    @foreach($asesmenItems as $asm)
                        <th class="w-asm">{{ $asm['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $idx => $st)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="col-nama-cell"><strong>{{ $st['siswa']->nama }}</strong></td>

                        {{-- Sarpras --}}
                        <td class="text-right">
                            @if($st['sarpras'] > 0)
                                <span class="val-paid">{{ number_format($st['sarpras'], 0, ',', '.') }}</span>
                            @else
                                <span class="val-zero">0</span>
                            @endif
                        </td>

                        {{-- Daftar Ulang --}}
                        <td class="text-right">
                            @if($st['du'] > 0)
                                <span class="val-paid">{{ number_format($st['du'], 0, ',', '.') }}</span>
                            @else
                                <span class="val-zero">0</span>
                            @endif
                        </td>

                        {{-- IPP per Bulan --}}
                        @foreach($bulanList as $b)
                            @php $blnVal = $st['ipp_bulan'][$b['nama']] ?? 0; @endphp
                            <td class="text-right">
                                @if($blnVal > 0)
                                    <span class="val-paid">{{ number_format($blnVal, 0, ',', '.') }}</span>
                                @else
                                    <span class="val-zero">0</span>
                                @endif
                            </td>
                        @endforeach

                        {{-- Asesmen --}}
                        @foreach($asesmenItems as $asm)
                            @php $asmVal = $st['asesmen'][$asm['key']] ?? 0; @endphp
                            <td class="text-right">
                                @if($asmVal > 0)
                                    <span class="val-paid">{{ number_format($asmVal, 0, ',', '.') }}</span>
                                @else
                                    <span class="val-zero">0</span>
                                @endif
                            </td>
                        @endforeach

                        {{-- Ekskul (jika ada) --}}
                        @if($hasEkskul)
                            <td class="text-right">
                                @if($st['ekskul'] > 0)
                                    <span class="val-paid">{{ number_format($st['ekskul'], 0, ',', '.') }}</span>
                                @else
                                    <span class="val-zero">0</span>
                                @endif
                            </td>
                        @endif

                        {{-- Kokurikuler (jika ada) --}}
                        @if($hasKoku)
                            <td class="text-right">
                                @if($st['kokurikuler'] > 0)
                                    <span class="val-paid">{{ number_format($st['kokurikuler'], 0, ',', '.') }}</span>
                                @else
                                    <span class="val-zero">0</span>
                                @endif
                            </td>
                        @endif

                        {{-- Total Target, Terbayar, Sisa --}}
                        <td class="text-right val-target">
                            {{ number_format($st['total_tagihan'], 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                            @if($st['total_terbayar'] > 0)
                                <span class="val-paid">{{ number_format($st['total_terbayar'], 0, ',', '.') }}</span>
                            @else
                                <span class="val-zero">0</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($st['sisa'] > 0)
                                <span class="val-sisa">{{ number_format($st['sisa'], 0, ',', '.') }}</span>
                            @else
                                <span class="val-paid">0</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php $sub = $classSubtotals[$namaKelas] ?? null; @endphp
                @if($sub)
                    <tr class="row-subtotal">
                        <td colspan="2" class="text-center">TOTAL KELAS</td>
                        <td class="text-right">{{ $sub['sarpras'] > 0 ? number_format($sub['sarpras'], 0, ',', '.') : '0' }}</td>
                        <td class="text-right">{{ $sub['du'] > 0 ? number_format($sub['du'], 0, ',', '.') : '0' }}</td>

                        @foreach($bulanList as $b)
                            @php $subBln = $sub['ipp_bulan'][$b['nama']] ?? 0; @endphp
                            <td class="text-right">{{ $subBln > 0 ? number_format($subBln, 0, ',', '.') : '0' }}</td>
                        @endforeach

                        @foreach($asesmenItems as $asm)
                            @php $subAsm = $sub['asesmen'][$asm['key']] ?? 0; @endphp
                            <td class="text-right">{{ $subAsm > 0 ? number_format($subAsm, 0, ',', '.') : '0' }}</td>
                        @endforeach

                        @if($hasEkskul)
                            <td class="text-right">{{ $sub['ekskul'] > 0 ? number_format($sub['ekskul'], 0, ',', '.') : '0' }}</td>
                        @endif

                        @if($hasKoku)
                            <td class="text-right">{{ $sub['kokurikuler'] > 0 ? number_format($sub['kokurikuler'], 0, ',', '.') : '0' }}</td>
                        @endif

                        <td class="text-right val-target">{{ number_format($sub['total_tagihan'], 0, ',', '.') }}</td>
                        <td class="text-right val-paid">{{ number_format($sub['total_terbayar'], 0, ',', '.') }}</td>
                        <td class="text-right val-sisa">{{ number_format($sub['sisa'], 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tfoot>
        </table>
    @empty
        <div style="padding: 20px; text-align: center; color: #94a3b8; border: 1px solid #cbd5e1;">
            Tidak ada data pembayaran yang ditemukan.
        </div>
    @endforelse

    {{-- REKAPITULASI GRAND TOTAL TARGET, TERBAYAR & SISA PER KELAS --}}
    <div class="class-header" style="background: #0f172a; color: #ffffff; border-color: #0f172a; margin-top: 10px;">
        REKAPITULASI TOTAL TARGET, TERBAYAR & SISA {{ $kelasFilter ? 'KELAS ' . strtoupper($kelasFilter) : 'PER KELAS (SEMUA KELAS)' }}
    </div>
    <table class="table-summary-kelas">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 28%;" class="text-left">Kelas</th>
                <th style="width: 15%;" class="text-center">Jumlah Siswa</th>
                <th style="width: 17%;" class="text-right">Total Target</th>
                <th style="width: 17%;" class="text-right">Total Terbayar</th>
                <th style="width: 18%;" class="text-right">Sisa Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedByKelas as $namaKelas => $students)
                @php $sub = $classSubtotals[$namaKelas] ?? null; @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-left"><strong>Kelas {{ $namaKelas }}</strong></td>
                    <td class="text-center">{{ $students->count() }} Siswa</td>
                    <td class="text-right val-target">Rp {{ number_format($sub['total_tagihan'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right val-paid">Rp {{ number_format($sub['total_terbayar'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right val-sisa">Rp {{ number_format($sub['sisa'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="row-subtotal" style="background: #f1f5f9;">
                <td colspan="2" class="text-center" style="font-weight: bold;">TOTAL KESELURUHAN</td>
                <td class="text-center" style="font-weight: bold;">{{ $grandTotal['total_siswa'] }} Siswa</td>
                <td class="text-right val-target" style="font-weight: bold;">Rp {{ number_format($grandTotal['total_tagihan'], 0, ',', '.') }}</td>
                <td class="text-right val-paid" style="font-weight: bold;">Rp {{ number_format($grandTotal['total_terbayar'], 0, ',', '.') }}</td>
                <td class="text-right val-sisa" style="font-weight: bold;">Rp {{ number_format($grandTotal['sisa'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- RINGKASAN TOTAL BOX --}}
    <table class="summary-box">
        <tr>
            <td class="summary-card-blue" style="width: 33.3%;">
                <span style="color: #1e40af; font-weight: bold;">TOTAL TARGET PEMASUKAN</span>
                <div class="summary-val" style="color: #1e3a8a;">
                    Rp {{ number_format($grandTotal['total_tagihan'], 0, ',', '.') }}
                </div>
            </td>
            <td class="summary-card-green" style="width: 33.3%;">
                <span style="color: #166534; font-weight: bold;">TOTAL PEMBAYARAN MASUK (TERBAYAR)</span>
                <div class="summary-val" style="color: #14532d;">
                    Rp {{ number_format($grandTotal['total_terbayar'], 0, ',', '.') }}
                </div>
            </td>
            <td class="summary-card-red" style="width: 33.3%;">
                <span style="color: #991b1b; font-weight: bold;">TOTAL SISA PIUTANG SEKOLAH</span>
                <div class="summary-val" style="color: #7f1d1d;">
                    Rp {{ number_format($grandTotal['sisa'], 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    {{-- LEMBAR TANDA TANGAN --}}
    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah SMK Muhammadiyah Margasari
                <div class="ttd-space"></div>
                <div class="ttd-nama">( ...................................................... )</div>
            </td>
            <td>
                Margasari, {{ $tanggalCetak }}<br>
                Bendahara Keuangan Sekolah
                <div class="ttd-space"></div>
                <div class="ttd-nama">Admin Keuangan SMK Muhammadiyah</div>
            </td>
        </tr>
    </table>

    {{-- FOOTER NOTE --}}
    <div class="footer-note">
        Dokumen ini diterbitkan secara resmi melalui Sistem Administrasi Sekolah SMK Muhammadiyah Margasari &bull; Dicetak pada {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</body>
</html>