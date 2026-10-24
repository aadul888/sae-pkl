<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_can_be_rendered_for_guest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SAE PKL');
        $response->assertSee('Kelola Praktik Kerja Lapangan Lebih Mudah dan Terintegrasi');
        $response->assertSee('Alur Pelaksanaan PKL');
        $response->assertSee('Fitur SAE PKL');
        $response->assertSee(route('login'));
        $response->assertDontSee(route('dashboard'));
    }

    public function test_landing_page_shows_dashboard_link_when_authenticated(): void
    {
        $user = User::create([
            'username' => 'test_user',
            'nama' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Buka Dashboard');
        $response->assertSee(route('dashboard'));
    }
}
