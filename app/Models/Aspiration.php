<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'nama_pengirim',
    'judul_aspirasi',
    'deskripsi_aspirasi',
    'status_aspirasi',
    'id_users',
    'kode_kategori',
])]
class Aspiration extends Model
{
    use HasFactory;

    protected $table = 'tr_aspirasi';

    protected $primaryKey = 'id_aspirasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_pengirim',
        'judul_aspirasi',
        'deskripsi_aspirasi',
        'status_aspirasi',
        'id_users',
        'kode_kategori',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AspirationCategory::class, 'kode_kategori', 'kode_kategori');
    }

    public function kategori(): BelongsTo
    {
        return $this->category();
    }
}
