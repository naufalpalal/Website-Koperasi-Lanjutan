<?php

namespace Database\Seeders;

use App\Models\Pengurus\Angsuran;
use App\Models\Pinjaman;
use App\Models\User;

use App\Models\Pengurus;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run()
    {
        DB::table('pengurus')->insert([
            // Superadmin Example
            [
                'nama' => 'Admin Utama',
                'no_telepon' => '081234567890',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat!
                'nip' => '199001012020011001',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => Carbon::parse('1990-01-01'),
                'alamat_rumah' => 'Jl. Merdeka No. 10, Jakarta Pusat',
                'unit_kerja' => 'Divisi Teknologi Informasi',
                'sk_perjanjian_kerja' => 'SK-SA-001',
                'photo_path' => null,
                'role' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Ketua Example
            [
                'nama' => 'Budi Santoso',
                'no_telepon' => '081122334455',
                'password' => Hash::make('ketua123'),
                'nip' => '198505152010052002',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => Carbon::parse('1985-05-15'),
                'alamat_rumah' => 'Jl. Pelajar Pejuang 45 No. 5, Bandung',
                'unit_kerja' => 'Direktorat Utama',
                'sk_perjanjian_kerja' => 'SK-KT-002',
                'photo_path' => null,
                'role' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Bendahara Example
            [
                'nama' => 'Siti Aminah',
                'no_telepon' => '085566778899',
                'password' => Hash::make('bendahara123'),
                'nip' => '199210202018092003',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => Carbon::parse('1992-10-20'),
                'alamat_rumah' => 'Jl. Pahlawan No. 7, Surabaya',
                'unit_kerja' => 'Departemen Keuangan',
                'sk_perjanjian_kerja' => 'SK-BH-003',
                'photo_path' => null,
                'role' => 'bendahara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Sekretaris Example
            [
                'nama' => 'Joko Susilo',
                'no_telepon' => '087766554433',
                'password' => Hash::make('sekretaris123'),
                'nip' => '198807252015071004',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => Carbon::parse('1988-07-25'),
                'alamat_rumah' => 'Jl. Malioboro No. 3, Yogyakarta',
                'unit_kerja' => 'Sekretariat Umum',
                'sk_perjanjian_kerja' => 'SK-SR-004',
                'photo_path' => null,
                'role' => 'sekretaris',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // [

            //     'nama' => 'nopal',
            //     'no_telepon' => '082136443134',
            //     'password' => Hash::make('anggota123'),
            //     'nip' => '1234567891',
            //     'tempat_lahir' => 'Genteng',
            //     'tanggal_lahir' => Carbon::parse('1988-07-25'),
            //     'alamat_rumah' => 'Jl. Malioboro No. 3, Genteng',
            //     'unit_kerja' => 'Karyawan',
            //     'sk_perjanjian_kerja' => 'SK-SR-004',
            //     'photo_path' => null,
            //     'role' => 'anggota',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ]);

        DB::table('users')->insert([
    'nama' => 'nopal',
    'no_telepon' => '082136443134',
    'password' => Hash::make('anggota123'),
    'nip' => '1234567891',
    'tempat_lahir' => 'Genteng',
    'tanggal_lahir' => Carbon::parse('1988-07-25'),
    'alamat_rumah' => 'Jl. Malioboro No. 3, Genteng',
    'unit_kerja' => 'Karyawan',
    'sk_perjanjian_kerja' => 'SK-SR-004',
    'status' => 'aktif',
    'photo_path' => null, // pastikan ENUM atau VARCHAR di DB mendukung ini
    'created_at' => now(),
    'updated_at' => now(),
]);
    }

  
}
