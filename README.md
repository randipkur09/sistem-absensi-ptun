# 📋 Sistem Absensi PTUN Bandar Lampung

<p align="center">
  <strong>Sistem Informasi Absensi Pegawai Berbasis Web</strong><br>
  Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
</p>

---

## 📖 Deskripsi

**Sistem Absensi PTUN** adalah aplikasi web untuk mengelola kehadiran pegawai di lingkungan Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung. Aplikasi ini mendukung dua jenis pegawai yaitu **Outsourcing** dan **Magang (Internship)**, dengan fitur absensi berbasis **GPS & Geolocation** serta **foto selfie** sebagai bukti kehadiran.

Sistem ini memiliki dua panel utama:
- **Panel Admin** — untuk mengelola data pegawai, memantau kehadiran, mengelola shift satpam, dan menghasilkan laporan.
- **Panel Pegawai** — untuk melakukan absensi harian (check-in & check-out) dan melihat riwayat kehadiran.

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Otorisasi
- Login dengan **username** & password
- Role-based access control (Admin & Pegawai)
- Middleware proteksi route berdasarkan role
- Validasi status akun (akun nonaktif otomatis ditolak saat login)
- Form Request validation (`LoginRequest`)

### 👨‍💼 Panel Admin

- **Dashboard** — Ringkasan statistik kehadiran hari ini (hadir, terlambat, izin, sakit) & data pegawai (total, outsourcing, magang), serta daftar 10 absensi terbaru
- **Manajemen Pegawai Outsourcing** — CRUD lengkap (Create, Read, Update, Delete) dengan data kontrak (perusahaan, jabatan, nomor kontrak, periode kontrak)
- **Manajemen Peserta Magang** — CRUD lengkap dengan data institusi, jurusan, periode magang, dan pembimbing
- **Monitoring Kehadiran** — Lihat detail absensi seluruh pegawai (index & show)
- **Manajemen Shift Satpam** — Master data shift (Pagi, Siang, Malam) & penjadwalan shift mingguan per satpam
- **Laporan Absensi** — Filter berdasarkan periode, pegawai, & tipe pegawai, dengan ringkasan statistik per status
- **Export Laporan** — Export ke format **PDF** (landscape A4) dan **Excel (.xlsx)** dengan auto-generate record alfa
- **Import/Export Data Pegawai** — Import data pegawai dari file Excel (xlsx/xls/csv), export ke Excel
- **Pengaturan Absensi** — Konfigurasi lokasi kantor, radius, jam kerja, dan batas keterlambatan

### 👤 Panel Pegawai

- **Dashboard** — Informasi kehadiran hari ini & statistik pribadi
- **Absensi Masuk (Check-in)** — Dengan pilihan status (hadir/sakit/izin), validasi GPS & radius lokasi, dan foto selfie
- **Absensi Pulang (Check-out)** — Dengan validasi GPS, radius lokasi, dan foto selfie
- **Riwayat Kehadiran** — Histori absensi lengkap

### 📍 Fitur Geolocation
- Validasi lokasi menggunakan **rumus Haversine** (perhitungan jarak dua titik koordinat)
- Konfigurasi **radius maksimal** dari lokasi kantor (default: 50 meter)
- Penyimpanan koordinat latitude & longitude saat absensi (masuk & pulang)
- Perhitungan jarak pegawai dari kantor secara real-time
- Validasi radius hanya untuk status **hadir** (status sakit/izin tidak perlu validasi lokasi)

### 📸 Foto Selfie
- Capture foto melalui kamera perangkat (base64 encoding)
- Mendukung format PNG, JPEG, dan WebP
- Penyimpanan foto terpisah untuk absensi masuk & pulang
- Format nama file: `{tipe}_{userId}_{timestamp}.png`
- Disimpan menggunakan custom disk `attendance_photos`

### ⏰ Manajemen Shift (Satpam)
- **Master Shift** — CRUD data shift dengan konfigurasi jam masuk, jam pulang, dan batas keterlambatan per shift
- **Jadwal Shift Mingguan** — Penjadwalan shift per satpam per hari dalam tampilan kalender mingguan
- **Bulk Schedule** — Simpan jadwal shift massal untuk satu minggu sekaligus
- **Integrasi Absensi** — Satpam menggunakan jam shift masing-masing, bukan jam kantor global
- **Validasi Hari Libur** — Satpam tanpa jadwal shift dianggap libur dan tidak bisa absen

### 📊 Auto-Generate Alfa
- Artisan command `attendance:generate-alfa` untuk otomatis membuat record **alfa** bagi pegawai yang tidak absen pada hari kerja
- Mendukung generate untuk tanggal spesifik, range tanggal, atau default kemarin
- Memperhatikan hari kerja (Senin–Jumat untuk non-satpam, berdasarkan jadwal shift untuk satpam)
- Memperhatikan masa aktif kontrak/magang pegawai
- Otomatis dipanggil saat membuka halaman laporan dan saat export

---

## 🛠️ Tech Stack

| Komponen        | Teknologi                                                    |
| --------------- | ------------------------------------------------------------ |
| **Framework**   | Laravel 10.x                                                 |
| **Bahasa**      | PHP 8.1+                                                     |
| **Database**    | MySQL                                                        |
| **Frontend**    | Blade Templates, Vite 5.x                                    |
| **PDF Export**  | [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) |
| **Excel I/O**   | [Maatwebsite Excel 3.x](https://laravel-excel.com/)          |
| **Auth Token**  | Laravel Sanctum                                              |
| **HTTP Client** | Guzzle                                                       |

---

## 📂 Struktur Proyek

```
sistem-absensi-ptun/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── GenerateAlfa.php        # Command generate record alfa
│   ├── Exports/                        # Export classes (Excel/PDF)
│   │   ├── AttendanceExport.php        #   Export data absensi
│   │   └── UserExport.php              #   Export data pegawai
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  # Controller panel admin
│   │   │   │   ├── AttendanceController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── InternshipController.php
│   │   │   │   ├── OutsourcingController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   ├── SettingController.php
│   │   │   │   └── ShiftController.php     # Manajemen shift satpam
│   │   │   ├── Employee/               # Controller panel pegawai
│   │   │   │   ├── AttendanceController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── HistoryController.php
│   │   │   └── Auth/
│   │   │       └── LoginController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php      # Middleware cek role
│   │   └── Requests/                   # Form Request Validation
│   │       ├── LoginRequest.php
│   │       ├── StoreAttendanceRequest.php
│   │       ├── StoreInternshipRequest.php
│   │       ├── StoreOutsourcingRequest.php
│   │       ├── StorePermissionRequest.php
│   │       ├── UpdateInternshipRequest.php
│   │       ├── UpdateOutsourcingRequest.php
│   │       └── UpdateSettingRequest.php
│   ├── Imports/
│   │   └── UserImport.php              # Import data pegawai dari Excel
│   ├── Models/
│   │   ├── Attendance.php
│   │   ├── AttendanceSetting.php
│   │   ├── InternshipParticipant.php
│   │   ├── OutsourcingEmployee.php
│   │   ├── Role.php
│   │   ├── Shift.php                   # Model master shift
│   │   ├── ShiftSchedule.php           # Model jadwal shift per user
│   │   └── User.php
│   └── Providers/
├── database/
│   ├── migrations/                     # Migrasi database
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       ├── AttendanceSettingSeeder.php
│       └── AdminSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── attendance/             # View monitoring kehadiran
│       │   ├── dashboard.blade.php     # View dashboard admin
│       │   ├── internship/             # View CRUD magang
│       │   ├── outsourcing/            # View CRUD outsourcing
│       │   ├── reports/                # View laporan & export
│       │   ├── settings/               # View pengaturan absensi
│       │   └── shifts/                 # View manajemen shift satpam
│       ├── employee/
│       │   ├── attendance/             # View halaman absensi
│       │   ├── dashboard.blade.php     # View dashboard pegawai
│       │   └── history/                # View riwayat kehadiran
│       ├── auth/                       # View halaman login
│       └── layouts/                    # Layout template
├── routes/
│   └── web.php                         # Definisi route
├── public/                             # Asset publik
└── storage/                            # File upload & cache
```

---

## 🗄️ Skema Database

### Tabel `users`
| Kolom           | Tipe                       | Keterangan                    |
| --------------- | -------------------------- | ----------------------------- |
| id              | bigint (PK)                | Primary key                   |
| role_id         | bigint (FK → roles)        | Relasi ke tabel roles         |
| name            | varchar                    | Nama lengkap                  |
| username        | varchar (unique)           | Username untuk login          |
| email           | varchar (nullable)         | Email (opsional)              |
| password        | varchar                    | Password (hashed)             |
| employee_type   | enum: outsourcing, magang  | Jenis pegawai (nullable)      |
| phone           | varchar(20)                | Nomor telepon                 |
| address         | text                       | Alamat                        |
| photo           | varchar                    | Path foto profil              |
| status          | enum: aktif, nonaktif      | Status akun                   |

### Tabel `roles`
| Kolom      | Tipe        | Keterangan   |
| ---------- | ----------- | ------------ |
| id         | bigint (PK) | Primary key  |
| name       | varchar     | Nama role    |
| guard_name | varchar     | Guard name   |

### Tabel `attendances`
| Kolom            | Tipe                                        | Keterangan                         |
| ---------------- | ------------------------------------------- | ---------------------------------- |
| id               | bigint (PK)                                 | Primary key                        |
| user_id          | bigint (FK → users)                         | Relasi ke user                     |
| shift_id         | bigint (FK → shifts, nullable)              | Relasi ke shift (untuk satpam)     |
| tanggal          | date                                        | Tanggal absensi                    |
| jam_masuk        | time                                        | Waktu check-in                     |
| jam_pulang       | time                                        | Waktu check-out                    |
| latitude_masuk   | decimal(10,7)                               | Latitude saat masuk                |
| longitude_masuk  | decimal(10,7)                               | Longitude saat masuk               |
| latitude_pulang  | decimal(10,7)                               | Latitude saat pulang               |
| longitude_pulang | decimal(10,7)                               | Longitude saat pulang              |
| jarak_masuk      | decimal(10,2)                               | Jarak dari kantor saat masuk (m)   |
| jarak_pulang     | decimal(10,2)                               | Jarak dari kantor saat pulang (m)  |
| foto_masuk       | varchar                                     | Nama file foto check-in            |
| foto_pulang      | varchar                                     | Nama file foto check-out           |
| status           | enum: hadir, terlambat, izin, sakit, alfa   | Status kehadiran                   |
| keterangan       | text                                        | Catatan tambahan                   |

> **Constraint**: Kombinasi `user_id` + `tanggal` bersifat **unique** (satu user hanya bisa absen satu kali per hari).

### Tabel `attendance_settings`
| Kolom              | Tipe          | Keterangan                        |
| ------------------ | ------------- | --------------------------------- |
| id                 | bigint (PK)   | Primary key                       |
| office_latitude    | decimal(10,7) | Latitude lokasi kantor            |
| office_longitude   | decimal(10,7) | Longitude lokasi kantor           |
| office_name        | varchar       | Nama kantor                       |
| office_address     | text          | Alamat kantor                     |
| max_radius_meters  | integer       | Radius maksimal absensi (meter)   |
| jam_masuk_start    | time          | Jam mulai absensi masuk           |
| jam_masuk_end      | time          | Jam akhir absensi masuk           |
| jam_pulang         | time          | Jam pulang                        |
| batas_terlambat    | time          | Batas waktu sebelum dianggap terlambat |

### Tabel `shifts`
| Kolom            | Tipe          | Keterangan                                 |
| ---------------- | ------------- | ------------------------------------------ |
| id               | bigint (PK)   | Primary key                                |
| name             | varchar       | Nama shift (Shift Pagi, Siang, Malam)      |
| jam_masuk_start  | time          | Jam mulai boleh absen masuk                |
| jam_masuk_end    | time          | Jam akhir boleh absen masuk                |
| batas_terlambat  | time          | Batas jam dianggap terlambat               |
| jam_pulang       | time          | Jam pulang shift                           |
| is_active        | boolean       | Status aktif shift (default: true)         |

### Tabel `shift_schedules`
| Kolom    | Tipe                 | Keterangan                    |
| -------- | -------------------- | ----------------------------- |
| id       | bigint (PK)          | Primary key                   |
| user_id  | bigint (FK → users)  | Satpam yang dijadwalkan       |
| shift_id | bigint (FK → shifts) | Shift yang diterapkan         |
| tanggal  | date                 | Tanggal jadwal                |

> **Constraint**: Kombinasi `user_id` + `tanggal` bersifat **unique** (satu satpam hanya 1 shift per hari).

### Tabel `outsourcing_employees`
| Kolom           | Tipe                | Keterangan               |
| --------------- | ------------------- | ------------------------ |
| id              | bigint (PK)         | Primary key              |
| user_id         | bigint (FK → users) | Relasi ke user           |
| company_name    | varchar             | Nama perusahaan          |
| position        | varchar             | Jabatan/posisi           |
| contract_start  | date                | Tanggal mulai kontrak    |
| contract_end    | date                | Tanggal akhir kontrak    |
| contract_number | varchar             | Nomor kontrak            |

### Tabel `internship_participants`
| Kolom      | Tipe                | Keterangan            |
| ---------- | ------------------- | --------------------- |
| id         | bigint (PK)         | Primary key           |
| user_id    | bigint (FK → users) | Relasi ke user        |
| institution| varchar             | Institusi asal        |
| major      | varchar             | Jurusan/program studi |
| start_date | date                | Tanggal mulai magang  |
| end_date   | date                | Tanggal akhir magang  |
| supervisor | varchar             | Nama pembimbing       |

---

## ⚙️ Persyaratan Sistem

- **PHP** >= 8.1
- **Composer** >= 2.x
- **MySQL** >= 5.7 / MariaDB >= 10.3
- **Node.js** >= 18.x & **NPM** >= 9.x
- **Web Server**: Apache / Nginx (atau gunakan Laragon / XAMPP)
- **Ekstensi PHP**: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/Imagick

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/randipkur09/sistem-absensi-ptun.git
cd sistem-absensi-ptun
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Frontend

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
APP_NAME="Sistem Absensi PTUN"
APP_URL=http://localhost/sistem-absensi-ptun/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_absensi_ptun
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database

Buat database MySQL dengan nama sesuai konfigurasi `.env`:

```sql
CREATE DATABASE sistem_absensi_ptun;
```

### 6. Jalankan Migrasi & Seeder

```bash
php artisan migrate --seed
```

Seeder akan membuat:
- **2 Role**: `admin` dan `pegawai`
- **1 Akun Admin** default (username: `admin`)
- **1 Pengaturan Absensi** default (lokasi PTUN Bandar Lampung)

### 7. Buat Storage Link

```bash
php artisan storage:link
```

### 8. Build Asset Frontend

```bash
# Development (dengan hot reload)
npm run dev

# Production
npm run build
```

### 9. Jalankan Server

```bash
php artisan serve
```

Atau jika menggunakan Laragon, akses langsung melalui:
```
http://sistem-absensi-ptun.test
```

---

## 🔑 Akun Default

| Role      | Username   | Email (opsional)                   | Password      |
| --------- | ---------- | ---------------------------------- | ------------- |
| **Admin** | `admin`    | `admin@ptun-bandarlampung.go.id`   | `password123` |

> ⚠️ **Penting**: Segera ganti password default setelah instalasi pertama!

---

## 📋 Panduan Penggunaan

### Sebagai Admin

1. **Login** dengan username & password admin
2. **Dashboard** — Lihat ringkasan kehadiran hari ini (hadir, terlambat, izin, sakit) & statistik pegawai
3. **Kelola Outsourcing** — Tambah/edit/hapus data pegawai outsourcing beserta data kontrak
4. **Kelola Magang** — Tambah/edit/hapus data peserta magang beserta data institusi
5. **Monitor Kehadiran** — Pantau absensi seluruh pegawai, lihat detail per absensi
6. **Kelola Shift Satpam** — Buat master shift (Pagi, Siang, Malam), atur jadwal shift mingguan per satpam
7. **Laporan** — Filter berdasarkan periode/pegawai/tipe, lihat ringkasan statistik, export ke PDF/Excel
8. **Pengaturan** — Konfigurasi lokasi kantor, radius, dan jam kerja
9. **Import/Export** — Import data pegawai dari Excel atau export ke Excel

### Sebagai Pegawai

1. **Login** dengan username & password pegawai
2. **Dashboard** — Lihat status kehadiran hari ini & statistik pribadi
3. **Absensi Masuk** — Pilih status (hadir/sakit/izin), izinkan akses kamera & lokasi, ambil foto selfie, lalu submit
4. **Absensi Pulang** — Izinkan akses kamera & lokasi, ambil foto selfie, lalu submit
5. **Riwayat** — Lihat histori kehadiran lengkap

### Khusus Satpam (Outsourcing dengan posisi Satpam)

- Jam kerja mengikuti **jadwal shift** yang sudah ditetapkan admin, bukan jam kantor global
- Tidak bisa melakukan absensi jika **tidak memiliki jadwal shift** di hari tersebut (dianggap libur)
- Status terlambat dihitung berdasarkan **batas terlambat shift** yang bersangkutan

---

## 🔒 Pengaturan Absensi Default

| Parameter          | Nilai Default                   |
| ------------------ | ------------------------------- |
| Nama Kantor        | PTUN Bandar Lampung             |
| Alamat             | Jl. Pangeran Emir M. Noer No.73, Durian Payung, Kec. Tanjung Karang Pusat |
| Latitude           | -5.4245573                      |
| Longitude          | 105.2437446                     |
| Radius Maksimal    | 50 meter                        |
| Jam Masuk (mulai)  | 08:00                           |
| Jam Masuk (akhir)  | 08:15                           |
| Jam Pulang         | 16:00                           |
| Batas Terlambat    | 08:15                           |

> Semua parameter di atas dapat diubah melalui menu **Pengaturan** di panel Admin.
> Untuk pegawai satpam, jam kerja mengikuti konfigurasi shift masing-masing.

---

## 🗺️ Alur Absensi

### Pegawai Umum (Outsourcing Non-Satpam & Magang)

```
┌─────────────┐     ┌──────────────┐     ┌──────────────┐     ┌───────────────┐
│  Pegawai     │────▶│  Buka Halaman│────▶│  Pilih Status│────▶│  Izinkan      │
│  Login       │     │  Absensi     │     │  (Hadir/     │     │  GPS & Kamera │
│              │     │              │     │  Sakit/Izin) │     │               │
└─────────────┘     └──────────────┘     └──────────────┘     └───────┬───────┘
                                                                       │
                                                                       ▼
┌─────────────┐     ┌──────────────┐     ┌──────────────┐     ┌───────────────┐
│  Absensi    │◀────│  Tentukan    │◀────│  Validasi    │◀────│  Ambil Foto   │
│  Tercatat   │     │  Status      │     │  Radius      │     │  Selfie &     │
│  ✅         │     │  Hadir/Telat │     │  (≤ 50m)*    │     │  Submit       │
└─────────────┘     └──────────────┘     └──────────────┘     └───────────────┘

* Validasi radius hanya berlaku untuk status "Hadir"
```

### Pegawai Satpam (Berbasis Shift)

```
┌─────────────┐     ┌──────────────┐     ┌──────────────┐     ┌───────────────┐
│  Satpam     │────▶│  Cek Jadwal  │────▶│  Ada Shift   │────▶│  Proses       │
│  Login      │     │  Shift Hari  │     │  Hari Ini?   │     │  Absensi      │
│             │     │  Ini         │     │              │     │  (sama seperti│
└─────────────┘     └──────────────┘     └──────┬───────┘     │  pegawai umum)│
                                                │              └───────────────┘
                                                │ Tidak
                                                ▼
                                         ┌──────────────┐
                                         │  Libur       │
                                         │  (tidak bisa │
                                         │  absen)      │
                                         └──────────────┘
```

---

## 📊 Artisan Commands

### Generate Record Alfa

Otomatis membuat record absensi berstatus **alfa** untuk pegawai yang tidak melakukan absensi pada hari kerja.

```bash
# Generate untuk kemarin (default)
php artisan attendance:generate-alfa

# Generate untuk tanggal spesifik
php artisan attendance:generate-alfa --date=2026-06-30

# Generate untuk range tanggal
php artisan attendance:generate-alfa --from=2026-06-01 --to=2026-06-30
```

> **Catatan**: Command ini juga otomatis dipanggil saat admin membuka halaman **Laporan** dan saat melakukan **export** (PDF/Excel), sehingga data alfa selalu up-to-date.

**Logika Generate Alfa:**
- Hanya untuk pegawai dengan status **aktif**
- Pegawai non-satpam: hanya hari kerja (Senin–Jumat)
- Pegawai satpam: hanya hari yang memiliki **jadwal shift**
- Memperhatikan **masa aktif** kontrak outsourcing atau periode magang
- Tidak membuat duplikat jika record sudah ada

---

## 🧪 Menjalankan Tests

```bash
php artisan test
```

Atau menggunakan PHPUnit secara langsung:

```bash
./vendor/bin/phpunit
```

---

## 📝 Catatan Pengembangan

- **Foto absensi** disimpan di `storage/app/public/attendance-photos/`
- **Foto profil** disimpan di `storage/app/public/`
- Pastikan direktori `storage` memiliki permission yang sesuai
- Gunakan `php artisan storage:link` untuk membuat symbolic link ke `public/storage`
- Aplikasi menggunakan **Vite** sebagai build tool untuk asset frontend
- Login menggunakan **username** (bukan email). Field email bersifat opsional.
- Fitur shift hanya berlaku untuk pegawai outsourcing dengan posisi **Satpam**
- Record **alfa** di-generate secara otomatis, tidak perlu input manual

---

## 🔄 API Endpoint (Internal)

Berikut daftar route yang tersedia dalam aplikasi:

### Auth
| Method | URI                  | Keterangan             |
| ------ | -------------------- | ---------------------- |
| GET    | `/login`             | Halaman login          |
| POST   | `/login`             | Proses login           |
| POST   | `/logout`            | Proses logout          |

### Admin (`/admin/*`)
| Method   | URI                              | Keterangan                          |
| -------- | -------------------------------- | ----------------------------------- |
| GET      | `/admin/dashboard`               | Dashboard admin                     |
| Resource | `/admin/outsourcing`             | CRUD pegawai outsourcing            |
| Resource | `/admin/internship`              | CRUD peserta magang                 |
| GET      | `/admin/attendance`              | Daftar kehadiran                    |
| GET      | `/admin/attendance/{id}`         | Detail kehadiran                    |
| GET      | `/admin/shifts`                  | Halaman manajemen shift             |
| POST     | `/admin/shifts`                  | Tambah shift baru                   |
| PUT      | `/admin/shifts/{id}`             | Update shift                        |
| DELETE   | `/admin/shifts/{id}`             | Hapus shift                         |
| POST     | `/admin/shifts/schedule`         | Simpan jadwal shift satpam          |
| POST     | `/admin/shifts/schedule/bulk`    | Simpan jadwal shift massal          |
| DELETE   | `/admin/shifts/schedule/{id}`    | Hapus jadwal shift                  |
| GET      | `/admin/reports`                 | Halaman laporan                     |
| GET      | `/admin/reports/export-pdf`      | Export laporan ke PDF               |
| GET      | `/admin/reports/export-excel`    | Export laporan ke Excel             |
| GET      | `/admin/settings`                | Halaman pengaturan                  |
| PUT      | `/admin/settings`                | Update pengaturan                   |
| GET      | `/admin/export-users/{type}`     | Export data pegawai ke Excel        |
| POST     | `/admin/import-users/{type}`     | Import data pegawai dari Excel      |

### Employee (`/employee/*`)
| Method | URI                              | Keterangan                     |
| ------ | -------------------------------- | ------------------------------ |
| GET    | `/employee/dashboard`            | Dashboard pegawai              |
| GET    | `/employee/attendance`           | Halaman absensi                |
| POST   | `/employee/attendance/check-in`  | Proses check-in (JSON)        |
| POST   | `/employee/attendance/check-out` | Proses check-out (JSON)       |
| GET    | `/employee/history`              | Riwayat kehadiran              |

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan internal **Pengadilan Tata Usaha Negara (PTUN) Bandar Lampung**.

---

<p align="center">
  Dibuat dengan ❤️ untuk PTUN Bandar Lampung
</p>
