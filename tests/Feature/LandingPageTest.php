<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_landing_page_accessible_without_authentication(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Kelola Praktik Kerja Lapangan Lebih Mudah dan Terintegrasi');
        $response->assertSee('Platform digital untuk membantu sekolah, siswa, guru pembimbing, dan mitra industri');
        $response->assertSee('#tentang');
        $response->assertSee('#alur');
        $response->assertSee('#fitur');
    }

    public function test_guest_sees_login_button_pointing_to_login_route(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('login'));
        $response->assertSee('Login ke Sistem');
        // Guest must not see internal dashboard links
        $response->assertDontSee(route('dashboard'));
        $response->assertDontSee('Buka Dashboard');
    }

    public function test_authenticated_user_sees_dashboard_link(): void
    {
        $user = User::create([
            'username' => 'kajur_user',
            'nama' => 'Kajur RPL',
            'password' => Hash::make('password123'),
            'role' => 'kajur',
            'jurusan_id' => 'RPL',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('dashboard'));
        $response->assertSee('Buka Dashboard');
    }

    public function test_internal_dashboard_remains_protected_by_auth_middleware(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_role_based_access_remains_enforced(): void
    {
        $siswa = User::create([
            'username' => 'siswa_test',
            'nama' => 'Siswa Test',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'is_active' => true,
        ]);

        // Siswa must not be allowed to access admin master sync route
        $response = $this->actingAs($siswa)->get('/master');
        $response->assertStatus(403);
    }
}
