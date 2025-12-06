# Sistem Informasi Koperasi Poliwangi

![Laravel Tests](https://github.com/naufalpalal/Website-Koperasi-Lanjutan/workflows/Laravel%20Tests/badge.svg)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%7C%208.3%20%7C%208.4-blue)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

Aplikasi web untuk manajemen koperasi dengan fitur simpanan, tabungan, dan pinjaman untuk anggota serta dashboard pengurus.

## 🚀 Fitur Utama

### Untuk Anggota
- 📊 Dashboard informasi keuangan
- 💰 Simpanan Wajib & Sukarela
- 🏦 Tabungan
- 💳 Pengajuan Pinjaman dengan kategori tenor
- 💸 **Diskon pelunasan angsuran**
- 👤 Manajemen Profil

### Untuk Pengurus
- 📈 Dashboard monitoring
- 👥 Kelola Data Anggota
- ✅ Persetujuan Pengajuan Pinjaman
- 💼 Manajemen Simpanan & Tabungan
- 📑 Laporan & Export Excel
- ⚙️ Pengaturan Tenor & Bunga Pinjaman

## 🛠️ Tech Stack

- **Framework**: Laravel 12.x
- **PHP**: 8.2 | 8.3 | 8.4
- **Database**: MySQL
- **Frontend**: Tailwind CSS, Alpine.js
- **Testing**: PHPUnit
- **CI/CD**: GitHub Actions

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM (untuk asset compilation)

## 🔧 Installation

```bash
# Clone repository
git clone https://github.com/naufalpalal/Website-Koperasi-Lanjutan.git
cd Website-Koperasi-Lanjutan/Koperasi_Lanjutan

# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=koperasi
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations & seeders
php artisan migrate --seed

# Build assets
npm run build

# Start development server
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run tests dengan parallel
php artisan test --parallel

# Run specific test suite
php artisan test --testsuite=Feature

# Run dengan coverage (jika diperlukan)
php artisan test --coverage
```

## 👥 Default Login Credentials

### Pengurus
- **URL**: `/pengurus/login`
- **Username**: lihat di database seeder

### Anggota
- **URL**: `/login`
- **NIP/Username**: lihat di database seeder

## 📁 Struktur Route

```
routes/
├── web.php      # Route loader utama
├── auth.php     # Authentication routes
├── user.php     # Anggota routes
└── pengurus.php # Pengurus routes
```

## 🔄 GitHub Actions CI/CD

Automated testing berjalan otomatis pada:
- ✅ Push ke branch `main`, `master`, `develop`
- ✅ Pull Request ke branch utama
- ✅ Multiple PHP versions (8.2, 8.3, 8.4)
- ✅ MySQL database testing

Lihat status tests di tab **Actions** repository.

## 📝 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Credits

Built with [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
