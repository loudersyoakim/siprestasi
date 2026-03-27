<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function indexProfil()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isUpdated = false;

        // ========================================================
        // 1. LOGIKA AUTO-FILL NIM (Sangat Cerdas, Kita Pertahankan!)
        // ========================================================
        $nim = $user->nim_nip;
        if (!empty($nim) && strlen($nim) >= 7) {
            $kodeFakultas = substr($nim, 0, 1);
            $tahunAngkatan = '20' . substr($nim, 1, 2);
            $kodeProdi = substr($nim, 5, 2);

            // Isi Angkatan jika kosong
            if (empty($user->angkatan)) {
                $user->angkatan = $tahunAngkatan;
                $isUpdated = true;
            }

            // Isi Fakultas, Jurusan, Prodi jika kosong
            if (empty($user->fakultas_id)) {
                $fakultas = Fakultas::where('kode_fakultas', $kodeFakultas)->first();
                if ($fakultas) {
                    $user->fakultas_id = $fakultas->id;
                    $isUpdated = true;

                    if (empty($user->prodi_id)) {
                        $prodi = Prodi::where('kode_prodi', $kodeProdi)
                            ->whereHas('jurusan', function ($q) use ($fakultas) {
                                $q->where('fakultas_id', $fakultas->id);
                            })->first();

                        if ($prodi) {
                            $user->jurusan_id = $prodi->jurusan_id;
                            $user->prodi_id = $prodi->id;
                            $isUpdated = true;
                        }
                    }
                }
            }
        }

        if ($isUpdated) {
            $user->save();
        }

        // ========================================================
        // 2. HITUNG PERSENTASE PROFIL (Sekarang di tabel Users)
        // ========================================================
        $fieldTerisi = 0;
        $fields = ['name', 'email', 'nim_nip', 'foto_profil', 'jenis_kelamin', 'angkatan', 'fakultas_id', 'prodi_id'];

        foreach ($fields as $f) {
            if (!empty($user->$f)) $fieldTerisi++;
        }
        $persentaseProfil = round(($fieldTerisi / count($fields)) * 100);

        // Data untuk Dropdown
        $fakultas = Fakultas::all();
        $jurusans = Jurusan::where('fakultas_id', $user->fakultas_id)->get();
        $prodis = Prodi::where('jurusan_id', $user->jurusan_id)->get();

        return view('mahasiswa.profil', compact('user', 'fakultas', 'jurusans', 'prodis', 'persentaseProfil'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'angkatan'      => 'required|digits:4',
            'fakultas_id'   => 'required|exists:fakultas,id',
            'jurusan_id'    => 'required|exists:jurusan,id',
            'prodi_id'      => 'required|exists:prodi,id',
        ]);

        // ========================================================
        // 3. LOGIKA FOTO HASIL CROP (BASE64) - Tetap Dipertahankan
        // ========================================================
        if ($request->filled('foto_base64')) {
            $base64Image = str_replace(' ', '+', $request->foto_base64);
            $imageParts = explode(";base64,", $base64Image);

            if (count($imageParts) == 2) {
                $imageDecode = base64_decode($imageParts[1]);

                // Hapus foto lama jika ada
                if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                    Storage::disk('public')->delete($user->foto_profil);
                }

                $fileName = 'profil/' . $user->nim_nip . '_' . time() . '.jpg';
                Storage::disk('public')->put($fileName, $imageDecode);
                $user->foto_profil = $fileName;
            }
        }

        // Simpan semua ke tabel USERS
        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'angkatan'      => $request->angkatan,
            'fakultas_id'   => $request->fakultas_id,
            'jurusan_id'    => $request->jurusan_id,
            'prodi_id'      => $request->prodi_id,
        ]);

        return back()->with('success', 'Data profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::find(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diubah!');
    }
}
