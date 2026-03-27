<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\FormPrestasi;
use App\Models\FieldFormPrestasi;
use App\Models\Konten;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    // =============================================================
    // BAGIAN 1: SISI MAHASISWA (Penyederhanaan dari Kode Lama)
    // =============================================================

    public function indexPrestasiMahasiswa(Request $request)
    {
        $userId = Auth::id();
        $query = Prestasi::with(['formPrestasi'])->where('user_id', $userId);

        if ($request->filled('search')) {
            $query->where('cerita_kegiatan', 'like', '%' . $request->search . '%');
        }

        $prestasi = $query->latest()->paginate(10);
        return view('mahasiswa.prestasi', compact('prestasi'));
    }

    public function createMahasiswa()
    {
        $categories = FormPrestasi::where('is_active', true)->get();
        return view('mahasiswa.prestasi-create', compact('categories'));
    }

    public function storeMahasiswa(Request $request)
    {
        // Validasi dasar (Data dinamis divalidasi manual atau via JSON)
        $request->validate([
            'form_prestasi_id' => 'required|exists:form_prestasis,id',
            'file_sertifikat'  => 'required|file|mimes:pdf,jpg,png|max:2048',
            'foto_kegiatan'    => 'nullable|image|max:2048',
        ]);

        // 1. Upload File
        $pathSertifikat = $request->file('file_sertifikat')->store('sertifikats', 'public');
        $pathFoto = $request->hasFile('foto_kegiatan') ? $request->file('foto_kegiatan')->store('kegiatan', 'public') : null;

        // 2. Tangkap semua inputan dinamis (Kecuali field sistem)
        $dataIsian = $request->except(['_token', 'form_prestasi_id', 'file_sertifikat', 'foto_kegiatan', 'cerita_kegiatan']);

        // 3. Simpan
        Prestasi::create([
            'user_id'          => Auth::id(),
            'form_prestasi_id' => $request->form_prestasi_id,
            'data_isian'       => $dataIsian, // Masuk otomatis jadi JSON
            'file_sertifikat'  => $pathSertifikat,
            'foto_kegiatan'    => $pathFoto,
            'cerita_kegiatan'  => $request->cerita_kegiatan,
            'status'           => 'Pending',
        ]);

        return redirect()->route('mahasiswa.prestasi')->with('success', 'Prestasi berhasil dilaporkan! Menunggu validasi.');
    }

    // =============================================================
    // BAGIAN 2: VALIDASI (JURUSAN & FAKULTAS)
    // =============================================================

    public function updateStatusPrestasi(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $role = Auth::user()->role->kode_role;

        // Logika Status Berjenjang
        $statusBaru = $request->status; // Disetujui Jurusan / Disetujui Fakultas / Ditolak

        $prestasi->update([
            'status' => $statusBaru,
            'catatan_penolakan' => $statusBaru == 'Ditolak' ? $request->alasan_ditolak : null
        ]);

        return back()->with('success', 'Status prestasi berhasil diperbarui!');
    }

    // =============================================================
    // BAGIAN 3: PUBLIKASI KONTEN (FITUR SAKTI DARI KODE LAMAMU)
    // =============================================================

    public function publishPrestasi($id)
    {
        $prestasi = Prestasi::with(['user', 'formPrestasi'])->findOrFail($id);

        // Helper untuk rapihin tulisan (Title Case)
        function formatCapital($text)
        {
            $text = preg_replace('/\s+/', ' ', trim($text));
            return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
        }

        // Ambil Nama Prestasi dari JSON data_isian (Asumsi ada field 'nama_event')
        $namaEvent = $prestasi->data_isian['nama_event'] ?? 'Kegiatan Mahasiswa';
        $namaMhs   = formatCapital($prestasi->user->name);

        // Buat Isi Berita Otomatis
        $content = "Selamat kepada {$namaMhs} yang telah berhasil meraih prestasi dalam ajang " . formatCapital($namaEvent) . ". " . $prestasi->cerita_kegiatan;

        // Gunakan foto kegiatan sebagai cover berita, jika tidak ada pakai placeholder
        $thumbnailPath = $prestasi->foto_kegiatan ?? 'defaults/news-placeholder.jpg';

        // Simpan ke Tabel Konten (Mading)
        Konten::updateOrCreate(
            ['judul' => "Prestasi: " . formatCapital($namaEvent)],
            [
                'slug'         => Str::slug($namaEvent) . '-' . time(),
                'isi_konten'   => $content,
                'gambar_cover' => $thumbnailPath,
                'kategori'     => 'Prestasi',
                'is_aktif'     => true,
                'created_by'   => Auth::id(),
            ]
        );

        $prestasi->update(['is_published' => true]);

        return back()->with('success', 'Prestasi resmi dipublikasikan ke halaman depan!');
    }

    public function destroyPrestasi($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        // Hapus File Fisik
        if ($prestasi->file_sertifikat) Storage::disk('public')->delete($prestasi->file_sertifikat);
        if ($prestasi->foto_kegiatan) Storage::disk('public')->delete($prestasi->foto_kegiatan);

        $prestasi->delete();
        return back()->with('success', 'Data prestasi telah dihapus.');
    }
}
