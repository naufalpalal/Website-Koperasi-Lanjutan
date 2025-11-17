<?php

use App\Models\Pengurus;
use App\Models\User;
use App\Models\User\MasterSimpananSukarela;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('pengurus dapat menyetujui pengajuan simpanan sukarela', function () {
    // Arrange
    $pengurus = Pengurus::factory()->create(['role' => 'bendahara']);

    // Buat data pengajuan simpanan sukarela
    $pengajuan = MasterSimpananSukarela::factory()->create([
        'status' => 'Pending',
    ]);

    // Act
    $response = $this->actingAs($pengurus)
                     ->post("/simpanan-sukarela/approve/{$pengajuan->id}");

    // Assert
    $response->assertStatus(302); // redirect setelah approve
    $this->assertDatabaseHas('master_simpanan_sukarela', [
        'id' => $pengajuan->id,
        'status' => 'Disetujui',
    ]);
});

it('pengurus dapat menolak pengajuan simpanan sukarela', function () {
    // Arrange
    $pengurus = Pengurus::factory()->create(['role' => 'bendahara']);

    // Buat data pengajuan simpanan sukarela
    $pengajuan = MasterSimpananSukarela::factory()->create([
        'status' => 'Pending',
    ]);

    // Act
    $response = $this->actingAs($pengurus)
                     ->post("/simpanan-sukarela/reject/{$pengajuan->id}");

    // Assert
    $response->assertStatus(302); // redirect setelah reject
    $this->assertDatabaseHas('master_simpanan_sukarela', [
        'id' => $pengajuan->id,
        'status' => 'Ditolak',
    ]);
});


it('anggota dapat mengajukan perubahan simpanan sukarela', function () {
    // Arrange
    $anggota = User::factory()->create(['role' => 'anggota']);
    $payload = [
        'nilai' => 15000,
        'tahun' => now()->year,
        'bulan' => now()->month,
    ];


    $response = $this->actingAs($anggota)
        ->post('/simpanan-sukarela-anggota/ajukan', $payload);


    $response->assertStatus(302); // redirect setelah pengajuan
    $response->assertSessionHas('success');
});
