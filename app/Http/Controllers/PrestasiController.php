<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\FormPrestasi;
use App\Models\FieldFormPrestasi;
use App\Models\User;
use App\Models\AlurPersetujuan;
use App\Models\TingkatPrestasi;
use App\Models\CapaianPrestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PrestasiController extends Controller
{
    public function indexPrestasi(Request $request)
    {
        $query = Prestasi::with(['user', 'formPrestasi', 'anggota', 'tingkatPrestasi', 'capaianPrestasi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('data_dinamis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dynamicFields = collect();
        if ($request->filled('form_id')) {
            $query->where('form_prestasi_id', $request->form_id);
            $selectedForm = FormPrestasi::with(['fields' => function ($q) {
                $q->orderBy('urutan', 'asc');
            }])->find($request->form_id);

            if ($selectedForm) {
                $dynamicFields = $selectedForm->fields->where('tipe', '!=', 'anggota_kelompok');
            }
        }

        $prestasi = $query->latest()->paginate(10)->withQueryString();
        $listForm = FormPrestasi::where('is_active', true)->get();

        return view('prestasi.index_all', compact('prestasi', 'listForm', 'dynamicFields'));
    }

    public function create(Request $request)
    {
        $forms = FormPrestasi::where('is_active', true)->get();
        $selectedForm = null;

        if ($request->filled('form_id')) {
            $selectedForm = FormPrestasi::with(['fields' => function ($q) {
                $q->orderBy('urutan', 'asc');
            }])->findOrFail($request->form_id);
        }

        $mahasiswa = User::whereHas('role', fn($q) => $q->where('kode_role', 'MHS'))->get();
        $tingkat_list = TingkatPrestasi::where('is_active', true)->orderBy('nama_tingkat')->get();
        $capaian_list = CapaianPrestasi::where('is_active', true)->orderBy('nama_capaian')->get();

        return view('prestasi.create', compact('forms', 'selectedForm', 'mahasiswa', 'tingkat_list', 'capaian_list'));
    }

    public function store(Request $request)
    {
        $formId = $request->form_prestasi_id;
        $form = FormPrestasi::findOrFail($formId);
        $fields = FieldFormPrestasi::where('form_prestasi_id', $formId)->get();

        // Ambil Setting Statis
        $settings = is_string($form->setting_statis) ? json_decode($form->setting_statis, true) : ($form->setting_statis ?? []);

        $rules = [
            'form_prestasi_id' => 'required|exists:form_prestasis,id',
            'user_ids'         => 'required|array|min:1',
        ];

        // VALIDASI KOLOM STATIS (Hanya jika aktif di setting)
        if ($settings['nama_kegiatan'] ?? true) $rules['nama_kegiatan'] = 'required|string|max:255';
        if ($settings['tingkat'] ?? true) $rules['tingkat_prestasi_id'] = 'required|exists:tingkat_prestasis,id';
        if ($settings['capaian'] ?? true) $rules['capaian_prestasi_id'] = 'required|exists:capaian_prestasis,id';
        if ($settings['tahun'] ?? true) $rules['tahun_kegiatan'] = 'required|integer';
        if ($settings['tanggal'] ?? true) {
            $rules['tanggal_mulai'] = 'required|date';
            $rules['tanggal_selesai'] = 'nullable|date|after_or_equal:tanggal_mulai';
        }

        // Validasi Form Dinamis
        foreach ($fields as $field) {
            if ($field->tipe === 'anggota_kelompok') continue;
            if ($field->is_required && $field->tipe !== 'file') $rules['field_' . $field->id] = 'required';
            if ($field->tipe === 'file') $rules['field_' . $field->id] = $field->is_required ? 'required|file|max:10240' : 'nullable|file|max:10240';
        }

        $request->validate($rules);

        $userIdsRaw = $request->user_ids;
        $firstUserParts = explode('|', $userIdsRaw[0]);
        $mainUserId = $firstUserParts[0];

        if (str_starts_with($mainUserId, 'MANUAL_')) {
            return back()->withInput()->with('error', 'Pelapor utama (Ketua) harus mahasiswa yang terdaftar di sistem!');
        }

        try {
            DB::beginTransaction();

            $dataDinamis = [];
            $anggotaManual = [];

            foreach ($fields as $field) {
                if ($field->tipe === 'anggota_kelompok') continue;
                $key = 'field_' . $field->id;

                if ($field->tipe === 'file' && $request->hasFile($key)) {
                    $dataDinamis[$field->id] = $request->file($key)->store('prestasi/lampiran', 'public');
                } else {
                    $dataDinamis[$field->id] = $request->input($key);
                }
            }

            for ($i = 1; $i < count($userIdsRaw); $i++) {
                $parts = explode('|', $userIdsRaw[$i]);
                if (str_starts_with($parts[0], 'MANUAL_')) {
                    $anggotaManual[] = ['nama' => $parts[1]];
                }
            }

            if (count($anggotaManual) > 0) {
                $dataDinamis['anggota_manual'] = $anggotaManual;
            }

            $rolePelapor = Auth::user()->role->kode_role ?? 'MHS';
            if ($rolePelapor !== 'MHS') {
                $statusAwal = 'Approved';
            } else {
                $adaAlurAktif = AlurPersetujuan::where('is_active', true)->exists();
                $statusAwal = $adaAlurAktif ? 'Pending' : 'Approved';
            }

            $prestasi = Prestasi::create([
                'user_id'             => $mainUserId,
                'form_prestasi_id'    => $formId,
                'status'              => $statusAwal,
                // Kolom Statis (Bakal null otomatis kalau inputannya dimatikan)
                'nama_kegiatan'       => $request->nama_kegiatan,
                'tingkat_prestasi_id' => $request->tingkat_prestasi_id,
                'capaian_prestasi_id' => $request->capaian_prestasi_id,
                'tahun_kegiatan'      => $request->tahun_kegiatan,
                'tanggal_mulai'       => $request->tanggal_mulai,
                'tanggal_selesai'     => $request->tanggal_selesai,
                'data_dinamis'        => $dataDinamis,
            ]);

            for ($i = 1; $i < count($userIdsRaw); $i++) {
                $parts = explode('|', $userIdsRaw[$i]);
                if (!str_starts_with($parts[0], 'MANUAL_')) {
                    DB::table('anggota_prestasis')->insert([
                        'prestasi_id' => $prestasi->id,
                        'user_id'     => $parts[0],
                        'peran'       => 'Anggota',
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('prestasi.index-all')->with('success', "Data prestasi berhasil disimpan dengan status: {$statusAwal}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $prestasi = Prestasi::with(['user', 'formPrestasi', 'anggota', 'tingkatPrestasi', 'capaianPrestasi'])->findOrFail($id);
        $fields = FieldFormPrestasi::where('form_prestasi_id', $prestasi->form_prestasi_id)
            ->where('tipe', '!=', 'anggota_kelompok')
            ->orderBy('urutan', 'asc')->get();

        return view('prestasi.show', compact('prestasi', 'fields'));
    }

    public function edit($id)
    {
        $prestasi = Prestasi::with(['user', 'anggota', 'tingkatPrestasi', 'capaianPrestasi', 'formPrestasi'])->findOrFail($id);
        $fields = FieldFormPrestasi::where('form_prestasi_id', $prestasi->form_prestasi_id)
            ->orderBy('urutan', 'asc')->get();

        $mahasiswa = User::whereHas('role', fn($q) => $q->where('kode_role', 'MHS'))->get();
        $tingkat_list = TingkatPrestasi::where('is_active', true)->orderBy('nama_tingkat')->get();
        $capaian_list = CapaianPrestasi::where('is_active', true)->orderBy('nama_capaian')->get();

        $mahasiswaTerpilih = [];
        $mahasiswaTerpilih[] = $prestasi->user_id . '|' . $prestasi->user->name . '|' . $prestasi->user->nim_nip;

        foreach ($prestasi->anggota as $ang) {
            $mahasiswaTerpilih[] = $ang->id . '|' . $ang->name . '|' . $ang->nim_nip;
        }

        $dataDinamis = is_string($prestasi->data_dinamis) ? json_decode($prestasi->data_dinamis, true) : ($prestasi->data_dinamis ?? []);
        if (isset($dataDinamis['anggota_manual']) && is_array($dataDinamis['anggota_manual'])) {
            foreach ($dataDinamis['anggota_manual'] as $man) {
                $mahasiswaTerpilih[] = 'MANUAL_' . uniqid() . '|' . $man['nama'] . '|-';
            }
        }

        $prestasi->data_dinamis = $dataDinamis;

        return view('prestasi.edit', compact('prestasi', 'fields', 'mahasiswa', 'mahasiswaTerpilih', 'tingkat_list', 'capaian_list'));
    }

    public function update(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $formId = $prestasi->form_prestasi_id;
        $form = FormPrestasi::findOrFail($formId);
        $fields = FieldFormPrestasi::where('form_prestasi_id', $formId)->get();

        $settings = is_string($form->setting_statis) ? json_decode($form->setting_statis, true) : ($form->setting_statis ?? []);

        $rules = ['user_ids' => 'required|array|min:1'];

        if ($settings['nama_kegiatan'] ?? true) $rules['nama_kegiatan'] = 'required|string|max:255';
        if ($settings['tingkat'] ?? true) $rules['tingkat_prestasi_id'] = 'required|exists:tingkat_prestasis,id';
        if ($settings['capaian'] ?? true) $rules['capaian_prestasi_id'] = 'required|exists:capaian_prestasis,id';
        if ($settings['tahun'] ?? true) $rules['tahun_kegiatan'] = 'required|integer';
        if ($settings['tanggal'] ?? true) {
            $rules['tanggal_mulai'] = 'required|date';
            $rules['tanggal_selesai'] = 'nullable|date|after_or_equal:tanggal_mulai';
        }

        foreach ($fields as $field) {
            if ($field->tipe === 'anggota_kelompok') continue;
            if ($field->is_required && $field->tipe !== 'file') $rules['field_' . $field->id] = 'required';
            if ($field->tipe === 'file') $rules['field_' . $field->id] = 'nullable|file|max:10240';
        }
        $request->validate($rules);

        $userIdsRaw = $request->user_ids;
        $firstUserParts = explode('|', $userIdsRaw[0]);
        $mainUserId = $firstUserParts[0];

        if (str_starts_with($mainUserId, 'MANUAL_')) {
            return back()->withInput()->with('error', 'Pelapor utama (Ketua) harus mahasiswa yang terdaftar di sistem!');
        }

        try {
            DB::beginTransaction();

            $dataDinamis = is_string($prestasi->data_dinamis) ? json_decode($prestasi->data_dinamis, true) : ($prestasi->data_dinamis ?? []);
            $anggotaManual = [];

            foreach ($fields as $field) {
                if ($field->tipe === 'anggota_kelompok') continue;
                $key = 'field_' . $field->id;

                if ($field->tipe === 'file' && $request->hasFile($key)) {
                    if (isset($dataDinamis[$field->id])) Storage::disk('public')->delete($dataDinamis[$field->id]);
                    $dataDinamis[$field->id] = $request->file($key)->store('prestasi/lampiran', 'public');
                } elseif ($field->tipe !== 'file') {
                    $dataDinamis[$field->id] = $request->input($key);
                }
            }

            for ($i = 1; $i < count($userIdsRaw); $i++) {
                $parts = explode('|', $userIdsRaw[$i]);
                if (str_starts_with($parts[0], 'MANUAL_')) $anggotaManual[] = ['nama' => $parts[1]];
            }
            $dataDinamis['anggota_manual'] = $anggotaManual;

            $rolePelapor = Auth::user()->role->kode_role ?? 'MHS';
            $statusAkhir = $prestasi->status;

            if ($rolePelapor === 'MHS') {
                $adaAlurAktif = AlurPersetujuan::where('is_active', true)->exists();
                $statusAkhir = $adaAlurAktif ? 'Pending' : 'Approved';
            }

            $prestasi->update([
                'user_id'             => $mainUserId,
                'status'              => $statusAkhir,
                'nama_kegiatan'       => $request->nama_kegiatan,
                'tingkat_prestasi_id' => $request->tingkat_prestasi_id,
                'capaian_prestasi_id' => $request->capaian_prestasi_id,
                'tahun_kegiatan'      => $request->tahun_kegiatan,
                'tanggal_mulai'       => $request->tanggal_mulai,
                'tanggal_selesai'     => $request->tanggal_selesai,
                'data_dinamis'        => $dataDinamis,
            ]);

            DB::table('anggota_prestasis')->where('prestasi_id', $prestasi->id)->delete();

            for ($i = 1; $i < count($userIdsRaw); $i++) {
                $parts = explode('|', $userIdsRaw[$i]);
                if (!str_starts_with($parts[0], 'MANUAL_')) {
                    DB::table('anggota_prestasis')->insert([
                        'prestasi_id' => $prestasi->id,
                        'user_id'     => $parts[0],
                        'peran'       => 'Anggota',
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('prestasi.index-all')->with('success', 'Data prestasi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        $dataDinamis = is_string($prestasi->data_dinamis) ? json_decode($prestasi->data_dinamis, true) : ($prestasi->data_dinamis ?? []);

        if (is_array($dataDinamis)) {
            foreach ($dataDinamis as $key => $val) {
                if (is_string($val) && Str::contains($val, 'prestasi/lampiran')) Storage::disk('public')->delete($val);
            }
        }
        $prestasi->delete();
        return back()->with('success', 'Data prestasi berhasil dihapus permanen.');
    }

    // ========================================================
    // 8. ALUR PERSETUJUAN & VALIDASI
    // ========================================================
    public function alurPersetujuan()
    {
        $alur = AlurPersetujuan::orderBy('urutan', 'asc')->get();
        return view('prestasi.alur_persetujuan', compact('alur'));
    }

    public function updateAlur(Request $request)
    {
        try {
            DB::beginTransaction();

            AlurPersetujuan::query()->update(['is_active' => false]);

            if ($request->has('is_active') && is_array($request->is_active)) {
                $activeIds = array_keys($request->is_active);
                AlurPersetujuan::whereIn('id', $activeIds)->update(['is_active' => true]);
            }

            DB::commit();
            return back()->with('success', 'Konfigurasi Alur Persetujuan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui konfigurasi: ' . $e->getMessage());
        }
    }

    public function validasiPrestasi(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        $pending = Prestasi::with(['user', 'formPrestasi', 'anggota', 'tingkatPrestasi', 'capaianPrestasi'])
            ->where('status', 'Pending')
            ->latest()
            ->paginate(10, ['*'], 'p_page')
            ->withQueryString();

        $validated = Prestasi::with(['user', 'formPrestasi', 'anggota', 'tingkatPrestasi', 'capaianPrestasi'])
            ->whereIn('status', ['Approved', 'Rejected'])
            ->latest()
            ->paginate(10, ['*'], 'v_page')
            ->withQueryString();

        return view('prestasi.validasi', compact('pending', 'validated', 'tab'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected,Pending',
            'pesan_revisi' => 'nullable|string'
        ]);

        $prestasi = Prestasi::findOrFail($id);

        $prestasi->update([
            'status' => $request->status,
            'pesan_revisi' => $request->status === 'Rejected' ? $request->pesan_revisi : null
        ]);

        $msg = $request->status === 'Approved' ? 'Prestasi berhasil disetujui!' : 'Prestasi telah ditolak.';
        return back()->with('success', $msg);
    }

    public function validasiMassal(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:prestasis,id'
        ]);

        Prestasi::whereIn('id', $request->ids)->update([
            'status' => 'Approved',
            'pesan_revisi' => null,
            'updated_at' => now()
        ]);

        return back()->with('success', count($request->ids) . ' Data prestasi berhasil disetujui secara massal!');
    }

    public function validasiShow($id)
    {
        $prestasi = Prestasi::with(['user.prodi.jurusan.fakultas', 'formPrestasi', 'anggota', 'tingkatPrestasi', 'capaianPrestasi'])
            ->findOrFail($id);

        $fields = \App\Models\FieldFormPrestasi::where('form_prestasi_id', $prestasi->form_prestasi_id)
            ->where('tipe', '!=', 'anggota_kelompok')
            ->orderBy('urutan', 'asc')
            ->get();

        return view('prestasi.validasi_show', compact('prestasi', 'fields'));
    }
}
