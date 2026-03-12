<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\JenisPrestasi;
use App\Models\KategoriPrestasi;
use App\Models\TingkatPrestasi;
use App\Models\TahunAkademik;
use App\Models\TemplateSurat;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED FAKULTAS
        $fmipa = Fakultas::create([
            'nama_fakultas' => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM'
        ]);

        // 2. SEED JURUSAN (Di bawah FMIPA)
        $jurMatematika = Jurusan::create(['fakultas_id' => $fmipa->id, 'nama_jurusan' => 'MATEMATIKA']);
        $jurKimia      = Jurusan::create(['fakultas_id' => $fmipa->id, 'nama_jurusan' => 'KIMIA']);
        $jurBiologi    = Jurusan::create(['fakultas_id' => $fmipa->id, 'nama_jurusan' => 'BIOLOGI']);
        $jurFisika     = Jurusan::create(['fakultas_id' => $fmipa->id, 'nama_jurusan' => 'FISIKA']);

        // 3. SEED PRODI (Di bawah Jurusan MATEMATIKA)
        $prodis = ['ILMU KOMPUTER', 'MATEMATIKA', 'PENDIDIKAN MATEMATIKA', 'STATISTIKA'];
        foreach ($prodis as $p) {
            Prodi::create([
                'jurusan_id' => $jurMatematika->id,
                'nama_prodi' => $p
            ]);
        }

        // 4. SEED ATRIBUT: JENIS
        $jenis = ['AKADEMIK', 'NON-AKADEMIK', 'LAINNYA'];
        foreach ($jenis as $jen) {
            JenisPrestasi::create(['nama_jenis' => $jen]);
        }

        // 5. SEED ATRIBUT: KATEGORI
        KategoriPrestasi::create(['nama_kategori' => 'LAINNYA']);

        // 6. SEED ATRIBUT: TINGKAT
        $tingkat = ['DESA/KELURAHAN', 'KECAMATAN', 'KABUPATEN/KOTA', 'PROVINSI', 'NASIONAL', 'INTERNASIONAL'];
        foreach ($tingkat as $ting) {
            TingkatPrestasi::create(['nama_tingkat' => $ting]);
        }

        // 7. SEED TAHUN AKADEMIK (Biar tidak "BELUM DISET")
        TahunAkademik::create(['tahun' => '2025/2026']);

        // 8. SEED TEMPLATE SURAT
        TemplateSurat::create(['nama_template' => 'LAPORAN', 'file_path' => 'templates/default_laporan.docx']);
        TemplateSurat::create(['nama_template' => 'REKAP', 'file_path' => 'templates/default_rekap.docx']);
    }
}
