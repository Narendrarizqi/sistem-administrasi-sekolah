<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Pembayaran Seluruh Siswa - {{ $tahunAjaranNama }}</title>
    <style>
        @page {
            margin: 12mm 10mm 12mm 10mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.35;
        }

        /* 1. Header & Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .kop-logo {
            width: 48px;
            vertical-align: middle;
            text-align: left;
        }
        .kop-logo img {
            width: 44px;
            height: 44px;
        }
        .kop-text {
            vertical-align: middle;
            text-align: center;
            padding-right: 48px;
        }
        .kop-instansi {
            font-size: 8.5px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .kop-sekolah {
            font-size: 13.5px;
            font-weight: bold;
            color: #15803d;
            margin: 1px 0;
            letter-spacing: 0.5px;
        }
        .kop-alamat {
            font-size: 7.5px;
            color: #64748b;
            margin: 0;
        }

        /* 2. Judul Laporan */
        .doc-title {
            text-align: center;
            margin: 8px 0 10px 0;
        }
        .doc-title h1 {
            font-size: 12.5px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 8.5px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        /* 3. Info Meta */
        .info-card {
            width: 100%;
            border-collapse: collapse;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        .info-card td {
            padding: 4px 8px;
            font-size: 9px;
            vertical-align: top;
        }
        .info-label {
            color: #64748b;
            width: 18%;
        }
        .info-sep {
            width: 2%;
            color: #64748b;
            text-align: center;
        }
        .info-val {
            font-weight: bold;
            color: #0f172a;
            width: 30%;
        }

        /* 4. Tabel Data */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table-data th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 8.5px;
            font-weight: bold;
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
            text-align: left;
            text-transform: uppercase;
        }
        .table-data td {
            padding: 4.5px 6px;
            border: 1px solid #cbd5e1;
            font-size: 8.5px;
            vertical-align: middle;
        }
        .table-data tfoot td {
            font-weight: bold;
            background-color: #f8fafc;
        }

        /* Alignment Utilities */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .text-left   { text-align: left; }

        /* Status Colors */
        .status-lunas {
            color: #15803d;
            font-weight: bold;
        }
        .status-sebagian {
            color: #b45309;
            font-weight: bold;
        }
        .status-belum {
            color: #dc2626;
            font-weight: bold;
        }
        .status-none {
            color: #94a3b8;
        }

        /* Box Total */
        .box-sisa {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 14px 0;
            border: 1.5px solid #16a34a;
            background: #f0fdf4;
            border-radius: 4px;
            page-break-inside: avoid;
        }
        .box-sisa td {
            padding: 8px 12px;
            vertical-align: middle;
        }
        .box-sisa-label {
            font-size: 9.5px;
            font-weight: bold;
            color: #15803d;
            text-transform: uppercase;
        }
        .box-sisa-sub {
            font-size: 8px;
            color: #166534;
            margin-top: 1px;
        }
        .box-sisa-val {
            font-size: 15px;
            font-weight: bold;
            color: #dc2626;
            text-align: right;
        }

        /* Lembar Tanda Tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            font-size: 8.5px;
        }
        .ttd-space {
            height: 48px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        /* Footer Note */
        .footer-note {
            font-size: 7.5px;
            color: #94a3b8;
            margin-top: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- 1. KOP SURAT SEKOLAH RESMI --}}
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

    {{-- 2. JUDUL DOKUMEN --}}
    <div class="doc-title">
        <h1>LAPORAN REKAP PEMBAYARAN SISWA</h1>
        <p>Rekapitulasi Keuangan Seluruh Siswa &bull; Tahun Ajaran {{ $tahunAjaranNama }}</p>
    </div>

    {{-- 3. INFORMASI METADATA --}}
    <table class="info-card">
        <tr>
            <td class="info-label">Cakupan Laporan</td>
            <td class="info-sep">:</td>
            <td class="info-val">Seluruh Siswa (Aktif)</td>
            <td class="info-label">Tahun Ajaran</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $tahunAjaranNama }}</td>
        </tr>
        <tr>
            <td class="info-label">Total Siswa Terdaftar</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $students->count() }} Siswa</td>
            <td class="info-label">Tanggal Cetak</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $tanggalCetak }}</td>
        </tr>
    </table>

    {{-- 4. TABEL REKAPITULASI SELURUH SISWA --}}
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 10%;">NIS</th>
                <th style="width: 22%;">Nama Lengkap Siswa</th>
                <th style="width: 10%;" class="text-center">Kelas</th>
                <th style="width: 14%;" class="text-right">Total Tagihan (Rp)</th>
                <th style="width: 14%;" class="text-right">Total Terbayar (Rp)</th>
                <th style="width: 14%;" class="text-right">Sisa Tagihan (Rp)</th>
                <th style="width: 12%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $st)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $st['siswa']->nis ?? '-' }}</td>
                    <td><strong>{{ $st['siswa']->nama ?? '-' }}</strong></td>
                    <td class="text-center">{{ $st['siswa']->kelas ?? '-' }}</td>
                    <td class="text-right"><strong>{{ number_format($st['total_tagihan'], 0, ',', '.') }}</strong></td>
                    <td class="text-right" style="color:#15803d;">
                        @if($st['terbayar'] > 0)
                            {{ number_format($st['terbayar'], 0, ',', '.') }}
                        @else
                            <span class="status-none">0</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($st['sisa'] > 0)
                            <strong class="status-belum">{{ number_format($st['sisa'], 0, ',', '.') }}</strong>
                        @elseif($st['total_tagihan'] > 0)
                            <span class="status-lunas">0</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($st['status'] === 'Lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($st['status'] === 'Sebagian')
                            <span class="status-sebagian">Sebagian</span>
                        @elseif($st['status'] === 'Belum Lunas')
                            <span class="status-belum">Belum Lunas</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 12px; color: #94a3b8;">
                        Tidak ada data pembayaran yang ditemukan pada tahun ajaran ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($students->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center" style="font-weight: bold; background:#f1f5f9;">TOTAL KESELURUHAN</td>
                    <td class="text-right">{{ number_format($grandTotal['total_tagihan'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color:#15803d;">{{ number_format($grandTotal['terbayar'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color:#dc2626;">{{ number_format($grandTotal['sisa'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($grandTotal['sisa'] <= 0 && $grandTotal['total_tagihan'] > 0)
                            <span class="status-lunas">LUNAS</span>
                        @elseif($grandTotal['terbayar'] > 0)
                            <span class="status-sebagian">SEBAGIAN</span>
                        @else
                            <span class="status-belum">BELUM LUNAS</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- 5. BOX TOTAL SISA PIUTANG SEKOLAH --}}
    <table class="box-sisa">
        <tr>
            <td>
                <div class="box-sisa-label">Total Sisa Piutang Pembayaran Siswa</div>
                <div class="box-sisa-sub">
                    Akumulasi seluruh kewajiban tagihan yang belum terbayarkan oleh siswa per {{ $tanggalCetak }}
                </div>
            </td>
            <td class="box-sisa-val {{ $grandTotal['sisa'] <= 0 ? 'status-lunas' : '' }}">
                Rp {{ number_format($grandTotal['sisa'], 0, ',', '.') }}
            </td>
        </tr>
    </table>

    {{-- 6. LEMBAR TANDA TANGAN --}}
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
                Bendahara / Petugas Administrasi Keuangan
                <div class="ttd-space"></div>
                <div class="ttd-nama">Admin Keuangan SMK Muhammadiyah</div>
            </td>
        </tr>
    </table>

    {{-- 7. FOOTER NOTE --}}
    <div class="footer-note">
        Dokumen ini diterbitkan secara resmi melalui Sistem Informasi Pembayaran Sekolah SMK Muhammadiyah Margasari &bull; Dicetak pada {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</body>
</html>