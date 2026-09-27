<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_pengurus', 'jabatan_pengurus', 'komisi_pengurus'])]
class ManagementProfile extends Model
{
    use HasFactory;

    protected $table = 'tb_profil_kepengurusan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_pengurus',
        'jabatan_pengurus',
        'komisi_pengurus',
    ];
}
