<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenAkunController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\ManajemenKontenController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\DaftarMahasiswaController;
use App\Http\Controllers\StrukturAkademikController;
use App\Http\Controllers\ManajemenFormController;

// =================================================================
// RUTE PUBLIK & LANDING PAGE
// =================================================================
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/api/statistik-landing', [LandingController::class, 'getStatistik']);
Route::get('/artikel', [LandingController::class, 'indexAll'])->name('artikel.index');
Route::get('/artikel/{slug}', [LandingController::class, 'show'])->name('artikel.show');

// =================================================================
// RUTE AUTENTIKASI
// =================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =================================================================
// RUTE SISTEM (FULL PERMISSION BASED)
// Tidak ada lagi pengelompokan berdasarkan Role. Semua berdasarkan FITUR.
// =================================================================
Route::middleware(['auth'])->group(function () {

    // -------------------------------------------------------------
    // 1. DASHBOARD AREA (Setiap role punya dashboard yang menyesuaikan)
    // -------------------------------------------------------------
    Route::prefix('dashboard')->group(function () {
        Route::get('/global', [DashboardController::class, 'superAdminDashboard'])->middleware('permission:dashboard.view_global')->name('super_admin.dashboard');
        Route::get('/admin', [DashboardController::class, 'superAdminDashboard'])->middleware('permission:dashboard.view_global')->name('admin.dashboard');
        Route::get('/fakultas', [DashboardController::class, 'wdDashboard'])->middleware('permission:dashboard.view_fakultas')->name('fakultas.dashboard');
        Route::get('/jurusan', [DashboardController::class, 'kajurDashboard'])->middleware('permission:dashboard.view_jurusan')->name('jurusan.dashboard');
        Route::get('/pribadi', [DashboardController::class, 'mahasiswaDashboard'])->middleware('permission:dashboard.view_pribadi')->name('mahasiswa.dashboard');
    });

    // -------------------------------------------------------------
    // 2. PROFIL AKUN
    // -------------------------------------------------------------
    Route::controller(ProfilController::class)->prefix('profil')->group(function () {
        Route::get('/', 'indexProfil')->name('mahasiswa.profil');
        Route::post('/update', 'update')->name('profil.update');
        Route::put('/update-password', 'updatePassword')->name('profil.update-password');
    });

    // -------------------------------------------------------------
    // 3. MANAJEMEN PENGGUNA (AKUN)
    // -------------------------------------------------------------
    Route::controller(ManajemenAkunController::class)->prefix('manajemen-akun')
        ->middleware('permission:akun.view_list')->group(function () {

            // Nama route ini dipertahankan agar Sidebar otomatis tidak error
            Route::get('/', 'indexManajemenAkun')->name('super_admin.manajemen-akun');
            Route::get('/data', 'indexManajemenAkun')->name('admin.manajemen-akun');

            // Aksi CRUD Akun (Semua user yang punya izin 'akun.view_list' bisa akses)
            Route::get('/create', 'createAkun')->name('akun.create');
            Route::post('/', 'storeAkun')->name('akun.store');
            Route::get('/{id}/edit', 'editAkun')->name('akun.edit');
            Route::put('/{id}', 'updateAkun')->name('akun.update');
            Route::delete('/{id}', 'destroyAkun')->name('akun.destroy');
            Route::post('/import', 'importAkun')->name('akun.import');
            Route::get('/export-template', 'exportFormatAkun')->name('akun.export-format');
            Route::post('/bulk', 'bulkAction')->name('akun.bulk');
            Route::patch('/{id}/aktivasi', 'aktivasiAkun')->name('akun.aktivasi');
        });

    // -------------------------------------------------------------
    // 4. MODUL PRESTASI (Inti Sistem)
    // -------------------------------------------------------------
    Route::controller(PrestasiController::class)->prefix('prestasi')->group(function () {

        // Izin Lapor Prestasi Baru (Untuk Mahasiswa atau Admin yang menginput)
        Route::middleware('permission:prestasi.create')->group(function () {
            Route::get('/lapor', 'createMahasiswa')->name('mahasiswa.prestasi.create');
            Route::post('/lapor', 'storeMahasiswa')->name('mahasiswa.prestasi.store');
        });

        // Izin Melihat Daftar Prestasi Pribadi (Mahasiswa)
        Route::get('/riwayat', 'indexPrestasiMahasiswa')
            ->middleware('permission:prestasi.view_own')
            ->name('mahasiswa.prestasi');

        // Izin Melihat Semua Data Prestasi (Admin, Kajur, Dekan)
        Route::middleware('permission:prestasi.view_all')->group(function () {
            Route::get('/semua', 'indexPrestasi')->name('super_admin.prestasi');
            Route::get('/semua/admin', 'indexPrestasi')->name('admin.prestasi');
            Route::get('/semua/fakultas', 'indexPrestasi')->name('fakultas.prestasi');
            Route::get('/semua/jurusan', 'indexPrestasi')->name('jurusan.prestasi');
            Route::get('/laporan/rekap', 'laporanRekap')->name('prestasi.laporan-rekap');
        });

        // Izin Validasi Prestasi (Kajur, Dekan, Admin)
        Route::middleware('permission:prestasi.validate')->group(function () {
            Route::get('/antrean-validasi', 'validasiPrestasi')->name('prestasi.validasi');
            Route::patch('/validasi/{id}', 'updateStatusPrestasi')->name('prestasi.status-update');
        });

        // Aksi Detail & Edit Prestasi (Secara otomatis di-handle Controller berdasarkan kepemilikan)
        Route::get('/{id}/detail', 'showPrestasi')->name('prestasi.show');
        Route::get('/{id}/edit', 'editMahasiswa')->name('prestasi.edit');
        Route::put('/{id}', 'updateMahasiswa')->name('prestasi.update');
        Route::delete('/{id}', 'destroyMahasiswa')->name('prestasi.destroy');
    });

    // -------------------------------------------------------------
    // 5. MASTER DATA & STRUKTUR AKADEMIK
    // -------------------------------------------------------------
    Route::controller(StrukturAkademikController::class)->prefix('master/struktur-akademik')
        ->middleware('permission:master.akademik')->group(function () {

            Route::get('/', 'indexStrukturAkademik')->name('super_admin.struktur-akademik');

            Route::post('/fakultas', 'storeFakultas')->name('fakultas.store');
            Route::put('/fakultas/{id}', 'updateFakultas')->name('fakultas.update');
            Route::delete('/fakultas/{id}', 'destroyFakultas')->name('fakultas.destroy');
            Route::post('/jurusan', 'storeJurusan')->name('jurusan.store');
            Route::put('/jurusan/{id}', 'updateJurusan')->name('jurusan.update');
            Route::delete('/jurusan/{id}', 'destroyJurusan')->name('jurusan.destroy');
            Route::post('/prodi', 'storeProdi')->name('prodi.store');
            Route::put('/prodi/{id}', 'updateProdi')->name('prodi.update');
            Route::delete('/prodi/{id}', 'destroyProdi')->name('prodi.destroy');
        });

    // -------------------------------------------------------------
    // 6. MANAJEMEN FORM BUILDER (Kategori Penilaian)
    // -------------------------------------------------------------
    Route::controller(ManajemenFormController::class)->prefix('pengaturan/form-prestasi')
        ->middleware('permission:prestasi.config_form')->group(function () {

            Route::get('/', 'indexManajemenForm')->name('super_admin.manajemen-form');
            Route::post('/', 'store')->name('form.store');
            Route::get('/{id}/edit', 'edit')->name('form.edit');
            Route::put('/{id}', 'update')->name('form.update');
            Route::delete('/{id}', 'destroy')->name('form.destroy');

            Route::post('/{id}/atur', 'storeField')->name('form.storeField');
            Route::get('/{id}/atur', 'show')->name('form.show');
            Route::put('/field/{id}', 'updateField')->name('form.updateField');
            Route::delete('/field/{id}', 'destroyField')->name('form.destroyField');
        });

    // -------------------------------------------------------------
    // 7. MADING DIGITAL & KONTEN PUBLIKASI
    // -------------------------------------------------------------
    Route::controller(ManajemenKontenController::class)->prefix('mading-digital')
        ->middleware('permission:konten.manage_artikel')->group(function () {

            Route::get('/', 'indexManajemenKonten')->name('konten.index');
            Route::get('/create', 'createKonten')->name('konten.create');
            Route::post('/', 'storeKonten')->name('konten.store');
            Route::get('/{id}/edit', 'editKonten')->name('konten.edit');
            Route::put('/{id}', 'updateKonten')->name('konten.update');
            Route::delete('/{id}', 'destroyKonten')->name('konten.destroy');
        });
});

require __DIR__ . '/settings.php';
