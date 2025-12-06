<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\User\MasterSimpananSukarela;
use App\Models\Pengurus\SimpananSukarela;
use Carbon\Carbon;

class PengajuanSukarelaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat mengakses halaman pengajuan simpanan sukarela
     */
    public function test_user_dapat_mengakses_halaman_pengajuan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.simpanan.sukarela.pengajuan'));

        $response->assertStatus(200)
            ->assertViewIs('user.simpanan.sukarela.pengajuan');
    }

    /**
     * Test: User dapat mengajukan simpanan sukarela untuk bulan depan
     */
    public function test_user_dapat_mengajukan_simpanan_sukarela()
    {
        $user = User::factory()->create();
        
        $bulanDepan = now()->addMonth();

        $response = $this->actingAs($user)->post(route('user.simpanan.sukarela.store'), [
            'nilai' => 50000,
            'tahun' => $bulanDepan->year,
            'bulan' => $bulanDepan->month,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('master_simpanan_sukarela', [
            'users_id' => $user->id,
            'nilai' => 50000,
            'tahun' => $bulanDepan->year,
            'bulan' => $bulanDepan->month,
            'status' => 'Pending',
        ]);
    }

    /**
     * Test: Validasi nilai minimum 10000
     */
    public function test_validasi_nilai_minimum()
    {
        $user = User::factory()->create();
        $bulanDepan = now()->addMonth();

        $response = $this->actingAs($user)->post(route('user.simpanan.sukarela.store'), [
            'nilai' => 5000, // Kurang dari 10000
            'tahun' => $bulanDepan->year,
            'bulan' => $bulanDepan->month,
        ]);

        $response->assertSessionHasErrors('nilai');
    }

    /**
     * Test: Tidak bisa mengajukan duplikat untuk periode yang sama
     */
    public function test_tidak_bisa_mengajukan_duplikat()
    {
        $user = User::factory()->create();
        $bulanDepan = now()->addMonth();

        // Pengajuan pertama
        MasterSimpananSukarela::create([
            'users_id' => $user->id,
            'nilai' => 50000,
            'tahun' => $bulanDepan->year,
            'bulan' => $bulanDepan->month,
            'status' => 'Pending',
        ]);

        // Pengajuan duplikat
        $response = $this->actingAs($user)->post(route('user.simpanan.sukarela.store'), [
            'nilai' => 60000,
            'tahun' => $bulanDepan->year,
            'bulan' => $bulanDepan->month,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('error');
    }

    /**
     * Test: Tidak bisa mengajukan untuk periode yang sudah lewat
     */
    public function test_tidak_bisa_mengajukan_periode_lewat()
    {
        $user = User::factory()->create();
        $bulanLalu = now()->subMonth();

        $response = $this->actingAs($user)->post(route('user.simpanan.sukarela.store'), [
            'nilai' => 50000,
            'tahun' => $bulanLalu->year,
            'bulan' => $bulanLalu->month,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('error');
    }

    /**
     * Test: Guest tidak dapat mengakses halaman pengajuan
     */
    public function test_guest_tidak_dapat_mengakses_pengajuan()
    {
        $response = $this->get(route('user.simpanan.sukarela.pengajuan'));

        $response->assertRedirect('/login');
    }

    /**
     * Test: Validasi tahun harus antara 2000-2100
     */
    public function test_validasi_tahun()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('user.simpanan.sukarela.store'), [
            'nilai' => 50000,
            'tahun' => 1999, // Kurang dari 2000
            'bulan' => 12,
        ]);

        $response->assertSessionHasErrors('tahun');
    }

    /**
     * Test: Validasi bulan harus antara 1-12
     */
    public function test_validasi_bulan()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('user.simpanan.sukarela.store'), [
            'nilai' => 50000,
            'tahun' => now()->year,
            'bulan' => 13, // Lebih dari 12
        ]);

        $response->assertSessionHasErrors('bulan');
    }
}
