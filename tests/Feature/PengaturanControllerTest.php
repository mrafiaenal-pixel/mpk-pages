<?php

namespace Tests\Feature;

use App\Models\ProfilKepengurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PengaturanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengurus_baru_tersimpan_bersama_pengurus_yang_sudah_ada(): void
    {
        $existing = ProfilKepengurusan::create([
            'nama_pengurus' => 'Pengurus Lama',
            'jabatan_pengurus' => 'Ketua Umum',
            'komisi_pengurus' => 'Pimpinan Inti',
        ]);

        $user = $this->userWithRole('ketua');

        $payload = [
            'singkatan' => 'MPR',
            'nama_lengkap' => 'Majelis Perwakilan Rapat',
            'nama_sekolah' => 'SMK Wikrama Bogor',
            'email_sekolah' => 'mpr@example.test',
            'descripsi_profile' => 'Profil organisasi.',
            'visi' => 'Visi organisasi.',
            'misi' => "Misi satu.\nMisi dua.",
            'maksimal_calon' => 8,
            'komisi' => [
                ['kode' => 'A', 'nama' => 'Komisi A', 'deskripsi' => 'Tata kelola'],
                ['kode' => 'B', 'nama' => 'Komisi B', 'deskripsi' => 'Aspirasi'],
                ['kode' => 'C', 'nama' => 'Komisi C', 'deskripsi' => 'Pengawasan'],
            ],
            'pengurus' => [
                [
                    'id' => $existing->id,
                    'nama_pengurus' => 'Pengurus Lama',
                    'jabatan_pengurus' => 'Ketua Umum',
                    'komisi_pengurus' => 'Pimpinan Inti',
                ],
                [
                    'id' => '',
                    'nama_pengurus' => 'Pengurus Baru',
                    'jabatan_pengurus' => 'Sekretaris',
                    'komisi_pengurus' => 'Komisi A',
                ],
            ],
        ];

        $this->actingAs($user)
            ->put(route('admin.pengaturan.update'), $payload)
            ->assertRedirect(route('admin.pengaturan.index'));

        $this->assertDatabaseHas('tb_profil_kepengurusan', [
            'id' => $existing->id,
            'nama_pengurus' => 'Pengurus Lama',
        ]);
        $this->assertDatabaseHas('tb_profil_kepengurusan', [
            'nama_pengurus' => 'Pengurus Baru',
            'jabatan_pengurus' => 'Sekretaris',
            'komisi_pengurus' => 'Komisi A',
        ]);
        $this->assertSame(2, ProfilKepengurusan::count());
    }

    public function test_kuota_calon_tersimpan_dan_divalidasi(): void
    {
        $user = $this->userWithRole('ketua');
        $payload = [
            'singkatan' => 'MPR',
            'nama_lengkap' => 'Majelis Perwakilan Rapat',
            'nama_sekolah' => 'SMK Wikrama Bogor',
            'email_sekolah' => 'mpr@example.test',
            'visi' => 'Visi organisasi.',
            'misi' => 'Misi organisasi.',
            'maksimal_calon' => 7,
            'komisi' => [
                ['kode' => 'A', 'nama' => 'Komisi A', 'deskripsi' => 'Tata kelola'],
                ['kode' => 'B', 'nama' => 'Komisi B', 'deskripsi' => 'Aspirasi'],
                ['kode' => 'C', 'nama' => 'Komisi C', 'deskripsi' => 'Pengawasan'],
            ],
            'pengurus' => [
                [
                    'id' => '',
                    'nama_pengurus' => 'Pengurus Satu',
                    'jabatan_pengurus' => 'Ketua Umum',
                    'komisi_pengurus' => 'Pimpinan Inti',
                ],
            ],
        ];

        $this->actingAs($user)
            ->put(route('admin.pengaturan.update'), $payload)
            ->assertRedirect(route('admin.pengaturan.index'));

        $this->assertDatabaseHas('tb_profile_sekolah', ['maksimal_calon' => 7]);

        $payload['maksimal_calon'] = 0;

        $this->actingAs($user)
            ->put(route('admin.pengaturan.update'), $payload)
            ->assertSessionHasErrors('maksimal_calon');
    }

    public function test_halaman_profil_mpr_memakai_data_alpine_yang_valid(): void
    {
        ProfilKepengurusan::create([
            'nama_pengurus' => 'Pengurus Lama',
            'jabatan_pengurus' => 'Ketua Umum',
            'komisi_pengurus' => 'Pimpinan Inti',
        ]);

        $response = $this->actingAs($this->userWithRole('ketua'))
            ->get(route('admin.pengaturan.index'));

        $response->assertOk()
            ->assertSee('Pengurus Lama')
            ->assertSee('x-data="{ pengurus: JSON.parse(', false)
            ->assertDontSee('x-data="{ pengurus: [{', false);
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
