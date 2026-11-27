<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnggotaRequest;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    /**
     * Tampilkan daftar anggota seksi bidang yang terpisah dari profil MPR.
     */
    public function index(): View
    {
        $divisions = Division::query()
            ->with(['members' => fn ($query) => $query->orderBy('nama_anggota')])
            ->orderBy('id')
            ->get();

        return view('admin.anggota', compact('divisions'));
    }

    /**
     * Tambahkan anggota ke salah satu seksi bidang.
     */
    public function store(StoreAnggotaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $division = Division::findOrFail($validated['id_sekbid']);
        $this->authorizeDivision($division->id);

        $division->members()->create(['nama_anggota' => $validated['nama_anggota']]);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Hapus anggota dari seksi bidang.
     */
    public function destroy(DivisionMember $member): RedirectResponse
    {
        $this->authorizeDivision($member->id_sekbid);

        $member->delete();

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    private function authorizeDivision(int|string|null $divisionId): void
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->canManageDivisionMembers($divisionId), 403);
    }
}
