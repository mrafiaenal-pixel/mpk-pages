@php
    $user = auth()->user();
    $roleName = $user?->roleLabel() ?? 'Administrator';
    $memilikiPeran = fn (array $roles): bool => $user?->hasAnyRole($roles) ?? false;
@endphp

<!-- Brand / Header Sidebar -->
<div class="h-20 flex items-center px-6 border-b border-slate-100 gap-3">
    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-blue-900 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-blue-500/20">
        <i class="fa-solid fa-graduation-cap"></i>
    </div>
    <div class="min-w-0 flex-1">
        <h1 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-1.5">
            {{ $site->singkatan }} <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-semibold border border-blue-200 truncate">Portal</span>
        </h1>
        <p class="text-[11px] text-slate-400 font-medium truncate">Panel Pengawasan</p>
    </div>
</div>

<!-- Nav Links -->
<div class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
    <!-- Menu Utama -->
    <div class="space-y-1">
        <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Menu Utama</p>
        
        {{-- Dashboard (Semua Role) --}}
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Dashboard</span>
        </a>

        {{-- Kegiatan OSIS / organisasi (Admin, Ketua, Kom C, Guru) --}}
        @if($memilikiPeran(['admin', 'ketua', 'komc', 'koma', 'komb', 'guru']))
            <a href="{{ route('admin.kegiatan') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.kegiatan.*') || request()->routeIs('admin.kegiatan') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-calendar-days w-5 text-center text-sm {{ request()->routeIs('admin.kegiatan.*') || request()->routeIs('admin.kegiatan') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Kegiatan OSIS / {{ $site->singkatan }}</span>
            </a>
        @endif

        {{-- Seksi Bidang (Admin, Ketua, Kom C, Guru) --}}
        @if($memilikiPeran(['admin', 'ketua', 'komc', 'koma', 'komb', 'guru']))
            <a href="{{ route('admin.sekbid') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.sekbid') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-network-wired w-5 text-center text-sm {{ request()->routeIs('admin.sekbid') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Seksi Bidang</span>
            </a>
        @endif

        {{-- Anggota (dikelola terpisah dari Profil MPR) --}}
        @if($memilikiPeran(['admin', 'ketua', 'komc']))
            <a href="{{ route('admin.anggota.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.anggota*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-users w-5 text-center text-sm {{ request()->routeIs('admin.anggota*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Anggota</span>
            </a>
        @endif

        {{-- Pemilu (semua pengguna yang login) --}}
        @if($user !== null)
            <a href="{{ route('admin.pemilu.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.pemilu*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-vote-big w-5 text-center text-sm {{ request()->routeIs('admin.pemilu*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Pemilu</span>
            </a>
        @endif

        {{-- Penilaian Kinerja --}}
        {{-- Ketua, Kom C, Guru, Admin, Bendahara, Anggota bisa input/lihat sesuai hak akses --}}
        @if($user !== null)
            <a href="{{ route('admin.penilaian') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.penilaian*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-award w-5 text-center text-sm {{ request()->routeIs('admin.penilaian*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Penilaian Kinerja</span>
            </a>
        @endif

        {{-- Rekap Visual (Admin, Ketua, Kom C, Guru) --}}
        @if($memilikiPeran(['admin', 'ketua', 'komc', 'koma', 'komb', 'guru']))
            <a href="{{ route('admin.rekap') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.rekap*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-chart-column w-5 text-center text-sm {{ request()->routeIs('admin.rekap*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Rekap Visual</span>
            </a>
        @endif

        {{-- Aspirasi Masuk (Kom B, Kom C, Ketua, Guru) --}}
        @if($memilikiPeran(['admin', 'komb', 'komc', 'ketua', 'guru']))
            <a href="{{ route('admin.aspirasi') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.aspirasi*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-inbox w-5 text-center text-sm {{ request()->routeIs('admin.aspirasi*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Aspirasi Masuk</span>
            </a>
        @endif
    </div>

    <!-- Keuangan & Kas -->
    @if($memilikiPeran(['admin', 'bendahara', 'ketua']))
        <div class="space-y-1 pt-4 border-t border-slate-100">
            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Keuangan</p>
            <a href="{{ route('admin.bendahara.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.bendahara.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-wallet w-5 text-center text-sm {{ request()->routeIs('admin.bendahara.*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Uang Kas</span>
            </a>
        </div>
    @endif

    <!-- Profile & Daily Life (Semua Role) -->
    <div class="space-y-1 pt-4 border-t border-slate-100">
        <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Akun Saya</p>
        <a href="{{ route('admin.profile.index') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.profile*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i class="fa-solid fa-user-gear w-5 text-center text-sm {{ request()->routeIs('admin.profile*') ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Edit Profile</span>
        </a>
        <a href="{{ route('admin.daily-life.index') }}" 
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.daily-life*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
            <i class="fa-solid fa-calendar-check w-5 text-center text-sm {{ request()->routeIs('admin.daily-life*') ? 'text-white' : 'text-slate-400' }}"></i>
            <span>Daily Life</span>
        </a>
    </div>

    <!-- Profil MPR (Ketua & Kom C) -->
    @if($memilikiPeran(['admin', 'ketua', 'komc']))
        <div class="space-y-1 pt-4 border-t border-slate-100">
            <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Pengaturan</p>
            <a href="{{ route('admin.pengaturan.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.pengaturan*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-gear w-5 text-center text-sm {{ request()->routeIs('admin.pengaturan*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Profil MPR</span>
            </a>
            <a href="{{ route('admin.calon.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.calon*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-users-line w-5 text-center text-sm {{ request()->routeIs('admin.calon*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Kelola Calon</span>
            </a>
            <a href="{{ route('admin.dokumentasi.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.dokumentasi*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-images w-5 text-center text-sm {{ request()->routeIs('admin.dokumentasi*') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Foto Dokumentasi</span>
            </a>
        </div>
    @endif

    <!-- Tautan Eksternal / Publik -->
    <div class="space-y-1 pt-4 border-t border-slate-100">
        <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Tautan Lainnya</p>
        <a href="{{ url('/') }}" target="_blank" 
           class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center text-slate-400"></i>
            <span>Lihat Halaman Publik</span>
        </a>
    </div>
</div>

<!-- User Mini Profile & Logout -->
<div class="p-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between gap-2">
    <div class="flex items-center gap-3 min-w-0">
        <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
            {{ strtoupper(substr($user->username ?? 'A', 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="text-xs font-bold text-slate-800 truncate">{{ $user->nama_lengkap ?? $user->username ?? 'Administrator' }}</p>
            <p class="text-[10px] text-blue-600 font-semibold truncate" title="{{ $roleName }}">
                {{ $roleName }}
            </p>
        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="shrink-0">
        @csrf
        <button type="submit" title="Keluar" 
                class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors cursor-pointer">
            <i class="fa-solid fa-right-from-bracket text-sm"></i>
        </button>
    </form>
</div>
