<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Tabungan;
use App\Models\Pengurus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tabungan>
 */
class TabunganFactory extends Factory
{
    protected $model = Tabungan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'users_id' => User::factory(),
            'nilai' => $this->faker->numberBetween(10000, 1000000),
            'debit' => $this->faker->numberBetween(0, 500000),
            'status' => $this->faker->randomElement(['pending', 'diterima', 'ditolak', 'dipotong']),
            'tanggal' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'bukti_transfer' => 'uploads/bukti_transfer/test.jpg',
            'pengurus_id' => null,
        ];
    }
}

