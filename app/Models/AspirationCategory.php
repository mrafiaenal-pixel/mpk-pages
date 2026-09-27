<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode_kategori', 'nama_kategori'])]
class AspirationCategory extends Model
{
    use HasFactory;

    protected $table = 'tb_kategori_aspirasi';

    protected $primaryKey = 'kode_kategori';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
    ];

    public function aspirations(): HasMany
    {
        return $this->hasMany(Aspiration::class, 'kode_kategori', 'kode_kategori');
    }

    public function aspirasi(): HasMany
    {
        return $this->aspirations();
    }
}
