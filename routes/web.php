<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AspirationController;
use App\Http\Controllers\AspirationManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\WorkProgramController;
use App\Models\Aspiration;
use App\Models\Division;
use App\Models\KategoriAspirasi;
use App\Models\WorkProgram;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/sekbid', [AdminController::class, 'sekbid'])->name('sekbid');
    Route::patch('/sekbid/{divisi}/penugasan', [AdminController::class, 'updatePenugasanSekbid'])->name('sekbid.penugasan');

    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
    Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
    Route::get('/penilaian/export-pdf', [PenilaianController::class, 'exportPdf'])->name('penilaian.export.pdf');
    Route::delete('/penilaian/{penilaian}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
    Route::get('/rekap/export', [RekapController::class, 'export'])->name('rekap.export');
    Route::get('/rekap/export-pdf', [RekapController::class, 'exportPdf'])->name('rekap.export.pdf');

    Route::get('/aspirasi', [AspirationManagementController::class, 'index'])->name('aspirasi');
    Route::patch('/aspirasi/{aspirasi}', [AspirationManagementController::class, 'update'])->name('aspirasi.update');
    Route::get('/aspirasi/export', [AspirationManagementController::class, 'export'])->name('aspirasi.export');
    Route::get('/aspirasi/export-pdf', [AspirationManagementController::class, 'exportPdf'])->name('aspirasi.export.pdf');

    Route::get('/kegiatan', [WorkProgramController::class, 'index'])->name('kegiatan');
    Route::post('/kegiatan', [WorkProgramController::class, 'store'])->name('kegiatan.store');
    Route::patch('/kegiatan/{kegiatan}', [WorkProgramController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [WorkProgramController::class, 'destroy'])->name('kegiatan.destroy');
    Route::get('/kegiatan/export-csv', [WorkProgramController::class, 'exportCsv'])->name('kegiatan.export.csv');
    Route::get('/kegiatan/export-pdf', [WorkProgramController::class, 'exportPdf'])->name('kegiatan.export.pdf');
});
