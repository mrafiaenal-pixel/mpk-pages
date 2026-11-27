<?php

namespace App\Models;

use Database\Factories\FotoDokumentasiFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['judul', 'keterangan', 'foto'])]
class FotoDokumentasi extends Model
{
    /** @use HasFactory<FotoDokumentasiFactory> */
    use HasFactory;

    protected $table = 'tb_foto_dokumentasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'judul',
        'keterangan',
        'foto',
    ];
}
