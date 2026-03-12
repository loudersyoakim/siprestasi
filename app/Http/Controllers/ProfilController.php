<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function indexProfil()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // --- LOGIKA HITUNG PERSENTASE (TAMBAHKAN INI) ---
        $persentaseProfil = 0;
        $totalField = 8; // (Nama, Email, NIM, JK, Angkatan, Fakultas, Jurusan, Prodi)
        $fieldTerisi = 2; // Nama & Email dari User pasti ada

        if ($mahasiswa) {
            if (!empty($mahasiswa->nim)) $fieldTerisi++;
            if (!empty($mahasiswa->jenis_kelamin)) $fieldTerisi++;
            if (!empty($mahasiswa->angkatan)) $fieldTerisi++;
            if (!empty($mahasiswa->fakultas_id)) $fieldTerisi++;
            if (!empty($mahasiswa->jurusan_id)) $fieldTerisi++;
            if (!empty($mahasiswa->prodi_id)) $fieldTerisi++;
        }
        $persentaseProfil = round(($fieldTerisi / $totalField) * 100);
        // ------------------------------------------------

        $fakultas = Fakultas::all();
        $jurusans = Jurusan::all();
        $prodis = Prodi::all();

        return view('mahasiswa.profil', compact(
            'user',
            'mahasiswa',
            'fakultas',
            'jurusans',
            'prodis',
            'persentaseProfil'
        ));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'nim'   => [
                'required',
                'string',
                'max:20',
                'unique:users,nim_nip,' . $user->id,
                'unique:mahasiswa,nim,' . ($user->mahasiswa->id ?? 0),
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'angkatan'      => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
            'fakultas_id'   => 'required|exists:fakultas,id',
            'jurusan_id'    => 'required|exists:jurusan,id',
            'prodi_id'      => 'required|exists:prodi,id',
        ], [
            'email.unique' => 'Alamat email tersebut sudah digunakan oleh akun lain.',
            'nim.unique'   => 'Nomor Induk Mahasiswa (NIM) tersebut sudah terdaftar pada akun lain.',
        ]);

        // Sinkronisasi ke tabel USERS
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nim_nip = $request->nim;
        $user->save();

        // Sinkronisasi ke tabel MAHASISWA
        Mahasiswa::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nim'           => $request->nim,
                'jenis_kelamin' => $request->jenis_kelamin,
                'angkatan'      => $request->angkatan,
                'fakultas_id'   => $request->fakultas_id,
                'jurusan_id'    => $request->jurusan_id,
                'prodi_id'      => $request->prodi_id,
            ]
        );

        return back()->with('success', 'Data profil dan akun Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diubah!');
    }
}
