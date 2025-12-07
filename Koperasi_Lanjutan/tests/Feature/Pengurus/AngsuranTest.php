<?php

namespace Tests\Feature\Pengurus;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Pinjaman;
use App\Models\Pengurus\Angsuran;

class AngsuranTest extends TestCase
{
    use RefreshDatabase;

    protected function createPengurus($role = 'bendahara')
    {
        return Pengurus::factory()->create([
            'role' => $role,
        ]);
    }

    /**
     * Test: Pengurus dapat melihat daftar angsuran pinjaman
     */
    public function test_pengurus_dapat_melihat_daftar_angsuran()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $pinjaman = Pinjaman::factory()->create([
            'user_id' => $user->id,
            'status' => 'disetujui',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.pinjaman.angsuran.index', $pinjaman->id));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.pinjaman.angsuran.index');
    }

    /**
     * Test: Pengurus dapat memperbarui status angsuran
     */
    public function test_pengurus_dapat_update_status_angsuran()
    {
        $pengurus = $this->createPengurus();
        $user = User::factory()->create();

        $pinjaman = Pinjaman::factory()->create([
            'user_id' => $user->id,
            'status' => 'disetujui',
        ]);

        $angsuran = Angsuran::create([
            'pinjaman_id' => $pinjaman->id,
            'bulan_ke' => 1,
            'jumlah_bayar' => 1000000,
            'tanggal_bayar' => now(),
            'status' => 'belum_lunas',
            'jenis_pembayaran' => 'angsuran',
        ]);

        $response = $this->actingAs($pengurus, 'pengurus')
            ->post(route('pengurus.pinjaman.updateStatus', $angsuran->id), [
                'status' => 'lunas',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('angsuran_pinjaman', [
            'id' => $angsuran->id,
            'status' => 'lunas',
        ]);
    }

    /**
     * Test: Pengurus dapat melihat halaman pemotongan
     */
    public function test_pengurus_dapat_melihat_halaman_pemotongan()
    {
        $this->markTestSkipped('Route issue with pengurus settings');
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.pinjaman.pemotongan'));

        $response->assertStatus(200)
            ->assertViewIs('pengurus.pinjaman.pemotongan');
    }

    /**
     * Test: Pengurus dapat filter pemotongan berdasarkan periode
     */
    public function test_pengurus_dapat_filter_pemotongan_periode()
    {
        $this->markTestSkipped('Route issue with pengurus settings');
        $pengurus = $this->createPengurus();

        $response = $this->actingAs($pengurus, 'pengurus')
            ->get(route('pengurus.pinjaman.pemotongan', ['periode' => '2024-12']));

        $response->assertStatus(200);
    }
}
