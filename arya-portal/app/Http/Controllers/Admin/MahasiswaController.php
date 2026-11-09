<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Mahasiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class MahasiswaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-mahasiswa', only: ['index']),
            new Middleware('permission:hapus-mahasiswa', only: ['destroy']),
        ];
    }

    /**
     * Tabel mahasiswa dengan pencarian nama/NRP, filter departemen, dan filter status.
     * Kueri kondisional memakai when() dan pagination dikunci dengan withQueryString().
     */
    public function index(Request $request): View
    {
        $mahasiswa = Mahasiswa::with('departemen')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nrp', 'like', "%{$search}%");
                });
            })
            ->when($request->input('departemen_id'), function ($query, $departemenId) {
                return $query->where('departemen_id', $departemenId);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('is_active', $request->input('status') === 'aktif');
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.mahasiswa.index', [
            'mahasiswa' => $mahasiswa,
            'departemen' => Departemen::orderBy('nama')->get(),
        ]);
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        $nama = $mahasiswa->nama;
        $mahasiswa->delete();

        return redirect()->back()->with('success', "Data mahasiswa {$nama} berhasil dihapus.");
    }
}
