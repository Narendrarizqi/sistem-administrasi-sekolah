<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rincian Saldo Akhir</title>

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
        <h3 style="margin: 0; font-size: 13px;">RINCIAN SALDO AKHIR</h3>
        <p style="margin: 2px 0 0; font-size: 11px; color: #666;">Per Sumber Dana &mdash; IPP, Daftar Ulang, Sarpras, Asesmen, BOS</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sumber</th>
                <th>Pemasukan (Rp)</th>
                <th>Pengeluaran (Rp)</th>
                <th>Saldo (Rp)</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rincianSumberDana as $rincian)
                <tr>
                    <td>{{ $rincian['jenis'] }}</td>

                    <td class="right">
                        {{ number_format($rincian['pemasukan'], 0, ',', '.') }}
                    </td>

                    <td class="right">
                        {{ number_format($rincian['pengeluaran'], 0, ',', '.') }}
                    </td>

                    <td class="right">
                        {{ $rincian['saldo'] >= 0 ? '' : '-' }}{{ number_format(abs($rincian['saldo']), 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="center">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th>TOTAL</th>
                <th class="right">
                    {{ number_format($totalRincianPemasukan, 0, ',', '.') }}
                </th>
                <th class="right">
                    {{ number_format($totalRincianPengeluaran, 0, ',', '.') }}
                </th>
                <th class="right">
                    {{ $totalRincianSaldo >= 0 ? '' : '-' }}{{ number_format(abs($totalRincianSaldo), 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

</body>
</html>