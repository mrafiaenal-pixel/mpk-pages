<?php

namespace App\Services;

use App\Models\Assesment;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Menerapkan filter penilaian secara konsisten pada halaman daftar,
 * export, dan halaman rekap visual.
 */
class PenilaianFilter
{
    /**
     * Bangun query penilaian sesuai filter request dan cakupan divisi pengguna.
     *
     * @return Builder<Assesment>
     */
    public function build(Request $request, ?User $user = null): Builder
    {
        $user ??= $request->user();

        $query = Assesment::query()
            ->with(['divisionMember.division', 'user', 'aspect'])
            ->visibleTo($user)
            ->latest('id');

        // Filter divisi. MPK yang terikat divisi hanya boleh filter divisinya sendiri.
        if ($request->filled('divisi')) {
            $divisiId = (int) $request->input('divisi');

            if ($user?->canAccessDivision($divisiId)) {
                $query->whereHas(
                    'divisionMember',
                    fn (Builder $member) => $member->where('id_sekbid', $divisiId)
                );
            }
        }

        // Filter anggota sekbid.
        if ($request->filled('anggota')) {
            $query->where('id_anggota_sekbid', (int) $request->input('anggota'));
        }

        // Filter MPK penilai.
        if ($request->filled('penilai')) {
            $query->where('id_users', (int) $request->input('penilai'));
        }

        // Rentang tanggal penilaian (kolom ini berada pada tabel aspek).
        $query->when($request->filled('dari') || $request->filled('sampai'), function (Builder $q) use ($request): void {
            $q->whereHas('aspect', function (Builder $aspect) use ($request): void {
                $aspect->when($request->filled('dari'), fn (Builder $d) => $d->whereDate('tanggal_penilaian', '>=', $request->input('dari')))
                    ->when($request->filled('sampai'), fn (Builder $s) => $s->whereDate('tanggal_penilaian', '<=', $request->input('sampai')));
            });
        });

        // Pencarian bebas pada nama anggota atau MPK penilai.
        if ($search = trim((string) $request->input('q'))) {
            $query->where(function (Builder $q) use ($search): void {
                $q->whereHas('divisionMember', fn (Builder $m) => $m->where('nama_anggota', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn (Builder $u) => $u->where('username', 'like', "%{$search}%"));
            });
        }

        return $query;
    }

    /**
     * Daftar divisi yang boleh diakses pengguna.
     *
     * @return Collection<int, Division>
     */
    public function divisiTersedia(?User $user)
    {
        return Division::query()
            ->when($user?->isDivisionScoped(), fn (Builder $q) => $q->whereKey($user->accessibleDivisionId()))
            ->orderBy('nama_sekbid')
            ->get();
    }

    /**
     * Daftar anggota sekbid yang boleh diakses pengguna.
     *
     * @return Collection<int, DivisionMember>
     */
    public function anggotaTersedia(?User $user, ?int $divisiId = null)
    {
        return DivisionMember::query()
            ->with('division')
            ->visibleTo($user)
            ->when($divisiId, fn (Builder $q) => $q->where('id_sekbid', $divisiId))
            ->orderBy('nama_anggota')
            ->get();
    }

    /**
     * Normalisasi input filter untuk dipakai kembali di query string.
     *
     * @return array<string, mixed>
     */
    public function parameterAktif(Request $request): array
    {
        return array_filter([
            'q' => $request->input('q'),
            'divisi' => $request->input('divisi'),
            'anggota' => $request->input('anggota'),
            'penilai' => $request->input('penilai'),
            'dari' => $request->input('dari'),
            'sampai' => $request->input('sampai'),
        ], fn ($value): bool => $value !== null && $value !== '');
    }
}
