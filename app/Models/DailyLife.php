<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_users', 'judul', 'deskripsi', 'tanggal', 'kategori'])]
class DailyLife extends Model
{
    use HasFactory;

    protected $table = 'daily_lives';

    protected $fillable = [
        'id_users',
        'judul',
        'deskripsi',
        'tanggal',
        'kategori',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}
