<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormPrestasi;
use App\Models\FieldFormPrestasi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class FormPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan tabel agar tidak duplikat saat di-seed ulang
        Schema::disableForeignKeyConstraints();
        FieldFormPrestasi::truncate();
        FormPrestasi::truncate();
        Schema::enableForeignKeyConstraints();

        // ========================================================
        // FORM 1: PRESTASI LUAR BELMAWA
        // ========================================================
        $form1 = FormPrestasi::create([
            'nama_form' => 'Pelaporan Prestasi Diluar Kegiatan Belmawa',
            'deskripsi' => 'Pelaporan Prestasi Diluar Kegiatan Belmawa Terhitung 01 Januari s.d. 31 Desember 2025',
            'slug'      => Str::slug('Pelaporan Prestasi Diluar Kegiatan Belmawa'),
            'is_active' => true,
        ]);

        $fields1 = [
            ['nama_field' => 'kategori_kegiatan', 'label' => 'Kategori Kegiatan', 'tipe' => 'select', 'opsi' => ['Kota', 'Provinsi', 'Wilayah', 'Nasional', 'Internasional'], 'urutan' => 1],
            ['nama_field' => 'jenis_kepesertaan', 'label' => 'Jenis Kepesertaan', 'tipe' => 'select', 'opsi' => ['Individu', 'Kelompok'], 'urutan' => 2],
            ['nama_field' => 'jumlah_pt_peserta', 'label' => 'Jumlah Perguruan Tinggi Peserta Lomba', 'tipe' => 'select', 'opsi' => ['< 10PT', '> 10PT'], 'urutan' => 3],
            ['nama_field' => 'nama_kegiatan', 'label' => 'Nama Kegiatan', 'tipe' => 'text', 'urutan' => 4],
            ['nama_field' => 'tempat_pelaksanaan', 'label' => 'Tempat Pelaksanaan', 'tipe' => 'text', 'keterangan' => 'Contoh: Kota Surabaya, Provinsi Jawa Timur', 'urutan' => 5],
            ['nama_field' => 'jumlah_peserta_lomba', 'label' => 'Jumlah Peserta Lomba', 'tipe' => 'number', 'urutan' => 6],
            ['nama_field' => 'capaian_prestasi', 'label' => 'Capaian Prestasi', 'tipe' => 'select', 'opsi' => ['Juara I', 'Juara II', 'Juara III', 'Harapan I', 'Harapan II', 'Harapan III', 'Peserta', 'Apresiasi Kejuaraan/Penghargaan'], 'urutan' => 7],
            ['nama_field' => 'tahun_kegiatan', 'label' => 'Tahun Kegiatan', 'tipe' => 'radio', 'opsi' => ['2022', '2023', '2024', '2025'], 'urutan' => 8],
            ['nama_field' => 'tanggal_mulai', 'label' => 'Tanggal Mulai Kegiatan', 'tipe' => 'date', 'urutan' => 9],
            ['nama_field' => 'tanggal_akhir', 'label' => 'Tanggal Akhir Kegiatan', 'tipe' => 'date', 'urutan' => 10],
            ['nama_field' => 'file_sertifikat', 'label' => 'Unggah Sertifikat / Piala / Medali', 'tipe' => 'file', 'keterangan' => 'Scan Asli Format PDF Maksimum 2 MB', 'urutan' => 11],
            ['nama_field' => 'url_kegiatan', 'label' => 'URL Penyelenggara / Media Sosial / Berita', 'tipe' => 'text', 'urutan' => 12],
            ['nama_field' => 'file_foto_penyerahan', 'label' => 'Unggah Foto Upacara Penyerahan Penghargaan', 'tipe' => 'file', 'keterangan' => 'PDF / JPG Maksimum 2 MB', 'urutan' => 13],
            ['nama_field' => 'file_surat_tugas', 'label' => 'Unggah Surat Tugas / Surat Izin / Surat Undangan', 'tipe' => 'file', 'keterangan' => 'Scan Asli PDF / JPG Maksimum 2 MB', 'urutan' => 14],

            ['nama_field' => 'nama_dosen', 'label' => 'NAMA LENGKAP Beserta Gelar Dosen Pembimbing', 'tipe' => 'text', 'urutan' => 18],
            ['nama_field' => 'nidn_dosen', 'label' => 'NIDN Pembimbing / Pendamping', 'tipe' => 'text', 'urutan' => 19],
            ['nama_field' => 'file_surat_tugas_dosen', 'label' => 'Surat Tugas Dosen Pembimbing / Pendamping', 'tipe' => 'file', 'keterangan' => 'Scan Asli PDF / JPG Maksimum 2 MB', 'urutan' => 20],
        ];

        foreach ($fields1 as $field) {
            $form1->fields()->create($field);
        }

        // ========================================================
        // FORM 2: SERTIFIKASI
        // ========================================================
        $form2 = FormPrestasi::create([
            'nama_form' => 'Kegiatan Mandiri Sertifikasi Internasional dan Nasional',
            'deskripsi' => 'Pelaporan Kegiatan Mandiri Sertifikasi Internasional dan Nasional Mahasiswa Tahun 2025',
            'slug'      => Str::slug('Kegiatan Mandiri Sertifikasi Internasional dan Nasional'),
            'is_active' => true,
        ]);

        $fields2 = [
            ['nama_field' => 'nama_skema', 'label' => 'Nama Skema Sertifikasi', 'tipe' => 'text', 'urutan' => 1],
            ['nama_field' => 'lembaga_pemberi', 'label' => 'Nama Lembaga Pemberi Sertifikasi', 'tipe' => 'text', 'urutan' => 2],
            ['nama_field' => 'tahun_kegiatan', 'label' => 'Tahun Kegiatan', 'tipe' => 'radio', 'opsi' => ['2022', '2023', '2024', '2025'], 'urutan' => 3],
            ['nama_field' => 'tanggal_sertifikasi', 'label' => 'Tanggal Sertifikasi', 'tipe' => 'date', 'urutan' => 4],
            ['nama_field' => 'kategori_kegiatan', 'label' => 'Kategori Kegiatan', 'tipe' => 'select', 'opsi' => ['NASIONAL', 'INTERNASIONAL'], 'urutan' => 5],
            ['nama_field' => 'file_sertifikat', 'label' => 'Unggah Sertifikat', 'tipe' => 'file', 'keterangan' => 'Scan Asli Format PDF Maksimum 2 MB', 'urutan' => 6],
            ['nama_field' => 'nidn_dosen', 'label' => 'NIDN / NIDK Dosen Pendamping', 'tipe' => 'text', 'urutan' => 7],
            ['nama_field' => 'nama_dosen', 'label' => 'NAMA LENGKAP Dosen Pendamping', 'tipe' => 'text', 'urutan' => 8],
            ['nama_field' => 'file_st_dosen', 'label' => 'Surat Tugas Dosen Pembimbing / Pendamping', 'tipe' => 'file', 'keterangan' => 'PDF / JPG Maksimum 2 MB', 'urutan' => 9]
        ];

        foreach ($fields2 as $field) {
            $form2->fields()->create($field);
        }

        // ========================================================
        // FORM 3: REKOGNISI
        // ========================================================
        $form3 = FormPrestasi::create([
            'nama_form' => 'Pelaporan Kegiatan Mandiri - Rekognisi',
            'deskripsi' => 'Pelaporan Kegiatan Mandiri - Rekognisi Tahun 2025',
            'slug'      => Str::slug('Pelaporan Kegiatan Mandiri - Rekognisi'),
            'is_active' => true,
        ]);

        $fields3 = [
            ['nama_field' => 'nama_kegiatan', 'label' => 'Nama Kegiatan', 'tipe' => 'text', 'urutan' => 1],
            ['nama_field' => 'kategori_kegiatan', 'label' => 'Kategori Kegiatan', 'tipe' => 'select', 'opsi' => [
                'Juri/Pelatih/Wasit Internasional',
                'Juri/Pelatih/Wasit Nasional',
                'Juri/Pelatih/Wasit Provinsi',
                'Pemakalah/Speaker Conference/ Seminar Internasional',
                'Pemakalah/Speaker Conference/ Seminar Nasional',
                'Pemakalah/Speaker Conference/ Seminar Provinsi',
                'Narasumber pada kegiatan/seminar Internasional',
                'Narasumber pada kegiatan/seminar Nasional',
                'Narasumber pada kegiatan/seminar Provinsi',
                'Penulisan ISBN Internasional',
                'Penulisan ISBN Nasional',
                'Paten/Paten Sederhana Nasional',
                'Publikasi jurnal sinta 1 dan 2 dan/atau internasional bereputasi (Internasional)',
                'Publikasi jurnal sinta 1 dan 2 dan/atau internasional bereputasi (Nasional)',
                'Tuan rumah kejuaraan/Kompetisi Mandiri Internasional',
                'Tuan rumah kejuaraan/Kompetisi Mandiri Nasional',
                'Tuan rumah kejuaraan/Kompetisi Mandiri Provinsi'
            ], 'urutan' => 2],
            ['nama_field' => 'tahun_kegiatan', 'label' => 'Tahun Kegiatan', 'tipe' => 'radio', 'opsi' => ['2023', '2024', '2025'], 'urutan' => 3],
            ['nama_field' => 'tanggal_mulai', 'label' => 'Tanggal Mulai Kegiatan', 'tipe' => 'date', 'urutan' => 4],
            ['nama_field' => 'tanggal_akhir', 'label' => 'Tanggal Akhir Kegiatan', 'tipe' => 'date', 'urutan' => 5],
            ['nama_field' => 'file_sertifikat', 'label' => 'Unggah pindaian sertifikat apresiasi', 'tipe' => 'file', 'keterangan' => 'Format JPG/PNG/PDF Maksimum 2 MB', 'urutan' => 6],
            ['nama_field' => 'file_foto', 'label' => 'Unggah foto penyerahan/pameran/pagelaran', 'tipe' => 'file', 'keterangan' => 'Format JPG/PNG/PDF Maksimum 2 MB', 'urutan' => 7],
            ['nama_field' => 'url_laman', 'label' => 'URL laman penyelenggara / media social / berita', 'tipe' => 'text', 'urutan' => 8],
            ['nama_field' => 'file_undangan', 'label' => 'Unggah pindaian surat undangan/tugas', 'tipe' => 'file', 'keterangan' => 'Format JPG/PNG/PDF Maksimum 2 MB', 'urutan' => 9],

        ];

        foreach ($fields3 as $field) {
            $form3->fields()->create($field);
        }
    }
}
