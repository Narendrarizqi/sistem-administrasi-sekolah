<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use Barryvdh\DomPDF\Facade\Pdf;

class BuktiPembayaranController extends Controller
{
    /**
     * Fitur 2: Cetak bukti pembayaran untuk SATU transaksi (DetailPembayaran)
     * yang spesifik, bukan agregat per tagihan. Jadi kalau 1 tagihan dibayar
     * beberapa kali, tiap transaksi punya bukti sendiri-sendiri.
     */
    public function cetak($id)
    {
        $detail = DetailPembayaran::with(['pembayaran.siswa', 'pembayaran.jenisPembayaran'])
            ->findOrFail($id);

        $pembayaran = $detail->pembayaran;

        $totalDibayar = $pembayaran->detailPembayaran()
            ->where('id', '<=', $detail->id)
            ->sum('nominal');

        $sisa = max($pembayaran->target - $totalDibayar, 0);

        $pdf = Pdf::loadView('bukti.pdf', [
            'detail' => $detail,
            'pembayaran' => $pembayaran,
            'totalDibayar' => $totalDibayar,
            'sisa' => $sisa,
        ])->setPaper('a5', 'portrait');

        return $pdf->stream('Bukti-Pembayaran-' . $detail->id . '.pdf');
    }
}