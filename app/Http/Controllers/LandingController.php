<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konten;
use App\Models\LandingSetting;
use App\Models\User;
use App\Models\Prestasi;
use App\Models\FormPrestasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class LandingController extends Controller
{
    private function getPengaturan()
    {
        $pengaturanSistem = DB::table('pengaturan_sistem')->pluck('nilai', 'kunci')->toArray();
        $pengaturanLanding = [];
        if (Schema::hasTable('landing_settings')) {
            $landingSettingsDb = LandingSetting::all();
            foreach ($landingSettingsDb as $setting) {
                $pengaturanLanding[$setting->key] = $setting->value;
            }
        }
        return array_merge($pengaturanSistem, $pengaturanLanding);
    }

    public function index()
    {
        $pengaturan = $this->getPengaturan();

        $allWidgets = isset($pengaturan['active_widgets']) ? json_decode($pengaturan['active_widgets'], true) : [];
        $activeWidgets = array_values(array_filter($allWidgets, function ($w) {
            return isset($w['is_active']) && $w['is_active'] == '1';
        }));

        $widgetData = [];

        // AMBIL DATA ASLI (Eager Loading agar relasi tingkatPrestasi & capaianPrestasi terbaca)
        $allApproved = Prestasi::with(['user.fakultas', 'user.prodi', 'formPrestasi', 'tingkatPrestasi', 'capaianPrestasi'])
            ->where('status', 'Approved')
            ->get();

        foreach ($activeWidgets as $index => $widget) {
            $type = $widget['type'];

            if ($type === 'leaderboard') {
                $limit = (int) ($widget['limit'] ?? 5);
                $widgetData[$index] = User::with('prodi')
                    ->whereHas('prestasis', fn($q) => $q->where('status', 'Approved'))
                    ->withCount(['prestasis as total_poin' => fn($q) => $q->where('status', 'Approved')])
                    ->orderByDesc('total_poin')->take($limit)->get();
            } elseif ($type === 'chart_distribusi') {
                $source = $widget['data_source'] ?? 'fakultas';

                if ($source === 'fakultas') {
                    $grouped = $allApproved->groupBy(fn($p) => $p->user->fakultas->nama_fakultas ?? 'Lainnya');
                } elseif ($source === 'prodi') {
                    $grouped = $allApproved->groupBy(fn($p) => $p->user->prodi->nama_prodi ?? 'Lainnya');
                } elseif ($source === 'kategori') {
                    $grouped = $allApproved->groupBy(fn($p) => $p->formPrestasi->nama_form ?? 'Lainnya');
                } elseif ($source === 'tingkat') {
                    $grouped = $allApproved->groupBy(fn($p) => $p->tingkatPrestasi->nama_tingkat ?? 'Lainnya');
                } elseif ($source === 'capaian') {
                    $grouped = $allApproved->groupBy(fn($p) => $p->capaianPrestasi->nama_capaian ?? 'Lainnya');
                } else {
                    $grouped = collect([]);
                }

                $widgetData[$index] = [
                    'labels' => $grouped->keys()->toArray(),
                    'data' => $grouped->map(fn($items) => $items->count())->values()->toArray()
                ];
            } elseif ($type === 'chart_tren') {
                $labels = [];
                $data = [];

                for ($i = 5; $i >= 0; $i--) {
                    // PERBAIKAN: Tambahkan ->startOfMonth() sebelum subMonths
                    // Ini biar hitungannya mulai dari tanggal 1, jadi nggak bakal overflow
                    $date = Carbon::now()->startOfMonth()->subMonths($i);

                    $labels[] = $date->translatedFormat('M Y');
                    $data[] = $allApproved->filter(fn($p) => $p->updated_at->format('Y-m') === $date->format('Y-m'))->count();
                }
                $widgetData[$index] = ['labels' => $labels, 'data' => $data];
            } elseif ($type === 'counter') {
                // LOGIKA STATISTIK SESUAI PERMINTAAN ABANG
                $widgetData[$index] = [
                    'total_prestasi'  => $allApproved->count(),
                    'mhs_berprestasi' => $allApproved->unique('user_id')->count(),
                    'total_berita'    => Konten::where('is_aktif', true)->count(),
                    'internasional'   => $allApproved->filter(fn($p) => str_contains(strtolower($p->tingkatPrestasi->nama_tingkat ?? ''), 'internasional'))->count(),
                    'nasional'        => $allApproved->filter(fn($p) => str_contains(strtolower($p->tingkatPrestasi->nama_tingkat ?? ''), 'nasional') && !str_contains(strtolower($p->tingkatPrestasi->nama_tingkat ?? ''), 'internasional'))->count(),
                    'wilayah_kota'    => $allApproved->filter(function ($p) {
                        $tingkat = strtolower($p->tingkatPrestasi->nama_tingkat ?? '');
                        return str_contains($tingkat, 'wilayah') || str_contains($tingkat, 'kota') || str_contains($tingkat, 'provinsi') || str_contains($tingkat, 'daerah');
                    })->count(),
                ];
            }
        }

        $kontenAktif = Konten::where('is_aktif', true)->latest()->get();
        $headline = $kontenAktif->first();
        $listBerita = $kontenAktif->skip(1)->take(7);

        return view('landing', compact('pengaturan', 'headline', 'listBerita', 'activeWidgets', 'widgetData'));
    }
}
