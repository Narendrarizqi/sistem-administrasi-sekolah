<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Smalot\PdfParser\Parser as PdfParser;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaImportService
{
    /**
     * Alias kolom yang dikenali untuk pemetaan otomatis
     */
    protected array $aliasNama = [
        'nama', 'nama siswa', 'nama lengkap', 'nama peserta didik', 
        'student name', 'name', 'full name', 'nama_lengkap', 'nama_siswa', 'namasiswa'
    ];

    protected array $aliasNis = [
        'nis', 'nomor induk', 'nomor induk siswa', 'no induk', 
        'no_induk', 'no. induk', 'nisn/nis', 'nis/nisn', 'id siswa', 
        'student id', 'nis_siswa', 'no.induk'
    ];

    protected array $aliasKelas = [
        'kelas', 'kelas siswa', 'rombel', 'rombongan belajar', 
        'tingkat kelas', 'grade', 'class', 'kelas_siswa', 'kelassiswa'
    ];

    /**
     * Download template resmi import siswa (.xlsx)
     */
    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Siswa');

        // Header
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');

        // Styling header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '16A34A'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(26);

        // Contoh Data
        $sampleData = [
            ['23001', 'Ahmad Fauzi', 'X TKJ'],
            ['23002', 'Budi Santoso', 'X TKR 1'],
            ['23003', 'Siti Aisyah', 'X TKR 2'],
        ];

        $row = 2;
        foreach ($sampleData as $data) {
            $sheet->setCellValueExplicit('A' . $row, $data[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $row, $data[1]);
            $sheet->setCellValue('C' . $row, $data[2]);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Template_Import_Siswa_SMK_Muhammadiyah.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Membaca dan memparsing berkas yang diunggah
     */
    public function parseFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filePath = $file->getRealPath();

        return match ($extension) {
            'xlsx', 'xls' => $this->parseExcel($filePath),
            'csv'         => $this->parseCsv($filePath),
            'docx'        => $this->parseDocx($filePath),
            'txt'         => $this->parseTxt($filePath),
            'pdf'         => $this->parsePdf($filePath),
            default       => throw new \Exception("Format berkas .{$extension} tidak didukung."),
        };
    }

    /**
     * Parsing file Excel (.xlsx / .xls)
     */
    protected function parseExcel(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (empty($rows)) {
            throw new \Exception("Berkas Excel kosong atau tidak memiliki data.");
        }

        return $this->processTabularData($rows);
    }

    /**
     * Parsing file CSV
     */
    protected function parseCsv(string $filePath): array
    {
        $content = file_get_contents($filePath);
        // Hapus UTF-8 BOM jika ada
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        
        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        if (empty($lines)) {
            throw new \Exception("Berkas CSV kosong.");
        }

        // Deteksi delimiter: koma, titik koma, atau tab
        $firstLine = $lines[0];
        $delimiters = [',', ';', "\t", '|'];
        $bestDelimiter = ',';
        $maxCount = 0;
        foreach ($delimiters as $del) {
            $count = substr_count($firstLine, $del);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $del;
            }
        }

        $rows = [];
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            $rows[] = str_getcsv($line, $bestDelimiter);
        }

        if (empty($rows)) {
            throw new \Exception("Tidak ada data yang dapat dibaca dari berkas CSV.");
        }

        return $this->processTabularData($rows);
    }

    /**
     * Parsing file Word (.docx)
     */
    protected function parseDocx(string $filePath): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception("Gagal membuka berkas Word (.docx).");
        }

        $xmlContent = $zip->getFromName('word/document.xml');
        $zip->close();

        if (!$xmlContent) {
            throw new \Exception("Dokumen Word tidak berisi konten teks yang valid.");
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($xmlContent);
        libxml_clear_errors();

        // 1. Coba cari elemen tabel (w:tbl) terlebih dahulu
        $tables = $dom->getElementsByTagName('tbl');
        if ($tables->length > 0) {
            $rows = [];
            foreach ($tables as $table) {
                $trNodes = $table->getElementsByTagName('tr');
                foreach ($trNodes as $tr) {
                    $row = [];
                    $tcNodes = $tr->getElementsByTagName('tc');
                    foreach ($tcNodes as $tc) {
                        $text = '';
                        $tNodes = $tc->getElementsByTagName('t');
                        foreach ($tNodes as $t) {
                            $text .= $t->nodeValue;
                        }
                        $row[] = trim($text);
                    }
                    if (!empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                        $rows[] = $row;
                    }
                }
                if (count($rows) >= 2) {
                    break;
                }
            }

            if (count($rows) >= 2) {
                return $this->processTabularData($rows);
            }
        }

        // 2. Jika tidak ada tabel, baca sebagai teks paragraf terstruktur
        $paragraphs = $dom->getElementsByTagName('p');
        $plainLines = [];
        foreach ($paragraphs as $p) {
            $text = '';
            $tNodes = $p->getElementsByTagName('t');
            foreach ($tNodes as $t) {
                $text .= $t->nodeValue;
            }
            $text = trim($text);
            if ($text !== '') {
                $plainLines[] = $text;
            }
        }

        return $this->parseTextLines($plainLines);
    }

    /**
     * Parsing file Plain Text (.txt)
     */
    protected function parseTxt(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $lines = preg_split('/\r\n|\r|\n/', trim($content));

        if (empty($lines)) {
            throw new \Exception("Berkas teks (.txt) kosong.");
        }

        // Cek jika teks berformat tabular (dipisahkan tab / koma / pipe)
        if (str_contains($lines[0], "\t") || str_contains($lines[0], ',') || str_contains($lines[0], '|')) {
            $first = $lines[0];
            $del = str_contains($first, "\t") ? "\t" : (str_contains($first, '|') ? '|' : ',');
            $rows = [];
            foreach ($lines as $line) {
                if (trim($line) === '') continue;
                $rows[] = str_getcsv($line, $del);
            }
            if (count($rows) >= 2 && count($rows[0]) >= 2) {
                return $this->processTabularData($rows);
            }
        }

        return $this->parseTextLines($lines);
    }

    /**
     * Parsing file PDF
     */
    protected function parsePdf(string $filePath): array
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
        } catch (\Throwable $e) {
            throw new \Exception("Gagal membaca file PDF: " . $e->getMessage());
        }

        $text = trim($text);
        if (empty($text) || strlen($text) < 10) {
            throw new \Exception(
                "PDF ini kemungkinan berupa hasil scan gambar dan tidak memiliki layer teks. " .
                "Data tidak dapat dibaca secara otomatis. Silakan gunakan file Excel (.xlsx / .xls) atau CSV."
            );
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $cleanLines = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed !== '') {
                $cleanLines[] = $trimmed;
            }
        }

        return $this->parseTextLines($cleanLines);
    }

    /**
     * Memproses data berbentuk baris tabel (header di baris pertama atau baris teridentifikasi)
     */
    protected function processTabularData(array $rawRows): array
    {
        // Cari baris header terbaik yang memiliki kolom kolom yang sesuai
        $headerRowIndex = 0;
        $headers = [];
        $bestScore = -1;

        foreach ($rawRows as $idx => $row) {
            $nonEmpty = array_values(array_filter($row, fn($c) => !is_null($c) && trim((string)$c) !== ''));
            if (count($nonEmpty) < 2) {
                continue;
            }

            $score = 0;
            foreach ($row as $cell) {
                $cleanCell = strtolower(trim((string)$cell));
                $cleanCell = preg_replace('/[^a-z0-9]/', '', $cleanCell);
                if ($cleanCell === '') continue;

                foreach (array_merge($this->aliasNama, $this->aliasNis, $this->aliasKelas, ['no', 'nomor']) as $alias) {
                    $cleanAlias = preg_replace('/[^a-z0-9]/', '', $alias);
                    if ($cleanCell === $cleanAlias) {
                        $score += 2;
                        break;
                    } elseif (str_contains($cleanCell, $cleanAlias)) {
                        $score += 1;
                        break;
                    }
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $headerRowIndex = $idx;
                $headers = $row;
                if ($score >= 4) {
                    break;
                }
            }
        }

        if (empty($headers)) {
            foreach ($rawRows as $idx => $row) {
                $nonEmpty = array_values(array_filter($row, fn($c) => !is_null($c) && trim((string)$c) !== ''));
                if (count($nonEmpty) >= 2) {
                    $headerRowIndex = $idx;
                    $headers = $row;
                    break;
                }
            }
        }

        if (empty($headers)) {
            throw new \Exception("Tidak dapat menemukan baris header pada berkas.");
        }

        // Deteksi mapping kolom otomatis
        $mapping = $this->autoDetectMapping($headers);
        $requiresMapping = ($mapping['nama'] === null || $mapping['nis'] === null || $mapping['kelas'] === null);

        $parsedRows = [];
        for ($i = $headerRowIndex + 1; $i < count($rawRows); $i++) {
            $row = $rawRows[$i];
            // Abaikan baris kosong
            if (empty(array_filter($row, fn($c) => !is_null($c) && trim((string)$c) !== ''))) {
                continue;
            }

            $rowData = [];
            foreach ($headers as $colIdx => $headerName) {
                $hName = trim((string)$headerName);
                if ($hName === '') {
                    $hName = 'Kolom ' . ($colIdx + 1);
                }
                $rowData[$colIdx] = trim((string)($row[$colIdx] ?? ''));
            }

            $parsedRows[] = [
                'raw' => $rowData,
                'nis' => $mapping['nis'] !== null ? trim((string)($row[$mapping['nis']] ?? '')) : '',
                'nama' => $mapping['nama'] !== null ? trim((string)($row[$mapping['nama']] ?? '')) : '',
                'kelas' => $mapping['kelas'] !== null ? $this->normalizeKelas((string)($row[$mapping['kelas']] ?? '')) : '',
            ];
        }

        if (empty($parsedRows)) {
            throw new \Exception("Berkas tidak memiliki baris data siswa yang valid.");
        }

        return [
            'type' => 'tabular',
            'headers' => array_map(function ($h, $idx) {
                $trimmed = trim((string)$h);
                return $trimmed !== '' ? $trimmed : 'Kolom ' . ($idx + 1);
            }, $headers, array_keys($headers)),
            'mapping' => $mapping,
            'requires_mapping' => $requiresMapping,
            'rows' => $parsedRows,
        ];
    }

    /**
     * Memproses baris teks (pola Key-Value atau baris berulang)
     */
    protected function parseTextLines(array $lines): array
    {
        $students = [];
        $current = ['nis' => '', 'nama' => '', 'kelas' => ''];
        $foundAny = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                if (!empty($current['nama']) || !empty($current['nis'])) {
                    $students[] = [
                        'raw' => $current,
                        'nis' => trim($current['nis']),
                        'nama' => trim($current['nama']),
                        'kelas' => $this->normalizeKelas($current['kelas']),
                    ];
                    $current = ['nis' => '', 'nama' => '', 'kelas' => ''];
                }
                continue;
            }

            // Pola: "Nama: Ahmad", "NIS: 123", "Kelas: X TKJ"
            if (preg_match('/^(?:nama\s*siswa|nama\s*lengkap|nama)\s*[:=]\s*(.+)$/i', $trimmed, $m)) {
                $current['nama'] = $m[1];
                $foundAny = true;
            } elseif (preg_match('/^(?:nomor\s*induk\s*siswa|nomor\s*induk|no\s*induk|nis)\s*[:=]\s*(.+)$/i', $trimmed, $m)) {
                $current['nis'] = $m[1];
                $foundAny = true;
            } elseif (preg_match('/^(?:kelas\s*siswa|rombel|kelas)\s*[:=]\s*(.+)$/i', $trimmed, $m)) {
                $current['kelas'] = $m[1];
                $foundAny = true;
            }
        }

        if (!empty($current['nama']) || !empty($current['nis'])) {
            $students[] = [
                'raw' => $current,
                'nis' => trim($current['nis']),
                'nama' => trim($current['nama']),
                'kelas' => $this->normalizeKelas($current['kelas']),
            ];
        }

        // Jika pola Key-Value tidak ditemukan, coba pola kolom terpisah spasi/tab per baris
        if (empty($students) && !$foundAny) {
            $rawRows = [];
            foreach ($lines as $line) {
                // Split berdasarkan 2 spasi atau lebih, atau tab
                $parts = preg_split('/\t+|\s{2,}/', trim($line));
                if (count($parts) >= 2) {
                    $rawRows[] = $parts;
                }
            }

            if (count($rawRows) >= 2) {
                return $this->processTabularData($rawRows);
            }

            throw new \Exception(
                "Format data dalam berkas teks tidak dapat dikenali secara otomatis. " .
                "Gunakan template Excel/CSV yang tersedia atau format teks: Nama: ..., NIS: ..., Kelas: ..."
            );
        }

        if (empty($students)) {
            throw new \Exception("Tidak ada data siswa yang berhasil diekstrak dari berkas.");
        }

        return [
            'type' => 'structured_text',
            'headers' => ['NIS', 'Nama', 'Kelas'],
            'mapping' => ['nis' => 0, 'nama' => 1, 'kelas' => 2],
            'requires_mapping' => false,
            'rows' => $students,
        ];
    }

    /**
     * Deteksi otomatis mapping kolom berdasarkan nama header
     */
    public function autoDetectMapping(array $headers): array
    {
        $mapping = ['nama' => null, 'nis' => null, 'kelas' => null];

        foreach ($headers as $index => $header) {
            $cleanHeader = strtolower(trim((string)$header));
            $cleanHeader = preg_replace('/[^a-z0-9]/', '', $cleanHeader);

            // Deteksi NIS
            if ($mapping['nis'] === null) {
                foreach ($this->aliasNis as $alias) {
                    $cleanAlias = preg_replace('/[^a-z0-9]/', '', $alias);
                    if ($cleanHeader === $cleanAlias || str_contains($cleanHeader, $cleanAlias)) {
                        $mapping['nis'] = $index;
                        break;
                    }
                }
            }

            // Deteksi Nama
            if ($mapping['nama'] === null) {
                foreach ($this->aliasNama as $alias) {
                    $cleanAlias = preg_replace('/[^a-z0-9]/', '', $alias);
                    if ($cleanHeader === $cleanAlias || str_contains($cleanHeader, $cleanAlias)) {
                        $mapping['nama'] = $index;
                        break;
                    }
                }
            }

            // Deteksi Kelas
            if ($mapping['kelas'] === null) {
                foreach ($this->aliasKelas as $alias) {
                    $cleanAlias = preg_replace('/[^a-z0-9]/', '', $alias);
                    if ($cleanHeader === $cleanAlias || str_contains($cleanHeader, $cleanAlias)) {
                        $mapping['kelas'] = $index;
                        break;
                    }
                }
            }
        }

        return $mapping;
    }

    /**
     * Terapkan custom column mapping yang dipilih admin
     */
    public function applyMapping(array $rawRows, array $mapping): array
    {
        $nisCol = $mapping['nis'] ?? null;
        $namaCol = $mapping['nama'] ?? null;
        $kelasCol = $mapping['kelas'] ?? null;

        $mappedRows = [];
        foreach ($rawRows as $row) {
            $raw = $row['raw'] ?? $row;

            $nisVal = ($nisCol !== null && isset($raw[$nisCol])) ? trim((string)$raw[$nisCol]) : '';
            $namaVal = ($namaCol !== null && isset($raw[$namaCol])) ? trim((string)$raw[$namaCol]) : '';
            $kelasVal = ($kelasCol !== null && isset($raw[$kelasCol])) ? $this->normalizeKelas((string)$raw[$kelasCol]) : '';

            $mappedRows[] = [
                'raw' => $raw,
                'nis' => $nisVal,
                'nama' => $namaVal,
                'kelas' => $kelasVal,
            ];
        }

        return $mappedRows;
    }

    /**
     * Validasi data siswa sebelum disimpan ke database
     */
    public function validateRows(array $rows): array
    {
        $existingStudents = Siswa::pluck('nama', 'nis')->toArray();
        $seenNisInFile = [];

        $validatedRows = [];
        $summary = [
            'total' => count($rows),
            'ready' => 0,
            'duplicate' => 0,
            'error' => 0,
        ];

        foreach ($rows as $index => $row) {
            $nis = trim((string)($row['nis'] ?? ''));
            $nama = trim((string)($row['nama'] ?? ''));
            $kelas = trim((string)($row['kelas'] ?? ''));

            $status = 'ready';
            $errors = [];
            $existingNama = null;

            // 1. Validasi NIS
            if ($nis === '') {
                $status = 'error';
                $errors[] = 'NIS tidak boleh kosong';
            } elseif (isset($seenNisInFile[$nis])) {
                $status = 'error';
                $errors[] = 'NIS duplikat pada baris ' . ($seenNisInFile[$nis] + 1) . ' di dalam file';
            } else {
                $seenNisInFile[$nis] = $index;
            }

            // 2. Validasi Nama
            if ($nama === '') {
                $status = 'error';
                $errors[] = 'Nama siswa tidak boleh kosong';
            }

            // 3. Validasi Kelas
            if ($kelas === '') {
                $status = 'error';
                $errors[] = 'Kelas tidak boleh kosong';
            }

            // 4. Cek apakah NIS sudah ada di database (hanya jika tidak ada error fatal sebelumnya)
            if ($status === 'ready' && array_key_exists($nis, $existingStudents)) {
                $status = 'duplicate';
                $existingNama = $existingStudents[$nis];
            }

            // Hitung summary
            if ($status === 'ready') {
                $summary['ready']++;
            } elseif ($status === 'duplicate') {
                $summary['duplicate']++;
            } else {
                $summary['error']++;
            }

            $validatedRows[] = [
                'index' => $index + 1,
                'nis' => $nis,
                'nama' => $nama,
                'kelas' => $kelas,
                'status' => $status,
                'existing_nama' => $existingNama,
                'errors' => $errors,
            ];
        }

        return [
            'summary' => $summary,
            'rows' => $validatedRows,
        ];
    }

    /**
     * Eksekusi penyimpanan data siswa dengan Database Transaction
     */
    public function executeImport(array $rows, string $duplicateAction = 'skip'): array
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first()
            ?? TahunAjaran::orderByDesc('nama')->first();

        $importedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($rows, $duplicateAction, $tahunAktif, &$importedCount, &$updatedCount, &$skippedCount) {
            foreach ($rows as $row) {
                $nis = trim((string)($row['nis'] ?? ''));
                $nama = trim((string)($row['nama'] ?? ''));
                $kelas = trim((string)($row['kelas'] ?? ''));
                $status = $row['status'] ?? 'ready';

                // Lewati data error
                if ($status === 'error' || $nis === '' || $nama === '' || $kelas === '') {
                    $skippedCount++;
                    continue;
                }

                if ($status === 'duplicate') {
                    if ($duplicateAction === 'update') {
                        $existing = Siswa::where('nis', $nis)->first();
                        if ($existing) {
                            $existing->update([
                                'nama' => $nama,
                                'kelas' => $kelas,
                            ]);

                            if ($tahunAktif) {
                                DB::table('siswa_tahun_ajaran')->updateOrInsert(
                                    ['siswa_id' => $existing->id, 'tahun_ajaran_id' => $tahunAktif->id],
                                    ['kelas' => $kelas, 'updated_at' => now()]
                                );
                            }
                            $updatedCount++;
                        }
                    } else {
                        // Default: lewati data duplikat
                        $skippedCount++;
                    }
                    continue;
                }

                // Status 'ready' -> buat siswa baru
                $siswa = Siswa::create([
                    'nis' => $nis,
                    'nama' => $nama,
                    'kelas' => $kelas,
                ]);

                if ($tahunAktif) {
                    DB::table('siswa_tahun_ajaran')->updateOrInsert(
                        ['siswa_id' => $siswa->id, 'tahun_ajaran_id' => $tahunAktif->id],
                        ['kelas' => $siswa->kelas, 'created_at' => now(), 'updated_at' => now()]
                    );
                }

                $importedCount++;
            }
        });

        return [
            'imported' => $importedCount,
            'updated'  => $updatedCount,
            'skipped'  => $skippedCount,
        ];
    }

    /**
     * Normalisasi string kelas agar konsisten dengan pilihan standar sekolah
     */
    protected function normalizeKelas(string $kelasRaw): string
    {
        $k = trim($kelasRaw);
        if ($k === '') return '';

        $kUpper = strtoupper(preg_replace('/\s+/', ' ', $k));

        // Konversi penulisan angka Arab 10, 11, 12 ke Romawi X, XI, XII
        if (preg_match('/^10\s+/i', $kUpper)) {
            $kUpper = preg_replace('/^10\s+/i', 'X ', $kUpper);
        } elseif (preg_match('/^11\s+/i', $kUpper)) {
            $kUpper = preg_replace('/^11\s+/i', 'XI ', $kUpper);
        } elseif (preg_match('/^12\s+/i', $kUpper)) {
            $kUpper = preg_replace('/^12\s+/i', 'XII ', $kUpper);
        }

        // Variasi penulisan khusus
        $map = [
            '10 TKJ' => 'X TKJ',
            '10 TKR' => 'X TKR 1',
            '10 TKR 1' => 'X TKR 1',
            '10 TKR 2' => 'X TKR 2',
            'X TKR' => 'X TKR 1',
            '11 TKJ' => 'XI TKJ',
            '11 TKR' => 'XI TKR 1',
            '11 TKR 1' => 'XI TKR 1',
            '11 TKR 2' => 'XI TKR 2',
            'XI TKR' => 'XI TKR 1',
            '12 TKJ' => 'XII TKJ',
            '12 TKR' => 'XII TKR 1',
            '12 TKR 1' => 'XII TKR 1',
            '12 TKR 2' => 'XII TKR 2',
            'XII TKR' => 'XII TKR 1',
            'ALUMNI' => 'Lulus',
            'LULUS' => 'Lulus',
        ];

        if (isset($map[$kUpper])) {
            return $map[$kUpper];
        }

        return $kUpper;
    }
}
