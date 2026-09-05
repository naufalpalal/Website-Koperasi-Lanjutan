# Dokumentasi Database Schema

## Diagram ER (Entity Relationship)

```
┌─────────────────────────────────────────────────────────────────────┐
│                         users                                        │
├─────────────────────────────────────────────────────────────────────┤
│ id (PK)                                                              │
│ nama                                                                 │
│ email (UNIQUE)                                                       │
│ no_telepon                                                           │
│ password (hashed)                                                    │
│ nip / username                                                       │
│ tempat_lahir, tanggal_lahir                                         │
│ alamat_rumah                                                         │
│ unit_kerja                                                           │
│ role (anggota, pengurus)                                            │
│ status (aktif, non-aktif, pending)                                  │
│ terakhir_tidak_aktif (nullable)                                     │
│ created_at, updated_at                                              │
└─────────────────────────────────────────────────────────────────────┘
         ↓                    ↓                    ↓
         │                    │                    │
    ┌────┴──────┐      ┌──────┴────┐      ┌───────┴──────┐
    │            │      │            │      │               │
    ▼            ▼      ▼            ▼      ▼               ▼

┌──────────────────────┐  ┌──────────────────────┐  ┌──────────────────┐
│  simpanan_wajib      │  │ simpanan_sukarela    │  │    tabungan      │
├──────────────────────┤  ├──────────────────────┤  ├──────────────────┤
│ id (PK)              │  │ id (PK)              │  │ id (PK)          │
│ users_id (FK)        │  │ users_id (FK)        │  │ users_id (FK)    │
│ bulan (1-12)         │  │ bulan (1-12)         │  │ jumlah_awal      │
│ tahun                │  │ tahun                │  │ saldo_akhir      │
│ nilai                │  │ nilai                │  │ bunga_persen     │
│ status               │  │ status               │  │ tanggal_dibuka   │
│ bukti_transfer       │  │ bukti_transfer       │  │ status           │
│ keterangan           │  │ created_at, updated  │  │ created_at       │
│ created_at, updated  │  │                      │  │                  │
└──────────────────────┘  └──────────────────────┘  └──────────────────┘

         │
         ▼

┌───────────────────────────┐
│ master_simpanan_wajib     │
├───────────────────────────┤
│ id (PK)                   │
│ tahun                     │
│ nominal                   │
│ deskripsi                 │
│ created_at, updated_at    │
└───────────────────────────┘


    ┌──────────────────────────┐
    │      pinjaman            │
    ├──────────────────────────┤
    │ id (PK)                  │
    │ users_id (FK)            │
    │ jumlah_pinjaman          │
    │ bunga_persen             │
    │ tenor_bulan              │
    │ status                   │
    │ tanggal_persetujuan      │
    │ tanggal_pencairan        │
    │ paket_id (FK)            │
    │ created_at, updated_at   │
    └──────────────────────────┘
             │
             ▼
    ┌──────────────────────────┐
    │ angsuran_pinjaman        │
    ├──────────────────────────┤
    │ id (PK)                  │
    │ pinjaman_id (FK)         │
    │ no_angsuran              │
    │ tanggal_jatuh_tempo      │
    │ pokok                    │
    │ bunga                    │
    │ total_angsuran           │
    │ status_pembayaran        │
    │ tanggal_pembayaran       │
    │ diskon                   │
    │ created_at, updated_at   │
    └──────────────────────────┘

    ┌──────────────────────────┐
    │    dokumen_pinjaman      │
    ├──────────────────────────┤
    │ id (PK)                  │
    │ pinjaman_id (FK)         │
    │ file_path                │
    │ tipe_dokumen             │
    │ status_verifikasi        │
    │ created_at, updated_at   │
    └──────────────────────────┘

    ┌──────────────────────────┐
    │      dokumen             │
    ├──────────────────────────┤
    │ id (PK)                  │
    │ users_id (FK)            │
    │ tipe_dokumen             │
    │ file_path                │
    │ tanggal_upload           │
    │ status_verifikasi        │
    │ created_at, updated_at   │
    └──────────────────────────┘

    ┌──────────────────────────┐
    │ identitas_koperasi       │
    ├──────────────────────────┤
    │ id (PK)                  │
    │ nama_koperasi            │
    │ alamat                   │
    │ telepon                  │
    │ email                    │
    │ logo                     │
    │ created_at, updated_at   │
    └──────────────────────────┘
```

---

## Tabel Utama

### 1. users

**Deskripsi:** Menyimpan data semua pengguna (anggota dan pengurus)

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID user |
| nama | VARCHAR(255) | NOT NULL | Nama lengkap |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Email unik |
| email_verified_at | TIMESTAMP | NULLABLE | Verifikasi email |
| no_telepon | VARCHAR(20) | NULLABLE | Nomor HP |
| password | VARCHAR(255) | NOT NULL | Password terenkripsi |
| nip | VARCHAR(50) | NULLABLE, UNIQUE | Nomor ID pegawai |
| username | VARCHAR(50) | NULLABLE, UNIQUE | Username login |
| tempat_lahir | VARCHAR(100) | NULLABLE | Tempat lahir |
| tanggal_lahir | DATE | NULLABLE | Tanggal lahir |
| alamat_rumah | TEXT | NULLABLE | Alamat tinggal |
| unit_kerja | VARCHAR(100) | NULLABLE | Unit kerja |
| role | ENUM('anggota', 'pengurus') | DEFAULT 'anggota' | Role user |
| status | ENUM('aktif', 'non-aktif', 'pending') | DEFAULT 'pending' | Status aktif |
| terakhir_tidak_aktif | TIMESTAMP | NULLABLE | Kapan terakhir aktif |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

**Indexes:**
- email (UNIQUE)
- nip (UNIQUE)
- username (UNIQUE)
- role, status

---

### 2. simpanan_wajib

**Deskripsi:** Simpanan wajib bulanan dari anggota

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID simpanan |
| users_id | BIGINT | FK → users.id | User penyimpan |
| bulan | INT | NOT NULL (1-12) | Bulan |
| tahun | INT | NOT NULL | Tahun |
| nilai | DECIMAL(15,2) | NOT NULL | Jumlah simpanan |
| status | ENUM('sudah', 'belum') | DEFAULT 'belum' | Status pembayaran |
| bukti_transfer | VARCHAR(255) | NULLABLE | Path foto bukti |
| keterangan | TEXT | NULLABLE | Catatan |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

**Indexes:**
- users_id, bulan, tahun (UNIQUE)
- status, tahun

---

### 3. simpanan_sukarela

**Deskripsi:** Simpanan sukarela (ad-hoc) dari anggota

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID simpanan |
| users_id | BIGINT | FK → users.id | User penyimpan |
| bulan | INT | NOT NULL (1-12) | Bulan |
| tahun | INT | NOT NULL | Tahun |
| nilai | DECIMAL(15,2) | NOT NULL | Jumlah simpanan |
| status | ENUM('pending', 'disetujui', 'ditolak') | DEFAULT 'pending' | Status approval |
| bukti_transfer | VARCHAR(255) | NOT NULL | Path foto bukti |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

**Indexes:**
- users_id, status
- tahun, bulan

---

### 4. master_simpanan_wajib

**Deskripsi:** Master data nominal simpanan wajib per tahun

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID master |
| tahun | INT | UNIQUE, NOT NULL | Tahun |
| nominal | DECIMAL(15,2) | NOT NULL | Nominal wajib/bulan |
| deskripsi | TEXT | NULLABLE | Keterangan |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

---

### 5. tabungan

**Deskripsi:** Tabungan sukarela anggota dengan bunga

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID tabungan |
| users_id | BIGINT | FK → users.id | Pemilik tabungan |
| jumlah_awal | DECIMAL(15,2) | NOT NULL | Modal awal |
| saldo_akhir | DECIMAL(15,2) | NOT NULL | Saldo akhir |
| bunga_persen | DECIMAL(5,2) | DEFAULT 0 | Persentase bunga |
| tanggal_dibuka | DATE | NOT NULL | Tanggal buka |
| status | ENUM('aktif', 'ditarik') | DEFAULT 'aktif' | Status |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

---

### 6. pinjaman

**Deskripsi:** Pengajuan pinjaman dari anggota

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID pinjaman |
| users_id | BIGINT | FK → users.id | Peminjam |
| jumlah_pinjaman | DECIMAL(15,2) | NOT NULL | Nominal pinjaman |
| bunga_persen | DECIMAL(5,2) | NOT NULL | Bunga (%) |
| tenor_bulan | INT | NOT NULL | Tenor cicilan (bulan) |
| status | ENUM('pending', 'disetujui', 'ditolak', 'selesai') | DEFAULT 'pending' | Status |
| tanggal_persetujuan | DATE | NULLABLE | Tgl approval |
| tanggal_pencairan | DATE | NULLABLE | Tgl pencairan dana |
| paket_id | BIGINT | FK → master_simpanan_pinjaman.id | Tipe paket pinjaman |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

**Indexes:**
- users_id, status
- status, tanggal_pencairan

---

### 7. angsuran_pinjaman

**Deskripsi:** Detail cicilan pinjaman per bulan

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID cicilan |
| pinjaman_id | BIGINT | FK → pinjaman.id | Pinjaman induk |
| no_angsuran | INT | NOT NULL | Nomor cicilan (1,2,3...) |
| tanggal_jatuh_tempo | DATE | NOT NULL | Deadline pembayaran |
| pokok | DECIMAL(15,2) | NOT NULL | Cicilan pokok |
| bunga | DECIMAL(15,2) | NOT NULL | Bunga cicilan |
| total_angsuran | DECIMAL(15,2) | NOT NULL | Total (pokok+bunga) |
| status_pembayaran | ENUM('belum', 'sebagian', 'lunas') | DEFAULT 'belum' | Status |
| tanggal_pembayaran | DATE | NULLABLE | Tgl bayar aktual |
| diskon | DECIMAL(15,2) | DEFAULT 0 | Diskon (jika ada) |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

**Indexes:**
- pinjaman_id, no_angsuran (UNIQUE)
- status_pembayaran, tanggal_jatuh_tempo

---

### 8. dokumen

**Deskripsi:** Dokumen verifikasi anggota

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID dokumen |
| users_id | BIGINT | FK → users.id | Pemilik dokumen |
| tipe_dokumen | VARCHAR(100) | NOT NULL | Tipe (KTP, NPWP, dll) |
| file_path | VARCHAR(255) | NOT NULL | Path file di storage |
| tanggal_upload | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Tgl upload |
| status_verifikasi | ENUM('pending', 'disetujui', 'ditolak') | DEFAULT 'pending' | Status verif |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

---

### 9. dokumen_pinjaman

**Deskripsi:** Dokumen pendukung pengajuan pinjaman

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK, AUTO_INCREMENT | ID dokumen |
| pinjaman_id | BIGINT | FK → pinjaman.id | Pinjaman terkait |
| file_path | VARCHAR(255) | NOT NULL | Path file |
| tipe_dokumen | VARCHAR(100) | NOT NULL | Tipe dokumen |
| status_verifikasi | ENUM('pending', 'disetujui', 'ditolak') | DEFAULT 'pending' | Status |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

---

### 10. identitas_koperasi

**Deskripsi:** Informasi identitas koperasi

| Column | Type | Constraint | Keterangan |
|--------|------|-----------|-----------|
| id | BIGINT | PK | ID (biasanya 1) |
| nama_koperasi | VARCHAR(255) | NOT NULL | Nama resmi |
| alamat | TEXT | NOT NULL | Alamat kantor |
| telepon | VARCHAR(20) | NULLABLE | No. telepon |
| email | VARCHAR(255) | NULLABLE | Email koperasi |
| logo | VARCHAR(255) | NULLABLE | Path logo |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Dibuat |
| updated_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Diupdate |

---

## Query Umum

### 1. Total Simpanan Anggota Per Tahun
```sql
SELECT 
    u.id, 
    u.nama, 
    YEAR(sw.created_at) as tahun,
    SUM(sw.nilai) as total_wajib,
    SUM(ss.nilai) as total_sukarela
FROM users u
LEFT JOIN simpanan_wajib sw ON u.id = sw.users_id
LEFT JOIN simpanan_sukarela ss ON u.id = ss.users_id AND ss.status = 'disetujui'
WHERE u.role = 'anggota'
GROUP BY u.id, YEAR(sw.created_at)
ORDER BY u.id, tahun DESC;
```

### 2. Pinjaman yang Belum Lunas
```sql
SELECT 
    p.id,
    u.nama,
    p.jumlah_pinjaman,
    SUM(ap.total_angsuran) as total_harus_bayar,
    SUM(CASE WHEN ap.status_pembayaran = 'lunas' THEN ap.total_angsuran ELSE 0 END) as sudah_bayar
FROM pinjaman p
JOIN users u ON p.users_id = u.id
LEFT JOIN angsuran_pinjaman ap ON p.id = ap.pinjaman_id
WHERE p.status = 'disetujui'
GROUP BY p.id
HAVING sudah_bayar < total_harus_bayar
ORDER BY p.created_at DESC;
```

### 3. Cicilan Jatuh Tempo
```sql
SELECT 
    ap.id,
    u.nama,
    p.jumlah_pinjaman,
    ap.no_angsuran,
    ap.tanggal_jatuh_tempo,
    ap.total_angsuran
FROM angsuran_pinjaman ap
JOIN pinjaman p ON ap.pinjaman_id = p.id
JOIN users u ON p.users_id = u.id
WHERE ap.status_pembayaran != 'lunas'
AND ap.tanggal_jatuh_tempo <= CURDATE()
ORDER BY ap.tanggal_jatuh_tempo ASC;
```

### 4. Dashboard Pengurus - Ringkasan
```sql
SELECT 
    (SELECT COUNT(*) FROM users WHERE role = 'anggota' AND status = 'aktif') as total_anggota,
    (SELECT SUM(nilai) FROM simpanan_wajib WHERE status = 'sudah') as total_simpanan_terkumpul,
    (SELECT COUNT(*) FROM pinjaman WHERE status = 'pending') as pinjaman_pending,
    (SELECT SUM(total_angsuran) FROM angsuran_pinjaman WHERE status_pembayaran != 'lunas') as cicilan_belum_bayar;
```

---

## Best Practices

### 1. Indexing
- Selalu index foreign keys
- Index kolom yang sering difilter (status, tahun, bulan)
- Hindari index berlebihan (query performance impact)

### 2. Data Integrity
- Gunakan FOREIGN KEY constraints
- Set NOT NULL untuk field wajib
- Gunakan UNIQUE constraint untuk email, nip

### 3. Archiving
- Jangan hapus data, gunakan soft delete atau archive
- Tambahkan kolom `deleted_at` untuk soft delete

```sql
ALTER TABLE pinjaman ADD COLUMN deleted_at TIMESTAMP NULL;
```

### 4. Audit Trail
- Track siapa yang mengubah data dan kapan
- Tambahkan kolom `updated_by`

```sql
ALTER TABLE pinjaman ADD COLUMN updated_by BIGINT NULL;
ALTER TABLE pinjaman ADD FOREIGN KEY (updated_by) REFERENCES users(id);
```

---

## Tools untuk Database

### DBeaver (Free GUI)
```
Download: https://dbeaver.io
Fitur: Query builder, ER diagram, data export
```

### Laravel Adminer (Web-based)
```bash
composer require --dev adminer
# Akses: http://localhost:8000/adminer
```

### MySQL Workbench
```
Download: https://www.mysql.com/products/workbench/
Fitur: Visual schema design, reverse engineering
```

---

**Last Updated:** 2025
**Database Version:** MySQL 8.0+
