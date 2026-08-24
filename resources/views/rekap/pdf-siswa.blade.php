<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Pembayaran - {{ $siswa->nama }} ({{ $tahunAjaranNama }})</title>
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
            padding-right: 48px; /* offset logo width for centered text */
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

        /* 3. Info Siswa (2 Kolom) */
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

        /* 4. Section Title */
        .section-header {
            font-size: 10px;
            font-weight: bold;
            color: #15803d;
            margin: 10px 0 4px 0;
            padding-bottom: 2px;
            border-bottom: 1px dashed #cbd5e1;
            text-transform: uppercase;
        }

        /* 5. Tabel Data */
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
            vertical-align: top;
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

        /* Riwayat Transaksi List */
        .tx-list {
            margin: 0;
            padding-left: 12px;
            font-size: 8px;
            color: #475569;
        }
        .tx-item {
            margin-bottom: 1.5px;
        }
        .tx-item-nom {
            font-weight: bold;
            color: #15803d;
        }

        /* Box Total Sisa */
        .box-sisa {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 14px 0;
            border: 1.5px solid #16a34a;
            background: #f0fdf4;
            border-radius: 4px;
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
        .box-sisa-val.lunas {
            color: #15803d;
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

        .page-break-inside-avoid {
            page-break-inside: avoid;
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
        <p>Tahun Ajaran {{ $tahunAjaranNama }} &bull; Tanggal Cetak: {{ $tanggalCetak }}</p>
    </div>

    {{-- 3. INFORMASI IDENTITAS SISWA --}}
    <table class="info-card">
        <tr>
            <td class="info-label">Nomor Induk Siswa (NIS)</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $siswa->nis ?? '-' }}</td>
            <td class="info-label">Tahun Ajaran</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $tahunAjaranNama }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama Lengkap Siswa</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $siswa->nama ?? '-' }}</td>
            <td class="info-label">Status Keseluruhan</td>
            <td class="info-sep">:</td>
            <td class="info-val">
                @if($grandTotal['status'] === 'Lunas')
                    <span class="status-lunas">Lunas Penuh</span>
                @elseif($grandTotal['status'] === 'Sebagian')
                    <span class="status-sebagian">Sebagian (Belum Lunas)</span>
                @elseif($grandTotal['status'] === 'Belum Lunas')
                    <span class="status-belum">Belum Bayar</span>
                @else
                    <span class="status-none">Belum Ada Tagihan</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="info-label">Tingkat / Kelas</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $siswa->kelas ?? '-' }}</td>
            <td class="info-label">Tanggal Laporan</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $tanggalCetak }}</td>
        </tr>
    </table>

    {{-- 4. BAGIAN 1: REKAPITULASI TAGIHAN KESELURUHAN --}}
    <div class="section-header">1. Rekapitulasi Tagihan &amp; Pembayaran Siswa</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th>Jenis Pembayaran</th>
                <th style="width: 14%;" class="text-right">Target Baru (Rp)</th>
                <th style="width: 15%;" class="text-right">Terbawa Thn Lalu (Rp)</th>
                <th style="width: 16%;" class="text-right">Total Kewajiban (Rp)</th>
                <th style="width: 15%;" class="text-right">Terbayar (Rp)</th>
                <th style="width: 15%;" class="text-right">Sisa Tagihan (Rp)</th>
                <th style="width: 12%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ringkasanSiswa as $row)
                <tr>
                    <td class="text-center">{{ $row['no'] }}</td>
                    <td><strong>{{ $row['jenis'] }}</strong></td>
                    <td class="text-right">{{ number_format($row['target'], 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($row['terbawa'] > 0)
                            <span class="status-sebagian">{{ number_format($row['terbawa'], 0, ',', '.') }}</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                    <td class="text-right"><strong>{{ number_format($row['total_tagihan'], 0, ',', '.') }}</strong></td>
                    <td class="text-right text-success" style="color:#15803d;">
                        @if($row['terbayar'] > 0)
                            {{ number_format($row['terbayar'], 0, ',', '.') }}
                        @else
                            <span class="status-none">0</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($row['sisa'] > 0)
                            <strong class="status-belum">{{ number_format($row['sisa'], 0, ',', '.') }}</strong>
                        @elseif($row['total_tagihan'] > 0)
                            <span class="status-lunas">0</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($row['status'] === 'Lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($row['status'] === 'Sebagian')
                            <span class="status-sebagian">Sebagian</span>
                        @elseif($row['status'] === 'Belum Lunas')
                            <span class="status-belum">Belum Lunas</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center" style="font-weight: bold; background:#f1f5f9;">TOTAL KESELURUHAN</td>
                <td class="text-right">{{ number_format($grandTotal['target'], 0, ',', '.') }}</td>
                <td class="text-right">
                    @if($grandTotal['terbawa'] > 0)
                        <span class="status-sebagian">{{ number_format($grandTotal['terbawa'], 0, ',', '.') }}</span>
                    @else
                        0
                    @endif
                </td>
                <td class="text-right">{{ number_format($grandTotal['total_tagihan'], 0, ',', '.') }}</td>
                <td class="text-right" style="color:#15803d;">{{ number_format($grandTotal['terbayar'], 0, ',', '.') }}</td>
                <td class="text-right" style="color:#dc2626;">{{ number_format($grandTotal['sisa'], 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($grandTotal['status'] === 'Lunas')
                        <span class="status-lunas">LUNAS</span>
                    @elseif($grandTotal['status'] === 'Sebagian')
                        <span class="status-sebagian">SEBAGIAN</span>
                    @elseif($grandTotal['status'] === 'Belum Lunas')
                        <span class="status-belum">BELUM LUNAS</span>
                    @else
                        <span class="status-none">-</span>
                    @endif
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- 5. BAGIAN 2: RINCIAN PEMBAYARAN IPP PER BULAN --}}
    <div class="page-break-inside-avoid">
        <div class="section-header">2. Rincian Pembayaran IPP (Iuran Pengembangan Pendidikan) Per Bulan</div>
        @if($ippData['has_data'])
            <table class="table-data">
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center">No</th>
                        <th style="width: 16%;">Bulan</th>
                        <th style="width: 15%;" class="text-right">Tarif Tagihan (Rp)</th>
                        <th style="width: 15%;" class="text-right">Terbayar (Rp)</th>
                        <th style="width: 15%;" class="text-right">Sisa (Rp)</th>
                        <th style="width: 12%;" class="text-center">Status</th>
                        <th>Keterangan / Riwayat Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ippData['bulan_list'] as $idx => $b)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td><strong>{{ $b['bulan'] }}</strong></td>
                            <td class="text-right">{{ number_format($b['tagihan'], 0, ',', '.') }}</td>
                            <td class="text-right" style="color:#15803d;">
                                @if($b['terbayar'] > 0)
                                    {{ number_format($b['terbayar'], 0, ',', '.') }}
                                @else
                                    <span class="status-none">0</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($b['sisa'] > 0)
                                    <span class="status-belum">{{ number_format($b['sisa'], 0, ',', '.') }}</span>
                                @else
                                    <span class="status-lunas">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($b['status'] === 'Lunas')
                                    <span class="status-lunas">Lunas</span>
                                @elseif($b['status'] === 'Sebagian')
                                    <span class="status-sebagian">Sebagian</span>
                                @elseif($b['status'] === 'Belum Lunas')
                                    <span class="status-belum">Belum Lunas</span>
                                @else
                                    <span class="status-none">-</span>
                                @endif
                            </td>
                            <td>
                                @if($b['status'] === 'Lunas')
                                    <span style="color:#15803d;">Lunas</span>
                                @elseif($b['status'] === 'Sebagian')
                                    <span class="status-sebagian">Terbayar sebagian (sisa Rp {{ number_format($b['sisa'], 0, ',', '.') }})</span>
                                @else
                                    <span class="status-none">Belum ada pembayaran</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">TOTAL IPP</td>
                        <td class="text-right">{{ number_format($ippData['target'], 0, ',', '.') }}</td>
                        <td class="text-right" style="color:#15803d;">{{ number_format($ippData['terbayar'], 0, ',', '.') }}</td>
                        <td class="text-right" style="color:#dc2626;">{{ number_format($ippData['sisa'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($ippData['status'] === 'Lunas')
                                <span class="status-lunas">Lunas</span>
                            @elseif($ippData['status'] === 'Sebagian')
                                <span class="status-sebagian">Sebagian</span>
                            @else
                                <span class="status-belum">Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($ippData['riwayat']->count() > 0)
                                {{ $ippData['riwayat']->count() }} kali transaksi tercatat
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>

            {{-- Riwayat Transaksi IPP Bertanggal --}}
            @if($ippData['riwayat']->count() > 0)
                <div style="font-size: 8px; color: #475569; margin-top: -6px; margin-bottom: 8px;">
                    <strong>Riwayat Transaksi IPP Aktual:</strong>
                    @foreach($ippData['riwayat'] as $d)
                        &bull; {{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}: <span style="color:#15803d; font-weight:bold;">Rp {{ number_format($d->nominal, 0, ',', '.') }}</span> ({{ $d->metode ?? 'Cash' }}){{ $d->keterangan ? ' - ' . $d->keterangan : '' }}
                    @endforeach
                </div>
            @endif
        @else
            <p style="font-size: 8.5px; color: #94a3b8; margin: 4px 0 10px;">Tidak ada tagihan IPP yang terdaftar untuk siswa pada tahun ajaran ini.</p>
        @endif
    </div>

    {{-- 6. BAGIAN 3: RINCIAN KEGIATAN INTRAKURIKULER (KI) --}}
    <div class="page-break-inside-avoid">
        <div class="section-header">3. Rincian Kegiatan Intrakurikuler (KI) — UTS, UAS &amp; Ujian</div>
        @if($kiData['has_data'])
            <table class="table-data">
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center">No</th>
                        <th style="width: 26%;">Komponen Evaluasi</th>
                        <th style="width: 15%;" class="text-right">Tagihan (Rp)</th>
                        <th style="width: 15%;" class="text-right">Terbayar (Rp)</th>
                        <th style="width: 15%;" class="text-right">Sisa (Rp)</th>
                        <th style="width: 12%;" class="text-center">Status</th>
                        <th>Riwayat Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kiData['komponen'] as $idx => $k)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td><strong>{{ $k['nama'] }}</strong></td>
                            <td class="text-right">{{ number_format($k['tagihan'], 0, ',', '.') }}</td>
                            <td class="text-right" style="color:#15803d;">
                                @if($k['terbayar'] > 0)
                                    {{ number_format($k['terbayar'], 0, ',', '.') }}
                                @else
                                    <span class="status-none">0</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($k['sisa'] > 0)
                                    <span class="status-belum">{{ number_format($k['sisa'], 0, ',', '.') }}</span>
                                @else
                                    <span class="status-lunas">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($k['status'] === 'Lunas')
                                    <span class="status-lunas">Lunas</span>
                                @elseif($k['status'] === 'Sebagian')
                                    <span class="status-sebagian">Sebagian</span>
                                @elseif($k['status'] === 'Belum Lunas')
                                    <span class="status-belum">Belum Lunas</span>
                                @else
                                    <span class="status-none">-</span>
                                @endif
                            </td>
                            <td>
                                @if($k['riwayat']->count() > 0)
                                    <ul class="tx-list">
                                        @foreach($k['riwayat'] as $tx)
                                            <li class="tx-item">
                                                {{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }} &rarr; <span class="tx-item-nom">Rp {{ number_format($tx->nominal, 0, ',', '.') }}</span> ({{ $tx->metode ?? 'Cash' }})
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="status-none">Belum ada transaksi</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">TOTAL KI</td>
                        <td class="text-right">{{ number_format($kiData['target'], 0, ',', '.') }}</td>
                        <td class="text-right" style="color:#15803d;">{{ number_format($kiData['terbayar'], 0, ',', '.') }}</td>
                        <td class="text-right" style="color:#dc2626;">{{ number_format($kiData['sisa'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($kiData['status'] === 'Lunas')
                                <span class="status-lunas">Lunas</span>
                            @elseif($kiData['status'] === 'Sebagian')
                                <span class="status-sebagian">Sebagian</span>
                            @else
                                <span class="status-belum">Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($kiData['riwayat']->count() > 0)
                                {{ $kiData['riwayat']->count() }} transaksi
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        @else
            <p style="font-size: 8.5px; color: #94a3b8; margin: 4px 0 10px;">Tidak ada tagihan Kegiatan Intrakurikuler yang terdaftar untuk siswa pada tahun ajaran ini.</p>
        @endif
    </div>

    {{-- 7. BAGIAN 4 & 5: RINCIAN DAFTAR ULANG & SARPRAS --}}
    <div class="page-break-inside-avoid">
        <div class="section-header">4. Rincian Pembayaran Daftar Ulang (DU) &amp; Sarana Prasarana</div>
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 4%;" class="text-center">No</th>
                    <th style="width: 26%;">Jenis Tagihan</th>
                    <th style="width: 15%;" class="text-right">Total Tagihan (Rp)</th>
                    <th style="width: 15%;" class="text-right">Terbayar (Rp)</th>
                    <th style="width: 15%;" class="text-right">Sisa (Rp)</th>
                    <th style="width: 12%;" class="text-center">Status</th>
                    <th>Riwayat Transaksi Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                {{-- Row DU --}}
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <strong>Daftar Ulang (DU)</strong>
                        @if($duData['terbawa'] > 0)
                            <div style="font-size: 7.5px; color:#b45309;">(Termasuk terbawa Rp {{ number_format($duData['terbawa'], 0, ',', '.') }})</div>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($duData['total_tagihan'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color:#15803d;">
                        @if($duData['terbayar'] > 0)
                            {{ number_format($duData['terbayar'], 0, ',', '.') }}
                        @else
                            <span class="status-none">0</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($duData['sisa'] > 0)
                            <span class="status-belum">{{ number_format($duData['sisa'], 0, ',', '.') }}</span>
                        @else
                            <span class="status-lunas">0</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($duData['status'] === 'Lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($duData['status'] === 'Sebagian')
                            <span class="status-sebagian">Sebagian</span>
                        @elseif($duData['status'] === 'Belum Lunas')
                            <span class="status-belum">Belum Lunas</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                    <td>
                        @if($duData['riwayat']->count() > 0)
                            <ul class="tx-list">
                                @foreach($duData['riwayat'] as $tx)
                                    <li class="tx-item">
                                        {{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }} &rarr; <span class="tx-item-nom">Rp {{ number_format($tx->nominal, 0, ',', '.') }}</span> ({{ $tx->metode ?? 'Cash' }})
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="status-none">Belum ada transaksi</span>
                        @endif
                    </td>
                </tr>

                {{-- Row Sarpras --}}
                <tr>
                    <td class="text-center">2</td>
                    <td>
                        <strong>Sarana &amp; Prasarana (Sarpras)</strong>
                        @if($sarprasData['terbawa'] > 0)
                            <div style="font-size: 7.5px; color:#b45309;">(Termasuk terbawa Rp {{ number_format($sarprasData['terbawa'], 0, ',', '.') }})</div>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($sarprasData['total_tagihan'], 0, ',', '.') }}</td>
                    <td class="text-right" style="color:#15803d;">
                        @if($sarprasData['terbayar'] > 0)
                            {{ number_format($sarprasData['terbayar'], 0, ',', '.') }}
                        @else
                            <span class="status-none">0</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($sarprasData['sisa'] > 0)
                            <span class="status-belum">{{ number_format($sarprasData['sisa'], 0, ',', '.') }}</span>
                        @else
                            <span class="status-lunas">0</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($sarprasData['status'] === 'Lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($sarprasData['status'] === 'Sebagian')
                            <span class="status-sebagian">Sebagian</span>
                        @elseif($sarprasData['status'] === 'Belum Lunas')
                            <span class="status-belum">Belum Lunas</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                    <td>
                        @if($sarprasData['riwayat']->count() > 0)
                            <ul class="tx-list">
                                @foreach($sarprasData['riwayat'] as $tx)
                                    <li class="tx-item">
                                        {{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }} &rarr; <span class="tx-item-nom">Rp {{ number_format($tx->nominal, 0, ',', '.') }}</span> ({{ $tx->metode ?? 'Cash' }})
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="status-none">Belum ada transaksi</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- 8. BAGIAN 5: TAGIHAN TERBAWA DARI TAHUN LALU (JIKA ADA) --}}
    @if(count($terbawaSummary) > 0)
        <div class="page-break-inside-avoid">
            <div class="section-header" style="color:#b45309;">5. Rincian Tagihan Terbawa dari Tahun Sebelumnya (Utang Lalu)</div>
            <table class="table-data">
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center">No</th>
                        <th>Sumber Tagihan Terbawa</th>
                        <th style="width: 20%;" class="text-right">Nominal Terbawa (Rp)</th>
                        <th style="width: 20%;" class="text-right">Terbayar Tahun Ini (Rp)</th>
                        <th style="width: 20%;" class="text-right">Sisa Terbawa (Rp)</th>
                        <th style="width: 15%;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terbawaSummary as $idx => $ts)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td><strong>{{ $ts['jenis'] }}</strong></td>
                            <td class="text-right font-weight-bold" style="color:#b45309;">{{ number_format($ts['terbawa'], 0, ',', '.') }}</td>
                            <td class="text-right" style="color:#15803d;">{{ number_format($ts['terbayar'], 0, ',', '.') }}</td>
                            <td class="text-right">
                                @if($ts['sisa'] > 0)
                                    <span class="status-belum">{{ number_format($ts['sisa'], 0, ',', '.') }}</span>
                                @else
                                    <span class="status-lunas">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($ts['status'] === 'Lunas')
                                    <span class="status-lunas">Lunas</span>
                                @elseif($ts['status'] === 'Sebagian')
                                    <span class="status-sebagian">Sebagian</span>
                                @else
                                    <span class="status-belum">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-center">TOTAL TERBAWA TAHUN LALU</td>
                        <td class="text-right">{{ number_format($totalTerbawaSemua, 0, ',', '.') }}</td>
                        <td class="text-right" style="color:#15803d;">{{ number_format($totalTerbayarTerbawa, 0, ',', '.') }}</td>
                        <td class="text-right" style="color:#dc2626;">{{ number_format($totalSisaTerbawa, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($totalSisaTerbawa <= 0)
                                <span class="status-lunas">Lunas</span>
                            @else
                                <span class="status-belum">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- 9. BOX TOTAL SISA KEWAJIBAN --}}
    <table class="box-sisa page-break-inside-avoid">
        <tr>
            <td>
                <div class="box-sisa-label">Total Sisa Kewajiban yang Belum Dibayar</div>
                <div class="box-sisa-sub">
                    Akumulasi seluruh jenis tagihan aktif siswa per tanggal {{ $tanggalCetak }}
                </div>
            </td>
            <td class="box-sisa-val {{ $grandTotal['sisa'] <= 0 ? 'lunas' : '' }}">
                @if($grandTotal['sisa'] <= 0 && $grandTotal['total_tagihan'] > 0)
                    LUNAS (Rp 0)
                @else
                    Rp {{ number_format($grandTotal['sisa'], 0, ',', '.') }}
                @endif
            </td>
        </tr>
    </table>

    {{-- 10. TANDA TANGAN RESMI --}}
    <table class="ttd-table">
        <tr>
            <td>
                Mengetahui,<br>
                Orang Tua / Wali Siswa
                <div class="ttd-space"></div>
                <div class="ttd-nama">( ...................................................... )</div>
            </td>
            <td>
                Margasari, {{ $tanggalCetak }}<br>
                Bendahara / Petugas Keuangan Sekolah
                <div class="ttd-space"></div>
                <div class="ttd-nama">Admin Keuangan SMK Muhammadiyah</div>
            </td>
        </tr>
    </table>

    {{-- 11. FOOTER SYSTEM NOTE --}}
    <div class="footer-note">
        Dokumen ini diterbitkan secara resmi melalui Sistem Informasi Pembayaran Sekolah SMK Muhammadiyah Margasari &bull; Dicetak pada {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</body>
</html>
