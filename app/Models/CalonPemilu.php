<?php

namespace App\Models;

use Database\Factories\CalonPemiluFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_calon', 'nomor_urut', 'slogan', 'visi_misi', 'foto'])]
class CalonPemilu extends Model
{
    /** @use HasFactory<CalonPemiluFactory> */
    use HasFactory;

    protected $table = 'tb_calon_pemilu';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_calon',
        'nomor_urut',
        'slogan',
        'visi_misi',
        'foto',
    ];

    /**
     * @return HasMany<SuaraPemilu, $this>
     */
    public function suara(): HasMany
    {
        return $this->hasMany(SuaraPemilu::class, 'id_calon');
    }
}
