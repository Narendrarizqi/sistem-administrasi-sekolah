<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['siswa', 'jenisPembayaran', 'tahunAjaran'])
            ->orderBy('id', 'desc')
            ->get();

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $siswa           = Siswa::orderBy('nama')->get();
        $jenisPembayaran = JenisPembayaran::orderBy('nama')->get();

        return view('pembayaran.create', compact(
            'siswa',
            'jenisPembayaran'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_id' => 'required|exists:jenis_pembayaran,id',
            'target'   => 'required|numeric|min:0',
            'status'   => 'required|in:Belum Lunas,Lunas',
        ]);

        $tahunAktif = $this->getTahunAjaranAktif();
        $validated['tahun_ajaran_id'] = $tahunAktif?->id;
        $validated['tahun_ajaran']    = $tahunAktif?->nama;
        $validated['belum_lunas']     = 0;

        Pembayaran::create($validated);

        return redirect()
            ->route('pembayaran.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function edit(Pembayaran $pembayaran)
    {
        $siswa           = Siswa::orderBy('nama')->get();
        $jenisPembayaran = JenisPembayaran::orderBy('nama')->get();

        return view('pembayaran.edit', compact(
            'pembayaran',
            'siswa',
            'jenisPembayaran'
        ));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_id' => 'required|exists:jenis_pembayaran,id',
            'target'   => 'required|numeric|min:0',
            'status'   => 'required|in:Belum Lunas,Lunas',
        ]);

        $pembayaran->update($validated);

        return redirect()
            ->route('pembayaran.index')
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->detailPembayaran()->delete();
        $pembayaran->delete();

        return redirect()
            ->route('pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}