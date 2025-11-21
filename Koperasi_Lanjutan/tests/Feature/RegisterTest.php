<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat mengakses halaman register
     */
    public function test_user_dapat_mengakses_halaman_register()
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
            ->assertViewIs('auth.register');
    }

    /**
     * Test: User dapat register sebagai pegawai dengan NIP
     */
    public function test_user_dapat_register_sebagai_pegawai()
    {
        $response = $this->post('/register', [
            'jenis_anggota' => 'pegawai',
            'name' => 'John Doe',
            'nip_username' => '1234567890',
            'no_telepon' => '081234567890',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'alamat_rumah' => 'Jl. Test No. 123',
            'unit_kerja' => 'IT',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertViewIs('guest.dashboard');

        $this->assertDatabaseHas('users', [
            'nama' => 'John Doe',
            'nip' => '1234567890',
            'username' => null,
            'no_telepon' => '081234567890',
        ]);

        $user = User::where('nip', '1234567890')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test: User dapat register sebagai non-pegawai dengan username
     */
    public function test_user_dapat_register_sebagai_non_pegawai()
    {
        $response = $this->post('/register', [
            'jenis_anggota' => 'non_pegawai',
            'name' => 'Jane Doe',
            'nip_username' => 'jane_doe',
            'no_telepon' => '081234567891',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1992-05-15',
            'alamat_rumah' => 'Jl. Test No. 456',
            'unit_kerja' => 'Marketing',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertViewIs('guest.dashboard');

        $this->assertDatabaseHas('users', [
            'nama' => 'Jane Doe',
            'username' => 'jane_doe',
            'nip' => null,
            'no_telepon' => '081234567891',
        ]);
    }

    /**
     * Test: Register gagal jika password tidak sesuai konfirmasi
     */
    public function test_register_gagal_jika_password_tidak_sesuai()
    {
        $response = $this->post('/register', [
            'jenis_anggota' => 'pegawai',
            'name' => 'John Doe',
            'nip_username' => '1234567890',
            'no_telepon' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test: Register gagal jika password terlalu pendek
     */
    public function test_register_gagal_jika_password_terlalu_pendek()
    {
        $response = $this->post('/register', [
            'jenis_anggota' => 'pegawai',
            'name' => 'John Doe',
            'nip_username' => '1234567890',
            'no_telepon' => '081234567890',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test: Register gagal jika data wajib tidak diisi
     */
    public function test_register_gagal_jika_data_wajib_tidak_diisi()
    {
        $response = $this->post('/register', [
            'jenis_anggota' => 'pegawai',
            // name tidak diisi
            'nip_username' => '1234567890',
            // no_telepon tidak diisi
        ]);

        $response->assertSessionHasErrors(['name', 'no_telepon', 'password']);
    }

    /**
     * Test: User otomatis login setelah register
     */
    public function test_user_otomatis_login_setelah_register()
    {
        $response = $this->post('/register', [
            'jenis_anggota' => 'pegawai',
            'name' => 'John Doe',
            'nip_username' => '1234567890',
            'no_telepon' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
    }
}

