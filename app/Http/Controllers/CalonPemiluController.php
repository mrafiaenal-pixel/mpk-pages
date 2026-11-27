<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalonRequest;
use App\Http\Requests\UpdateCalonRequest;
use App\Models\CalonPemilu;
use App\Services\FotoUploadService;
use App\Services\SiteSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CalonPemiluController extends Controller
{
    public function __construct(
        private readonly FotoUploadService $fotoUploader,
        private readonly SiteSettings $siteSettings,
    ) {}

    /**
     * Kelola calon sekaligus pantau jumlah suara yang masuk.
     */
    public function index(): View
    {
        $calons = CalonPemilu::query()
            ->withCount('suara')
            ->orderBy('nomor_urut')
            ->get();

        return view('admin.pemilu', [
            'calons' => $calons,
            'maksimalCalon' => $this->siteSettings->maksimalCalon,
        ]);
    }

    /**
     * Tambahkan calon selama kuota pengaturan belum penuh.
     */
    public function store(StoreCalonRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->fotoUploader->store($request->file('foto'), 'calon');
        }

        CalonPemilu::query()->create($data);

        return redirect()->route('admin.calon.index')
            ->with('success', 'Calon berhasil ditambahkan.');
    }

    /**
     * Ubah data calon, termasuk mengganti foto profilnya.
     */
    public function update(UpdateCalonRequest $request, CalonPemilu $calon): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $this->fotoUploader->delete($calon->foto);
            $data['foto'] = $this->fotoUploader->store($request->file('foto'), 'calon');
        }

        $calon->update($data);

        return redirect()->route('admin.calon.index')
            ->with('success', 'Data calon berhasil diperbarui.');
    }

    /**
     * Hapus calon beserta fotonya.
     */
    public function destroy(CalonPemilu $calon): RedirectResponse
    {
        $this->fotoUploader->delete($calon->foto);
        $calon->delete();

        return redirect()->route('admin.calon.index')
            ->with('success', 'Calon berhasil dihapus.');
    }
}
