<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::middleware(['auth'])->group(function () {

    // Group Mahasiswa 
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');
    });

    // Group Admin
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Utama
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Manajemen Akun (CRUD)
        Route::controller(AdminController::class)->group(function () {
            Route::get('/manajemen-akun', 'indexAkun')->name('manajemen-akun');
            Route::get('/manajemen-akun/create', 'createAkun')->name('manajemen-akun.create');
            Route::post('/manajemen-akun', 'storeAkun')->name('manajemen-akun.store');
            Route::get('/manajemen-akun/{id}/edit', 'editAkun')->name('manajemen-akun.edit');
            Route::put('/manajemen-akun/{id}', 'updateAkun')->name('manajemen-akun.update');
            Route::delete('/manajemen-akun/{id}', 'destroyAkun')->name('manajemen-akun.destroy');
            Route::post('/manajemen-akun/import', [AdminController::class, 'importAkun'])->name('manajemen-akun.import');
            Route::get('/manajemen-akun/export-template', [AdminController::class, 'exportFormatAkun'])->name('manajemen-akun.export-format');
        });


        // Manajemen Konten
        Route::get('/manajemen-konten', [AdminController::class, 'manajemenKonten'])->name('manajemen-konten');

        // Fitur Prestasi
        Route::get('/tambah-prestasi', [AdminController::class, 'tambahPrestasi'])->name('tambah-prestasi');
        Route::get('/daftar-prestasi', [AdminController::class, 'daftarPrestasi'])->name('daftar-prestasi');
        Route::get('/validasi-prestasi', [AdminController::class, 'validasiPrestasi'])->name('validasi-prestasi');
        Route::get('/laporan-rekap', [AdminController::class, 'laporanRekap'])->name('laporan-rekap');


        // Master Data
        Route::get('/master-data', [AdminController::class, 'masterData'])->name('master-data');
    });

    // Group WD (Wakil Dekan 2/3)
    Route::middleware(['role:wd'])->prefix('wd')->name('wd.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'wd'])->name('dashboard');
    });

    // Group Ketua Jurusan
    Route::middleware(['role:kajur'])->prefix('kajur')->name('kajur.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kajur'])->name('dashboard');
    });

    // Group GPM / Dosen
    Route::middleware(['role:gpm'])->prefix('gpm')->name('gpm.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'gpm'])->name('dashboard');
    });
});

require __DIR__ . '/settings.php';
