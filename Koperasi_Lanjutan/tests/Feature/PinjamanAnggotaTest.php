<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pinjaman;
use App\Models\Pengurus\PinjamanSetting;
use Carbon\Carbon;

class PinjamanAnggotaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed settings for all tests
        PinjamanSetting::create(['tenor' => 12, 'bunga' => 1.5]);
    }

    /**
     * Test: User dapat mengakses halaman pengajuan pinjaman
     */
    public function test_user_dapat_mengakses_halaman_pinjaman()
    {
        $user = User::factory()->create([
            'bulan_masuk' => 'Januari 2023', // Asumsi sudah > 6 bulan
        ]);

        // Mock settings
        PinjamanSetting::create(['tenor' => 12, 'bunga' => 1.5]);

        $response = $this->actingAs($user)->get(route('user.pinjaman.create'));

        $response->assertStatus(200)
            ->assertViewIs('user.pinjaman.create');
    }

    /**
     * Test: User baru (< 6 bulan) tidak bisa mengajukan pinjaman
     */
    public function test_user_baru_tidak_bisa_mengajukan_pinjaman()
    {
        // Set user baru masuk bulan ini (Desember 2025 based on current time)
        // Controller expects Indonesian month names
        $bulanIni = 'Desember 2025'; 
        
        $user = User::factory()->create([
            'bulan_masuk' => $bulanIni,
        ]);

        $response = $this->actingAs($user)->post(route('user.pinjaman.store'), [
            'nominal' => 1000000,
            'tenor' => 12,
            'bunga' => 1.5,
        ]);

        // Harusnya redirect back dengan error
        $response->assertSessionHas('error');
    }

    /**
     * Test: User dapat mengajukan pinjaman jika memenuhi syarat
     */
    public function test_user_dapat_mengajukan_pinjaman()
    {
        // User masuk 1 tahun lalu (Desember 2024)
        $bulanMasuk = 'Desember 2024';
        
        $user = User::factory()->create([
            'bulan_masuk' => $bulanMasuk,
        ]);

        $response = $this->actingAs($user)->post(route('user.pinjaman.store'), [
            'nominal' => 5000000,
            'tenor' => 12,
            'bunga' => 1.5,
        ]);

        $response->assertRedirect(route('user.pinjaman.create'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pinjaman', [
            'user_id' => $user->id,
            'nominal' => 5000000,
            'status' => 'pending',
        ]);
    }

    /**
     * Test: Validasi nominal minimum
     */
    public function test_validasi_nominal_minimum()
    {
        $bulanMasuk = now()->subYear()->translatedFormat('F Y');
        $user = User::factory()->create(['bulan_masuk' => $bulanMasuk]);

        $response = $this->actingAs($user)->post(route('user.pinjaman.store'), [
            'nominal' => 50000, // Kurang dari 100,000
            'tenor' => 12,
            'bunga' => 1.5,
        ]);

        $response->assertSessionHasErrors('nominal');
    }

    /**
     * Test: Tidak bisa mengajukan jika ada pinjaman pending
     */
    public function test_tidak_bisa_mengajukan_jika_ada_pinjaman_pending()
    {
        $bulanMasuk = now()->subYear()->translatedFormat('F Y');
        $user = User::factory()->create(['bulan_masuk' => $bulanMasuk]);

        // Buat pinjaman pending
        Pinjaman::create([
            'user_id' => $user->id,
            'nominal' => 1000000,
            'tenor' => 12,
            'bunga' => 1.5,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)->post(route('user.pinjaman.store'), [
            'nominal' => 2000000,
            'tenor' => 12,
            'bunga' => 1.5,
        ]);

        $response->assertSessionHas('error');
    }
}
