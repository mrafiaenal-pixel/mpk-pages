<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_sekbid', 'nama_anggota'])]
class DivisionMember extends Model
{
    use HasFactory;

    protected $table = 'tb_anggota_sekbid';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_sekbid',
        'nama_anggota',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'id_sekbid', 'id');
    }

    public function sekbid(): BelongsTo
    {
        return $this->division();
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assesment::class, 'id_anggota_sekbid', 'id');
    }

    public function penilaian(): HasMany
    {
        return $this->assessments();
    }
}
