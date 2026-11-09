<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Menentukan tujuan setelah login berdasarkan role Spatie.
 */
class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasAnyRole(['admin', 'staf'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }
}
