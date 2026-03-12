<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JenisPrestasi;
use App\Models\KategoriPrestasi;
use App\Models\TingkatPrestasi;
use App\Models\Prestasi;
use App\Models\TahunAkademik;
use App\Models\Konten;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class PrestasiController extends Controller
{
    public function indexPrestasi(Request $request)
    {
        $query = Prestasi::with(['mahasiswa', 'tingkat', 'kategori', 'jenis', 'tahunAkademik']);

        // 1. Pencarian Teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_prestasi', 'like', "%{$search}%")
                ->orWhereHas('mahasiswa', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        // 2. Filter Kepesertaan (Individu / Tim)
        if ($request->filled('kepesertaan')) {
            if ($request->kepesertaan == 'individu') {
                $query->has('mahasiswa', '=', 1);
            } elseif ($request->kepesertaan == 'tim') {
                $query->has('mahasiswa', '>', 1);
            }
        }

        // 3. Filter Master Data (Jenis, Kategori, Tingkat)
        if ($request->filled('jenis_id')) $query->where('jenis_id', $request->jenis_id);
        if ($request->filled('kategori_id')) $query->where('kategori_id', $request->kategori_id);
        if ($request->filled('tingkat_id')) $query->where('tingkat_id', $request->tingkat_id);

        // 4. Filter Status
        if ($request->filled('status')) $query->where('status', $request->status);

        // 5. Filter Publikasi
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published == '1' ? true : false);
        }

        // 6. Sorting Tanggal Sertifikat
        if ($request->filled('sort_tanggal')) {
            $query->orderBy('tanggal_peroleh', $request->sort_tanggal); // 'asc' atau 'desc'
        } else {
            $query->latest(); // Default
        }

        $prestasi = $query->paginate(10)->withQueryString();

        // Ambil data master untuk dikirim ke Dropdown Filter di View
        $masterTingkat = TingkatPrestasi::all();
        $masterKategori = KategoriPrestasi::all();
        $masterJenis = JenisPrestasi::all();
        $role = Auth::user()->role;

        return view($role . '.prestasi', compact('prestasi', 'masterTingkat', 'masterKategori', 'masterJenis'));
    }

    public function createPrestasi()
    {
        $mahasiswa = User::where('role', 'mahasiswa')->get();
        $tingkat = TingkatPrestasi::all();
        $kategori = KategoriPrestasi::all();
        $jenis = JenisPrestasi::all();
        $tahunAkademik = TahunAkademik::all();
        $role = Auth::user()->role;

        return view($role . '.prestasi-create', compact('tingkat', 'mahasiswa', 'kategori', 'jenis', 'tahunAkademik'));
    }

    public function storePrestasi(Request $request)
    {
        // 1. Validasi Input (Ubah bagian kategori_id dan jenis_id)
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'nama_prestasi' => 'required|string|max:255',
            'tingkat_id' => 'required|exists:tingkat_prestasi,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'sertifikat' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'tanggal_peroleh' => 'required|date',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required',
            'jenis_id' => 'required',
            'kategori_baru' => 'required_if:kategori_id,lainnya|nullable|string|max:255',
            'jenis_baru' => 'required_if:jenis_id,lainnya|nullable|string|max:255',
        ]);

        // 2. Logika "Lainnya" (Insert ke Master Data on-the-fly)
        $kategoriIdFinal = $request->kategori_id;
        if ($request->kategori_id === 'lainnya') {
            // Cek apakah sudah ada untuk menghindari duplikat
            $newKategori = KategoriPrestasi::firstOrCreate(
                ['nama_kategori' => strtoupper($request->kategori_baru)]
            );
            $kategoriIdFinal = $newKategori->id;
        }

        $jenisIdFinal = $request->jenis_id;
        if ($request->jenis_id === 'lainnya') {
            $newJenis = JenisPrestasi::firstOrCreate(
                ['nama_jenis' => strtoupper($request->jenis_baru)]
            );
            $jenisIdFinal = $newJenis->id;
        }

        // 3. Upload File Sertifikat
        $fileName = time() . '_' . $request->file('sertifikat')->getClientOriginalName();
        $path = $request->file('sertifikat')->storeAs('sertifikat_prestasi', $fileName, 'public');

        // 4. Simpan Data Inti (Gunakan variabel $kategoriIdFinal dan $jenisIdFinal)
        $prestasi = Prestasi::create([
            'nama_prestasi' => $request->nama_prestasi,
            'tingkat_id' => $request->tingkat_id,
            'kategori_id' => $kategoriIdFinal,
            'jenis_id' => $jenisIdFinal,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'sertifikat' => $path,
            'tanggal_peroleh' => $request->tanggal_peroleh,
            'deskripsi' => $request->deskripsi,
            'status' => in_array(Auth::user()->role, ['admin', 'wd', 'kajur']) ? 'approved' : 'pending',
            'is_published' => false,
        ]);

        // 5. Simpan Relasi Pivot Tim
        $prestasi->mahasiswa()->attach($request->user_ids);
        $role = Auth::user()->role;


        return redirect()->route($role . '.prestasi')->with('success', 'Prestasi berhasil disimpan dan data baru telah direkam!');
    }

    public function showPrestasi($id)
    {
        $prestasi = Prestasi::with(['mahasiswa', 'tingkat', 'kategori', 'jenis', 'tahunAkademik'])->findOrFail($id);
        $role = Auth::user()->role;

        return view($role . '.prestasi-show', compact('prestasi'));
    }
    public function editPrestasi($id)
    {
        $prestasi = Prestasi::with('mahasiswa')->findOrFail($id);

        // 1. Ambil data master untuk Dropdown
        $mahasiswa = User::where('role', 'mahasiswa')->get();
        $tingkat = TingkatPrestasi::all();
        $kategori = KategoriPrestasi::all();
        $jenis = JenisPrestasi::all();
        $tahunAkademik = TahunAkademik::all();

        // 2. Sortir data mahasiswa yang sudah terpilih di PHP (Bukan di Blade/JS)
        $mahasiswaTerpilih = [];
        foreach ($prestasi->mahasiswa as $m) {
            $mahasiswaTerpilih[] = [
                'id' => (string) $m->id,
                'name' => $m->name,
                'nim' => $m->nim_nip
            ];
        }
        $role = Auth::user()->role;


        // 3. Lempar semua data ke View
        return view($role . '.prestasi-edit', compact(
            'prestasi',
            'tingkat',
            'mahasiswa',
            'kategori',
            'jenis',
            'tahunAkademik',
            'mahasiswaTerpilih'
        ));
    }

    public function updatePrestasi(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);

        $request->validate([
            'user_ids' => 'required|array|min:1',
            'nama_prestasi' => 'required|string|max:255',
            'tingkat_id' => 'required|exists:tingkat_prestasi,id',
            'jenis_id' => 'required|exists:jenis_prestasi,id',
            'kategori_id' => 'required|exists:kategori_prestasi,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,png|max:2048', // NULLABLE SAAT UPDATE
            'tanggal_peroleh' => 'required|date',
        ]);

        $data = $request->except(['sertifikat', 'user_ids']);

        if ($request->hasFile('sertifikat')) {
            if ($prestasi->sertifikat && Storage::disk('public')->exists($prestasi->sertifikat)) {
                Storage::disk('public')->delete($prestasi->sertifikat);
            }
            $fileName = time() . '_' . $request->file('sertifikat')->getClientOriginalName();
            $data['sertifikat'] = $request->file('sertifikat')->storeAs('sertifikat_prestasi', $fileName, 'public');
        }

        $prestasi->update($data);
        $prestasi->mahasiswa()->sync($request->user_ids); // SYNC untuk update tim
        $role = Auth::user()->role;

        return redirect()->route($role . '.prestasi')->with('success', 'Data prestasi berhasil diperbarui!');
    }

    public function validasiPrestasi()
    {
        $pending = Prestasi::with(['mahasiswa', 'tingkat', 'kategori', 'jenis', 'tahunAkademik'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        $role = Auth::user()->role;

        return view($role . '.prestasi-validasi', compact('pending'));
    }

    public function validasiMassal(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:prestasi,id',
            'action' => 'required|in:approve'
        ]);

        Prestasi::whereIn('id', $request->ids)->update([
            'status' => 'approved',
            'updated_at' => now()
        ]);

        return back()->with('success', count($request->ids) . ' data prestasi berhasil disetujui sekaligus!');
    }

    public function updateStatusPrestasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
            'alasan_ditolak' => 'required_if:status,rejected',
        ]);

        $prestasi = Prestasi::findOrFail($id);
        $prestasi->update([
            'status' => $request->status,
            'alasan_ditolak' => $request->status === 'rejected' ? $request->alasan_ditolak : null
        ]);

        return back()->with('success', 'Status validasi berhasil diperbarui!');
    }

    public function publishPrestasi($id)
    {
        // Load relasi kategori juga biar gak error pas dipanggil di template
        $prestasi = Prestasi::with(['mahasiswa', 'tingkat', 'kategori'])->findOrFail($id);

        // 1. Update status publikasi di tabel prestasi
        $prestasi->update(['is_published' => true]);

        // 2. Bersihkan nama prestasi dari kata Juara di awal (untuk isi konten)
        $rawNama = $prestasi->nama_prestasi;
        $cleanNama = preg_replace('/^(juara|pemenang|winner)\s*([1-9]|satu|dua|tiga|empat|lima)?\s+/i', '', $rawNama);

        function formatCapital($text)
        {
            // Hilangkan spasi ganda yang mungkin bikin tulisan jadi renggang
            $text = preg_replace('/\s+/', ' ', trim($text));
            return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
        }

        // 3. Ambil data pendukung & Format jadi Title Case (Kapital Setiap Kata)
        $namaMhs   = formatCapital($prestasi->mahasiswa->pluck('name')->implode(', '));
        $tingkat   = formatCapital($prestasi->tingkat->nama_tingkat ?? 'Nasional');
        $kategori  = formatCapital($prestasi->kategori->nama_kategori ?? 'Umum');
        $cleanNama = formatCapital($cleanNama);


        // 4. Template Berita
        $content = "Selamat kepada {$namaMhs} yang telah berhasil meraih prestasi dalam ajang {$cleanNama} tingkat {$tingkat}. Capaian pada kategori {$kategori} ini merupakan bentuk dedikasi mahasiswa dalam mengharumkan nama almamater.";
        $isPdf = Str::endsWith(strtolower($prestasi->sertifikat), '.pdf');
        $thumbnailPath = $isPdf ? 'defaults/news-placeholder.jpg' : $prestasi->sertifikat;


        // 5. Simpan (Gunakan formatCapital juga untuk Title agar rapi)
        Konten::updateOrCreate(
            ['prestasi_id' => $prestasi->id],
            [
                'user_id'      => Auth::id(),
                'title'        => "Prestasi: " . formatCapital($rawNama),
                'slug'         => Str::slug($rawNama) . '-' . time(),
                'category'     => 'prestasi',
                'content'      => $content,
                'thumbnail'    => $thumbnailPath,
                'is_published' => true,
            ]
        );

        return back()->with('success', 'Prestasi berhasil dipublikasikan sebagai berita!');
    }
    public function takedownPrestasi($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $prestasi->update(['is_published' => false]);

        // Hapus artikel berita otomatis yang berhubungan dengan prestasi ini
        Konten::where('prestasi_id', $id)->delete();

        return back()->with('success', 'Prestasi ditarik dan artikel terkait otomatis dihapus.');
    }

    public function destroyPrestasi($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        // Hapus file fisik sertifikat
        if (Storage::disk('public')->exists($prestasi->sertifikat)) {
            Storage::disk('public')->delete($prestasi->sertifikat);
        }

        // Hapus data dari database (Relasi pivot akan otomatis terhapus karena 'onDelete cascade')
        $prestasi->delete();

        return back()->with('success', 'Data prestasi telah dihapus permanen.');
    }

    public function laporanRekap()
    {
        $role = Auth::user()->role;

        return view($role . '.prestasi-laporan-rekap');
    }


    //=====================================================
    public function indexPrestasiMahasiswa(Request $request)
    {
        // Ambil ID mahasiswa yang login
        $userId = Auth::id();

        // Query hanya prestasi milik mahasiswa yang sedang login
        $query = Prestasi::with(['tingkat', 'kategori', 'jenis', 'tahunAkademik'])
            ->whereHas('mahasiswa', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });

        // 1. Pencarian Teks (Hanya cari di nama prestasi karena mahasiswa sudah pasti dirinya sendiri)
        if ($request->filled('search')) {
            $query->where('nama_prestasi', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Master Data
        if ($request->filled('jenis_id')) $query->where('jenis_id', $request->jenis_id);
        if ($request->filled('kategori_id')) $query->where('kategori_id', $request->kategori_id);
        if ($request->filled('tingkat_id')) $query->where('tingkat_id', $request->tingkat_id);

        // 3. Filter Status (Approved/Pending/Rejected)
        if ($request->filled('status')) $query->where('status', $request->status);

        // 4. Sorting
        if ($request->filled('sort_tanggal')) {
            $query->orderBy('tanggal_peroleh', $request->sort_tanggal);
        } else {
            $query->latest();
        }

        $prestasi = $query->paginate(10)->withQueryString();

        // Data Master untuk Dropdown Filter di View Mahasiswa
        $masterTingkat = TingkatPrestasi::all();
        $masterKategori = KategoriPrestasi::all();
        $masterJenis = JenisPrestasi::all();

        return view('mahasiswa.prestasi', compact('prestasi', 'masterTingkat', 'masterKategori', 'masterJenis'));
    }

    public function createMahasiswa()
    {
        $tingkat = TingkatPrestasi::all();
        $kategori = KategoriPrestasi::all();
        $jenis = JenisPrestasi::all();
        $tahunAkademik = TahunAkademik::all();
        // Ambil data mahasiswa lain untuk pilihan tim
        $allMahasiswa = \App\Models\User::where('role', 'mahasiswa')->get();

        return view('mahasiswa.prestasi-create', compact('tingkat', 'kategori', 'jenis', 'tahunAkademik', 'allMahasiswa'));
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'tingkat_id' => 'required|exists:tingkat_prestasi,id',
            'kategori_id' => 'required', // Bisa berisi ID atau string "lainnya"
            'jenis_id' => 'required',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'user_ids' => 'required|array|min:1',
            'sertifikat' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'tanggal_peroleh' => 'required|date',
        ]);

        // 1. LOGIKA HANDLE KATEGORI BARU
        $kategoriId = $request->kategori_id;
        if ($kategoriId === 'lainnya') {
            // Simpan ke tabel KategoriPrestasi (Sesuaikan nama model & kolomnya)
            $kategoriBaru = \App\Models\KategoriPrestasi::create([
                'nama_kategori' => $request->kategori_baru
            ]);
            $kategoriId = $kategoriBaru->id; // Ambil ID barunya
        }

        // 2. LOGIKA HANDLE JENIS BARU
        $jenisId = $request->jenis_id;
        if ($jenisId === 'lainnya') {
            // Simpan ke tabel JenisPrestasi
            $jenisBaru = \App\Models\JenisPrestasi::create([
                'nama_jenis' => $request->jenis_baru
            ]);
            $jenisId = $jenisBaru->id; // Ambil ID barunya
        }

        // 3. Handle File Upload
        $file = $request->file('sertifikat');
        $path = $file->store('sertifikats', 'public');

        // 4. Simpan Data Prestasi (Gunakan ID yang sudah diproses)
        $prestasi = Prestasi::create([
            'nama_prestasi'     => $request->nama_prestasi,
            'tingkat_id'        => $request->tingkat_id,
            'kategori_id'       => $kategoriId, // <--- Sudah jadi Angka ID
            'jenis_id'          => $jenisId,    // <--- Sudah jadi Angka ID
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'sertifikat'        => $path,
            'tanggal_peroleh'   => $request->tanggal_peroleh,
            'deskripsi'         => $request->deskripsi,
            'status'            => 'pending',
            'is_published'      => false,
        ]);

        // Sinkronisasi Mahasiswa (Pivot Table)
        $prestasi->mahasiswa()->sync($request->user_ids);

        return redirect()->route('mahasiswa.prestasi')->with('success', 'Prestasi berhasil dilaporkan!');
    }
    // Tampilkan Halaman Edit
    public function editMahasiswa($id)
    {
        // Pastikan data milik mahasiswa yang login DAN statusnya bukan approved
        $prestasi = Prestasi::whereHas('mahasiswa', function ($q) {
            $q->where('users.id', Auth::id());
        })->findOrFail($id);

        if ($prestasi->status === 'approved') {
            return redirect()->route('mahasiswa.prestasi')->with('error', 'Data yang telah diverifikasi tidak dapat diubah.');
        }

        $tingkat = TingkatPrestasi::all();
        $kategori = KategoriPrestasi::all();
        $jenis = JenisPrestasi::all();
        $tahunAkademik = TahunAkademik::all();
        $allMahasiswa = User::where('role', 'mahasiswa')->get();

        return view('mahasiswa.prestasi-edit', compact('prestasi', 'tingkat', 'kategori', 'jenis', 'tahunAkademik', 'allMahasiswa'));
    }

    // Proses Update Data
    public function updateMahasiswa(Request $request, $id)
    {
        $prestasi = Prestasi::whereHas('mahasiswa', function ($q) {
            $q->where('users.id', Auth::id());
        })->findOrFail($id);

        if ($prestasi->status === 'approved') {
            return back()->with('error', 'Update gagal. Data sudah terkunci.');
        }

        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'user_ids' => 'required|array|min:1',
        ]);

        // Handle File jika ada upload baru
        if ($request->hasFile('sertifikat')) {
            // Hapus file lama jika ingin hemat storage
            if ($prestasi->sertifikat) {
                Storage::disk('public')->delete($prestasi->sertifikat);
            }
            $path = $request->file('sertifikat')->store('sertifikats', 'public');
            $prestasi->sertifikat = $path;
        }

        // Update data inti (gunakan logika "lainnya" yang sama seperti store jika perlu)
        $prestasi->update([
            'nama_prestasi' => $request->nama_prestasi,
            'tingkat_id' => $request->tingkat_id,
            'kategori_id' => $request->kategori_id,
            'jenis_id' => $request->jenis_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'tanggal_peroleh' => $request->tanggal_peroleh,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending', // Reset status ke pending agar dicek ulang admin
        ]);

        $prestasi->mahasiswa()->sync($request->user_ids);

        return redirect()->route('mahasiswa.prestasi')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroyMahasiswa($id)
    {
        // Cek apakah data ketemu
        $prestasi = Prestasi::whereHas('mahasiswa', function ($q) {
            $q->where('users.id', Auth::id());
        })->find($id);

        if (!$prestasi) {
            return back()->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($prestasi->status === 'approved') {
            return back()->with('error', 'Data sudah disetujui, tidak bisa dihapus!');
        }

        // Lepas relasi pivot dulu biar nggak dicegat Database Constraint
        $prestasi->mahasiswa()->detach();

        // Hapus file
        if ($prestasi->sertifikat) {
            Storage::disk('public')->delete($prestasi->sertifikat);
        }

        $prestasi->delete();

        return redirect()->route('mahasiswa.prestasi')->with('success', 'Data berhasil dihapus.');
    }

    public function showMahasiswa($id)
    {
        // Pastikan mahasiswa hanya bisa melihat detail prestasinya sendiri
        $prestasi = Prestasi::with(['mahasiswa', 'tingkat', 'kategori', 'jenis', 'tahunAkademik'])
            ->whereHas('mahasiswa', function ($q) {
                $q->where('users.id', Auth::id());
            })
            ->findOrFail($id);

        return view('mahasiswa.prestasi-show', compact('prestasi'));
    }
}
