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

Halaman `/` adalah beranda publik: hero, fitur, tentang, statistik, ajakan, dan footer. Semua foto sengaja kubiarkan kosong, berupa kotak bertuliskan "Tambah ... di sini", karena foto Teknik Informatika ITS akan kutambahkan sendiri.

Untuk mengisinya, aku menyimpan foto ke folder `public/img/landing/` dengan nama persis seperti di tabel (format `.jpg`, `.jpeg`, `.png`, atau `.webp`). Kotak kosong otomatis berganti jadi fotonya tanpa mengubah kode.

| Nama file | Posisi |
|---|---|
| `hero` | Background besar di bagian atas (sisi kanan) |
| `tentang` | Foto di bagian Tentang Portal |
| `fitur-1` sampai `fitur-5` | Gambar kartu fitur |
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

Setiap kriteria penilaian (slide 40) dan tantangan tambahan aku uji dua cara: **tes otomatis** (cepat, bisa diulang) dan **uji manual di browser** (bukti yang kelihatan). Kalau keduanya sesuai hasil yang diharapkan, kriteria itu terbukti berhasil.

### Menjalankan tes otomatis

```bash
php artisan test                                  # semua tes
php artisan test --filter=KriteriaTugasTest       # khusus tes per kriteria
php artisan test --filter=kriteria_5              # satu kriteria saja
```

Hasil yang diharapkan: seluruh tes berstatus `PASS` dan baris terakhir `Tests: ... passed`. Tes memakai database sementara (`RefreshDatabase`), jadi data aslinya tidak tersentuh. Aku mengambil screenshot hasil `php artisan test` sebagai bukti dan menyimpannya di `docs/hasil-tes.png`.

### Tabel pembuktian

| # | Kriteria | Tes otomatis (`tests/Feature/KriteriaTugasTest.php`) | Cara membuktikan manual | Terbukti kalau |
|---|---|---|---|---|
| 1 | Integrasi UI backoffice | `kriteria_1_halaman_admin_memakai_layout_adminlte` | Login sebagai Admin, buka Dashboard, Mahasiswa, Dosen, Tugas, dan Pengguna | Kelima halaman tampil dengan sidebar, navbar, dan footer AdminLTE |
| 2 | Autentikasi | `kriteria_2_tamu_ditolak...`, `kriteria_2_login_berhasil...`, `kriteria_2_setelah_login_diarahkan...` | Tanpa login, buka `/admin/dashboard`. Lalu login dengan password salah, kemudian benar | Tamu dilempar ke `/login`, password salah ditolak, password benar masuk. Admin dan Staf ke `/admin/dashboard`, pengguna biasa ke `/beranda` |
| 3 | Otorisasi database Spatie | `kriteria_3_role_dan_permission_tersimpan...`, `kriteria_3_role_pengguna_tercatat...` | Jalankan perintah tinker di bawah tabel ini, atau buka tabel `roles`, `permissions`, `role_has_permissions` | Ada 2 role (`admin`, `staf`) dan 7 permission. Admin memegang 7, Staf memegang 4 (tanpa `hapus-*` dan `lihat-pengguna`) |
| 4 | Minimal 3 widget agregat | `kriteria_4_dashboard_menampilkan_widget_agregat...` | Buka `/admin/dashboard`, lalu bandingkan angka kartu dengan jumlah baris di tabel Mahasiswa, Dosen, dan Tugas | Ada 4 widget (Total Mahasiswa, Total Staf Dosen, Total Tugas, Mahasiswa Aktif) dan angkanya sama dengan data |
| 5 | Pengaman controller dan hak eksklusif admin | `kriteria_5_staf_tidak_bisa_menghapus...`, `kriteria_5_staf_tidak_bisa_menghapus_tugas...`, `kriteria_5_tombol_hapus_hanya_dirender...`, `kriteria_5_halaman_pengguna_khusus_admin...` | Lihat langkah uji manual kriteria 5 di bawah | Staf tidak melihat tombol Hapus, request hapus dari Staf mendapat 403, halaman Pengguna hanya bisa dibuka Admin |

Perintah bukti untuk kriteria 3:

```bash
php artisan tinker --execute="echo Spatie\Permission\Models\Role::count().' role, '.Spatie\Permission\Models\Permission::count().' permission';"
```

Hasil yang diharapkan: `2 role, 7 permission`.

### Uji manual kriteria 5 (langkah demi langkah)

1. Login **Admin** (tombol Auto-Login), buka Data Mahasiswa. Tombol **Hapus** muncul di setiap baris. Hapus satu baris, dan baris itu hilang dengan animasi.
2. Logout, login **Staf**, buka Data Mahasiswa. Tombol **Hapus** tidak muncul sama sekali.
3. Sebagai Staf, buka `/admin/pengguna`. Hasilnya halaman **403**.
4. Logout, login **Pengguna**, buka `/admin/dashboard`. Hasilnya halaman **403**.
5. Pembuktian serangan manual: sebagai Staf, kirim request hapus tanpa lewat tombol (misalnya dari Postman atau `curl -X DELETE`). Hasilnya **403** dan datanya tetap ada. Ini membuktikan penjagaan ada di controller, bukan hanya menyembunyikan tombol.

### Tantangan tambahan (nilai plus)

| Fitur | Tes otomatis | Cara membuktikan manual | Terbukti kalau |
|---|---|---|---|
| Pencarian dan filter `when()` | `tambahan_filter_when_departemen_dan_status`, `pencarian_menyaring...` (di `AdminAccessTest`) | Di Data Mahasiswa, cari nama atau NRP, pilih departemen, pilih status, lalu klik Cari | Tabel hanya menampilkan baris yang cocok |
| Pagination `withQueryString()` | `tambahan_pagination_mempertahankan_filter...` | Cari kata tertentu, lalu klik halaman 2 | Alamat URL tetap memuat `?search=...&page=2` dan hasil tetap tersaring |
| Auto-seeder Faker | `tambahan_seeder_faker_mengisi_data_awal` | Jalankan `php artisan migrate:fresh --seed` | Terisi 6 departemen, 61 mahasiswa, 12 dosen, 25 tugas |
| Auto-login test accounts | `tambahan_auto_login_hanya_hidup_di_environment_local` | Klik tombol Admin, Staf, atau Pengguna di halaman login | Langsung masuk. Kalau `APP_ENV` diubah ke `production`, URL `/login/demo/admin` menjawab 404 |

### Tes pendukung

`tests/Feature/AdminAccessTest.php` menguji akses per role dan pencarian nama atau NRP. `tests/Feature/ExampleTest.php` menguji beranda publik terbuka untuk tamu.

### Catatan hasil

Bagian ini kuisi setelah menjalankan tes di komputerku:

| Pengujian | Hasil | Tanggal |
|---|---|---|
| `php artisan test` | ... | ... |
| Uji manual kriteria 1 sampai 5 | ... | ... |
| Tantangan tambahan | ... | ... |

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
