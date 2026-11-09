<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use Illuminate\Database\Seeder;

/**
 * Menyiram data dummy realistis (Faker locale id_ID): 6 departemen FTEIC, 60 mahasiswa
 * (ditambah satu baris untuk pemilik proyek), 12 dosen, dan 25 tugas.
 *
 * NRP, NIP, dan judul bersifat deterministik sehingga seeder aman dijalankan berulang.
 */
class AkademikSeeder extends Seeder
{
    private const DEPARTEMEN = [
        'Teknik Informatika',
        'Sistem Informasi',
        'Teknik Komputer',
        'Teknik Elektro',
        'Teknik Biomedik',
        'Teknologi Informasi',
    ];

    private const MATA_KULIAH = [
        'Pemrograman Berbasis Kerangka Kerja',
        'Manajemen Proyek Perangkat Lunak',
        'Basis Data',
        'Jaringan Komputer',
        'Kecerdasan Buatan',
        'Rekayasa Kebutuhan',
    ];

    private const TOPIK = [
        'Rancangan Basis Data',
        'Implementasi CRUD',
        'Studi Kasus Keamanan',
        'Analisis Kebutuhan',
        'Presentasi Kelompok',
        'Laporan Praktikum',
    ];

    public function run(): void
    {
        $faker = fake('id_ID');

        $departemen = collect(self::DEPARTEMEN)
            ->map(fn (string $nama) => Departemen::firstOrCreate(['nama' => $nama]));

        // Satu baris mahasiswa untuk pemilik proyek ini.
        Mahasiswa::updateOrCreate(
            ['nrp' => '5025241072'],
            [
                'nama' => 'Arya Rangga Putra Pratama',
                'email' => '5025241072@student.its.ac.id',
                'departemen_id' => $departemen->first()->id,
                'is_active' => true,
            ],
        );

        $angkatan = ['22', '23', '24'];

        for ($i = 1; $i <= 60; $i++) {
            $nrp = '5025'.$angkatan[$i % 3].str_pad((string) $i, 4, '0', STR_PAD_LEFT);

            Mahasiswa::updateOrCreate(
                ['nrp' => $nrp],
                [
                    'nama' => $faker->name(),
                    'email' => $nrp.'@student.its.ac.id',
                    'departemen_id' => $departemen->random()->id,
                    'is_active' => $faker->boolean(85), // sekitar 15% sedang cuti akademik
                ],
            );
        }

        for ($i = 1; $i <= 12; $i++) {
            Dosen::updateOrCreate(
                ['nip' => '1980'.str_pad((string) $i, 14, '0', STR_PAD_LEFT)],
                [
                    'nama' => $faker->name(),
                    'departemen_id' => $departemen->random()->id,
                ],
            );
        }

        for ($i = 1; $i <= 25; $i++) {
            $topik = self::TOPIK[$i % count(self::TOPIK)];

            Tugas::updateOrCreate(
                ['judul' => "Tugas {$i}: {$topik}"],
                [
                    'mata_kuliah' => self::MATA_KULIAH[$i % count(self::MATA_KULIAH)],
                    'departemen_id' => $departemen->random()->id,
                    'tenggat' => now()->addDays($faker->numberBetween(-20, 30))->toDateString(),
                ],
            );
        }
    }
}
