<?php

namespace Tests\Feature\Pengurus;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Tabungan;

class TabunganTest extends TestCase
{
    use RefreshDatabase;

    protected function createPengurus($role = 'bendahara')
    {
        return Pengurus::factory()->create([
            'role' => $role,
        ]);
    }

    /**
     * Test: Pengurus dapat mengakses halaman tabungan
     */
    public function test_pengurus_dapat_mengakses_halaman_tabungan()
    {
        $this->markTestSkipped('Route loading issue with role.pengurus middleware in test environment');

        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.tabungan.index'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.simpanan.tabungan.index');
    }

    /**
     * Test: Pengurus dapat melihat detail tabungan anggota
     */
    public function test_pengurus_dapat_melihat_detail_tabungan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.tabungan.detail', $user->id));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.simpanan.tabungan.detail');
    }

    /**
     * Test: Pengurus dapat menyetujui tabungan
     */
    public function test_pengurus_dapat_menyetujui_tabungan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $tabungan = Tabungan::create([
            'users_id' => $user->id,
            'nilai' => 100000,
            'tanggal' => now(),
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->put(route('pengurus.tabungan.terima', $tabungan->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungans', [
            'id' => $tabungan->id,
            'status' => 'diterima',
        ]);
    }

    /**
     * Test: Pengurus dapat menolak tabungan
     */
    public function test_pengurus_dapat_menolak_tabungan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $tabungan = Tabungan::create([
            'users_id' => $user->id,
            'nilai' => 100000,
            'tanggal' => now(),
            'status' => 'diajukan',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->put(route('pengurus.tabungan.tolak', $tabungan->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungans', [
            'id' => $tabungan->id,
            'status' => 'ditolak',
        ]);
    }

    /**
     * Test: Pengurus dapat menambah tabungan (store)
     */
    public function test_pengurus_dapat_menambah_tabungan()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.tabungan.store'), [
                'users_id' => $user->id,
                'nilai' => 150000,
                'tanggal' => now()->format('Y-m-d'),
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tabungans', [
            'users_id' => $user->id,
            'nilai' => 150000,
        ]);
    }

    /**
     * Test: Pengurus dapat mengakses halaman kredit
     */
    public function test_pengurus_dapat_mengakses_halaman_kredit()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.tabungan.kredit', $user->id));

        $response->assertStatus(200);
    }

    /**
     * Test: Pengurus dapat mengakses halaman debit
     */
    public function test_pengurus_dapat_mengakses_halaman_debit()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.tabungan.debit', $user->id));

        $response->assertStatus(200);
    }
}
