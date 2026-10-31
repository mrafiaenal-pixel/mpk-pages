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
     * Daftar role yang valid dalam sistem.
     */
    public const ROLES = [
        'admin',
        'mpk',
        'guru',
        'ketua',
        'komc',
        'anggota',
        'bendahara',
    ];

    /**
     * Cek apakah role user adalah salah satu dari role yang ditentukan.
     */
    public function isRole(string $role): bool
    {
        return in_array($this->role, static::ROLES, true) && $this->role === $role;
    }

    /**
     * Cek apakah user memiliki role admin atau mpk.
     */
    public function isAdminOrMpk(): bool
    {
        return $this->role === 'admin' || $this->role === 'mpk';
    }

    /**
     * Cek apakah user adalah ketua sekbid.
     */
    public function isKetua(): bool
    {
        return $this->role === 'ketua';
    }

    /**
     * Cek apakah user adalah komc.
     */
    public function isKomc(): bool
    {
        return $this->role === 'komc';
    }

    /**
     * Cek apakah user adalah guru.
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Cek apakah user adalah anggota osis.
     */
    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    /**
     * Cek apakah user adalah bendahara.
     */
    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    /**
     * Cek apakah user bisa mengelola penilaian.
     */
    public function canManageAssessment(): bool
    {
        return $this->isKetua() || $this->isKomc() || $this->isGuru() || $this->isAdminOrMpk();
    }

    /**
     * Cek apakah user bisa melihat chart penilaian.
     */
    public function canViewChart(): bool
    {
        // hanya komc dan ketua yang bisa melihat chart
        return $this->isKomc() || $this->isKetua();
    }

    /**
     * Cek apakah user bisa mengedit data penilaian.
     */
    public function canEditPenilaian(): bool
    {
        // hanya komc dan ketua yang bisa edit
        return $this->isKomc() || $this->isKetua();
    }

    /**
     * Cek apakah user bisa menginput penilaian.
     * - guru, ketua, komc, admin, mpk bisa input
     * - anggota hanya bisa input tapi tidak bisa edit/hapus
     * - bendahara similar to anggota: bisa input tapi tidak bisa edit/hapus
     */
    public function canInputPenilaian(): bool
    {
        // guru dan atas bisa input, anggota dan bendahara tidak bisa edit/hapus
        // tetapi bendahara bisa input seperti anggota
        return $this->isGuru() || $this->isKetua() || $this->isKomc() || $this->isAdminOrMpk();
    }

    /**
     * Cek apakah user bisa melihat daftar penilaian.
     * Bendahara similar to anggota: bisa melihat tapi terbatas
     */
    public function canViewPenilaianDaftar(): bool
    {
        // hanya ketua, komc, guru yang bisa melihat daftar lengkap
        // bendahara dan anggota hanya bisa input
        return $this->isKetua() || $this->isKomc() || $this->isGuru();
    }

    /**
     * Cek apakah user bisa mengelola aspirasi.
     * Bendahara bisa melihat dan menambah aspirasi yang dikirim ke ketua.
     */
    public function canManageAspirasi(): bool
    {
        // bendahara bisa menambah aspirasi, ketua bisa mengelolanya semua
        return $this->isBendahara() || $this->isKetua() || $this->isKomc() || $this->isGuru() || $this->isAdminOrMpk();
    }

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
