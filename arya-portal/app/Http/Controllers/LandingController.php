<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Halaman beranda publik. Angka statistik diambil langsung dari database.
 */
class LandingController extends Controller
{
    public function __invoke(): View
    {
        return view('landing', [
            'totalMahasiswa' => Mahasiswa::count(),
            'totalDosen' => Dosen::count(),
            'totalTugas' => Tugas::count(),
            'totalDepartemen' => Departemen::count(),
            'totalRole' => Role::count(),
            'totalPermission' => Permission::count(),
        ]);
    }
}
