<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Phase1FoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('SAE PKL');
        $response->assertSee('Masuk');
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_authenticate_and_access_dashboard(): void
    {
        $admin = User::create([
            'username' => 'admin_test',
            'nama' => 'Admin Tester',
            'email' => 'admintest@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $loginResponse = $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $loginResponse->assertRedirect(route('dashboard'));

        $dashboardResponse = $this->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Admin Tester');
        $dashboardResponse->assertSee('FASE 1 SELESAI');

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'role' => 'admin',
            'action' => 'auth.login',
        ]);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::create([
            'username' => 'inactive_user',
            'nama' => 'Inactive User',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'username' => 'inactive_user',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('username');

        // Verify audit log for failed login
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'auth.login_failed',
        ]);
    }

    public function test_audit_log_sanitizes_sensitive_keys(): void
    {
        $log = AuditLog::record('test.sensitive', null, null, [
            'username' => 'budi',
            'password' => 'supersecret',
            'remember_token' => 'token123',
            'meta' => [
                'api_key' => 'secret_key',
            ],
        ]);

        $this->assertNotNull($log);
        $this->assertEquals('[REDACTED]', $log->new_values['password']);
        $this->assertEquals('[REDACTED]', $log->new_values['remember_token']);
        $this->assertEquals('[REDACTED]', $log->new_values['meta']['api_key']);
        $this->assertEquals('budi', $log->new_values['username']);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::create([
            'username' => 'user_logout',
            'nama' => 'Logout User',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');

        // Verify logout audit log
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'role' => 'admin',
            'action' => 'auth.logout',
        ]);
    }

    public function test_setting_model_helpers(): void
    {
        Setting::set('test_string', 'Halo SAE PKL', 'string', 'general');
        $this->assertEquals('Halo SAE PKL', Setting::get('test_string'));

        Setting::set('test_bool', true, 'boolean', 'general');
        $this->assertTrue(Setting::get('test_bool'));

        Setting::set('test_int', 42, 'integer', 'general');
        $this->assertSame(42, Setting::get('test_int'));

        Setting::set('test_json', ['key' => 'val'], 'json', 'general');
        $this->assertEquals(['key' => 'val'], Setting::get('test_json'));

        $this->assertEquals('default_val', Setting::get('non_existent_key', 'default_val'));
    }

    public function test_user_role_helper_methods(): void
    {
        $admin = new User(['role' => 'admin']);
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isKajur());

        $kajur = new User(['role' => 'kajur', 'jurusan_id' => 'RPL']);
        $this->assertTrue($kajur->isKajur());
        $this->assertEquals('RPL', $kajur->jurusan_id);

        $hubin = new User(['role' => 'waka_hubin']);
        $this->assertTrue($hubin->isWakaHubin());
        $this->assertNull($hubin->jurusan_id);

        $guru = new User(['role' => 'guru_pembimbing']);
        $this->assertTrue($guru->isGuruPembimbing());

        $industri = new User(['role' => 'pembimbing_industri', 'mitra_id' => 10]);
        $this->assertTrue($industri->isPembimbingIndustri());

        $siswa = new User(['role' => 'siswa']);
        $this->assertTrue($siswa->isSiswa());
    }

    public function test_user_can_change_password(): void
    {
        $user = User::create([
            'username' => 'user_pwd_test',
            'nama' => 'User Pwd Test',
            'password' => Hash::make('old_password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        // 1. Current password salah -> gagal
        $failResponse = $this->post('/profile/password', [
            'current_password' => 'wrong_password',
            'password' => 'new_secret_123',
            'password_confirmation' => 'new_secret_123',
        ]);
        $failResponse->assertSessionHasErrors('current_password');

        // 2. Current password benar -> sukses
        $successResponse = $this->post('/profile/password', [
            'current_password' => 'old_password',
            'password' => 'new_secret_123',
            'password_confirmation' => 'new_secret_123',
        ]);
        $successResponse->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertTrue(Hash::check('new_secret_123', $user->password));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'auth.password_changed',
        ]);
    }

    public function test_admin_setup_command(): void
    {
        $this->artisan('sae:admin-setup', [
            '--username' => 'superadmin',
            '--nama' => 'Super Administrator',
            '--email' => 'super@sekolah.id',
            '--password' => 'secure_password_88',
        ])
        ->expectsOutputToContain('Akun administrator [superadmin] berhasil dibuat!')
        ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'username' => 'superadmin',
            'nama' => 'Super Administrator',
            'role' => 'admin',
        ]);

        $admin = User::where('username', 'superadmin')->first();
        $this->assertTrue(Hash::check('secure_password_88', $admin->password));
    }
}
