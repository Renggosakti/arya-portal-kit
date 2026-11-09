<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Mahasiswa;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    private function akun(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function mahasiswa(string $nama, string $nrp): Mahasiswa
    {
        $departemen = Departemen::firstOrCreate(['nama' => 'Teknik Informatika']);

        return Mahasiswa::create([
            'nama' => $nama,
            'nrp' => $nrp,
            'email' => $nrp.'@student.its.ac.id',
            'departemen_id' => $departemen->id,
            'is_active' => true,
        ]);
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_user_tanpa_role_tidak_boleh_masuk_panel_admin(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_setelah_login_admin_dan_staf_diarahkan_ke_panel_sedangkan_user_biasa_ke_beranda(): void
    {
        $this->actingAs($this->akun('admin'))->get('/dashboard')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($this->akun('staf'))->get('/dashboard')->assertRedirect(route('admin.dashboard'));
        $this->actingAs(User::factory()->create())->get('/dashboard')->assertRedirect(route('home'));
    }

    public function test_staf_bisa_melihat_data_tetapi_tidak_bisa_menghapus(): void
    {
        $mhs = $this->mahasiswa('Budi Santoso', '5025240001');
        $staf = $this->akun('staf');

        $this->actingAs($staf)->get('/admin/dashboard')->assertOk();
        $this->actingAs($staf)->get('/admin/mahasiswa')->assertOk()->assertSee('Budi Santoso');

        $this->actingAs($staf)->delete(route('admin.mahasiswa.destroy', $mhs))->assertForbidden();
        $this->assertDatabaseHas('mahasiswa', ['id' => $mhs->id]);
    }

    public function test_admin_bisa_menghapus_mahasiswa(): void
    {
        $mhs = $this->mahasiswa('Budi Santoso', '5025240001');

        $this->actingAs($this->akun('admin'))
            ->delete(route('admin.mahasiswa.destroy', $mhs))
            ->assertRedirect();

        $this->assertDatabaseMissing('mahasiswa', ['id' => $mhs->id]);
    }

    public function test_halaman_pengguna_hanya_untuk_admin(): void
    {
        $this->actingAs($this->akun('staf'))->get('/admin/pengguna')->assertForbidden();
        $this->actingAs($this->akun('admin'))->get('/admin/pengguna')->assertOk();
    }

    public function test_pencarian_menyaring_berdasarkan_nama_atau_nrp(): void
    {
        $this->mahasiswa('Budi Santoso', '5025240001');
        $this->mahasiswa('Citra Dewi', '5025240002');

        $admin = $this->akun('admin');

        $this->actingAs($admin)->get('/admin/mahasiswa?search=Budi')
            ->assertSee('Budi Santoso')
            ->assertDontSee('Citra Dewi');

        $this->actingAs($admin)->get('/admin/mahasiswa?search=5025240002')
            ->assertSee('Citra Dewi')
            ->assertDontSee('Budi Santoso');
    }
}
