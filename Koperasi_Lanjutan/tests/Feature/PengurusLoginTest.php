<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Pengurus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PengurusLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Pengurus dapat mengakses halaman login pengurus
     */
    public function test_pengurus_dapat_mengakses_halaman_login()
    {
        $response = $this->get('/pengurus/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login-pengurus');
    }

    /**
     * Test: Pengurus dapat login dengan username dan password yang benar
     */
    public function test_pengurus_dapat_login_dengan_username_dan_password_benar()
    {
        $pengurus = Pengurus::factory()->create([
            'username' => 'admin_pengurus',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);

        $response = $this->post('/pengurus/login', [
            'username' => 'admin_pengurus',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('pengurus.dashboard.index'));
        $this->assertTrue(Auth::guard('pengurus')->check());
        $this->assertEquals($pengurus->id, Auth::guard('pengurus')->id());
    }

    /**
     * Test: Login pengurus gagal jika username tidak ditemukan
     */
    public function test_login_pengurus_gagal_jika_username_tidak_ditemukan()
    {
        $response = $this->post('/pengurus/login', [
            'username' => 'usernotfound',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('username');
        $response->assertStatus(302);
        $this->assertFalse(Auth::guard('pengurus')->check());
    }

    /**
     * Test: Login pengurus gagal jika password salah
     */
    public function test_login_pengurus_gagal_jika_password_salah()
    {
        $pengurus = Pengurus::factory()->create([
            'username' => 'admin_pengurus',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/pengurus/login', [
            'username' => 'admin_pengurus',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('username');
        $response->assertStatus(302);
        $this->assertFalse(Auth::guard('pengurus')->check());
    }

    /**
     * Test: Login pengurus gagal jika username tidak diisi
     */
    public function test_login_pengurus_gagal_jika_username_tidak_diisi()
    {
        $response = $this->post('/pengurus/login', [
            'username' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('username');
        $response->assertStatus(302);
        $this->assertFalse(Auth::guard('pengurus')->check());
    }

    /**
     * Test: Login pengurus gagal jika password tidak diisi
     */
    public function test_login_pengurus_gagal_jika_password_tidak_diisi()
    {
        $response = $this->post('/pengurus/login', [
            'username' => 'admin_pengurus',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertStatus(302);
        $this->assertFalse(Auth::guard('pengurus')->check());
    }

    /**
     * Test: Pengurus dapat logout
     */
    public function test_pengurus_dapat_logout()
    {
        $pengurus = Pengurus::factory()->create();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post('/pengurus/logout');

        $response->assertRedirect('/pengurus/login');
        $this->assertFalse(Auth::guard('pengurus')->check());
    }

    /**
     * Test: Pengurus dengan role berbeda dapat login
     */
    public function test_pengurus_berbagai_role_dapat_login()
    {
        $roles = ['superadmin', 'bendahara', 'sekretaris', 'ketua'];

        foreach ($roles as $role) {
            $pengurus = Pengurus::factory()->create([
                'username' => "pengurus_{$role}",
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);

            $response = $this->post('/pengurus/login', [
                'username' => "pengurus_{$role}",
                'password' => 'password123',
            ]);

            $response->assertRedirect(route('pengurus.dashboard.index'));
            $this->assertTrue(Auth::guard('pengurus')->check());

            // Logout untuk test berikutnya
            Auth::guard('pengurus')->logout();
        }
    }

    /**
     * Test: Input username tetap tersimpan setelah login gagal
     */
    public function test_input_username_tetap_tersimpan_setelah_login_pengurus_gagal()
    {
        $response = $this->post('/pengurus/login', [
            'username' => 'admin_pengurus',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $this->assertNotNull(session()->getOldInput('username'));
    }
}
