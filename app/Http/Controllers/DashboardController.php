<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\User;
use App\Models\PermohonanSurat;
use App\Models\Konten; // Tambahkan ini untuk memanggil berita di Mading Mahasiswa
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ========================================================
    // 1. Dashboard Super Admin & Admin (Melihat Semua Data)
    // ========================================================
    public function superAdminDashboard()
    {
        $totalPrestasi = Prestasi::count();
        $prestasiPending = Prestasi::where('status', 'Pending')->count();

        $totalMahasiswa = User::whereHas('role', function ($q) {
            $q->where('kode_role', 'MHS');
        })->count();

        $suratPending = PermohonanSurat::whereIn('status', ['Pending', 'Diproses Admin'])->count();

        return view('dashboard.super_admin', compact(
            'totalPrestasi',
            'prestasiPending',
            'totalMahasiswa',
            'suratPending'
        ));
    }

    // ========================================================
    // 2. Dashboard Fakultas (Melihat Data Fakultasnya Saja)
    // ========================================================
    public function wdDashboard()
    {
        $user = Auth::user();
        $fakultasId = $user->fakultas_id;

        // Ambil prestasi yang mahasiswanya ada di fakultas ini
        $prestasiQuery = Prestasi::whereHas('user', function ($q) use ($fakultasId) {
            $q->where('fakultas_id', $fakultasId);
        });

        $totalPrestasiFakultas = $prestasiQuery->count();

        // Fakultas memvalidasi yang sudah disetujui jurusan
        $menungguValidasiFakultas = (clone $prestasiQuery)->where('status', 'Disetujui Jurusan')->count();

        return view('dashboard.fakultas', compact('totalPrestasiFakultas', 'menungguValidasiFakultas'));
    }

    // ========================================================
    // 3. Dashboard Jurusan (Melihat Data Jurusannya Saja)
    // ========================================================
    public function kajurDashboard()
    {
        $user = Auth::user();
        $jurusanId = $user->jurusan_id;

        $prestasiQuery = Prestasi::whereHas('user', function ($q) use ($jurusanId) {
            $q->where('jurusan_id', $jurusanId);
        });

        $totalPrestasiJurusan = $prestasiQuery->count();

        // Jurusan memvalidasi yang masih berstatus Pending
        $menungguValidasiJurusan = (clone $prestasiQuery)->where('status', 'Pending')->count();

        return view('dashboard.jurusan', compact('totalPrestasiJurusan', 'menungguValidasiJurusan'));
    }

    // ========================================================
    // 4. Dashboard Mahasiswa (Self-Service)
    // ========================================================
    public function mahasiswaDashboard()
    {
        $userId = Auth::id();

        // Hitung status prestasi untuk kartu statistik
        $approvedCount = Prestasi::where('user_id', $userId)
            ->whereIn('status', ['Disetujui Jurusan', 'Disetujui Fakultas'])
            ->count();

        $pendingCount = Prestasi::where('user_id', $userId)
            ->where('status', 'Pending')
            ->count();

        $rejectedCount = Prestasi::where('user_id', $userId)
            ->where('status', 'Ditolak')
            ->count();

        // Ambil 3 riwayat pengajuan terbaru untuk list di tengah
        $latestPrestasi = Prestasi::with('formPrestasi')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        // Ambil 4 berita/pengumuman terbaru untuk mading di sebelah kanan
        $pengumuman = Konten::where('is_aktif', true)
            ->latest()
            ->take(4)
            ->get();

        return view('dashboard.mahasiswa', compact(
            'approvedCount',
            'pendingCount',
            'rejectedCount',
            'latestPrestasi',
            'pengumuman'
        ));
    }
}
