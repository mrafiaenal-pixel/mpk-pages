<header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900 flex items-center gap-1.5">
                        MPR <span class="text-blue-600 text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 border border-blue-200/60 uppercase tracking-wider">Official</span>
                    </span>
                    <p class="text-xs text-slate-500 font-medium tracking-wide">Majelis Permusyawaratann Rayon</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="#beranda" class="px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50/60 transition-colors">
                    Beranda
                </a>
                <a href="#sekbid" class="px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50/60 transition-colors">
                    Seksi Bidang
                </a>
                <a href="#progja" class="px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50/60 transition-colors">
                    Program Kerja
                </a>
                <a href="#penilaian" class="px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50/60 transition-colors">
                    Penilaian
                </a>
                <a href="#aspirasi" class="px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50/60 transition-colors">
                    Aspirasi
                </a>
                <a href="#visimisi" class="px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-blue-600 rounded-lg hover:bg-blue-50/60 transition-colors">
                    Visi Misi
                </a>
            </nav>

            <!-- Right CTA Button -->
            <div class="hidden sm:flex items-center gap-3">
                <a href="#aspirasi" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:text-blue-600 hover:bg-slate-50 border border-slate-200 transition-all">
                    <i class="fa-solid fa-paper-plane text-xs text-blue-600"></i>
                    <span>Kirim Aspirasi</span>
                </a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/25 transition-all">
                        <i class="fa-solid fa-chart-pie text-xs"></i>
                        <span>Panel Admin</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/25 transition-all">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Login Pengurus</span>
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex md:hidden items-center">
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-hidden"
                        aria-label="Toggle Menu">
                    <i class="fa-solid text-xl" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-b border-slate-200 bg-white/95 backdrop-blur-md px-4 pt-3 pb-6 space-y-2 shadow-lg">
        <a href="#beranda" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-house w-6 text-slate-400"></i> Beranda
        </a>
        <a href="#sekbid" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-network-wired w-6 text-slate-400"></i> Seksi Bidang
        </a>
        <a href="#progja" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-list-check w-6 text-slate-400"></i> Program Kerja
        </a>
        <a href="#penilaian" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-chart-line w-6 text-slate-400"></i> Penilaian Kinerja
        </a>
        <a href="#aspirasi" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-envelope-open-text w-6 text-slate-400"></i> Kirim Aspirasi
        </a>
        <a href="#visimisi" @click="mobileMenuOpen = false" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
            <i class="fa-solid fa-users w-6 text-slate-400"></i> Visi Misi & Struktur
        </a>
        <div class="pt-2 space-y-2">
            <a href="#aspirasi" @click="mobileMenuOpen = false" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs">
                <i class="fa-solid fa-paper-plane text-xs text-blue-600"></i>
                <span>Kirim Aspirasi</span>
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-blue-600 text-white font-bold text-xs shadow-md shadow-blue-600/25">
                    <i class="fa-solid fa-chart-pie text-xs"></i>
                    <span>Panel Dashboard Admin</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-blue-600 text-white font-bold text-xs shadow-md shadow-blue-600/25">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>Login Pengurus</span>
                </a>
            @endauth
        </div>
    </div>
</header>
