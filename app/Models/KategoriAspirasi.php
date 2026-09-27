<?php

namespace App\Models;

class KategoriAspirasi extends AspirationCategory
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
    ];
}
