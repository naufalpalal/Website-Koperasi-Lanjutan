<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call([AdminSeeder::class]);
        // User::factory(10)->create();

        User::create([
            'nama' => 'Admin Utama',
            'nip' => '1234567890',
            'no_telepon' => '081234567890',
            'password' => Hash::make('admin123'),
            'role' => 'pengurus',
            'status' => 'aktif',
        ]);
    }
}
