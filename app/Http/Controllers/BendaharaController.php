<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBendaharaRequest;
use App\Models\Bendahara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

class BendaharaController extends Controller
{
    public function __construct()
    {
        new Middleware('role:bendahara|ketua|komc|guru|admin|mpk');
    }

    /**
     * Display a list of cash transactions.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Bendahara::with('user');

        // Filter by type (masuk/keluar)
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->input('tipe'));
        }

        // Filter by date range
        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->input('dari'));
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->input('sampai'));
        }

        // Filter by search
        if ($search = trim($request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('username', 'like', '%' . $search . '%');
                    });
            });
        }

        $uangKas = $query->latest('tanggal')->paginate(10);

        return view('admin.bendahara', [
            'uangKas' => $uangKas,
            'filterAktif' => array_filter([
                'q' => $request->input('q'),
                'tipe' => $request->input('tipe'),
                'dari' => $request->input('dari'),
                'sampai' => $request->input('sampai'),
            ], fn($value): bool => $value !== null && $value !== ''),
            'userRole' => $user->role,
        ]);
    }

    /**
     * Store a new cash transaction.
     */
    public function store(StoreBendaharaRequest $request)
    {
        $validated = $request->validated();

        $validated['id_users'] = Auth::id();

        Bendahara::create($validated);

        return redirect()->route('bendahara.index')
            ->with('success', "Transaksi kas {$validated['tipe']} Rp " . number_format($validated['nominal'], 0, ',', '.') . ' berhasil dicatat.');
    }

    /**
     * Export cash transactions to CSV.
     */
    public function export(Request $request)
    {
        $uangKas = Bendahara::with('user');

        if ($request->filled('tipe')) {
            $uangKas->where('tipe', $request->input('tipe'));
        }
        if ($request->filled('dari')) {
            $uangKas->where('tanggal', '>=', $request->input('dari'));
        }
        if ($request->filled('sampai')) {
            $uangKas->where('tanggal', '<=', $request->input('sampai'));
        }

        $rows = $uangKas->latest('tanggal')->get();

        $namaBerkas = 'uang-kas-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'wb');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Tanggal',
                'Nama User',
                'Keterangan',
                'Jenis',
                'Nominal',
            ], ';');

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->tanggal->format('Y-m-d H:i'),
                    $row->user?->username,
                    $row->keterangan,
                    $row->tipe,
                    'Rp ' . number_format($row->nominal, 0, ',', '.'),
                ], ';');
            }

            fclose($handle);
        }, $namaBerkas, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Get summary of cash flow (masuk - keluar).
     */
    public function summary(Request $request)
    {
        $query = Bendahara::query();

        if ($request->filled('dari')) {
            $query->where('tanggal', '>=', $request->input('dari'));
        }
        if ($request->filled('sampai')) {
            $query->where('tanggal', '<=', $request->input('sampai'));
        }

        $masuk = $query->where('tipe', 'masuk')->sum('nominal');
        $keluar = $query->where('tipe', 'keluar')->sum('nominal');
        $sisa = $masuk - $keluar;

        return response()->json([
            'masuk' => $masuk,
            'keluar' => $keluar,
            'sisa' => $sisa,
        ]);
    }
}
