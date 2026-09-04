<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kuitansi Pembayaran</title>
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
            width: 40%;
            color: #64748B;
        }
        table.info td.sep {
            width: 3%;
        }
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
        .status-lunas { color: #15803D; font-weight: bold; }
        .status-belum { color: #B91C1C; font-weight: bold; }
        .ttd {
            width: 100%;
            margin-top: 30px;
        }
        .ttd td {
            width: 50%;
            text-align: center;
            font-size: 10px;
            vertical-align: top;
        }
        .ttd .space { height: 50px; }
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
                    <p>Jl. Raya Margasari, Kec. Margasari, Kab. Tegal</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="title">
        <h1>KUITANSI PEMBAYARAN</h1>
        <p>No. Kuitansi: KW-{{ str_pad($detail->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <table class="info">
        <tr>
            <td class="label">Tanggal Pembayaran</td>
            <td class="sep">:</td>
            <td>{{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td class="sep">:</td>
            <td>{{ $pembayaran->siswa->nis ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Siswa</td>
            <td class="sep">:</td>
            <td>{{ $pembayaran->siswa->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="sep">:</td>
            <td>{{ $pembayaran->siswa->kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Pembayaran</td>
            <td class="sep">:</td>
            <td>
                @php
                    $rawJenis = $pembayaran->jenisPembayaran->nama ?? '-';
                    $namaJenis = ($rawJenis === 'KI' || $rawJenis === 'Kegiatan Intrakurikuler') ? 'Asesmen' : $rawJenis;
                    $rawKategori = $detail->kategori;
                    $kategoriDisplay = ($rawKategori === 'KI' || $rawKategori === 'Kegiatan Intrakurikuler') ? 'Asesmen' : $rawKategori;
                @endphp
                {{ $namaJenis }}{{ ($kategoriDisplay && $kategoriDisplay !== $namaJenis) ? " ({$kategoriDisplay})" : '' }}
            </td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td class="sep">:</td>
            <td>{{ $detail->metode ?? '-' }}</td>
        </tr>
        @if(($detail->potongan ?? 0) > 0)
        <tr>
            <td class="label">Potongan IPP</td>
            <td class="sep">:</td>
            <td style="color: #15803D; font-weight: bold;">
                Rp {{ number_format($detail->potongan, 0, ',', '.') }}
            </td>
        </tr>
        @endif
        <tr>
            <td class="label">Keterangan</td>
            <td class="sep">:</td>
            <td>
                {{ $detail->keterangan ?: '-' }}
                @if(($detail->potongan ?? 0) > 0 && !$detail->keterangan)
                    <span style="color: #15803D; font-style: italic;">(Mendapatkan potongan sebesar Rp {{ number_format($detail->potongan, 0, ',', '.') }})</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="box-nominal">
        <div class="label">Nominal Dibayar Tunai (Transaksi Ini)</div>
        <div class="value">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</div>
        @if(($detail->potongan ?? 0) > 0)
            <div style="font-size: 10px; color: #15803D; margin-top: 4px; font-weight: bold;">
                + Potongan IPP: Rp {{ number_format($detail->potongan, 0, ',', '.') }}
                (Total Pemenuhan Kewajiban: Rp {{ number_format($detail->nominal + $detail->potongan, 0, ',', '.') }})
            </div>
        @endif
    </div>

    <table class="info">
        <tr>
            <td class="label">Tagihan Awal</td>
            <td class="sep">:</td>
            <td>Rp {{ number_format($totalTagihanAwal ?? $pembayaran->totalTagihanAwal(), 0, ',', '.') }}</td>
        </tr>
        @if(($detail->potongan ?? 0) > 0)
        <tr>
            <td class="label">Potongan Transaksi Ini</td>
            <td class="sep">:</td>
            <td style="color: #15803D; font-weight: bold;">Rp {{ number_format($detail->potongan, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if(($totalPotongan ?? 0) > 0)
        <tr>
            <td class="label">Total Potongan (s/d Transaksi Ini)</td>
            <td class="sep">:</td>
            <td style="color: #15803D; font-weight: bold;">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Total Sudah Dibayar Tunai</td>
            <td class="sep">:</td>
            <td>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Sisa Tagihan</td>
            <td class="sep">:</td>
            <td>Rp {{ number_format($sisa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Status Tagihan</td>
            <td class="sep">:</td>
            <td class="{{ $sisa <= 0 ? 'status-lunas' : 'status-belum' }}">
                {{ $sisa <= 0 ? 'Lunas' : 'Belum Lunas' }}
            </td>
        </tr>
    </table>

    <table class="ttd">
        <tr>
            <td>
                Orang Tua / Wali
                <div class="space"></div>
                (....................................)
            </td>
            <td>
                Petugas Pembayaran
                <div class="space"></div>
                (....................................)
            </td>
        </tr>
    </table>

    <p class="footer-note">
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB — dokumen ini sah tanpa tanda tangan basah untuk keperluan administrasi internal.
    </p>

</body>
</html>