<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_anggota_sekbid', 'id_users', 'id_aspek_penilaian'])]
class Assesment extends Model
{
    use HasFactory;

    protected $table = 'tr_penilaian';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_anggota_sekbid',
        'id_users',
        'id_aspek_penilaian',
    ];

    public function divisionMember(): BelongsTo
    {
        return $this->belongsTo(DivisionMember::class, 'id_anggota_sekbid', 'id');
    }

    public function anggotaSekbid(): BelongsTo
    {
        return $this->divisionMember();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function aspect(): BelongsTo
    {
        return $this->belongsTo(AssesmentAspect::class, 'id_aspek_penilaian', 'id');
    }

    public function aspekPenilaian(): BelongsTo
    {
        return $this->aspect();
    }
}
