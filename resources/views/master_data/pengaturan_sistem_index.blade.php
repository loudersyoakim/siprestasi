@extends('layouts.app')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Pengaturan Sistem</h3>
    </div>
</div>
{{-- ALERT NOTIFIKASI VALIDASI FORM (PENTING BUAT UPLOAD GAMBAR) --}}
@if($errors->any())
<div class="mb-6 p-4 text-sm font-bold text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm">
    <div class="flex items-center gap-2 mb-2">
        <i class="bi bi-x-octagon-fill text-lg"></i>
        <span>Formulir gagal disimpan karena:</span>
    </div>
    <ul class="list-disc list-inside ml-7 text-xs font-medium text-red-700">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
{{-- ALERT NOTIFIKASI --}}
@if(session('success'))
<div id="success-alert" class="mb-6 flex items-center justify-between p-4 text-sm font-bold text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm transition-opacity duration-500">
    <div class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-lg"></i><span>{{ session('success') }}</span></div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

@if(session('error'))
<div class="mb-6 flex items-center justify-between p-4 text-sm font-bold text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm">
    <div class="flex items-center gap-2"><i class="bi bi-exclamation-triangle-fill text-lg"></i><span>{{ session('error') }}</span></div>
    <button onclick="this.parentElement.style.display='none'" class="text-red-600 hover:text-red-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

{{-- FORM PENGATURAN UTAMA --}}
<form action="{{ route('pengaturan-sistem.update') }}" method="POST" enctype="multipart/form-data" class="pb-24">
    @csrf @method('PUT')

    {{-- Container dilebarkan: Penuh di layar kecil, 83% di layar Ultra Wide (xl) agar tetap nyaman dibaca --}}
    <div class="w-full xl:w-10/12 space-y-6">
        
        {{-- 1. IDENTITAS & LOGO --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="bi bi-building"></i> Identitas Kampus & Aplikasi
                </h4>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Nama Aplikasi</label>
                        <input type="text" name="nama_aplikasi" value="{{ $settings['nama_aplikasi']->nilai ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">
                        <p class="text-[10px] text-gray-400 mt-1.5 ml-1">{{ $settings['nama_aplikasi']->deskripsi ?? '' }}</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Nama Universitas</label>
                        <input type="text" name="nama_universitas" value="{{ $settings['nama_universitas']->nilai ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">
                    </div>
                </div>
                
                {{-- Upload Logo --}}
                <div class="pt-2 border-t border-gray-50">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-3">Logo Aplikasi (Format: PNG/JPG/SVG)</label>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="w-20 h-20 rounded-2xl border border-gray-200 flex items-center justify-center bg-gray-50 overflow-hidden shrink-0 shadow-inner">
                            @php $logoPath = isset($settings['logo_aplikasi']) && $settings['logo_aplikasi']->nilai != 'logo-unimed.png' ? asset('storage/' . $settings['logo_aplikasi']->nilai) : asset('img/logo-unimed.png'); @endphp
                            <img id="preview-logo" src="{{ $logoPath }}" alt="Logo" class="w-full h-full object-contain p-2">
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" name="logo_aplikasi" id="logo_aplikasi" accept="image/*" onchange="previewImage(event)" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#006633] file:text-white hover:file:bg-[#004d26] cursor-pointer border border-gray-200 rounded-2xl bg-white transition-colors shadow-sm">
                            <p class="text-[10px] text-gray-400 mt-2 ml-1">{{ $settings['logo_aplikasi']->deskripsi ?? 'Max ukuran file 2MB. Logo akan ditampilkan di sidebar menu.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. KONTEN LANDING PAGE --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="bi bi-window-sidebar"></i> Konten Landing Page
                </h4>
            </div>
            <div class="p-6 space-y-6">
                {{-- Grid dibagi 3 untuk Title 1, 2, dan 3 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Teks Judul 1 (Warna Hitam)</label>
                        <input type="text" name="hero_title_1" value="{{ $settings['hero_title_1']->nilai ?? '' }}" placeholder="Contoh: Sistem Arsip" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Teks Judul 2 (Warna Hijau)</label>
                        <input type="text" name="hero_title_2" value="{{ $settings['hero_title_2']->nilai ?? '' }}" placeholder="Contoh: Prestasi" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none text-[#006633] font-bold bg-green-50/30 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Teks Judul 3 (Warna Hitam)</label>
                        <input type="text" name="hero_title_3" value="{{ $settings['hero_title_3']->nilai ?? '' }}" placeholder="Contoh: Mahasiswa" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Deskripsi Landing Page</label>
                    <textarea name="deskripsi_landing" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">{{ $settings['deskripsi_landing']->nilai ?? '' }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1.5 ml-1">{{ $settings['deskripsi_landing']->deskripsi ?? '' }}</p>
                </div>
            </div>
        </div>

        {{-- 3. KONTAK & BANTUAN --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="bi bi-headset"></i> Kontak & Bantuan
                </h4>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Email Kampus / Helpdesk</label>
                        <input type="email" name="email_kampus" value="{{ $settings['email_kampus']->nilai ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="kontak_telepon" value="{{ $settings['kontak_telepon']->nilai ?? '' }}" placeholder="+628..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Pesan Jam Operasional Bantuan</label>
                    <textarea name="pesan_bantuan" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-[#006633] outline-none transition-colors">{{ $settings['pesan_bantuan']->nilai ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- 4. ATURAN SISTEM --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest flex items-center gap-2">
                    <i class="bi bi-shield-lock"></i> Aturan Sistem & Login
                </h4>
            </div>
            <div class="p-6">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Aktivasi Akun Mahasiswa Baru</label>
                {{-- Menghapus batasan max-w agar dropdown melar penuh secara responsif --}}
                <select name="wajib_aktivasi_mahasiswa" class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-700 focus:border-[#006633] outline-none cursor-pointer transition-colors shadow-sm appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%239CA3AF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:12px_12px] bg-[position:right_1rem_center] bg-no-repeat pr-10">
                    <option value="1" {{ (isset($settings['wajib_aktivasi_mahasiswa']) && $settings['wajib_aktivasi_mahasiswa']->nilai == '1') ? 'selected' : '' }}>Wajib (Perlu Persetujuan Admin sebelum bisa login)</option>
                    <option value="0" {{ (isset($settings['wajib_aktivasi_mahasiswa']) && $settings['wajib_aktivasi_mahasiswa']->nilai == '0') ? 'selected' : '' }}>Otomatis Aktif (Bisa langsung login setelah daftar)</option>
                </select>
            </div>
        </div>

    </div>

    {{-- TOMBOL SUBMIT FLOATING --}}
    <div class="fixed bottom-0 left-0 md:left-72 right-0 p-4 bg-white border-t border-gray-200 flex justify-end z-40 shadow-[0_-4px_15px_-3px_rgba(0,0,0,0.05)]">
        <button type="submit" class="bg-[#006633] text-white px-10 py-3.5 rounded-xl text-sm font-bold shadow-lg shadow-green-200 hover:bg-[#004d26] hover:-translate-y-0.5 transition-all flex items-center gap-2"> Simpan Pemngaturan
        </button>
    </div>
</form>

<script>
    // Preview gambar logo yang mau diupload
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('preview-logo');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Auto dismiss alert dengan transisi halus
    const alert = document.getElementById('success-alert');
    if(alert) {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.style.display = 'none', 500);
        }, 4000);
    }
</script>

@endsection