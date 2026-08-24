<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Kas Umum</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            text-align: center;
            background: #eee;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div style="text-align: center; margin-bottom: 15px;">
        <h2 style="margin: 0 0 2px 0; font-size: 15px;">SMK MUHAMMADIYAH MARGASARI</h2>
        <p style="margin: 0 0 8px 0; font-size: 10px; color: #555;">Jl. Raya Margasari, Kec. Margasari, Kab. Tegal</p>
        <h3 style="margin: 0; font-size: 13px;">LAPORAN BUKU KAS UMUM</h3>
        <p style="margin: 2px 0 0; font-size: 11px; color: #666;">Seluruh Pemasukan &amp; Pengeluaran</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Uraian</th>
                <th>Masuk (Rp)</th>
                <th>Keluar (Rp)</th>
                <th>Saldo (Rp)</th>
            </tr>
        </thead>

        <tbody>
            @foreach($laporan as $row)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}
                    </td>

                    <td>
                        {{ $row['uraian'] }}
                    </td>

                    <td class="right">
                        {{ $row['masuk'] > 0
                            ? number_format($row['masuk'], 0, ',', '.')
                            : '-' }}
                    </td>

                    <td class="right">
                        {{ $row['keluar'] > 0
                            ? number_format($row['keluar'], 0, ',', '.')
                            : '-' }}
                    </td>

                    <td class="right">
                        {{ number_format($row['saldo'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3">TOTAL</th>
                <th class="right">
                    {{ number_format($totalMasuk, 0, ',', '.') }}
                </th>
                <th class="right">
                    {{ number_format($totalKeluar, 0, ',', '.') }}
                </th>
                <th class="right">
                    {{ number_format($saldoAkhir, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

</body>
</html>