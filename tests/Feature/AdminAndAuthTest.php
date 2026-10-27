<?php

namespace Tests\Feature;

use App\Models\Aspiration;
use App\Models\AspirationCategory;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAndAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        AspirationCategory::create([
            'kode_kategori' => 'SARPRAS',
            'nama_kategori' => 'Sarana & Prasarana Sekolah',
        ]);
    }

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
            'role' => 'mpk',
        ]);

        $response = $this->post('/login', [
            'login' => 'rafi_admin',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::create([
            'username' => 'admin_test',
            'email' => 'test@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Ikhtisar Dashboard');
    }

    public function test_mpk_can_store_penilaian(): void
    {
        $user = User::create([
            'username' => 'penilai_user',
            'email' => 'penilai@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        $divisi = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        $anggota = DivisionMember::create([
            'id_sekbid' => $divisi->id,
            'nama_anggota' => 'Ahmad Santoso',
        ]);

        $response = $this->actingAs($user)->post('/admin/penilaian', $this->payloadPenilaian($anggota));

        $response->assertRedirect(route('admin.penilaian'));
        $this->assertDatabaseHas('tr_penilaian', [
            'id_anggota_sekbid' => $anggota->id,
            'id_users' => $user->id_users,
        ]);
        $this->assertDatabaseHas('tb_aspek_penilaian', [
            'keaktifan_gds' => 'hadir',
            'nilai' => 100,
        ]);
    }

    public function test_penilaian_rejects_unknown_aspect_option(): void
    {
        $user = User::create([
            'username' => 'validator',
            'email' => 'validator@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        $divisi = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        $anggota = DivisionMember::create(['id_sekbid' => $divisi->id, 'nama_anggota' => 'Ahmad Santoso']);

        $response = $this->actingAs($user)->post('/admin/penilaian', [
            ...$this->payloadPenilaian($anggota),
            'keaktifan_gds' => 'Sangat Baik',
        ]);

        $response->assertSessionHasErrors('keaktifan_gds');
        $this->assertDatabaseCount('tr_penilaian', 0);
    }

    public function test_admin_can_update_aspirasi_status_and_tanggapan(): void
    {
        $user = User::create([
            'username' => 'admin_aspirasi',
            'email' => 'admin_asp@mpk.id',
            'password' => Hash::make('password'),
            'role' => 'mpk',
        ]);

        $aspirasi = Aspiration::create([
            'nama_pengirim' => 'Siswa XI',
            'judul_aspirasi' => 'Stopkontak Rusak',
            'deskripsi_aspirasi' => 'Stopkontak di kelas XI tidak berfungsi.',
            'status_aspirasi' => 'menunggu',
            'kode_kategori' => 'SARPRAS',
        ]);

        $response = $this->actingAs($user)->patch(route('admin.aspirasi.update', $aspirasi->id_aspirasi), [
            'status_aspirasi' => 'selesai',
            'tanggapan_mpk' => 'Sudah kami perbaiki minggu ini.',
            'catatan_internal' => 'Sudah substituusi baru.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tr_aspirasi', [
            'id_aspirasi' => $aspirasi->id_aspirasi,
            'status_aspirasi' => 'selesai',
            'tanggapan_mpk' => 'Sudah kami perbaiki minggu ini.',
            'id_users_penangan' => $user->id_users,
        ]);
    }

    /**
     * Payload penilaian valid untuk seluruh aspek.
     *
     * @return array<string, mixed>
     */
    private function payloadPenilaian(DivisionMember $anggota): array
    {
        return [
            'id_anggota_sekbid' => $anggota->id,
            'keaktifan_gds' => 'hadir',
            'piket_ro' => 'hadir',
            'kekompakan' => 'ramai aktif',
            'komunikasi' => 'baik responsif',
            'kehadiran_rapat' => 'tepat waktu',
            'kegiatan_progja' => 'sesuai job',
            'inisiatif_bantuan' => '1',
            'tanggal_penilaian' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
