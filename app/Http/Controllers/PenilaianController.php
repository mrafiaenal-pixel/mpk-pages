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
}
