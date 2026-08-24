<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1E293B;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #16A34A;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header table {
            width: 100%;
        }
        .header .logo {
            width: 60px;
        }
        .header .logo img {
            width: 55px;
            height: 55px;
        }
        .header .sekolah h2 {
            margin: 0;
            font-size: 15px;
            color: #15803D;
        }
        .header .sekolah p {
            margin: 2px 0 0;
            font-size: 10px;
            color: #64748B;
        }
        .title {
            text-align: center;
            margin: 10px 0 4px;
        }
        .title h1 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title p {
            margin: 3px 0 0;
            font-size: 10.5px;
            color: #64748B;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        table.data th {
            background: #E7F7EE;
            color: #15803D;
            font-size: 10px;
            text-transform: uppercase;
            padding: 6px 5px;
            border: 1px solid #CBD5E1;
            text-align: left;
        }
        table.data td {
            padding: 5px;
            border: 1px solid #CBD5E1;
            font-size: 10px;
            vertical-align: top;
        }
        table.data tbody tr:nth-child(even) {
            background: #F8FAFC;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .status-lunas {
            color: #15803D;
            font-weight: bold;
        }
        .status-belum {
            color: #B91C1C;
            font-weight: bold;
        }
        .total-row td {
            font-weight: bold;
            background: #F1F5F9 !important;
        }
        .footer-note {
            margin-top: 18px;
            font-size: 9.5px;
            color: #94A3B8;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="logo">
                    <img src="{{ public_path('images/logo.png') }}">
                </td>
                <td class="sekolah">
                    <h2>SMK Muhammadiyah Margasari</h2>
                    <p>Sistem Informasi Pembayaran Sekolah</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">
        <h1>Laporan Pembayaran</h1>
        <p>
            @if($tanggalMulai && $tanggalAkhir)
                Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d F Y') }}
                s/d
                {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d F Y') }}
            @else
                Periode: Seluruh Riwayat Transaksi
            @endif
        </p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                <th style="width:9%;">NIS</th>
                <th style="width:15%;">Nama Siswa</th>
                <th style="width:11%;">Jenis</th>
                <th style="width:9%;">Tanggal</th>
                <th style="width:13%; background:#C6F6D5; color:#15803D;">Bulan Dibayar</th>
                <th style="width:11%;">Nominal (Rp)</th>
                <th style="width:8%;">Metode</th>
                <th style="width:11%;">Keterangan</th>
                <th style="width:9%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $i => $detail)
                @php
                    $siswa = $detail->pembayaran->siswa ?? null;
                    $jenis = $detail->pembayaran->jenisPembayaran->nama ?? '-';
                    $status = $detail->pembayaran->status ?? '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $siswa->nis ?? '-' }}</td>
                    <td>{{ $siswa->nama ?? '-' }}</td>
                    <td>{{ $jenis }}</td>
                    <td>{{ \Carbon\Carbon::parse($detail->tanggal)->format('d-m-Y') }}</td>
                    <td class="text-center" style="font-weight:bold; color:#15803D; background:#F0FDF4;">
                        {{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('F Y') }}
                    </td>
                    <td class="text-right">{{ number_format($detail->nominal, 0, ',', '.') }}</td>
                    <td>{{ $detail->metode ?? '-' }}</td>
                    <td>{{ $detail->keterangan ?: '-' }}</td>
                    <td class="{{ $status === 'Lunas' ? 'status-lunas' : 'status-belum' }}">
                        {{ $status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
                        Tidak ada transaksi pembayaran pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($transaksi->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($totalNominal, 0, ',', '.') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <p class="footer-note">
        Dicetak pada {{ now()->format('d F Y, H:i') }} WIB melalui Sistem Informasi Pembayaran Sekolah.
    </p>

</body>
</html>