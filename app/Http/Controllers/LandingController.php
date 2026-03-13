<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\TingkatPrestasi;
use App\Models\Prestasi;
use App\Models\Konten;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{

    public function index()
    {
        $allFeeds = Konten::where('is_published', true)
            ->latest()
            ->take(7)
            ->get();

        $headline = $allFeeds->first();
        $listBerita = $allFeeds->skip(1);

        return view('landing', compact('headline', 'listBerita'));
    }

    public function indexAll(Request $request)
    {
        $query = Konten::where('is_published', true)->latest();

        // Logika Pencarian
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Logika Filter Kategori
        if ($request->has('category') && $request->category != 'semua') {
            $query->where('category', $request->category);
        }

        $artikels = $query->paginate(9); // Tampilkan 9 berita per halaman

        return view('daftar-artikel', compact('artikels'));
    }

    public function show($slug)
    {
        // Cari konten yang dipublish berdasarkan slug
        $artikel = Konten::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Ambil 3 berita lainnya untuk rekomendasi di bawah/samping
        $rekomendasi = \App\Models\Konten::where('id', '!=', $artikel->id)
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        return view('artikel', compact('artikel', 'rekomendasi'));
    }

    public function getStatistik(Request $request)
    {
        $type = $request->query('type', 'tingkat');
        $tableExists = Schema::hasTable('prestasi');

        if (!$tableExists) {
            return response()->json(['labels' => [], 'values' => []]);
        }

        // 1. Tentukan Master Data & Kolom Target di tabel 'mahasiswa'
        $column = '';
        switch ($type) {
            case 'fakultas':
                $master = Fakultas::all();
                $column = 'fakultas_id';
                break;
            case 'jurusan':
                $master = Jurusan::all();
                $column = 'jurusan_id';
                break;
            case 'prodi':
                $master = Prodi::all();
                $column = 'prodi_id';
                break;
            default:
                $master = TingkatPrestasi::all();
                break;
        }

        // 2. Hitung TOTAL SELURUH PARTISIPASI (Baris di pivot yang prestasinya APPROVED)
        $totalInvolvements = DB::table('prestasi_user')
            ->join('prestasi', 'prestasi_user.prestasi_id', '=', 'prestasi.id')
            ->where('prestasi.status', 'approved')
            ->count();

        $tempLabels = [];
        $tempValues = [];
        $totalCountCategorized = 0;

        // 3. Looping Master Data
        foreach ($master as $item) {
            $name = ($type == 'fakultas') ? $item->nama_fakultas : (($type == 'jurusan') ? $item->nama_jurusan : (($type == 'prodi') ? $item->nama_prodi : $item->nama_tingkat));

            if ($type == 'tingkat') {
                // Hitung partisipasi per TINGKAT (Data ada di tabel prestasi)
                $count = DB::table('prestasi_user')
                    ->join('prestasi', 'prestasi_user.prestasi_id', '=', 'prestasi.id')
                    ->where('prestasi.status', 'approved')
                    ->where('prestasi.tingkat_id', $item->id)
                    ->count();
            } else {
                // Hitung partisipasi per FAKULTAS/JURUSAN/PRODI (Data ada di tabel mahasiswa)
                $count = DB::table('prestasi_user')
                    ->join('prestasi', 'prestasi_user.prestasi_id', '=', 'prestasi.id')
                    ->join('mahasiswa', 'prestasi_user.user_id', '=', 'mahasiswa.user_id') // Jembatan ke tabel mahasiswa
                    ->where('prestasi.status', 'approved')
                    ->where('mahasiswa.' . $column, $item->id)
                    ->count();
            }

            if ($count > 0) {
                $tempLabels[] = $name;
                $tempValues[] = $count;
                $totalCountCategorized += $count;
            }
        }

        // 4. LOGIKA "LAINNYA" (Untuk mahasiswa yang belum isi profil/tabel mahasiswa masih kosong)
        if ($totalInvolvements > $totalCountCategorized) {
            $tempLabels[] = "Lainnya (Profil Belum Lengkap)";
            $tempValues[] = $totalInvolvements - $totalCountCategorized;
        }

        // 5. Final Check (Data Asli vs Dummy)
        if ($totalInvolvements > 0) {
            $labels = $tempLabels;
            $values = $tempValues;
            $isDummy = false;
        } else {
            // Fallback Dummy kalau database bener-bener kosong melompong
            foreach ($master->take(5) as $index => $item) {
                $labels[] = ($type == 'fakultas') ? $item->nama_fakultas : $item->nama_tingkat;
                $values[] = 10 - $index;
            }
            $isDummy = true;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'is_dummy' => $isDummy
        ]);
    }
}
