<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use App\Models\Prestasi;
use App\Models\LandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ManajemenKontenController extends Controller
{
    // =======================================================
    // 1. MANAJEMEN ARTIKEL (BERITA & GALERI)
    // =======================================================

    public function indexManajemenKonten(Request $request)
    {
        $tab = $request->get('tab', 'berita');
        $search = $request->get('search');
        $status = $request->get('status');
        $kategori = $request->get('kategori'); // Tambahan filter kategori

        // --- TAB BERITA (Mengambil data dari tabel Konten) ---
        $queryBerita = Konten::with('penulis')->latest();
        if ($tab === 'berita') {
            if ($search) {
                $queryBerita->where('judul', 'like', "%{$search}%");
            }
            if ($status !== null && $status !== '') {
                $queryBerita->where('is_aktif', $status === 'live' ? 1 : 0);
            }
            if ($kategori) {
                $queryBerita->where('kategori', $kategori);
            }
        }
        $kontens = $queryBerita->paginate(10, ['*'], 'page_konten')->withQueryString();

        // --- TAB PRESTASI (Mengambil data dari tabel Prestasi yang sudah Approved) ---
        $queryPrestasi = Prestasi::with(['user', 'formPrestasi', 'tingkatPrestasi', 'capaianPrestasi'])
            ->where('status', 'Approved')->latest();

        if ($tab === 'prestasi') {
            if ($search) {
                $queryPrestasi->where(function ($q) use ($search) {
                    $q->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
                });
            }
            if ($status !== null && $status !== '') {
                $queryPrestasi->where('is_published', $status === 'live' ? 1 : 0);
            }
        }
        $prestasiApproved = $queryPrestasi->paginate(10, ['*'], 'page_prestasi')->withQueryString();

        return view('manajemen_konten.index', compact('kontens', 'prestasiApproved', 'tab', 'search', 'status', 'kategori'));
    }

    public function createKonten()
    {
        return view('manajemen_konten.create');
    }

    public function storeKonten(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori'     => 'required',
            'isi_konten'   => 'required',
            'gambar_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = null;

        // UPLOAD GAMBAR STANDAR
        if ($request->hasFile('gambar_cover')) {
            $path = $request->file('gambar_cover')->store('konten_covers', 'public');
        }

        Konten::create([
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul . '-' . Str::random(5)),
            'kategori'     => $request->kategori,
            'isi_konten'   => $request->isi_konten,
            'gambar_cover' => $path,
            'is_aktif'     => $request->has('is_aktif'),
            'created_by'   => Auth::id()
        ]);

        return redirect()->route('konten.index')->with('success', 'Konten berhasil dipublikasikan!');
    }

    public function editKonten($id)
    {
        $konten = Konten::findOrFail($id);
        return view('manajemen_konten.edit', compact('konten'));
    }

    public function updateKonten(Request $request, $id)
    {
        $konten = Konten::findOrFail($id);

        $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori'     => 'required',
            'isi_konten'   => 'required',
            'gambar_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $konten->gambar_cover;

        // UPDATE GAMBAR STANDAR
        if ($request->hasFile('gambar_cover')) {
            // Hapus gambar lama jika ada
            if ($path) Storage::disk('public')->delete($path);

            // Simpan gambar baru
            $path = $request->file('gambar_cover')->store('konten_covers', 'public');
        }

        $konten->update([
            'judul'        => $request->judul,
            'kategori'     => $request->kategori,
            'isi_konten'   => $request->isi_konten,
            'gambar_cover' => $path,
            'is_aktif'     => $request->has('is_aktif')
        ]);

        return redirect()->route('konten.index')->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroyKonten($id)
    {
        $konten = Konten::findOrFail($id);

        // Jika yang dihapus adalah artikel kategori 'prestasi'
        if ($konten->kategori === 'prestasi') {
            // Cari semua prestasi yang sedang Live
            $prestasis = Prestasi::with('user')->where('is_published', true)->get();

            foreach ($prestasis as $p) {
                // Rangkai kembali judulnya untuk dicocokkan
                $judulStandar = $p->nama_kegiatan . " - " . $p->user->name;

                // Jika cocok dengan judul artikel yang mau dihapus, ubah jadi Idle (false)
                if ($judulStandar === $konten->judul) {
                    $p->update(['is_published' => false]);
                }
            }
        }

        // Hapus file gambar cover dari storage server jika ada
        if ($konten->gambar_cover) {
            Storage::disk('public')->delete($konten->gambar_cover);
        }

        // Hapus permanen dari database tabel konten
        $konten->delete();

        return back()->with('success', 'Konten artikel dihapus dan status di Antrean Prestasi otomatis menjadi Idle.');
    }

    // =======================================================
    // 2. PUBLIKASI PRESTASI MAHASISWA
    // =======================================================
    public function publishPrestasi($id)
    {
        try {
            DB::beginTransaction();
            $prestasi = Prestasi::with(['user', 'tingkatPrestasi', 'capaianPrestasi', 'formPrestasi'])->findOrFail($id);

            // 1. Update status is_published di tabel prestasi
            $prestasi->update(['is_published' => true]);

            // 2. Generate Isi Konten Otomatis dari data prestasi
            $isiKonten = "<strong>{$prestasi->user->name}</strong> kembali menorehkan prestasi gemilang ";
            $isiKonten .= "dengan meraih <strong>{$prestasi->capaianPrestasi->nama_capaian}</strong> ";
            $isiKonten .= "pada ajang <strong>{$prestasi->nama_kegiatan}</strong> ";
            $isiKonten .= "tingkat <strong>{$prestasi->tingkatPrestasi->nama_tingkat}</strong>. ";

            $isiKonten .= "<br><br>Diharapkan pencapaian ini dapat menjadi motivasi bagi seluruh mahasiswa ";
            $isiKonten .= "untuk terus berkarya, berinovasi, dan mengukir prestasi di berbagai bidang.";

            // 3. Ambil Thumbnail (Jika ada file gambar di data dinamis, ambil file pertama)
            $gambarPath = null;
            if ($prestasi->data_dinamis) {
                foreach ($prestasi->data_dinamis as $key => $val) {
                    if (is_string($val) && (Str::endsWith($val, '.jpg') || Str::endsWith($val, '.png') || Str::endsWith($val, '.jpeg'))) {
                        $gambarPath = $val;
                        break;
                    }
                }
            }

            // 4. Buat atau perbarui konten artikel terkait
            $judulStandar = "" . $prestasi->nama_kegiatan . " - " . $prestasi->user->name;

            Konten::updateOrCreate(
                ['judul' => $judulStandar],
                [
                    'slug'         => Str::slug("prestasi-" . $prestasi->nama_kegiatan . "-" . $prestasi->user->name . "-" . Str::random(3)),
                    'kategori'     => 'prestasi',
                    'isi_konten'   => $isiKonten,
                    'gambar_cover' => $gambarPath,
                    'is_aktif'     => true,
                    'created_by'   => Auth::id(),
                ]
            );

            DB::commit();
            return back()->with('success', 'Prestasi berhasil divalidasi dan diterbitkan sebagai berita!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mempublish: ' . $e->getMessage());
        }
    }

    public function takedownPrestasi($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        // 1. Matikan status publish di tabel prestasi
        $prestasi->update(['is_published' => false]);

        // 2. Cari judul standar yang SAMA PERSIS dengan saat dipublish
        $judulStandar = $prestasi->nama_kegiatan . " - " . $prestasi->user->name;

        // 3. Hapus permanen konten berita tersebut
        Konten::where('judul', $judulStandar)->delete();

        return back()->with('success', 'Konten prestasi telah diturunkan dan berita otomatis dihapus.');
    }

    // =======================================================
    // 3. KONFIGURASI BERANDA (LANDING PAGE)
    // =======================================================
    public function landingSettings()
    {
        $widgetSetting = LandingSetting::where('key', 'active_widgets')->first();
        $savedWidgets = $widgetSetting && $widgetSetting->value ? json_decode($widgetSetting->value, true) : [];

        return view('manajemen_konten.pengaturan_beranda', compact('savedWidgets'));
    }

    public function updateLandingSettings(Request $request)
    {
        try {
            $widgets = $request->input('landing_widgets', []);
            $widgets = array_values($widgets);

            LandingSetting::updateOrCreate(
                ['key' => 'active_widgets'],
                [
                    'label' => 'Konfigurasi Widget Beranda',
                    'value' => json_encode($widgets),
                    'is_active' => true,
                    'type' => 'json'
                ]
            );

            return back()->with('success', 'Konfigurasi widget beranda berhasil disimpan dan dipublikasikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
