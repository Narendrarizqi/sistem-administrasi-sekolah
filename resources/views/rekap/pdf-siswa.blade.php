<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan - {{ $siswa->nama }}</title>
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
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .header table { width: 100%; }
        .header .logo img { width: 45px; height: 45px; }
        .header .sekolah h2 {
            margin: 0;
            font-size: 13px;
            color: #15803D;
        }
        .header .sekolah p {
            margin: 1px 0 0;
            font-size: 9px;
            color: #64748B;
        }
        .title {
            text-align: center;
            margin: 10px 0;
        }
        .title h1 {
            font-size: 14px;
            margin: 0;
            letter-spacing: 1px;
        }
        .title p {
            margin: 3px 0 0;
            font-size: 9.5px;
            color: #64748B;
        }
        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.info td {
            padding: 4px 2px;
            font-size: 10.5px;
            vertical-align: top;
        }
        table.info td.label {
            width: 30%;
            color: #64748B;
        }
        table.info td.sep {
            width: 3%;
        }
        table.rincian {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        table.rincian th,
        table.rincian td {
            border: 1px solid #CBD5E1;
            padding: 6px 8px;
            font-size: 10px;
        }
        table.rincian th {
            background: #F0FDF4;
            color: #15803D;
            text-align: center;
        }
        table.rincian td.right { text-align: right; }
        table.rincian td.center { text-align: center; }
        table.rincian tfoot td {
            font-weight: bold;
            background: #F8FAFC;
        }
        .status-lunas { color: #15803D; font-weight: bold; }
        .status-belum { color: #B91C1C; font-weight: bold; }
        .status-none { color: #64748B; font-weight: bold; }
        .box-nominal {
            margin: 14px 0;
            padding: 10px;
            background: #E7F7EE;
            border: 1px solid #16A34A;
            border-radius: 4px;
            text-align: center;
        }
        .box-nominal .label {
            font-size: 9.5px;
            color: #15803D;
            text-transform: uppercase;
        }
        .box-nominal .value {
            font-size: 18px;
            font-weight: bold;
            color: #15803D;
        }
        .footer-note {
            margin-top: 20px;
            font-size: 8.5px;
            color: #94A3B8;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="width:50px;" class="logo">
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
        <h1>LAPORAN TAGIHAN SISWA</h1>
        <p>Rekap Keseluruhan Tagihan &amp; Pembayaran</p>
    </div>

    <table class="info">
        <tr>
            <td class="label">NIS</td>
            <td class="sep">:</td>
            <td>{{ $siswa->nis }}</td>
        </tr>
        <tr>
            <td class="label">Nama Siswa</td>
            <td class="sep">:</td>
            <td>{{ $siswa->nama }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="sep">:</td>
            <td>{{ $siswa->kelas }}</td>
        </tr>
    </table>

    <table class="rincian">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Jenis Pembayaran</th>
                <th style="width:18%;">Target (Rp)</th>
                <th style="width:18%;">Terbayar (Rp)</th>
                <th style="width:18%;">Sisa (Rp)</th>
                <th style="width:15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jenisData as $row)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="right">{{ number_format($row['target'], 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($row['terbayar'], 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($row['sisa'], 0, ',', '.') }}</td>
                    <td class="center">
                        @if($row['status'] === 'Lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($row['status'] === 'Belum Lunas')
                            <span class="status-belum">Belum Lunas</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Belum ada tagihan untuk siswa ini.</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($jenisData))
            <tfoot>
                <tr>
                    <td colspan="2" class="center">TOTAL</td>
                    <td class="right">{{ number_format($overall['target'], 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($overall['terbayar'], 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($overall['sisa'], 0, ',', '.') }}</td>
                    <td class="center">
                        @if($overall['status'] === 'Lunas')
                            <span class="status-lunas">Lunas</span>
                        @elseif($overall['status'] === 'Belum Lunas')
                            <span class="status-belum">Belum Lunas</span>
                        @else
                            <span class="status-none">-</span>
                        @endif
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="box-nominal">
        <div class="label">Total Sisa Tagihan</div>
        <div class="value">Rp {{ number_format($overall['sisa'], 0, ',', '.') }}</div>
    </div>

    <p class="footer-note">
        Dicetak pada {{ now()->format('d F Y, H:i') }} WIB — dokumen ini sah tanpa tanda tangan basah untuk keperluan administrasi internal.
    </p>

</body>
</html>
