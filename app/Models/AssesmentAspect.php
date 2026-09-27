<?php

namespace App\Models;

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
}
