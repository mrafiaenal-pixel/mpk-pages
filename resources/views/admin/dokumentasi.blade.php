@extends('layout.admin')

@section('title', 'Foto Dokumentasi')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Foto Dokumentasi</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Unggah, ganti, atau hapus foto kegiatan yang ditampilkan di halaman publik.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                    <i class="fa-solid fa-images"></i> {{ $fotos->count() }} Foto
                </span>
                <a href="{{ url('/') }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                    <i class="fa-solid fa-arrow-up-right-from-square text-slate-500"></i>
                    <span>Lihat Halaman Publik</span>
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

        <!-- Tambah Foto -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-image text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-900">Tambah Foto Dokumentasi</h2>
                    <p class="text-[11px] text-slate-400">Format JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.dokumentasi.store') }}" enctype="multipart/form-data"
                class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                @csrf
                <div class="sm:col-span-4">
                    <label for="judul" class="block text-[11px] font-bold text-slate-500 mb-1">Judul Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" id="judul" name="judul" required maxlength="150" value="{{ old('judul') }}"
                        placeholder="Contoh: Upacara Bendera"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                    @error('judul') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-5">
                    <label for="keterangan" class="block text-[11px] font-bold text-slate-500 mb-1">Keterangan</label>
                    <input type="text" id="keterangan" name="keterangan" maxlength="500" value="{{ old('keterangan') }}"
                        placeholder="Singkat, misalnya: Kegiatan rutin setiap Senin"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                    @error('keterangan') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-3">
                    <label for="foto" class="block text-[11px] font-bold text-slate-500 mb-1">Berkas Foto <span class="text-rose-500">*</span></label>
                    <input type="file" id="foto" name="foto" required accept="image/jpeg,image/png,image/webp"
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs file:mr-3 file:font-bold file:text-blue-600">
                    @error('foto') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-12 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-upload text-[10px]"></i>
                        <span>Unggah Foto</span>
                    </button>
                </div>
            </form>
        </section>

        <!-- Daftar Foto -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-black text-slate-900">Galeri Dokumentasi</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Ganti foto lama dengan mengunggah berkas baru pada form ubah.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 p-5 sm:p-6">
                @forelse ($fotos as $foto)
                    <article class="rounded-2xl border border-slate-200 overflow-hidden" x-data="{ edit: false }">
                        <div class="aspect-video bg-slate-100 overflow-hidden">
                            <a href="{{ Storage::url($foto->foto) }}" target="_blank" title="Lihat foto penuh">
                                <img src="{{ Storage::url($foto->foto) }}" alt="{{ $foto->judul }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </a>
                        </div>
                        <div class="p-4 space-y-3" x-show="!edit">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-xs font-black text-slate-900 leading-tight">{{ $foto->judul }}</h3>
                                    <p class="text-[11px] text-slate-400 mt-1">{{ $foto->keterangan ?: 'Tanpa keterangan' }}</p>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <button type="button" @click="edit = true" title="Ubah foto"
                                        class="w-7 h-7 rounded-lg text-blue-600 hover:bg-blue-50 flex items-center justify-center cursor-pointer">
                                        <i class="fa-solid fa-pen text-[10px]"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.dokumentasi.destroy', $foto) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus foto"
                                            onclick="return confirm('Hapus foto dokumentasi ini?')"
                                            class="w-7 h-7 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center cursor-pointer">
                                            <i class="fa-solid fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border-t border-slate-100 space-y-3" x-show="edit" x-cloak>
                            <form method="POST" action="{{ route('admin.dokumentasi.update', $foto) }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1">Judul</label>
                                    <input type="text" name="judul" required maxlength="150"
                                        value="{{ old('judul', $foto->judul) }}"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1">Keterangan</label>
                                    <input type="text" name="keterangan" maxlength="500"
                                        value="{{ old('keterangan', $foto->keterangan) }}"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1">Ganti Foto</label>
                                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp"
                                        class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs file:mr-3 file:font-bold file:text-blue-600">
                                </div>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="edit = false"
                                        class="px-3 py-2 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs cursor-pointer">Batal</button>
                                    <button type="submit"
                                        class="px-3 py-2 rounded-lg bg-blue-600 text-white font-bold text-xs cursor-pointer">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full p-8 text-center">
                        <p class="text-xs text-slate-400">Belum ada foto dokumentasi. Unggah foto pertama di atas.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
