<?php

namespace App\Http\Controllers;

use App\Models\Assesment;
use App\Services\PenilaianFilter;
use App\Services\PenilaiSkor;
use Barryvdh\LaravelDompdf\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapController extends Controller
{
    public function __construct(private readonly PenilaianFilter $filter) {}

    /**
     * Halaman rekap visual: per sekbid, per aspek, dan tren waktu.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $penilaians = $this->filter->build($request, $user)
            ->with(['aspect', 'divisionMember.division'])
            ->get();

        return view('admin.rekap', [
            'statistikSekbid' => $this->rekapPerSekbid($penilaians),
            'statistikAspek' => $this->rekapPerAspek($penilaians),
            'trenBulanan' => $this->trenBulanan($penilaians),
            'anggotaTeratas' => $this->anggotaTeratas($penilaians),
            'divisiList' => $this->filter->divisiTersedia($user),
            'filterAktif' => $this->filter->parameterAktif($request),
            'totalPenilaian' => $penilaians->count(),
        ]);
    }

    /**
     * Export rekap per sekbid ke CSV (dibuka di Excel).
     */
    public function export(Request $request): StreamedResponse
    {
        $statistik = $this->rekapPerSekbid(
            $this->filter->build($request, $request->user())->with(['aspect', 'divisionMember.division'])->get()
        );

        $namaBerkas = 'rekap-penilaian-osis-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($statistik): void {
            $handle = fopen('php://output', 'wb');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Peringkat', 'Seksi Bidang', 'Anggota Dinilai', 'Jumlah Penilaian',
                'Rata-rata Nilai', 'Nilai Tertinggi', 'Nilai Terendah', 'Predikat',
            ], ';');

            foreach ($statistik as $index => $row) {
                fputcsv($handle, [
                    $index + 1,
                    $row['nama'],
                    $row['jumlah_anggota'],
                    $row['jumlah_penilaian'],
                    $row['rata_nilai'],
                    $row['tertinggi'],
                    $row['terendah'],
                    $row['predikat'],
                ], ';');
            }

            fclose($handle);
        }, $namaBerkas, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Export rekap per sekbid ke PDF (tata letak rapi).
     */
    public function exportPdf(Request $request)
    {
        $statistik = $this->rekapPerSekbid(
            $this->filter->build($request, $request->user())->with(['aspect', 'divisionMember.division'])->get()
        );
        $aspek = $this->rekapPerAspek(
            $this->filter->build($request, $request->user())->with(['aspect'])->get()
        );
        $tren = $this->trenBulanan(
            $this->filter->build($request, $request->user())->with(['aspect'])->get()
        );

        $pdf = app(Pdf::class)
            ->loadView('admin.rekap-pdf', [
                'statistikSekbid' => $statistik,
                'statistikAspek' => $aspek,
                'trenBulanan' => $tren,
                'filterAktif' => $this->filter->parameterAktif($request),
                'generatedAt' => now()->translatedFormat('d M Y, H:i'),
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download('rekap-penilaian-osis-'.now()->format('Ymd-His').'.pdf');
    }

    /**
     * Rata-rata nilai per sekbid.
     *
     * @param  Collection<int, Assesment>  $penilaians
     * @return Collection<int, array<string, mixed>>
     */
    private function rekapPerSekbid(Collection $penilaians): Collection
    {
        return $penilaians
            ->groupBy(fn ($item) => $item->divisionMember?->division?->id)
            ->map(function (Collection $grup, $divisiId): array {
                $nilai = $grup->pluck('aspect')->filter()->pluck('nilai')->filter(fn ($n): bool => $n !== null);

                $rata = $nilai->isEmpty() ? 0.0 : round($nilai->avg(), 2);

                return [
                    'id' => $divisiId,
                    'nama' => $grup->first()->divisionMember?->division?->nama_sekbid ?? 'Tanpa Sekbid',
                    'jumlah_penilaian' => $nilai->count(),
                    'jumlah_anggota' => $grup->pluck('id_anggota_sekbid')->unique()->count(),
                    'rata_nilai' => $rata,
                    'tertinggi' => $nilai->isEmpty() ? 0.0 : round($nilai->max(), 2),
                    'terendah' => $nilai->isEmpty() ? 0.0 : round($nilai->min(), 2),
                    'predikat' => PenilaiSkor::predikat($rata),
                ];
            })
            ->sortByDesc('rata_nilai')
            ->values();
    }

    /**
     * Rata-rata tiap aspek, dinormalisasi ke skala 0-100 agar sebanding.
     *
     * @param  Collection<int, Assesment>  $penilaians
     * @return Collection<int, array<string, mixed>>
     */
    private function rekapPerAspek(Collection $penilaians): Collection
    {
        $aspekTersedia = $penilaians->pluck('aspect')->filter();

        return collect(PenilaiSkor::aspekKeys())
            ->map(function (string $aspek) use ($aspekTersedia): array {
                $jumlah = $aspekTersedia
                    ->map(fn ($a): int => PenilaiSkor::poinAspek($aspek, $a->{$aspek}))
                    ->filter(fn (int $poin): bool => $poin > 0);

                $maksimum = max(PenilaiSkor::RUBRIK[$aspek]);

                return [
                    'key' => $aspek,
                    'label' => PenilaiSkor::LABEL_ASPEK[$aspek],
                    'rata' => $jumlah->isEmpty() ? 0.0 : round($jumlah->avg() / $maksimum * 100, 2),
                    'jumlah' => $aspekTersedia->count(),
                ];
            });
    }

    /**
     * Tren rata-rata nilai per bulan.
     *
     * @param  Collection<int, Assesment>  $penilaians
     * @return Collection<int, array<string, mixed>>
     */
    private function trenBulanan(Collection $penilaians): Collection
    {
        return $penilaians
            ->filter(fn ($item): bool => $item->aspect?->tanggal_penilaian !== null)
            ->groupBy(fn ($item): string => $item->aspect->tanggal_penilaian->format('Y-m'))
            ->map(function (Collection $grup): array {
                $nilai = $grup->pluck('aspect')->filter()->pluck('nilai')->filter(fn ($n): bool => $n !== null);

                return [
                    'periode' => $grup->first()->aspect->tanggal_penilaian->translatedFormat('M Y'),
                    'rata_nilai' => $nilai->isEmpty() ? 0.0 : round($nilai->avg(), 2),
                    'jumlah' => $nilai->count(),
                ];
            })
            ->sortBy('periode')
            ->values();
    }

    /**
     * Lima anggota dengan rata-rata nilai tertinggi.
     *
     * @param  Collection<int, Assesment>  $penilaians
     * @return Collection<int, array<string, mixed>>
     */
    private function anggotaTeratas(Collection $penilaians): Collection
    {
        return $penilaians
            ->groupBy('id_anggota_sekbid')
            ->map(function (Collection $grup): array {
                $nilai = $grup->pluck('aspect')->filter()->pluck('nilai')->filter(fn ($n): bool => $n !== null);
                $rata = $nilai->isEmpty() ? 0.0 : round($nilai->avg(), 2);

                return [
                    'nama' => $grup->first()->divisionMember?->nama_anggota ?? 'Tanpa Nama',
                    'divisi' => $grup->first()->divisionMember?->division?->nama_sekbid ?? '-',
                    'jumlah_penilaian' => $nilai->count(),
                    'rata_nilai' => $rata,
                    'predikat' => PenilaiSkor::predikat($rata),
                ];
            })
            ->sortByDesc('rata_nilai')
            ->take(5)
            ->values();
    }
}
