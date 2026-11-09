# Akadema: Admin Dashboard dan RBAC

Tugas PBKK Pertemuan 10 (Admin Dashboard dan Role-Based Access Control), Laravel + Breeze + Spatie Permission + AdminLTE.

**Arya Rangga Putra Pratama** · NRP 5025241072 · Teknik Informatika · Institut Teknologi Sepuluh Nopember (ITS)

![Dashboard admin](docs/dashboard.png)

> Ganti `docs/dashboard.png` dengan screenshot beranda dashboard kamu sendiri (syarat README di slide 43).
> Setelah menjalankan proyek, login sebagai Admin, buka `/admin/dashboard`, lalu simpan screenshot ke folder `docs/`.

## Akun Uji Coba

Semua akun berpassword `password`. Di halaman login ada tombol **Auto-Login** (Admin, Staf, Pengguna) untuk mempermudah demo.

| Role | Email | Akses |
|---|---|---|
| Admin | `admin@aryarangga.test` | Penuh: melihat semua data, menghapus data, halaman Pengguna dan Role |
| Staf | `staf@aryarangga.test` | Terbatas: hanya melihat dashboard, mahasiswa, dosen, dan tugas. Tidak bisa menghapus |
| Pengguna biasa | `pengguna@aryarangga.test` | Tanpa role: diarahkan ke beranda publik, panel admin ditolak (403) |

> Tombol Auto-Login hanya aktif saat `APP_ENV=local`. Di production route-nya menjawab 404.

## Menjalankan Proyek

Prasyarat: PHP 8.2+ (ekstensi sqlite, mbstring, xml, zip), Composer, Node.js.

Cara cepat (Windows PowerShell), dari folder kit:

```powershell
powershell -ExecutionPolicy Bypass -File .\setup.ps1
cd ..\arya-portal
php artisan serve
```

Cara manual (jika proyek Laravel sudah ada):

```bash
composer require spatie/laravel-permission jeroennoten/laravel-adminlte
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan adminlte:install
# salin isi folder files/ ke root proyek (timpa file yang sama)
php artisan migrate:fresh --seed
npm install && npm run build
php artisan serve
```

Buka http://127.0.0.1:8000, lalu klik salah satu tombol Auto-Login.

## Pemenuhan Spesifikasi Tugas

| Syarat (slide 40) | Implementasi |
|---|---|
| 1. Integrasi UI backoffice | Semua halaman admin memakai `@extends('adminlte::page')` (`resources/views/admin/*`) |
| 2. Autentikasi | Laravel Breeze. Panel admin ada di bawah middleware `auth` dan `verified` |
| 3. Otorisasi database Spatie | Role `admin` dan `staf` serta 7 permission tersimpan di tabel Spatie (`RolePermissionSeeder`) |
| 4. Minimal 3 widget agregat | 4 widget: `Mahasiswa::count()`, `Dosen::count()`, `Tugas::count()`, dan mahasiswa aktif (`app/Http/Controllers/Admin/DashboardController.php`) |
| 5. Pengaman controller | Middleware `permission:` di setiap controller admin lewat `HasMiddleware` (pengganti constructor middleware di Laravel 11 ke atas) |

Hak eksklusif admin: tombol Hapus hanya dirender lewat `@can('hapus-mahasiswa')` / `@can('hapus-tugas')` di Blade, dan eksekusi `destroy` dikunci `permission:hapus-*` di controller sehingga request manual lewat Postman tetap ditolak 403.

Redirect setelah login: `DashboardRedirectController` mengecek role Spatie. Admin dan staf ke `/admin/dashboard`, selain itu ke `/beranda`. Seluruh grup `/admin` dikunci `role:admin|staf`, dan `/admin/pengguna` dikunci `role:admin`.

### Tantangan Tambahan (Nilai Plus)

- **Pencarian dan filter `when()`**: tabel mahasiswa (nama/NRP, departemen, status), dosen, dan tugas.
- **Pengunci pagination `withQueryString()`**: filter tetap aktif saat pindah halaman.
- **Auto-seeder Faker**: `AkademikSeeder` mengisi 6 departemen, 61 mahasiswa, 12 dosen, dan 25 tugas dengan Faker `id_ID`.
- **Auto-login test accounts**: tiga tombol di halaman login.

## Mengganti Nama Brand dan Logo

- **Nama brand** (teks di sidebar, judul tab, halaman login): ubah `APP_NAME="Akadema"` di file `.env`, lalu jalankan `php artisan config:clear`.
- **Logo**: simpan file logo dengan nama `logo.png` (atau `logo.svg`, `logo.webp`, `logo.jpg`) di folder `public/img/`. Otomatis dipakai di sidebar, preloader, dan halaman login. Hapus file itu untuk kembali ke logo bawaan (`logo-default.svg`). Ukuran terbaik: persegi, minimal 128 x 128 piksel.

## Tema dan Transisi

Nuansa putih dengan aksen biru, cyan, pink, oranye, hijau, dan ungu. Semua efek tanpa library tambahan (`public/css/arya-theme.css`, `public/js/arya-theme.js`):

- Preloader logo saat halaman dimuat dan fade-in halaman.
- Fade-out saat berpindah halaman.
- Kartu dan widget muncul bertahap (fade-up dan pop), terangkat saat di-hover.
- Angka widget menghitung naik dari nol (count-up).
- Grafik batang per departemen tumbuh dari kiri dengan jeda bertahap.
- Menu sidebar bergeser dan ikon berputar saat di-hover, item aktif bergradien.
- Efek kilau di header, logo melayang, dan latar login dengan gumpalan warna bergerak.
- Baris tabel muncul bertahap, dan meluncur keluar sebelum data dihapus.
- Tombol terangkat, memiliki efek ripple, dan alert sukses menghilang dengan fade.
- Menghormati `prefers-reduced-motion`.

## Pengujian

```bash
php artisan test
```

`tests/Feature/AdminAccessTest.php` menguji: tamu ditolak, user tanpa role dapat 403, redirect sesuai role, staf tidak bisa menghapus, admin bisa menghapus, halaman Pengguna khusus admin, dan pencarian nama/NRP.

## Keamanan Git

`.env` dan folder `vendor/` sudah dikecualikan oleh `.gitignore` bawaan Laravel. Jangan pernah meng-commit `.env`.

## Struktur File Utama

```
app/Http/Controllers/Admin/      Dashboard, Mahasiswa, Dosen, Tugas, Pengguna
app/Http/Controllers/            DashboardRedirectController, DemoLoginController
app/Models/                      User (HasRoles), Departemen, Mahasiswa, Dosen, Tugas
app/Providers/AppServiceProvider.php   menu dan pengaturan AdminLTE
bootstrap/app.php                alias middleware role / permission
database/migrations/             departemen, mahasiswa, dosen, tugas
database/seeders/                RolePermission, AkunDemo, Akademik (Faker)
resources/views/admin/           halaman AdminLTE
resources/views/auth/login.blade.php   login beranimasi + Auto-Login
public/css/arya-theme.css        tema putih cerah dan animasi
public/js/arya-theme.js          count-up, transisi halaman, ripple, hapus beranimasi
routes/web.php                   grup /admin dengan role:admin|staf
tests/Feature/AdminAccessTest.php
```

## Beranda Publik dan Slot Foto

Halaman `/` adalah beranda publik (hero, fitur, tentang, statistik, CTA, footer). Semua foto masih berupa kotak kosong bertuliskan "Tambah ... di sini".

Untuk mengisi, simpan foto Teknik Informatika ITS ke folder `public/img/landing/` dengan nama persis seperti ini (format `.jpg`, `.jpeg`, `.png`, atau `.webp`). Kotak otomatis berganti jadi foto, tanpa mengubah kode:

| Nama file | Posisi |
|---|---|
| `hero` | Background besar di bagian atas (sisi kanan) |
| `tentang` | Foto di bagian Tentang Portal |
| `fitur-1` sampai `fitur-5` | Gambar kartu fitur |
| `cta` | Foto di bagian ajakan Mulai Sekarang |

Angka statistik di beranda diambil dari database. Nama brand mengikuti `APP_NAME` di `.env`.
