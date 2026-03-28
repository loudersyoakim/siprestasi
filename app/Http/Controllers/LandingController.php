<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konten;
use App\Models\LandingSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LandingController extends Controller
{
    /**
     * Helper untuk mengambil dan menggabungkan SEMUA pengaturan
     * (Dari tabel pengaturan_sistem lama + landing_settings baru)
     */
    private function getPengaturan()
    {
        // 1. Ambil pengaturan sistem dasar (Nama Web, Kontak, dll)
        $pengaturanSistem = DB::table('pengaturan_sistem')->pluck('nilai', 'kunci')->toArray();

        // 2. Ambil pengaturan tampilan landing (Hero, Widget Leaderboard, dll)
        $pengaturanLanding = [];
        if (Schema::hasTable('landing_settings')) {
            $landingSettingsDb = LandingSetting::all();
            foreach ($landingSettingsDb as $setting) {
                if ($setting->type === 'toggle') {
                    // Jika toggle, simpan status true/false
                    $pengaturanLanding[$setting->key] = (bool) $setting->is_active;
                } else {
                    // Jika text/number, simpan nilainya kalau aktif. Kalau mati, false.
                    $pengaturanLanding[$setting->key] = $setting->is_active ? $setting->value : false;
                }
            }
        }

        // Gabungkan kedua array pengaturan agar bisa dipakai global di semua view
        return array_merge($pengaturanSistem, $pengaturanLanding);
    }

    /**
     * 1. Halaman Beranda Utama (Landing Page)
     */
    public function index()
    {
        $pengaturan = $this->getPengaturan();

        // --- Logika Top Leaderboard Otomatis ---
        $leaderboard = [];
        // Cek apakah Admin menyalakan fitur leaderboard dan set limitnya
        if (!empty($pengaturan['show_leaderboard'])) {
            $limit = (int) $pengaturan['show_leaderboard'];

            // Ambil mahasiswa yang punya prestasi "Approved" dan hitung totalnya
            // (Catatan: Pastikan relasi di model User Abang bernama 'prestasi' atau 'prestasis')
            $leaderboard = User::with('prodi')
                ->whereHas('prestasis', function ($query) { // <--- Tambah huruf 's'
                    $query->where('status', 'Approved');
                })
                ->withCount(['prestasis as total_poin' => function ($query) { // <--- Tambah huruf 's'
                    $query->where('status', 'Approved');
                }])
                ->orderByDesc('total_poin')
                ->take($limit)
                ->get();
        }

        // --- Logika Berita & Mading Digital ---
        $kontenAktif = Konten::where('is_aktif', true)->latest()->get();
        $headline = $kontenAktif->first();
        // Skip 1 (karena udah jadi headline), lalu ambil 7 untuk list
        $listBerita = $kontenAktif->skip(1)->take(7);

        return view('landing', compact('pengaturan', 'headline', 'listBerita', 'leaderboard'));
    }

    /**
     * 2. Halaman Daftar Semua Berita (Mading Publik)
     */
    public function indexAll(Request $request)
    {
        $pengaturan = $this->getPengaturan();
        $query = Konten::where('is_aktif', true)->latest();

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Logika Filter Kategori
        if ($request->has('category') && $request->category != 'semua') {
            $query->where('kategori', $request->category);
        }

        $artikels = $query->paginate(9);

        return view('daftar-artikel', compact('artikels', 'pengaturan'));
    }

    /**
     * 3. Halaman Baca Artikel / Berita Detail
     */
    public function show($slug)
    {
        $pengaturan = $this->getPengaturan();
        $artikel = Konten::where('slug', $slug)->where('is_aktif', true)->firstOrFail();

        // Ambil 4 berita lainnya untuk rekomendasi di sidebar
        $rekomendasi = Konten::where('is_aktif', true)
            ->where('id', '!=', $artikel->id)
            ->latest()
            ->take(4)
            ->get();

        return view('artikel', compact('artikel', 'rekomendasi', 'pengaturan'));
    }
}
