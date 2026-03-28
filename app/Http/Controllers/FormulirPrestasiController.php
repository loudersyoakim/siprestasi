<?php

namespace App\Http\Controllers;

use App\Models\FormPrestasi;
use App\Models\FieldFormPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FormulirPrestasiController extends Controller
{
    public function index()
    {
        $kategori = FormPrestasi::withCount('fields')->latest()->get();
        return view('prestasi.formulir_prestasi_index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_form' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        FormPrestasi::create([
            'nama_form' => $request->nama_form,
            'slug'      => Str::slug($request->nama_form . '-' . Str::random(5)),
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
            'deskripsi' => $request->deskripsi,
        ]);

        return back()->with('success', 'Informasi Form berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $form = FormPrestasi::findOrFail($id);
        $form->delete();
        return back()->with('success', 'Kategori Form berhasil dinonaktifkan!');
    }

    public function show($id)
    {
        $form = FormPrestasi::with(['fields' => function ($q) {
            $q->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        return view('prestasi.formulir_prestasi_show', compact('form'));
    }

    public function storeField(Request $request, $id)
    {
        $form = FormPrestasi::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
            'tipe'  => 'required|string',
        ]);

        $opsiArray = null;
        // FIX: Tambahkan pengecekan untuk radio dan checkbox
        if (in_array($request->tipe, ['select', 'radio', 'checkbox']) && $request->opsi) {
            $opsiArray = array_map('trim', explode(',', $request->opsi));
        }

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
        // FIX: Tambahkan pengecekan untuk radio dan checkbox
        if (in_array($request->tipe, ['select', 'radio', 'checkbox']) && $request->opsi) {
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
        $field->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus permanen!');
    }

    public function reorderFields(Request $request, $id)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        foreach ($request->order as $urutan => $fieldId) {
            FieldFormPrestasi::where('id', $fieldId)
                ->where('form_prestasi_id', $id)
                ->update(['urutan' => $urutan + 1]);
        }

        return response()->json(['status' => 'success', 'message' => 'Urutan berhasil disimpan!']);
    }
}
