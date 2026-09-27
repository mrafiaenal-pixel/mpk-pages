<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali di Portal MPK!');
        }

        return back()->withErrors([
            'login' => 'Username/Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'email' => 'required|string|email|max:100|unique:users,email',
            'role' => 'required|in:admin,mpk,osis,siswa',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => 'accepted',
        ], [
            'username.unique' => 'Username ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'siswa',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang di Portal MPR!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
