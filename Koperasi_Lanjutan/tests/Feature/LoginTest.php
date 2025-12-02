<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat mengakses halaman login
     */
    public function test_user_dapat_mengakses_halaman_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test: User dapat login dengan NIP dan password yang benar
     */
    public function test_user_dapat_login_dengan_nip_dan_password_benar()
    {
        $user = User::factory()->create([
            'nip' => '1234567890',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'nip' => '1234567890',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.dashboard.index'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    /**
     * Test: User dapat login dengan username dan password yang benar
     */
    public function test_user_dapat_login_dengan_username_dan_password_benar()
    {
        $user = User::factory()->create([
            'nip' => null,
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'nip' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.dashboard.index'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    /**
     * Test: User dengan status aktif diarahkan ke dashboard
     */
    public function test_user_dengan_status_aktif_diarahkan_ke_dashboard()
    {
        $user = User::factory()->create([
            'nip' => '1234567890',
            'password' => Hash::make('password123'),
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'nip' => '1234567890',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.dashboard.index'));
    }

    /**
     * Test: User dengan status non-aktif diarahkan ke guest dashboard dengan warning
     */
    public function test_user_dengan_status_non_aktif_diarahkan_ke_guest_dashboard()
    {
        $user = User::factory()->create([
            'nip' => '1234567890',
            'password' => Hash::make('password123'),
            'status' => 'pending',
        ]);

        $response = $this->post('/login', [
            'nip' => '1234567890',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('guest.dashboard'));
        $response->assertSessionHas('warning', 'Akun Anda belum aktif.');
        $this->assertTrue(Auth::check()); // User tetap login
    }

    /**
     * Test: Login gagal jika NIP tidak ditemukan
     */
    public function test_login_gagal_jika_nip_tidak_ditemukan()
    {
        $response = $this->post('/login', [
            'nip' => '9999999999',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('nip');
        $response->assertStatus(302); // Redirect back
        $this->assertFalse(Auth::check());
    }

    /**
     * Test: Login gagal jika password salah
     */
    public function test_login_gagal_jika_password_salah()
    {
        $user = User::factory()->create([
            'nip' => '1234567890',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'nip' => '1234567890',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('nip');
        $response->assertStatus(302); // Redirect back
        $this->assertFalse(Auth::check());
    }

    /**
     * Test: Login gagal jika NIP tidak diisi
     */
    public function test_login_gagal_jika_nip_tidak_diisi()
    {
        $response = $this->post('/login', [
            'nip' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('nip');
        $response->assertStatus(302); // Redirect back
        $this->assertFalse(Auth::check());
    }

    /**
     * Test: Login gagal jika password tidak diisi
     */
    public function test_login_gagal_jika_password_tidak_diisi()
    {
        $response = $this->post('/login', [
            'nip' => '1234567890',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertStatus(302); // Redirect back
        $this->assertFalse(Auth::check());
    }

    /**
     * Test: User yang sudah login dapat mengakses halaman login
     * Note: Middleware logout.if.authenticated mungkin hanya logout user, tidak redirect
     */
    public function test_user_yang_sudah_login_dapat_mengakses_halaman_login()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->get('/login');
        
        // Middleware mungkin hanya logout user tanpa redirect
        // Atau mengizinkan akses ke halaman login
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test: Input NIP tetap tersimpan setelah login gagal
     */
    public function test_input_nip_tetap_tersimpan_setelah_login_gagal()
    {
        $response = $this->post('/login', [
            'nip' => '1234567890',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        // Cek bahwa input NIP masih ada di session (withInput)
        $this->assertNotNull(session()->getOldInput('nip'));
    }
}

