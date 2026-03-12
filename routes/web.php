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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::get('/api/statistik-landing', [App\Http\Controllers\LandingController::class, 'getStatistik']);

Route::get('/artikel', [LandingController::class, 'indexAll'])->name('artikel.index');
Route::get('/artikel/{slug}', [LandingController::class, 'show'])->name('artikel.show');

Route::middleware(['auth'])->group(function () {

    // Group Admin
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard Utama
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Manajemen Akun 
        Route::controller(ManajemenAkunController::class)->prefix('manajemen-akun')->name('manajemen-akun')->group(function () {
            Route::get('/', 'indexManajemenAkun');
            Route::get('/create', 'createAkun')->name('.create');
            Route::post('/', 'storeAkun')->name('.store');
            Route::get('/{id}/edit', 'editAkun')->name('.edit');
            Route::put('/{id}', 'updateAkun')->name('.update');
            Route::delete('/{id}', 'destroyAkun')->name('.destroy');
            Route::post('/import', 'importAkun')->name('.import');
            Route::get('/export-template', 'exportFormatAkun')->name('.export-format');
        });


        // Manajemen Konten
        Route::controller(ManajemenKontenController::class)->prefix('manajemen-konten')->name('manajemen-konten')->group(function () {
            Route::get('/', 'indexManajemenKonten');
            Route::get('/create', 'createKonten')->name('.create');
            Route::post('/', 'storeKonten')->name('.store');
            Route::get('/{id}/edit', 'editKonten')->name('.edit');
            Route::put('/{id}', 'updateKonten')->name('.update');
            Route::delete('/{id}', 'destroyKonten')->name('.destroy');
        });


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
            Route::patch('/{id}/takedowm', 'takeDownPrestasi')->name('.takedown');
            Route::get('/laporan-rekap', 'laporanRekap')->name('.laporan-rekap');
        });


        // Master Data 
        Route::controller(MasterDataController::class)->prefix('master-data')->name('master-data')->group(function () {
            Route::get('/', 'indexMasterData');
            // fakultas
            Route::get('/fakultas', 'masterDataFakultas')->name('.fakultas');
            Route::post('/fakultas', 'storeFakultas')->name('.fakultas.store');
            Route::put('/fakultas/{id}', 'updateFakultas')->name('.fakultas.update');
            Route::delete('/fakultas/{id}', 'destroyFakultas')->name('.fakultas.destroy');
            // jurusan
            Route::get('/jurusan', 'masterDataJurusan')->name('.jurusan');
            Route::post('/jurusan', 'storeJurusan')->name('.jurusan.store');
            Route::put('/jurusan/{id}', 'updateJurusan')->name('.jurusan.update');
            Route::delete('/jurusan/{id}', 'destroyJurusan')->name('.jurusan.destroy');
            // prodi
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
            // Jenis Prestasi
            Route::post('/jenis', 'storeJenis')->name('.jenis.store');
            Route::put('/jenis/{id}', 'updateJenis')->name('.jenis.update');
            Route::delete('/jenis/{id}', 'destroyJenis')->name('.jenis.destroy');
            // Kategori Prestasi
            Route::post('/kategori', 'storeKategori')->name('.kategori.store');
            Route::put('/kategori/{id}', 'updateKategori')->name('.kategori.update');
            Route::delete('/kategori/{id}', 'destroyKategori')->name('.kategori.destroy');
            // Tingkat Prestasi
            Route::post('/tingkat', 'storeTingkat')->name('.tingkat.store');
            Route::put('/tingkat/{id}', 'updateTingkat')->name('.tingkat.update');
            Route::delete('/tingkat/{id}', 'destroyTingkat')->name('.tingkat.destroy');
        });
    });

    // Group Mahasiswa 
    Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'mahasiswaDashboard'])->name('dashboard');

        // Profil 
        Route::controller(ProfilController::class)->prefix('profil')->name('profil')->group(function () {
            Route::get('/', 'indexProfil');
            Route::get('/edit', 'editProfil')->name('.edit');
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


    // Group WD (Wakil Dekan 2/3)
    Route::middleware(['role:wd'])->prefix('wd')->name('wd.')->group(function () {
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
            Route::patch('/{id}/takedowm', 'takeDownPrestasi')->name('.takedown');
            Route::get('/laporan-rekap', 'laporanRekap')->name('.laporan-rekap');
        });
    });

    // Group Kajur
    Route::middleware(['role:kajur'])->prefix('kepala-jurusan')->name('kajur.')->group(function () {
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
            Route::patch('/{id}/takedowm', 'takeDownPrestasi')->name('.takedown');
            Route::get('/laporan-rekap', 'laporanRekap')->name('.laporan-rekap');
        });
    });

    // Group GPM / Dosen
    Route::middleware(['role:gpm'])->prefix('panel')->name('gpm.')->group(function () {
        Route::get('/dashboard', [PrestasiCOntroller::class, 'indexPrestasi'])->name('dashboard');

        // Prestasi
        Route::controller(PrestasiController::class)->prefix('prestasi')->name('prestasi')->group(function () {
            Route::get('/{id}/detail', 'showPrestasi')->name('.show');
            Route::get('/rekap', 'laporanRekap')->name('.rekap');
        });
    });
});

require __DIR__ . '/settings.php';
