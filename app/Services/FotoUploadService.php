<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Simpan dan hapus berkas gambar pada disk publik.
 */
final class FotoUploadService
{
    /**
     * Simpan gambar dengan nama yang dibuat Laravel agar tidak bentrok.
     */
    public function store(UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }

    /**
     * Hapus gambar lama saat foto diganti atau data dihapus.
     */
    public function delete(?string $path): void
    {
        if ($path !== null && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
