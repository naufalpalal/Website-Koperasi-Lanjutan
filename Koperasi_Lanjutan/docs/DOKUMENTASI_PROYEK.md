# Dokumentasi Proyek: Koperasi Lanjutan

## 1. Ringkasan Proyek

Proyek ini adalah aplikasi web berbasis Laravel yang digunakan untuk mengelola sistem koperasi, khususnya untuk proses simpanan, tabungan, pinjaman, autentikasi, dan laporan keuangan. Aplikasi ini dibangun untuk dua jenis pengguna utama:

- Anggota koperasi
- Pengurus koperasi

Sistem ini mendukung proses operasional koperasi seperti:

- pendaftaran dan login anggota
- pengelolaan simpanan wajib dan sukarela
- pengelolaan tabungan anggota
- pengajuan pinjaman dan angsuran
- verifikasi dokumen dan persetujuan pengurus
- export laporan dan data ke Excel/PDF

## 2. Tujuan Aplikasi

Aplikasi ini dibuat untuk membantu koperasi dalam:

- mempermudah pengelolaan data anggota
- menstandardisasi transaksi simpanan dan pinjaman
- meningkatkan transparansi laporan keuangan
- mengurangi proses manual dalam verifikasi dan administrasi
- menyediakan akses berbasis peran sesuai hak pengguna

## 3. Teknologi yang Digunakan

- PHP 8.2+
- Laravel 12
- MySQL
- Tailwind CSS
- Vite
- Alpine.js
- DOMPDF
- Maatwebsite Excel
- Pest / PHPUnit untuk testing

## 4. Struktur Folder Utama

```text
Koperasi_Lanjutan/
├── app/
│   ├── Exports/
│   ├── Helpers/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Mail/
│   ├── Models/
│   └── Providers/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docs/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   ├── auth.php
│   ├── web.php
│   └── ...
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── README.md
├── vite.config.js
└── tailwind.config.js
```

## 5. Role Pengguna

### 5.1 Anggota
Anggota dapat melakukan hal-hal berikut:

- login ke sistem
- melihat dashboard keuangan pribadi
- melihat riwayat simpanan wajib
- mengajukan simpanan sukarela
- melihat dan mengelola tabungan
- mengajukan pinjaman
- mengunggah dokumen pendukung
- melihat riwayat angsuran
- memperbarui profil dan password

### 5.2 Pengurus
Pengurus dapat melakukan:

- login ke area pengurus
- melihat dashboard koperasi
- mengelola data anggota
- mengecek dan memvalidasi simpanan anggota
- membukukan tabungan dan riwayat transaksi
- menyetujui atau menolak pengajuan pinjaman
- mengatur nominal simpanan dan tenor pinjaman
- membuat laporan bulanan/tahunan
- melakukan export laporan ke Excel/PDF

## 6. Fitur Utama

### 6.1 Simpanan Wajib
- transaksi simpanan rutin anggota
- dukungan upload bukti transaksi
- status validasi oleh pengurus
- laporan dan riwayat per anggota

### 6.2 Simpanan Sukarela
- pengajuan simpanan sukarela oleh anggota
- review dan approval oleh pengurus
- pencatatan transaksi dan riwayat

### 6.3 Tabungan
- pencatatan transaksi tabungan
- riwayat transaksi anggota
- tampilan detail saldo dan aktivitas

### 6.4 Pinjaman
- pengajuan pinjaman dengan kategori dan tenor tertentu
- pengunggahan dokumen pinjaman
- review dan approval pengurus
- pencatatan angsuran
- perhitungan pembayaran secara otomatis

### 6.5 Laporan
- laporan simpanan tahunan/bulanan
- laporan tabungan
- export file Excel
- export dokumen pendukung

## 7. Alur Aplikasi

### 7.1 Alur Login
1. User membuka halaman login
2. Sistem memvalidasi kredensial
3. User diarahkan ke dashboard sesuai role

### 7.2 Alur Anggota
1. Anggota login
2. Anggota mengakses dashboard
3. Anggota dapat melihat data keuangan pribadi
4. Anggota melakukan transaksi sesuai kebutuhan
5. Pengurus memverifikasi status data

### 7.3 Alur Pengurus
1. Pengurus login ke area pengurus
2. Pengurus mengakses dashboard koperasi
3. Pengurus memvalidasi simpanan, pinjaman, dan dokumen anggota
4. Pengurus menyiapkan laporan dan export data

## 8. Routing Utama

Beberapa route utama yang ada pada aplikasi:

- `/login` untuk login anggota
- `/register` untuk pendaftaran anggota
- `/dashboard` untuk halaman dashboard anggota
- `/anggota/simpanan` untuk simpanan wajib anggota
- `/simpanan-sukarela-anggota` untuk simpanan sukarela anggota
- `/tabungan` untuk tabungan anggota
- `/pinjaman/create` untuk pengajuan pinjaman
- `/pengurus/login` untuk login pengurus
- `/pengurus/dashboard` untuk dashboard pengurus
- `/pengurus/simpanan-wajib` untuk pengelolaan simpanan wajib
- `/pengurus/simpanan-sukarela` untuk pengelolaan simpanan sukarela
- `/pengurus/tabungan` untuk pengelolaan tabungan pengurus

## 9. Persiapan Environment

Buat file environment dari contoh:

```bash
cp .env.example .env
```

Atur konfigurasi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi
DB_USERNAME=root
DB_PASSWORD=
```

Generate key aplikasi:

```bash
php artisan key:generate
```

## 10. Cara Menjalankan Proyek

### 10.1 Install Dependency

```bash
composer install
npm install
```

### 10.2 Migrasi Database dan Seeder

```bash
php artisan migrate --seed
```

### 10.3 Build Asset Frontend

```bash
npm run build
```

### 10.4 Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan pada:

```text
http://localhost:8000
```

Untuk mode development frontend:

```bash
npm run dev
```

## 11. Testing

Jalankan pengujian unit/feature dengan:

```bash
php artisan test
```

Untuk menjalankan suite yang lebih spesifik:

```bash
php artisan test --testsuite=Feature
```

## 12. Praktik Pengembangan

- gunakan branch yang terpisah untuk setiap fitur
- lakukan migrasi saat ada perubahan struktur database
- pastikan environment `.env` tidak dikirim ke repository public
- lakukan testing sebelum merge ke branch utama
- gunakan Laravel artisan untuk pembuatan controller/model/migration

## 13. Catatan Deployment

Untuk environment produksi:

```bash
composer install --no-dev
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

Pastikan server memiliki akses tulis ke folder berikut:

- `storage/`
- `bootstrap/cache/`
- `public/uploads/`

## 14. Risiko dan perhatian penting

- file dokumen anggota dan bukti transaksi perlu diatur permission file secara aman
- pengelolaan role akses harus tetap diperhatikan agar tidak ada user yang dapat mengakses area pengurus tanpa otorisasi
- verifikasi data pinjaman dan simpanan harus dijaga agar laporan keuangan akurat

## 15. Kesimpulan

Project ini adalah sistem manajemen koperasi yang fokus pada transaksi keuangan, validasi admin, dan monitoring operasional koperasi. Dengan struktur Laravel yang rapi dan penggunaan role-based access, aplikasi ini dapat dikembangkan lebih lanjut untuk kebutuhan koperasi yang lebih kompleks.

## 16. Referensi

- Laravel Framework: https://laravel.com
- Laravel Breeze: https://laravel.com/docs
- Tailwind CSS: https://tailwindcss.com
- Vite: https://vitejs.dev
- Maatwebsite Excel: https://docs.laravel-excel.com
