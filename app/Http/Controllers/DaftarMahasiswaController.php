<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Prodi;

class DaftarMahasiswaController extends Controller
{
    public function indexDaftarMahasiswa(Request $request)
    {
        $prefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';

        // 1. Ambil inputan dari form filter & search
        $search = $request->search;
        $fakultas_id = $request->fakultas_id;
        $jurusan_id = $request->jurusan_id;
        $prodi_id = $request->prodi_id;

        // 2. Buat Query Dasar
        $query = User::where('role', 'mahasiswa')->with('mahasiswa.prodi.jurusan.fakultas');

        // 3. Logika Pencarian (Berdasarkan Nama atau NIM)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim_nip', 'like', "%{$search}%");
            });
        }

        // 4. Logika Filter Dropdown (Tembus ke relasi tabel Mahasiswa)
        if ($fakultas_id || $jurusan_id || $prodi_id) {
            $query->whereHas('mahasiswa', function ($q) use ($fakultas_id, $jurusan_id, $prodi_id) {
                if ($fakultas_id) $q->where('fakultas_id', $fakultas_id);
                if ($jurusan_id) $q->where('jurusan_id', $jurusan_id);
                if ($prodi_id)   $q->where('prodi_id', $prodi_id);
            });
        }

        // 5. Eksekusi Query
        $mahasiswa = $query->latest()->get();

        // 6. Ambil Data Master untuk isi Dropdown Filter
        $fakultas = Fakultas::all();
        $jurusans = Jurusan::all();
        $prodis = Prodi::all();

        return view("{$prefix}.daftar-mahasiswa", compact('mahasiswa', 'prefix', 'fakultas', 'jurusans', 'prodis'));
    }

    public function edit($id)
    {
        $prefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';

        // Ambil data user beserta relasi mahasiswanya
        $user = User::with('mahasiswa')->findOrFail($id);

        $fakultas = Fakultas::all();
        $jurusans = Jurusan::all();
        $prodis = Prodi::all();

        return view("{$prefix}.daftar-mahasiswa-edit", compact('user', 'prefix', 'fakultas', 'jurusans', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $prefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'nim_nip'       => 'required|string|max:50|unique:users,nim_nip,' . $user->id,
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'angkatan'      => 'nullable|digits:4|integer|min:2000|max:' . (date('Y') + 1),
            'fakultas_id'   => 'nullable|exists:fakultas,id',
            'jurusan_id'    => 'nullable|exists:jurusan,id',
            'prodi_id'      => 'nullable|exists:prodi,id',
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'nim_nip' => $request->nim_nip,
        ]);

        $mahasiswa = Mahasiswa::firstOrCreate(['user_id' => $user->id]);

        // LOGIKA FOTO: Hapus atau Ganti
        if ($request->hapus_foto == '1') {
            if ($mahasiswa->foto_profil && Storage::disk('public')->exists($mahasiswa->foto_profil)) {
                Storage::disk('public')->delete($mahasiswa->foto_profil);
            }
            $mahasiswa->foto_profil = null;
        } elseif ($request->filled('foto_base64')) {
            $base64Image = str_replace(' ', '+', $request->foto_base64);
            $imageParts = explode(";base64,", $base64Image);

            if (count($imageParts) == 2) {
                $imageDecode = base64_decode($imageParts[1]);

                if (!Storage::disk('public')->exists('profil')) {
                    Storage::disk('public')->makeDirectory('profil');
                }

                if ($mahasiswa->foto_profil && Storage::disk('public')->exists($mahasiswa->foto_profil)) {
                    Storage::disk('public')->delete($mahasiswa->foto_profil);
                }

                $fileName = 'profil/' . ($user->nim_nip ?? 'unknown') . '_' . time() . '.jpg';
                Storage::disk('public')->put($fileName, $imageDecode);
                $mahasiswa->foto_profil = $fileName;
            }
        }

        $mahasiswa->update([
            'foto_profil'   => $mahasiswa->foto_profil,
            'jenis_kelamin' => $request->jenis_kelamin,
            'angkatan'      => $request->angkatan,
            'fakultas_id'   => $request->fakultas_id,
            'jurusan_id'    => $request->jurusan_id,
            'prodi_id'      => $request->prodi_id,
        ]);

        return redirect()->route($prefix . '.daftar-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus foto profil fisik jika ada sebelum akun dihapus
        if ($user->mahasiswa && $user->mahasiswa->foto_profil) {
            if (Storage::disk('public')->exists($user->mahasiswa->foto_profil)) {
                Storage::disk('public')->delete($user->mahasiswa->foto_profil);
            }
        }

        $user->delete();

        return back()->with('success', 'Data mahasiswa beserta fotonya berhasil dihapus permanen!');
    }
}
