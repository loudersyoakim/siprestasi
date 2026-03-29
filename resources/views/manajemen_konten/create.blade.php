@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('konten.index') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h3 class="text-2xl font-black text-gray-800 tracking-tight mt-1">Tambah Konten</h3>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('konten.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10 space-y-8 max-w-4xl mx-auto">
        @csrf

        {{-- Judul --}}
        <div class="space-y-2">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Judul Konten</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul berita..."
                class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-lg focus:ring-2 focus:ring-[#006633]/20 focus:bg-white transition-all font-bold text-gray-800">
            @error('judul') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Kategori & Thumbnail --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                <select name="kategori" required class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#006633]/20 focus:bg-white outline-none font-bold transition-all cursor-pointer">
                    <option value="berita">Berita</option>
                    <option value="informasi">Informasi</option>
                    <option value="pengumuman">Pengumuman</option>
                    <option value="prestasi">Prestasi</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Thumbnail Konten</label>
                <div class="flex items-center gap-4">
                    {{-- Kotak Preview --}}
                    <div class="w-16 h-14 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden shrink-0 shadow-inner">
                        <img id="image-preview" src="https://ui-avatars.com/api/?name=Img&background=f3f4f6&color=9ca3af" class="w-full h-full object-cover">
                    </div>
                    {{-- Input File --}}
                    <div class="flex-1 min-w-0">
                        <input type="file" name="gambar_cover" id="image-input" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-[#006633] file:text-white hover:file:bg-black transition-all cursor-pointer">
                        <p class="text-[9px] text-gray-400 italic mt-1.5">*Maksimal 2MB (JPG/PNG).</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Isi Konten --}}
        <div class="space-y-2">
            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Isi Artikel</label>
            <textarea name="isi_konten" id="editor">{{ old('isi_konten') }}</textarea>
            @error('isi_konten') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Toggle Publish --}}
        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-2xl border border-gray-100">
            <div>
                <h4 class="text-sm font-bold text-gray-800">Publikasikan Konten</h4>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Aktifkan agar langsung muncul di beranda utama</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_aktif" value="1" class="sr-only peer" checked>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#006633]"></div>
            </label>
        </div>

        <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl font-bold shadow-xl shadow-green-900/20 hover:bg-black transition-all uppercase tracking-[0.2em] text-xs">
            Simpan & Tayangkan
        </button>
    </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Inisialisasi CKEditor
    ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));

    // Logika Simple Preview Gambar
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('image-preview').src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<style>
    .ck-editor__editable { min-height: 400px !important; border-radius: 0 0 20px 20px !important; border-color: #f3f4f6 !important; font-size: 14px; }
    .ck-toolbar { border-radius: 20px 20px 0 0 !important; border-color: #f3f4f6 !important; background: #f9fafb !important; }
    .ck.ck-editor__main>.ck-editor__editable.ck-focused { border-color: #006633 !important; box-shadow: none !important; }
</style>
@endsection