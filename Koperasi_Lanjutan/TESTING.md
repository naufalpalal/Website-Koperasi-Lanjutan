# Automation Testing Guide

Dokumentasi lengkap tentang automation testing untuk aplikasi Koperasi.

## 📋 Daftar Isi

1. [Pengenalan](#pengenalan)
2. [Struktur Test](#struktur-test)
3. [Menjalankan Test](#menjalankan-test)
4. [CI/CD dengan GitHub Actions](#cicd-dengan-github-actions)
5. [Best Practices](#best-practices)
6. [Troubleshooting](#troubleshooting)

## 🎯 Pengenalan

Automation testing adalah proses menjalankan test secara otomatis tanpa intervensi manual. Di aplikasi ini, kita menggunakan:

- **PHPUnit** - Framework testing untuk PHP
- **Laravel Testing** - Testing utilities dari Laravel
- **GitHub Actions** - CI/CD untuk automation

## 📁 Struktur Test

```
tests/
├── Feature/          # Test untuk fitur aplikasi (end-to-end)
│   ├── LoginTest.php
│   ├── RegisterTest.php
│   ├── ForgotPasswordTest.php
│   ├── ProfileTest.php
│   ├── TabunganTest.php
│   └── SimpananSukarelaTest.php
└── Unit/             # Test untuk unit kecil (functions, methods)
    └── ExampleTest.php
```

## 🚀 Menjalankan Test

### 1. Manual Testing

#### Menjalankan semua test:
```bash
php artisan test
```

#### Menjalankan test tertentu:
```bash
# Test berdasarkan file
php artisan test --filter LoginTest

# Test berdasarkan method
php artisan test --filter test_user_dapat_login

# Test berdasarkan suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

#### Dengan coverage:
```bash
php artisan test --coverage
```

### 2. Menggunakan Script Automation

#### Linux/Mac:
```bash
# Memberikan permission execute
chmod +x scripts/run-tests.sh

# Menjalankan semua test
./scripts/run-tests.sh --all

# Menjalankan feature test saja
./scripts/run-tests.sh --feature

# Menjalankan test tertentu
./scripts/run-tests.sh --filter=LoginTest

# Setup database dan jalankan test
./scripts/run-tests.sh --setup-db --all

# Dengan coverage
./scripts/run-tests.sh --coverage
```

#### Windows:
```cmd
REM Menjalankan semua test
scripts\run-tests.bat --all

REM Menjalankan feature test
scripts\run-tests.bat --feature

REM Menjalankan test tertentu
scripts\run-tests.bat --filter=LoginTest
```

### 3. Testing dengan Database

Test menggunakan database in-memory (SQLite) secara default. Untuk menggunakan MySQL:

1. Update `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="koperasi_test"/>
```

2. Buat database test:
```bash
php artisan migrate:fresh --env=testing
```

## 🔄 CI/CD dengan GitHub Actions

Test otomatis dijalankan melalui GitHub Actions pada:

- **Push ke branch main/develop**
- **Pull Request ke main/develop**
- **Schedule: Setiap hari jam 2 pagi UTC**

### File Workflow

Workflow configuration ada di: `.github/workflows/tests.yml`

### Melihat Hasil Test

1. Buka repository di GitHub
2. Klik tab **Actions**
3. Pilih workflow run yang ingin dilihat
4. Lihat hasil test di job **test**

## ✅ Best Practices

### 1. Naming Convention

- Test class: `FeatureNameTest.php`
- Test method: `test_deskripsi_singkat_apa_yang_ditest`

Contoh:
```php
class LoginTest extends TestCase
{
    public function test_user_dapat_login_dengan_nip_dan_password_benar()
    {
        // Test implementation
    }
}
```

### 2. Test Structure (AAA Pattern)

```php
public function test_something()
{
    // Arrange - Setup data yang diperlukan
    $user = User::factory()->create();
    
    // Act - Jalankan action yang ditest
    $response = $this->actingAs($user)->get('/dashboard');
    
    // Assert - Verifikasi hasil
    $response->assertStatus(200);
}
```

### 3. Test Isolation

- Setiap test harus independen
- Gunakan `RefreshDatabase` trait
- Jangan bergantung pada test lain

### 4. Test Coverage

Target coverage yang baik:
- **Feature Tests**: 80%+ untuk critical paths
- **Unit Tests**: 70%+ untuk business logic

### 5. Test Data

- Gunakan **Factories** untuk membuat test data
- Jangan hardcode data di test
- Gunakan **Faker** untuk data random

Contoh:
```php
$user = User::factory()->create([
    'nip' => '1234567890',
    'status' => 'aktif',
]);
```

## 🐛 Troubleshooting

### Test Gagal karena Database

**Problem**: `SQLSTATE[HY000]: General error: 1 no such table`

**Solution**:
```bash
php artisan migrate:fresh --env=testing
```

### Test Gagal karena Cache

**Problem**: Test menggunakan cache lama

**Solution**:
```bash
php artisan config:clear
php artisan cache:clear
php artisan test
```

### Test Lambat

**Problem**: Test memakan waktu lama

**Solutions**:
1. Gunakan `--parallel` untuk parallel testing:
   ```bash
   php artisan test --parallel
   ```
2. Gunakan database in-memory (SQLite)
3. Mock external services (email, API, dll)

### Test Gagal di CI/CD

**Problem**: Test pass lokal tapi gagal di GitHub Actions

**Solutions**:
1. Cek environment variables di workflow
2. Pastikan database service running
3. Cek log di GitHub Actions untuk detail error

## 📊 Test Reports

### Menjalankan dengan Report

```bash
# Generate HTML report
php artisan test --coverage-html=coverage

# Generate XML report (untuk CI/CD)
php artisan test --coverage-xml=coverage.xml
```

## 🔗 Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)

## 📝 Checklist Sebelum Commit

- [ ] Semua test pass lokal
- [ ] Test coverage mencukupi
- [ ] Tidak ada test yang di-skip tanpa alasan
- [ ] Test menggunakan bahasa Indonesia
- [ ] Test mengikuti naming convention
- [ ] Test isolated (tidak depend pada test lain)

---

**Happy Testing! 🎉**

