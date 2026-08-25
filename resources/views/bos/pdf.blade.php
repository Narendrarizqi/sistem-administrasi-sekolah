<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Dana BOS {{ $tahunAnggaran }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11.5px;
            color: #1e293b;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px solid #334155;
            padding-bottom: 8px;
        }

        .header h2 {
            margin: 0 0 2px 0;
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }

        .header p {
            margin: 0 0 4px 0;
            font-size: 10px;
            color: #64748b;
        }

        .header h3 {
            margin: 6px 0 2px 0;
            font-size: 13px;
            color: #0369a1;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin: 14px 0 6px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #cbd5e1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
        }

        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-success {
            color: #15803d;
        }

        .text-danger {
            color: #b91c1c;
        }

        .summary-box {
            margin-top: 14px;
            margin-bottom: 20px;
            width: 100%;
        }

        .summary-table {
            width: 60%;
            margin-left: auto;
        }

        .summary-table td {
            padding: 5px 8px;
        }

        .signature {
            margin-top: 25px;
            width: 100%;
        }

        .signature td {
            border: none;
            text-align: center;
            padding: 0;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>SMK MUHAMMADIYAH MARGASARI</h2>
        <p>Jl. Raya Margasari, Kec. Margasari, Kab. Tegal &bull; Telp. (0283) 3467xxx</p>
        <h3>LAPORAN REALISASI BANTUAN OPERASIONAL SEKOLAH (BOS)</h3>
        <p style="font-size: 11px; color: #334155; font-weight: bold;">Tahun Anggaran {{ $tahunAnggaran }}</p>
    </div>

    {{-- BAGIAN 1: PEMASUKAN DANA BOS --}}
    <div class="section-title">I. PEMASUKAN DANA BOS</div>
    <table>
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 100px;">Tahap</th>
                <th style="width: 110px;">Tanggal Diterima</th>
                <th>Keterangan / Catatan</th>
                <th style="width: 140px;" class="right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="center">1</td>
                <td class="font-bold">Tahap 1</td>
                <td class="center">
                    {{ $tahap1 ? \Carbon\Carbon::parse($tahap1->tanggal)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $tahap1?->keterangan ?? 'Penerimaan BOS Tahap 1' }}</td>
                <td class="right font-bold text-success">
                    {{ $tahap1 ? number_format($tahap1->nominal, 0, ',', '.') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="center">2</td>
                <td class="font-bold">Tahap 2</td>
                <td class="center">
                    {{ $tahap2 ? \Carbon\Carbon::parse($tahap2->tanggal)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $tahap2?->keterangan ?? 'Penerimaan BOS Tahap 2' }}</td>
                <td class="right font-bold text-success">
                    {{ $tahap2 ? number_format($tahap2->nominal, 0, ',', '.') : '-' }}
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc;">
                <th colspan="4" class="right">TOTAL DANA BOS MASUK</th>
                <th class="right text-success" style="font-size: 12px;">
                    Rp {{ number_format($totalPemasukanBos, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    {{-- BAGIAN 2: RINCIAN PENGELUARAN DARI DANA BOS --}}
    <div class="section-title">II. RINCIAN PENGELUARAN DARI DANA BOS</div>
    <table>
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 100px;">Tanggal</th>
                <th>Uraian / Keperluan Pengeluaran</th>
                <th style="width: 140px;" class="right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaranBos as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td class="center">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                    </td>
                    <td>{{ $item->keterangan }}</td>
                    <td class="right text-danger">
                        {{ number_format($item->nominal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="center" style="padding: 12px; color: #94a3b8;">
                        Belum ada data pengeluaran dari Dana BOS pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc;">
                <th colspan="3" class="right">TOTAL PENGELUARAN BOS</th>
                <th class="right text-danger" style="font-size: 12px;">
                    Rp {{ number_format($totalPengeluaranBos, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    {{-- BAGIAN 3: RINGKASAN SALDO --}}
    <div class="summary-box">
        <table class="summary-table">
            <thead>
                <tr>
                    <th colspan="2" class="center">REKAPITULASI SALDO DANA BOS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Dana BOS Masuk</td>
                    <td class="right font-bold text-success">
                        Rp {{ number_format($totalPemasukanBos, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Total Pengeluaran BOS</td>
                    <td class="right font-bold text-danger">
                        Rp {{ number_format($totalPengeluaranBos, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="background-color: #f1f5f9;">
                    <td class="font-bold">SISA SALDO DANA BOS</td>
                    <td class="right font-bold" style="font-size: 12px; {{ $sisaSaldoBos >= 0 ? 'color: #0d9488;' : 'color: #b91c1c;' }}">
                        Rp {{ number_format($sisaSaldoBos, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- TANDA TANGAN --}}
    <table class="signature">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br>
                <strong>Kepala Sekolah</strong>
                <br><br><br><br>
                <u>_______________________</u><br>
                NIP.
            </td>
            <td style="width: 50%;">
                Margasari, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Bendahara BOS</strong>
                <br><br><br><br>
                <u>_______________________</u><br>
                NIP.
            </td>
        </tr>
    </table>

</body>
</html>
