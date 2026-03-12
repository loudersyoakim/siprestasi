<?php

namespace App\Livewire\Statistik;

use Livewire\Component;
use App\Models\Prestasi;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class MahasiswaStatistikAnalysis extends Component
{
    public function render()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // 1. HITUNG PERSENTASE PROFIL
        $persentaseProfil = 0;
        $totalField = 8;
        $fieldTerisi = 2;

        if ($mahasiswa) {
            if (!empty($mahasiswa->nim)) $fieldTerisi++;
            if (!empty($mahasiswa->jenis_kelamin)) $fieldTerisi++;
            if (!empty($mahasiswa->angkatan)) $fieldTerisi++;
            if (!empty($mahasiswa->fakultas_id)) $fieldTerisi++;
            if (!empty($mahasiswa->jurusan_id)) $fieldTerisi++;
            if (!empty($mahasiswa->prodi_id)) $fieldTerisi++;
        }
        $persentaseProfil = round(($fieldTerisi / $totalField) * 100);

        // 2. AMBIL DATA PRESTASI (HANYA ANGKA)
        $total_prestasi = 0;
        $menunggu_validasi = 0;
        $disetujui = 0;
        $ditolak = 0;

        $userId = Auth::id();
        $prestasiSaya = Prestasi::whereHas('mahasiswa', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })->get();

        $total_prestasi = $prestasiSaya->count();
        $menunggu_validasi = $prestasiSaya->where('status', 'pending')->count();
        $disetujui = $prestasiSaya->where('status', 'approved')->count();
        $ditolak = $prestasiSaya->where('status', 'rejected')->count();

        // LANGSUNG RETURN TANPA DISPATCH CHART
        return view('livewire.statistik.mahasiswa-statistik-analysis', [
            'persentaseProfil'  => $persentaseProfil,
            'total_prestasi'    => $total_prestasi,
            'menunggu_validasi' => $menunggu_validasi,
            'disetujui'         => $disetujui,
            'ditolak'           => $ditolak,
        ]);
    }
}
