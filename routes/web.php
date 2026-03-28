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
use App\Http\Controllers\FormulirPrestasiController;
use App\Http\Controllers\PengaturanSistemController;

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
            Route::get('/import-status', 'checkImportStatus')->name('akun.import-status');
            Route::post('/import-status/clear', 'clearImportStatus')->name('akun.import-status.clear');
            Route::get('/export-template', 'exportFormatAkun')->name('akun.export-format');
            Route::post('/bulk', 'bulkAction')->name('akun.bulk');
            Route::patch('/{id}/aktivasi', 'aktivasiAkun')->name('akun.aktivasi');

            Route::get('/role-permission', 'indexRolePermission')->middleware('permission:akun.manage_role')->name('akun.role-permission');
            Route::post('/role-permission/update', 'updateRolePermission')
                ->middleware('permission:akun.manage_role')
                ->name('akun.role-permission.update');
        });

    // -------------------------------------------------------------
    // 4. MODUL PRESTASI (Inti Sistem)
    // -------------------------------------------------------------
    Route::controller(PrestasiController::class)->prefix('prestasi')->group(function () {

        // Izin Melihat Semua Data Prestasi (Admin, Kajur, Dekan)
        Route::middleware('permission:prestasi.view_all')->group(function () {
            Route::get('/semua', 'indexPrestasi')->name('prestasi.index-all');
            Route::get('/rekap', 'laporanRekap')->name('prestasi.rekap');
        });

        // Izin Lapor/Tambah Prestasi (Internal Admin & Mahasiswa)
        Route::middleware('permission:prestasi.create')->group(function () {
            Route::get('/tambah', 'create')->name('prestasi.create');
            Route::post('/tambah', 'store')->name('prestasi.store');
        });

        // Validasi
        Route::middleware('permission:prestasi.validate')->group(function () {
            Route::get('/antrean-validasi', 'validasiPrestasi')->name('prestasi.validasi');
            Route::get('/{id}/validasi-detail', 'validasiShow')->name('prestasi.validasi-show');
            Route::patch('/validasi-massal', 'validasiMassal')->name('prestasi.validasi-massal');
            Route::patch('/{id}/status', 'updateStatus')->name('prestasi.status-update');
        });

        // Manajemen Alur Persetujuan (Hanya Super Admin / Admin Pusat)
        Route::middleware('permission:prestasi.config_workflow')->group(function () {
            Route::get('/alur-persetujuan', 'alurPersetujuan')->name('prestasi.alur');
            Route::post('/alur-persetujuan/update', 'updateAlur')->name('prestasi.alur.update');
        });

        // Aksi Individual
        Route::get('/{id}/detail', 'show')->name('prestasi.show');
        Route::get('/{id}/edit', 'edit')->name('prestasi.edit');
        Route::put('/{id}', 'update')->name('prestasi.update');
        Route::delete('/{id}', 'destroy')->name('prestasi.destroy');
        Route::post('/{id}/publish', 'publish')->name('prestasi.publish');
    });

    // -------------------------------------------------------------
    // 5. MASTER DATA 
    // -------------------------------------------------------------
    Route::prefix('master')->group(function () {

        // --- A. STRUKTUR AKADEMIK ---
        Route::controller(StrukturAkademikController::class)
            ->prefix('struktur-akademik')
            ->middleware('permission:master.akademik')
            ->group(function () {
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

        // --- B. PENGATURAN SISTEM ---
        Route::controller(PengaturanSistemController::class)
            ->prefix('pengaturan-sistem')
            ->middleware('permission:sistem.config')
            ->group(function () {
                Route::get('/', 'index')->name('pengaturan-sistem.index');
                Route::put('/update', 'update')->name('pengaturan-sistem.update');
            });
    });
    // -------------------------------------------------------------
    // 6. MANAJEMEN FORM BUILDER 
    // -------------------------------------------------------------
    Route::prefix('prestasi/formulir-prestasi')
        ->middleware('permission:prestasi.config_form')
        ->controller(FormulirPrestasiController::class)
        ->name('prestasi.formulir-prestasi.')
        ->group(function () {
            // Form Utama
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            // Atur Pertanyaan (Field)
            Route::get('/{id}/atur', 'show')->name('show');
            Route::post('/{id}/field', 'storeField')->name('field.store');
            Route::put('/field/{id}', 'updateField')->name('field.update');
            Route::delete('/field/{id}', 'destroyField')->name('field.destroy');
            Route::post('/{id}/reorder', 'reorderFields')->name('field.reorder');
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
