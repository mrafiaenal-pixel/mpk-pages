<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

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
class AspekPenilaian extends AssesmentAspect
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
}
