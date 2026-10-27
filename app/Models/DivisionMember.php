<?php

namespace App\Models;

use App\Services\PenilaiSkor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Batasi anggota pada divisi yang boleh diakses pengguna.
     *
     * @param  Builder<DivisionMember>  $query
     * @return Builder<DivisionMember>
     */
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if ($user === null || ! $user->isDivisionScoped()) {
            return $query;
        }

        return $query->where('id_sekbid', $user->accessibleDivisionId());
    }

    /**
     * Rata-rata nilai seluruh penilaian anggota ini.
     */
    public function nilaiRataRata(): float
    {
        $this->loadMissing('assessments.aspect');

        $nilai = $this->assessments
            ->pluck('aspect')
            ->filter()
            ->pluck('nilai')
            ->filter(fn (?float $n): bool => $n !== null);

        return $nilai->isEmpty() ? 0.0 : round($nilai->avg(), 2);
    }

    /**
     * Rata-rata skor tiap aspek dari seluruh penilaian anggota ini.
     *
     * @return array<string, float>
     */
    public function rataRataPerAspek(): array
    {
        $this->loadMissing('assessments.aspect');

        $aspects = $this->assessments->pluck('aspect')->filter();

        $rata = [];

        foreach (PenilaiSkor::aspekKeys() as $aspek) {
            $jumlahAspek = $aspects->map(fn (AssesmentAspect $a): float => PenilaiSkor::poinAspek($aspek, $a->{$aspek}));

            $rata[$aspek] = $jumlahAspek->isEmpty()
                ? 0.0
                : round($jumlahAspek->avg() / max(PenilaiSkor::RUBRIK[$aspek]) * 100, 2);
        }

        return $rata;
    }
}
