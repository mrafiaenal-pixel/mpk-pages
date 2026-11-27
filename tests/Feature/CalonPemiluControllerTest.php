<?php

namespace Tests\Feature;

use App\Models\CalonPemilu;
use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CalonPemiluControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_halaman_kelola_calon_dapat_diakses_ketua_komc_dan_admin(): void
    {
        foreach (['ketua', 'komc', 'admin'] as $role) {
            $this->actingAs(User::factory()->withRole($role)->create())
                ->get(route('admin.calon.index'))
                ->assertOk();
        }
    }

    public function test_halaman_kelola_calon_ditolak_untuk_role_lain(): void
    {
        $this->actingAs(User::factory()->withRole('anggota')->create())
            ->get(route('admin.calon.index'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_tambah_calon_tersimpan_bersama_fotonya(): void
    {
        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->post(route('admin.calon.store'), [
                'nama_calon' => 'Calon Uji',
                'nomor_urut' => 1,
                'slogan' => 'Maju Bersama',
                'visi_misi' => 'Visi dan misi calon.',
                'foto' => UploadedFile::fake()->image('calon.jpg'),
            ])
            ->assertRedirect(route('admin.calon.index'));

        $calon = CalonPemilu::query()->firstOrFail();

        $this->assertSame('Calon Uji', $calon->nama_calon);
        $this->assertNotNull($calon->foto);
        Storage::disk('public')->assertExists($calon->foto);
    }

    public function test_kuota_calon_dari_pengaturan_ditegakkan(): void
    {
        $this->profilDenganKuota(1);
        CalonPemilu::factory()->create(['nomor_urut' => 1]);

        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->post(route('admin.calon.store'), [
                'nama_calon' => 'Calon Kelebihan Kuota',
                'nomor_urut' => 2,
                'visi_misi' => 'Visi calon.',
            ])
            ->assertSessionHasErrors('nama_calon');

        $this->assertSame(1, CalonPemilu::count());
    }

    public function test_nomor_urut_calon_tidak_boleh_sama(): void
    {
        CalonPemilu::factory()->create(['nomor_urut' => 1]);

        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->post(route('admin.calon.store'), [
                'nama_calon' => 'Calon Nomor Sama',
                'nomor_urut' => 1,
                'visi_misi' => 'Visi calon.',
            ])
            ->assertSessionHasErrors('nomor_urut');

        $this->assertSame(1, CalonPemilu::count());
    }

    public function test_ganti_foto_calon_menghapus_foto_lama(): void
    {
        $fotoLama = UploadedFile::fake()->image('lama.jpg')->store('calon', 'public');
        $calon = CalonPemilu::factory()->create([
            'nomor_urut' => 1,
            'foto' => $fotoLama,
        ]);

        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->put(route('admin.calon.update', $calon), [
                'nama_calon' => 'Calon Diubah',
                'nomor_urut' => 1,
                'visi_misi' => 'Visi yang diperbarui.',
                'foto' => UploadedFile::fake()->image('baru.png'),
            ])
            ->assertRedirect(route('admin.calon.index'));

        $calon->refresh();

        $this->assertSame('Calon Diubah', $calon->nama_calon);
        $this->assertNotSame($fotoLama, $calon->foto);
        Storage::disk('public')->assertMissing($fotoLama);
        Storage::disk('public')->assertExists($calon->foto);
    }

    public function test_hapus_calon_menghapus_foto_dari_storage(): void
    {
        $foto = UploadedFile::fake()->image('calon.jpg')->store('calon', 'public');
        $calon = CalonPemilu::factory()->create([
            'nomor_urut' => 1,
            'foto' => $foto,
        ]);

        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->delete(route('admin.calon.destroy', $calon))
            ->assertRedirect(route('admin.calon.index'));

        $this->assertDatabaseMissing('tb_calon_pemilu', ['id' => $calon->id]);
        Storage::disk('public')->assertMissing($foto);
    }

    private function profilDenganKuota(int $kuota): SchoolProfile
    {
        return SchoolProfile::create([
            'nama_sekolah' => 'SMK Wikrama Bogor',
            'email_sekolah' => 'sekolah@example.test',
            'nama_mpk' => 'MPK',
            'nama_lengkap' => 'Majelis Perwakilan Kelas',
            'maksimal_calon' => $kuota,
        ]);
    }
}
