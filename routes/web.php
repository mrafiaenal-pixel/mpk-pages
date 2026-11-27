<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AspirationController;
use App\Http\Controllers\AspirationManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\CalonPemiluController;
use App\Http\Controllers\DailyLifeController;
use App\Http\Controllers\DivisionMemberController;
use App\Http\Controllers\FotoDokumentasiController;
use App\Http\Controllers\PemiluController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\WorkProgramController;
use App\Models\Aspiration;
use App\Models\Division;
use App\Models\FotoDokumentasi;
use App\Models\KategoriAspirasi;
use App\Models\WorkProgram;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the Laravel ServiceProvider within a group which
| is assigned the "web" middleware group. You can create something
| wonderful here!
|
*/

// Public routes
Route::get('/', function () {
    $kategoris = KategoriAspirasi::all();
    $sekbids = Division::with('members')->get();
    $progjas = WorkProgram::latest()->take(6)->get();
    $recentAspirasi = Aspiration::latest('id_aspirasi')->take(3)->get();
    $fotoDokumentasi = FotoDokumentasi::query()->latest()->take(8)->get();

    return view('welcome', compact('kategoris', 'sekbids', 'progjas', 'recentAspirasi', 'fotoDokumentasi'));
})->name('home');

Route::post('/aspirasi', [AspirationController::class, 'store'])
    ->name('aspirasi.store');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile routes (all authenticated users)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/search', [AdminController::class, 'search'])->name('search');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Daily Life routes (all authenticated users)
    Route::get('/daily-life', [DailyLifeController::class, 'index'])->name('daily-life.index');
    Route::post('/daily-life', [DailyLifeController::class, 'store'])->name('daily-life.store');
    Route::delete('/daily-life/{dailyLife}', [DailyLifeController::class, 'destroy'])->name('daily-life.destroy');

    // Pemilu: semua pengguna yang login boleh melihat calon dan memilih satu calon.
    Route::get('/pemilu', [PemiluController::class, 'index'])->name('pemilu.index');
    Route::post('/pemilu/vote', [PemiluController::class, 'vote'])->name('pemilu.vote');
});

// Profil MPR dan anggota: ketua & komc (admin otomatis diizinkan oleh middleware role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:ketua,komc'])->group(function () {
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::delete('/anggota/{member}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');

    // Kelola calon pemilu dan foto dokumentasi kegiatan.
    Route::get('/calon', [CalonPemiluController::class, 'index'])->name('calon.index');
    Route::post('/calon', [CalonPemiluController::class, 'store'])->name('calon.store');
    Route::put('/calon/{calon}', [CalonPemiluController::class, 'update'])->name('calon.update');
    Route::delete('/calon/{calon}', [CalonPemiluController::class, 'destroy'])->name('calon.destroy');

    Route::get('/dokumentasi', [FotoDokumentasiController::class, 'index'])->name('dokumentasi.index');
    Route::post('/dokumentasi', [FotoDokumentasiController::class, 'store'])->name('dokumentasi.store');
    Route::put('/dokumentasi/{fotoDokumentasi}', [FotoDokumentasiController::class, 'update'])->name('dokumentasi.update');
    Route::delete('/dokumentasi/{fotoDokumentasi}', [FotoDokumentasiController::class, 'destroy'])->name('dokumentasi.destroy');
});

// API routes for chart visualization
Route::get('/api/penilaian/gds-piket', [PenilaianController::class, 'getGdsPiketData'])
    ->name('api.penilaian.gds-piket');
Route::get('/api/penilaian/aspek', [PenilaianController::class, 'getAspekData'])
    ->name('api.penilaian.aspek');
Route::get('/api/penilaian/divisi-stats', [PenilaianController::class, 'getDivisiStats'])
    ->name('api.penilaian.divisi-stats');

// API bendahara routes
Route::get('/api/bendahara/summary', [BendaharaController::class, 'summary'])
    ->name('api.bendahara.summary');

// Apply role middleware to admin prefix
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role'])->group(function () {
    // Dashboard - accessible to admin, mpk, guru, ketua, komc
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Sekbid routes - accessible to admin, ketua, komc, guru
    Route::get('/sekbid', [AdminController::class, 'sekbid'])->name('sekbid');
    Route::patch('/sekbid/{divisi}/penugasan', [AdminController::class, 'updatePenugasanSekbid'])->name('sekbid.penugasan');

    // Division Member CRUD (masing-masing sekbid bisa mengatur anggota)
    Route::post('/sekbid/{division}/members', [DivisionMemberController::class, 'store'])->name('sekbid.members.store');
    Route::patch('/sekbid/members/{member}', [DivisionMemberController::class, 'update'])->name('sekbid.members.update');
    Route::delete('/sekbid/members/{member}', [DivisionMemberController::class, 'destroy'])->name('sekbid.members.destroy');

    // Penilaian routes - with role-based access
    // komc and ketua can view and manage all assessments
    // guru can view and create assessments
    // anggota only can input (store), not edit/delete
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
    // Only komc, ketua, and guru can edit/delete assessments
    Route::delete('/penilaian/{penilaian}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');
    Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
    Route::get('/penilaian/export-pdf', [PenilaianController::class, 'exportPdf'])->name('penilaian.export.pdf');

    // Rekap routes - accessible to admin, ketua, komc, guru
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
    Route::get('/rekap/export', [RekapController::class, 'export'])->name('rekap.export');
    Route::get('/rekap/export-pdf', [RekapController::class, 'exportPdf'])->name('rekap.export.pdf');

    // Aspirasi hanya untuk Kom B, Kom C, Ketua, Guru (admin diizinkan middleware)
    Route::middleware('role:komb,komc,ketua,guru')->group(function () {
        Route::get('/aspirasi', [AspirationManagementController::class, 'index'])->name('aspirasi');
        Route::patch('/aspirasi/{aspirasi}', [AspirationManagementController::class, 'update'])->name('aspirasi.update');
        Route::get('/aspirasi/export', [AspirationManagementController::class, 'export'])->name('aspirasi.export');
        Route::get('/aspirasi/export-pdf', [AspirationManagementController::class, 'exportPdf'])->name('aspirasi.export.pdf');
    });

    // Kegiatan routes - accessible to admin, ketua, komc, guru
    Route::get('/kegiatan', [WorkProgramController::class, 'index'])->name('kegiatan');
    Route::post('/kegiatan', [WorkProgramController::class, 'store'])->name('kegiatan.store');
    Route::patch('/kegiatan/{kegiatan}', [WorkProgramController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [WorkProgramController::class, 'destroy'])->name('kegiatan.destroy');
    Route::get('/kegiatan/export-csv', [WorkProgramController::class, 'exportCsv'])->name('kegiatan.export.csv');
    Route::get('/kegiatan/export-pdf', [WorkProgramController::class, 'exportPdf'])->name('kegiatan.export.pdf');

    // Bendahara routes - mencatat uang kas masuk/keluar
    Route::get('/bendahara', [BendaharaController::class, 'index'])->name('bendahara.index');
    Route::post('/bendahara', [BendaharaController::class, 'store'])->name('bendahara.store');
    Route::get('/bendahara/export', [BendaharaController::class, 'export'])->name('bendahara.export');
    Route::get('/bendahara/summary', [BendaharaController::class, 'summary'])->name('bendahara.summary');
});
