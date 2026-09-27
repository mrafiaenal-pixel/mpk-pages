<?php

namespace App\Http\Controllers;

use App\Models\AnggotaSekbid;
use App\Models\AspekPenilaian;
use App\Models\Aspiration;
use App\Models\Assesment;
use App\Models\Division;
use App\Models\DivisionMember;
use App\Models\Penilaian;
use App\Models\ProgramKerja;
use App\Models\Sekbid;
use App\Models\WorkProgram;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSekbid = Division::count();
        $totalAnggota = DivisionMember::count();
        $totalAspirasi = Aspiration::count();
        $totalProgja = WorkProgram::count();
        $progjaSelesai = WorkProgram::where('status_progja', 'terlaksana')->count();
        $persentaseProgja = $totalProgja > 0 ? round(($progjaSelesai / $totalProgja) * 100) : 0;

        $aspirasiMenunggu = Aspiration::where('status_aspirasi', 'menunggu')->count();
        $aspirasiProses = Aspiration::where('status_aspirasi', 'proses')->count();
        $aspirasiSelesai = Aspiration::where('status_aspirasi', 'selesai')->count();

        // Recent Penilaian
        $recentPenilaian = Assesment::with(['anggotaSekbid.division', 'user', 'aspect'])
            ->latest('id')
            ->take(5)
            ->get();

        // Recent Aspirasi
        $recentAspirasi = Aspiration::with('category')
            ->latest('id_aspirasi')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSekbid',
            'totalAnggota',
            'totalAspirasi',
            'totalProgja',
            'persentaseProgja',
            'aspirasiMenunggu',
            'aspirasiProses',
            'aspirasiSelesai',
            'recentPenilaian',
            'recentAspirasi'
        ));
    }

    public function sekbid()
    {
        $sekbids = Division::with('members')->get();

        return view('admin.sekbid', compact('sekbids'));
    }

    public function penilaian()
    {
        $penilaians = Assesment::with(['anggotaSekbid.division', 'user', 'aspect'])
            ->latest('id')
            ->paginate(10);

        $anggotaList = DivisionMember::with('division')->get();

        return view('admin.penilaian', compact('penilaians', 'anggotaList'));
    }

    public function storePenilaian(Request $request)
    {
        $validated = $request->validate([
            'id_anggota_sekbid' => 'required|exists:tb_anggota_sekbid,id',
            'keaktifan_gds' => 'required|in:hadir,telat 1x,telat 2x,telat 3x,izin/lainnya,tidak hadir',
            'piket_ro' => 'required|in:hadir,tidak ada jadwal,tanpa keterangan,izin/sakit,tidak bagiannya',
            'kekompakan' => 'required|in:ramai aktif,sepi ada komunikasi,ramai saat progja,grup mati',
            'komunikasi' => 'required|in:baik responsif,lambat merespon,sulit koordinasi,tidak berkomunikasi',
            'kehadiran_rapat' => 'required|in:tepat waktu,telat,tanpa keterangan,izin alasan jelas,sakit',
            'kegiatan_progja' => 'required|in:sesuai job,kurang maksimal,tidak ikut alasan,tidak ada progja',
            'inisiatif_bantuan' => 'nullable|boolean',
            'catatan_terhadap_anggota' => 'nullable|string|max:1000',
            'tanggal_penilaian' => 'required|date',
        ]);

        $aspek = AspekPenilaian::create([
            'keaktifan_gds' => $validated['keaktifan_gds'],
            'piket_ro' => $validated['piket_ro'],
            'kekompakan' => $validated['kekompakan'],
            'komunikasi' => $validated['komunikasi'],
            'kehadiran_rapat' => $validated['kehadiran_rapat'],
            'kegiatan_progja' => $validated['kegiatan_progja'],
            'inisiatif_bantuan' => $request->boolean('inisiatif_bantuan'),
            'catatan_terhadap_anggota' => $validated['catatan_terhadap_anggota'] ?? null,
            'tanggal_penilaian' => $validated['tanggal_penilaian'],
        ]);

        Assesment::create([
            'id_anggota_sekbid' => $validated['id_anggota_sekbid'],
            'id_users' => auth()->id(),
            'id_aspek_penilaian' => $aspek->id,
        ]);

        return redirect()->route('admin.penilaian')
            ->with('success', 'Penilaian kinerja anggota sekbid berhasil disimpan!');
    }

    public function aspirasi(Request $request)
    {
        $query = Aspiration::with('category')->latest('id_aspirasi');

        if ($request->filled('status')) {
            $query->where('status_aspirasi', $request->status);
        }

        $aspirasis = $query->paginate(10);

        return view('admin.aspirasi', compact('aspirasis'));
    }

    public function updateAspirasiStatus(Request $request, $id)
    {
        $request->validate([
            'status_aspirasi' => 'required|in:menunggu,proses,selesai,ditolak',
        ]);

        $aspirasi = Aspiration::findOrFail($id);
        $aspirasi->update([
            'status_aspirasi' => $request->status_aspirasi,
        ]);

        return redirect()->back()->with('success', 'Status aspirasi #' . $id . ' berhasil diperbarui!');
    }
}
