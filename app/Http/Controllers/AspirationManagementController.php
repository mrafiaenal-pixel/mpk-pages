<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAspirasiRequest;
use App\Models\Aspiration;
use App\Models\KategoriAspirasi;
use Barryvdh\LaravelDompdf\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AspirationManagementController extends Controller
{
    /**
     * Daftar aspirasi dengan filter status, kategori, dan pencarian.
     */
    public function index(Request $request)
    {
        $query = $this->buildQuery($request);

        return view('admin.aspirasi', [
            'aspirasis' => $query->clone()->paginate(10)->withQueryString(),
            'kategoriList' => KategoriAspirasi::orderBy('nama_kategori')->get(),
            'statistik' => $this->ringkasan(),
        ]);
    }

    /**
     * Perbarui status sekaligus simpan tanggapan dan catatan internal MPK.
     */
    public function update(UpdateAspirasiRequest $request, Aspiration $aspirasi)
    {
        $aspirasi->update([
            ...$request->safe()->only(['status_aspirasi', 'tanggapan_mpk', 'catatan_internal']),
            'id_users_penangan' => $request->user()->id_users,
        ]);

        return redirect()->back()->with('success', "Tindak lanjut aspirasi #{$aspirasi->id_aspirasi} berhasil disimpan.");
    }

    /**
     * Export data aspirasi terkini ke CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $rows = $this->buildQuery($request)->orderBy('id_aspirasi')->get();

        $namaBerkas = 'aspirasi-osis-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'wb');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID', 'Tanggal Masuk', 'Pengirim', 'Judul', 'Deskripsi', 'Kategori',
                'Status', 'Tanggapan MPK', 'Catatan Internal', 'Ditangani Oleh',
            ], ';');

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->id_aspirasi,
                    $row->created_at?->format('Y-m-d H:i'),
                    $row->nama_pengirim,
                    $row->judul_aspirasi,
                    $row->deskripsi_aspirasi,
                    $row->category?->nama_kategori,
                    $row->status_aspirasi,
                    $row->tanggapan_mpk,
                    $row->catatan_internal,
                    $row->penangan?->username,
                ], ';');
            }

            fclose($handle);
        }, $namaBerkas, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Export aspirasi yang sedang difilter ke PDF (tata letak rapi).
     */
    public function exportPdf(Request $request)
    {
        $rows = $this->buildQuery($request)
            ->with(['category', 'penangan'])
            ->orderBy('id_aspirasi')
            ->get();

        $pdf = app(Pdf::class)
            ->loadView('admin.aspirasi-pdf', [
                'aspirasis' => $rows,
                'filterAktif' => array_filter([
                    'q' => $request->input('q'),
                    'status' => $request->input('status'),
                    'kategori' => $request->input('kategori'),
                    'dari' => $request->input('dari'),
                    'sampai' => $request->input('sampai'),
                ]),
                'generatedAt' => now()->translatedFormat('d M Y, H:i'),
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download('aspirasi-osis-'.now()->format('Ymd-His').'.pdf');
    }

    /**
     * @return Builder<Aspiration>
     */
    private function buildQuery(Request $request): Builder
    {
        return Aspiration::query()
            ->with(['category', 'penangan'])
            ->latest('id_aspirasi')
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status_aspirasi', $request->input('status')))
            ->when($request->filled('kategori'), fn (Builder $q) => $q->where('kode_kategori', $request->input('kategori')))
            ->when($request->filled('dari'), fn (Builder $q) => $q->whereDate('created_at', '>=', $request->input('dari')))
            ->when($request->filled('sampai'), fn (Builder $q) => $q->whereDate('created_at', '<=', $request->input('sampai')))
            ->when($search = trim((string) $request->input('q')), function (Builder $q) use ($search): void {
                $q->where(fn (Builder $inner) => $inner
                    ->where('judul_aspirasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi_aspirasi', 'like', "%{$search}%")
                    ->orWhere('nama_pengirim', 'like', "%{$search}%"));
            });
    }

    /**
     * @return array<string, int>
     */
    private function ringkasan(): array
    {
        $hitung = fn (string $status): int => Aspiration::where('status_aspirasi', $status)->count();

        return [
            'total' => Aspiration::count(),
            'menunggu' => $hitung('menunggu'),
            'proses' => $hitung('proses'),
            'selesai' => $hitung('selesai'),
            'ditolak' => $hitung('ditolak'),
            'belum_ditangani' => Aspiration::whereNull('tanggapan_mpk')->count(),
        ];
    }
}
