<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class TugasController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-tugas', only: ['index']),
            new Middleware('permission:hapus-tugas', only: ['destroy']),
        ];
    }

    /**
     * Filter bertingkat (kata kunci + departemen) dengan pagination yang tetap membawa filter.
     */
    public function index(Request $request): View
    {
        $tugas = Tugas::with('departemen')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%");
            })
            ->when($request->input('departemen_id'), function ($query, $departemenId) {
                return $query->where('departemen_id', $departemenId);
            })
            ->orderByDesc('tenggat')
            ->paginate(15)
            ->withQueryString(); // kunci parameter query pada link pagination

        return view('admin.tugas.index', [
            'tugas' => $tugas,
            'departemen' => Departemen::orderBy('nama')->get(),
        ]);
    }

    public function destroy(Tugas $tugas): RedirectResponse
    {
        $judul = $tugas->judul;
        $tugas->delete();

        return redirect()->back()->with('success', "Tugas \"{$judul}\" berhasil dihapus.");
    }
}
