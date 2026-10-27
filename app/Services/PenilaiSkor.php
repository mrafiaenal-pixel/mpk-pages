<?php

namespace App\Services;

/**
 * Pemetaan rubrik penilaian kategorikal (enum) menjadi skor numerik.
 *
 * Kolom `tb_aspek_penilaian` hanya menyimpan nilai kategorikal, sehingga
 * perhitungan poin, nilai akhir, dan predikat dilakukan di level aplikasi
 * agar konsisten antara tabel, chart, dan export.
 */
class PenilaiSkor
{
    /**
     * Rubrik poin per aspek. Kunci = nama kolom, nilai = opsi => poin.
     *
     * @var array<string, array<string, int>>
     */
    public const RUBRIK = [
        'keaktifan_gds' => [
            'hadir' => 100,
            'izin/lainnya' => 60,
            'telat 1x' => 85,
            'telat 2x' => 70,
            'telat 3x' => 55,
            'tidak hadir' => 0,
        ],
        'piket_ro' => [
            'hadir' => 100,
            'tidak ada jadwal' => 90,
            'izin/sakit' => 85,
            'tidak bagiannya' => 50,
            'tanpa keterangan' => 40,
        ],
        'kekompakan' => [
            'ramai aktif' => 120,
            'sepi ada komunikasi' => 50,
            'ramai saat progja' => 25,
            'grup mati' => 0,
        ],
        'komunikasi' => [
            'baik responsif' => 100,
            'lambat merespon' => 70,
            'sulit koordinasi' => 40,
            'tidak berkomunikasi' => 0,
        ],
        'kehadiran_rapat' => [
            'tepat waktu' => 100,
            'izin alasan jelas' => 80,
            'sakit' => 80,
            'telat' => 60,
            'tanpa keterangan' => 20,
        ],
        'kegiatan_progja' => [
            'sesuai job' => 100,
            'kurang maksimal' => 60,
            'tidak ikut alasan' => 40,
            'tidak ada progja' => 0,
        ],
    ];

    /** Bobot bonus bila anggota berinisiatif memberikan bantuan tambahan. */
    public const BONUS_INISIATIF = 15;

    /** Label aspek untuk ditampilkan pada chart dan tabel rekap. */
    public const LABEL_ASPEK = [
        'keaktifan_gds' => 'Keaktifan GDS',
        'piket_ro' => 'Kedisiplinan Piket RO',
        'kekompakan' => 'Kekompakan Sekbid',
        'komunikasi' => 'Komunikasi & Koordinasi',
        'kehadiran_rapat' => 'Kehadiran Rapat',
        'kegiatan_progja' => 'Pelaksanaan Progja',
    ];

    /** @return list<string> */
    public static function aspekKeys(): array
    {
        return array_keys(self::RUBRIK);
    }

    /**
     * Poin maksimum yang mungkin diperoleh satu formulir penilaian.
     */
    public static function poinMaksimum(): int
    {
        $total = array_sum(array_map(
            fn (array $opsi): int => max($opsi),
            self::RUBRIK
        ));

        return $total + self::BONUS_INISIATIF;
    }

    /**
     * Hitung poin satu aspek. Opsi di luar rubrik dianggap tidak dinilai (0).
     */
    public static function poinAspek(string $aspek, ?string $opsi): int
    {
        if ($opsi === null) {
            return 0;
        }

        return self::RUBRIK[$aspek][$opsi] ?? 0;
    }

    /**
     * Hitung total poin, nilai akhir (0-100), dan predikat dari data aspek.
     *
     * @param  array<string, mixed>  $data
     * @return array{total_poin: float, nilai: float, predikat: string}
     */
    public static function hitung(array $data): array
    {
        $totalPoin = 0.0;

        foreach (self::RUBRIK as $aspek => $opsi) {
            $totalPoin += self::poinAspek($aspek, self::ambilNilai($data, $aspek));
        }

        if (! empty($data['inisiatif_bantuan'])) {
            $totalPoin += self::BONUS_INISIATIF;
        }

        $nilai = round($totalPoin / self::poinMaksimum() * 100, 2);

        return [
            'total_poin' => round($totalPoin, 2),
            'nilai' => $nilai,
            'predikat' => self::predikat($nilai),
        ];
    }

    /**
     * Ambil nilai aspek dari array atau objek yang diberikan.
     */
    public static function ambilNilai(array|object $sumber, string $aspek): ?string
    {
        $nilai = is_array($sumber) ? ($sumber[$aspek] ?? null) : ($sumber->{$aspek} ?? null);

        return $nilai === null ? null : (string) $nilai;
    }

    /**
     * Nilai akhir -> predikat kinerja.
     */
    public static function predikat(float $nilai): string
    {
        return match (true) {
            $nilai >= 85 => 'Sangat Baik',
            $nilai >= 70 => 'Baik',
            $nilai >= 55 => 'Cukup',
            $nilai >= 40 => 'Perlu Pembinaan',
            default => 'Tidak Memadai',
        };
    }

    /**
     * Warna Tailwind untuk badge predikat.
     */
    public static function warnaPredikat(string $predikat): string
    {
        return match ($predikat) {
            'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Baik' => 'bg-blue-50 text-blue-700 border-blue-200',
            'Cukup' => 'bg-amber-50 text-amber-700 border-amber-200',
            'Perlu Pembinaan' => 'bg-orange-50 text-orange-700 border-orange-200',
            default => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }
}
