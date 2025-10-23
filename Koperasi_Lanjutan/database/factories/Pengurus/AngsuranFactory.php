<?php

namespace Database\Factories\Pengurus;

use App\Models\Pengurus\Angsuran;
use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AngsuranFactory extends Factory
{
    protected $model = Angsuran::class;

    public function definition(): array
    {
        return [
            'pinjaman_id' => Pinjaman::factory(), // otomatis buat pinjaman baru jika belum ada
            'petugas_id' => User::factory(), // petugas random
            'bulan_ke' => $this->faker->numberBetween(1, 12),
            'jumlah_bayar' => $this->faker->numberBetween(100000, 2000000),
            'tanggal_bayar' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'status' => $this->faker->randomElement(['belum_lunas','lunas']),
            'jenis_pembayaran' => $this->faker->randomElement(['angsuran', 'pelunasan']),

        ];
    }
}
