# Aplikasi Kurikulum S1 Manajemen FEB UNM

Sistem Informasi Kurikulum berbasis OBE (Outcome-Based Education) untuk Program Studi S1 Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar.

## Fitur Utama

- **Portal Publik** — Beranda, Mata Kuliah, CPL, dan Matriks MK–CPL yang dapat diakses tanpa login
- **Panel Admin** — Manajemen data kurikulum lengkap dengan autentikasi & role-based access
- **12 Tabel Kurikulum** — Matriks MK-CPL (1–5), CPMK (6–7), Sub-CPMK (8–10), Bobot (11–12)
- **Input CPMK & Sub-CPMK** — Per mata kuliah dengan keterkaitan CPL dan CLO Taxonomy
- **Bobot Asesmen** — Konfigurasi bobot penilaian per Sub-CPMK
- **RPS** — Rencana Pembelajaran Semester dengan alur review & persetujuan
- **Dashboard Kelengkapan** — Monitoring progress pengisian kurikulum per dosen & semester

## Stack Teknologi

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: Tailwind CSS, Alpine.js, Vite
- **Auth & Role**: Laravel Breeze + Spatie Laravel Permission

## Instalasi Lokal

```bash
# 1. Clone repository
git clone https://github.com/ridhoachmad712/kurikulum-app.git
cd kurikulum-app

# 2. Install PHP dependencies
composer install

# 3. Install & build frontend assets
npm install && npm run build

# 4. Salin & konfigurasi environment
cp .env.example .env
php artisan key:generate

# 5. Atur koneksi database di .env, lalu jalankan migrasi
php artisan migrate --seed

# 6. Buat symlink storage
php artisan storage:link

# 7. Jalankan server
php artisan serve
```

Buka `http://localhost:8000`

## Instalasi di Hosting (Shared Hosting / cPanel)

```bash
# 1. Upload semua file ke folder public_html (atau subdomain)
# 2. Arahkan document root ke folder public/

# 3. Di terminal hosting / SSH:
composer install --no-dev --optimize-autoloader

# 4. Salin & isi .env
cp .env.example .env
# Edit .env: isi APP_URL, APP_KEY, DB_*, APP_ENV=production, APP_DEBUG=false

# 5. Generate key
php artisan key:generate

# 6. Migrasi & seed
php artisan migrate --seed

# 7. Symlink storage
php artisan storage:link

# 8. Cache untuk performa
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> **Catatan**: Asset CSS/JS (`public/build`) sudah disertakan dalam repo sehingga tidak perlu menjalankan Node.js di server.

## Akun Demo (setelah seeding)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@kurikulum.id | password |
| Kaprodi | kaprodi@kurikulum.id | password |
| Dosen | dosen@kurikulum.id | password |

> ⚠️ **Segera ganti password** setelah login pertama kali di menu Profil.

## Struktur Role

| Role | Akses |
|------|-------|
| **Admin** | Full akses semua fitur + kelola user & penugasan |
| **Kaprodi** | Kelola master data, review & approve RPS |
| **Dosen** | Input CPMK, Sub-CPMK, Asesmen, dan RPS mata kuliah yang ditugaskan |

## Lisensi

Dikembangkan untuk keperluan internal Program Studi S1 Manajemen FEB UNM.
