<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToThrough;

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

    public function division(): BelongsToThrough
    {
        return $this->belongsToThrough(
            Division::class,
            DivisionMember::class,
            'id_sekbid',
            'id',
            'id_anggota_sekbid',
            'id'
        );
    }

    /**
     * Batasi query pada divisi yang boleh diakses pengguna. MPK dengan
     * assignment divisi hanya melihat penilaian divisi tersebut.
     *
     * @param  Builder<Assesment>  $query
     * @return Builder<Assesment>
     */
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if ($user === null || ! $user->isDivisionScoped()) {
            return $query;
        }

        return $query->whereHas(
            'divisionMember',
            fn (Builder $member) => $member->where('id_sekbid', $user->accessibleDivisionId())
        );
    }
}
