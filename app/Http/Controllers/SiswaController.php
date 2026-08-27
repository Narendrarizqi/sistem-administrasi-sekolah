<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\SiswaImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(?Request $request = null)
    {
        $request = $request ?? request();
        $taAktif = $this->getTahunAjaranAktif();
        $tahunAjaranNama = $taAktif ? $taAktif->nama : $this->tahunAjaranSaatIni();

        $query = Siswa::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($kelas = $request->input('kelas')) {
            $query->where('kelas', $kelas);
        }

        $siswa = $query->orderBy('nama')->get();

        return view('siswa.index', compact('siswa', 'tahunAjaranNama'));
    }

    /**
     * Unduh template resmi import siswa
     */
    public function downloadTemplate(SiswaImportService $importService)
    {
        return $importService->downloadTemplate();
    }

    /**
     * Parse berkas yang diunggah dan kembalikan pratinjau / kebutuhan mapping
     */
    public function parseImport(Request $request, SiswaImportService $importService)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'Silakan pilih berkas data siswa yang akan diimport.',
            'file.file'     => 'Berkas yang diunggah tidak valid.',
            'file.max'      => 'Ukuran berkas maksimal adalah 10 MB.',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = ['xlsx', 'xls', 'csv', 'docx', 'txt', 'pdf'];

        if (!in_array($ext, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => "Format berkas .{$ext} tidak didukung. Silakan gunakan format: .xlsx, .xls, .csv, .docx, .txt, atau .pdf.",
            ], 422);
        }

        try {
            $parsed = $importService->parseFile($file);

            // Jika kolom belum terdeteksi lengkap, minta admin memetakan kolom
            if ($parsed['requires_mapping'] ?? false) {
                return response()->json([
                    'success'  => true,
                    'status'   => 'need_mapping',
                    'headers'  => $parsed['headers'],
                    'mapping'  => $parsed['mapping'],
                    'raw_rows' => $parsed['rows'],
                    'message'  => 'Kolom tidak dapat dikenali secara otomatis. Silakan lakukan pemetaan kolom.',
                ]);
            }

            // Validasi baris data terhadap database
            $validated = $importService->validateRows($parsed['rows']);

            return response()->json([
                'success' => true,
                'status'  => 'preview',
                'summary' => $validated['summary'],
                'rows'    => $validated['rows'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Terapkan pemetaan kolom manual dan kembalikan pratinjau
     */
    public function applyMapping(Request $request, SiswaImportService $importService)
    {
        $request->validate([
            'raw_rows' => 'required|array',
            'mapping'  => 'required|array',
        ]);

        try {
            $mappedRows = $importService->applyMapping($request->input('raw_rows'), $request->input('mapping'));
            $validated = $importService->validateRows($mappedRows);

            return response()->json([
                'success' => true,
                'status'  => 'preview',
                'summary' => $validated['summary'],
                'rows'    => $validated['rows'],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Konfirmasi dan eksekusi import siswa dengan Database Transaction
     */
    public function confirmImport(Request $request, SiswaImportService $importService)
    {
        $request->validate([
            'rows'             => 'required|array',
            'duplicate_action' => 'nullable|string|in:skip,update',
        ], [
            'rows.required' => 'Tidak ada data siswa untuk diimport.',
        ]);

        $rows = $request->input('rows');
        $duplicateAction = $request->input('duplicate_action', 'skip');

        try {
            $result = $importService->executeImport($rows, $duplicateAction);

            $msgParts = [];
            if ($result['imported'] > 0) {
                $msgParts[] = "{$result['imported']} siswa baru berhasil ditambahkan";
            }
            if ($result['updated'] > 0) {
                $msgParts[] = "{$result['updated']} siswa berhasil diperbarui";
            }
            if ($result['skipped'] > 0) {
                $msgParts[] = "{$result['skipped']} data dilewati";
            }

            $successMsg = !empty($msgParts)
                ? 'Import berhasil: ' . implode(', ', $msgParts) . '.'
                : 'Tidak ada data baru yang diimport.';

            if ($request->wantsJson() || $request->ajax()) {
                session()->flash('success', $successMsg);
                return response()->json([
                    'success' => true,
                    'result'  => $result,
                    'message' => $successMsg,
                ]);
            }

            return redirect()->route('siswa.index')->with('success', $successMsg);
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengimport data: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('siswa.index')->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
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