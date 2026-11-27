<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSiteSettingsRequest;
use App\Models\ProfilKepengurusan;
use App\Models\SchoolProfile;
use App\Models\VissionMission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PengaturanController extends Controller
{
    /**
     * Tampilkan formulir pengaturan identitas organisasi.
     */
    public function index(): View
    {
        return view('admin.pengaturan');
    }

    /**
     * Simpan identitas, visi misi, komisi, kuota calon, dan struktur pengurus.
     */
    public function update(UpdateSiteSettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data): void {
            $profil = SchoolProfile::firstOrNew([]);
            $profil->fill([
                'nama_sekolah' => $data['nama_sekolah'],
                'email_sekolah' => $data['email_sekolah'],
                'nama_mpk' => $data['singkatan'],
                'nama_lengkap' => $data['nama_lengkap'],
                'descripsi_profile' => $data['descripsi_profile'] ?? null,
                'komisi' => array_values($data['komisi']),
                'maksimal_calon' => $data['maksimal_calon'],
            ]);
            $profil->save();

            $visiMisi = VissionMission::firstOrNew([]);
            $visiMisi->fill([
                'visi' => $data['visi'],
                'misi' => implode("\n", array_values(array_filter(
                    array_map('trim', preg_split('/\r?\n/', $data['misi']) ?: []),
                ))),
            ]);
            $visiMisi->save();

            $keptIds = [];
            foreach ($data['pengurus'] as $item) {
                $pengurus = ! empty($item['id'])
                    ? ProfilKepengurusan::query()->find($item['id']) ?? new ProfilKepengurusan
                    : new ProfilKepengurusan;

                $pengurus->fill([
                    'nama_pengurus' => $item['nama_pengurus'],
                    'jabatan_pengurus' => $item['jabatan_pengurus'],
                    'komisi_pengurus' => $item['komisi_pengurus'],
                ]);
                $pengurus->save();

                $keptIds[] = $pengurus->getKey();
            }

            if ($keptIds !== []) {
                ProfilKepengurusan::query()->whereNotIn('id', $keptIds)->delete();
            }
        });

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan organisasi berhasil disimpan.');
    }
}
