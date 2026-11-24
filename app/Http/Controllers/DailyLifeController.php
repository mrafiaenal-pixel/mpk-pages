<?php

namespace App\Http\Controllers;

use App\Models\DailyLife;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyLifeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $dailyLives = DailyLife::where('id_users', $user->id_users)
            ->latest('tanggal')
            ->paginate(10);

        $totalAktivitas = DailyLife::where('id_users', $user->id_users)->count();
        $totalRutinitasPositif = DailyLife::where('id_users', $user->id_users)
            ->where('kategori', 'rutinitas_positif')
            ->count();

        return view('admin.daily-life', [
            'dailyLives' => $dailyLives,
            'totalAktivitas' => $totalAktivitas,
            'totalRutinitasPositif' => $totalRutinitasPositif,
            'userRole' => $user->role,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:2000',
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:50',
        ]);

        DailyLife::create([
            'id_users' => Auth::id(),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'tanggal' => $validated['tanggal'],
            'kategori' => $validated['kategori'],
        ]);

        return redirect()->route('admin.daily-life.index')
            ->with('success', 'Aktivitas harian berhasil dicatat.');
    }

    public function destroy(DailyLife $dailyLife)
    {
        $user = Auth::user();

        if ($dailyLife->id_users !== $user->id_users && ! $user->isAdmin()) {
            abort(403);
        }

        $dailyLife->delete();

        return redirect()->route('admin.daily-life.index')
            ->with('success', 'Aktivitas harian berhasil dihapus.');
    }
}
