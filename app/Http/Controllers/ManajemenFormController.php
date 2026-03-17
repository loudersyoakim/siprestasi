<?php

namespace App\Http\Controllers;

use App\Models\KategoriPrestasi;
use App\Models\FieldFormPrestasi; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ManajemenFormController extends Controller
{
    // Helper prefix untuk membedakan view Admin dan Super Admin
    private function getPrefix()
    {
        return Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
    }

    // 1. Menampilkan Halaman Utama (Daftar Kategori)
    public function indexManajemenForm()
    {
        $prefix = $this->getPrefix();
        $kategori = KategoriPrestasi::withCount('fields')->get();
        return view("$prefix.manajemen-form", compact('kategori'));
    }

    // 2. Simpan Kategori Baru
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

    // 3. Menampilkan Halaman Detail Form Builder (Ini yang error tadi)
    public function show($id)
    {
        $prefix = $this->getPrefix();

        // Ambil kategori beserta pertanyaan di dalamnya, diurutkan berdasarkan kolom 'urutan'
        $kategori = KategoriPrestasi::with(['fields' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        return view("$prefix.manajemen-form-detail", compact('kategori'));
    }

    // 4. Update Nama/Deskripsi Kategori (Dari Modal Edit)
    public function update(Request $request, $id)
    {
        $kategori = KategoriPrestasi::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => Str::slug($request->nama_kategori),
            'deskripsi' => $request->deskripsi
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui');
    }

    // 5. Hapus Kategori (Soft Delete)
    public function destroy($id)
    {
        $kategori = KategoriPrestasi::findOrFail($id);
        $kategori->delete();

        return back()->with('success', 'Formulir dinonaktifkan (Data lama tetap aman)');
    }

    // Menyimpan field/pertanyaan baru ke dalam form
    public function storeField(Request $request, $id)
    {
        // 1. Pastikan kategori form-nya ada
        $kategori = KategoriPrestasi::findOrFail($id);

        // 2. Validasi inputan admin
        $request->validate([
            'label' => 'required|string|max:255',
            'tipe'  => 'required|string',
        ]);

        // 3. Cari urutan terakhir, lalu tambah 1 agar selalu ada di paling bawah
        $urutanTerakhir = $kategori->fields()->max('urutan') ?? 0;

        // 4. Simpan ke tabel field_form_prestasis
        $kategori->fields()->create([
            'nama_field'  => Str::slug($request->label, '_'),

            'label'       => $request->label,
            'tipe'        => $request->tipe,
            'keterangan'  => $request->keterangan,
            'is_required' => $request->has('is_required'), // Kalau dicentang jadi true (1)
            'urutan'      => $urutanTerakhir + 1,
        ]);

        return back()->with('success', 'Pertanyaan baru berhasil ditambahkan!');
    }

    public function updateField(Request $request, $id)
    {
        // Cari field berdasarkan ID
        $field = FieldFormPrestasi::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
            'tipe'  => 'required|string',
        ]);

        $field->update([
            'nama_field'  => Str::slug($request->label, '_'),
            'label'       => $request->label,
            'tipe'        => $request->tipe,
            'keterangan'  => $request->keterangan,
            'is_required' => $request->has('is_required'), // bernilai true jika dicentang
        ]);

        return back()->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    // Hapus Field/Pertanyaan
    public function destroyField($id)
    {
        $field = FieldFormPrestasi::findOrFail($id);

        // Hapus permanen (atau pakai $field->delete() jika pakai soft deletes)
        $field->forceDelete();

        return back()->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
