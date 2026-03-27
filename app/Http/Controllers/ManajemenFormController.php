<?php

namespace App\Http\Controllers;

use App\Models\FormPrestasi;
use App\Models\FieldFormPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ManajemenFormController extends Controller
{
    // ==========================================
    // BAGIAN 1: MANAJEMEN KATEGORI FORM
    // ==========================================

    public function indexManajemenForm()
    {
        // Ambil semua form beserta jumlah field-nya
        $forms = FormPrestasi::withCount('fields')->latest()->get();
        return view('super_admin.manajemen_form.index', compact('forms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_form' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        FormPrestasi::create([
            'nama_form' => $request->nama_form,
            'slug'      => Str::slug($request->nama_form . '-' . Str::random(5)), // Antisipasi nama sama
            'deskripsi' => $request->deskripsi,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Form Kategori Prestasi berhasil dibuat! Silakan atur pertanyaannya.');
    }

    public function update(Request $request, $id)
    {
        $form = FormPrestasi::findOrFail($id);

        $request->validate([
            'nama_form' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $form->update([
            'nama_form' => $request->nama_form,
            // Slug sengaja tidak diupdate agar URL yang sudah nyebar tidak rusak
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'), // Checkbox aktif/tidak
        ]);

        return back()->with('success', 'Informasi Form berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $form = FormPrestasi::findOrFail($id);
        $form->delete(); // Ini Soft Delete, jadi data aman
        return back()->with('success', 'Form berhasil dinonaktifkan / dihapus sementara!');
    }

    // ==========================================
    // BAGIAN 2: MANAJEMEN FIELD (PERTANYAAN)
    // ==========================================

    public function show($id)
    {
        // Halaman "Atur Pertanyaan"
        $form = FormPrestasi::with(['fields' => function ($q) {
            $q->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        return view('super_admin.manajemen_form.atur_field', compact('form'));
    }

    public function storeField(Request $request, $id)
    {
        $form = FormPrestasi::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
            'tipe'  => 'required|string',
        ]);

        $opsiArray = null;
        if ($request->tipe === 'select' && $request->opsi) {
            $opsiArray = array_map('trim', explode(',', $request->opsi));
        }

        // Cari urutan terakhir
        $urutanTerakhir = $form->fields()->max('urutan') ?? 0;

        $form->fields()->create([
            'nama_field'  => Str::slug($request->label, '_'),
            'label'       => $request->label,
            'tipe'        => $request->tipe,
            'keterangan'  => $request->keterangan,
            'opsi'        => $opsiArray,
            'is_required' => $request->has('is_required'),
            'urutan'      => $urutanTerakhir + 1,
        ]);

        return back()->with('success', 'Pertanyaan baru berhasil ditambahkan!');
    }

    public function updateField(Request $request, $id)
    {
        $field = FieldFormPrestasi::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
            'tipe'  => 'required|string',
        ]);

        $opsiArray = null;
        if ($request->tipe === 'select' && $request->opsi) {
            $opsiArray = array_map('trim', explode(',', $request->opsi));
        }

        $field->update([
            'nama_field'  => Str::slug($request->label, '_'),
            'label'       => $request->label,
            'tipe'        => $request->tipe,
            'keterangan'  => $request->keterangan,
            'opsi'        => $opsiArray,
            'is_required' => $request->has('is_required'),
        ]);

        return back()->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    public function destroyField($id)
    {
        $field = FieldFormPrestasi::findOrFail($id);
        $field->delete(); // Karena tidak pakai soft delete di tabel ini, maka ini force delete
        return back()->with('success', 'Pertanyaan berhasil dihapus permanen!');
    }
}
