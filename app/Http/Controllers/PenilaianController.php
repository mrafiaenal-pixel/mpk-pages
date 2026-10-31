<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenilaianRequest;
use App\Models\AspekPenilaian;
use App\Models\Assesment;
use App\Services\PenilaianFilter;
use App\Services\PenilaiSkor;
use Barryvdh\LaravelDompdf\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PenilaianController extends Controller
{
    public function __construct(private readonly PenilaianFilter $filter) {}

    /**
     * Daftar penilaian kinetic dengan filter divisi, anggota, penilai, dan tanggal.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Cek role user - aturan akses:
        // - ketua, komc, guru: bisa melihat daftar penilaian lengkap
        // - bendahara: bisa melihat tapi hanya untuk input referensi, tidak bisa edit
        // - anggota: hanya bisa input, tidak bisa melihat daftar penilaian
        // - admin/mpk: bisa semua
        if ($user->isBendahara()) {
            // Bendahara bisa melihat daftar tapi dengan batasan
            // (boleh lihat tapi fitur edit dihapus)
        } elseif ($user->isAnggota()) {
            // Anggota hanya bisa input, tidak boleh melihat daftar penilaian
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda hanya boleh menginput penilaian, tidak boleh melihat daftar.');
        }

        $penilaians = $this->filter->build($request, $user)
            ->paginate(10)
            ->withQueryString();

        $divisiId = $request->filled('divisi') ? (int) $request->input('divisi') : null;

        return view('admin.penilaian', [
            'penilaians' => $penilaians,
            'divisiList' => $this->filter->divisiTersedia($user),
            'anggotaList' => $this->filter->anggotaTersedia($user, $divisiId),
            'penilaiList' => $this->filter->parameterAktif($request),
            'statistik' => $this->ringkasan($this->filter->build($request, $user)),
            'userRole' => $user->role,
            'showEditButtons' => $user->canEditPenilaian() || $user->isAdminOrMpk(),
        ]);
    }

    /**
     * Simpan penilaian baru beserta skor numeriknya.
     */
    public function store(StorePenilaianRequest $request)
    {
        $validated = $request->validated();

        $aspek = AspekPenilaian::create([
            ...$validated,
            'inisiatif_bantuan' => $request->boolean('inisiatif_bantuan'),
        ]);

        $aspek->refreshSkor();

        Assesment::create([
            'id_anggota_sekbid' => $validated['id_anggota_sekbid'],
            'id_users' => $request->user()->id_users,
            'id_aspek_penilaian' => $aspek->id,
        ]);

        return redirect()->route('admin.penilaian')
            ->with('success', "Penilaian berhasil disimpan dengan nilai {$aspek->nilai} ({$aspek->predikat()}).");
    }

    /**
     * Hapus satu riwayat penilaian beserta data aspeknya.
     */
    public function destroy(Request $request, Assesment $penilaian)
    {
        // Hanya komc, ketua, dan admin/mpk yang bisa menghapus penilaian
        if (! $request->user()->canEditPenilaian()) {
            abort_unless(false, 403);
        }

        abort_unless($request->user()->canAccessDivision($penilaian->divisionMember?->id_sekbid), 403);

        $penilaian->aspect?->delete();
        $penilaian->delete();

        return redirect()->route('admin.penilaian', $this->filter->parameterAktif($request))
            ->with('success', 'Data penilaian berhasil dihapus.');
    }

    /**
     * Export penilaian yang sedang difilter ke CSV (dibuka di Excel).
     */
    public function export(Request $request): StreamedResponse
    {
        // Hanya komc, ketua, guru, dan admin/mpk yang bisa export
        if (! $request->user()->canEditPenilaian() && ! $request->user()->isAdminOrMpk()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk ekspor data.');
        }

        $rows = $this->filter->build($request, $request->user())
            ->orderBy('id')
            ->get();

        $namaBerkas = 'penilaian-osis-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'wb');

            // BOM UTF-8 agar karakter Indonesia terbaca benar di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID', 'Tanggal', 'Seksi Bidang', 'Anggota', 'MPK Penilai',
                'Keaktifan GDS', 'Piket RO', 'Kekompakan', 'Komunikasi',
                'Kehadiran Rapat', 'Pelaksanaan Progja', 'Inisiatif Bantuan',
                'Total Poin', 'Nilai', 'Predikat', 'Catatan',
            ], ';');

            foreach ($rows as $row) {
                $aspek = $row->aspect;

                fputcsv($handle, [
                    $row->id,
                    $aspek?->tanggal_penilaian?->format('Y-m-d H:i'),
                    $row->divisionMember?->division?->nama_sekbid,
                    $row->divisionMember?->nama_anggota,
                    $row->user?->username,
                    $aspek?->keaktifan_gds,
                    $aspek?->piket_ro,
                    $aspek?->kekompakan,
                    $aspek?->komunikasi,
                    $aspek?->kehadiran_rapat,
                    $aspek?->kegiatan_progja,
                    $aspek?->inisiatif_bantuan ? 'Ya' : 'Tidak',
                    $aspek?->total_poin,
                    $aspek?->nilai,
                    $aspek ? PenilaiSkor::predikat((float) $aspek->nilai) : null,
                    $aspek?->catatan_terhadap_anggota,
                ], ';');
            }

            fclose($handle);
        }, $namaBerkas, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export penilaian yang sedang difilter ke PDF (tata letak rapi).
     */
    public function exportPdf(Request $request)
    {
        // Hanya komc, ketua, guru, dan admin/mpk yang bisa export PDF
        if (! $request->user()->canEditPenilaian() && ! $request->user()->isAdminOrMpk()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk ekspor PDF.');
        }

        $rows = $this->filter->build($request, $request->user())
            ->with(['aspect', 'divisionMember.division', 'user'])
            ->orderBy('id')
            ->get();

        $pdf = app(Pdf::class)
            ->loadView('admin.penilaian-pdf', [
                'penilaians' => $rows,
                'filterAktif' => $this->filter->parameterAktif($request),
                'generatedAt' => now()->translatedFormat('d M Y, H:i'),
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download('penilaian-osis-'.now()->format('Ymd-His').'.pdf');
    }

    /**
     * Ringkasan angka untuk kartu statistik di halaman penilaian.
     *
     * @param  Builder<Assesment>  $query
     * @return array{jumlah: int, rataNilai: float, tertinggi: float, terendah: float}
     */
    private function ringkasan(Builder $query): array
    {
        $nilai = $query->with('aspect')
            ->get()
            ->pluck('aspect')
            ->filter()
            ->pluck('nilai')
            ->filter(fn (?float $n): bool => $n !== null);

        return [
            'jumlah' => $nilai->count(),
            'rataNilai' => $nilai->isEmpty() ? 0.0 : round($nilai->avg(), 2),
            'tertinggi' => $nilai->isEmpty() ? 0.0 : round($nilai->max(), 2),
            'terendah' => $nilai->isEmpty() ? 0.0 : round($nilai->min(), 2),
        ];
    }

    /**
     * API: Dapatkan data GDS vs Piket RO per divisi untuk chart.
     * Hanya komc, ketua, guru, dan admin/mpk yang bisa mengakses.
     */
    public function getGdsPiketData(Request $request)
    {
        $user = $request->user();

        // Hanya komc, ketua, guru, dan admin/mpk yang bisa melihat chart
        if (! $user->canViewChart() && ! $user->isAdminOrMpk()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $divisi = $request->query('divisi');
        $type = $request->query('type', 'bar');

        $query = Assesment::query()
            ->with(['divisionMember.division', 'aspect']);

        if ($divisi) {
            $query->whereHas(
                'divisionMember',
                fn (Builder $member) => $member->where('id_sekbid', $divisi)
            );
        }

        $data = $query->get()
            ->groupBy(
                fn ($item) => $item->divisionMember?->division?->nama_sekbid ?? 'Tanpa Divisi'
            );

        $labels = [];
        $gdsData = [];
        $piketData = [];

        foreach ($data as $divisiName => $items) {
            $labels[] = $divisiName;
            $gdsAvg = $items->avg(fn ($item) => $item->aspect->pluck('keaktifan_gds')?->first() ?? 0);
            $piketAvg = $items->avg(fn ($item) => $item->aspect->pluck('piket_ro')?->first() ?? 0);
            $gdsData[] = round($gdsAvg, 2);
            $piketData[] = round($piketAvg, 2);
        }

        return response()->json([
            'labels' => $labels,
            'gdsData' => $gdsData,
            'piketData' => $piketData,
        ]);
    }

    /**
     * API: Dapatkan data distribusi poin per aspek penilaian.
     * Hanya komc, ketua, guru, dan admin/mpk yang bisa mengakses.
     */
    public function getAspekData(Request $request)
    {
        $user = $request->user();

        // Hanya komc, ketua, guru, dan admin/mpk yang bisa mengakses chart
        if (! $user->canViewChart() && ! $user->isAdminOrMpk()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $divisi = $request->query('divisi');

        $query = Assesment::query()
            ->with(['aspect', 'divisionMember.division']);

        if ($divisi) {
            $query->whereHas(
                'divisionMember',
                fn (Builder $member) => $member->where('id_sekbid', $divisi)
            );
        }

        $data = $query->get()
            ->groupBy(
                fn ($item) => $item->aspect?->id_aspek_penilaian !== null
                    ? 'Aspek '.$item->aspect->id
                    : 'Tanpa Aspek'
            );

        $labels = [];
        $totalPoinData = [];

        foreach ($data as $aspekName => $items) {
            $labels[] = $aspekName;
            $totalPoinData[] = $items->sum(fn ($item) => $item->aspect->pluck('total_poin')?->first() ?? 0);
        }

        return response()->json([
            'aspekLabels' => $labels,
            'totalPoinData' => $totalPoinData,
        ]);
    }

    /**
     * API: Dapatkan statistik per divisi.
     * Hanya komc, ketua, guru, dan admin/mpk yang bisa mengakses.
     */
    public function getDivisiStats(Request $request)
    {
        $user = $request->user();

        // Hanya komc, ketua, guru, dan admin/mpk yang bisa mengakses
        if (! $user->canViewChart() && ! $user->isAdminOrMpk()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $divisi = $request->query('divisi');

        $query = Assesment::query()
            ->with(['aspect', 'divisionMember.division']);

        if ($divisi) {
            $query->whereHas(
                'divisionMember',
                fn (Builder $member) => $member->where('id_sekbid', $divisi)
            );
        }

        $divisions = $query->get()
            ->groupBy(
                fn ($item) => $item->divisionMember?->division?->nama_sekbid ?? 'Tanpa Divisi'
            );

        $labels = [];
        $jumlahData = [];
        $rataData = [];
        $tertinggiData = [];
        $terendahData = [];

        foreach ($divisions as $divName => $items) {
            $labels[] = $divName;
            $jumlahData[] = $items->count();
            $rataData[] = round($items->avg(fn ($i) => $i->aspect->pluck('nilai')?->first() ?? 0), 2);
            $tertinggiData[] = round($items->max(fn ($i) => $i->aspect->pluck('nilai')?->first() ?? 0), 2);
            $terendahData[] = round($items->min(fn ($i) => $i->aspect->pluck('nilai')?->first() ?? 0), 2);
        }

        return response()->json([
            'labels' => $labels,
            'jumlah' => $jumlahData,
            'rata_nilai' => $rataData,
            'tertinggi' => $tertinggiData,
            'terendah' => $terendahData,
        ]);
    }
}
