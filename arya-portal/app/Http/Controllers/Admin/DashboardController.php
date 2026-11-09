<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class DashboardController extends Controller implements HasMiddleware
{
    /**
     * Pengganti constructor middleware (Laravel 11 ke atas memakai HasMiddleware).
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-dashboard'),
        ];
    }

    public function index(): View
    {
        return view('admin.dashboard', [
            // Widget statistik: total data dari kueri count() pada model.
            'totalMahasiswa' => Mahasiswa::count(),
            'totalDosen' => Dosen::count(),
            'totalTugas' => Tugas::count(),
            'mahasiswaAktif' => Mahasiswa::where('is_active', true)->count(),

            // Grafik batang per departemen (withCount menghindari N+1).
            'perDepartemen' => Departemen::withCount('mahasiswa')->orderBy('nama')->get(),

            'tugasTerbaru' => Tugas::with('departemen')->latest('tenggat')->take(6)->get(),
        ]);
    }
}
