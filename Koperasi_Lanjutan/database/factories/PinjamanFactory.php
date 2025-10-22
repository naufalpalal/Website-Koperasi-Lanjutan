<?php

namespace Database\Factories;

use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pinjaman>
 */
class PinjamanFactory extends Factory
{
    protected $model = Pinjaman::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // otomatis bikin user dummy jika belum ada
            'nominal' => $this->faker->numberBetween(1_000_000, 100_000_000),
            'status' => $this->faker->randomElement(['pending', 'disetujui', 'ditolak']),
            'dokumen_pinjaman' => $this->faker->filePath(), // bisa diganti dengan nama file dummy juga
            'bunga' => $this->faker->randomFloat(2, 1, 15), // contoh: 5.25%
            'tenor' => $this->faker->randomElement([3, 6, 12, 24, 36]), // bulan
            'angsuran' => $this->faker->numberBetween(500_000, 10_000_000),
        ];
    }
}
