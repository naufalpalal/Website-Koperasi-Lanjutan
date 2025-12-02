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
        // Gunakan updateOrInsert untuk menghindari duplikasi
        // Superadmin Example
        DB::table('pengurus')->updateOrInsert(
            ['no_telepon' => '081234567890'], // Unique key untuk mencari
            [
                'nama' => 'Admin Utama',
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
            ]
        );

        // Ketua Example
        DB::table('pengurus')->updateOrInsert(
            ['no_telepon' => '081122334455'],
            [
                'nama' => 'Budi Santoso',
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
            ]
        );

        // Bendahara Example
        DB::table('pengurus')->updateOrInsert(
            ['no_telepon' => '085566778899'],
            [
                'nama' => 'Siti Aminah',
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
            ]
        );

        // Sekretaris Example
        DB::table('pengurus')->updateOrInsert(
            ['no_telepon' => '087766554433'],
            [
                'nama' => 'Joko Susilo',
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
            ]
        );

        // Gunakan updateOrInsert untuk menghindari duplikasi
        DB::table('users')->updateOrInsert(
            ['nip' => '1234567891'], // Unique key untuk mencari
            [
                'nama' => 'nopal',
                'no_telepon' => '082136443134',
                'password' => Hash::make('anggota123'),
                'tempat_lahir' => 'Genteng',
                'tanggal_lahir' => Carbon::parse('1988-07-25'),
                'alamat_rumah' => 'Jl. Malioboro No. 3, Genteng',
                'unit_kerja' => 'Karyawan',
                'sk_perjanjian_kerja' => 'SK-SR-004',
                'status' => 'aktif',
                'photo_path' => null, // pastikan ENUM atau VARCHAR di DB mendukung ini
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Seed Pinjaman Settings (10 & 20 bulan)
        DB::table('pinjaman_settings')->updateOrInsert(
            ['tenor' => 10],
            ['bunga' => 1.5, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('pinjaman_settings')->updateOrInsert(
            ['tenor' => 20],
            ['bunga' => 2.0, 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
