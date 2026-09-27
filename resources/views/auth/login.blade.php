<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - MPK Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 selection:bg-blue-600 selection:text-white">

    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- LEFT: Form Area (55% width on desktop) -->
        <div class="flex-1 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-20 xl:px-28 bg-white">
            <div class="w-full max-w-md mx-auto space-y-8">
                <!-- Brand Logo & Back to Home -->
                <div class="flex items-center justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-slate-900 flex items-center gap-1.5">
                                MPK <span class="text-blue-600 text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 border border-blue-200/60 uppercase tracking-wider">Portal</span>
                            </span>
                            <p class="text-xs text-slate-400 font-medium tracking-wide">Majelis Perwakilan Kelas</p>
                        </div>
                    </a>

                    <a href="{{ url('/') }}" class="text-xs font-semibold text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Beranda
                    </a>
                </div>

                <!-- Header Text -->
                <div class="space-y-2">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Masukkan kredensial akun Anda untuk mengakses sistem penilaian kinerja dan manajemen aspirasi MPK.
                    </p>
                </div>

                <!-- Session / Error Alerts -->
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
                            <span>Gagal Masuk:</span>
                        </div>
                        <ul class="list-disc pl-5 font-normal text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Username or Email -->
                    <div class="space-y-1.5">
                        <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Username atau Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <input type="text" id="login" name="login" required autofocus
                                   value="{{ old('login') }}"
                                   placeholder="Masukkan username atau email akun"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden text-slate-900 placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-11 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden text-slate-900">
                            <button type="button" @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                                    tabindex="-1">
                                <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" value="1" 
                                   class="w-4 h-4 text-blue-600 rounded-sm border-slate-300 focus:ring-blue-500">
                            <span class="text-xs font-medium text-slate-600">Ingat sesi saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all text-sm flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Masuk Sekarang</span>
                    </button>
                </form>

                <!-- Demo Credentials Box -->
                <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100 text-xs text-slate-600 space-y-1">
                    <p class="font-bold text-blue-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-blue-600"></i> Akun Demo Tersedia:
                    </p>
                    <p class="text-slate-600">Username: <code class="font-bold text-slate-800 bg-white px-1.5 py-0.5 rounded border border-blue-200">admin_mpk</code> | Sandi: <code class="font-bold text-slate-800 bg-white px-1.5 py-0.5 rounded border border-blue-200">password</code></p>
                </div>

                <!-- Register Footer Link -->
                <div class="text-center pt-2 text-xs text-slate-500">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 transition-colors ml-1">
                        Daftar sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT: Decorative Brand Panel (45% width on desktop) -->
        <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 p-12 text-white relative overflow-hidden flex-col justify-between">
            <!-- Decorative Hexagons & Circles Background -->
            <div class="absolute -top-16 -right-16 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute bottom-10 -left-10 w-96 h-96 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/3 right-10 w-48 h-48 border border-white/15 rounded-3xl rotate-12 pointer-events-none"></div>
            <div class="absolute top-1/4 right-20 w-32 h-32 border border-white/20 rounded-2xl -rotate-6 pointer-events-none"></div>

            <!-- Top Brand Badge -->
            <div class="relative z-10 flex items-center justify-between">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-blue-100 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Sistem Terintegrasi v2.0
                </span>
                <span class="text-xs text-blue-200 font-medium">MPK Official</span>
            </div>

            <!-- Center Mockup / Artwork -->
            <div class="relative z-10 my-auto py-12 text-center space-y-6">
                <!-- Large Hexagon Logo Badge -->
                <div class="w-28 h-28 mx-auto rounded-3xl bg-white/10 backdrop-blur-xl border border-white/25 flex items-center justify-center text-5xl shadow-2xl shadow-blue-900/40">
                    <i class="fa-solid fa-shield-halved text-white"></i>
                </div>

                <div class="space-y-3 max-w-sm mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight tracking-tight">
                        Transparansi & Akuntabilitas Siswa
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100/80 leading-relaxed font-normal">
                        Platform resmi pemantauan kinerja 10 Seksi Bidang OSIS, rekapitulasi nilai aspek kegiatan, dan penampungan aspirasi warga sekolah.
                    </p>
                </div>
            </div>

            <!-- Bottom Quote -->
            <div class="relative z-10 pt-6 border-t border-white/15 flex items-center justify-between text-xs text-blue-200">
                <p>&copy; {{ date('Y') }} Majelis Perwakilan Kelas</p>
                <p class="font-semibold text-white">Integritas • Advokasi • Dedikasi</p>
            </div>
        </div>
    </div>

</body>
</html>
