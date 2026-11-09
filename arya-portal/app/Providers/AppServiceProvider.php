<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // AdminLTE 3 memakai Bootstrap 4, jadi tampilan pagination disesuaikan.
        Paginator::useBootstrapFour();

        // Nama brand diambil dari APP_NAME di .env. Logo: taruh file bernama logo.svg / logo.png /
        // logo.webp / logo.jpg di folder public/img, otomatis dipakai menggantikan logo bawaan.
        $brand = config('app.name', 'Akadema');
        $logo = 'img/logo-default.svg';
        foreach (['svg', 'png', 'webp', 'jpg', 'jpeg'] as $ext) {
            if (file_exists(public_path("img/logo.{$ext}"))) {
                $logo = "img/logo.{$ext}";
                break;
            }
        }
        View::share('brandName', $brand);
        View::share('brandLogo', $logo);

        // Pengaturan AdminLTE ditimpa dari sini agar config/adminlte.php bawaan paket tidak perlu diedit.
        config([
            'adminlte.title' => $brand,
            'adminlte.logo' => '<b>'.e($brand).'</b>',
            'adminlte.logo_img' => $logo,
            'adminlte.logo_img_alt' => 'Logo '.$brand,
            'adminlte.logo_img_class' => 'brand-image arya-logo',
            'adminlte.use_route_url' => true,
            'adminlte.dashboard_url' => 'admin.dashboard',
            'adminlte.logout_url' => 'logout',
            'adminlte.logout_method' => 'POST',
            'adminlte.login_url' => 'login',
            'adminlte.profile_url' => 'profile.edit',
            'adminlte.layout_dark_mode' => false,
            'adminlte.classes_topnav' => 'navbar-white navbar-light',
            'adminlte.classes_body' => 'arya-body',
            'adminlte.classes_brand' => 'arya-brand',
            'adminlte.classes_sidebar' => 'sidebar-light-primary elevation-0 arya-sidebar',
            'adminlte.preloader' => [
                'enabled' => true,
                'mode' => 'fullscreen',
                'img' => [
                    'path' => $logo,
                    'alt' => 'Memuat',
                    'effect' => 'animation__shake',
                    'width' => 64,
                    'height' => 64,
                ],
            ],
            'adminlte.menu' => [
                [
                    'text' => 'Arya Rangga Putra Pratama  |  5025241072',
                    'url' => '#',
                    'icon' => 'far fa-id-badge',
                    'topnav_right' => true,
                ],
                ['header' => 'MENU UTAMA'],
                [
                    'text' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => 'fas fa-fw fa-tachometer-alt',
                    'can' => 'lihat-dashboard',
                ],
                [
                    'text' => 'Data Mahasiswa',
                    'route' => 'admin.mahasiswa.index',
                    'icon' => 'fas fa-fw fa-user-graduate',
                    'can' => 'lihat-mahasiswa',
                ],
                [
                    'text' => 'Data Dosen',
                    'route' => 'admin.dosen.index',
                    'icon' => 'fas fa-fw fa-chalkboard-teacher',
                    'can' => 'lihat-dosen',
                ],
                [
                    'text' => 'Tugas Kuliah',
                    'route' => 'admin.tugas.index',
                    'icon' => 'fas fa-fw fa-tasks',
                    'can' => 'lihat-tugas',
                ],
                ['header' => 'ADMINISTRASI', 'can' => 'lihat-pengguna'],
                [
                    'text' => 'Pengguna dan Role',
                    'route' => 'admin.pengguna.index',
                    'icon' => 'fas fa-fw fa-user-shield',
                    'can' => 'lihat-pengguna',
                ],
            ],
        ]);
    }
}