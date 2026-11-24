@extends('layout.admin')

@section('title', 'Edit Profile')

@section('content')
@php
    $roleLabel = match($userRole) {
        'admin' => 'Administrator',
        'guru' => 'Guru Pembina',
        'ketua' => 'Ketua Umum',
        'komc' => 'Komisi C',
        'koma' => 'Komisi A',
        'komb' => 'Komisi B',
        'bendahara' => 'Bendahara',
        'anggota' => 'Anggota OSIS',
        default => ucfirst($userRole),
    };
@endphp

<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Profile</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui informasi akun dan keamanan password Anda.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
            <i class="fa-solid fa-user-gear"></i> {{ $roleLabel }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Form Card -->
        <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Informasi Akun</h3>
                    <p class="text-xs text-slate-400">Data yang Anda ubah akan langsung disimpan.</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-id-card"></i>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap -->
                <div class="sm:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap"
                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                        placeholder="Masukkan nama lengkap Anda"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all">
                    @error('nama_lengkap')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Username</label>
                    <input type="text" name="username"
                        value="{{ old('username', $user->username) }}"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all"
                        required>
                    @error('username')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all"
                        required>
                    @error('email')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="space-y-1.5 sm:col-span-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Role</label>
                    <input type="text" value="{{ $roleLabel }}" disabled
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-500 cursor-not-allowed">
                </div>

                <!-- Password Divider -->
                <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                    <h4 class="text-xs font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-blue-600"></i> Ubah Password (Opsional)
                    </h4>
                    <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                <!-- Current Password -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password Lama</label>
                    <input type="password" name="current_password" autocomplete="current-password"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all">
                    @error('current_password')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Password Baru</label>
                    <input type="password" name="new_password" autocomplete="new-password"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all">
                    @error('new_password')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1.5 sm:col-span-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" autocomplete="new-password"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all">
                </div>

                <div class="sm:col-span-2 flex justify-end pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Profile Summary Card -->
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5 h-fit">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xl font-black shadow-md shadow-blue-600/20">
                    {{ strtoupper(substr($user->nama_lengkap ?? $user->username ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-black text-slate-900 truncate">{{ $user->nama_lengkap ?? $user->username }}</p>
                    <p class="text-[11px] text-slate-400 truncate">&#64;{{ $user->username }}</p>
                </div>
            </div>

            <div class="space-y-3 pt-4 border-t border-slate-100">
                <div class="flex items-start justify-between gap-3">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email</span>
                    <span class="text-[11px] font-semibold text-slate-700 text-right break-all">{{ $user->email }}</span>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Role</span>
                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-200">{{ $roleLabel }}</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-[11px] text-blue-800 flex items-start gap-2.5">
                <i class="fa-solid fa-circle-info mt-0.5"></i>
                <p>Gunakan password yang mudah Anda ingat. Perubahan password akan berlaku pada login berikutnya.</p>
            </div>
        </div>
    </div>
</div>
@endsection
