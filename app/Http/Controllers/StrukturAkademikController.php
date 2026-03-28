<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use Illuminate\Http\Request;

class StrukturAkademikController extends Controller
{
    public function indexStrukturAkademik(Request $request)
    {
        // Tangkap parameter tab dari URL, default ke 'fakultas'
        $tab = $request->query('tab', 'fakultas');

        // Panggil semua data yang dibutuhkan View
        $fakultas = Fakultas::all();
        $jurusans = Jurusan::with('fakultas')->get();
        $prodis = Prodi::with(['jurusan.fakultas'])->get();

        // Sesuaikan nama folder view Abang
        return view('master_data.struktur_akademik_index', compact('tab', 'fakultas', 'jurusans', 'prodis'));
    }

    // --- FAKULTAS ---
    public function storeFakultas(Request $request)
    {
        $request->validate(['kode_fakultas' => 'required|unique:fakultas', 'nama_fakultas' => 'required']);
        Fakultas::create($request->all());
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'fakultas'])->with('success', 'Fakultas berhasil ditambahkan');
    }

    public function updateFakultas(Request $request, $id)
    {
        $fakultas = Fakultas::findOrFail($id);
        $request->validate(['kode_fakultas' => 'required|unique:fakultas,kode_fakultas,' . $id, 'nama_fakultas' => 'required']);
        $fakultas->update($request->all());
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'fakultas'])->with('success', 'Fakultas berhasil diupdate');
    }

    public function destroyFakultas($id)
    {
        Fakultas::findOrFail($id)->delete();
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'fakultas'])->with('success', 'Fakultas berhasil dihapus');
    }

    // --- JURUSAN ---
    public function storeJurusan(Request $request)
    {
        $request->validate(['fakultas_id' => 'required', 'nama_jurusan' => 'required']);
        Jurusan::create($request->all());
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'jurusan'])->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function updateJurusan(Request $request, $id)
    {
        Jurusan::findOrFail($id)->update($request->all());
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'jurusan'])->with('success', 'Jurusan berhasil diupdate');
    }

    public function destroyJurusan($id)
    {
        Jurusan::findOrFail($id)->delete();
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'jurusan'])->with('success', 'Jurusan berhasil dihapus');
    }

    // --- PRODI ---
    public function storeProdi(Request $request)
    {
        $request->validate(['jurusan_id' => 'required', 'kode_prodi' => 'required|unique:prodi', 'nama_prodi' => 'required', 'jenjang' => 'required']);
        Prodi::create($request->all());
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'prodi'])->with('success', 'Prodi berhasil ditambahkan');
    }

    public function updateProdi(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);
        $request->validate(['kode_prodi' => 'required|unique:prodi,kode_prodi,' . $id, 'nama_prodi' => 'required']);
        $prodi->update($request->all());
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'prodi'])->with('success', 'Prodi berhasil diupdate');
    }

    public function destroyProdi($id)
    {
        Prodi::findOrFail($id)->delete();
        return redirect()->route('super_admin.struktur-akademik', ['tab' => 'prodi'])->with('success', 'Prodi berhasil dihapus');
    }
}
