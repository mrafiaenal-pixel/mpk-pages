<?php

namespace Tests\Feature;

use App\Models\FotoDokumentasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FotoDokumentasiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_halaman_dokumentasi_dapat_diakses_ketua_komc_dan_admin(): void
    {
        foreach (['ketua', 'komc', 'admin'] as $role) {
            $this->actingAs(User::factory()->withRole($role)->create())
                ->get(route('admin.dokumentasi.index'))
                ->assertOk();
        }
    }

    public function test_halaman_dokumentasi_ditolak_untuk_role_lain(): void
    {
        $this->actingAs(User::factory()->withRole('anggota')->create())
            ->get(route('admin.dokumentasi.index'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_foto_dokumentasi_berhasil_diunggah(): void
    {
        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->post(route('admin.dokumentasi.store'), [
                'judul' => 'Upacara Bendera',
                'keterangan' => 'Kegiatan rutin setiap Senin.',
                'foto' => UploadedFile::fake()->image('kegiatan.jpg'),
            ])
            ->assertRedirect(route('admin.dokumentasi.index'));

        $foto = FotoDokumentasi::query()->firstOrFail();

        $this->assertSame('Upacara Bendera', $foto->judul);
        Storage::disk('public')->assertExists($foto->foto);
    }

    public function test_unggahan_non_gambar_ditolak(): void
    {
        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->post(route('admin.dokumentasi.store'), [
                'judul' => 'Dokumen',
                'foto' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('foto');

        $this->assertSame(0, FotoDokumentasi::count());
    }

    public function test_ubah_foto_dokumentasi_mengganti_berkas_lama(): void
    {
        $fotoLama = UploadedFile::fake()->image('lama.jpg')->store('dokumentasi', 'public');
        $dokumentasi = FotoDokumentasi::factory()->create([
            'judul' => 'Judul Lama',
            'foto' => $fotoLama,
        ]);

        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->put(route('admin.dokumentasi.update', $dokumentasi), [
                'judul' => 'Judul Baru',
                'keterangan' => 'Keterangan diperbarui.',
                'foto' => UploadedFile::fake()->image('baru.png'),
            ])
            ->assertRedirect(route('admin.dokumentasi.index'));

        $dokumentasi->refresh();

        $this->assertSame('Judul Baru', $dokumentasi->judul);
        $this->assertSame('Keterangan diperbarui.', $dokumentasi->keterangan);
        $this->assertNotSame($fotoLama, $dokumentasi->foto);
        Storage::disk('public')->assertMissing($fotoLama);
        Storage::disk('public')->assertExists($dokumentasi->foto);
    }

    public function test_hapus_foto_dokumentasi_menghapus_berkas_dari_storage(): void
    {
        $foto = UploadedFile::fake()->image('kegiatan.jpg')->store('dokumentasi', 'public');
        $dokumentasi = FotoDokumentasi::factory()->create(['foto' => $foto]);

        $this->actingAs(User::factory()->withRole('ketua')->create())
            ->delete(route('admin.dokumentasi.destroy', $dokumentasi))
            ->assertRedirect(route('admin.dokumentasi.index'));

        $this->assertDatabaseMissing('tb_foto_dokumentasi', ['id' => $dokumentasi->id]);
        Storage::disk('public')->assertMissing($foto);
    }

    public function test_foto_dokumentasi_tampil_di_halaman_publik(): void
    {
        $foto = UploadedFile::fake()->image('kegiatan.jpg')->store('dokumentasi', 'public');
        FotoDokumentasi::factory()->create([
            'judul' => 'Galeri Publik',
            'foto' => $foto,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Galeri Publik');
    }
}
