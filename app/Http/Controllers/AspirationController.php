<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Aspiration;
use App\Models\KategoriAspirasi;
use Illuminate\Http\Request;

class AspirationController extends Controller
{
    //

    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengirim' => 'nullable|string|max:100',
            'is_anonim' => 'nullable|boolean',
            'judul_aspirasi' => 'required|string|max:200',
            'kode_kategori' => 'required|string|max:50',
            'deskripsi_aspirasi' => 'required|string|max:2000',
        ]);

        // Handle anonim checkbox
        $namaPengirim = $request->boolean('is_anonim') ? 'Anonim' : ($validated['nama_pengirim'] ?: 'Siswa');

        // Ensure category exists or create fallback if needed
        if (! KategoriAspirasi::where('kode_kategori', $validated['kode_kategori'])->exists()) {
            KategoriAspirasi::firstOrCreate(
                ['kode_kategori' => $validated['kode_kategori']],
                ['nama_kategori' => ucwords(str_replace('_', ' ', $validated['kode_kategori']))]
            );
        }

        Aspiration::create([
            'nama_pengirim' => $namaPengirim,
            'judul_aspirasi' => $validated['judul_aspirasi'],
            'deskripsi_aspirasi' => $validated['deskripsi_aspirasi'],
            'status_aspirasi' => 'menunggu',
            'id_users' => auth()->id() ?? null,
            'kode_kategori' => $validated['kode_kategori'],
        ]);

        return redirect()->back()->with('success', 'Aspirasi Anda berhasil dikirim dan akan segera ditinjau oleh MPK!');
    }
}
