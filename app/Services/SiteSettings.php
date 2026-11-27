<?php

namespace App\Services;

use App\Models\ProfilKepengurusan;
use App\Models\SchoolProfile;
use App\Models\VissionMission;
use Illuminate\Support\Collection;

/**
 * Identitas organisasi, visi/misi, dan struktur pengurus yang bisa
 * disesuaikan per sekolah melalui halaman pengaturan.
 */
final class SiteSettings
{
    public const MAKSIMAL_CALON_DEFAULT = 5;

    /** @var list<array{kode: string, nama: string, deskripsi: string}> */
    public const KOMISI_DEFAULT = [
        ['kode' => 'A', 'nama' => 'Komisi A', 'deskripsi' => 'Tata Kelola Hukum'],
        ['kode' => 'B', 'nama' => 'Komisi B', 'deskripsi' => 'Jembatan antara Siswa dan Sekolah'],
        ['kode' => 'C', 'nama' => 'Komisi C', 'deskripsi' => 'Pengawasan & Evaluasi'],
    ];

    /**
     * @param  list<string>  $misi
     * @param  Collection<int, ProfilKepengurusan>  $pengurus
     * @param  Collection<int, array{kode: string, nama: string, deskripsi: string}>  $komisi
     */
    public function __construct(
        public readonly string $singkatan,
        public readonly string $namaLengkap,
        public readonly string $namaSekolah,
        public readonly string $email,
        public readonly string $deskripsi,
        public readonly string $visi,
        public readonly array $misi,
        public readonly Collection $pengurus,
        public readonly Collection $komisi,
        public readonly int $maksimalCalon,
    ) {}

    /**
     * Kumpulkan seluruh pengaturan dari database dengan nilai cadangan.
     */
    public static function resolve(): self
    {
        $profil = SchoolProfile::first();
        $visiMisi = VissionMission::first();
        $pengurus = ProfilKepengurusan::query()->orderBy('id')->get();

        $komisi = collect(is_array($profil?->komisi) ? $profil->komisi : self::KOMISI_DEFAULT)
            ->filter(fn (mixed $item): bool => is_array($item) && filled($item['kode'] ?? null))
            ->values();

        if ($komisi->isEmpty()) {
            $komisi = collect(self::KOMISI_DEFAULT);
        }

        $misi = collect(preg_split('/\r?\n/', trim((string) ($visiMisi?->misi ?? ''))) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();

        return new self(
            singkatan: $profil?->nama_mpk ?: 'MPK',
            namaLengkap: $profil?->nama_lengkap ?: 'Majelis Perwakilan Kelas',
            namaSekolah: $profil?->nama_sekolah ?: 'Sekolah',
            email: $profil?->email_sekolah ?: '',
            deskripsi: $profil?->descripsi_profile ?: '',
            visi: $visiMisi?->visi ?: '',
            misi: $misi,
            pengurus: $pengurus,
            komisi: $komisi,
            maksimalCalon: (int) ($profil?->maksimal_calon ?? self::MAKSIMAL_CALON_DEFAULT),
        );
    }
}
