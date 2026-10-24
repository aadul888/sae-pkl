<?php

namespace Tests\Feature;

use App\Contracts\SaeIntegrationInterface;
use App\Models\AuditLog;
use App\Models\RefJurusan;
use App\Models\RefSekolah;
use App\Models\RefSiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class Phase2IntegrationAndRbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_middleware_allows_authorized_role(): void
    {
        $admin = User::create([
            'username' => 'admin_role',
            'nama' => 'Admin Role Test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/master');
        $response->assertStatus(200);
        $response->assertSee('Integrasi & Data Master SAE Utama', false);
    }

    public function test_role_middleware_blocks_unauthorized_role(): void
    {
        $siswa = User::create([
            'username' => 'siswa_role',
            'nama' => 'Siswa Role Test',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($siswa)->get('/master');
        $response->assertStatus(403);
    }

    public function test_sec01_kajur_cannot_view_or_verify_other_department_student(): void
    {
        // 1. Buat data referensi jurusan & siswa
        RefJurusan::create([
            'jurusan_id' => 'TKJ',
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
        ]);
        RefJurusan::create([
            'jurusan_id' => 'RPL',
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        $siswaTkj = RefSiswa::create([
            'peserta_didik_id' => 'pd-tkj-001',
            'nisn' => '1234567890',
            'nama' => 'Siswa TKJ',
            'jurusan_id' => 'TKJ',
            'status_kesiapan' => 'belum_verifikasi',
        ]);

        // 2. Buat akun Kajur RPL
        $kajurRpl = User::create([
            'username' => 'kajur_rpl',
            'nama' => 'Kajur RPL',
            'password' => Hash::make('password'),
            'role' => 'kajur',
            'jurusan_id' => 'RPL', // Terikat pada RPL
            'is_active' => true,
        ]);

        // 3. Kajur RPL mencoba melihat detail siswa TKJ -> Harus 403 Forbidden
        $viewResponse = $this->actingAs($kajurRpl)->get("/students/{$siswaTkj->peserta_didik_id}");
        $viewResponse->assertStatus(403);

        // 4. Kajur RPL mencoba memverifikasi kesiapan siswa TKJ -> Harus 403 Forbidden
        $verifyResponse = $this->actingAs($kajurRpl)->post("/students/{$siswaTkj->peserta_didik_id}/verify", [
            'status_kesiapan' => 'siap',
            'catatan_kesiapan' => 'Ilegal verification attempt',
        ]);
        $verifyResponse->assertStatus(403);

        // Pastikan status siswa TKJ TIDAK berubah di database
        $this->assertDatabaseHas('ref_siswa', [
            'peserta_didik_id' => 'pd-tkj-001',
            'status_kesiapan' => 'belum_verifikasi',
        ]);
    }

    public function test_sec02_waka_hubin_can_view_cross_department_students(): void
    {
        RefJurusan::create([
            'jurusan_id' => 'TKJ',
            'nama_jurusan' => 'Teknik Komputer dan Jaringan',
        ]);

        $siswaTkj = RefSiswa::create([
            'peserta_didik_id' => 'pd-tkj-002',
            'nisn' => '9876543210',
            'nama' => 'Ahmad TKJ',
            'jurusan_id' => 'TKJ',
            'status_kesiapan' => 'belum_verifikasi',
        ]);

        $hubin = User::create([
            'username' => 'waka_hubin',
            'nama' => 'Waka Hubin Sekolah',
            'password' => Hash::make('password'),
            'role' => 'waka_hubin',
            'jurusan_id' => null, // Lintas jurusan
            'is_active' => true,
        ]);

        // Waka Hubin dapat melihat detail siswa TKJ
        $response = $this->actingAs($hubin)->get("/students/{$siswaTkj->peserta_didik_id}");
        $response->assertStatus(200);
        $response->assertSee('Ahmad TKJ');
    }

    public function test_kajur_can_verify_own_department_student(): void
    {
        RefJurusan::create([
            'jurusan_id' => 'RPL',
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        $siswaRpl = RefSiswa::create([
            'peserta_didik_id' => 'pd-rpl-001',
            'nisn' => '1122334455',
            'nama' => 'Budi RPL',
            'jurusan_id' => 'RPL',
            'status_kesiapan' => 'belum_verifikasi',
        ]);

        $kajurRpl = User::create([
            'username' => 'kajur_rpl_auth',
            'nama' => 'Kajur RPL Sah',
            'password' => Hash::make('password'),
            'role' => 'kajur',
            'jurusan_id' => 'RPL',
            'is_active' => true,
        ]);

        $response = $this->actingAs($kajurRpl)->post("/students/{$siswaRpl->peserta_didik_id}/verify", [
            'status_kesiapan' => 'siap',
            'catatan_kesiapan' => 'Nilai semester 1-4 lengkap dan kompeten',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ref_siswa', [
            'peserta_didik_id' => 'pd-rpl-001',
            'status_kesiapan' => 'siap',
            'catatan_kesiapan' => 'Nilai semester 1-4 lengkap dan kompeten',
        ]);

        // Audit log tercatat
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $kajurRpl->id,
            'action' => 'student.readiness_verified',
        ]);
    }

    public function test_sync_master_command_with_mock_integration(): void
    {
        $mockSae = Mockery::mock(SaeIntegrationInterface::class);

        $mockSae->shouldReceive('getSekolah')->once()->andReturn([
            'sekolah_id' => 'sek-001',
            'npsn' => '20100001',
            'nama' => 'SMK Negeri 1 Contoh',
            'alamat' => 'Jl. Pendidikan No. 1',
        ]);

        $mockSae->shouldReceive('getJurusanList')->once()->andReturn([
            [
                'jurusan_id' => 'RPL',
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'bidang_keahlian' => 'TIK',
                'program_keahlian' => 'Teknik Komputer & Informatika',
            ]
        ]);

        $mockSae->shouldReceive('getGtkList')->once()->andReturn([
            [
                'ptk_id' => 'ptk-001',
                'nuptk' => '1234567',
                'nip' => '198501012010011001',
                'nama' => 'Drs. Supriyanto, M.Kom',
                'jenis_ptk' => 'Guru Mapel Kejuruan',
                'no_hp' => '08123456789',
                'is_active' => true,
            ]
        ]);

        $mockSae->shouldReceive('getActiveDuties')->once()->andReturn([
            [
                'ptk_id' => 'ptk-001',
                'nama' => 'Drs. Supriyanto, M.Kom',
                'duty_code' => 'KAPROG',
                'jurusan_id' => 'RPL',
            ]
        ]);

        $mockSae->shouldReceive('getPesertaDidikByJurusan')->once()->andReturn([
            [
                'peserta_didik_id' => 'pd-001',
                'nisn' => '0051234567',
                'nipd' => '22231001',
                'nik' => '3201012345670001',
                'nama' => 'Bintang Pratama',
                'jenis_kelamin' => 'L',
                'nama_rombel' => 'XII RPL 1',
                'rombongan_belajar_id' => 'rombel-001',
                'jurusan_id' => 'RPL',
                'no_hp' => '08987654321',
            ]
        ]);

        $this->app->instance(SaeIntegrationInterface::class, $mockSae);

        $this->artisan('sae:sync-master')
            ->expectsOutputToContain('Sinkronisasi data master selesai dengan sukses!')
            ->assertSuccessful();

        // Verifikasi data masuk ke database lokal
        $this->assertDatabaseHas('ref_sekolah', [
            'sekolah_id' => 'sek-001',
            'npsn' => '20100001',
            'nama' => 'SMK Negeri 1 Contoh',
        ]);

        $this->assertDatabaseHas('ref_jurusan', [
            'jurusan_id' => 'RPL',
            'kajur_ptk_id' => 'ptk-001',
            'kajur_nama' => 'Drs. Supriyanto, M.Kom',
        ]);

        $this->assertDatabaseHas('ref_guru', [
            'ptk_id' => 'ptk-001',
            'nama' => 'Drs. Supriyanto, M.Kom',
        ]);

        $this->assertDatabaseHas('ref_siswa', [
            'peserta_didik_id' => 'pd-001',
            'nama' => 'Bintang Pratama',
            'jurusan_id' => 'RPL',
        ]);

        // Verifikasi audit log tercatat
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'master.sync',
        ]);
    }

    public function test_sec06_graceful_degradation_when_sae_database_fails(): void
    {
        $mockSae = Mockery::mock(SaeIntegrationInterface::class);

        // Simulasi database eksternal down / tidak merespons
        $mockSae->shouldReceive('getSekolah')->once()->andReturn(null);
        $mockSae->shouldReceive('getJurusanList')->once()->andReturn([]);
        $mockSae->shouldReceive('getGtkList')->once()->andReturn([]);
        $mockSae->shouldReceive('getActiveDuties')->once()->andReturn([]);
        $mockSae->shouldReceive('getPesertaDidikByJurusan')->once()->andReturn([]);

        $this->app->instance(SaeIntegrationInterface::class, $mockSae);

        // Command harus selesai tanpa crash unhandled exception
        $this->artisan('sae:sync-master')
            ->expectsOutputToContain('Data sekolah tidak ditemukan atau koneksi belum siap')
            ->assertSuccessful();

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'master.sync',
        ]);
    }
}
