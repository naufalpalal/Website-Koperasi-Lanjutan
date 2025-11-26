<?php

namespace Database\Factories\Pengurus;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Pengurus\SimpananSukarela;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengurus\SimpananSukarela>
 */
class SimpananSukarelaFactory extends Factory
{
    protected $model = SimpananSukarela::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'users_id' => User::factory(),
            'nilai' => $this->faker->numberBetween(10000, 100000),
            'tahun' => now()->year,
            'bulan' => now()->month,
            'status' => $this->faker->randomElement(['Diajukan', 'Dibayar']),
        ];
    }
}

