<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFotoDokumentasiRequest;
use App\Http\Requests\UpdateFotoDokumentasiRequest;
use App\Models\FotoDokumentasi;
use App\Services\FotoUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FotoDokumentasiController extends Controller
{
    public function __construct(private readonly FotoUploadService $fotoUploader) {}

    /**
     * Kelola foto dokumentasi kegiatan yang tampil di halaman publik.
     */
    public function index(): View
    {
        $fotos = FotoDokumentasi::query()->latest()->get();

        return view('admin.dokumentasi', compact('fotos'));
    }

    /**
     * Unggah foto dokumentasi baru.
     */
    public function store(StoreFotoDokumentasiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['foto'] = $this->fotoUploader->store($request->file('foto'), 'dokumentasi');

        FotoDokumentasi::query()->create($data);

        return redirect()->route('admin.dokumentasi.index')
            ->with('success', 'Foto dokumentasi berhasil ditambahkan.');
    }

    /**
     * Ubah keterangan dan/atau ganti foto dokumentasi.
     */
    public function update(UpdateFotoDokumentasiRequest $request, FotoDokumentasi $fotoDokumentasi): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $this->fotoUploader->delete($fotoDokumentasi->foto);
            $data['foto'] = $this->fotoUploader->store($request->file('foto'), 'dokumentasi');
        }

        $fotoDokumentasi->update($data);

        return redirect()->route('admin.dokumentasi.index')
            ->with('success', 'Foto dokumentasi berhasil diperbarui.');
    }

    /**
     * Hapus foto dokumentasi beserta berkasnya.
     */
    public function destroy(FotoDokumentasi $fotoDokumentasi): RedirectResponse
    {
        $this->fotoUploader->delete($fotoDokumentasi->foto);
        $fotoDokumentasi->delete();

        return redirect()->route('admin.dokumentasi.index')
            ->with('success', 'Foto dokumentasi berhasil dihapus.');
    }
}
