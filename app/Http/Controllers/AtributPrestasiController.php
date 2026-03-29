<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TingkatPrestasi;
use App\Models\CapaianPrestasi;

class AtributPrestasiController extends Controller
{
    public function index()
    {
        $tingkat = TingkatPrestasi::orderBy('nama_tingkat')->get();
        $capaian = CapaianPrestasi::orderBy('nama_capaian')->get();

        return view('master_data.atribut_prestasi_index', compact('tingkat', 'capaian'));
    }

    // ============================================
    // TINGKAT PRESTASI
    // ============================================
    public function storeTingkat(Request $request)
    {
        $request->validate([
            'nama_tingkat' => 'required|string|max:100|unique:tingkat_prestasis,nama_tingkat',
        ]);

        TingkatPrestasi::create([
            'nama_tingkat' => $request->nama_tingkat,
            'is_active' => true
        ]);

        return back()->with('success', 'Tingkat Prestasi berhasil ditambahkan!');
    }

    public function destroyTingkat($id)
    {
        $tingkat = TingkatPrestasi::findOrFail($id);

        // Cek apakah data dipakai di tabel prestasis (opsional, kalau mau aman)
        if ($tingkat->prestasis()->count() > 0) {
            return back()->with('error', 'Gagal dihapus! Data tingkat ini sedang digunakan oleh Mahasiswa.');
        }

        $tingkat->delete();
        return back()->with('success', 'Tingkat Prestasi berhasil dihapus!');
    }

    // ============================================
    // CAPAIAN PRESTASI
    // ============================================
    public function storeCapaian(Request $request)
    {
        $request->validate([
            'nama_capaian' => 'required|string|max:150|unique:capaian_prestasis,nama_capaian',
        ]);

        CapaianPrestasi::create([
            'nama_capaian' => $request->nama_capaian,
            'is_active' => true
        ]);

        return back()->with('success', 'Capaian Prestasi berhasil ditambahkan!');
    }

    public function destroyCapaian($id)
    {
        $capaian = CapaianPrestasi::findOrFail($id);

        if ($capaian->prestasis()->count() > 0) {
            return back()->with('error', 'Gagal dihapus! Data capaian ini sedang digunakan oleh Mahasiswa.');
        }

        $capaian->delete();
        return back()->with('success', 'Capaian Prestasi berhasil dihapus!');
    }

    // --- UPDATE TINGKAT ---
    public function updateTingkat(Request $request, $id)
    {
        $request->validate([
            'nama_tingkat' => 'required|string|max:100|unique:tingkat_prestasis,nama_tingkat,' . $id,
        ]);

        TingkatPrestasi::findOrFail($id)->update([
            'nama_tingkat' => $request->nama_tingkat
        ]);

        return back()->with('success', 'Tingkat Prestasi berhasil diperbarui!');
    }

    // --- UPDATE CAPAIAN ---
    public function updateCapaian(Request $request, $id)
    {
        $request->validate([
            'nama_capaian' => 'required|string|max:150|unique:capaian_prestasis,nama_capaian,' . $id,
        ]);

        CapaianPrestasi::findOrFail($id)->update([
            'nama_capaian' => $request->nama_capaian
        ]);

        return back()->with('success', 'Capaian Prestasi berhasil diperbarui!');
    }
}
