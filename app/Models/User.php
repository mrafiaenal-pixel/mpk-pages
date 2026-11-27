<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'nama_lengkap', 'email', 'password', 'role', 'role_tambahan', 'id_sekbid'])]
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
        'nama_lengkap',
        'email',
        'password',
        'role',
        'role_tambahan',
        'id_sekbid',
    ];

    /**
     * Daftar role yang valid dalam sistem.
     */
    public const ROLES = [
        'admin',
        'guru',
        'ketua',
        'komc',
        'koma',
        'komb',
        'anggota',
        'bendahara',
    ];

    /** @var array<string, string> */
    public const ROLE_LABELS = [
        'admin' => 'Administrator',
        'guru' => 'Guru Pembina',
        'ketua' => 'Ketua Umum',
        'komc' => 'Komisi C',
        'koma' => 'Komisi A',
        'komb' => 'Komisi B',
        'anggota' => 'Anggota OSIS',
        'bendahara' => 'Bendahara',
    ];

    /**
     * @return list<string>
     */
    public function roles(): array
    {
        return array_values(array_unique(array_filter([
            $this->role,
            $this->role_tambahan,
        ])));
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles(), true);
    }

    /**
     * @param  list<string>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function roleLabel(): string
    {
        $labels = array_map(
            fn (string $role): string => self::ROLE_LABELS[$role] ?? ucfirst($role),
            $this->roles(),
        );

        return implode(' & ', $labels);
    }

    /**
     * Cek apakah role user adalah salah satu dari role yang ditentukan.
     */
    public function isRole(string $role): bool
    {
        return in_array($role, static::ROLES, true) && $this->hasRole($role);
    }

    /**
     * Cek apakah user memiliki role admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Cek apakah user adalah ketua sekbid.
     */
    public function isKetua(): bool
    {
        return $this->hasRole('ketua');
    }

    /**
     * Cek apakah user adalah komc.
     */
    public function isKomc(): bool
    {
        return $this->hasRole('komc');
    }

    /**
     * Cek apakah user adalah guru.
     */
    public function isGuru(): bool
    {
        return $this->hasRole('guru');
    }

    /**
     * Cek apakah user adalah anggota osis.
     */
    public function isAnggota(): bool
    {
        return $this->hasRole('anggota');
    }

    /**
     * Cek apakah user adalah bendahara.
     */
    public function isBendahara(): bool
    {
        return $this->hasRole('bendahara');
    }

    public function isKoma(): bool
    {
        return $this->hasRole('koma');
    }

    public function isKomb(): bool
    {
        return $this->hasRole('komb');
    }

    /**
     * Cek apakah user bisa mengelola penilaian.
     */
    public function canManageAssessment(): bool
    {
        return $this->isKetua() || $this->isKomc() || $this->isGuru() || $this->isAdmin();
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
     * - guru, ketua, komc, koma, komb, admin bisa input
     * - anggota hanya bisa input tapi tidak bisa edit/hapus
     * - bendahara similar to anggota: bisa input tapi tidak bisa edit/hapus
     */
    public function canInputPenilaian(): bool
    {
        // guru, ketua, komc, koma, komb, admin, anggota, bendahara bisa input
        return $this->isGuru() || $this->isKetua() || $this->isKomc() || $this->isKoma() || $this->isKomb() || $this->isAdmin() || $this->isAnggota() || $this->isBendahara();
    }

    /**
     * Cek apakah user bisa melihat daftar penilaian.
     * Bendahara dan anggota hanya bisa menginput, bukan melihat daftar lengkap.
     */
    public function canViewPenilaianDaftar(): bool
    {
        return $this->isKetua() || $this->isKomc() || $this->isGuru() || $this->isAdmin();
    }

    /**
     * Cek apakah user bisa melihat dan menindaklanjuti aspirasi masuk.
     */
    public function canManageAspirasi(): bool
    {
        return $this->isKomb() || $this->isKetua() || $this->isKomc() || $this->isGuru() || $this->isAdmin();
    }

    /**
     * Cek apakah user boleh mengelola anggota pada satu seksi bidang.
     */
    public function canManageDivisionMembers(int|string|null $divisionId): bool
    {
        if ($this->isAdmin() || $this->isKetua() || $this->isKomc()) {
            return true;
        }

        $divisionId = $divisionId === null || $divisionId === '' ? null : (int) $divisionId;
        $divisionIds = $this->accessibleDivisionIds();

        return $divisionId !== null && $divisionIds !== [] && in_array($divisionId, $divisionIds, true);
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

    /**
     * @return HasOne<SuaraPemilu, $this>
     */
    public function suaraPemilu(): HasOne
    {
        return $this->hasOne(SuaraPemilu::class, 'id_users', 'id_users');
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
     * Penugasan tambahan ke banyak seksi bidang.
     *
     * @return BelongsToMany<Division, $this>
     */
    public function sekbids(): BelongsToMany
    {
        return $this->belongsToMany(Division::class, 'tb_user_sekbid', 'id_users', 'id_sekbid');
    }

    /**
     * Semua ID divisi yang boleh diakses pengguna. Kosong berarti tanpa batas.
     *
     * @return list<int>
     */
    public function accessibleDivisionIds(): array
    {
        $ids = $this->sekbids->pluck('id')->map(fn (mixed $id): int => (int) $id)->all();

        if ($this->id_sekbid !== null) {
            $ids[] = (int) $this->id_sekbid;
        }

        return array_values(array_unique($ids));
    }

    /**
     * ID divisi pertama untuk kompatibilitas dengan tampilan lama.
     */
    public function accessibleDivisionId(): ?int
    {
        return $this->accessibleDivisionIds()[0] ?? null;
    }

    /**
     * Penilai yang terikat satu atau beberapa divisi hanya mengelola divisi
     * tersebut. Penilai tanpa penugasan memiliki akses ke seluruh divisi.
     */
    public function isDivisionScoped(): bool
    {
        return $this->accessibleDivisionIds() !== [];
    }

    /**
     * Pastikan divisi yang diminta ada di dalam cakupan akses pengguna.
     */
    public function canAccessDivision(int|string|null $divisionId): bool
    {
        $divisionId = $divisionId === null || $divisionId === '' ? null : (int) $divisionId;
        $divisionIds = $this->accessibleDivisionIds();

        if ($divisionIds === []) {
            return true;
        }

        return $divisionId !== null && in_array($divisionId, $divisionIds, true);
    }
}
