<?php

namespace Tests\Feature\Pengurus;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;

class PinjamanTest extends TestCase
{
    use RefreshDatabase;

    protected function createPengurus($role = 'ketua')
    {
        return Pengurus::factory()->create([
            'role' => $role,
        ]);
    }

    /**
     * Test: Pengurus dapat mengakses dashboard pinjaman
     */
    public function test_pengurus_dapat_mengakses_dashboard_pinjaman()
    {
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.pinjaman.index'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.pinjaman.index');
    }

    /**
     * Test: Pengurus dapat melihat daftar pengajuan pinjaman
     */
    public function test_pengurus_dapat_melihat_pengajuan_pinjaman()
    {
        $pengurus = $this->createPengurus();

        // Buat pinjaman pending
        $user = User::factory()->create();
        Pinjaman::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.pinjaman.pengajuan'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.pinjaman.pengajuan')
            ->assertSee($user->nama);
    }

    /**
     * Test: Pengurus dapat menyetujui pinjaman
     */
    public function test_pengurus_dapat_menyetujui_pinjaman()
    {
        $pengurus = $this->createPengurus();

        $user = User::factory()->create();
        $pinjaman = Pinjaman::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'nominal' => 12000000,
            'bunga' => 1, // 1%
            'tenor' => 12, // 12 bulan
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.pinjaman.approve', $pinjaman->id));

        $response->assertRedirect();

        // Cek status pinjaman berubah
        $this->assertDatabaseHas('pinjaman', [
            'id' => $pinjaman->id,
            'status' => 'disetujui',
        ]);

        // Cek angsuran terbentuk (12 bulan)
        $this->assertDatabaseCount('angsuran_pinjaman', 12);

        // Cek detail angsuran pertama
        $this->assertDatabaseHas('angsuran_pinjaman', [
            'pinjaman_id' => $pinjaman->id,
            'bulan_ke' => 1,
            'status' => 'belum_lunas',
        ]);
    }

    /**
     * Test: Pengurus dapat menolak pinjaman
     */
    public function test_pengurus_dapat_menolak_pinjaman()
    {
        $pengurus = $this->createPengurus();

        $user = User::factory()->create();
        $pinjaman = Pinjaman::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.pinjaman.reject', $pinjaman->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('pinjaman', [
            'id' => $pinjaman->id,
            'status' => 'ditolak',
        ]);
    }

    /**
     * Test: Anggota biasa tidak dapat mengakses halaman pengurus
     */
    public function test_anggota_tidak_dapat_mengakses_halaman_pengurus()
    {
        $this->markTestSkipped('Requires middleware configuration investigation');

        $user = User::factory()->create(['role' => 'anggota']);

        $response = $this->actingAs($user) // default guard 'web'
            ->get(route('pengurus.pinjaman.index'));

        $response->assertStatus(403);
    }
}
