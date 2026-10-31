<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AspirationController;
use App\Http\Controllers\AspirationManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\WorkProgramController;
use App\Models\Aspiration;
use App\Models\Division;
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

    return view('welcome', compact('kategoris', 'sekbids', 'progjas', 'recentAspirasi'));
})->name('home');

Route::post('/aspirasi', [AspirationController::class, 'store'])
    ->name('aspirasi.store');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    // Aspirasi routes - with role-based access including bendahara
    Route::get('/aspirasi', [AspirationManagementController::class, 'index'])->name('aspirasi');
    Route::patch('/aspirasi/{aspirasi}', [AspirationManagementController::class, 'update'])->name('aspirasi.update');
    Route::get('/aspirasi/export', [AspirationManagementController::class, 'export'])->name('aspirasi.export');
    Route::get('/aspirasi/export-pdf', [AspirationManagementController::class, 'exportPdf'])->name('aspirasi.export.pdf');

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
