<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use Barryvdh\DomPDF\Facades\Pdf;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function pdf(DetailPembayaran $detailPembayaran)
    {
        $pembayaran = $detailPembayaran->pembayaran()->with(['siswa', 'jenisPembayaran', 'detailPembayaran'])->first();
        $totalTerbayar = $pembayaran->detailPembayaran->sum('nominal');
        $sisa = max($pembayaran->totalTagihan() - $totalTerbayar, 0);

        $pdf = Pdf::loadView('receipt.pdf', [
            'detail' => $detailPembayaran,
            'pembayaran' => $pembayaran,
            'totalTerbayar' => $totalTerbayar,
            'sisa' => $sisa,
        ]);

        return $pdf->stream('bukti-pembayaran-' . $detailPembayaran->id . '.pdf');
    }
}
