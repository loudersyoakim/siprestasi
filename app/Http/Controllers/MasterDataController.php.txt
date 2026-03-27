<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\JenisPrestasi;
use App\Models\KategoriPrestasi;
use App\Models\TingkatPrestasi;
use App\Models\Prestasi;
use App\Models\TahunAkademik;
use App\Models\TemplateSurat;
use App\Imports\UsersImport;
use App\Exports\TemplateAkunExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class MasterDataController extends Controller
{
    /**
     * Halaman Utama Master Data (Dashboard Master Data)
     */
    public function masterData()
    {
        return view('admin.master-data.index');
    }

    /**
     * Manajemen Fakultas
     */
    public function masterDataFakultas()
    {
        $fakultas = Fakultas::withCount('jurusan')->latest()->paginate(10);
        return view('admin.master-data-fakultas', compact('fakultas'));
    }

    // Simpan Fakultas Baru
    public function storeFakultas(Request $request)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255|unique:fakultas,nama_fakultas',
        ]);

        Fakultas::create([
            'nama_fakultas' => strtoupper($request->nama_fakultas) // Standarisasi huruf kapital
        ]);

        return redirect()->back()->with('success', 'Fakultas baru berhasil ditambahkan!');
    }

    // Update Fakultas
    public function updateFakultas(Request $request, $id)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255|unique:fakultas,nama_fakultas,' . $id,
        ]);

        $fakultas = Fakultas::findOrFail($id);
        $fakultas->update([
            'nama_fakultas' => strtoupper($request->nama_fakultas)
        ]);

        return redirect()->back()->with('success', 'Data fakultas berhasil diperbarui!');
    }

    // Hapus Fakultas
    public function destroyFakultas($id)
    {
        $fakultas = Fakultas::findOrFail($id);

        // Cek apakah ada jurusan di bawahnya (Proteksi data)
        if ($fakultas->jurusan()->count() > 0) {
            return redirect()->back()->with('error', 'Fakultas tidak bisa dihapus karena masih memiliki data jurusan!');
        }

        $fakultas->delete();
        return redirect()->back()->with('success', 'Fakultas berhasil dihapus!');
    }

    /**
     * Manajemen Jurusan
     */
    public function masterDataJurusan()
    {
        $fakultas = Fakultas::all();
        $jurusan = Jurusan::with('fakultas')->withCount('prodi')->latest()->paginate(10);
        return view('admin.master-data-jurusan', compact('jurusan', 'fakultas'));
    }

    // Simpan Jurusan
    public function storeJurusan(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan',
        ]);

        Jurusan::create([
            'fakultas_id' => $request->fakultas_id,
            'nama_jurusan' => strtoupper($request->nama_jurusan)
        ]);

        return redirect()->back()->with('success', 'Jurusan berhasil ditambahkan!');
    }

    // Update Jurusan
    public function updateJurusan(Request $request, $id)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan,' . $id,
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update([
            'fakultas_id' => $request->fakultas_id,
            'nama_jurusan' => strtoupper($request->nama_jurusan)
        ]);

        return redirect()->back()->with('success', 'Jurusan berhasil diperbarui!');
    }

    // Hapus Jurusan
    public function destroyJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        if ($jurusan->prodi()->count() > 0) {
            return redirect()->back()->with('error', 'Jurusan tidak bisa dihapus karena masih memiliki data Program Studi!');
        }

        $jurusan->delete();
        return redirect()->back()->with('success', 'Jurusan berhasil dihapus!');
    }

    /**
     * Manajemen Program Studi
     */
    public function masterDataProdi()
    {
        $fakultas = Fakultas::all();
        $jurusan = Jurusan::all();
        $prodi = Prodi::with('jurusan.fakultas')->latest()->paginate(10);
        return view('admin.master-data-prodi', compact('prodi', 'jurusan', 'fakultas'));
    }

    /**
     * Simpan Prodi Baru
     */
    public function storeProdi(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id',
            'nama_prodi' => 'required|string|max:255|unique:prodi,nama_prodi',
        ]);

        Prodi::create([
            'jurusan_id' => $request->jurusan_id,
            'nama_prodi' => strtoupper($request->nama_prodi)
        ]);

        return redirect()->back()->with('success', 'Program Studi berhasil ditambahkan!');
    }

    /**
     * Update Data Prodi
     */
    public function updateProdi(Request $request, $id)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id',
            'nama_prodi' => 'required|string|max:255|unique:prodi,nama_prodi,' . $id,
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'jurusan_id' => $request->jurusan_id,
            'nama_prodi' => strtoupper($request->nama_prodi)
        ]);

        return redirect()->back()->with('success', 'Program Studi berhasil diperbarui!');
    }

    /**
     * Hapus Prodi
     */
    public function destroyProdi($id)
    {
        $prodi = Prodi::findOrFail($id);

        // if ($prodi->user()->count() > 0) {
        //     return redirect()->back()->with('error', 'Prodi tidak bisa dihapus karena masih memiliki data mahasiswa!');
        // }

        $prodi->delete();
        return redirect()->back()->with('success', 'Program Studi berhasil dihapus!');
    }

    /**
     * Pengaturan Atribut Prestasi
     */
    public function masterDataAtributPrestasi()
    {
        $jenis = JenisPrestasi::all();
        $kategori = KategoriPrestasi::all();
        $tingkat = TingkatPrestasi::all();

        return view('admin.master-data-atribut-prestasi', compact('jenis', 'kategori', 'tingkat'));
    }

    /* --- JENIS PRESTASI --- */
    public function storeJenis(Request $request)
    {
        $request->validate(['nama_jenis' => 'required|string|unique:jenis_prestasi,nama_jenis']);
        JenisPrestasi::create(['nama_jenis' => strtoupper($request->nama_jenis)]);
        return redirect()->back()->with('success', 'Jenis prestasi berhasil ditambahkan!');
    }

    public function updateJenis(Request $request, $id)
    {
        $request->validate(['nama_jenis' => 'required|string|unique:jenis_prestasi,nama_jenis,' . $id]);
        JenisPrestasi::findOrFail($id)->update(['nama_jenis' => strtoupper($request->nama_jenis)]);
        return redirect()->back()->with('success', 'Jenis prestasi diperbarui!');
    }

    public function destroyJenis($id)
    {
        JenisPrestasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jenis prestasi dihapus!');
    }

    /* --- KATEGORI PRESTASI --- */
    public function storeKategori(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|unique:kategori_prestasi,nama_kategori']);
        KategoriPrestasi::create(['nama_kategori' => strtoupper($request->nama_kategori)]);
        return redirect()->back()->with('success', 'Kategori prestasi berhasil ditambahkan!');
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|string|unique:kategori_prestasi,nama_kategori,' . $id]);
        KategoriPrestasi::findOrFail($id)->update(['nama_kategori' => strtoupper($request->nama_kategori)]);
        return redirect()->back()->with('success', 'Kategori prestasi diperbarui!');
    }

    public function destroyKategori($id)
    {
        KategoriPrestasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kategori prestasi dihapus!');
    }

    /* --- TINGKAT PRESTASI --- */
    public function storeTingkat(Request $request)
    {
        $request->validate(['nama_tingkat' => 'required|string|unique:tingkat_prestasi,nama_tingkat']);
        TingkatPrestasi::create(['nama_tingkat' => strtoupper($request->nama_tingkat)]);
        return redirect()->back()->with('success', 'Tingkat prestasi berhasil ditambahkan!');
    }

    public function updateTingkat(Request $request, $id)
    {
        $request->validate(['nama_tingkat' => 'required|string|unique:tingkat_prestasi,nama_tingkat,' . $id]);
        TingkatPrestasi::findOrFail($id)->update(['nama_tingkat' => strtoupper($request->nama_tingkat)]);
        return redirect()->back()->with('success', 'Tingkat prestasi diperbarui!');
    }

    public function destroyTingkat($id)
    {
        TingkatPrestasi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Tingkat prestasi dihapus!');
    }

    /**
     * Pengaturan Tahun Akademik
     */
    public function masterDataSTA()
    {
        $tahun = TahunAkademik::first(); // Ambil tahun yang aktif
        $templates = TemplateSurat::all();
        return view('admin.master-data-sta', compact('tahun', 'templates'));
    }

    public function updateTahunAkademik(Request $request, $id)
    {
        $request->validate(['tahun' => 'required|string']);
        $tahun = TahunAkademik::findOrFail($id);
        $tahun->update(['tahun' => $request->tahun]);

        return redirect()->back()->with('success', 'Tahun Akademik berhasil diperbarui!');
    }

    public function updateTemplateSurat(Request $request, $id)
    {
        $template = TemplateSurat::findOrFail($id);
        // Ubah nama template jadi kecil dan ganti spasi dengan underscore (misal: "LAPORAN" jadi "laporan")
        $namaClean = strtolower(str_replace(' ', '_', $template->nama_template));

        // 1. Validasi Dinamis
        if (strtoupper($template->nama_template) == 'REKAP') {
            $request->validate(['file' => 'required|mimes:xlsx,xls|max:5120']);
        } else {
            $request->validate(['file' => 'required|mimes:doc,docx,pdf|max:5120']);
        }

        if ($request->hasFile('file')) {
            // 2. Hapus file fisik lama
            if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
                Storage::disk('public')->delete($template->file_path);
            }

            // 3. LOGIKA RENAME OTOMATIS
            $extension = $request->file('file')->getClientOriginalExtension();
            $newFileName = "template_" . $namaClean . "." . $extension; // Hasil: template_laporan.docx

            // 4. Simpan dengan nama baru ke folder templates
            $path = $request->file('file')->storeAs('templates', $newFileName, 'public');

            // 5. Update Database
            $template->update(['file_path' => $path]);

            return redirect()->back()->with('success', "Template $template->nama_template berhasil diupdate dengan nama: $newFileName");
        }
    }
}
