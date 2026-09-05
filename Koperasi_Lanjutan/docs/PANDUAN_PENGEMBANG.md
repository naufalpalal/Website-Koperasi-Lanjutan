# Panduan Pengembang - Sistem Manajemen Koperasi

## Daftar Isi
1. [Setup Awal](#setup-awal)
2. [Struktur Aplikasi](#struktur-aplikasi)
3. [Model & Database](#model--database)
4. [Controllers & Routes](#controllers--routes)
5. [Fitur Utama](#fitur-utama)
6. [Authentication & Authorization](#authentication--authorization)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)

---

## Setup Awal

### Persyaratan Sistem
- PHP 8.2+
- Composer
- Node.js 16+ (untuk npm)
- MySQL 8.0+
- Git

### Instalasi Lokal

```bash
# 1. Clone repository
git clone https://github.com/naufalpalal/Website-Koperasi-Lanjutan.git
cd Koperasi_Lanjutan

# 2. Install PHP dependencies
composer install

# 3. Install NPM dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Setup database
php artisan migrate --seed

# 7. Build frontend assets
npm run build  # production
# atau
npm run dev    # development

# 8. Jalankan aplikasi
php artisan serve
```

### Database Seeding
Database seeding menyediakan data dummy untuk testing:

```bash
php artisan db:seed
```

Data yang di-seed:
- Users (Anggota & Pengurus)
- Simpanan Wajib & Sukarela
- Tabungan
- Pinjaman & Angsuran

---

## Struktur Aplikasi

### Folder Utama

```
app/
├── Exports/                    # Export Excel (Maatwebsite)
│   └── SimpananExport.php
├── Helpers/                    # Helper functions
│   └── Bulan.php              # Convert bulan ke format teks
├── Http/
│   ├── Controllers/           # Route controllers
│   │   ├── User/              # Anggota controllers
│   │   ├── Pengurus/          # Pengurus controllers
│   │   └── Auth/              # Authentication
│   ├── Middleware/            # Custom middleware
│   │   └── RolePengurusMiddleware.php
│   └── Requests/              # Form Request Validation
├── Mail/                      # Email templates
│   └── ResetPasswordMail.php
├── Models/                    # Eloquent models
│   ├── User.php
│   ├── Simpanan.php
│   ├── Pinjaman.php
│   ├── Tabungan.php
│   ├── Pengurus/              # Models untuk Pengurus
│   │   ├── SimpananWajib.php
│   │   ├── SimpananSukarela.php
│   │   └── ...
│   └── user/                  # Models untuk User
│       ├── SimpananWajib.php
│       └── ...
├── Providers/
│   ├── AppServiceProvider.php
│   └── RouteServiceProvider.php
└── Services/                  # Business logic
    └── WhatsappService.php
```

### Resources (Frontend)

```
resources/
├── css/                       # Tailwind CSS
│   └── app.css
├── js/                        # JavaScript & Alpine.js
│   ├── app.js
│   └── bootstrap.js
└── views/                     # Blade templates
    ├── components/            # Reusable components
    ├── layouts/               # Master layouts
    ├── user/                  # User views
    │   ├── dashboard.blade.php
    │   ├── simpanan/
    │   ├── tabungan/
    │   └── pinjaman/
    ├── pengurus/              # Pengurus views
    │   ├── dashboard.blade.php
    │   ├── simpanan/
    │   ├── pinjaman/
    │   └── laporan/
    └── auth/                  # Auth views
```

### Database Migrations

Semua migrations tersimpan di `database/migrations/`:
- `create_users_table` - User anggota & pengurus
- `create_simpanan_table` - Simpanan dasar
- `create_simpanan_wajib_table` - Simpanan wajib anggota
- `create_simpanan_sukarela_table` - Simpanan sukarela
- `create_tabungan_table` - Tabungan anggota
- `create_pinjaman_table` - Pengajuan pinjaman
- `create_angsuran_pinjaman_table` - Cicilan pinjaman
- `create_dokumen_table` - File dokumen

---

## Model & Database

### User Model
**Lokasi:** `app/Models/User.php`

```php
// Atribut utama
- nama
- email
- no_telepon
- password
- nip / username
- alamat_rumah, tempat_lahir, tanggal_lahir
- unit_kerja
- role (anggota, pengurus)
- status (aktif, non-aktif, pending)

// Relationships
- simpananWajib() - hasMany SimpananWajib
- simpananSukarela() - hasMany SimpananSukarela
- tabungan() - hasMany Tabungan
- pinjaman() - hasMany Pinjaman
- dokumen() - hasMany Dokumen
```

### Simpanan Models

#### 1. SimpananWajib (Pengurus namespace)
**Lokasi:** `app/Models/Pengurus/SimpananWajib.php`

Simpanan wajib yang harus disetor anggota setiap bulannya.

```php
Atribut:
- users_id
- bulan (1-12)
- tahun (YYYY)
- nilai (decimal)
- status (sudah, belum)
- bukti_transfer (file path)
- keterangan

Relationships:
- user() - belongsTo User
```

#### 2. SimpananSukarela (Pengurus namespace)
**Lokasi:** `app/Models/Pengurus/SimpananSukarela.php`

Simpanan sukarela yang diajukan anggota.

```php
Atribut:
- users_id
- bulan
- tahun
- nilai
- status (pending, disetujui, ditolak)
- bukti_transfer

Relationships:
- user() - belongsTo User
```

#### 3. MasterSimpananWajib
**Lokasi:** `app/Models/MasterSimpananWajib.php`

Master data untuk nominal simpanan wajib per tahun.

```php
Atribut:
- tahun
- nominal (decimal)
- deskripsi
```

### Pinjaman & Angsuran

#### Pinjaman Model
**Lokasi:** `app/Models/Pinjaman.php`

```php
Atribut:
- users_id
- jumlah_pinjaman (decimal)
- bunga_persen (decimal)
- tenor_bulan (int)
- status (pending, disetujui, ditolak, selesai)
- tanggal_persetujuan
- tanggal_pencairan
- paket_id (referensi paket)

Relationships:
- user() - belongsTo User
- angsuran() - hasMany AngsuranPinjaman
- dokumen() - hasMany DokumenPinjaman
```

#### AngsuranPinjaman Model
**Lokasi:** `app/Models/AngsuranPinjaman.php`

```php
Atribut:
- pinjaman_id
- no_angsuran
- tanggal_jatuh_tempo
- pokok
- bunga
- total_angsuran
- status_pembayaran (belum, sebagian, lunas)
- tanggal_pembayaran
- diskon (decimal)

Relationships:
- pinjaman() - belongsTo Pinjaman
```

### Dokumen Model
**Lokasi:** `app/Models/Dokumen.php`

```php
Atribut:
- users_id
- tipe_dokumen (ktp, npwp, slip_gaji, dll)
- file_path
- tanggal_upload
- status_verifikasi (pending, disetujui, ditolak)

Relationships:
- user() - belongsTo User
```

---

## Controllers & Routes

### Route Structure

**Web Routes:** `routes/web.php`
- Public routes (login, register, forgot password)
- Auth routes di `routes/auth.php`

**Route Prefix:**
```php
// Anggota routes
Route::prefix('user')->middleware(['auth'])->group(function () {
    // Dashboard, simpanan, tabungan, pinjaman
});

// Pengurus routes
Route::prefix('pengurus')->middleware(['auth', 'role.pengurus'])->group(function () {
    // Dashboard pengurus, validasi, laporan
});
```

### User Controllers

#### 1. SimpananWajibController
**Lokasi:** `app/Http/Controllers/User/SimpananWajibController.php`

Methods:
- `index()` - Tampilkan riwayat simpanan wajib
- `bayar()` - Form pembayaran simpanan
- `kirimBukti()` - Upload bukti transfer

#### 2. SimpananSukarelaAnggotaController
**Lokasi:** `app/Http/Controllers/User/SimpananSukarelaAnggotaController.php`

Methods:
- `index()` - Dashboard simpanan sukarela
- `riwayat()` - Riwayat dengan filter bulan/tahun
- `toggle()` - Update status simpanan

#### 3. PinjamanAnggotaController
**Lokasi:** `app/Http/Controllers/User/PinjamanAnggotaController.php`

Methods:
- `index()` - List pinjaman anggota
- `create()` - Form pengajuan pinjaman
- `store()` - Submit pinjaman
- `show()` - Detail pinjaman

#### 4. AngsuranAnggotaController
**Lokasi:** `app/Http/Controllers/User/AngsuranAnggotaController.php`

Methods:
- `index()` - List cicilan
- `bayar()` - Form pembayaran cicilan
- `kirimBukti()` - Upload bukti pembayaran

### Pengurus Controllers

#### 1. PinjamanController (Pengurus)
**Lokasi:** `app/Http/Controllers/Pengurus/PinjamanController.php`

Methods:
- `index()` - List semua pengajuan pinjaman
- `show()` - Detail pinjaman untuk verifikasi
- `approve()` - Approve pinjaman
- `reject()` - Tolak pinjaman

#### 2. PengurusSimpananWajibController
**Lokasi:** `app/Http/Controllers/Pengurus/PengurusSimpananWajibController.php`

Methods:
- `index()` - List simpanan wajib
- `validasi()` - Verifikasi simpanan
- `export()` - Export ke Excel

#### 3. LaporanController
**Lokasi:** `app/Http/Controllers/LaporanController.php`

Methods:
- `simpanan()` - Laporan simpanan
- `pinjaman()` - Laporan pinjaman
- `keuangan()` - Laporan keuangan
- `exportExcel()` - Export laporan
- `exportPDF()` - Unduh PDF

---

## Fitur Utama

### 1. Simpanan Wajib

**Alur:**
1. Admin/Pengurus set nominal simpanan wajib per tahun
2. Sistem generate list simpanan untuk setiap anggota per bulan
3. Anggota melihat riwayat simpanan di dashboard
4. Anggota upload bukti transfer
5. Pengurus validasi pembayaran

**Key Files:**
- Model: `app/Models/Pengurus/SimpananWajib.php`
- Controller: `app/Http/Controllers/User/SimpananWajibController.php`
- Migration: `create_simpanan_wajib_table.php`

### 2. Simpanan Sukarela

**Alur:**
1. Anggota mengajukan simpanan sukarela
2. Anggota upload bukti transfer
3. Pengurus review dan approve/reject
4. Approved simpanan ditambahkan ke saldo anggota

**Key Files:**
- Model: `app/Models/Pengurus/SimpananSukarela.php`
- Controller: `app/Http/Controllers/User/SimpananSukarelaAnggotaController.php`
- Pengurus Controller: `app/Http/Controllers/Pengurus/SimpananSukarelaController.php`

### 3. Tabungan

**Alur:**
1. Anggota membuat akun tabungan
2. Anggota setor tabungan sesuai keinginan
3. Sistem hitung bunga (jika ada)
4. Anggota bisa tarik tabungan dengan approval pengurus

**Key Files:**
- Model: `app/Models/Tabungan.php`
- Controller: `app/Http/Controllers/User/TabunganController.php`

### 4. Pinjaman & Angsuran

**Alur Pengajuan:**
1. Anggota klik "Ajukan Pinjaman"
2. Isi form: jumlah, tenor, tujuan
3. Upload dokumen pendukung
4. Pengurus review & approve
5. Uang dicairkan jika approved

**Alur Cicilan:**
1. Sistem generate jadwal cicilan berdasarkan tenor
2. Anggota melihat jadwal di dashboard
3. Anggota upload bukti pembayaran
4. Pengurus validasi pembayaran
5. Status cicilan berubah menjadi "Lunas"

**Key Files:**
- Model: `app/Models/Pinjaman.php`, `app/Models/AngsuranPinjaman.php`
- Controller: `app/Http/Controllers/User/PinjamanAnggotaController.php`
- Pengurus: `app/Http/Controllers/Pengurus/PinjamanController.php`

### 5. Laporan & Export

**Tipe Laporan:**
1. Laporan Simpanan - Per anggota, per bulan, per tahun
2. Laporan Pinjaman - Outstanding, lunas, pending approval
3. Laporan Keuangan - Neraca, laba-rugi
4. Export Excel - Semua laporan bisa di-export
5. Export PDF - Laporan resmi untuk cetak

**Key Files:**
- `app/Http/Controllers/LaporanController.php`
- `app/Exports/SimpananExport.php`
- Menggunakan: Maatwebsite Excel, DOMPDF

---

## Authentication & Authorization

### Authentication Flow

```php
// Login
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// User divalidasi di User model
// Password di-hash dengan bcrypt

// Role checking
- 'anggota' → Akses user dashboard & fitur anggota
- 'pengurus' → Akses pengurus area & fitur validasi
- 'admin' → Super admin (jika ada)
```

### Middleware

#### 1. RolePengurusMiddleware
**Lokasi:** `app/Http/Middleware/RolePengurusMiddleware.php`

```php
// Gunakan di routes:
Route::middleware('role.pengurus')->group(function () {
    // Hanya pengurus yang bisa akses
});
```

Checking:
- User harus logged in
- User role harus 'pengurus'
- Return 403 jika tidak authorized

### Authorization Best Practices

```php
// Di Controller
public function show(Pinjaman $pinjaman)
{
    // Cek apakah user adalah pemilik pinjaman
    if ($pinjaman->users_id !== Auth::id()) {
        return abort(403, 'Unauthorized');
    }
    
    return view('pinjaman.show', ['pinjaman' => $pinjaman]);
}

// Atau gunakan Policy
$this->authorize('view', $pinjaman);
```

---

## Best Practices

### 1. Model Design
- Selalu definisikan `$fillable` untuk mass assignment
- Gunakan relationships daripada manual joins
- Definisikan casts untuk type safety

```php
protected $fillable = ['nama', 'email', 'password'];
protected $casts = [
    'password' => 'hashed',
    'tanggal_lahir' => 'date',
];
```

### 2. Database Queries
- Gunakan eager loading untuk menghindari N+1 queries

```php
// ❌ Bad - N+1 queries
$users = User::all();
foreach ($users as $user) {
    echo $user->simpananWajib()->sum('nilai');
}

// ✅ Good - Single query
$users = User::with('simpananWajib')->get();
foreach ($users as $user) {
    echo $user->simpananWajib->sum('nilai');
}
```

### 3. Route Organization
- Group routes by prefix dan middleware
- Gunakan resource routes untuk CRUD

```php
Route::apiResource('posts', PostController::class);
// Auto generate: index, create, store, show, edit, update, destroy
```

### 4. Error Handling
- Gunakan try-catch untuk operasi kritis

```php
try {
    $pinjaman = Pinjaman::create($data);
    return redirect()->with('success', 'Pinjaman berhasil dibuat');
} catch (\Exception $e) {
    return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
}
```

### 5. Validation
- Validasi di Request class, tidak di Controller

```php
// app/Http/Requests/StorePinjamanRequest.php
public function rules()
{
    return [
        'jumlah_pinjaman' => 'required|numeric|min:1000000',
        'tenor_bulan' => 'required|numeric|min:6|max:60',
    ];
}
```

### 6. Testing
- Tulis test untuk fitur kritis
- Gunakan Pest PHP

```bash
php artisan test
```

---

## Troubleshooting

### Common Issues

#### 1. "SQLSTATE[HY000]: General error: 1030"
**Penyebab:** Memory limit terlalu rendah saat query besar
**Solusi:**
```bash
# Edit .env
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# Atau set memory limit di php.ini
memory_limit=512M
```

#### 2. "Class not found"
**Penyebab:** Autoload belum di-refresh
**Solusi:**
```bash
composer dumpautoload
```

#### 3. "Permission denied" pada storage
**Penyebab:** Folder permissions tidak tepat
**Solusi:**
```bash
chmod -R 775 storage bootstrap/cache
```

#### 4. Migration error
**Penyebab:** Syntax error di migration atau database corruption
**Solusi:**
```bash
# Rollback semua
php artisan migrate:reset

# Jalankan ulang
php artisan migrate --seed
```

#### 5. Assets tidak load (CSS/JS)
**Penyebab:** Vite assets belum di-build
**Solusi:**
```bash
npm run build    # Production
# atau
npm run dev      # Development dengan hot reload
```

### Debug Mode

Aktifkan debug mode di `.env`:
```
APP_DEBUG=true
```

**Hati-hati:** Jangan enable di production!

### Useful Commands

```bash
# Database
php artisan migrate                 # Run migrations
php artisan migrate:refresh         # Reset dan re-run
php artisan migrate:reset           # Rollback semua
php artisan db:seed                 # Run seeders

# Cache
php artisan cache:clear             # Clear app cache
php artisan config:cache            # Cache config
php artisan view:clear              # Clear view cache

# Assets
npm run dev                          # Development
npm run build                        # Production

# Testing
php artisan test                     # Run Pest tests

# Maintenance
php artisan tinker                   # Interactive shell
php artisan queue:work               # Process queue jobs
```

---

## Kontak & Support

Untuk pertanyaan atau issue, silakan:
1. Buka GitHub Issues
2. Contact tim developer
3. Check dokumentasi di `/docs/`

---

**Last Updated:** 2025
**Maintained By:** Tim Developer Koperasi
