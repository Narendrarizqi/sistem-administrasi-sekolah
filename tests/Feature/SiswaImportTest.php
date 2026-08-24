<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Services\SiswaImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SiswaImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan ada Tahun Ajaran aktif
        TahunAjaran::firstOrCreate(
            ['nama' => '2026/2027'],
            [
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2027-06-30',
                'is_active' => true,
            ]
        );
    }

    public function test_can_download_template_excel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('siswa.import.template'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_can_parse_csv_file_with_custom_columns(): void
    {
        $user = User::factory()->create();

        // Buat file CSV simulasi dengan alias kolom (Nomor Induk, Nama Lengkap, Rombel)
        $csvContent = "No,Nomor Induk,NISN,Nama Lengkap,Jenis Kelamin,Rombel,Alamat\n" .
                      "1,99001,0012345678,Ahmad Dani,L,X TKJ,Margasari\n" .
                      "2,99002,0012345679,Budi Pratama,L,X TKR 1,Tegal\n";

        $file = UploadedFile::fake()->createWithContent('siswa_test.csv', $csvContent);

        $response = $this->actingAs($user)->post(route('siswa.import.parse'), [
            'file' => $file,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'preview',
            'summary' => [
                'total' => 2,
                'ready' => 2,
                'duplicate' => 0,
                'error' => 0,
            ],
        ]);
    }

    public function test_can_parse_txt_file_with_key_value_pattern(): void
    {
        $user = User::factory()->create();

        $txtContent = "Nama: Citra Lestari\nNIS: 99003\nKelas: XI TKJ\n\n" .
                      "Nama: Dimas Saputra\nNIS: 99004\nKelas: XII TKR 1\n";

        $file = UploadedFile::fake()->createWithContent('siswa_test.txt', $txtContent);

        $response = $this->actingAs($user)->post(route('siswa.import.parse'), [
            'file' => $file,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'preview',
            'summary' => [
                'total' => 2,
                'ready' => 2,
            ],
        ]);
    }

    public function test_detects_duplicates_and_empty_fields(): void
    {
        $user = User::factory()->create();

        // Buat siswa yang sudah ada di database
        Siswa::create([
            'nis' => '99005',
            'nama' => 'Eko Prasetyo',
            'kelas' => 'X TKJ',
        ]);

        $csvContent = "NIS,Nama,Kelas\n" .
                      "99005,Eko Prasetyo Baru,X TKJ\n" . // Duplikat di database
                      ",Fani Tanpa NIS,X TKR 1\n" .        // NIS kosong (error)
                      "99006,,X TKR 2\n" .                // Nama kosong (error)
                      "99007,Gita Nirmala,X TKJ\n";        // Siap diimport

        $file = UploadedFile::fake()->createWithContent('siswa_validation.csv', $csvContent);

        $response = $this->actingAs($user)->post(route('siswa.import.parse'), [
            'file' => $file,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'summary' => [
                'total' => 4,
                'ready' => 1,
                'duplicate' => 1,
                'error' => 2,
            ],
        ]);
    }

    public function test_can_confirm_import_and_skip_duplicates(): void
    {
        $user = User::factory()->create();

        // Siswa lama
        $siswaLama = Siswa::create([
            'nis' => '99010',
            'nama' => 'Hadi Lama',
            'kelas' => 'X TKJ',
        ]);

        $rowsToImport = [
            [
                'index' => 1,
                'nis' => '99010',
                'nama' => 'Hadi Baru',
                'kelas' => 'X TKR 1',
                'status' => 'duplicate',
                'errors' => [],
            ],
            [
                'index' => 2,
                'nis' => '99011',
                'nama' => 'Indra Wijaya',
                'kelas' => 'X TKJ',
                'status' => 'ready',
                'errors' => [],
            ],
        ];

        $response = $this->actingAs($user)->post(route('siswa.import.confirm'), [
            'rows' => $rowsToImport,
            'duplicate_action' => 'skip',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'result' => [
                'imported' => 1,
                'updated' => 0,
                'skipped' => 1,
            ],
        ]);

        // Pastikan Hadi lama tidak tertimpa
        $this->assertDatabaseHas('siswa', [
            'nis' => '99010',
            'nama' => 'Hadi Lama',
        ]);

        // Pastikan Indra baru masuk
        $this->assertDatabaseHas('siswa', [
            'nis' => '99011',
            'nama' => 'Indra Wijaya',
        ]);
    }

    public function test_can_confirm_import_and_update_duplicates(): void
    {
        $user = User::factory()->create();

        $siswaLama = Siswa::create([
            'nis' => '99020',
            'nama' => 'Joko Lama',
            'kelas' => 'X TKJ',
        ]);

        $rowsToImport = [
            [
                'index' => 1,
                'nis' => '99020',
                'nama' => 'Joko Terupdate',
                'kelas' => 'XI TKJ',
                'status' => 'duplicate',
                'errors' => [],
            ],
        ];

        $response = $this->actingAs($user)->post(route('siswa.import.confirm'), [
            'rows' => $rowsToImport,
            'duplicate_action' => 'update',
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'result' => [
                'imported' => 0,
                'updated' => 1,
                'skipped' => 0,
            ],
        ]);

        $this->assertDatabaseHas('siswa', [
            'nis' => '99020',
            'nama' => 'Joko Terupdate',
            'kelas' => 'XI TKJ',
        ]);
    }

    public function test_needs_mapping_when_columns_are_unknown(): void
    {
        $user = User::factory()->create();

        $csvContent = "Data_Satu,Data_Dua,Data_Tiga\n" .
                      "99030,Kiki Amelia,XII TKJ\n";

        $file = UploadedFile::fake()->createWithContent('siswa_unknown.csv', $csvContent);

        $response = $this->actingAs($user)->post(route('siswa.import.parse'), [
            'file' => $file,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'need_mapping',
        ]);

        // Simulasikan submit mapping manual
        $mappingResponse = $this->actingAs($user)->post(route('siswa.import.mapping'), [
            'raw_rows' => $response->json('raw_rows'),
            'mapping' => [
                'nis' => 0,
                'nama' => 1,
                'kelas' => 2,
            ],
        ], ['Accept' => 'application/json']);

        $mappingResponse->assertStatus(200);
        $mappingResponse->assertJson([
            'success' => true,
            'status' => 'preview',
            'summary' => [
                'total' => 1,
                'ready' => 1,
            ],
        ]);
    }
}
