<?php

namespace App\Livewire\Statistik;

use Livewire\Component;
use App\Models\User;
use App\Models\Prestasi;
use App\Models\JenisPrestasi;
use App\Models\TingkatPrestasi;
use App\Models\KategoriPrestasi;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class StatistikAnalysis extends Component
{
    public function render()
    {
        // 1. Inisialisasi Default
        $pieLabels = [];
        $pieData = [];
        $barCategories = [];
        $barData = [];
        $areaCategories = [];
        $areaData = [];

        // Nilai Default Card
        $total_akun = 0;
        $akun_baru = 0;
        $total_prestasi = 0;
        $perlu_validasi = 0;
        $input_data = 0;
        $validasi_data = 0;
        $disetujui = 0;
        $ditolak = 0;

        // Tren Persentase
        $tren_input = 0;
        $tren_validasi = 0;
        $tren_disetujui = 0;
        $tren_ditolak = 0;

        // 2. AMBIL DATA ASLI DARI MASTER DATA
        if (Schema::hasTable('users')) {
            $total_akun = User::count();
            $akun_baru  = User::where('created_at', '>=', now()->subDay())->count();
        }

        if (Schema::hasTable('prestasi')) {
            // A. Dinamis: Distribusi Tingkat (Pie Chart)
            $masterTingkat = TingkatPrestasi::all();
            foreach ($masterTingkat as $t) {
                $pieLabels[] = $t->nama_tingkat;
                // Tambahkan filter approved agar sama dengan landing page
                $pieData[] = Prestasi::where('status', 'approved')->where('tingkat_id', $t->id)->count();
            }

            // B. Dinamis: Perbandingan Jenis (Bar Chart)
            $masterJenis = JenisPrestasi::all();
            foreach ($masterJenis as $j) {
                $barCategories[] = $j->nama_jenis;
                // Tambahkan filter approved
                $barData[] = Prestasi::where('status', 'approved')->where('jenis_id', $j->id)->count();
            }

            // C. Tren 7 Bulan Terakhir (Area Chart)
            for ($i = 6; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $areaCategories[] = $month->translatedFormat('M');
                $areaData[] = Prestasi::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count();
            }

            // --- PERHITUNGAN DATA CARD & TREN PERSENTASE --- //

            // Tentukan Waktu Bulan Ini & Bulan Lalu
            $bulanIni = now()->month;
            $tahunIni = now()->year;
            $bulanLalu = now()->subMonth()->month;
            $tahunLalu = now()->subMonth()->year;

            // D. Data Rekap Keseluruhan (Untuk Angka Besar di Card)
            $total_prestasi = Prestasi::count();
            $perlu_validasi = Prestasi::where('status', 'pending')->count();
            $input_data     = Prestasi::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
            $validasi_data  = Prestasi::where('status', '!=', 'pending')->count();
            $disetujui      = Prestasi::where('status', 'approved')->count();
            $ditolak        = Prestasi::where('status', 'rejected')->count();

            // E. Menghitung Tren Persentase (Bulan Ini vs Bulan Lalu)

            // 1. Ambil data spesifik bulan lalu untuk perbandingan
            $input_lalu     = Prestasi::whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
            $validasi_lalu  = Prestasi::where('status', '!=', 'pending')->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
            $disetujui_lalu = Prestasi::where('status', 'approved')->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
            $ditolak_lalu   = Prestasi::where('status', 'rejected')->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();

            // 2. Data spesifik bulan ini untuk perbandingan tren (Selain $input_data yang sudah ada di atas)
            $validasi_ini  = Prestasi::where('status', '!=', 'pending')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
            $disetujui_ini = Prestasi::where('status', 'approved')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
            $ditolak_ini   = Prestasi::where('status', 'rejected')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();

            // 3. Rumus Rahasia Menghitung Persentase Naik/Turun
            $hitungTren = function ($sekarang, $lalu) {
                if ($lalu == 0) {
                    return $sekarang > 0 ? 100 : 0; // Jika bulan lalu kosong dan sekarang ada, anggap naik 100%
                }
                return round((($sekarang - $lalu) / $lalu) * 100);
            };

            // 4. Masukkan hasil ke variabel tren yang dikirim ke Blade
            $tren_input     = $hitungTren($input_data, $input_lalu);
            $tren_validasi  = $hitungTren($validasi_ini, $validasi_lalu);
            $tren_disetujui = $hitungTren($disetujui_ini, $disetujui_lalu);
            $tren_ditolak   = $hitungTren($ditolak_ini, $ditolak_lalu);
        }

        // Kirim ke JavaScript (Highcharts) untuk di-render ulang
        $this->dispatch('updateAllCharts', [
            'pie'  => [
                'labels' => $pieLabels,
                'data'   => $pieData
            ],
            'bar'  => [
                'categories' => $barCategories,
                'data' => $barData
            ],
            'area' => [
                'categories' => $areaCategories,
                'data' => $areaData
            ]
        ]);

        return view('livewire.statistik.statistik-analysis', [
            'total_akun'     => number_format($total_akun),
            'akun_baru'      => number_format($akun_baru),
            'total_prestasi' => number_format($total_prestasi),
            'perlu_validasi' => number_format($perlu_validasi),
            'input_data'     => number_format($input_data),
            'validasi_data'  => number_format($validasi_data),
            'disetujui'      => number_format($disetujui),
            'ditolak'        => number_format($ditolak),
            'tren_input'     => $tren_input,
            'tren_validasi'  => $tren_validasi,
            'tren_disetujui' => $tren_disetujui,
            'tren_ditolak'   => $tren_ditolak,
        ]);
    }
}
