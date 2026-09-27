<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - MPK Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 bg-slate-100/70" x-data="{ sidebarOpen: false }">

    <div class="min-h-full flex">
        <!-- SIDEBAR FOR DESKTOP -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-white border-r border-slate-200/90 shrink-0 select-none">
            <!-- Brand -->
            <div class="h-20 flex items-center px-6 border-b border-slate-100 gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-1.5">
                        MPK <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-semibold border border-blue-200">Admin</span>
                    </h1>
                    <p class="text-[11px] text-slate-400 font-medium">Panel Pengawasan</p>
                </div>
            </div>

            <!-- Nav Links -->
            <div class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                <div class="space-y-1">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Menu Utama</p>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-pie w-5 text-center text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.sekbid') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.sekbid') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-network-wired w-5 text-center text-sm {{ request()->routeIs('admin.sekbid') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Seksi Bidang</span>
                    </a>

                    <a href="{{ route('admin.penilaian') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.penilaian') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-award w-5 text-center text-sm {{ request()->routeIs('admin.penilaian') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Penilaian Kinerja</span>
                    </a>

                    <a href="{{ route('admin.aspirasi') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.aspirasi') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-inbox w-5 text-center text-sm {{ request()->routeIs('admin.aspirasi') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Aspirasi Masuk</span>
                    </a>
                </div>

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
            <div class="p-4 border-t border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->username ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-blue-600 font-semibold uppercase">{{ auth()->user()->role ?? 'Admin' }}</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" 
                            class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- TOP NAVBAR -->
            <header class="h-20 bg-white border-b border-slate-200/90 px-4 sm:px-8 flex items-center justify-between gap-4 shrink-0">
                <!-- Mobile Menu Button & Search -->
                <div class="flex items-center gap-4 flex-1">
                    <button type="button" @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>

                    <div class="relative w-full max-w-md hidden sm:block">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" placeholder="Cari data sekbid, anggota, atau aspirasi..." 
                               class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all text-slate-800">
                    </div>
                </div>

                <!-- Right Quick Stats & User Profile -->
                <div class="flex items-center gap-3 sm:gap-5">
                    <!-- Notification Bell -->
                    <div class="relative">
                        <button class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 flex items-center justify-center text-sm border border-slate-200/60 transition-colors">
                            <i class="fa-regular fa-bell"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                        </button>
                    </div>

                    <!-- Date Badge -->
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/70 text-xs text-slate-500 font-medium">
                        <i class="fa-regular fa-calendar text-blue-600"></i>
                        <span>{{ now()->translatedFormat('d F Y') }}</span>
                    </div>

                    <!-- User Dropdown -->
                    <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                            {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->username ?? 'Admin' }}</p>
                            <p class="text-[10px] text-slate-400">{{ auth()->user()->email ?? 'admin@mpk.id' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MOBILE SIDEBAR OVERLAY -->
            <div x-show="sidebarOpen" 
                 class="relative z-50 lg:hidden" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="sidebarOpen = false"></div>
                <div class="fixed inset-y-0 left-0 flex w-full max-w-xs flex-col bg-white shadow-2xl">
                    <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <span class="text-base font-extrabold text-slate-900">MPK Admin</span>
                        </div>
                        <button @click="sidebarOpen = false" class="p-2 text-slate-400 hover:text-slate-600">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-700 hover:bg-blue-50">
                            <i class="fa-solid fa-chart-pie w-5 text-blue-600"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.sekbid') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-700 hover:bg-blue-50">
                            <i class="fa-solid fa-network-wired w-5 text-blue-600"></i> Seksi Bidang
                        </a>
                        <a href="{{ route('admin.penilaian') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-700 hover:bg-blue-50">
                            <i class="fa-solid fa-award w-5 text-blue-600"></i> Penilaian Kinerja
                        </a>
                        <a href="{{ route('admin.aspirasi') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-700 hover:bg-blue-50">
                            <i class="fa-solid fa-inbox w-5 text-blue-600"></i> Aspirasi Masuk
                        </a>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8">
                <!-- Flash Notification -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" 
                         class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
