@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('admin.manajemen-konten') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Edit Konten</h3>
        <p class="text-xs text-gray-500">Ubah detail berita atau informasi yang sudah dipublish.</p>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('admin.manajemen-konten.update', $konten->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Utama --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Judul --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Judul Konten</label>
                    <input type="text" name="title" value="{{ old('title', $konten->title) }}" required placeholder="Masukkan judul..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all font-bold text-gray-800">
                </div>

                {{-- Isi Konten --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Isi Artikel</label>
                    <div class="editor-container">
                        <textarea name="content" id="editor">{{ old('content', $konten->content) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Pengaturan --}}
            <div class="space-y-6">
                <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100 space-y-4">
                    {{-- Kategori --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Konten</label>
                        <select name="category" required
                            class="w-full px-4 py-3 border border-gray-100 bg-gray-50 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="berita">Berita </option>
                            <option value="prestasi">Prestasi Mahasiswa</option>
                            <option value="lomba">Informasi Lomba</option>
                            <option value="pengumuman">Pengumuman Resmi</option>
                        </select>
                        @error('category')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thumbnail Saat Ini --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Thumbnail Saat Ini</label>
                        <div class="relative aspect-video rounded-2xl overflow-hidden border border-gray-200 bg-white">
                            @if($konten->thumbnail)
                            <img src="{{ asset('storage/'.$konten->thumbnail) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 italic text-[10px]">Tidak ada gambar</div>
                            @endif
                        </div>
                    </div>

                    {{-- Upload Baru --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ganti Thumbnail (Opsional)</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-gray-200 file:text-gray-700 hover:file:bg-[#006633] hover:file:text-white transition-all">
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <input type="checkbox" name="is_published" value="1" id="publish" {{ $konten->is_published ? 'checked' : '' }} class="w-4 h-4 text-[#006633] rounded focus:ring-[#006633]">
                        <label for="publish" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Terbitkan Konten</label>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl font-bold shadow-lg shadow-green-100 hover:bg-black transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                    <i class="bi bi-cloud-check-fill text-lg"></i>
                    Simpan Perubahan
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
    .ck-editor__editable {
        min-height: 400px !important;
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
        box-shadow: none !important;
    }
</style>
@endsection