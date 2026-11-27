@extends('layout.admin')

@section('title', 'Anggota')

@section('content')
    @php
        $totalAnggota = $divisions->sum(fn ($division) => $division->members->count());
    @endphp

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Anggota</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Kelola anggota seksi bidang secara terpisah dari struktur pengurus MPR.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                    <i class="fa-solid fa-users"></i> {{ $totalAnggota }} Anggota Terdaftar
                </span>
                <a href="{{ route('admin.pengaturan.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                    <i class="fa-solid fa-id-card text-slate-500"></i>
                    <span>Profil MPR</span>
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3 text-xs font-semibold">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tambah Anggota -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-900">Tambah Anggota</h2>
                    <p class="text-[11px] text-slate-400">Pilih seksi bidang lalu isi nama anggota baru.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.anggota.store') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                @csrf
                <div class="sm:col-span-5">
                    <label for="nama_anggota" class="block text-[11px] font-bold text-slate-500 mb-1">Nama Anggota <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama_anggota" name="nama_anggota" required maxlength="100"
                        value="{{ old('nama_anggota') }}" placeholder="Nama lengkap anggota"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                    @error('nama_anggota') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-5">
                    <label for="id_sekbid" class="block text-[11px] font-bold text-slate-500 mb-1">Seksi Bidang <span class="text-rose-500">*</span></label>
                    <select id="id_sekbid" name="id_sekbid" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden cursor-pointer">
                        <option value="" disabled @selected(! old('id_sekbid'))>— Pilih seksi bidang —</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}" @selected(old('id_sekbid') == $division->id)>{{ $division->nama_sekbid }}</option>
                        @endforeach
                    </select>
                    @error('id_sekbid') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 flex items-end justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Tambah</span>
                    </button>
                </div>
            </form>
        </section>

        <!-- Daftar Anggota per Seksi Bidang -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Daftar Anggota per Seksi Bidang</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Anggota di sini tidak memengaruhi profil pengurus MPR.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($divisions as $division)
                    <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <div class="sm:col-span-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-slate-900 leading-tight">{{ $division->nama_sekbid }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $division->members->count() }} anggota</p>
                                </div>
                            </div>
                        </div>
                        <div class="sm:col-span-8">
                            @forelse ($division->members as $member)
                                <div class="flex items-center justify-between gap-3 py-1.5 border-b border-slate-100 last:border-b-0">
                                    <span class="text-xs font-semibold text-slate-700">{{ $member->nama_anggota }}</span>
                                    <form method="POST" action="{{ route('admin.anggota.destroy', $member) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus anggota"
                                            onclick="return confirm('Hapus anggota ini?')"
                                            class="w-7 h-7 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer">
                                            <i class="fa-solid fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-1.5">Belum ada anggota pada seksi bidang ini.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-xs text-slate-400">Belum ada seksi bidang. Tambahkan seksi bidang terlebih dahulu.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
