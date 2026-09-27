<?php

namespace Tests\Feature;

use App\Models\AnggotaSekbid;
use App\Models\Aspirasi;
use App\Models\KategoriAspirasi;
use App\Models\Sekbid;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAndAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
    }

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Buat Akun Baru');
    }

    public function test_user_can_login_with_username_or_email(): void
    {
        $user = User::create([
            'username' => 'rafi_admin',
            'email' => 'rafi@mpk.id',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'login' => 'rafi_admin',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'username' => 'budi_mpk',
            'email' => 'budi@sekolah.sch.id',
            'role' => 'mpk',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('users', [
            'username' => 'budi_mpk',
            'email' => 'budi@sekolah.sch.id',
        ]);
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::create([
            'username' => 'admin_test',
            'email' => 'test@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Ikhtisar Dashboard');
    }

    public function test_admin_can_store_penilaian(): void
    {
        $user = User::create([
            'username' => 'penilai_user',
            'email' => 'penilai@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        $sekbid = Sekbid::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        $anggota = AnggotaSekbid::create([
            'id_sekbid' => $sekbid->id,
            'nama_anggota' => 'Ahmad Santoso',
        ]);

        $response = $this->actingAs($user)->post('/admin/penilaian', [
            'id_anggota_sekbid' => $anggota->id,
            'keaktifan_gds' => 'Sangat Baik',
            'piket_ro' => 'Baik',
            'kekompakan' => 'Sangat Baik',
            'komunikasi' => 'Baik',
            'kegiatan_progja' => 'Sangat Baik',
            'catatan_terhadap_anggota' => 'Bagus sekali kinerjanya.',
            'tanggal_penilaian' => now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('admin.penilaian'));
        $this->assertDatabaseHas('tr_penilaian', [
            'id_anggota_sekbid' => $anggota->id,
            'id_users' => $user->id_users,
        ]);
    }

    public function test_admin_can_update_aspirasi_status(): void
    {
        $user = User::create([
            'username' => 'admin_aspirasi',
            'email' => 'admin_asp@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $kategori = KategoriAspirasi::create([
            'kode_kategori' => 'SARPRAS',
            'nama_kategori' => 'Sarana & Prasarana',
        ]);

        $aspirasi = Aspirasi::create([
            'nama_pengirim' => 'Siswa XI',
            'judul_aspirasi' => 'Stopkontak Rusak',
            'deskripsi_aspirasi' => 'Stopkontak di kelas XI tidak berfungsi.',
            'status_aspirasi' => 'menunggu',
            'kode_kategori' => $kategori->kode_kategori,
        ]);

        $response = $this->actingAs($user)->patch("/admin/aspirasi/{$aspirasi->id_aspirasi}/status", [
            'status_aspirasi' => 'selesai',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tr_aspirasi', [
            'id_aspirasi' => $aspirasi->id_aspirasi,
            'status_aspirasi' => 'selesai',
        ]);
    }
}
