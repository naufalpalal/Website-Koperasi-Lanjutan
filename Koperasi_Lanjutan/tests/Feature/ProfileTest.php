<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat mengakses halaman profile
     */
    public function test_user_dapat_mengakses_halaman_profile()
    {
        $user = User::factory()->create();
                /** @var User $user */

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200)
            ->assertViewIs('profile.edit');
    }

    /**
     * Test: User dapat mengupdate profile
     */
    public function test_user_dapat_mengupdate_profile()
    {
        $user = User::factory()->create([
            'nama' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        /** @var User $user */
        $response = $this->actingAs($user)->post('/profile', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertEquals('Jane Doe', $user->nama);
        $this->assertEquals('jane@example.com', $user->email);
    }

    /**
     * Test: User dapat mengupdate password
     */
    public function test_user_dapat_mengupdate_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        /** @var User $user */
        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test: Update password gagal jika current password salah
     */
    public function test_update_password_gagal_jika_current_password_salah()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        /** @var User $user */
        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    /**
     * Test: Update password gagal jika password tidak sesuai konfirmasi
     */
    public function test_update_password_gagal_jika_password_tidak_sesuai()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        /** @var User $user */
        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test: Guest tidak dapat mengakses halaman profile
     */
    public function test_guest_tidak_dapat_mengakses_profile()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }
}

