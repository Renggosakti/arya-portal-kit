<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Akun uji coba untuk demo dosen/asdos. Semua berpassword "password".
 * Tombol Auto-Login di halaman login memakai tiga akun ini.
 */
class AkunDemoSeeder extends Seeder
{
    /** @var list<array{name: string, email: string, role: string|null}> */
    private const AKUN = [
        ['name' => 'Arya Rangga (Admin)', 'email' => 'admin@aryarangga.test', 'role' => 'admin'],
        ['name' => 'Staf Akademik', 'email' => 'staf@aryarangga.test', 'role' => 'staf'],
        ['name' => 'Pengguna Biasa', 'email' => 'pengguna@aryarangga.test', 'role' => null],
    ];

    public function run(): void
    {
        foreach (self::AKUN as $akun) {
            $user = User::firstOrNew(['email' => $akun['email']]);

            // email_verified_at tidak ada di $fillable, jadi diisi lewat forceFill.
            $user->forceFill([
                'name' => $akun['name'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ])->save();

            $user->syncRoles($akun['role'] ? [$akun['role']] : []);
        }
    }
}
