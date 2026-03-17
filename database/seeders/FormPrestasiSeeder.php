<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriPrestasi;
use App\Models\FieldFormPrestasi;
use Illuminate\Support\Str;

class FormPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================================
        // FORM 1: PRESTASI LUAR BELMAWA
        // ========================================================
        $form1 = KategoriPrestasi::create([
            'nama_kategori' => 'Pelaporan Prestasi Diluar Kegiatan Belmawa',
            'deskripsi'     => 'Pelaporan Prestasi Diluar Kegiatan Belmawa Terhitung 01 Januari s.d. 31 Desember',
            'slug'          => Str::slug('Pelaporan Prestasi Diluar Kegiatan Belmawa'),
        ]);

        $fields1 = [
            ['nama_field' => 'kategori_kegiatan', 'label' => 'Kategori Kegiatan', 'tipe' => 'select', 'opsi' => json_encode(['Internasional', 'Nasional', 'Provinsi', 'Wilayah', 'Universitas']), 'aturan_validasi' => null, 'urutan' => 1],
            ['nama_field' => 'jenis_kepesertaan', 'label' => 'Jenis Kepesertaan', 'tipe' => 'select', 'opsi' => json_encode(['Individu', 'Kelompok']), 'aturan_validasi' => null, 'urutan' => 2],
            ['nama_field' => 'jumlah_pt_peserta', 'label' => 'Jumlah Perguruan Tinggi Peserta Lomba', 'tipe' => 'number', 'opsi' => null, 'aturan_validasi' => json_encode(['min' => 1]), 'urutan' => 3],
            ['nama_field' => 'nama_kegiatan', 'label' => 'Nama Kegiatan', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 4],
            ['nama_field' => 'tempat_pelaksanaan', 'label' => 'Tempat Pelaksanaan', 'tipe' => 'text', 'keterangan' => 'Contoh: Kota Surabaya, Provinsi Jawa Timur', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 5],
            ['nama_field' => 'jumlah_peserta_lomba', 'label' => 'Jumlah Peserta Lomba', 'tipe' => 'number', 'opsi' => null, 'aturan_validasi' => json_encode(['min' => 1]), 'urutan' => 6],
            ['nama_field' => 'capaian_prestasi', 'label' => 'Capaian Prestasi', 'tipe' => 'text', 'keterangan' => 'Contoh: Juara 1, Medali Emas', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 7],
            ['nama_field' => 'tahun_kegiatan', 'label' => 'Tahun Kegiatan', 'tipe' => 'number', 'opsi' => null, 'aturan_validasi' => json_encode(['min' => 2000, 'max' => date('Y')]), 'urutan' => 8],
            ['nama_field' => 'tanggal_mulai', 'label' => 'Tanggal Mulai Kegiatan', 'tipe' => 'date', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 9],
            ['nama_field' => 'tanggal_akhir', 'label' => 'Tanggal Akhir Kegiatan', 'tipe' => 'date', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 10],

            // Aturan Validasi File (Hanya PDF, Max 10MB)
            ['nama_field' => 'file_sertifikat', 'label' => 'Unggah Sertifikat / Piala / Medali', 'tipe' => 'file', 'keterangan' => 'Scan Asli Format PDF Maks 10 MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf', 'max_kb' => 10240]), 'urutan' => 11],

            ['nama_field' => 'url_kegiatan', 'label' => 'URL Penyelenggara / Berita', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => json_encode(['url' => true]), 'urutan' => 12],

            // Aturan Validasi File (PDF/JPG/JPEG/PNG, Max 10MB)
            ['nama_field' => 'file_foto_penyerahan', 'label' => 'Unggah Foto Upacara Penyerahan Penghargaan', 'tipe' => 'file', 'keterangan' => 'PDF / JPG Maks 10 MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf,jpg,jpeg,png', 'max_kb' => 10240]), 'urutan' => 13],
            ['nama_field' => 'file_surat_tugas', 'label' => 'Unggah Surat Tugas / Undangan', 'tipe' => 'file', 'keterangan' => 'PDF / JPG Maks 10 MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf,jpg,jpeg,png', 'max_kb' => 10240]), 'urutan' => 14],

            ['nama_field' => 'anggota_kelompok', 'label' => 'Anggota Tim (Jika Berkelompok)', 'tipe' => 'anggota_kelompok', 'keterangan' => 'Ketik Nama/NIM teman setim. Jika muncul, KLIK namanya agar sinkron.', 'is_required' => false, 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 15],
            ['nama_field' => 'nama_dosen_pembimbing', 'label' => 'Nama Lengkap Beserta Gelar Dosen Pembimbing', 'tipe' => 'text', 'keterangan' => 'Contoh: Dr. WAHID SYAHPUTRA, S.Pd., M.Pd.', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 16],
            ['nama_field' => 'nidn_dosen', 'label' => 'NIDN Pembimbing / Pendamping', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 17],

            // Aturan Validasi File
            ['nama_field' => 'file_surat_tugas_dosen', 'label' => 'Surat Tugas Dosen Pembimbing', 'tipe' => 'file', 'keterangan' => 'Scan Asli PDF/JPG Maks 10 MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf,jpg,jpeg,png', 'max_kb' => 10240]), 'urutan' => 18],
        ];

        foreach ($fields1 as $field) {
            $form1->fields()->create($field);
        }

        // ========================================================
        // FORM 2: SERTIFIKASI
        // ========================================================
        $form2 = KategoriPrestasi::create([
            'nama_kategori' => 'Kegiatan Mandiri Sertifikasi Internasional dan Nasional',
            'deskripsi'     => 'Pelaporan Kegiatan Mandiri Sertifikasi Internasional dan Nasional Mahasiswa',
            'slug'          => Str::slug('Kegiatan Mandiri Sertifikasi Internasional dan Nasional'),
        ]);

        $fields2 = [
            ['nama_field' => 'nama_skema_sertifikasi', 'label' => 'Nama Skema Sertifikasi', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 1],
            ['nama_field' => 'nama_lembaga_sertifikasi', 'label' => 'Nama Lembaga Pemberi Sertifikasi', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 2],
            ['nama_field' => 'tahun_kegiatan', 'label' => 'Tahun Kegiatan', 'tipe' => 'select', 'opsi' => json_encode(['2024', '2025', '2026']), 'aturan_validasi' => null, 'urutan' => 3],
            ['nama_field' => 'tanggal_sertifikasi', 'label' => 'Tanggal Sertifikasi', 'tipe' => 'date', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 4],
            ['nama_field' => 'kategori_kegiatan', 'label' => 'Kategori Kegiatan', 'tipe' => 'select', 'opsi' => json_encode(['Nasional', 'Internasional']), 'aturan_validasi' => null, 'urutan' => 5],
            ['nama_field' => 'file_sertifikat', 'label' => 'Unggah Sertifikat', 'tipe' => 'file', 'keterangan' => 'Scan Asli PDF Maks 10 MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf', 'max_kb' => 10240]), 'urutan' => 6],
            ['nama_field' => 'nidn_dosen', 'label' => 'NIDN / NIDK Dosen Pendamping', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 7],
            ['nama_field' => 'nama_dosen', 'label' => 'Nama Lengkap Dosen Pendamping', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 8],
            ['nama_field' => 'file_surat_tugas_dosen', 'label' => 'Surat Tugas Dosen Pembimbing', 'tipe' => 'file', 'keterangan' => 'PDF/JPG Maks 10MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf,jpg,jpeg,png', 'max_kb' => 10240]), 'urutan' => 9],
        ];

        foreach ($fields2 as $field) {
            $form2->fields()->create($field);
        }

        // ========================================================
        // FORM 3: REKOGNISI
        // ========================================================
        $form3 = KategoriPrestasi::create([
            'nama_kategori' => 'Pelaporan Kegiatan Mandiri - Rekognisi',
            'deskripsi'     => 'Formulir Pelaporan Kegiatan Mandiri - Rekognisi',
            'slug'          => Str::slug('Pelaporan Kegiatan Mandiri - Rekognisi'),
        ]);

        $fields3 = [
            ['nama_field' => 'nama_kegiatan', 'label' => 'Nama Kegiatan', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 1],
            ['nama_field' => 'kategori_kegiatan', 'label' => 'Kategori Kegiatan', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 2],
            ['nama_field' => 'tahun_kegiatan', 'label' => 'Tahun Kegiatan', 'tipe' => 'select', 'opsi' => json_encode(['2024', '2025', '2026']), 'aturan_validasi' => null, 'urutan' => 3],
            ['nama_field' => 'tanggal_mulai', 'label' => 'Tanggal Mulai Kegiatan', 'tipe' => 'date', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 4],
            ['nama_field' => 'tanggal_akhir', 'label' => 'Tanggal Akhir Kegiatan', 'tipe' => 'date', 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 5],

            // Sesuai teks di Google Form: "Format JPG/PNG/PDF Maksimum 2 MB" -> 2048 KB
            ['nama_field' => 'file_sertifikat', 'label' => 'Unggah Pindaian Sertifikat Apresiasi', 'tipe' => 'file', 'keterangan' => 'Format JPG/PNG/PDF Maks 2MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf,jpg,jpeg,png', 'max_kb' => 2048]), 'urutan' => 6],
            ['nama_field' => 'file_foto_kegiatan', 'label' => 'Unggah Foto Penyerahan / Pagelaran', 'tipe' => 'file', 'keterangan' => 'Format JPG/PNG/PDF Maks 2MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf,jpg,jpeg,png', 'max_kb' => 2048]), 'urutan' => 7],

            ['nama_field' => 'url_laman', 'label' => 'URL Laman Penyelenggara / Berita', 'tipe' => 'text', 'opsi' => null, 'aturan_validasi' => json_encode(['url' => true]), 'urutan' => 8],

            ['nama_field' => 'file_surat_undangan', 'label' => 'Unggah Surat Undangan / Tugas', 'tipe' => 'file', 'keterangan' => 'Format JPG/PNG/PDF Maks 2MB', 'opsi' => null, 'aturan_validasi' => json_encode(['mimes' => 'pdf,jpg,jpeg,png', 'max_kb' => 2048]), 'urutan' => 9],
            ['nama_field' => 'anggota_kelompok', 'label' => 'Anggota Tim (Jika Berkelompok)', 'tipe' => 'anggota_kelompok', 'keterangan' => 'Ketik Nama/NIM teman setim. Jika muncul, KLIK namanya agar sinkron.', 'is_required' => false, 'opsi' => null, 'aturan_validasi' => null, 'urutan' => 10],
        ];

        foreach ($fields3 as $field) {
            $form3->fields()->create($field);
        }
    }
}
