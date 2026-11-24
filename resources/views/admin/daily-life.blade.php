@extends('layout.admin')

@section('title', 'Daily Life')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daily Life</h1>
            <p class="text-xs text-slate-500 mt-1">Catat aktivitas harian dan bangun rutinitas positif Anda.</p>
        </div>
        <a href="#form-aktivitas" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Catat Aktivitas</span>
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Catatan</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-note-sticky"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalAktivitas }}</p>
                <p class="text-xs text-blue-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-calendar-day text-[10px]"></i> Aktivitas tercatat
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rutinitas Positif</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-seedling"></i>
                </div>
            </div>
            <div>
                <p class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $totalRutinitasPositif }}</p>
                <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> Kebiasaan baik
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aktivitas Terbaru</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <div>
                <p class="text-lg font-black text-slate-900 tracking-tight truncate">{{ $dailyLives->first()?->judul ?? '-' }}</p>
                <p class="text-xs text-amber-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-tag text-[10px]"></i> {{ $dailyLives->first()?->kategori ?? 'Belum ada aktivitas' }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Form Card -->
        <div id="form-aktivitas" class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5 h-fit">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Catat Aktivitas Baru</h3>
                    <p class="text-xs text-slate-400">Isi detail aktivitas harian Anda.</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.daily-life.store') }}" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Piket pagi"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all"
                        required>
                    @error('judul')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Ceritakan aktivitas Anda..."
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all resize-none"
                        required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all"
                        required>
                    @error('tanggal')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</label>
                    <select name="kategori"
                        class="w-full px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden transition-all">
                        <option value="umum" {{ old('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="rapat" {{ old('kategori') == 'rapat' ? 'selected' : '' }}>Rapat</option>
                        <option value="piket" {{ old('kategori') == 'piket' ? 'selected' : '' }}>Piket</option>
                        <option value="belajar" {{ old('kategori') == 'belajar' ? 'selected' : '' }}>Belajar</option>
                        <option value="rutinitas_positif" {{ old('kategori') == 'rutinitas_positif' ? 'selected' : '' }}>Rutinitas Positif</option>
                    </select>
                    @error('kategori')
                        <p class="text-[11px] font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Aktivitas</span>
                </button>
            </form>
        </div>

        <!-- History Table -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Riwayat Aktivitas Harian</h3>
                    <p class="text-xs text-slate-500">Daftar seluruh aktivitas yang sudah Anda catat.</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 w-fit">
                    {{ $dailyLives->total() }} Data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Tanggal</th>
                            <th class="py-3.5 px-6">Judul</th>
                            <th class="py-3.5 px-6">Kategori</th>
                            <th class="py-3.5 px-6">Deskripsi</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dailyLives as $dailyLife)
                            @php
                                $badgeClass = match($dailyLife->kategori) {
                                    'rutinitas_positif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'kegiatan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'rapat' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'piket' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'belajar' => 'bg-violet-50 text-violet-700 border-violet-200',
                                    default => 'bg-slate-100 text-slate-600 border-slate-200',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                                    {{ $dailyLife->tanggal?->translatedFormat('d M Y') ?? '-' }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-800">{{ $dailyLife->judul }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded-md border text-[11px] font-semibold {{ $badgeClass }}">
                                        {{ $dailyLife->kategori }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-600 max-w-xs">
                                    {{ \Illuminate\Support\Str::limit($dailyLife->deskripsi, 60) }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <form method="POST" action="{{ route('admin.daily-life.destroy', $dailyLife) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white text-[11px] font-bold border border-red-100 transition-colors"
                                            onclick="return confirm('Hapus aktivitas ini?')">
                                            <i class="fa-solid fa-trash text-[10px]"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center">
                                    <i class="fa-regular fa-calendar-xmark text-3xl text-slate-300"></i>
                                    <p class="text-xs text-slate-400 mt-2">Belum ada aktivitas harian. Mulai catat rutinitas positif pertama Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($dailyLives->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $dailyLives->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
