<?php

namespace Tests\Feature\Pengurus;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Pengurus\SimpananSukarela;
use App\Models\User\MasterSimpananSukarela;

class SimpananSukarelaTest extends TestCase
{
    use RefreshDatabase;

    protected function createPengurus($role = 'bendahara')
    {
        return Pengurus::factory()->create([
            'role' => $role,
        ]);
    }

    /**
     * Test: Pengurus dapat mengakses halaman simpanan sukarela
     */
    public function test_pengurus_dapat_mengakses_halaman_simpanan_sukarela()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.simpanan.sukarela.index'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.simpanan.sukarela.index');
    }

    /**
     * Test: Pengurus dapat melihat daftar pengajuan simpanan
     */
    public function test_pengurus_dapat_melihat_pengajuan_simpanan()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.simpanan.sukarela.pengajuan'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.simpanan.sukarela.pengajuan');
    }

    /**
     * Test: Pengurus dapat menyetujui pengajuan simpanan
     */
    public function test_pengurus_dapat_menyetujui_pengajuan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $pengajuan = MasterSimpananSukarela::create([
            'users_id' => $user->id,
            'nilai' => 50000,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.simpanan.sukarela.approve', $pengajuan->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('master_simpanan_sukarela', [
            'id' => $pengajuan->id,
            'status' => 'Disetujui',
        ]);
    }

    /**
     * Test: Pengurus dapat menolak pengajuan simpanan
     */
    public function test_pengurus_dapat_menolak_pengajuan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $pengajuan = MasterSimpananSukarela::create([
            'users_id' => $user->id,
            'nilai' => 50000,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.simpanan.sukarela.reject', $pengajuan->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('master_simpanan_sukarela', [
            'id' => $pengajuan->id,
            'status' => 'Ditolak',
        ]);
    }

    /**
     * Test: Pengurus dapat generate simpanan sukarela
     */
    public function test_pengurus_dapat_generate_simpanan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        // Buat master simpanan yang sudah disetujui
        MasterSimpananSukarela::create([
            'users_id' => $user->id,
            'nilai' => 75000,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Disetujui',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.simpanan.sukarela.generate'), [
                'bulan' => now()->month,
                'tahun' => now()->year,
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        // Cek simpanan sukarela terbentuk
        $this->assertDatabaseHas('simpanan_sukarela', [
            'users_id' => $user->id,
            'bulan' => now()->month,
            'tahun' => now()->year,
            'nilai' => 75000,
            'status' => 'Diajukan',
        ]);
    }

    /**
     * Test: Pengurus dapat melihat riwayat simpanan
     */
    public function test_pengurus_dapat_melihat_riwayat()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.simpanan.sukarela.riwayat'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.simpanan.sukarela.riwayat');
    }

    /**
     * Test: Validasi generate memerlukan bulan dan tahun
     */
    public function test_validasi_generate_simpanan()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.simpanan.sukarela.generate'), [
                // bulan dan tahun kosong
            ]);

        $response->assertSessionHasErrors(['bulan', 'tahun']);
    }
}
