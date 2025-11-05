<?php

namespace Database\Seeders;

use App\Models\Pengurus\Angsuran;
use App\Models\Pinjaman;
use App\Models\User;

use App\Models\Pengurus;
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

        // User::create([
        //     'nama' => 'Admin Utama',
        //     'nip' => '1234567891',
        //     'no_telepon' => '081234567891',
        //     'password' => Hash::make('admin123'),
        //     'role' => 'pengurus',
        //     'status' => 'aktif',
        // ]);

        User::create([
            'nama' => 'Admin Utama',
            'nip' => '1234567891',
            'no_telepon' => '081234867891',
            'password' => Hash::make('admin123'),
            'status' => 'aktif',
        ]);
        Pengurus::insert([
            [
                'nama' => 'Super Admin',
                'no_telepon' => '081111111111',
                'password' => Hash::make('password123'),
                'nip' => '10001',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1980-01-01',
                'alamat_rumah' => 'Jl. Merdeka No. 1 Jakarta',
                'unit_kerja' => 'Manajemen',
                'sk_perjanjian_kerja' => 'SK-001',
                'photo_path' => null,
                'role' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ketua Koperasi',
                'no_telepon' => '082222222222',
                'password' => Hash::make('password123'),
                'nip' => '10002',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1985-05-12',
                'alamat_rumah' => 'Jl. Sudirman No. 10 Bandung',
                'unit_kerja' => 'Manajemen',
                'sk_perjanjian_kerja' => 'SK-002',
                'photo_path' => null,
                'role' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bendahara Koperasi',
                'no_telepon' => '083333333333',
                'password' => Hash::make('password123'),
                'nip' => '10003',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1990-03-22',
                'alamat_rumah' => 'Jl. Gajah Mada No. 12 Surabaya',
                'unit_kerja' => 'Keuangan',
                'sk_perjanjian_kerja' => 'SK-003',
                'photo_path' => null,
                'role' => 'bendahara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sekretaris Koperasi',
                'no_telepon' => '084444444444',
                'password' => Hash::make('password123'),
                'nip' => '10004',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1992-07-15',
                'alamat_rumah' => 'Jl. Malioboro No. 5 Yogyakarta',
                'unit_kerja' => 'Administrasi',
                'sk_perjanjian_kerja' => 'SK-004',
                'photo_path' => null,
                'role' => 'sekretaris',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        // User::create([
        //     'nama' => 'User Biasa',
        //     'nip' => '0987654321',
        //     'no_telepon' => '12345678901',
        //     'password' => Hash::make('user123'),
        //     'role' => 'anggota',
        //     'status' => 'aktif',
        // ]);

        // User::create([
        //     'nama' => 'Admin Kedua',
        //     'nip' => '1234567892',
        //     'no_telepon' => '081234567892',
        //     'password' => Hash::make('admin123'),
        //     'role' => 'pengurus',
        //     'status' => 'aktif',
        // ]);



        // User::factory(10)->create(
        //     [
        //         'role'=>'anggota'
        //     ]
        // );
    }
}
