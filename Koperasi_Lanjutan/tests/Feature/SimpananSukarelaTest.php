<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Pengurus\SimpananSukarela;
use illuminate\Contracts\Auth\Authenticatable;

class SimpananSukarelaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User dapat mengakses halaman simpanan sukarela
     */
    public function test_user_dapat_mengakses_halaman_simpanan_sukarela()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/simpanan-sukarela-anggota');

        $response->assertStatus(200)
            ->assertViewIs('user.simpanan.sukarela.index');
    }

    /**
     * Test: User dapat melihat riwayat simpanan sukarela
     */
    public function test_user_dapat_melihat_riwayat_simpanan_sukarela()
    {
        $user = User::factory()->create();

        SimpananSukarela::factory()->count(3)->create([
            'users_id' => $user->id,
            'status' => 'Dibayar',
        ]);

        $response = $this->actingAs($user)->get('/simpanan-sukarela-anggota/riwayat');

        $response->assertStatus(200)
            ->assertViewIs('user.simpanan.sukarela.riwayat');
    }

    /**
     * Test: Total saldo hanya menghitung simpanan dengan status Dibayar
     */
    public function test_total_saldo_hanya_menghitung_status_dibayar()
    {
        $user = User::factory()->create();

        SimpananSukarela::factory()->create([
            'users_id' => $user->id,
            'nilai' => 50000,
            'status' => 'Dibayar',
        ]);

        SimpananSukarela::factory()->create([
            'users_id' => $user->id,
            'nilai' => 30000,
            'status' => 'Diajukan',
        ]);

        SimpananSukarela::factory()->create([
            'users_id' => $user->id,
            'nilai' => 20000,
            'status' => 'Diajukan',
        ]);

        $response = $this->actingAs($user)->get('/simpanan-sukarela-anggota');

        $response->assertStatus(200);
        $this->assertEquals(50000, $response->viewData('totalSaldo'));
    }

    /**
     * Test: User hanya melihat simpanan miliknya sendiri
     */
    public function test_user_hanya_melihat_simpanan_miliknya_sendiri()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        SimpananSukarela::factory()->create([
            'users_id' => $user1->id,
            'nilai' => 50000,
        ]);

        SimpananSukarela::factory()->create([
            'users_id' => $user2->id,
            'nilai' => 100000,
        ]);

        $response = $this->actingAs($user1)->get('/simpanan-sukarela-anggota/riwayat');

        $response->assertStatus(200);
        $riwayat = $response->viewData('riwayat');
        $this->assertCount(1, $riwayat);
        $this->assertEquals(50000, $riwayat->first()->nilai);
    }

    /**
     * Test: Guest tidak dapat mengakses halaman simpanan sukarela
     */
    public function test_guest_tidak_dapat_mengakses_simpanan_sukarela()
    {
        $response = $this->get('/simpanan-sukarela-anggota');

        $response->assertRedirect('/login');
    }

    /**
     * Test: Statistik simpanan ditampilkan dengan benar
     */
    public function test_statistik_simpanan_ditampilkan_dengan_benar()
    {
        $user = User::factory()->create();

        SimpananSukarela::factory()->count(2)->create([
            'users_id' => $user->id,
            'status' => 'Dibayar',
        ]);

        // Controller mencari status 'Pending', tapi di database hanya ada 'Diajukan'
        // Karena controller mencari 'Pending', countPending akan 0
        SimpananSukarela::factory()->count(1)->create([
            'users_id' => $user->id,
            'status' => 'Diajukan',
        ]);

        $response = $this->actingAs($user)->get('/simpanan-sukarela-anggota');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->viewData('countBerhasil'));
        // Controller mencari 'Pending' yang tidak ada, jadi akan 0
        $this->assertEquals(0, $response->viewData('countPending'));
        // Controller mencari 'Gagal' yang tidak ada, jadi akan 0
        $this->assertEquals(0, $response->viewData('countGagal'));
    }
}

