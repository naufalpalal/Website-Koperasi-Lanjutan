<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\MasterSimpananSukarela;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\MasterSimpananSukarela>
 */
class MasterSimpananSukarelaFactory extends Factory
{
    protected $model = MasterSimpananSukarela::class;

    public function definition(): array
    {
        return [
            'users_id' => User::factory(), // otomatis buat user baru
            'nilai' => $this->faker->numberBetween(100000, 1000000),
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => $this->faker->randomElement(['Pending', 'Disetujui', 'Ditolak']),
        ];
    }
}
