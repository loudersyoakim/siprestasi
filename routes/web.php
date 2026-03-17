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
// RUTE AUTENTIKASI (Sesuai AuthController Manual)
// =================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Tambahan: Rute Logout agar fungsi logout di AuthController bisa dipanggil
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =================================================================
// RUTE SETELAH LOGIN
// =================================================================
Route::middleware(['auth'])->group(function () {

    // -------------------------------------------------------------
    // 1. Group Admin  dan super admin
    // -------------------------------------------------------------
    Route::middleware(['role:super_admin'])->prefix('super-admin')->name('super_admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('.dashboard');

        // Manajemen Akun 
        Route::controller(ManajemenAkunController::class)->prefix('manajemen-akun')->name('.manajemen-akun')->group(function () {
            Route::get('/', 'indexManajemenAkun');
            Route::get('/create', 'createAkun')->name('.create');
            Route::post('/', 'storeAkun')->name('.store');
            Route::get('/{id}/edit', 'editAkun')->name('.edit');
            Route::put('/{id}', 'updateAkun')->name('.update');
            Route::delete('/{id}', 'destroyAkun')->name('.destroy');
            Route::post('/import', 'importAkun')->name('.import');
            Route::get('/export-template', 'exportFormatAkun')->name('.export-format');

            Route::post('/bulk', 'bulkAction')->name('.bulk');
            Route::patch('/{id}/aktivasi', 'aktivasiAkun')->name('.aktivasi');
        });

        Route::controller(StrukturAkademikController::class)->prefix('struktur-akademik')->name('.struktur-akademik')->group(function () {
            Route::get('/', 'indexStrukturAkademik');
            Route::post('/fakultas', 'storeFakultas')->name('.fakultas.store');
            Route::put('/fakultas/{id}', 'updateFakultas')->name('.fakultas.update');
            Route::delete('/fakultas/{id}', 'destroyFakultas')->name('.fakultas.destroy');

            Route::post('/jurusan', 'storeJurusan')->name('.jurusan.store');
            Route::put('/jurusan/{id}', 'updateJurusan')->name('.jurusan.update');
            Route::delete('/jurusan/{id}', 'destroyJurusan')->name('.jurusan.destroy');

            Route::post('/prodi', 'storeProdi')->name('.prodi.store');
            Route::put('/prodi/{id}', 'updateProdi')->name('.prodi.update');
            Route::delete('/prodi/{id}', 'destroyProdi')->name('.prodi.destroy');
        });

        Route::controller(DaftarMahasiswaController::class)->prefix('daftar-mahasiswa')->name('.daftar-mahasiswa')->group(function () {
            Route::get('/', 'indexDaftarMahasiswa');
            Route::get('/{id}/edit', 'edit')->name('.edit');
            Route::put('/{id}', 'update')->name('.update');
            Route::delete('/{id}', 'destroy')->name('.destroy');
        });

        Route::controller(ManajemenFormController::class)->prefix('manajemen-form')->name('.manajemen-form')->group(function () {
            Route::get('/', 'indexManajemenForm');
            Route::post('/', 'store')->name('.store');
            Route::get('/{id}/edit', 'edit')->name('.edit');
            Route::put('/{id}', 'update')->name('.update');
            Route::delete('/{id}', 'destroy')->name('.destroy');

            // Route tambahan untuk mengatur pertanyaan di dalam form
            Route::get('/{id}/atur', 'show')->name('.show');
        });
    });

    Route::middleware(['role:admin'])->prefix('admin')->name('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'superAdminDashboard'])->name('.dashboard');


        // Manajemen Akun 
        Route::controller(ManajemenAkunController::class)->prefix('manajemen-akun')->name('.manajemen-akun')->group(function () {
            Route::get('/', 'indexManajemenAkun');
            Route::get('/create', 'createAkun')->name('.create');
            Route::post('/', 'storeAkun')->name('.store');
            Route::get('/{id}/edit', 'editAkun')->name('.edit');
            Route::put('/{id}', 'updateAkun')->name('.update');
            Route::delete('/{id}', 'destroyAkun')->name('.destroy');
            Route::post('/import', 'importAkun')->name('.import');
            Route::get('/export-template', 'exportFormatAkun')->name('.export-format');

            Route::post('/bulk', 'bulkAction')->name('super_admin.manajemen-akun.bulk');
        });

        // Manajemen Konten
        Route::controller(ManajemenKontenController::class)->prefix('manajemen-konten')->name('.manajemen-konten')->group(function () {
            Route::get('/', 'indexManajemenKonten');
            Route::get('/create', 'createKonten')->name('.create');
            Route::post('/', 'storeKonten')->name('.store');
            Route::get('/{id}/edit', 'editKonten')->name('.edit');
            Route::put('/{id}', 'updateKonten')->name('.update');
            Route::delete('/{id}', 'destroyKonten')->name('.destroy');
        });

        // Prestasi
        Route::controller(PrestasiController::class)->prefix('prestasi')->name('.prestasi')->group(function () {
            Route::get('/', 'indexPrestasi');
            Route::get('/create', 'createPrestasi')->name('.create');
            Route::post('/', 'storePrestasi')->name('.store');
            Route::get('/{id}/detail', 'showPrestasi')->name('.show');
            Route::get('/{id}/edit', 'editPrestasi')->name('.edit');
            Route::put('/{id}', 'updatePrestasi')->name('.update');
            Route::delete('/{id}', 'destroyPrestasi')->name('.destroy');

            Route::get('/validasi', 'validasiPrestasi')->name('.validasi');
            Route::patch('/validasi/{id}', 'updateStatusPrestasi')->name('.status-update');
            Route::patch('/validasi-massal', 'validasiMassal')->name('.validasi-massal');

            Route::patch('/{id}/publish', 'publishPrestasi')->name('.publish');
            Route::patch('/{id}/takedown', 'takeDownPrestasi')->name('.takedown'); // Diperbaiki dari takedowm
            Route::get('/laporan-rekap', 'laporanRekap')->name('.laporan-rekap');
        });

        // Master Data 
        Route::controller(MasterDataController::class)->prefix('master-data')->name('.master-data')->group(function () {
            Route::get('/', 'indexMasterData');

            // Fakultas, Jurusan, Prodi
            Route::get('/fakultas', 'masterDataFakultas')->name('.fakultas');
            Route::post('/fakultas', 'storeFakultas')->name('.fakultas.store');
            Route::put('/fakultas/{id}', 'updateFakultas')->name('.fakultas.update');
            Route::delete('/fakultas/{id}', 'destroyFakultas')->name('.fakultas.destroy');

            Route::get('/jurusan', 'masterDataJurusan')->name('.jurusan');
            Route::post('/jurusan', 'storeJurusan')->name('.jurusan.store');
            Route::put('/jurusan/{id}', 'updateJurusan')->name('.jurusan.update');
            Route::delete('/jurusan/{id}', 'destroyJurusan')->name('.jurusan.destroy');

            Route::get('/prodi', 'masterDataProdi')->name('.prodi');
            Route::post('/prodi', 'storeProdi')->name('.prodi.store');
            Route::put('/prodi/{id}', 'updateProdi')->name('.prodi.update');
            Route::delete('/prodi/{id}', 'destroyProdi')->name('.prodi.destroy');

            // STA 
            Route::get('/sta', 'masterDataSTA')->name('.sta');
            Route::post('/tahun-akademik/update/{id}', 'updateTahunAkademik')->name('.tahun.update');
            Route::post('/template-surat/update/{id}', 'updateTemplateSurat')->name('.template.update');

            // Atribut Prestasi
            Route::get('/atribut-prestasi', 'masterDataAtributPrestasi')->name('.atribut-prestasi');
            Route::post('/jenis', 'storeJenis')->name('.jenis.store');
            Route::put('/jenis/{id}', 'updateJenis')->name('.jenis.update');
            Route::delete('/jenis/{id}', 'destroyJenis')->name('.jenis.destroy');
            Route::post('/kategori', 'storeKategori')->name('.kategori.store');
            Route::put('/kategori/{id}', 'updateKategori')->name('.kategori.update');
            Route::delete('/kategori/{id}', 'destroyKategori')->name('.kategori.destroy');
            Route::post('/tingkat', 'storeTingkat')->name('.tingkat.store');
            Route::put('/tingkat/{id}', 'updateTingkat')->name('.tingkat.update');
            Route::delete('/tingkat/{id}', 'destroyTingkat')->name('.tingkat.destroy');
        });
    });

    // -------------------------------------------------------------
    // 2. Group Mahasiswa 
    // -------------------------------------------------------------
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mahasiswaDashboard'])->name('dashboard');

        Route::controller(ProfilController::class)->prefix('profil')->name('profil')->group(function () {
            Route::get('/', 'indexProfil');
            Route::post('/update', 'update')->name('.update');
            Route::put('/update-password', 'updatePassword')->name('.update-password');
        });

        // Prestasi 
        Route::controller(PrestasiController::class)->prefix('prestasi')->name('prestasi')->group(function () {
            Route::get('/', 'indexPrestasiMahasiswa');
            Route::get('/create', 'createMahasiswa')->name('.create');
            Route::post('/', 'storeMahasiswa')->name('.store');
            Route::get('/{id}/edit', 'editMahasiswa')->name('.edit');
            Route::put('/{id}', 'updateMahasiswa')->name('.update');
            Route::delete('/{id}', 'destroyMahasiswa')->name('.destroy');
            Route::get('/{id}', 'showMahasiswa')->name('.show');
        });
    });

    // -------------------------------------------------------------
    // 3. Group Wakil Dekan (Disesuaikan dari wd menjadi wakil_dekan)
    // -------------------------------------------------------------
    Route::middleware(['role:wakil_dekan'])->prefix('wakil-dekan')->name('wakil_dekan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'wdDashboard'])->name('dashboard');

        // Prestasi
        Route::controller(PrestasiController::class)->prefix('prestasi')->name('prestasi')->group(function () {
            Route::get('/', 'indexPrestasi');
            Route::get('/create', 'createPrestasi')->name('.create');
            Route::post('/', 'storePrestasi')->name('.store');
            Route::get('/{id}/detail', 'showPrestasi')->name('.show');
            Route::get('/{id}/edit', 'editPrestasi')->name('.edit');
            Route::put('/{id}', 'updatePrestasi')->name('.update');
            Route::delete('/{id}', 'destroyPrestasi')->name('.destroy');

            Route::get('/validasi', 'validasiPrestasi')->name('.validasi');
            Route::patch('/validasi/{id}', 'updateStatusPrestasi')->name('.status-update');
            Route::patch('/validasi-massal', 'validasiMassal')->name('.validasi-massal');

            Route::patch('/{id}/publish', 'publishPrestasi')->name('.publish');
            Route::patch('/{id}/takedown', 'takeDownPrestasi')->name('.takedown');
            Route::get('/laporan-rekap', 'laporanRekap')->name('.laporan-rekap');
        });
    });

    // -------------------------------------------------------------
    // 4. Group Jurusan (Disesuaikan dari kajur menjadi jurusan)
    // -------------------------------------------------------------
    Route::middleware(['role:jurusan'])->prefix('jurusan')->name('jurusan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kajurDashboard'])->name('dashboard');

        // Prestasi
        Route::controller(PrestasiController::class)->prefix('prestasi')->name('prestasi')->group(function () {
            Route::get('/', 'indexPrestasi');
            Route::get('/create', 'createPrestasi')->name('.create');
            Route::post('/', 'storePrestasi')->name('.store');
            Route::get('/{id}/detail', 'showPrestasi')->name('.show');
            Route::get('/{id}/edit', 'editPrestasi')->name('.edit');
            Route::put('/{id}', 'updatePrestasi')->name('.update');
            Route::delete('/{id}', 'destroyPrestasi')->name('.destroy');

            Route::get('/validasi', 'validasiPrestasi')->name('.validasi');
            Route::patch('/validasi/{id}', 'updateStatusPrestasi')->name('.status-update');
            Route::patch('/validasi-massal', 'validasiMassal')->name('.validasi-massal');

            Route::patch('/{id}/publish', 'publishPrestasi')->name('.publish');
            Route::patch('/{id}/takedown', 'takeDownPrestasi')->name('.takedown');
            Route::get('/laporan-rekap', 'laporanRekap')->name('.laporan-rekap');
        });
    });
});

require __DIR__ . '/settings.php';
