<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'no_telepon' => $this->faker->numerify('08##########'),
            'password' => Hash::make('password'),
            'nip' => $this->faker->numerify('19##########'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'alamat_rumah' => $this->faker->address(),
            'unit_kerja' => $this->faker->randomElement(['Keuangan', 'HRD', 'IT', 'Produksi']),
            'role' => $this->faker->randomElement(['pengurus', 'anggota']),
            'status' => $this->faker->randomElement(['pending', 'aktif', 'ditolak']),
        ];
    }

    /**
     * State khusus untuk role anggota.
     */
    public function anggota(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'anggota',
            'status' => 'aktif',
        ]);
    }

    /**
     * State khusus untuk role pengurus.
     */
    public function pengurus(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengurus',
            'status' => 'aktif',
        ]);
    }
}
