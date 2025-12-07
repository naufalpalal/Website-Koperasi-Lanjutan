<?php

namespace Tests\Feature\Pengurus;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\MasterSimpananPokok;

class KelolaAnggotaTest extends TestCase
{
    use RefreshDatabase;

    protected function createPengurus($role = 'sekretaris')
    {
        return Pengurus::factory()->create([
            'role' => $role,
        ]);
    }

    /**
     * Test: Pengurus dapat mengakses halaman kelola anggota
     */
    public function test_pengurus_dapat_mengakses_halaman_anggota()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.anggota.index'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.KelolaAnggota.index');
    }

    /**
     * Test: Pengurus dapat mengakses halaman verifikasi anggota
     */
    public function test_pengurus_dapat_mengakses_halaman_verifikasi()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.anggota.verifikasi'));

        $response->assertStatus(200);
    }

    /**
     * Test: Pengurus dapat menyetujui anggota pending
     */
    public function test_pengurus_dapat_menyetujui_anggota()
    {
        $pengurus = $this->createPengurus();

        $user = User::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.anggota.approve', $user->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'aktif',
        ]);
    }

    /**
     * Test: Pengurus dapat menolak anggota pending
     */
    public function test_pengurus_dapat_menolak_anggota()
    {
        $pengurus = $this->createPengurus();

        $user = User::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.anggota.reject', $user->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'ditolak',
        ]);
    }

    /**
     * Test: Pengurus dapat mengakses form tambah anggota
     */
    public function test_pengurus_dapat_mengakses_form_tambah_anggota()
    {
        $pengurus = $this->createPengurus();

        MasterSimpananPokok::create([
            'nilai' => 100000,
            'tahun' => date('Y'),
            'bulan' => date('n'),
            'pengurus_id' => $pengurus->id,
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.anggota.create'));

        $response->assertStatus(200);
    }

    /**
     * Test: Pengurus dapat mengakses form edit anggota
     */
    public function test_pengurus_dapat_mengakses_form_edit_anggota()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.anggota.edit', $user->id));

        $response->assertStatus(200);
    }

    /**
     * Test: Pengurus dapat melihat daftar anggota tidak aktif
     */
    public function test_pengurus_dapat_melihat_anggota_tidak_aktif()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.anggota.nonaktif'));

        $response->assertStatus(200);
    }

    /**
     * Test: Pengurus dapat toggle status anggota
     */
    public function test_pengurus_dapat_toggle_status_anggota()
    {
        $pengurus = $this->createPengurus();

        $user = User::factory()->create([
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.anggota.toggleStatus', $user->id), [
                'status' => 'tidak aktif',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'tidak aktif',
        ]);
    }
}
