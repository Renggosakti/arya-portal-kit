<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\TugasController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\DemoLoginController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Beranda publik (landing page). Foto dan background bisa ditambahkan di public/img/landing.
Route::get('/', LandingController::class)->name('landing');

// Tombol Auto-Login untuk demo (hanya aktif saat APP_ENV=local).
Route::post('/login/demo/{akun}', DemoLoginController::class)
    ->middleware('guest')
    ->name('login.demo');

// Pintu masuk setelah login: admin/staf diarahkan ke panel, selain itu ke beranda publik.
Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/beranda', fn () => view('home'))
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Seluruh panel admin dikunci: harus login, terverifikasi, dan berperan admin atau staf.
// Izin per aksi (lihat/hapus) dijaga lagi di dalam masing-masing controller.
Route::middleware(['auth', 'verified', 'role:admin|staf'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

        Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');

        Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
        Route::delete('/tugas/{tugas}', [TugasController::class, 'destroy'])->name('tugas.destroy');

        // Khusus admin murni.
        Route::get('/pengguna', [PenggunaController::class, 'index'])
            ->middleware('role:admin')
            ->name('pengguna.index');
    });

require __DIR__.'/auth.php';
