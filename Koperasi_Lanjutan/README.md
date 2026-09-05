# Dokumentasi Proyek Koperasi Lanjutan

Aplikasi web untuk pengelolaan koperasi yang berfokus pada transaksi anggota, simpanan, tabungan, pinjaman, validasi pengurus, dan laporan keuangan.

## 1. Tentang Proyek

Proyek ini dibangun dengan Laravel 12 dan dirancang untuk membantu koperasi mengelola kegiatan administrasi secara digital. Fungsionalitas utama mencakup:

- autentikasi anggota dan pengurus
- simpanan wajib dan sukarela
- tabungan anggota
- pengajuan pinjaman dan angsuran
- approval serta verifikasi dokumen oleh pengurus
- laporan dan export data

## 2. Tujuan

- meningkatkan akurasi data koperasi
- mempercepat proses transaksi keuangan
- memudahkan monitoring keuangan anggota dan koperasi
- menyiapkan sistem dengan role-based access untuk pengurus dan anggota

## 3. Teknologi

- PHP 8.2+
- Laravel 12
- MySQL
- Tailwind CSS
- Vite + Alpine.js
- DOMPDF
- Maatwebsite Excel
- Pest / PHPUnit

## 4. Fitur Utama

### Anggota
- dashboard pribadi
- riwayat simpanan wajib dan sukarela
- tabungan anggota
- pengajuan pinjaman dan angsuran
- upload dokumen verifikasi
- kelola profil dan password

### Pengurus
- dashboard koperasi
- pengelolaan anggota
- validasi simpanan
- approval pengajuan pinjaman
- pengaturan nominal dan tenor
- export laporan bulanan/tahunan

## 5. Struktur Proyek

```text
Koperasi_Lanjutan/
├── app/
├── config/
├── database/
├── docs/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── README.md
├── vite.config.js
└── tailwind.config.js
```

## 6. Setup Awal

```bash
git clone https://github.com/naufalpalal/Website-Koperasi-Lanjutan.git
cd Website-Koperasi-Lanjutan/Koperasi_Lanjutan
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Konfigurasi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi
DB_USERNAME=root
DB_PASSWORD=
```

## 7. Migrasi dan Seeder

```bash
php artisan migrate --seed
```

## 8. Menjalankan Aplikasi

### Development

```bash
npm run dev
php artisan serve
```

Akses aplikasi di:

```text
http://localhost:8000
```

### Production build

```bash
npm run build
```

## 9. Testing

```bash
php artisan test
```

## 10. Dokumentasi Lengkap

Dokumentasi detail proyek tersedia di:

- [docs/DOKUMENTASI_PROYEK.md](docs/DOKUMENTASI_PROYEK.md)

## 11. Catatan Penting

- Pastikan PHP minimal versi 8.2
- Gunakan database MySQL yang siap dan memiliki izin akses
- Simpan file dokumen dan bukti transaksi di folder yang aman dan dapat diakses aplikasi
- Selalu jalankan testing sebelum melakukan merge ke branch utama

## 12. Lisensi

Project ini menggunakan lisensi MIT sesuai dengan Laravel project default.
