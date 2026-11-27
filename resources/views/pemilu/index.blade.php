@extends('layout.admin')

@section('title', 'Pemilu')

@section('content')
    @php
        $totalSuara = $calons->sum('suara_count');
    @endphp

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pemilu {{ $site->singkatan }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Setiap akun hanya dapat memberikan satu suara dan suara tidak dapat diubah.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 text-violet-700 text-xs font-bold border border-violet-200">
                <i class="fa-solid fa-check-to-slot"></i> {{ $totalSuara }} Suara Masuk
            </span>
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

        @if ($suaraSaya)
            <!-- Hasil pemilu tampil setelah pengguna memberikan suara -->
            <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-check-double text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Terima Kasih atas Partisipasi Anda</h2>
                        <p class="text-[11px] text-slate-400">
                            Suara Anda untuk <span class="font-bold text-emerald-600">{{ $suaraSaya->calon?->nama_calon }}</span> sudah tercatat.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse ($calons as $calon)
                        @php
                            $persentase = $totalSuara > 0 ? (int) round($calon->suara_count / $totalSuara * 100) : 0;
                        @endphp
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-[10px] font-black text-violet-600">NO. {{ $calon->nomor_urut }}</span>
                                    <span class="text-xs font-bold text-slate-800 truncate">{{ $calon->nama_calon }}</span>
                                </div>
                                <span class="text-xs font-black text-slate-900">{{ $calon->suara_count }} suara ({{ $persentase }}%)</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-violet-500 transition-all" style="width: {{ $persentase }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Belum ada calon yang didaftarkan.</p>
                    @endforelse
                </div>
            </section>
        @else
            <!-- Form voting -->
            <form method="POST" action="{{ route('admin.pemilu.vote') }}" class="space-y-5">
                @csrf
                <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-9 h-9 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                            <i class="fa-solid fa-vote-big text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-900">Pilih Satu Calon</h2>
                            <p class="text-[11px] text-slate-400">Pilih calon yang sesuai dengan keyakinan Anda.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @forelse ($calons as $calon)
                            <label class="relative flex gap-3 p-4 rounded-2xl border border-slate-200 cursor-pointer has-checked:border-violet-500 has-checked:bg-violet-50/50 hover:border-violet-300 transition-all">
                                <input type="radio" name="id_calon" value="{{ $calon->id }}" required
                                    class="mt-1 accent-violet-600" @checked(old('id_calon') == $calon->id)>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0">
                                            @if ($calon->foto)
                                                <img src="{{ Storage::url($calon->foto) }}" alt="Foto {{ $calon->nama_calon }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-user text-slate-400"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] font-bold text-violet-600 uppercase tracking-wider">Nomor {{ $calon->nomor_urut }}</span>
                                            <p class="text-xs font-black text-slate-900 leading-tight truncate">{{ $calon->nama_calon }}</p>
                                        </div>
                                    </div>
                                    @if ($calon->slogan)
                                        <p class="text-[11px] font-bold text-slate-500 italic mb-1">“{{ $calon->slogan }}”</p>
                                    @endif
                                    <p class="text-[11px] text-slate-500 line-clamp-3 whitespace-pre-line">{{ $calon->visi_misi }}</p>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-full p-8 text-center rounded-2xl border border-dashed border-slate-200">
                                <p class="text-xs text-slate-400">Belum ada calon yang didaftarkan. Silakan kembali nanti.</p>
                            </div>
                        @endforelse
                    </div>
                    @error('id_calon') <p class="text-[10px] text-rose-600 mt-3">{{ $message }}</p> @enderror
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-xs shadow-md shadow-violet-600/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        <span>Kirim Suara Saya</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
