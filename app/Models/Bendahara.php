<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_users', 'keterangan', 'nominal', 'tipe', 'tanggal'])]
class Bendahara extends Model
{
    use HasFactory;

    protected $table = 'tb_uang_kas';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_users',
        'keterangan',
        'nominal',
        'tipe',
        'tanggal',
    ];

    /**
     * Get the user who recorded this cash transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}
