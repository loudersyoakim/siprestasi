<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StrukturAkademikController extends Controller
{
    private function getRolePrefix()
    {
        return Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
    }

    // 1. Tampilkan Halaman Utama (Semua Tab)
    public function indexStrukturAkademik(Request $request)
    {
        $tab = $request->query('tab', 'fakultas'); // Default tab fakultas
        $prefix = $this->getRolePrefix();

        $fakultas = Fakultas::latest()->get();
        $jurusans = Jurusan::with('fakultas')->latest()->get();
        $prodis = Prodi::with('jurusan.fakultas')->latest()->get();

        return view("{$prefix}.struktur-akademik", compact('tab', 'fakultas', 'jurusans', 'prodis', 'prefix'));
    }

    // ==========================================
    // FAKULTAS
    // ==========================================
    public function storeFakultas(Request $request)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:1|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:255',
            'singkatan'     => 'nullable|string|max:20',
        ]);

        Fakultas::create($request->all());
        return back()->with('success', 'Data Fakultas berhasil ditambahkan!');
    }

    // ==========================================
    // JURUSAN
    // ==========================================
    public function storeJurusan(Request $request)
    {
        $request->validate([
            'fakultas_id'  => 'required|exists:fakultas,id',
            'nama_jurusan' => 'required|string|max:255',
        ]);

        Jurusan::create($request->all());
        return redirect()->route($this->getRolePrefix() . '.struktur-akademik', ['tab' => 'jurusan'])
            ->with('success', 'Data Jurusan berhasil ditambahkan!');
    }

    // ==========================================
    // PROGRAM STUDI
    // ==========================================
    public function storeProdi(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id',
            'kode_prodi' => 'required|string|max:2',
            'nama_prodi' => 'required|string|max:255',
            'jenjang'    => 'required|string|max:10',
        ]);

        Prodi::create($request->all());
        return redirect()->route($this->getRolePrefix() . '.struktur-akademik', ['tab' => 'prodi'])
            ->with('success', 'Data Program Studi berhasil ditambahkan!');
    }

    // ==========================================
    // FAKULTAS
    // ==========================================
    public function updateFakultas(Request $request, $id)
    {
        $fakultas = Fakultas::findOrFail($id);
        $request->validate([
            'kode_fakultas' => 'required|string|max:1|unique:fakultas,kode_fakultas,' . $id,
            'nama_fakultas' => 'required|string|max:255',
            'singkatan'     => 'nullable|string|max:20',
        ]);
        $fakultas->update($request->all());
        return back()->with('success', 'Data Fakultas berhasil diupdate!');
    }

    public function destroyFakultas($id)
    {
        $fakultas = Fakultas::findOrFail($id);

        // PENCEGAHAN: Cek apakah Fakultas masih punya Jurusan
        if (\App\Models\Jurusan::where('fakultas_id', $id)->exists()) {
            return back()->with('error', 'Gagal menghapus! Fakultas ini masih memiliki data Jurusan di dalamnya.');
        }

        $fakultas->delete();
        return back()->with('success', 'Data Fakultas berhasil dihapus!');
    }

    // ==========================================
    // JURUSAN
    // ==========================================
    public function updateJurusan(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $request->validate([
            'fakultas_id'  => 'required|exists:fakultas,id',
            'nama_jurusan' => 'required|string|max:255',
        ]);
        $jurusan->update($request->all());
        return back()->with('success', 'Data Jurusan berhasil diupdate!');
    }

    public function destroyJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        // PENCEGAHAN: Cek apakah Jurusan masih punya Prodi
        if (\App\Models\Prodi::where('jurusan_id', $id)->exists()) {
            return back()->with('error', 'Gagal menghapus! Jurusan ini masih memiliki Program Studi di dalamnya.');
        }

        $jurusan->delete();
        return back()->with('success', 'Data Jurusan berhasil dihapus!');
    }

    // ==========================================
    // PROGRAM STUDI
    // ==========================================
    public function updateProdi(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id',
            'kode_prodi' => 'required|string|max:2|unique:prodi,kode_prodi,' . $id,
            'nama_prodi' => 'required|string|max:255',
            'jenjang'    => 'required|string|max:10',
        ]);
        $prodi->update($request->all());
        return back()->with('success', 'Data Program Studi berhasil diupdate!');
    }

    public function destroyProdi($id)
    {
        // Prodi adalah entitas paling bawah, jadi aman untuk dihapus langsung (kecuali ada relasi lain nanti)
        Prodi::findOrFail($id)->delete();
        return back()->with('success', 'Data Program Studi berhasil dihapus!');
    }
}
