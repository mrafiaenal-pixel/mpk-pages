<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\ProfilKepengurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AnggotaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_anggota_dapat_diakses_ketua_komc_dan_admin(): void
    {
        foreach (['ketua', 'komc', 'admin'] as $role) {
            $this->actingAs($this->userWithRole($role))
                ->get(route('admin.anggota.index'))
                ->assertOk();
        }
    }

    public function test_halaman_anggota_ditolak_untuk_role_lainnya(): void
    {
        foreach (['koma', 'komb', 'guru', 'bendahara', 'anggota'] as $role) {
            $this->actingAs($this->userWithRole($role))
                ->get(route('admin.anggota.index'))
                ->assertRedirect(route('admin.dashboard'))
                ->assertSessionHas('error');
        }
    }

    public function test_tambah_anggota_tersimpan_tanpa_mengubah_profil_mpr(): void
    {
        $division = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);

        $this->actingAs($this->userWithRole('ketua'))
            ->post(route('admin.anggota.store'), [
                'nama_anggota' => 'Anggota Baru',
                'id_sekbid' => $division->id,
            ])
            ->assertRedirect(route('admin.anggota.index'));

        $this->assertDatabaseHas('tb_anggota_sekbid', [
            'id_sekbid' => $division->id,
            'nama_anggota' => 'Anggota Baru',
        ]);
        $this->assertSame(0, ProfilKepengurusan::count());
    }

    public function test_tambah_anggota_menolak_nama_kosong(): void
    {
        $division = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);

        $this->actingAs($this->userWithRole('ketua'))
            ->post(route('admin.anggota.store'), [
                'nama_anggota' => '',
                'id_sekbid' => $division->id,
            ])
            ->assertSessionHasErrors('nama_anggota');

        $this->assertDatabaseMissing('tb_anggota_sekbid', ['nama_anggota' => '']);
    }

    public function test_nama_anggota_yang_sudah_ada_ditolak_pada_seksi_bidang_sama(): void
    {
        $division = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        DivisionMember::create([
            'id_sekbid' => $division->id,
            'nama_anggota' => 'Anggota Kembar',
        ]);

        $this->actingAs($this->userWithRole('ketua'))
            ->post(route('admin.anggota.store'), [
                'nama_anggota' => 'Anggota Kembar',
                'id_sekbid' => $division->id,
            ])
            ->assertSessionHasErrors('nama_anggota');

        $this->assertSame(1, DivisionMember::count());
    }

    public function test_halaman_anggota_menampilkan_anggota_yang_tersimpan(): void
    {
        $division = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        DivisionMember::create([
            'id_sekbid' => $division->id,
            'nama_anggota' => 'Anggota Tersimpan',
        ]);

        $this->actingAs($this->userWithRole('ketua'))
            ->get(route('admin.anggota.index'))
            ->assertOk()
            ->assertSee('Anggota Tersimpan');
    }

    public function test_hapus_anggota_menghapus_data_dari_seksi_bidang(): void
    {
        $division = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        $member = DivisionMember::create([
            'id_sekbid' => $division->id,
            'nama_anggota' => 'Anggota Hapus',
        ]);

        $this->actingAs($this->userWithRole('ketua'))
            ->delete(route('admin.anggota.destroy', $member))
            ->assertRedirect(route('admin.anggota.index'));

        $this->assertDatabaseMissing('tb_anggota_sekbid', ['id' => $member->id]);
    }

    private function userWithRole(string $role): User
    {
        return User::create([
            'username' => 'user_'.$role.'_'.fake()->unique()->numberBetween(10000, 99999),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => $role,
        ]);
    }
}
