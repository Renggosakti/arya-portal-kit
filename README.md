# Akadema: Admin Dashboard dan RBAC

Ini tugas PBKK Pertemuan 10 (Admin Dashboard dan Role-Based Access Control) yang kukerjakan dengan Laravel, Breeze, Spatie Permission, dan AdminLTE.

**Arya Rangga Putra Pratama** · NRP 5025241072 · Teknik Informatika · Institut Teknologi Sepuluh Nopember (ITS)

![Dashboard admin](docs/dashboard.png)

> Gambar di atas masih tempat kosong. Setelah proyek jalan, aku login sebagai Admin, membuka `/admin/dashboard`, lalu menyimpan screenshot-nya ke `docs/dashboard.png` (syarat README di slide 43).

## Yang Kubuat

Akadema adalah portal data akademik Teknik Informatika ITS. Ada beranda publik, halaman login, dan panel admin yang dikunci berdasarkan role. Aku memakai satu keluarga tampilan di seluruh halaman: navy, emas, dan krem, dengan judul huruf serif, supaya beranda dan panel admin terasa satu aplikasi.

## Akun Uji Coba

Semua akun berpassword `password`. Di halaman login ada tombol **Auto-Login** (Admin, Staf, Pengguna) supaya aku mudah demo.

| Role | Email | Akses |
|---|---|---|
| Admin | `admin@aryarangga.test` | Penuh: melihat semua data, menghapus data, halaman Pengguna dan Role |
| Staf | `staf@aryarangga.test` | Terbatas: hanya melihat dashboard, mahasiswa, dosen, dan tugas. Tidak bisa menghapus |
| Pengguna biasa | `pengguna@aryarangga.test` | Tanpa role: diarahkan ke halaman beranda pengguna, panel admin ditolak (403) |

> Tombol Auto-Login hanya aktif saat `APP_ENV=local`. Di production route-nya menjawab 404.

## Cara Menjalankan

Prasyarat: PHP 8.2+ (ekstensi sqlite, mbstring, xml, zip), Composer, Node.js.

Cara cepat di Windows PowerShell, dari folder kit yang berisi `setup.ps1`:

```powershell
Set-ExecutionPolicy -Scope Process Bypass
.\setup.ps1
cd ..\arya-portal
php artisan serve
```

Proyek Laravel-nya dibuat di folder induk kit, bernama `arya-portal`.

Kalau proyek Laravel sudah ada, aku bisa memasangnya manual:

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

Lalu aku buka http://127.0.0.1:8000. Beranda publik tampil dulu, dan tombol Masuk Portal membawaku ke login.

## Bagaimana Aku Memenuhi Spesifikasi

| Syarat (slide 40) | Yang kulakukan |
|---|---|
| 1. Integrasi UI backoffice | Semua halaman admin memakai `@extends('adminlte::page')` (`resources/views/admin/*`) |
| 2. Autentikasi | Laravel Breeze. Panel admin ada di bawah middleware `auth` dan `verified` |
| 3. Otorisasi database Spatie | Role `admin` dan `staf` serta 7 permission tersimpan di tabel Spatie (`RolePermissionSeeder`) |
| 4. Minimal 3 widget agregat | 4 widget: `Mahasiswa::count()`, `Dosen::count()`, `Tugas::count()`, dan mahasiswa aktif (`app/Http/Controllers/Admin/DashboardController.php`) |
| 5. Pengaman controller | Middleware `permission:` di setiap controller admin lewat `HasMiddleware` (pengganti constructor middleware di Laravel 11 ke atas) |

Hak eksklusif admin: tombol Hapus hanya dirender lewat `@can('hapus-mahasiswa')` dan `@can('hapus-tugas')` di Blade. Eksekusi `destroy` juga dikunci `permission:hapus-*` di controller, jadi request manual lewat Postman tetap ditolak 403.

Redirect setelah login: `DashboardRedirectController` mengecek role Spatie. Admin dan staf ke `/admin/dashboard`, selain itu ke `/beranda`. Seluruh grup `/admin` dikunci `role:admin|staf`, dan `/admin/pengguna` dikunci `role:admin`.

### Tantangan Tambahan (Nilai Plus)

- **Pencarian dan filter `when()`**: tabel mahasiswa (nama atau NRP, departemen, status), dosen, dan tugas.
- **Pagination `withQueryString()`**: filter tetap aktif saat pindah halaman.
- **Auto-seeder Faker**: `AkademikSeeder` mengisi 6 departemen, 61 mahasiswa, 12 dosen, dan 25 tugas dengan Faker `id_ID`.
- **Auto-login test accounts**: tiga tombol di halaman login.

## Beranda Publik dan Foto Teknik Informatika ITS

Halaman `/` adalah beranda publik: hero, fitur, tentang, statistik, ajakan, dan footer. Foto latar sengaja kubiarkan kosong, berupa kotak bertuliskan "Tambah ... di sini" (kartu Fitur Portal memakai ilustrasi ikon, jadi tidak perlu foto), karena foto Teknik Informatika ITS akan kutambahkan sendiri.

Untuk mengisinya, aku menyimpan foto ke folder `public/img/landing/` dengan nama persis seperti di tabel (format `.jpg`, `.jpeg`, `.png`, atau `.webp`). Kotak kosong otomatis berganti jadi fotonya tanpa mengubah kode.

| Nama file | Posisi |
|---|---|
| `hero` | Background besar di bagian atas (sisi kanan) |
| `tentang` | Foto di bagian Tentang Portal |
| `cta` | Foto di bagian ajakan Mulai Sekarang |

Angka statistik di beranda diambil langsung dari database.

## Mengganti Nama Brand dan Logo

- **Nama brand** (sidebar, judul tab, login, beranda): aku ubah `APP_NAME="Akadema"` di `.env`, lalu jalankan `php artisan config:clear`.
- **Logo**: aku simpan file dengan nama `logo.png` (atau `logo.svg`, `logo.webp`, `logo.jpg`) di folder `public/img/`. Otomatis dipakai di sidebar, preloader, login, dan beranda. Kalau file itu kuhapus, logo kembali ke bawaan (`logo-default.svg`). Ukuran terbaik persegi, minimal 128 x 128 piksel.

## Tema dan Transisi

Palet utamanya navy `#0f2a5c`, emas `#f5a623`, dan krem `#fbf8f3`, sama di beranda dan panel admin: sidebar dan footer navy, navbar putih, menu aktif berwarna emas, judul huruf serif. Semua efek tanpa library tambahan (`public/css/arya-theme.css` dan `public/js/arya-theme.js`):

- Preloader logo saat halaman dimuat, fade-in halaman, dan fade-out saat berpindah halaman.
- Kartu dan widget muncul bertahap, lalu terangkat saat di-hover.
- Angka menghitung naik dari nol (count-up), baik di beranda maupun di dashboard.
- Grafik batang per departemen tumbuh dari kiri dengan jeda bertahap.
- Menu sidebar bergeser dan ikonnya berputar saat di-hover.
- Baris tabel muncul bertahap dan meluncur keluar sebelum data dihapus.
- Tombol terangkat dan punya efek ripple, alert sukses hilang dengan fade.
- Menghormati `prefers-reduced-motion`.

Aku juga memaksa tema tetap terang (`color-scheme: only light`) dan memasang kelas `arya-body` lewat JS supaya tampilannya konsisten di browser apa pun. Ikon memakai Font Awesome 5 dari CDN sebagai cadangan.

## Pengujian dan Pembuktian Kriteria

Setiap kriteria penilaian (slide 40) dan tantangan tambahan aku uji dengan dua cara: **tes otomatis** (cepat dan bisa diulang) dan **uji manual di browser** (bukti yang kelihatan). Kalau keduanya sesuai hasil yang diharapkan, kriteria itu terbukti berhasil.

Urutan pengujianku: persiapan, tes otomatis, uji manual langkah 1 sampai 14, lalu catatan hasil. Screenshot bukti kusimpan di folder `docs/bukti/`.

### A. Persiapan

1. Buka terminal di folder `arya-portal`, lalu reset database dengan data awal:
   ```bash
   php artisan migrate:fresh --seed
   ```
   Hasil yang diharapkan: semua migrasi dan seeder selesai tanpa error.
2. Jalankan server:
   ```bash
   php artisan serve
   ```
   Hasil yang diharapkan: muncul tulisan `Server running on [http://127.0.0.1:8000]`. Terminal ini kubiarkan terbuka.
3. Pastikan `.env` berisi `APP_ENV=local` supaya tombol Auto-Login muncul.

### B. Tes otomatis

Buka terminal kedua (terminal server tetap jalan), lalu jalankan:

```bash
php artisan test
```

Untuk menjalankan sebagian saja:

```bash
php artisan test --filter=KriteriaTugasTest   # semua tes per kriteria
php artisan test --filter=kriteria_5          # satu kriteria saja
```

Hasil yang diharapkan: semua tes berstatus `PASS` dan baris terakhir berbunyi `Tests: ... passed`. Tes memakai database sementara (`RefreshDatabase`), jadi data asliku tidak tersentuh.

Bukti: screenshot hasilnya kusimpan di `docs/bukti/00-php-artisan-test.png`.

### C. Uji manual berurutan

Aku menjalankan langkah-langkah ini dari atas ke bawah. Untuk berganti akun, aku klik namaku di pojok kanan atas lalu pilih **Log Out**.

**Kriteria 2: Autentikasi**

| Langkah | Yang kulakukan | Hasil yang diharapkan | Bukti |
|---|---|---|---|
| 1 | Tanpa login, buka `http://127.0.0.1:8000/admin/dashboard` | Dialihkan otomatis ke `/login`, panel admin tidak terbuka | `01-tamu-ke-login.png` |
| 2 | Di halaman login, isi `admin@aryarangga.test` dengan password `salah`, lalu klik Login | Muncul pesan kredensial tidak cocok, tetap di halaman login | `02-password-salah.png` |
| 3 | Isi `admin@aryarangga.test` dengan password `password`, lalu klik Login | Masuk dan dialihkan ke `/admin/dashboard` | `03-login-admin.png` |

**Kriteria 1: Integrasi UI backoffice (AdminLTE)**

| Langkah | Yang kulakukan | Hasil yang diharapkan | Bukti |
|---|---|---|---|
| 4 | Sebagai Admin, buka satu per satu: Dashboard, Data Mahasiswa, Data Dosen, Tugas Kuliah, Pengguna dan Role (lewat menu sidebar) | Kelima halaman memakai kerangka AdminLTE yang sama: sidebar di kiri, navbar di atas, footer di bawah | `04-halaman-adminlte.png` |

**Kriteria 4: Minimal 3 widget agregat**

| Langkah | Yang kulakukan | Hasil yang diharapkan | Bukti |
|---|---|---|---|
| 5 | Buka `/admin/dashboard`, lihat kartu statistik | Ada 4 widget: Total Mahasiswa (61), Total Staf Dosen (12), Total Tugas (25), Mahasiswa Aktif | `05-widget-dashboard.png` |
| 6 | Bandingkan angka widget dengan data di tabel Mahasiswa, Dosen, dan Tugas (lihat teks jumlah di judul halaman) | Angka widget sama dengan jumlah data di tabel | `06-cocokkan-angka.png` |

**Kriteria 3: Otorisasi database Spatie**

| Langkah | Yang kulakukan | Hasil yang diharapkan | Bukti |
|---|---|---|---|
| 7 | Di terminal, jalankan: `php artisan tinker --execute="echo Spatie\Permission\Models\Role::count().' role, '.Spatie\Permission\Models\Permission::count().' permission';"` | Tercetak `2 role, 7 permission` | `07-role-permission.png` |
| 8 | Jalankan: `php artisan tinker --execute="echo Spatie\Permission\Models\Role::findByName('staf')->permissions->pluck('name')->implode(', ');"` | Tercetak 4 permission staf saja: `lihat-dashboard, lihat-mahasiswa, lihat-dosen, lihat-tugas` (tanpa `hapus-*` dan `lihat-pengguna`) | `08-permission-staf.png` |

**Kriteria 5: Pengaman controller dan hak eksklusif admin**

| Langkah | Yang kulakukan | Hasil yang diharapkan | Bukti |
|---|---|---|---|
| 9 | Masih sebagai Admin, buka Data Mahasiswa dan klik **Hapus** di satu baris, lalu konfirmasi | Tombol Hapus ada, barisnya hilang dengan animasi, dan muncul pesan berhasil dihapus | `09-admin-hapus.png` |
| 10 | Sebagai Admin, buka `/admin/pengguna` | Daftar pengguna beserta role tampil | `10-admin-pengguna.png` |
| 11 | Log Out, login lewat tombol **Staf**, lalu buka Data Mahasiswa | Data tampil, tetapi tombol Hapus **tidak ada** di semua baris | `11-staf-tanpa-hapus.png` |
| 12 | Sebagai Staf, buka `/admin/pengguna` | Halaman **403 Forbidden** | `12-staf-403-pengguna.png` |
| 13 | Log Out, login lewat tombol **Pengguna**. Perhatikan halaman `/beranda`, lalu ketik alamat `/admin/dashboard` | `/beranda` menampilkan "Halo, Pengguna Biasa. Akunmu belum memiliki role admin atau staf...". Alamat `/admin/dashboard` menjawab **403 Forbidden** | `13-pengguna-beranda.png`, `13-pengguna-403.png` |

Pembuktian serangan manual (penjagaan ada di controller, bukan hanya tombol yang disembunyikan): request hapus dari Staf tanpa lewat tombol tetap ditolak. Aku membuktikannya dengan tes otomatis:

```bash
php artisan test --filter=kriteria_5_staf_tidak_bisa_menghapus
```

Hasil yang diharapkan: dua tes `PASS`, yaitu staf mendapat 403 saat menghapus mahasiswa dan tugas, dan datanya tetap ada di database.

**Tantangan tambahan (nilai plus)**

| Langkah | Yang kulakukan | Hasil yang diharapkan | Bukti |
|---|---|---|---|
| 14a | Login Admin, buka Data Mahasiswa. Ketik sebagian nama atau NRP di kolom pencarian, pilih satu departemen, pilih status, lalu klik Cari | Tabel hanya menampilkan baris yang cocok dengan semua kondisi (filter `when()`) | `14a-filter.png` |
| 14b | Dengan filter masih aktif, klik halaman 2 | Alamat URL tetap memuat parameter filter, misalnya `?search=...&page=2`, dan hasil tetap tersaring (`withQueryString()`) | `14b-pagination.png` |
| 14c | Di terminal, jalankan `php artisan migrate:fresh --seed` lagi | Terisi 6 departemen, 61 mahasiswa, 12 dosen, 25 tugas (seeder Faker) | `14c-seeder.png` |
| 14d | Ubah `.env` menjadi `APP_ENV=production`, jalankan `php artisan config:clear`, lalu buka `/login`. Setelah itu kembalikan ke `local` | Tombol Auto-Login hilang di mode production dan alamat `/login/demo/admin` menjawab 404. Di mode `local` tombolnya ada | `14d-auto-login.png` |

### D. Peta kriteria ke tes otomatis

Semua ada di `tests/Feature/KriteriaTugasTest.php`.

| # | Kriteria | Tes otomatis | Terbukti kalau |
|---|---|---|---|
| 1 | Integrasi UI backoffice | `kriteria_1_halaman_admin_memakai_layout_adminlte` | Kelima halaman admin memuat sidebar dan area isi AdminLTE |
| 2 | Autentikasi | `kriteria_2_tamu_ditolak...`, `kriteria_2_login_berhasil...`, `kriteria_2_setelah_login_diarahkan...` | Tamu dialihkan ke login, password salah ditolak, password benar masuk, redirect sesuai role |
| 3 | Otorisasi database Spatie | `kriteria_3_role_dan_permission_tersimpan...`, `kriteria_3_role_pengguna_tercatat...` | 2 role dan 7 permission ada di database. Admin memegang 7, Staf memegang 4 |
| 4 | Minimal 3 widget agregat | `kriteria_4_dashboard_menampilkan_widget_agregat...` | Empat angka dashboard sama dengan data di database |
| 5 | Pengaman controller dan hak eksklusif admin | `kriteria_5_staf_tidak_bisa_menghapus...` (dua tes), `kriteria_5_tombol_hapus_hanya_dirender...`, `kriteria_5_halaman_pengguna_khusus_admin...` | Staf mendapat 403 saat menghapus, tombol Hapus hanya tampil untuk Admin, halaman Pengguna khusus Admin |
| + | Filter `when()` | `tambahan_filter_when_departemen_dan_status` | Tabel tersaring sesuai departemen dan status |
| + | Pagination `withQueryString()` | `tambahan_pagination_mempertahankan_filter...` | Tautan halaman membawa parameter filter |
| + | Seeder Faker | `tambahan_seeder_faker_mengisi_data_awal` | 6, 61, 12, dan 25 baris data awal |
| + | Auto-login | `tambahan_auto_login_hanya_hidup_di_environment_local` | 404 di luar `local`, berhasil masuk di `local` |

Tes pendukung: `tests/Feature/AdminAccessTest.php` (akses per role dan pencarian) dan `tests/Feature/ExampleTest.php` (beranda publik terbuka untuk tamu).

### E. Catatan hasil

Bagian ini kuisi setelah menjalankan semua langkah di komputerku:

| Pengujian | Hasil | Tanggal |
|---|---|---|
| Tes otomatis (`php artisan test`) | ... | ... |
| Kriteria 2: langkah 1 sampai 3 | ... | ... |
| Kriteria 1: langkah 4 | ... | ... |
| Kriteria 4: langkah 5 dan 6 | ... | ... |
| Kriteria 3: langkah 7 dan 8 | ... | ... |
| Kriteria 5: langkah 9 sampai 13 | ... | ... |
| Tantangan tambahan: langkah 14a sampai 14d | ... | ... |

## Keamanan Git

`.env` dan folder `vendor/` sudah dikecualikan oleh `.gitignore` bawaan Laravel. Aku tidak pernah meng-commit `.env`.

## Struktur File Utama

```
app/Http/Controllers/Admin/      Dashboard, Mahasiswa, Dosen, Tugas, Pengguna
app/Http/Controllers/            Landing, DashboardRedirect, DemoLogin
app/Models/                      User (HasRoles), Departemen, Mahasiswa, Dosen, Tugas
app/Providers/AppServiceProvider.php   menu dan pengaturan AdminLTE
bootstrap/app.php                alias middleware role / permission
database/migrations/             departemen, mahasiswa, dosen, tugas
database/seeders/                RolePermission, AkunDemo, Akademik (Faker)
resources/views/landing.blade.php      beranda publik
resources/views/components/foto-slot.blade.php   kotak foto otomatis
resources/views/admin/           halaman AdminLTE
resources/views/auth/login.blade.php   login beranimasi + Auto-Login
public/img/landing/              tempat foto beranda
public/css/arya-theme.css        tema navy, emas, krem dan animasi
public/js/arya-theme.js          count-up, transisi halaman, ripple, hapus beranimasi
routes/web.php                   beranda, login, dan grup /admin dengan role:admin|staf
tests/Feature/                   KriteriaTugasTest (per kriteria), AdminAccessTest, ExampleTest
```
