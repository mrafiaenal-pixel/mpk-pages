<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'password', 'role', 'id_sekbid'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'id_sekbid',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspiration::class, 'id_users', 'id_users');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Assesment::class, 'id_users', 'id_users');
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'id_sekbid', 'id');
    }

    public function sekbid(): BelongsTo
    {
        return $this->division();
    }

    /**
     * MPK yang ditugaskan ke satu seksi bidang hanya boleh mengelola
     * penilaian divisi tersebut. MPK tanpa assignment (ketua) tanpa batas.
     */
    public function isDivisionScoped(): bool
    {
        return $this->id_sekbid !== null;
    }

    /**
     * ID divisi yang boleh diakses pengguna ini, atau null bila tanpa batas.
     */
    public function accessibleDivisionId(): ?int
    {
        return $this->id_sekbid !== null ? (int) $this->id_sekbid : null;
    }

    /**
     * Pastikan divisi yang diminta ada di dalam cakupan akses pengguna.
     */
    public function canAccessDivision(int|string|null $divisionId): bool
    {
        $divisionId = $divisionId === null || $divisionId === '' ? null : (int) $divisionId;

        if (! $this->isDivisionScoped()) {
            return true;
        }

        return $divisionId !== null && $divisionId === $this->accessibleDivisionId();
    }
}
