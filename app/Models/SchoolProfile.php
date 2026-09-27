<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_sekolah', 'email_sekolah', 'nama_mpk', 'descripsi_profile'])]
class SchoolProfile extends Model
{
    use HasFactory;

    protected $table = 'tb_profile_sekolah';

    protected $primaryKey = 'id_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_sekolah',
        'email_sekolah',
        'nama_mpk',
        'descripsi_profile',
    ];
}
