@extends('layout.admin')

@section('title', 'Penilaian Kinerja Sekbid')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Penilaian Kinerja Anggota Sekbid</h1>
            <p class="text-xs text-slate-500 mt-1">
                @if(auth()->user()->isDivisionScoped())
                    Anda hanya menangani <span class="font-bold text-blue-600">{{ auth()->user()->division->nama_sekbid ?? 'divisi Anda' }}</span>.
                @else
                    Evaluasi kedisiplinan dan capaian kegiatan OSIS berdasarkan 6 aspek standar.
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.penilaian.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition-all">
                <i class="fa-solid fa-file-excel text-emerald-600"></i>
                <span>Export Excel</span>
            </a>
            <button @click="modalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Input Penilaian Baru</span>
            </button>
        </div>
    </div>

    <!-- Statistik Singkat -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jumlah Penilaian</p>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $statistik['jumlah'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Nilai</p>
            <p class="text-2xl font-black text-blue-600 mt-1">{{ number_format($statistik['rataNilai'], 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nilai Tertinggi</p>
            <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($statistik['tertinggi'], 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nilai Terendah</p>
            <p class="text-2xl font-black text-rose-600 mt-1">{{ number_format($statistik['terendah'], 2) }}</p>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('admin.penilaian') }}"
          class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
        <div class="lg:col-span-2">
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Cari</label>
            <input type="text" name="q" value="{{ $penilaiList['q'] ?? '' }}" placeholder="Nama anggota atau MPK penilai..."
                   class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Divisi / Sekbid</label>
            <select name="divisi" onchange="this.form.submit()"
                    @disabled(auth()->user()->isDivisionScoped())
                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white disabled:opacity-60">
                <option value="">Semua Divisi</option>
                @foreach($divisiList as $divisi)
                    <option value="{{ $divisi->id }}" @selected(($penilaiList['divisi'] ?? null) == $divisi->id)>{{ $divisi->nama_sekbid }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Anggota</label>
            <select name="anggota" onchange="this.form.submit()"
                    class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
                <option value="">Semua Anggota</option>
                @foreach($anggotaList as $anggota)
                    <option value="{{ $anggota->id }}" @selected(($penilaiList['anggota'] ?? null) == $anggota->id)>{{ $anggota->nama_anggota }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ $penilaiList['dari'] ?? '' }}"
                   class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
        </div>

        <div>
            <label class="block text-[11px] font-bold text-slate-500 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ $penilaiList['sampai'] ?? '' }}"
                   class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden focus:border-blue-500 focus:bg-white">
        </div>

        <div class="sm:col-span-2 lg:col-span-6 flex items-center gap-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs">
                <i class="fa-solid fa-filter mr-1"></i>Terapkan Filter
            </button>
            @if(count($penilaiList) > 0)
                <a href="{{ route('admin.penilaian') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </div>
    </form>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Penilaian -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table id="tabelPenilaian" class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Anggota Dinilai</th>
                        <th class="py-3.5 px-6">Seksi Bidang</th>
                        <th class="py-3.5 px-6">Keaktifan GDS</th>
                        <th class="py-3.5 px-6">Piket RO</th>
                        <th class="py-3.5 px-6">Kekompakan</th>
                        <th class="py-3.5 px-6">Komunikasi</th>
                        <th class="py-3.5 px-6">Rapat</th>
                        <th class="py-3.5 px-6">Progja</th>
                        <th class="py-3.5 px-6">Nilai</th>
                        <th class="py-3.5 px-6">Predikat</th>
                        <th class="py-3.5 px-6">Tanggal</th>
                        <th class="py-3.5 px-6 text-right">Penilai</th>
                        <th class="py-3.5 px-6"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penilaians as $item)
                        @php $aspek = $item->aspect; @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($item->divisionMember?->nama_anggota ?? 'A', 0, 1)) }}
                                    </div>
                                    <span>{{ $item->divisionMember?->nama_anggota ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $item->divisionMember?->division?->nama_sekbid ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-600">{{ $aspek?->keaktifan_gds ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-700">{{ $aspek?->piket_ro ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-700">{{ $aspek?->kekompakan ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-700">{{ $aspek?->komunikasi ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-700">{{ $aspek?->kehadiran_rapat ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-700">{{ $aspek?->kegiatan_progja ?? '-' }}</td>
                            <td class="py-4 px-6 font-black text-slate-900">
                                {{ $aspek?->nilai !== null ? number_format($aspek->nilai, 2) : '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($aspek?->nilai !== null)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ \App\Services\PenilaiSkor::warnaPredikat($aspek->predikat()) }}">
                                        {{ $aspek->predikat() }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $aspek?->tanggal_penilaian?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-right text-slate-600 font-medium">
                                {{ $item->user?->username ?? 'MPK' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.penilaian.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus data penilaian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @if($aspek?->catatan_terhadap_anggota)
                            <tr class="bg-slate-50/50">
                                <td colspan="13" class="px-6 py-2.5 text-[11px] text-slate-600">
                                    <i class="fa-solid fa-note-sticky text-blue-400 mr-1"></i>
                                    <span class="font-bold text-slate-700">Catatan:</span> {{ $aspek->catatan_terhadap_anggota }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="13" class="py-8 text-center text-slate-400">
                                Belum ada riwayat penilaian kinerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penilaians->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $penilaians->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL FORM INPUT PENILAIAN -->
    <div x-show="modalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="modalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Formulir Penilaian Kinerja</h3>
                            <p class="text-xs text-slate-400">Isi penilaian 6 aspek kerja. Nilai dihitung otomatis.</p>
                        </div>
                    </div>
                    <button @click="modalOpen = false" class="p-2 text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.penilaian.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="id_anggota_sekbid" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Pilih Anggota Sekbid <span class="text-rose-500">*</span>
                        </label>
                        <select id="id_anggota_sekbid" name="id_anggota_sekbid" required
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-blue-500 outline-hidden">
                            <option value="" disabled selected>Pilih nama anggota OSIS...</option>
                            @foreach($anggotaList as $anggota)
                                <option value="{{ $anggota->id }}">
                                    {{ $anggota->nama_anggota }} — ({{ $anggota->division->nama_sekbid ?? 'Sekbid' }})
                                </option>
                            @endforeach
                        </select>
                        @if($anggotaList->isEmpty())
                            <p class="text-[11px] text-amber-600 font-semibold">
                                Tidak ada anggota sekbid yang dapat Anda nilai. Hubungi ketua MPK.
                            </p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        @foreach(\App\Services\PenilaiSkor::RUBRIK as $kolom => $opsi)
                            <div class="space-y-1">
                                <label for="{{ $kolom }}" class="block text-[11px] font-bold text-slate-700">
                                    {{ \App\Services\PenilaiSkor::LABEL_ASPEK[$kolom] }}
                                </label>
                                <select id="{{ $kolom }}" name="{{ $kolom }}" required
                                        class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                                    @foreach($opsi as $label => $poin)
                                        <option value="{{ $label }}">{{ ucfirst($label) }} ({{ $poin }} poin)</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-2.5">
                        <input type="checkbox" name="inisiatif_bantuan" id="inisiatif_bantuan" value="1" class="w-4 h-4 text-blue-600 rounded">
                        <label for="inisiatif_bantuan" class="text-xs font-semibold text-slate-700 cursor-pointer">
                            Memberikan Inisiatif Bantuan Tambahan (+{{ \App\Services\PenilaiSkor::BONUS_INISIATIF }} Poin)
                        </label>
                    </div>

                    <div class="space-y-1">
                        <label for="tanggal_penilaian" class="block text-[11px] font-bold text-slate-700">Tanggal Penilaian</label>
                        <input type="datetime-local" id="tanggal_penilaian" name="tanggal_penilaian" required
                               value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden">
                    </div>

                    <div class="space-y-1">
                        <label for="catatan_terhadap_anggota" class="block text-[11px] font-bold text-slate-700">Catatan & Masukan Pembinaan (Opsional)</label>
                        <textarea id="catatan_terhadap_anggota" name="catatan_terhadap_anggota" rows="3"
                                  placeholder="Tuliskan catatan evaluasi kinerja untuk anggota bersangkutan..."
                                  class="w-full px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs outline-hidden resize-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="modalOpen = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" @disabled($anggotaList->isEmpty())
                                class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 disabled:opacity-50">
                            Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        $('#tabelPenilaian').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            info: false,
            autoWidth: false,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id-ID.json' },
        });
    });
</script>
@endpush

@endsection