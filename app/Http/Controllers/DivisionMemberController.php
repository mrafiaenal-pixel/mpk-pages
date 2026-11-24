<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Division $division): RedirectResponse
    {
        $this->authorizeDivision($division->id);

        $validated = $request->validate([
            'nama_anggota' => 'required|string|max:100',
        ]);

        $division->members()->create($validated);

        return redirect()->route('admin.sekbid')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, DivisionMember $member): RedirectResponse
    {
        $validated = $request->validate([
            'nama_anggota' => 'required|string|max:100',
            'id_sekbid' => 'required|exists:tb_sekbid,id',
        ]);

        $this->authorizeDivision($member->id_sekbid);
        $this->authorizeDivision((int) $validated['id_sekbid']);

        $member->update($validated);

        return redirect()->route('admin.sekbid')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(DivisionMember $member): RedirectResponse
    {
        $this->authorizeDivision($member->id_sekbid);

        $member->delete();

        return redirect()->route('admin.sekbid')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    private function authorizeDivision(int|string|null $divisionId): void
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->canManageDivisionMembers($divisionId), 403);
    }
}
