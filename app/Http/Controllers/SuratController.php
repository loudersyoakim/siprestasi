<?php

namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use App\Models\PermohonanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
{
    // ==========================================
    // SISI MAHASISWA: MENGajukan Surat
    // ==========================================
    public function indexMahasiswa()
    {
        $templates = TemplateSurat::where('is_active', true)->get();
        $riwayat = PermohonanSurat::where('user_id', Auth::id())->latest()->get();

        return view('mahasiswa.surat.index', compact('templates', 'riwayat'));
    }

    public function ajukanSurat(Request $request, $templateId)
    {
        $template = TemplateSurat::findOrFail($templateId);

        // Simpan data_isian dalam bentuk JSON (dari field dinamis surat)
        PermohonanSurat::create([
            'user_id' => Auth::id(),
            'template_surat_id' => $template->id,
            'data_isian' => $request->except(['_token']),
            'status' => 'Pending'
        ]);

        return back()->with('success', 'Permohonan surat berhasil diajukan! Menunggu proses Admin.');
    }

    // ==========================================
    // SISI ADMIN & FAKULTAS: Validasi & Generate
    // ==========================================
    public function antreanSurat()
    {
        $permohonan = PermohonanSurat::with(['user', 'templateSurat'])->latest()->get();
        return view('admin.surat.antrean', compact('permohonan'));
    }

    public function prosesAdmin(Request $request, $id)
    {
        $surat = PermohonanSurat::findOrFail($id);

        if ($request->aksi == 'tolak') {
            $surat->update(['status' => 'Ditolak', 'catatan_admin' => $request->catatan]);
            return back()->with('error', 'Permohonan surat ditolak.');
        }

        // Jika disetujui admin, ubah status dan kasih nomor surat
        $surat->update([
            'status' => 'Menunggu TTD',
            'nomor_surat' => $request->nomor_surat
        ]);

        return back()->with('success', 'Surat diteruskan ke Fakultas untuk ditandatangani.');
    }

    public function ttdFakultas($id)
    {
        $surat = PermohonanSurat::findOrFail($id);

        // Asumsi PDF digenerate di sini (Nanti kita pakai package Barryvdh/DomPDF)
        // $pdfPath = ... logika cetak PDF ...

        $surat->update([
            'status' => 'Selesai',
            // 'file_pdf' => $pdfPath
        ]);

        return back()->with('success', 'Surat berhasil ditandatangani dan siap didownload mahasiswa!');
    }
}
