<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function indexProfil()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::firstOrCreate(['user_id' => $user->id]);
        $isUpdated = false;

        // Auto-fill dari NIM
        $nim = $user->nim_nip;
        if (!empty($nim) && strlen($nim) >= 7) {
            $kodeFakultas = substr($nim, 0, 1);
            $tahunAngkatan = '20' . substr($nim, 1, 2);
            $kodeProdi = substr($nim, 5, 2);

            if (empty($mahasiswa->angkatan)) {
                $mahasiswa->angkatan = $tahunAngkatan;
                $isUpdated = true;
            }

            if (empty($mahasiswa->fakultas_id)) {
                $fakultas = Fakultas::where('kode_fakultas', $kodeFakultas)->first();
                if ($fakultas) {
                    $mahasiswa->fakultas_id = $fakultas->id;
                    $isUpdated = true;

                    if (empty($mahasiswa->prodi_id)) {
                        $prodi = Prodi::where('kode_prodi', $kodeProdi)
                            ->whereHas('jurusan', function ($q) use ($fakultas) {
                                $q->where('fakultas_id', $fakultas->id);
                            })->first();

                        if ($prodi) {
                            $mahasiswa->jurusan_id = $prodi->jurusan_id;
                            $mahasiswa->prodi_id = $prodi->id;
                            $isUpdated = true;
                        }
                    }
                }
            }
        }

        if ($isUpdated) {
            $mahasiswa->save();
        }

        // Hitung Persentase Profil
        $persentaseProfil = 0;
        $totalField = 8;
        $fieldTerisi = 3;

        if (!empty($mahasiswa->foto_profil)) $fieldTerisi++;
        if (!empty($mahasiswa->jenis_kelamin)) $fieldTerisi++;
        if (!empty($mahasiswa->angkatan)) $fieldTerisi++;
        if (!empty($mahasiswa->fakultas_id)) $fieldTerisi++;
        if (!empty($mahasiswa->prodi_id)) $fieldTerisi++;

        $persentaseProfil = min(round(($fieldTerisi / $totalField) * 100), 100);

        $fakultas = Fakultas::all();
        $jurusans = Jurusan::all();
        $prodis = Prodi::all();

        return view('mahasiswa.profil', compact('user', 'mahasiswa', 'fakultas', 'jurusans', 'prodis', 'persentaseProfil'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'angkatan'      => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
            'fakultas_id'   => 'required|exists:fakultas,id',
            'jurusan_id'    => 'required|exists:jurusan,id',
            'prodi_id'      => 'required|exists:prodi,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // ========================================================
        // LOGIKA FOTO HASIL CROP (BASE64) -> TANPA INTERVENTION IMAGE!
        // ========================================================
        if ($request->filled('foto_base64')) {
            // 1. Kembalikan spasi menjadi tanda '+'
            $base64Image = str_replace(' ', '+', $request->foto_base64);

            // 2. Pisahkan prefix "data:image/jpeg;base64,"
            $imageParts = explode(";base64,", $base64Image);

            if (count($imageParts) == 2) {
                // 3. Decode teks murni pakai fungsi bawaan PHP
                $imageDecode = base64_decode($imageParts[1]);

                // Buat folder jika belum ada
                if (!Storage::disk('public')->exists('profil')) {
                    Storage::disk('public')->makeDirectory('profil');
                }

                // Hapus foto lama jika ada
                if ($mahasiswa->foto_profil && Storage::disk('public')->exists($mahasiswa->foto_profil)) {
                    Storage::disk('public')->delete($mahasiswa->foto_profil);
                }

                // Siapkan nama file unik
                $nim = $user->nim_nip ?? 'unknown';
                $fileName = 'profil/' . $nim . '_' . time() . '.jpg';

                // 4. SIMPAN LANGSUNG PAKAI LARAVEL STORAGE (Sangat Aman & Cepat)
                Storage::disk('public')->put($fileName, $imageDecode);

                $mahasiswa->foto_profil = $fileName;
            }
        }

        $mahasiswa->jenis_kelamin = $request->jenis_kelamin;
        $mahasiswa->angkatan      = $request->angkatan;
        $mahasiswa->fakultas_id   = $request->fakultas_id;
        $mahasiswa->jurusan_id    = $request->jurusan_id;
        $mahasiswa->prodi_id      = $request->prodi_id;
        $mahasiswa->save();

        return back()->with('success', 'Data profil berhasil diperbarui.');
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
