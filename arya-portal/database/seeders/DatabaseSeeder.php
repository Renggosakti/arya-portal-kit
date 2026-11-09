<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Urutan seeding: role dan permission dulu, lalu akun demo, lalu data akademik (Faker).
 * Aman dijalankan berulang kali.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AkunDemoSeeder::class,
            AkademikSeeder::class,
        ]);
    }
}
