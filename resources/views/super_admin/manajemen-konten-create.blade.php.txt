@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('admin.manajemen-konten') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Tulis Konten Baru</h3>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('admin.manajemen-konten.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Utama --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Judul --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Judul Konten</label>
                    <input type="text" name="title" required placeholder="Masukkan judul berita atau informasi..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all font-bold text-gray-800">
                </div>

                {{-- Isi Konten --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Isi Artikel</label>
                    <div class="editor-container">
                        <textarea name="content" id="editor"></textarea>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Pengaturan --}}
            <div class="space-y-6">
                {{-- Kategori --}}
                <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100 space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Konten</label>
                        <select name="category" required
                            class="w-full px-4 py-3 border border-gray-100 bg-gray-50 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="berita">Berita</option>
                            <option value="prestasi">Prestasi Mahasiswa</option>
                            <option value="lomba">Informasi Lomba</option>
                            <option value="pengumuman">Pengumuman Resmi</option>
                        </select>
                        @error('category')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Thumbnail (Gambar)</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#006633] file:text-white hover:file:bg-black transition-all">
                        <p class="text-[9px] text-gray-400 italic mt-1">*Format: JPG, PNG, Max 2MB</p>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" name="is_published" value="1" id="publish" class="w-4 h-4 text-[#006633] rounded">
                        <label for="publish" class="text-xs font-bold text-gray-700">Terbitkan Sekarang?</label>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl font-bold shadow-lg shadow-green-100 hover:bg-black transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                    <i class="bi bi-send-fill"></i>
                    Simpan Konten
                </button>
            </div>
        </div>
    </form>
</div>

{{-- CKEditor 5 --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
        })
        .catch(error => {
            console.error(error);
        });
</script>

<style>
    /* Custom style agar editor terlihat menyatu dengan tema */
    .ck-editor__editable {
        min-height: 300px !important;
        border-radius: 0 0 20px 20px !important;
        border-color: #f3f4f6 !important;
        font-size: 14px;
    }

    .ck-toolbar {
        border-radius: 20px 20px 0 0 !important;
        border-color: #f3f4f6 !important;
        background: #f9fafb !important;
    }

    .ck.ck-editor__main>.ck-editor__editable.ck-focused {
        border-color: #fbbf24 !important;
        /* Fokus warna kuning seperti form lainnya */
        box-shadow: none !important;
    }
</style>
@endsection