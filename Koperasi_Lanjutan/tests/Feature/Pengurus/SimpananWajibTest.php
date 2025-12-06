<?php

namespace Tests\Feature\Pengurus;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Pengurus\SimpananWajib;
use App\Models\Pengurus\MasterSimpananWajib;

class SimpananWajibTest extends TestCase
{
    use RefreshDatabase;

    protected function createPengurus($role = 'bendahara')
    {
        return Pengurus::factory()->create([
            'role' => $role,
        ]);
    }

    /**
     * Test: Pengurus dapat generate simpanan wajib
     */
    public function test_pengurus_dapat_generate_simpanan_wajib()
    {
        $this->markTestSkipped('Requires model investigation');

        $pengurus = $this->createPengurus();
        $user = User::factory()->create(['status' => 'aktif']);

        // Buat master simpanan wajib
        MasterSimpananWajib::create([
            'nilai' => 50000,
            'tahun' => now()->year,
            'bulan' => now()->month,
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.simpanan.wajib_2.generate'), [
                'bulan' => now()->format('Y-m'),
            ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        // Cek simpanan wajib terbentuk
        $this->assertDatabaseHas('simpanan_wajib', [
            'users_id' => $user->id,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Diajukan',
        ]);
    }

    /**
     * Test: Validasi generate memerlukan bulan
     */
    public function test_validasi_generate_simpanan()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.simpanan.wajib_2.generate'), [
                // bulan kosong
            ]);

        $response->assertSessionHasErrors(['bulan']);
    }

    /**
     * Test: Pengurus dapat update status simpanan
     */
    public function test_pengurus_dapat_update_status_simpanan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create(['status' => 'aktif']);

        SimpananWajib::create([
            'users_id' => $user->id,
            'pengurus_id' => $pengurus->id,
            'nilai' => 50000,
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => 'Diajukan',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.simpanan.wajib_2.updateStatus'), [
                'bulan' => now()->format('Y-m'),
                'anggota' => [$user->id],
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('simpanan_wajib', [
            'users_id' => $user->id,
            'status' => 'Dibayar',
        ]);
    }
}
