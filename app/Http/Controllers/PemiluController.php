<?php

namespace App\Http\Controllers;

use App\Models\CalonPemilu;
use App\Models\SuaraPemilu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PemiluController extends Controller
{
    /**
     * Tampilkan calon dan hasil pemilu bagi pengguna yang sudah login.
     */
    public function index(Request $request): View
    {
        $calons = CalonPemilu::query()
            ->withCount('suara')
            ->orderBy('nomor_urut')
            ->get();
        $suaraSaya = $request->user()->suaraPemilu()->first();

        return view('pemilu.index', compact('calons', 'suaraSaya'));
    }

    /**
     * Simpan satu suara untuk setiap akun.
     */
    public function vote(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_calon' => ['required', 'exists:tb_calon_pemilu,id'],
        ]);

        $suara = SuaraPemilu::firstOrCreate(
            ['id_users' => $request->user()->getKey()],
            ['id_calon' => $validated['id_calon']],
        );

        if ((int) $suara->id_calon !== (int) $validated['id_calon']) {
            return redirect()->route('admin.pemilu.index')
                ->with('error', 'Anda sudah memberikan suara. Suara tidak dapat diubah.');
        }

        return redirect()->route('admin.pemilu.index')
            ->with('success', 'Terima kasih, suara Anda berhasil disimpan.');
    }
}
