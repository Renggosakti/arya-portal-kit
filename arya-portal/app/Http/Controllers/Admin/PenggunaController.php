<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class PenggunaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat-pengguna'),
        ];
    }

    public function index(): View
    {
        return view('admin.pengguna.index', [
            // with('roles') = eager loading agar tidak terjadi N+1 query.
            'pengguna' => User::with('roles')->orderBy('name')->get(),
        ]);
    }
}
