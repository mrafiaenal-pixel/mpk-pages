<?php

namespace App\Models;

class ProfilKepengurusan extends ManagementProfile
{
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
