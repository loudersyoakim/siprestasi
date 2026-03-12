<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManajemenKontenController extends Controller
{
    /**
     * Bagian Manajemen Konten & Lainnya
     */
    public function indexManajemenKonten(Request $request)
    {
        // Ambil input pencarian & tab yang sedang aktif
        $search = $request->search;
        $tab = $request->input('tab', 'berita'); // default ke berita

        // -------------------------------------------------------------
        // QUERY UNTUK TAB BERITA
        // -------------------------------------------------------------
        $queryBerita = Konten::latest();

        // Hanya filter berita jika tab-nya 'berita' dan ada input search
        if ($search && $tab == 'berita') {
            $queryBerita->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");

                // Menambahkan filter status (Draft / Live)
                // Jika user ngetik "Live", cari yg is_published = true
                if (strtolower($search) === 'live') {
                    $q->orWhere('is_published', true);
                } elseif (strtolower($search) === 'draft') {
                    $q->orWhere('is_published', false);
                }
            });
        }
        $kontens = $queryBerita->paginate(10, ['*'], 'page_konten');


        // -------------------------------------------------------------
        // QUERY UNTUK TAB PRESTASI
        // -------------------------------------------------------------
        $queryPrestasi = Prestasi::with(['mahasiswa', 'tingkat'])
            ->where('status', 'approved')
            ->latest();

        // Hanya filter prestasi jika tab-nya 'prestasi' dan ada input search
        if ($search && $tab == 'prestasi') {
            $queryPrestasi->where(function ($q) use ($search) {

                // 1. Cari berdasarkan Nama Prestasi
                $q->where('nama_prestasi', 'like', "%{$search}%")

                    // 2. Cari berdasarkan Nama Mahasiswa (Relasi Many-to-Many)
                    ->orWhereHas('mahasiswa', function ($mhs) use ($search) {
                        $mhs->where('name', 'like', "%{$search}%");
                    })

                    // 3. Cari berdasarkan Nama Tingkat (Relasi BelongsTo)
                    ->orWhereHas('tingkat', function ($tk) use ($search) {
                        $tk->where('nama_tingkat', 'like', "%{$search}%");
                    });

                // 4. Cari berdasarkan Status Landing (Draft / Live)
                if (strtolower($search) === 'publish') {
                    $q->orWhere('is_published', true);
                } elseif (strtolower($search) === 'draft') {
                    $q->orWhere('is_published', false);
                }
            });
        }
        $prestasiApproved = $queryPrestasi->paginate(10, ['*'], 'page_prestasi');

        return view('admin.manajemen-konten', compact('kontens', 'prestasiApproved'));
    }

    public function createKonten()
    {
        return view('admin.manajemen-konten-create');
    }

    public function storeKonten(Request $request)
    {
        $request->validate([
            'title'     => 'required|max:255',
            'category'  => 'required|in:berita,lomba,pengumuman,prestasi',
            'content'   => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('konten', 'public');
        }

        // 3. Simpan ke Database
        Konten::create([
            'user_id' => Auth::id(),
            'title'         => $request->title,
            'slug'          => Str::slug($request->title) . '-' . time(), // Slug unik
            'category'      => $request->category,
            'content'       => $request->content,
            'thumbnail'     => $thumbnailPath,
            'is_published'  => $request->has('is_published'),
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        return redirect()->route('admin.manajemen-konten')->with('success', 'Konten berhasil diterbitkan!');
    }

    public function editKonten($id)
    {
        $konten = Konten::findOrFail($id);
        return view('admin.manajemen-konten-edit', compact('konten'));
    }

    public function updateKonten(Request $request, $id)
    {
        $konten = Konten::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'content' => 'required',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'is_published' => $request->has('is_published')
        ];

        if ($request->hasFile('thumbnail')) {
            // Hapus foto lama jika ada
            if ($konten->thumbnail) {
                Storage::disk('public')->delete($konten->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('konten', 'public');
        }

        $konten->update($data);

        return redirect()->route('admin.manajemen-konten')->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroyKonten($id)
    {
        $konten = Konten::findOrFail($id);

        // Hapus file gambar jika ada
        if ($konten->thumbnail) {
            Storage::disk('public')->delete($konten->thumbnail);
        }

        $konten->delete();
        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
