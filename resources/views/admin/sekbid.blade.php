@extends('layout.admin')

@section('title', 'Daftar Seksi Bidang OSIS')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Seksi Bidang & Anggota OSIS</h1>
            <p class="text-xs text-slate-500 mt-1">
                Daftar seksi bidang beserta anggota dan {{ $site->singkatan }} penanggung jawab yang menanganinya.
            </p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
            <i class="fa-solid fa-layer-group"></i> {{ $sekbids->count() }} Sekbid Terdaftar
        </span>
    </div>

    @if(! $canKelolaPenugasan)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center gap-2.5">
            <i class="fa-solid fa-circle-info"></i>
            Anda hanya ditugaskan menangani satu divisi. Penetapan {{ $site->singkatan }} hanya dapat diubah oleh {{ $site->singkatan }} tanpa assignment.
        </div>
    @endif

    <!-- Table of Sekbid & Anggota -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table id="tabelSekbid" class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6 w-16">No</th>
                        <th class="py-3.5 px-6">Nama Seksi Bidang</th>
                        <th class="py-3.5 px-6">Daftar Anggota</th>
                        <th class="py-3.5 px-6 text-center">Anggota</th>
                        <th class="py-3.5 px-6">{{ $site->singkatan }} Penanggung Jawab</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sekbids as $index => $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-400">{{ sprintf('%02d', $index + 1) }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ $index + 1 }}
                                    </div>
                                    <span>{{ $item->nama_sekbid }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1.5">
                                    @php $bolehKelolaAnggota = auth()->user()->canManageDivisionMembers($item->id); @endphp
                                    @forelse($item->members as $member)
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium border border-slate-200/60 inline-flex flex-wrap items-center gap-1" x-data="{ editing: false }">
                                            <span>{{ $member->nama_anggota }}</span>
                                            @if($bolehKelolaAnggota)
                                                <button type="button" @click="editing = !editing" title="Ubah anggota" class="text-blue-500 hover:text-blue-700 cursor-pointer">
                                                    <i class="fa-solid fa-pen text-[8px]"></i>
                                                </button>
                                                <form x-show="editing" action="{{ route('admin.sekbid.members.update', $member) }}" method="POST" class="inline-flex items-center gap-1 w-full pt-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="id_sekbid" value="{{ $member->id_sekbid }}">
                                                    <input type="text" name="nama_anggota" value="{{ $member->nama_anggota }}" class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-[11px] w-28" required>
                                                    <button type="submit" class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white text-[10px] font-bold">Simpan</button>
                                                </form>
                                                <form action="{{ route('admin.sekbid.members.destroy', $member) }}" method="POST" class="inline-flex">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Hapus anggota ini?')">
                                                        <i class="fa-solid fa-times text-[8px]"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </span>
                                    @empty
                                        <span class="text-slate-400 italic">Belum ada anggota</span>
                                    @endforelse
                                    @if($bolehKelolaAnggota)
                                        <form action="{{ route('admin.sekbid.members.store', $item) }}" method="POST" class="inline-flex items-center gap-1">
                                            @csrf
                                            <input type="text" name="nama_anggota" placeholder="Nama anggota baru" class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-[11px] w-32" required>
                                            <button type="submit" class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white text-[11px] font-bold">
                                                <i class="fa-solid fa-plus text-[8px]"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-slate-700">{{ $item->members->count() }}</td>
                            <td class="py-4 px-6">
                                @if($canKelolaPenugasan)
                                    <form action="{{ route('admin.sekbid.penugasan', $item->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="id_users" onchange="this.form.submit()"
                                                class="flex-1 min-w-[10rem] px-2.5 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 outline-hidden focus:border-blue-500 cursor-pointer">
                                            <option value="">— Tanpa Penugasan —</option>
                                            @foreach($daftarMpk as $mpk)
                                                @php $divisiDitangani = $mpk->accessibleDivisionIds(); @endphp
                                                <option value="{{ $mpk->id_users }}"
                                                    @selected(in_array($item->id, $divisiDitangani, true))
                                                    @disabled($divisiDitangani !== [] && ! in_array($item->id, $divisiDitangani, true))>
                                                    {{ $mpk->username }}{{ $divisiDitangani !== [] && ! in_array($item->id, $divisiDitangani, true) ? ' (sibuh)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @elseif($item->assessors->isNotEmpty())
                                    @foreach($item->assessors as $mpk)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-user-shield text-[10px]"></i> {{ $mpk->username }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-slate-400 italic">Belum ada {{ $site->singkatan }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.penilaian', ['divisi' => $item->id]) }}"
                                   class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-bold text-xs transition-colors inline-flex items-center gap-1">
                                    <i class="fa-solid fa-award text-[10px]"></i> Beri Nilai
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada data Seksi Bidang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        $('#tabelSekbid').DataTable({
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