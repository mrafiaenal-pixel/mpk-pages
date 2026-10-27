<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkProgramRequest;
use App\Models\WorkProgram;
use Barryvdh\LaravelDompdf\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WorkProgramController extends Controller
{
    /**
     * Daftar kegiatan OSIS / MPR dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $query = WorkProgram::query()->latest('id')
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status_progja', $request->input('status')))
            ->when($search = trim((string) $request->input('q')), fn (Builder $q) => $q->where(function (Builder $inner) use ($search): void {
                $inner->where('nama_progja', 'like', "%{$search}%")
                    ->orWhere('deskripsi_progja', 'like', "%{$search}%");
            }));

        $kegiatans = $query->paginate(10)->withQueryString();

        return view('admin.kegiatan', [
            'kegiatans' => $kegiatans,
            'statistik' => $this->ringkasan(),
        ]);
    }

    /**
     * Simpan kegiatan OSIS / MPR baru.
     */
    public function store(StoreWorkProgramRequest $request)
    {
        WorkProgram::create($request->validated());

        return redirect()->route('admin.kegiatan')
            ->with('success', 'Kegiatan OSIS / MPR berhasil ditambahkan.');
    }

    /**
     * Perbarui kegiatan OSIS / MPR.
     */
    public function update(StoreWorkProgramRequest $request, WorkProgram $kegiatan)
    {
        $kegiatan->update($request->validated());

        return redirect()->route('admin.kegiatan')
            ->with('success', 'Kegiatan OSIS / MPR berhasil diperbarui.');
    }

    /**
     * Hapus kegiatan OSIS / MPR.
     */
    public function destroy(Request $request, WorkProgram $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatan', $request->only(['q', 'status']))
            ->with('success', 'Kegiatan OSIS / MPR berhasil dihapus.');
    }

    /**
     * Export daftar kegiatan ke CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $rows = WorkProgram::query()
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status_progja', $request->input('status')))
            ->latest('id')
            ->get();

        $namaBerkas = 'kegiatan-osis-mpr-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'wb');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID', 'Nama Kegiatan', 'Deskripsi', 'Status', 'Dibuat Pada',
            ], ';');

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->nama_progja,
                    $row->deskripsi_progja,
                    $row->status_progja,
                    $row->created_at?->format('Y-m-d H:i'),
                ], ';');
            }

            fclose($handle);
        }, $namaBerkas, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Export daftar kegiatan ke PDF (tata letak rapi).
     */
    public function exportPdf(Request $request)
    {
        $rows = WorkProgram::query()
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status_progja', $request->input('status')))
            ->latest('id')
            ->get();

        $pdf = app(Pdf::class)
            ->loadView('admin.kegiatan-pdf', [
                'kegiatans' => $rows,
                'statusFilter' => $request->input('status'),
                'generatedAt' => now()->translatedFormat('d M Y, H:i'),
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download('kegiatan-osis-mpr-'.now()->format('Ymd-His').'.pdf');
    }

    /**
     * @return array<string, int>
     */
    private function ringkasan(): array
    {
        $hitung = fn (string $status): int => WorkProgram::where('status_progja', $status)->count();

        return [
            'total' => WorkProgram::count(),
            'rencana' => $hitung('rencana'),
            'berjalan' => $hitung('berjalan') + $hitung('sedang_berjalan'),
            'terlaksana' => $hitung('terlaksana'),
            'batal' => $hitung('batal'),
        ];
    }
}
