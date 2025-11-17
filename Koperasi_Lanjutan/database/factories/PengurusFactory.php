<?php

namespace Database\Factories;

use App\Models\Pengurus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengurus>
 */
class PengurusFactory extends Factory
{
    /**
     * Nama model yang sesuai dengan factory ini.
     *
     * @var string
     */
    protected $model = Pengurus::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar peran (roles) yang mungkin
        $roles = ['ketua', 'bendahara', 'sekretaris', 'superadmin'];

        return [
            'nama' => $this->faker->name(),
            'no_telepon' => $this->faker->phoneNumber(),
            'password' => bcrypt('password'), // Atur password default, misal: 'password'
            'nip' => $this->faker->unique()->numerify('##########'), // 10 digit NIP unik
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'), // Tanggal lahir sebelum 2000
            'alamat_rumah' => $this->faker->address(),
            'unit_kerja' => $this->faker->company(),
            'sk_perjanjian_kerja' => 'SK-' . $this->faker->unique()->randomNumber(5),
            'photo_path' => null, // Biarkan null atau tambahkan path placeholder jika perlu
            'role' => $this->faker->randomElement($roles), // Pilih salah satu peran secara acak
        ];
    }

    /**
     * Tunjukkan bahwa pengurus memiliki peran 'ketua'.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function ketua()
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'ketua',
        ]);
    }
    
    /**
     * Tunjukkan bahwa pengurus memiliki peran 'superadmin'.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function superadmin()
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'superadmin',
        ]);
    }
}