<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAndMenuAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengaturan_dapat_diakses_ketua_komc_dan_admin(): void
    {
        foreach (['ketua', 'komc', 'admin'] as $role) {
            $this->actingAs($this->userWithRole($role))
                ->get(route('admin.pengaturan.index'))
                ->assertOk();
        }
    }

    public function test_pengaturan_ditolak_untuk_role_lainnya(): void
    {
        foreach (['koma', 'komb', 'guru', 'bendahara', 'anggota'] as $role) {
            $this->actingAs($this->userWithRole($role))
                ->get(route('admin.pengaturan.index'))
                ->assertRedirect(route('admin.dashboard'))
                ->assertSessionHas('error');
        }
    }

    public function test_aspirasi_hanya_terlihat_oleh_role_yang_diizinkan(): void
    {
        foreach (['admin', 'komb', 'komc', 'ketua', 'guru'] as $role) {
            $this->actingAs($this->userWithRole($role))
                ->get(route('admin.aspirasi'))
                ->assertOk();
        }

        foreach (['koma', 'bendahara', 'anggota'] as $role) {
            $this->actingAs($this->userWithRole($role))
                ->get(route('admin.aspirasi'))
                ->assertRedirect(route('admin.dashboard'));
        }
    }

    public function test_pencarian_global_menampilkan_hasil(): void
    {
        $divisi = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        DivisionMember::create([
            'id_sekbid' => $divisi->id,
            'nama_anggota' => 'Zahra Kamila',
        ]);

        $this->actingAs($this->userWithRole('ketua'))
            ->get(route('admin.search', ['q' => 'Zahra']))
            ->assertOk()
            ->assertSee('Zahra Kamila');
    }

    public function test_kolom_pencarian_dashboard_terhubung_ke_rute_pencarian(): void
    {
        $this->actingAs($this->userWithRole('ketua'))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('action="'.route('admin.search').'"', false)
            ->assertSee('name="q"', false);
    }

    public function test_penilai_dengan_multi_sekbid_mengakses_semua_divisinya(): void
    {
        $divisiSatu = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        $divisiTiga = Division::create(['nama_sekbid' => 'Sekbid 3: Kewirausahaan']);
        $divisiLain = Division::create(['nama_sekbid' => 'Sekbid 9: TIK']);

        $penilai = $this->userWithRole('komb');
        $penilai->sekbids()->sync([$divisiSatu->id, $divisiTiga->id]);

        $penilai->refresh();

        $this->assertTrue($penilai->isDivisionScoped());
        $this->assertSame([$divisiSatu->id, $divisiTiga->id], $penilai->accessibleDivisionIds());
        $this->assertTrue($penilai->canAccessDivision($divisiSatu->id));
        $this->assertTrue($penilai->canAccessDivision($divisiTiga->id));
        $this->assertFalse($penilai->canAccessDivision($divisiLain->id));
    }

    public function test_pengurus_tanpa_penugasan_mengakses_semua_divisi(): void
    {
        $divisi = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        $ketua = $this->userWithRole('ketua');

        $this->assertFalse($ketua->isDivisionScoped());
        $this->assertTrue($ketua->canAccessDivision($divisi->id));
    }

    public function test_anggota_sekbid_hanya_dapat_mengelola_divisinya_sendiri(): void
    {
        $divisiSendiri = Division::create(['nama_sekbid' => 'Sekbid 1: Ketaqwaan']);
        $divisiLain = Division::create(['nama_sekbid' => 'Sekbid 2: Kreativitas']);

        $penilai = $this->userWithRole('komb');
        $penilai->sekbids()->sync([$divisiSendiri->id]);

        $this->actingAs($penilai)
            ->post(route('admin.sekbid.members.store', $divisiSendiri), ['nama_anggota' => 'Anggota Sendiri'])
            ->assertRedirect(route('admin.sekbid'));

        $this->assertDatabaseHas('tb_anggota_sekbid', [
            'id_sekbid' => $divisiSendiri->id,
            'nama_anggota' => 'Anggota Sendiri',
        ]);

        $this->actingAs($penilai)
            ->post(route('admin.sekbid.members.store', $divisiLain), ['nama_anggota' => 'Anggota Lain'])
            ->assertForbidden();

        $this->assertDatabaseMissing('tb_anggota_sekbid', ['nama_anggota' => 'Anggota Lain']);
    }

    public function test_role_tambahan_memberikan_akses_ke_duaperan(): void
    {
        $pengguna = $this->userWithRole('komb', 'bendahara');

        $this->assertTrue($pengguna->isKomb());
        $this->assertTrue($pengguna->isBendahara());
        $this->assertSame('Komisi B & Bendahara', $pengguna->roleLabel());

        $this->actingAs($pengguna)
            ->get(route('admin.aspirasi'))
            ->assertSee('Aspirasi Masuk');

        $this->actingAs($pengguna)
            ->get(route('admin.bendahara.index'))
            ->assertSee('Uang Kas');
    }

    public function test_komb_tanpa_role_tambahan_tidak_dapat_mengakses_keuangan(): void
    {
        $this->actingAs($this->userWithRole('komb'))
            ->get(route('admin.bendahara.index'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_role_tambahan_bendahara_dapat_mencatat_transaksi_kas(): void
    {
        $pengguna = $this->userWithRole('komb', 'bendahara');

        $this->actingAs($pengguna)
            ->post(route('admin.bendahara.store'), [
                'keterangan' => 'Kas kelas',
                'nominal' => 150000,
                'tipe' => 'masuk',
                'tanggal' => now()->toDateString(),
            ])
            ->assertRedirect(route('admin.bendahara.index'));

        $this->assertDatabaseHas('tb_uang_kas', [
            'id_users' => $pengguna->id_users,
            'keterangan' => 'Kas kelas',
            'nominal' => 150000,
            'tipe' => 'masuk',
        ]);
    }

    public function test_seeder_menugaskan_penilai_ke_sekbid_yang_ditentukan(): void
    {
        $this->seed();

        $penugasan = [
            'iksan' => [3, 1],
            'denis' => [10, 1],
            'redina' => [6, 1],
            'ezri' => [5, 1],
            'sena' => [4, 1],
            'ezra' => [2, 1],
        ];

        $idSekbid = fn (int $nomor): int => (int) Division::where('nama_sekbid', 'like', "Sekbid {$nomor}:%")->value('id');

        foreach ($penugasan as $username => $nomorSekbid) {
            $penilai = User::where('username', $username)->firstOrFail();
            $diharapkan = array_map($idSekbid, $nomorSekbid);
            $aktual = $penilai->accessibleDivisionIds();

            sort($diharapkan);
            sort($aktual);

            $this->assertSame($diharapkan, $aktual, $username);
        }

        foreach ([7, 8, 9] as $nomor) {
            $this->assertDatabaseMissing('tb_user_sekbid', ['id_sekbid' => $idSekbid($nomor)]);
        }

        foreach (['admin', 'ketua', 'komc', 'rafi', 'samuel', 'kiran', 'guru', 'komb', 'koma', 'anggota', 'bendahara'] as $username) {
            $pengguna = User::where('username', $username)->firstOrFail();

            $this->assertSame([], $pengguna->accessibleDivisionIds(), $username);
        }
    }

    private function userWithRole(string $role, ?string $roleTambahan = null): User
    {
        return User::create([
            'username' => 'user_'.$role.'_'.fake()->unique()->numberBetween(10000, 99999),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => $role,
            'role_tambahan' => $roleTambahan,
        ]);
    }
}
