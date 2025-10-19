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
            'nip' => '1234567891',
            'no_telepon' => '081234567891',
            'password' => Hash::make('admin123'),
            'role' => 'pengurus',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'User Biasa',
            'nip' => '0987654321',
            'no_telepon' => '12345678901',
            'password' => Hash::make('user123'),
            'role' => 'anggota',
            'status' => 'aktif',
        ]);

        User::create([
            'nama' => 'Admin Kedua',
            'nip' => '1234567892',
            'no_telepon' => '081234567892',
            'password' => Hash::make('admin123'),
            'role' => 'pengurus',
            'status' => 'aktif',
        ]);

        
    }
}
