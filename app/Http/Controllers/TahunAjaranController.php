<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $daftarTahunAjaran = TahunAjaran::withCount(['pembayaran', 'siswa'])
            ->orderByDesc('nama')
            ->get();

        return view('tahun-ajaran.index', compact('daftarTahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'regex:/^\d{4}\/\d{4}$/', 'unique:tahun_ajaran,nama'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ], [
            'nama.required' => 'Format tahun ajaran harus diisi.',
            'nama.regex' => 'Format tahun ajaran harus berupa YYYY/YYYY (contoh: 2025/2026).',
            'nama.unique' => 'Tahun ajaran ini sudah terdaftar.',
        ]);

        $parts = explode('/', $request->nama);
        $tMulai = $request->tanggal_mulai ?: $parts[0] . '-07-01';
        $tSelesai = $request->tanggal_selesai ?: $parts[1] . '-06-30';

        $isFirst = TahunAjaran::count() === 0;

        TahunAjaran::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $tMulai,
            'tanggal_selesai' => $tSelesai,
            'is_active' => $isFirst || $request->boolean('is_active'),
        ]);

        if ($request->boolean('is_active')) {
            $latest = TahunAjaran::where('nama', $request->nama)->first();
            if ($latest) {
                TahunAjaran::where('id', '!=', $latest->id)->update(['is_active' => false]);
            }
        }

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$request->nama} berhasil ditambahkan.");
    }

    public function activate($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        DB::transaction(function () use ($ta) {
            TahunAjaran::query()->update(['is_active' => false]);
            $ta->update(['is_active' => true]);
        });

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$ta->nama} berhasil diatur sebagai tahun aktif.");
    }

    public function destroy($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        // Cek apakah ada pembayaran terkait
        $count = Pembayaran::where('tahun_ajaran_id', $ta->id)
            ->orWhere('tahun_ajaran', $ta->nama)
            ->count();

        if ($count > 0) {
            return redirect()
                ->route('tahun-ajaran.index')
                ->with('error', "Tahun ajaran {$ta->nama} tidak dapat dihapus karena masih memiliki {$count} data tagihan / pembayaran.");
        }

        $ta->delete();

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$ta->nama} berhasil dihapus.");
    }
}
