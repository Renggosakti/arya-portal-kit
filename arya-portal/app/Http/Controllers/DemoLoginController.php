<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Login instan untuk memudahkan demo ke dosen/asdos.
 * Hanya hidup di environment local; di production route ini menjawab 404.
 */
class DemoLoginController extends Controller
{
    private const AKUN = [
        'admin' => 'admin@aryarangga.test',
        'staf' => 'staf@aryarangga.test',
        'pengguna' => 'pengguna@aryarangga.test',
    ];

    public function __invoke(Request $request, string $akun): RedirectResponse
    {
        abort_unless(app()->environment('local'), 404);
        abort_unless(array_key_exists($akun, self::AKUN), 404);

        $user = User::where('email', self::AKUN[$akun])->firstOrFail();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
