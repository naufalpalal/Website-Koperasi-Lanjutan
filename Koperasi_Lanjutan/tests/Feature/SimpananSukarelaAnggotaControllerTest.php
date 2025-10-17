<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * -----------------------------------------------------
 * TEST: Akses Halaman Simpanan Sukarela Anggota
 * -----------------------------------------------------
 */

it('anggota dapat mengakses halaman index simpanan', function () {
    // Arrange
    $anggota = User::factory()->create(['role' => 'anggota']);

    // Act
    $response = $this->actingAs($anggota)->get('/simpanan-sukarela-anggota');

    // Assert
    $response->assertStatus(200)
             ->assertViewIs('user.simpanan.sukarela.index');
});

it('anggota dapat mengakses halaman riwayat simpanan', function () {
    // Arrange
    $anggota = User::factory()->create(['role' => 'anggota']);

    // Act
    $response = $this->actingAs($anggota)->get('/simpanan-sukarela-anggota/riwayat');

    // Assert
    $response->assertStatus(200)
             ->assertViewIs('user.simpanan.sukarela.riwayat');
});

it('guest (tanpa login) akan diarahkan ke halaman login', function () {
    // Act
    $response = $this->get('/simpanan-sukarela-anggota');

    // Assert
    $response->assertRedirect('/login');
});


