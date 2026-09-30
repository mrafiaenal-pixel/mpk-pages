<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AspirationController;
use App\Http\Controllers\AuthController;
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
    Route::get('/penilaian', [AdminController::class, 'penilaian'])->name('penilaian');
    Route::post('/penilaian', [AdminController::class, 'storePenilaian'])->name('penilaian.store');
    Route::get('/aspirasi', [AdminController::class, 'aspirasi'])->name('aspirasi');
    Route::patch('/aspirasi/{id}/status', [AdminController::class, 'updateAspirasiStatus'])->name('aspirasi.status');
});
