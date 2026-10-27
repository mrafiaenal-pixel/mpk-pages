<?php

namespace App\Models;

use App\Services\PenilaiSkor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'keaktifan_gds',
    'piket_ro',
    'kekompakan',
    'komunikasi',
    'kehadiran_rapat',
    'kegiatan_progja',
    'inisiatif_bantuan',
    'catatan_terhadap_anggota',
    'tanggal_penilaian',
])]
class AssesmentAspect extends Model
{
    use HasFactory;

    protected $table = 'tb_aspek_penilaian';

    protected function casts(): array
    {
        return [
            'tanggal_penilaian' => 'datetime',
            'inisiatif_bantuan' => 'boolean',
            'total_poin' => 'float',
            'nilai' => 'float',
        ];
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assesment::class, 'id_aspek_penilaian', 'id');
    }

    public function penilaian(): HasMany
    {
        return $this->assessments();
    }

    /**
     * Hitung skor dari data kategorikal yang tersimpan tanpa menyimpannya.
     *
     * @return array{total_poin: float, nilai: float, predikat: string}
     */
    public function hitungSkor(): array
    {
        return PenilaiSkor::hitung(
            $this->only(array_merge(PenilaiSkor::aspekKeys(), ['inisiatif_bantuan']))
        );
    }

    /**
     * Hitung ulang lalu simpan skor numerik aspek ini. `predikat` sengaja
     * tidak disimpan karena merupakan turunan dari `nilai`.
     */
    public function refreshSkor(): void
    {
        $skor = $this->hitungSkor();

        $this->forceFill([
            'total_poin' => $skor['total_poin'],
            'nilai' => $skor['nilai'],
        ])->save();
    }

    public function predikat(): string
    {
        return PenilaiSkor::predikat((float) $this->nilai);
    }
}
