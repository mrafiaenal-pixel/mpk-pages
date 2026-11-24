<?php

namespace App\Http\Controllers;

use App\Models\Aspiration;
use App\Models\Assesment;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\User;
use App\Models\WorkProgram;
use App\Services\PenilaianFilter;
use App\Services\PenilaiSkor;
use App\Services\SiteSettings;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct(
        private readonly PenilaianFilter $filter,
        private readonly SiteSettings $site,
    ) {}

    public function dashboard(Request $request)
    {
        $user = $request->user();

        $totalProgja = WorkProgram::count();
        $progjaSelesai = WorkProgram::where('status_progja', 'terlaksana')->count();

        // Rekap penilaian dibatasi pada cakupan divisi pengguna.
        $penilaians = $this->filter->build($request, $user)->get();
        $nilaiTerdaftar = $penilaians->pluck('aspect')->filter()->pluck('nilai')->filter(fn ($n): bool => $n !== null);
        $rataNilai = $nilaiTerdaftar->isEmpty() ? 0.0 : round($nilaiTerdaftar->avg(), 2);

        // Distribusi predikat untuk donut chart dashboard.
        $distribusiPredikat = $nilaiTerdaftar
            ->map(fn ($n): string => PenilaiSkor::predikat($n))
            ->countBy()
            ->map(fn (int $jumlah, string $label): array => ['label' => $label, 'jumlah' => $jumlah])
            ->sortByDesc('jumlah')
            ->values();

        return view('admin.dashboard', [
            'totalSekbid' => Division::count(),
            'totalAnggota' => DivisionMember::visibleTo($user)->count(),
            'totalAspirasi' => Aspiration::count(),
            'totalProgja' => $totalProgja,
            'persentaseProgja' => $totalProgja > 0 ? round(($progjaSelesai / $totalProgja) * 100) : 0,
            'aspirasiMenunggu' => Aspiration::where('status_aspirasi', 'menunggu')->count(),
            'aspirasiProses' => Aspiration::where('status_aspirasi', 'proses')->count(),
            'aspirasiSelesai' => Aspiration::where('status_aspirasi', 'selesai')->count(),
            'totalPenilaian' => $nilaiTerdaftar->count(),
            'rataNilai' => $rataNilai,
            'predikat' => PenilaiSkor::predikat($rataNilai),
            'distribusiPredikat' => $distribusiPredikat,
            'rekapSekbid' => $this->rekapSekbid($penilaians),
            'rekapAspek' => $this->rekapAspek($penilaians),
            'trenBulanan' => $this->trenBulanan($penilaians),
            'recentPenilaian' => $penilaians->take(5),
            'recentAspirasi' => Aspiration::with('category')->latest('id_aspirasi')->take(5)->get(),
        ]);
    }

    /**
     * Pencarian global lintas sekbid, anggota, aspirasi, dan kegiatan.
     *
     * @return View
     */
    public function search(Request $request)
    {
        $user = $request->user();
        $q = trim((string) $request->input('q'));

        $sekbids = collect();
        $anggotas = collect();
        $aspirasis = collect();
        $progjas = collect();

        if ($q !== '') {
            $sekbids = Division::query()
                ->where('nama_sekbid', 'like', "%{$q}%")
                ->when($user->isDivisionScoped(), fn (Builder $query) => $query->whereKey($user->accessibleDivisionIds()))
                ->limit(10)
                ->get();

            $anggotas = DivisionMember::query()
                ->with('division')
                ->visibleTo($user)
                ->where('nama_anggota', 'like', "%{$q}%")
                ->limit(10)
                ->get();

            if ($user->canManageAspirasi()) {
                $aspirasis = Aspiration::query()
                    ->where('judul_aspirasi', 'like', "%{$q}%")
                    ->orWhere('deskripsi_aspirasi', 'like', "%{$q}%")
                    ->orWhere('nama_pengirim', 'like', "%{$q}%")
                    ->latest('id_aspirasi')
                    ->limit(10)
                    ->get();
            }

            if ($user->hasAnyRole(['admin', 'ketua', 'komc', 'koma', 'komb', 'guru'])) {
                $progjas = WorkProgram::query()
                    ->where('nama_progja', 'like', "%{$q}%")
                    ->orWhere('deskripsi_progja', 'like', "%{$q}%")
                    ->latest('id')
                    ->limit(10)
                    ->get();
            }
        }

        return view('admin.search', compact('q', 'sekbids', 'anggotas', 'aspirasis', 'progjas'));
    }

    public function sekbid()
    {
        $user = request()->user();

        $sekbids = Division::with(['members', 'assessors'])
            ->when($user->isDivisionScoped(), fn (Builder $q) => $q->whereKey($user->accessibleDivisionIds()))
            ->orderBy('nama_sekbid')
            ->get();

        return view('admin.sekbid', [
            'sekbids' => $sekbids,
            'daftarMpk' => User::with('sekbids')
                ->whereIn('role', ['admin', 'komc', 'ketua', 'koma', 'komb', 'guru'])
                ->orderBy('username')
                ->get(),
            'canKelolaPenugasan' => ! $user->isDivisionScoped(),
        ]);
    }

    /**
     * Tentukan penilai penanggung jawab satu seksi bidang. Setiap penilai
     * hanya boleh memegang satu divisi sebagai penanggung jawab utama.
     */
    public function updatePenugasanSekbid(Request $request, Division $divisi)
    {
        // Hanya pengurus tanpa penugasan yang boleh menetapkan penugasan.
        if ($request->user()->isDivisionScoped()) {
            return back()->with('error', 'Hanya pengurus tanpa penugasan yang dapat mengubah penugasan sekbid.');
        }

        $validated = $request->validate([
            'id_users' => ['nullable', Rule::exists('users', 'id_users')],
        ]);

        // Lepaskan penilai lama sekbid ini maupun penilai yang ditugaskan ke sini.
        User::where('id_sekbid', $divisi->id)->update(['id_sekbid' => null]);

        $user = isset($validated['id_users']) ? User::find($validated['id_users']) : null;

        if ($user !== null) {
            $user->forceFill(['id_sekbid' => $divisi->id])->save();
        }

        return back()->with('success', $user === null
            ? "Penugasan {$this->site->singkatan} untuk {$divisi->nama_sekbid} berhasil dikosongkan."
            : "{$user->username} kini menangani {$divisi->nama_sekbid}.");
    }

    /**
     * Rata-rata nilai per sekbid untuk chart dashboard.
     *
     * @param  Collection<int, Assesment>  $penilaians
     * @return Collection<int, array<string, mixed>>
     */
    private function rekapSekbid(Collection $penilaians): Collection
    {
        return $penilaians
            ->groupBy(fn (Assesment $item): string => $item->divisionMember?->division?->nama_sekbid ?? 'Tanpa Sekbid')
            ->map(function (Collection $grup): array {
                $nilai = $grup->pluck('aspect')->filter()->pluck('nilai')->filter(fn ($n): bool => $n !== null);

                return [
                    'nama' => $grup->first()->divisionMember?->division?->nama_sekbid ?? 'Tanpa Sekbid',
                    'rata_nilai' => $nilai->isEmpty() ? 0.0 : round($nilai->avg(), 2),
                    'jumlah' => $nilai->count(),
                ];
            })
            ->sortByDesc('rata_nilai')
            ->values();
    }

    /**
     * Rata-rata nilai dan jumlah penilaian per bulan.
     *
     * @param  Collection<int, Assesment>  $penilaians
     * @return Collection<int, array<string, mixed>>
     */
    private function trenBulanan(Collection $penilaians): Collection
    {
        $terisi = $penilaians->filter(fn (Assesment $item): bool => $item->aspect?->tanggal_penilaian !== null);

        // Pastikan ada minimal 6 bulan agar garis tren tetap terbaca.
        $periode = collect(range(5, 0))
            ->map(fn (int $mundur): string => now()->subMonths($mundur)->format('Y-m'))
            ->values();

        return $periode->map(function (string $key) use ($terisi): array {
            $grup = $terisi->filter(fn (Assesment $item): bool => $item->aspect->tanggal_penilaian->format('Y-m') === $key);
            $nilai = $grup->pluck('aspect')->filter()->pluck('nilai')->filter(fn ($n): bool => $n !== null);

            return [
                'periode' => Carbon::createFromFormat('Y-m', $key)->translatedFormat('M Y'),
                'rata_nilai' => $nilai->isEmpty() ? null : round($nilai->avg(), 2),
                'jumlah' => $nilai->count(),
            ];
        });
    }

    /**
     * Rata-rata tiap aspek untuk radar chart.
     *
     * @param  Collection<int, Assesment>  $penilaians
     * @return Collection<int, array<string, mixed>>
     */
    private function rekapAspek(Collection $penilaians): Collection
    {
        $aspekTersedia = $penilaians->pluck('aspect')->filter();

        return collect(PenilaiSkor::aspekKeys())->map(function (string $aspek) use ($aspekTersedia): array {
            $jumlah = $aspekTersedia
                ->map(fn ($a): int => PenilaiSkor::poinAspek($aspek, $a->{$aspek}))
                ->filter(fn (int $poin): bool => $poin > 0);

            return [
                'label' => PenilaiSkor::LABEL_ASPEK[$aspek],
                'rata' => $jumlah->isEmpty() ? 0.0 : round($jumlah->avg() / max(PenilaiSkor::RUBRIK[$aspek]) * 100, 2),
            ];
        });
    }
}
