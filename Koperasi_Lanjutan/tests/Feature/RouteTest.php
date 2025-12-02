<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman utama redirect ke login.
     */
    public function test_homepage_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test user dashboard memerlukan autentikasi.
     */
    public function test_user_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user dapat akses dashboard.
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'status' => 'aktif'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test profile route memerlukan autentikasi.
     */
    public function test_profile_route_requires_authentication(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user dapat akses profile.
     */
    public function test_authenticated_user_can_access_profile(): void
    {
        $user = User::factory()->create([
            'status' => 'aktif'
        ]);

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
    }

    /**
     * Test pengurus dashboard memerlukan autentikasi pengurus.
     */
    public function test_pengurus_dashboard_requires_authentication(): void
    {
        $response = $this->get('/pengurus/dashboard');
        $response->assertRedirect('/pengurus/login');
    }

    /**
     * Test authenticated pengurus dapat akses dashboard.
     */
    public function test_authenticated_pengurus_can_access_dashboard(): void
    {
        $pengurus = Pengurus::factory()->create([
            'jabatan' => 'ketua'
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')->get('/pengurus/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test user pinjaman route memerlukan autentikasi.
     */
    public function test_user_pinjaman_route_requires_authentication(): void
    {
        $response = $this->get('/anggota/pinjaman/create');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user dapat akses halaman pinjaman.
     */
    public function test_authenticated_user_can_access_pinjaman_page(): void
    {
        $user = User::factory()->create([
            'status' => 'aktif',
            'bulan_masuk' => 'Januari 2020' // Sudah lebih dari 6 bulan
        ]);

        $response = $this->actingAs($user)->get('/anggota/pinjaman/create');
        $response->assertStatus(200);
    }

    /**
     * Test logout route berfungsi.
     */
    public function test_logout_route_works(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
