<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Mendaftarkan role "admin" (akses penuh) dan "staf" (akses terbatas, hanya melihat).
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Spatie menyimpan permission di cache, jadi dibersihkan sebelum seeding.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $semua = [
            'lihat-dashboard',
            'lihat-mahasiswa',
            'hapus-mahasiswa',
            'lihat-dosen',
            'lihat-tugas',
            'hapus-tugas',
            'lihat-pengguna',
        ];

        foreach ($semua as $nama) {
            Permission::firstOrCreate(['name' => $nama, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($semua);

        $staf = Role::firstOrCreate(['name' => 'staf', 'guard_name' => 'web']);
        $staf->syncPermissions([
            'lihat-dashboard',
            'lihat-mahasiswa',
            'lihat-dosen',
            'lihat-tugas',
        ]);
    }
}
