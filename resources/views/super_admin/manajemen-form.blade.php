@extends('layouts.app')

@section('content')

@php
    $routePrefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Manajemen Form Prestasi</h3>
    </div>

    {{-- Tombol Tambah Menggunakan Modal agar Cepat --}}
    <button onclick="openModal('modal-tambah-kategori')" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-[#004d26] transition-colors shadow-sm cursor-pointer">
        <i class="bi bi-plus-square-fill"></i>
        <span>Buat Formulir Baru</span>
    </button>
</div>

{{-- ALERT NOTIFIKASI --}}
@if(session('success'))
<div id="success-alert" class="mb-6 flex items-center justify-between p-4 text-sm font-bold text-green-800 rounded-xl bg-green-50 border border-green-200 transition-opacity duration-500">
    <div class="flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

{{-- TABEL DATA KATEGORI FORM --}}
<div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col mb-8 overflow-hidden relative">
    
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Kategori & Skema Pelaporan</h4>
    </div>

    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="w-16 px-6 py-3 text-gray-400 text-[10px] uppercase font-bold tracking-wider text-center">No</th>
                    <th class="px-6 py-3 text-gray-400 text-[10px] uppercase font-bold tracking-wider">Nama Kategori & Deskripsi</th>
                    <th class="px-6 py-3 text-gray-400 text-[10px] uppercase font-bold tracking-wider text-center">Jumlah Field</th>
                    <th class="px-6 py-3 text-gray-400 text-[10px] uppercase font-bold tracking-wider text-center">Status</th>
                    <th class="px-6 py-3 text-gray-400 text-[10px] uppercase font-bold tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($kategori as $index => $item)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 text-center text-gray-400 font-medium">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $item->nama_kategori }}</div>
                        <div class="text-xs text-gray-500 mt-0.5 max-w-md truncate">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $item->fields_count }} Pertanyaan
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->deleted_at == null)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase text-green-700 bg-green-50 px-2 py-1 rounded border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase text-red-700 bg-red-50 px-2 py-1 rounded border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Non-Aktif
                            </span>
                        @endif
                    </td>
                    {{-- Ganti bagian aksi di dalam loop forelse --}}
<td class="px-6 py-4 text-center">
    <div class="flex items-center justify-center gap-2">
        {{-- LIHAT & ATUR FORM (Mata) --}}
        <a href="{{ route($routePrefix . '.manajemen-form.show', $item->id) }}" 
           class="w-9 h-9 rounded-xl bg-green-50 text-[#006633] hover:bg-[#006633] hover:text-white flex items-center justify-center transition-all shadow-sm group/btn" 
           title="Lihat & Atur Pertanyaan">
            <i class="bi bi-eye-fill text-lg"></i>
        </a>

        {{-- EDIT KATEGORI (Pensil) --}}
        <button onclick="openEditKategori('{{ $item->id }}', '{{ $item->nama_kategori }}', '{{ $item->deskripsi }}')" 
                class="w-9 h-9 rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-all shadow-sm" 
                title="Edit Nama/Deskripsi">
            <i class="bi bi-pencil-square text-lg"></i>
        </button>

        {{-- HAPUS (Trash) --}}
        <form action="{{ route($routePrefix . '.manajemen-form.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan formulir ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm">
                <i class="bi bi-trash3-fill text-lg"></i>
            </button>
        </form>
    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Belum ada kategori formulir yang dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div id="modal-tambah-kategori" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-black text-gray-800">Buat Formulir Baru</h3>
            <button onclick="closeModal('modal-tambah-kategori')" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route($routePrefix . '.manajemen-form.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required placeholder="Contoh: Lomba Akademik"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan tujuan formulir ini..."
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all bg-gray-50"></textarea>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-tambah-kategori')" class="px-5 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-[#006633] text-white rounded-xl text-sm font-bold hover:bg-[#004d26] shadow-sm">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
{{-- MODAL EDIT KATEGORI --}}
<div id="modal-edit-kategori" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-black text-gray-800">Edit Kategori Form</h3>
            <button onclick="closeModal('modal-edit-kategori')" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-edit-kategori" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit-nama" required 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="edit-deskripsi" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all bg-gray-50"></textarea>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-edit-kategori')" class="px-5 py-2.5 text-sm font-bold text-gray-500">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white rounded-xl text-sm font-bold hover:bg-yellow-600 shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditKategori(id, nama, deskripsi) {
        const form = document.getElementById('form-edit-kategori');
        form.action = `/{{ Auth::user()->role === 'super_admin' ? 'super-admin' : 'admin' }}/manajemen-form/${id}`;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-deskripsi').value = deskripsi;
        openModal('modal-edit-kategori');
    }
</script>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Auto-hide alert
    const alert = document.getElementById('success-alert');
    if(alert) {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.style.display = 'none', 500);
        }, 4000);
    }
</script>

@endsection