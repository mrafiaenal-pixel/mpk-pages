@extends('layout.admin')

@section('title', 'Kelola Calon Pemilu')

@section('content')
    @php
        $totalSuara = $calons->sum('suara_count');
    @endphp

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Calon Pemilu</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Tambah dan ubah calon beserta fotonya. Kuota maksimal diatur di halaman Profil MPR.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 text-violet-700 text-xs font-bold border border-violet-200">
                    <i class="fa-solid fa-users"></i> {{ $calons->count() }} / {{ $maksimalCalon }} Calon
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                    <i class="fa-solid fa-check-to-slot"></i> {{ $totalSuara }} Suara Masuk
                </span>
                <a href="{{ route('admin.pemilu.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                    <i class="fa-solid fa-chart-bar text-slate-500"></i>
                    <span>Lihat Pemilu</span>
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

        <!-- Tambah Calon -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-900">Tambah Calon</h2>
                    <p class="text-[11px] text-slate-400">Foto opsional, maksimal 2 MB (JPG, PNG, atau WebP).</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.calon.store') }}" enctype="multipart/form-data"
                class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                @csrf
                <div class="sm:col-span-4">
                    <label for="nama_calon" class="block text-[11px] font-bold text-slate-500 mb-1">Nama Calon <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama_calon" name="nama_calon" required maxlength="100"
                        value="{{ old('nama_calon') }}" placeholder="Nama lengkap calon"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                    @error('nama_calon') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="nomor_urut" class="block text-[11px] font-bold text-slate-500 mb-1">No. Urut <span class="text-rose-500">*</span></label>
                    <input type="number" id="nomor_urut" name="nomor_urut" required min="1" max="99"
                        value="{{ old('nomor_urut') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                    @error('nomor_urut') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-3">
                    <label for="slogan" class="block text-[11px] font-bold text-slate-500 mb-1">Slogan</label>
                    <input type="text" id="slogan" name="slogan" maxlength="120" value="{{ old('slogan') }}"
                        placeholder="Contoh: Bersama Kita Maju"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                    @error('slogan') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-3">
                    <label for="foto" class="block text-[11px] font-bold text-slate-500 mb-1">Foto Calon</label>
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs file:mr-3 file:font-bold file:text-blue-600">
                    @error('foto') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-12">
                    <label for="visi_misi" class="block text-[11px] font-bold text-slate-500 mb-1">Visi & Misi <span class="text-rose-500">*</span></label>
                    <textarea id="visi_misi" name="visi_misi" rows="3" required maxlength="2000"
                        placeholder="Tuliskan visi dan misi calon..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden resize-none">{{ old('visi_misi') }}</textarea>
                    @error('visi_misi') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-12 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-xs shadow-md shadow-violet-600/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Tambah Calon</span>
                    </button>
                </div>
            </form>
        </section>

        <!-- Daftar Calon -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Daftar Calon & Hasil Suara</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Klik ikon pensil untuk mengubah data atau mengganti foto calon.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($calons as $calon)
                    <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-12 gap-5" x-data="{ edit: false }">
                        <div class="lg:col-span-3 flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                                @if ($calon->foto)
                                    <img src="{{ Storage::url($calon->foto) }}" alt="Foto {{ $calon->nama_calon }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user text-slate-400 text-xl"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-violet-600 uppercase tracking-wider">Nomor {{ $calon->nomor_urut }}</span>
                                <p class="text-sm font-black text-slate-900 leading-tight truncate">{{ $calon->nama_calon }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $calon->slogan ?: 'Tanpa slogan' }}</p>
                                <p class="text-[11px] font-bold text-emerald-600 mt-1">{{ $calon->suara_count }} suara</p>
                            </div>
                        </div>

                        <div class="lg:col-span-8" x-show="!edit">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Visi & Misi</p>
                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $calon->visi_misi }}</p>
                        </div>

                        <div class="lg:col-span-1 flex items-start justify-end gap-2" x-show="!edit">
                            <button type="button" @click="edit = true" title="Ubah calon"
                                class="w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 flex items-center justify-center transition-colors cursor-pointer">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.calon.destroy', $calon) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus calon"
                                    onclick="return confirm('Hapus calon ini? Suara yang sudah masuk juga akan terhapus.')"
                                    class="w-8 h-8 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>

                        <div class="lg:col-span-12 border-t border-slate-100 pt-4" x-show="edit" x-cloak>
                            <form method="POST" action="{{ route('admin.calon.update', $calon) }}" enctype="multipart/form-data"
                                class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                @csrf
                                @method('PUT')
                                <div class="sm:col-span-4">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Nama Calon</label>
                                    <input type="text" name="nama_calon" required maxlength="100" value="{{ old('nama_calon', $calon->nama_calon) }}"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1">No. Urut</label>
                                    <input type="number" name="nomor_urut" required min="1" max="99" value="{{ old('nomor_urut', $calon->nomor_urut) }}"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Slogan</label>
                                    <input type="text" name="slogan" maxlength="120" value="{{ old('slogan', $calon->slogan) }}"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Ganti Foto</label>
                                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs file:mr-3 file:font-bold file:text-blue-600">
                                </div>
                                <div class="sm:col-span-9">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1">Visi & Misi</label>
                                    <textarea name="visi_misi" rows="3" required maxlength="2000"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500 resize-none">{{ old('visi_misi', $calon->visi_misi) }}</textarea>
                                </div>
                                <div class="sm:col-span-3 flex items-end justify-end gap-2">
                                    <button type="button" @click="edit = false"
                                        class="px-3.5 py-2 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs cursor-pointer">Batal</button>
                                    <button type="submit"
                                        class="px-3.5 py-2 rounded-lg bg-blue-600 text-white font-bold text-xs cursor-pointer">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-xs text-slate-400">Belum ada calon. Tambahkan calon pertama di atas.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
