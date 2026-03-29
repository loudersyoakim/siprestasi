<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prestasi;
use App\Models\User;
use App\Models\FormPrestasi;
use App\Models\TingkatPrestasi;
use App\Models\CapaianPrestasi;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PrestasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tarik semua data dari tabel relasi
        $users = User::all();
        $forms = FormPrestasi::all();
        $tingkatans = TingkatPrestasi::all();
        $capaians = CapaianPrestasi::all();

        // 2. Pastikan Master Data sudah di-seed sebelumnya
        if ($users->isEmpty() || $forms->isEmpty() || $tingkatans->isEmpty() || $capaians->isEmpty()) {
            $this->command->info('Gagal: Pastikan tabel User, FormPrestasi, TingkatPrestasi, dan CapaianPrestasi sudah ada isinya sebelum run Seeder ini.');
            return;
        }

        $kegiatanLomba = ['Olimpiade Matematika', 'Kompetisi Robotik', 'Lomba Debat Bahasa Inggris', 'Hackathon AI', 'Kejuaraan Pencak Silat'];
        $kegiatanSertifikasi = ['Sertifikasi Mikrotik', 'Sertifikasi Cisco MTCNA', 'TOEFL ITP', 'Sertifikasi Digital Marketing'];
        $kegiatanRekognisi = ['Pemakalah Seminar', 'Narasumber Workshop', 'Juri Lomba', 'Penulisan ISBN'];

        // Buat 50 Data Prestasi Dummy (Approved) menyebar di 12 bulan terakhir
        for ($i = 0; $i < 50; $i++) {
            $randomUser = $users->random();
            $randomForm = $forms->random();
            $randomTingkat = $tingkatans->random(); // Ambil Tingkat Acak dari Database
            $randomCapaian = $capaians->random();   // Ambil Capaian Acak dari Database

            // Tanggal acak antara hari ini sampai 330 hari ke belakang
            $randomDate = Carbon::now()->subDays(rand(1, 330));
            $endDate = $randomDate->copy()->addDays(rand(1, 5));
            $tahun = $randomDate->format('Y');

            // Menyesuaikan Nama Kegiatan berdasarkan tipe form
            $nama_kegiatan = 'Kegiatan Prestasi Mahasiswa';
            if (Str::contains($randomForm->nama_form, 'Sertifikasi')) {
                $nama_kegiatan = $kegiatanSertifikasi[array_rand($kegiatanSertifikasi)] . " Tahun " . $tahun;
            } elseif (Str::contains($randomForm->nama_form, 'Rekognisi')) {
                $nama_kegiatan = $kegiatanRekognisi[array_rand($kegiatanRekognisi)] . " di Universitas Brawijaya";
            } else {
                $nama_kegiatan = $kegiatanLomba[array_rand($kegiatanLomba)] . " Tingkat Mahasiswa";
            }

            Prestasi::create([
                'user_id'          => $randomUser->id,
                'form_prestasi_id' => $randomForm->id,
                'status'           => 'Approved',

                'nama_kegiatan'       => $nama_kegiatan,
                'tahun_kegiatan'      => $tahun,
                'tingkat_prestasi_id' => $randomTingkat->id,
                'capaian_prestasi_id' => $randomCapaian->id,

                'tanggal_mulai'    => $randomDate,
                'tanggal_selesai'  => $endDate,
                'is_published'     => rand(0, 1), // Sebagian sudah di-publish, sebagian draft
                'deskripsi_kegiatan' => 'Ini adalah deskripsi kegiatan untuk ' . $nama_kegiatan . ' yang diselenggarakan pada tahun ' . $tahun . '. Mahasiswa menunjukkan dedikasi yang luar biasa.',

                // KOLOM DINAMIS (Sisa data spesifik form masuk sini)
                'data_dinamis' => [
                    'nama_dosen'           => 'Dr. Wahid Syahputra, M.Pd.',
                    'nidn_dosen'           => '1234567890',
                    'jumlah_peserta_lomba' => rand(50, 500),
                    'tempat_pelaksanaan'   => 'Kota Jakarta',
                    'url_laman'            => 'https://dikti.kemdikbud.go.id',
                ],

                'created_at' => $randomDate,
                'updated_at' => $randomDate->copy()->addDays(2), // Tanggal disetujui
            ]);
        }

        $this->command->info('50 Data Prestasi Dummy (Terkoneksi dengan Master Data Tingkat & Capaian) berhasil di-generate!');
    }
}
