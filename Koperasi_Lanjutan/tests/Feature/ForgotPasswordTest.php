<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordResetRequest;
use App\Mail\ResetPasswordMail;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat request reset password dengan NIP dan email
     */
    public function test_user_dapat_request_reset_password_dengan_nip_dan_email()
    {
        Mail::fake();

        $user = User::factory()->create([
            'nip' => '12345',
            'email' => 'user@example.com',
        ]);

        $response = $this->postJson('/forgot-password/send', [
            'nip' => '12345',
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Link reset password telah dikirim ke email Anda.'
            ]);

        Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertDatabaseHas('password_reset_requests', [
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'status' => 'token_sent',
        ]);
    }

    /**
     * Test: User dapat request reset password tanpa email terdaftar
     */
    public function test_user_dapat_request_reset_password_tanpa_email_terdaftar()
    {
        Mail::fake();

        $user = User::factory()->create([
            'nip' => '12345',
            'email' => null,
        ]);

        $response = $this->postJson('/forgot-password/send', [
            'nip' => '12345',
            'email' => 'newemail@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('password_reset_requests', [
            'user_id' => $user->id,
            'email' => 'newemail@example.com',
            'status' => 'token_sent',
        ]);
    }

    /**
     * Test: Gagal request reset jika NIP tidak ditemukan
     */
    public function test_gagal_request_reset_jika_nip_tidak_ditemukan()
    {
        $response = $this->postJson('/forgot-password/send', [
            'nip' => '99999',
            'email' => 'example@mail.com',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'NIP tidak ditemukan.'
            ]);
    }

    /**
     * Test: Gagal request reset jika email tidak sesuai dengan email terdaftar
     */
    public function test_gagal_request_reset_jika_email_tidak_sesuai()
    {
        User::factory()->create([
            'nip' => '12345',
            'email' => 'registered@example.com',
        ]);

        $response = $this->postJson('/forgot-password/send', [
            'nip' => '12345',
            'email' => 'wrong@example.com',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Email tidak sesuai dengan email terdaftar untuk NIP ini.'
            ]);
    }

    /**
     * Test: User dapat reset password dengan token valid
     */
    public function test_user_dapat_reset_password_dengan_token_valid()
    {
        $user = User::factory()->create([
            'nip' => '12345',
            'password' => Hash::make('oldpass123'),
        ]);

        $tokenPlain = Str::random(64);
        $tokenHash = Hash::make($tokenPlain);

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'token_hash' => $tokenHash,
            'status' => 'token_sent',
            'expires_at' => Carbon::now()->addMinutes(60),
            'ip' => '127.0.0.1',
        ]);

        $response = $this->postJson('/forgot-password/reset', [
            'nip' => '12345',
            'token' => $tokenPlain,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password berhasil direset!'
            ]);

        $this->assertTrue(
            Hash::check('newpassword123', $user->fresh()->password)
        );

        $this->assertDatabaseHas('password_reset_requests', [
            'user_id' => $user->id,
            'status' => 'used',
        ]);
    }

    /**
     * Test: Reset gagal jika token salah
     */
    public function test_reset_gagal_jika_token_salah()
    {
        $user = User::factory()->create(['nip' => '12345']);

        $tokenPlain = Str::random(64);
        $correctTokenHash = Hash::make($tokenPlain);

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'token_hash' => $correctTokenHash,
            'status' => 'token_sent',
            'expires_at' => Carbon::now()->addMinutes(60),
            'ip' => '127.0.0.1',
        ]);

        $response = $this->postJson('/forgot-password/reset', [
            'nip' => '12345',
            'token' => 'wrong-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Token tidak valid.'
            ]);
    }

    /**
     * Test: Reset gagal jika token expired
     */
    public function test_reset_gagal_jika_token_expired()
    {
        $user = User::factory()->create(['nip' => '12345']);

        $tokenPlain = Str::random(64);
        $tokenHash = Hash::make($tokenPlain);

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'token_hash' => $tokenHash,
            'status' => 'token_sent',
            'expires_at' => Carbon::now()->subMinutes(61), // Token sudah expired
            'ip' => '127.0.0.1',
        ]);

        $response = $this->postJson('/forgot-password/reset', [
            'nip' => '12345',
            'token' => $tokenPlain,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Token sudah kedaluwarsa.'
            ]);

        $this->assertDatabaseHas('password_reset_requests', [
            'user_id' => $user->id,
            'status' => 'expired',
        ]);
    }

    /**
     * Test: Reset gagal jika token sudah digunakan
     */
    public function test_reset_gagal_jika_token_sudah_digunakan()
    {
        $user = User::factory()->create(['nip' => '12345']);

        $tokenPlain = Str::random(64);
        $tokenHash = Hash::make($tokenPlain);

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'token_hash' => $tokenHash,
            'status' => 'used', // Token sudah digunakan
            'used_at' => Carbon::now()->subMinutes(10),
            'expires_at' => Carbon::now()->addMinutes(60),
            'ip' => '127.0.0.1',
        ]);

        $response = $this->postJson('/forgot-password/reset', [
            'nip' => '12345',
            'token' => $tokenPlain,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Token tidak ditemukan atau sudah digunakan.'
            ]);
    }

    /**
     * Test: Reset gagal jika NIP tidak ditemukan
     */
    public function test_reset_gagal_jika_nip_tidak_ditemukan()
    {
        $response = $this->postJson('/forgot-password/reset', [
            'nip' => '99999',
            'token' => 'some-token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'NIP tidak ditemukan.'
            ]);
    }

    /**
     * Test: Reset gagal jika password tidak sesuai konfirmasi
     */
    public function test_reset_gagal_jika_password_tidak_sesuai_konfirmasi()
    {
        $user = User::factory()->create(['nip' => '12345']);

        $tokenPlain = Str::random(64);
        $tokenHash = Hash::make($tokenPlain);

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'token_hash' => $tokenHash,
            'status' => 'token_sent',
            'expires_at' => Carbon::now()->addMinutes(60),
            'ip' => '127.0.0.1',
        ]);

        $response = $this->postJson('/forgot-password/reset', [
            'nip' => '12345',
            'token' => $tokenPlain,
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422); // Validation error
    }

    /**
     * Test: Reset gagal jika password terlalu pendek
     */
    public function test_reset_gagal_jika_password_terlalu_pendek()
    {
        $user = User::factory()->create(['nip' => '12345']);

        $tokenPlain = Str::random(64);
        $tokenHash = Hash::make($tokenPlain);

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'email' => 'user@example.com',
            'token_hash' => $tokenHash,
            'status' => 'token_sent',
            'expires_at' => Carbon::now()->addMinutes(60),
            'ip' => '127.0.0.1',
        ]);

        $response = $this->postJson('/forgot-password/reset', [
            'nip' => '12345',
            'token' => $tokenPlain,
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422); // Validation error
    }
}
