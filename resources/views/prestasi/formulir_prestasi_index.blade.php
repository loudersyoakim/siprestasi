@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Formulir Prestasi</h3>
    </div>
    <button onclick="openModal('modal-tambah-kategori')" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#004d26] transition-colors shadow-sm cursor-pointer">
        <i class="bi bi-plus-lg"></i> <span>Buat Formulir Baru</span>
    </button>
</div>

@if(session('success'))
<div id="success-alert" class="mb-6 flex items-center justify-between p-4 text-sm font-bold text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm">
    <div class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-lg"></i><span>{{ session('success') }}</span></div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

<div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col mb-8 overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Daftar Kategori Formulir</h4>
    </div>
    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="w-16 px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider text-center">No</th>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider">Nama Kategori & Deskripsi</th>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider text-center">Jumlah Field</th>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($kategori as $index => $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-center text-gray-400 font-medium">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $item->nama_form }}</div>
                        <div class="text-xs text-gray-500 mt-0.5 truncate max-w-md">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $item->fields_count }} Pertanyaan
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('prestasi.formulir-prestasi.show', $item->id) }}" class="w-8 h-8 rounded-lg bg-green-50 text-[#006633] hover:bg-[#006633] hover:text-white flex items-center justify-center transition-all shadow-sm" title="Lihat & Atur Pertanyaan"><i class="bi bi-eye-fill"></i></a>
                            <button onclick="openEditKategori('{{ $item->id }}', '{{ addslashes($item->nama_form) }}', '{{ addslashes($item->deskripsi) }}')" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Edit"><i class="bi bi-pencil-square"></i></button>
                            <form action="{{ route('prestasi.formulir-prestasi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus formulir ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Belum ada kategori formulir.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modal-tambah-kategori" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-3xl">
            <h3 class="text-lg font-black text-gray-800 tracking-tight uppercase">Buat Formulir Baru</h3>
            <button onclick="closeModal('modal-tambah-kategori')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('prestasi.formulir-prestasi.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_form" required placeholder="Contoh: Lomba Akademik" class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-[#006633] outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan tujuan formulir ini..." class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-[#006633] outline-none"></textarea>
                </div>
            </div>
            <div class="p-6 pt-2 rounded-b-3xl flex gap-3">
                <button type="button" onclick="closeModal('modal-tambah-kategori')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26] shadow-lg shadow-green-100">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modal-edit-kategori" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-3xl">
            <h3 class="text-lg font-black text-gray-800 tracking-tight uppercase">Edit Kategori</h3>
            <button onclick="closeModal('modal-edit-kategori')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-edit-kategori" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_form" id="edit-nama" required class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-[#006633] outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="edit-deskripsi" rows="3" class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-[#006633] outline-none"></textarea>
                </div>
            </div>
            <div class="p-6 pt-2 rounded-b-3xl flex gap-3">
                <button type="button" onclick="closeModal('modal-edit-kategori')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26] shadow-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditKategori(id, nama, deskripsi) {
        document.getElementById('form-edit-kategori').action = `/prestasi/formulir-prestasi/${id}`;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-deskripsi').value = deskripsi;
        openModal('modal-edit-kategori');
    }
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }
</script>
@endsection