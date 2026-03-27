<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManajemenKontenController extends Controller
{
    public function indexManajemenKonten()
    {
        $konten = Konten::with('penulis')->latest()->get();
        return view('admin.manajemen_konten.index', compact('konten'));
    }

    public function createKonten()
    {
        return view('admin.manajemen_konten.create');
    }

    public function storeKonten(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_konten' => 'required',
            'gambar_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('gambar_cover')) {
            $path = $request->file('gambar_cover')->store('konten_covers', 'public');
        }

        Konten::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul . '-' . Str::random(5)),
            'isi_konten' => $request->isi_konten,
            'gambar_cover' => $path,
            'is_aktif' => $request->has('is_aktif'),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.manajemen-konten')->with('success', 'Konten / Pengumuman berhasil dipublikasikan!');
    }

    public function editKonten($id)
    {
        $konten = Konten::findOrFail($id);
        return view('admin.manajemen_konten.edit', compact('konten'));
    }

    public function updateKonten(Request $request, $id)
    {
        $konten = Konten::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi_konten' => 'required',
            'gambar_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $konten->gambar_cover;
        if ($request->hasFile('gambar_cover')) {
            if ($path) Storage::disk('public')->delete($path);
            $path = $request->file('gambar_cover')->store('konten_covers', 'public');
        }

        $konten->update([
            'judul' => $request->judul,
            'isi_konten' => $request->isi_konten,
            'gambar_cover' => $path,
            'is_aktif' => $request->has('is_aktif')
        ]);

        return redirect()->route('admin.manajemen-konten')->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroyKonten($id)
    {
        $konten = Konten::findOrFail($id);
        if ($konten->gambar_cover) {
            Storage::disk('public')->delete($konten->gambar_cover);
        }
        $konten->delete();

        return back()->with('success', 'Konten berhasil dihapus permanen.');
    }
}
