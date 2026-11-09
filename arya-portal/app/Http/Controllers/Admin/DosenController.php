<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class DosenController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-dosen'),
        ];
    }

    public function index(Request $request): View
    {
        $dosen = Dosen::with('departemen')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->when($request->input('departemen_id'), function ($query, $departemenId) {
                return $query->where('departemen_id', $departemenId);
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dosen.index', [
            'dosen' => $dosen,
            'departemen' => Departemen::orderBy('nama')->get(),
        ]);
    }
}
