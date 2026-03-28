<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengaturanSistemController extends Controller
{
    public function index()
    {
        // Ambil semua data dan jadikan array dengan key berupa 'kunci'
        // Biar di view gampang manggilnya: $settings['nama_aplikasi']->nilai
        $data = DB::table('pengaturan_sistem')->get();
        $settings = $data->keyBy('kunci');

        return view('master_data.pengaturan_sistem_index', compact('settings'));
    }

    public function update(Request $request)
    {
        // 1. VALIDASI DI AWAL (Sebelum nyentuh database)
        $request->validate([
            'logo_aplikasi' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048' // Maksimal 2MB
        ], [
            'logo_aplikasi.max' => 'Gagal! Ukuran file logo maksimal 2 MB, Bang.',
            'logo_aplikasi.image' => 'Gagal! File yang diupload harus berupa gambar.',
            'logo_aplikasi.mimes' => 'Gagal! Format logo harus jpeg, png, jpg, atau svg.'
        ]);

        $inputs = $request->except(['_token', '_method', 'logo_aplikasi']);

        DB::beginTransaction();
        try {
            // 2. Update text/textarea/select
            foreach ($inputs as $kunci => $nilai) {
                DB::table('pengaturan_sistem')
                    ->where('kunci', $kunci)
                    ->update(['nilai' => $nilai ?? '']);
            }

            // 3. Update File Logo ke Storage (JIKA ADA & JIKA VALID)
            if ($request->hasFile('logo_aplikasi') && $request->file('logo_aplikasi')->isValid()) {

                $oldLogo = DB::table('pengaturan_sistem')->where('kunci', 'logo_aplikasi')->value('nilai');

                if ($oldLogo && $oldLogo !== 'logo-unimed.png' && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
                }

                $path = $request->file('logo_aplikasi')->store('pengaturan', 'public');

                DB::table('pengaturan_sistem')
                    ->where('kunci', 'logo_aplikasi')
                    ->update(['nilai' => $path]);
            }

            DB::commit();
            return back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
