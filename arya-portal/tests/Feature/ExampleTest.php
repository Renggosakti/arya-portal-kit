<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    // Halaman beranda membaca angka dari database, jadi tabel harus tersedia saat test.
    use RefreshDatabase;

    public function test_halaman_beranda_dapat_dibuka_tamu(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Masuk Portal');
    }
}
