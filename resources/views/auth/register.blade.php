<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - MPK Portal</title>

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
        <!-- LEFT: Form Area -->
        <div class="flex-1 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-20 xl:px-28 bg-white">
            <div class="w-full max-w-md mx-auto space-y-7">
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
                <div class="space-y-1.5">
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Buat Akun Baru</h1>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Lengkapi formulir di bawah ini untuk mendaftar akun portal MPK.
                    </p>
                </div>

                <!-- Error Alerts -->
                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
                            <span>Terdapat Kesalahan:</span>
                        </div>
                        <ul class="list-disc pl-5 font-normal text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Register Form -->
                <form action="{{ route('register.post') }}" method="POST" class="space-y-4" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Username -->
                    <div class="space-y-1">
                        <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <input type="text" id="username" name="username" required
                                   value="{{ old('username') }}"
                                   placeholder="Contoh: rafi_mpk"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden text-slate-900">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </div>
                            <input type="email" id="email" name="email" required
                                   value="{{ old('email') }}"
                                   placeholder="nama@sekolah.sch.id"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden text-slate-900">
                        </div>
                    </div>


                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                   placeholder="Min. 6 karakter"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden text-slate-900">
                        </div>

                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Konfirmasi Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input :type="showPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                                   placeholder="Ulangi sandi"
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm transition-all outline-hidden text-slate-900">
                        </div>
                    </div>

                    <!-- Toggle show password -->
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" @change="showPassword = !showPassword" class="rounded text-blue-600 focus:ring-blue-500">
                            <span>Tampilkan kata sandi</span>
                        </label>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="pt-1">
                        <label class="flex items-start gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="terms" value="1" required
                                   class="w-4 h-4 mt-0.5 text-blue-600 rounded-sm border-slate-300 focus:ring-blue-500">
                            <span class="text-xs text-slate-600 leading-tight">
                                Saya menyetujui <a href="#" class="text-blue-600 font-semibold underline">Syarat & Ketentuan</a> serta Kebijakan Privasi sistem MPK.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all text-sm flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Daftar Akun Sekarang</span>
                    </button>
                </form>

                <!-- Login Footer Link -->
                <div class="text-center pt-1 text-xs text-slate-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 transition-colors ml-1">
                        Masuk ke akun
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT: Decorative Brand Panel -->
        <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 p-12 text-white relative overflow-hidden flex-col justify-between">
            <div class="absolute -top-16 -right-16 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute bottom-10 -left-10 w-96 h-96 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center justify-between">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-blue-100 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Registrasi Terbuka
                </span>
                <span class="text-xs text-blue-200 font-medium">MPK Official</span>
            </div>

            <div class="relative z-10 my-auto py-12 text-center space-y-6">
                <div class="w-28 h-28 mx-auto rounded-3xl bg-white/10 backdrop-blur-xl border border-white/25 flex items-center justify-center text-5xl shadow-2xl shadow-blue-900/40">
                    <i class="fa-solid fa-user-shield text-white"></i>
                </div>

                <div class="space-y-3 max-w-sm mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight tracking-tight">
                        Bergabung Bersama Suara Siswa
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100/80 leading-relaxed font-normal">
                        Dapatkan akses resmi untuk memantau capaian program kerja sekbid OSIS dan menyampaikan ide perubahan untuk sekolah.
                    </p>
                </div>
            </div>

            <div class="relative z-10 pt-6 border-t border-white/15 flex items-center justify-between text-xs text-blue-200">
                <p>&copy; {{ date('Y') }} Majelis Perwakilan Kelas</p>
                <p class="font-semibold text-white">Transparan • Aspiratif • Terpercaya</p>
            </div>
        </div>
    </div>

</body>
</html>
