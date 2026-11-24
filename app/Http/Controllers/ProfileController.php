<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        return view('admin.profile', [
            'user' => $user,
            'userRole' => $user->role,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username,'.$user->id_users.',id_users',
            'nama_lengkap' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,'.$user->id_users.',id_users',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update basic info
        $user->username = $validated['username'];
        $user->nama_lengkap = $validated['nama_lengkap'] ?? $user->nama_lengkap;
        $user->email = $validated['email'];

        // Update password if provided
        if (! empty($validated['new_password'])) {
            if (empty($validated['current_password']) || ! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile berhasil diperbarui.');
    }
}
