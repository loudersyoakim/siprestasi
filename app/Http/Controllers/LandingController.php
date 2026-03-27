<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konten;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Helper untuk mengambil semua pengaturan sistem menjadi array
     */
    private function getPengaturan()
    {
        // Mengubah baris di DB menjadi array, misal: ['kontak_wa' => '081234567890', 'nama_aplikasi' => 'SIARPRESTASI']
        return DB::table('pengaturan_sistem')->pluck('nilai', 'kunci')->toArray();
    }

    public function index()
    {
        $pengaturan = $this->getPengaturan();

        $kontenAktif = Konten::where('is_aktif', true)->latest()->get();
        $headline = $kontenAktif->first();
        $listBerita = $kontenAktif->skip(1)->take(7);

        return view('landing', compact('pengaturan', 'headline', 'listBerita'));
    }

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

    public function show($slug)
    {
        $pengaturan = $this->getPengaturan();
        $artikel = Konten::where('slug', $slug)->where('is_aktif', true)->firstOrFail();

        // Ambil 4 berita lainnya untuk rekomendasi di sidebar
        $rekomendasi = Konten::where('is_aktif', true)->where('id', '!=', $artikel->id)->latest()->take(4)->get();

        return view('artikel', compact('artikel', 'rekomendasi', 'pengaturan'));
    }
}
