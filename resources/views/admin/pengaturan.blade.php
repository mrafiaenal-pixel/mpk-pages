@extends('layout.admin')

@section('title', 'Pengaturan Organisasi')

@section('content')
    @php
        $daftarKomisi = array_values(old('komisi', $site->komisi->map(fn ($komisi) => [
            'kode' => $komisi['kode'] ?? '',
            'nama' => $komisi['nama'] ?? '',
            'deskripsi' => $komisi['deskripsi'] ?? '',
        ])->all()));

        $daftarPengurus = old('pengurus', $site->pengurus->map(fn ($pengurus) => [
            'id' => $pengurus->id,
            'nama_pengurus' => $pengurus->nama_pengurus,
            'jabatan_pengurus' => $pengurus->jabatan_pengurus,
            'komisi_pengurus' => $pengurus->komisi_pengurus,
        ])->all());
    @endphp

    <div class="space-y-6" x-data="{ pengurus: @json($daftarPengurus) }">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Organisasi</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Atur identitas, visi misi, tiga komisi (A, B, C), dan struktur pengurus yang tampil di seluruh portal.
                </p>
            </div>
            <a href="{{ url('/') }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square text-slate-500"></i>
                <span>Lihat Halaman Publik</span>
            </a>
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

        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Identitas Organisasi -->
            <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-building-columns text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Identitas Organisasi</h2>
                        <p class="text-[11px] text-slate-400">Nama singkat di sini yang dipakai di seluruh portal.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="singkatan" class="block text-[11px] font-bold text-slate-500 mb-1">Singkatan Organisasi <span class="text-rose-500">*</span></label>
                        <input type="text" id="singkatan" name="singkatan" required maxlength="20"
                            value="{{ old('singkatan', $site->singkatan) }}" placeholder="Contoh: MPK, MPM, MPR"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                        @error('singkatan') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nama_lengkap" class="block text-[11px] font-bold text-slate-500 mb-1">Nama Lengkap Organisasi <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required maxlength="120"
                            value="{{ old('nama_lengkap', $site->namaLengkap) }}" placeholder="Contoh: Majelis Perwakilan Kelas"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                        @error('nama_lengkap') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nama_sekolah" class="block text-[11px] font-bold text-slate-500 mb-1">Nama Sekolah <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama_sekolah" name="nama_sekolah" required maxlength="150"
                            value="{{ old('nama_sekolah', $site->namaSekolah) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                        @error('nama_sekolah') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email_sekolah" class="block text-[11px] font-bold text-slate-500 mb-1">Email Kontak <span class="text-rose-500">*</span></label>
                        <input type="email" id="email_sekolah" name="email_sekolah" required maxlength="150"
                            value="{{ old('email_sekolah', $site->email) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">
                        @error('email_sekolah') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="descripsi_profile" class="block text-[11px] font-bold text-slate-500 mb-1">Deskripsi Singkat</label>
                        <textarea id="descripsi_profile" name="descripsi_profile" rows="2" maxlength="1000"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden resize-none">{{ old('descripsi_profile', $site->deskripsi) }}</textarea>
                        @error('descripsi_profile') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Visi & Misi -->
            <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-bullseye text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Visi & Misi</h2>
                        <p class="text-[11px] text-slate-400">Satu misi per baris agar tampil sebagai poin di halaman publik.</p>
                    </div>
                </div>

                <div>
                    <label for="visi" class="block text-[11px] font-bold text-slate-500 mb-1">Visi <span class="text-rose-500">*</span></label>
                    <textarea id="visi" name="visi" rows="3" required maxlength="2000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden resize-none">{{ old('visi', $site->visi) }}</textarea>
                    @error('visi') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="misi" class="block text-[11px] font-bold text-slate-500 mb-1">Misi <span class="text-rose-500">*</span></label>
                    <textarea id="misi" name="misi" rows="6" required maxlength="5000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-hidden">{{ old('misi', implode("\n", $site->misi)) }}</textarea>
                    @error('misi') <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </section>

            <!-- Komisi A, B, C -->
            <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Komisi Organisasi</h2>
                        <p class="text-[11px] text-slate-400">Terdiri dari tiga komisi: A, B, dan C.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($daftarKomisi as $index => $komisi)
                        <div class="rounded-xl border border-slate-200 p-4 space-y-3">
                            <input type="hidden" name="komisi[{{ $index }}][kode]" value="{{ $komisi['kode'] }}">
                            <p class="text-[11px] font-black text-blue-600 uppercase tracking-wider">Komisi {{ $komisi['kode'] }}</p>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">Nama</label>
                                <input type="text" name="komisi[{{ $index }}][nama]" required maxlength="60"
                                    value="{{ $komisi['nama'] }}"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">Tugas / Fokus</label>
                                <input type="text" name="komisi[{{ $index }}][deskripsi]" required maxlength="150"
                                    value="{{ $komisi['deskripsi'] }}"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs outline-hidden focus:border-blue-500">
                            </div>
                            @error('komisi.'.$index.'.nama') <p class="text-[10px] text-rose-600">{{ $message }}</p> @enderror
                            @error('komisi.'.$index.'.deskripsi') <p class="text-[10px] text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Struktur Pengurus -->
            <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-users-gear text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-900">Struktur Pengurus</h2>
                            <p class="text-[11px] text-slate-400">Tambah, ubah, atau hapus pengurus sesuai periode masa bakti.</p>
                        </div>
                    </div>
                    <button type="button" @click="pengurus.push({ id: '', nama_pengurus: '', jabatan_pengurus: '', komisi_pengurus: 'Pimpinan Inti' })"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-all cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Tambah Pengurus</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in pengurus" :key="index">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50/60">
                            <input type="hidden" :name="'pengurus[' + index + '][id]'" x-model="item.id">
                            <div class="sm:col-span-4">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">Nama Pengurus</label>
                                <input type="text" :name="'pengurus[' + index + '][nama_pengurus]'" x-model="item.nama_pengurus"
                                    required maxlength="100" placeholder="Nama lengkap"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs outline-hidden focus:border-blue-500">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">Jabatan</label>
                                <input type="text" :name="'pengurus[' + index + '][jabatan_pengurus]'" x-model="item.jabatan_pengurus"
                                    required maxlength="100" placeholder="Contoh: Ketua Umum"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs outline-hidden focus:border-blue-500">
                            </div>
                            <div class="sm:col-span-4">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">Bagian</label>
                                <select :name="'pengurus[' + index + '][komisi_pengurus]'" x-model="item.komisi_pengurus" required
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs outline-hidden focus:border-blue-500">
                                    <option value="Pimpinan Inti">Pimpinan Inti</option>
                                    <option value="Komisi A">Komisi A</option>
                                    <option value="Komisi B">Komisi B</option>
                                    <option value="Komisi C">Komisi C</option>
                                </select>
                            </div>
                            <div class="sm:col-span-1 flex items-end justify-end">
                                <button type="button" @click="pengurus.splice(index, 1)" title="Hapus pengurus"
                                    class="w-9 h-9 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                @error('pengurus') <p class="text-[10px] text-rose-600 mt-2">{{ $message }}</p> @enderror
            </section>

            <!-- Simpan -->
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>
@endsection
