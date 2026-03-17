<?php

namespace App\Http\Controllers;

use App\Models\KategoriPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ManajemenFormController extends Controller
{
    private function getPrefix()
    {
        return Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
    }

    public function indexManajemenForm()
    {
        $prefix = $this->getPrefix();
        $kategori = KategoriPrestasi::withCount('fields')->get();
        return view("$prefix.manajemen-form", compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required']);

        KategoriPrestasi::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi
        ]);

        return back()->with('success', 'Formulir baru berhasil dibuat!');
    }

    public function edit($id)
    {
        $prefix = $this->getPrefix();
        $kategori = KategoriPrestasi::findOrFail($id);
        // Bisa pakai modal atau halaman terpisah, ini contoh halaman terpisah
        return view("$prefix.manajemen-form-edit", compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriPrestasi::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route($this->getPrefix() . '.manajemen-form.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kategori = KategoriPrestasi::findOrFail($id);

        $kategori->delete();

        return back()->with('success', 'Formulir dinonaktifkan (Data lama tetap aman)');
    }
}
