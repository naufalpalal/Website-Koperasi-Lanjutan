<?php

use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User\MasterSimpananSukarela;

uses(RefreshDatabase::class);

it('pengurus dapat menyetujui pengajuan simpanan sukarela', function () {
    // Arrange
    $pengurus = Pengurus::factory()->create(['role' => 'bendahara']);

    $pengajuan = MasterSimpananSukarela::factory()->create([
        'status' => 'Pending',
    ]);

    // Act
    $response = $this->actingAs($pengurus, 'pengurus')
                     ->post("/pengurus/simpanan-sukarela/approve/{$pengajuan->id}");

    // Assert
    $response->assertStatus(302);
    $this->assertDatabaseHas('master_simpanan_sukarela', [
        'id' => $pengajuan->id,
        'status' => 'Disetujui',
    ]);
});

it('anggota tidak dapat mengakses halaman pengurus', function () {
    // Buat akun dengan role anggota
    $anggota = User::factory()->create([
        'role' => 'anggota',
    ]);

    // Login sebagai anggota dan coba akses halaman pengurus
    $response = $this->actingAs($anggota)->get('/simpanan-sukarela');

    // Pastikan diarahkan (403 Forbidden)
    $response->assertStatus(403);
});

it('guest (belum login) akan diarahkan ke halaman login', function () {
    // Akses tanpa login
    $response = $this->get('/simpanan-sukarela');

    // Pastikan diarahkan ke login
    $response->assertRedirect('/login');
});


it('pengurus dapat mengenerate simpanan sukarela', function () {
    // Buat akun pengurus
    $pengurus = Pengurus::factory()->create(['role' => 'bendahara']);

    // Login dan kirim request POST dengan data bulan dan tahun
    $response = $this->actingAs($pengurus)->post('/simpanan-sukarela/generate', [
        'bulan' => 10,
        'tahun' => 2025,
    ]);

    // Pastikan redirect (302) dengan pesan sukses di session
    $response->assertStatus(302);
    $response->assertSessionHas('success');
});

it('pengurus dapat menceklist dan menyimpan data simpanan sukarela', function () {
    $pengurus = Pengurus::factory()->create(['role' => 'bendahara']);

    // Simulasi data simpanan yang akan di-checklist dan disimpan
    $data = [
        [
            'user_id' => 1,
            'jumlah' => 50000,
            'checked' => true,
        ],
        [
            'user_id' => 2,
            'jumlah' => 75000,
            'checked' => false,
        ],
    ];

    // Login sebagai pengurus dan kirim data simpanan
    $response = $this->actingAs($pengurus)->post('/simpanan-sukarela/edit', [
        'simpanan' => $data,
        'bulan' => 10,
        'tahun' => 2025,
    ]);

    // Pastikan redirect dan pesan sukses
    $response->assertStatus(302);
    $response->assertSessionHas('success');
});