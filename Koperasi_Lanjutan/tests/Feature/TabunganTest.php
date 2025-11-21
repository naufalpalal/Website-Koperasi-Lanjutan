<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Tabungan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TabunganTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat mengakses halaman tabungan
     */
    public function test_user_dapat_mengakses_halaman_tabungan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tabungan');

        $response->assertStatus(200)
            ->assertViewIs('user.simpanan.tabungan.index');
    }

    /**
     * Test: User dapat melihat riwayat tabungan
     */
    public function test_user_dapat_melihat_riwayat_tabungan()
    {
        $user = User::factory()->create();

        Tabungan::factory()->count(3)->create([
            'users_id' => $user->id,
            'status' => 'diterima',
        ]);

        $response = $this->actingAs($user)->get('/tabungan/history');

        $response->assertStatus(200)
            ->assertViewIs('user.simpanan.tabungan.history');
    }

    /**
     * Test: User dapat menyimpan tabungan baru
     */
    public function test_user_dapat_menyimpan_tabungan_baru()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('bukti-transfer.jpg');

        $response = $this->actingAs($user)->post('/tabungan/store', [
            'nilai' => 100000,
            'tanggal' => now()->format('Y-m-d'),
            'bukti_transfer' => $file,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungans', [
            'users_id' => $user->id,
            'nilai' => 100000,
            'status' => 'pending',
        ]);
    }

    /**
     * Test: Simpan tabungan gagal jika nilai terlalu kecil
     */
    public function test_simpan_tabungan_gagal_jika_nilai_terlalu_kecil()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('bukti-transfer.jpg');

        $response = $this->actingAs($user)->post('/tabungan/store', [
            'nilai' => 50, // Kurang dari minimum 100
            'tanggal' => now()->format('Y-m-d'),
            'bukti_transfer' => $file,
        ]);

        $response->assertSessionHasErrors('nilai');
    }

    /**
     * Test: Simpan tabungan gagal jika tanggal sebelum hari ini
     */
    public function test_simpan_tabungan_gagal_jika_tanggal_sebelum_hari_ini()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('bukti-transfer.jpg');

        $response = $this->actingAs($user)->post('/tabungan/store', [
            'nilai' => 100000,
            'tanggal' => now()->subDay()->format('Y-m-d'),
            'bukti_transfer' => $file,
        ]);

        $response->assertSessionHasErrors('tanggal');
    }

    /**
     * Test: Simpan tabungan gagal jika bukti transfer tidak diupload
     */
    public function test_simpan_tabungan_gagal_jika_bukti_transfer_tidak_diupload()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tabungan/store', [
            'nilai' => 100000,
            'tanggal' => now()->format('Y-m-d'),
            // bukti_transfer tidak diupload
        ]);

        $response->assertSessionHasErrors('bukti_transfer');
    }

    /**
     * Test: Guest tidak dapat mengakses halaman tabungan
     */
    public function test_guest_tidak_dapat_mengakses_tabungan()
    {
        $response = $this->get('/tabungan');

        $response->assertRedirect('/login');
    }

    /**
     * Test: User hanya melihat tabungan miliknya sendiri
     */
    public function test_user_hanya_melihat_tabungan_miliknya_sendiri()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Tabungan::factory()->create([
            'users_id' => $user1->id,
            'nilai' => 100000,
        ]);

        Tabungan::factory()->create([
            'users_id' => $user2->id,
            'nilai' => 200000,
        ]);

        $response = $this->actingAs($user1)->get('/tabungan');

        $response->assertStatus(200);
        $this->assertCount(1, $response->viewData('tabungans'));
    }
}

