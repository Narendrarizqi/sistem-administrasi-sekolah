<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::orderBy('nama')->get();

        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:30|unique:siswa,nis',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|in:X TKJ,X TKR 1,X TKR 2,XI TKJ,XI TKR 1,XI TKR 2,XII TKJ,XII TKR 1,XII TKR 2,Lulus',
        ]);

        $siswa = Siswa::create($validated);

        // Catat riwayat kelas siswa pada tahun ajaran aktif
        $tahunAktif = $this->getTahunAjaranAktif();
        if ($tahunAktif) {
            DB::table('siswa_tahun_ajaran')->updateOrInsert(
                ['siswa_id' => $siswa->id, 'tahun_ajaran_id' => $tahunAktif->id],
                ['kelas' => $siswa->kelas, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:30|unique:siswa,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|in:X TKJ,X TKR 1,X TKR 2,XI TKJ,XI TKR 1,XI TKR 2,XII TKJ,XII TKR 1,XII TKR 2,Lulus',
        ]);

        $siswa->update($validated);

        // Update juga riwayat kelas pada tahun ajaran aktif
        $tahunAktif = $this->getTahunAjaranAktif();
        if ($tahunAktif) {
            DB::table('siswa_tahun_ajaran')->updateOrInsert(
                ['siswa_id' => $siswa->id, 'tahun_ajaran_id' => $tahunAktif->id],
                ['kelas' => $siswa->kelas, 'updated_at' => now()]
            );
        }

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function naikKelas()
    {
        $taLama = $this->getTahunAjaranAktif();

        if (!$taLama) {
            return redirect()
                ->route('siswa.index')
                ->with('error', 'Tidak ada tahun ajaran aktif. Silakan buat tahun ajaran terlebih dahulu.');
        }

        $tahunLamaNama = $taLama->nama;
        $tahunBaruNama = $this->tahunAjaranBerikutnya($tahunLamaNama);

        // Pastikan record TahunAjaran baru sudah ada di database
        $taBaru = TahunAjaran::firstOrCreate(
            ['nama' => $tahunBaruNama],
            [
                'tanggal_mulai' => explode('/', $tahunBaruNama)[0] . '-07-01',
                'tanggal_selesai' => explode('/', $tahunBaruNama)[1] . '-06-30',
                'is_active' => false,
            ]
        );

        DB::transaction(function () use ($taLama, $taBaru, $tahunLamaNama, $tahunBaruNama) {
            foreach (Siswa::query()->get() as $siswa) {
                if ($siswa->kelas === 'Lulus') {
                    continue;
                }

                // Pindahkan tagihan belum lunas sebagai carryover (1 record per jenis di tahun baru)
                $this->pindahkanTagihanBelumLunas(
                    $siswa->id,
                    $taLama->id,
                    $tahunLamaNama,
                    $taBaru->id,
                    $tahunBaruNama
                );

                // Naik kelas
                switch ($siswa->kelas) {
                    case 'X TKJ':
                        $siswa->kelas = 'XI TKJ';
                        break;

                    case 'X TKR 1':
                        $siswa->kelas = 'XI TKR 1';
                        break;

                    case 'X TKR 2':
                        $siswa->kelas = 'XI TKR 2';
                        break;

                    case 'XI TKJ':
                        $siswa->kelas = 'XII TKJ';
                        break;

                    case 'XI TKR 1':
                        $siswa->kelas = 'XII TKR 1';
                        break;

                    case 'XI TKR 2':
                        $siswa->kelas = 'XII TKR 2';
                        break;

                    case 'XII TKJ':
                    case 'XII TKR 1':
                    case 'XII TKR 2':
                        $siswa->kelas = 'Lulus';
                        break;
                }

                $siswa->save();

                // Simpan kelas baru siswa pada tahun ajaran baru
                DB::table('siswa_tahun_ajaran')->updateOrInsert(
                    ['siswa_id' => $siswa->id, 'tahun_ajaran_id' => $taBaru->id],
                    ['kelas' => $siswa->kelas, 'created_at' => now(), 'updated_at' => now()]
                );
            }

            // Aktifkan tahun ajaran baru, nonaktifkan tahun lama
            TahunAjaran::query()->update(['is_active' => false]);
            $taBaru->update(['is_active' => true]);
        });

        return redirect()
            ->route('siswa.index')
            ->with(
                'success',
                'Kenaikan kelas berhasil dilakukan. ' .
                'Sisa tagihan tahun sebelumnya dibawa sebagai Terbawa Tahun Lalu pada ' . $tahunBaruNama . '.'
            );
    }

    /**
     * Pindahkan sisa tagihan yang belum lunas dari tahun lama ke tahun baru sebagai terbawa (belum_lunas).
     *
     * Menjamin 1 NIS = 1 record per jenis pembayaran di tahun ajaran baru.
     */
    private function pindahkanTagihanBelumLunas(
        int $siswaId,
        int $tahunLamaId,
        string $tahunLamaNama,
        int $tahunBaruId,
        string $tahunBaruNama
    ): void {
        // Ambil semua tagihan siswa pada tahun lama
        $tagihanLama = Pembayaran::with('detailPembayaran')
            ->where('siswa_id', $siswaId)
            ->where(function ($q) use ($tahunLamaId, $tahunLamaNama) {
                $q->where('tahun_ajaran_id', $tahunLamaId)
                  ->orWhere(function ($sub) use ($tahunLamaNama) {
                      $sub->whereNull('tahun_ajaran_id')
                          ->where('tahun_ajaran', $tahunLamaNama);
                  });
            })
            ->get();

        $terbawaPerJenis = [];
        $originMap = [];

        foreach ($tagihanLama as $tagihan) {
            $terbayar = (float) $tagihan->detailPembayaran->sum('nominal');
            $totalTagihan = (float) $tagihan->target + (float) ($tagihan->belum_lunas ?? 0);
            $sisa = max($totalTagihan - $terbayar, 0);

            if ($sisa <= 0) {
                continue;
            }

            $jenisId = $tagihan->jenis_id;
            $terbawaPerJenis[$jenisId] = ($terbawaPerJenis[$jenisId] ?? 0) + $sisa;
            $originMap[$jenisId] = $tagihan->id;
        }

        foreach ($terbawaPerJenis as $jenisId => $totalTerbawa) {
            if ($totalTerbawa <= 0) {
                continue;
            }

            // Pastikan HANYA ada 1 record per jenis di tahun baru untuk siswa ini
            $baru = Pembayaran::where('siswa_id', $siswaId)
                ->where('jenis_id', $jenisId)
                ->where('tahun_ajaran_id', $tahunBaruId)
                ->first();

            if (!$baru) {
                $baru = new Pembayaran([
                    'siswa_id'        => $siswaId,
                    'jenis_id'        => $jenisId,
                    'tahun_ajaran_id' => $tahunBaruId,
                    'tahun_ajaran'    => $tahunBaruNama,
                    'target'          => 0,
                    'belum_lunas'     => $totalTerbawa,
                    'status'          => 'Belum Lunas',
                    'carryover_from_pembayaran_id' => $originMap[$jenisId] ?? null,
                ]);
                $baru->save();
            } else {
                $baru->belum_lunas = $totalTerbawa;
                $terbayarBaru = (float) $baru->detailPembayaran()->sum('nominal');
                $totalKewajibanBaru = (float) $baru->target + (float) $baru->belum_lunas;
                $baru->status = ($totalKewajibanBaru > 0 && $terbayarBaru >= $totalKewajibanBaru)
                    ? 'Lunas'
                    : 'Belum Lunas';
                if (!empty($originMap[$jenisId])) {
                    $baru->carryover_from_pembayaran_id = $originMap[$jenisId];
                }
                $baru->save();
            }
        }
    }
}