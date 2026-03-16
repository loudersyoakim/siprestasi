<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarMahasiswaController extends Controller
{
    public function indexDaftarMahasiswa()
    {
        $prefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';

        // Ambil semua user yang rolenya 'mahasiswa'
        $mahasiswa = User::where('role', 'mahasiswa')->latest()->get();

        return view("{$prefix}.daftar-mahasiswa", compact('mahasiswa', 'prefix'));
    }

    // (Opsional nanti) Fungsi untuk hapus/edit bisa kita tambahkan di sini
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Data mahasiswa berhasil dihapus!');
    }
}
