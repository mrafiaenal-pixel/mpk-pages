<?php

namespace Tests\Feature;

use App\Models\CalonPemilu;
use App\Models\SuaraPemilu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemiluControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_pemilu_menampilkan_calon_untuk_pengguna_login(): void
    {
        CalonPemilu::factory()->create([
            'nama_calon' => 'Calon Pertama',
            'nomor_urut' => 1,
        ]);
        CalonPemilu::factory()->create([
            'nama_calon' => 'Calon Kedua',
            'nomor_urut' => 2,
        ]);

        $this->actingAs(User::factory()->withRole('anggota')->create())
            ->get(route('admin.pemilu.index'))
            ->assertOk()
            ->assertSee('Calon Pertama')
            ->assertSee('Calon Kedua');
    }

    public function test_halaman_pemilu_mengarahkan_tamu_ke_halaman_login(): void
    {
        $this->get(route('admin.pemilu.index'))
            ->assertRedirect(route('login'));
    }

    public function test_satu_akun_hanya_bisa_memberikan_satu_suara(): void
    {
        $pertama = CalonPemilu::factory()->create(['nomor_urut' => 1]);
        $kedua = CalonPemilu::factory()->create(['nomor_urut' => 2]);
        $user = User::factory()->withRole('anggota')->create();

        $this->actingAs($user)
            ->post(route('admin.pemilu.vote'), ['id_calon' => $pertama->id])
            ->assertRedirect(route('admin.pemilu.index'))
            ->assertSessionHas('success');

        $this->actingAs($user)
            ->post(route('admin.pemilu.vote'), ['id_calon' => $kedua->id])
            ->assertRedirect(route('admin.pemilu.index'))
            ->assertSessionHas('error');

        $this->assertSame(1, SuaraPemilu::count());
        $this->assertDatabaseHas('tb_suara_pemilu', [
            'id_users' => $user->getKey(),
            'id_calon' => $pertama->id,
        ]);
        $this->assertDatabaseMissing('tb_suara_pemilu', [
            'id_users' => $user->getKey(),
            'id_calon' => $kedua->id,
        ]);
    }

    public function test_suara_ditolak_untuk_calon_yang_tidak_terdaftar(): void
    {
        $this->actingAs(User::factory()->withRole('anggota')->create())
            ->post(route('admin.pemilu.vote'), ['id_calon' => 999])
            ->assertSessionHasErrors('id_calon');

        $this->assertSame(0, SuaraPemilu::count());
    }

    public function test_hasil_pemilu_tampil_setelah_pengguna_memilih(): void
    {
        $pertama = CalonPemilu::factory()->create(['nomor_urut' => 1]);
        $kedua = CalonPemilu::factory()->create(['nomor_urut' => 2]);
        $voter = User::factory()->withRole('anggota')->create();

        SuaraPemilu::factory()->count(2)->create(['id_calon' => $pertama->id]);
        SuaraPemilu::factory()->create([
            'id_users' => $voter->getKey(),
            'id_calon' => $kedua->id,
        ]);

        $this->actingAs($voter)
            ->get(route('admin.pemilu.index'))
            ->assertOk()
            ->assertSee('Terima Kasih atas Partisipasi Anda')
            ->assertSee('3 Suara Masuk');
    }
}
