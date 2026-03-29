@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h3 class="text-2xl font-black text-gray-800 tracking-tight">Atribut Prestasi</h3>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

    {{-- KOTAK TINGKAT PRESTASI --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-max">
        <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <div>
                <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider mb-0.5">Tingkat Prestasi</h4>
                <p class="text-[10px] text-gray-400">Contoh: Internasional, Nasional, Wilayah.</p>
            </div>
            <button onclick="openModal('modal-tingkat')" class="w-8 h-8 rounded-xl bg-[#006633] text-white flex items-center justify-center shadow-md hover:bg-black transition-all">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <tbody class="divide-y divide-gray-50">
                    @forelse($tingkat as $item)
                    <tr class="group hover:bg-gray-50/50 transition-all">
                        {{-- Padding diperkecil jadi py-2.5 agar lebih rapat --}}
                        <td class="px-5 py-2.5 text-xs font-bold text-gray-800 uppercase">{{ $item->nama_tingkat }}</td>
                        <td class="px-5 py-2.5 text-right w-24">
                            <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                {{-- Tombol Edit --}}
                                <button onclick="editAtribut('tingkat', '{{ $item->id }}', '{{ $item->nama_tingkat }}')" class="p-1.5 text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                    <i class="bi bi-pencil-square text-sm"></i>
                                </button>
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('master.tingkat.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" onclick="return confirm('Hapus tingkat ini?')">
                                        <i class="bi bi-trash3-fill text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="p-5 text-center text-xs text-gray-400 italic">Belum ada data tingkat prestasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- KOTAK CAPAIAN PRESTASI --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-max">
        <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <div>
                <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider mb-0.5">Capaian Prestasi</h4>
                <p class="text-[10px] text-gray-400">Contoh: Juara 1, Medali Emas, Lulus.</p>
            </div>
            <button onclick="openModal('modal-capaian')" class="w-8 h-8 rounded-xl bg-[#006633] text-white flex items-center justify-center shadow-md hover:bg-black transition-all">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <tbody class="divide-y divide-gray-50">
                    @forelse($capaian as $item)
                    <tr class="group hover:bg-gray-50/50 transition-all">
                        {{-- Padding diperkecil jadi py-2.5 agar lebih rapat --}}
                        <td class="px-5 py-2.5 text-xs font-bold text-gray-800 uppercase">{{ $item->nama_capaian }}</td>
                        <td class="px-5 py-2.5 text-right w-24">
                            <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                {{-- Tombol Edit --}}
                                <button onclick="editAtribut('capaian', '{{ $item->id }}', '{{ $item->nama_capaian }}')" class="p-1.5 text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                    <i class="bi bi-pencil-square text-sm"></i>
                                </button>
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('master.capaian.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" onclick="return confirm('Hapus capaian ini?')">
                                        <i class="bi bi-trash3-fill text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="p-5 text-center text-xs text-gray-400 italic">Belum ada data capaian prestasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH TINGKAT --}}
<div id="modal-tingkat" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-[2rem] w-full max-w-sm shadow-2xl overflow-hidden transform transition-all">
        <form action="{{ route('master.tingkat.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-base font-black text-gray-800 tracking-tight">Tambah Tingkat</h3>
                    <button type="button" onclick="closeModal('modal-tingkat')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-2">Nama Tingkat</label>
                    <input type="text" name="nama_tingkat" required placeholder="Contoh: Internasional" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] outline-none transition-all">
                </div>
            </div>
            <div class="bg-gray-50 p-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-tingkat')" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">Batal</button>
                <button type="submit" class="px-5 py-2 bg-[#006633] text-white text-xs font-bold rounded-xl hover:bg-black transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH CAPAIAN --}}
<div id="modal-capaian" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-[2rem] w-full max-w-sm shadow-2xl overflow-hidden transform transition-all">
        <form action="{{ route('master.capaian.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-base font-black text-gray-800 tracking-tight">Tambah Capaian</h3>
                    <button type="button" onclick="closeModal('modal-capaian')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-2">Nama Capaian</label>
                    <input type="text" name="nama_capaian" required placeholder="Contoh: Juara 1" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] outline-none transition-all">
                </div>
            </div>
            <div class="bg-gray-50 p-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-capaian')" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">Batal</button>
                <button type="submit" class="px-5 py-2 bg-[#006633] text-white text-xs font-bold rounded-xl hover:bg-black transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT DINAMIS (Satu modal untuk Tingkat & Capaian) --}}
<div id="modal-edit-atribut" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-[2rem] w-full max-w-sm shadow-2xl overflow-hidden transform transition-all">
        <form id="form-edit-atribut" method="POST">
            @csrf @method('PUT')
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 id="edit-title" class="text-base font-black text-gray-800 tracking-tight">Edit Atribut</h3>
                    <button type="button" onclick="closeModal('modal-edit-atribut')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <div>
                    <label id="edit-label" class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-2">Nama Atribut</label>
                    <input type="text" id="edit-nama-atribut" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] outline-none transition-all">
                </div>
            </div>
            <div class="bg-gray-50 p-4 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-edit-atribut')" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700">Batal</button>
                <button type="submit" class="px-5 py-2 bg-yellow-500 text-white text-xs font-bold rounded-xl hover:bg-yellow-600 transition-all">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Fungsi canggih untuk handle Edit Tingkat & Capaian di 1 Modal
    function editAtribut(type, id, nama) {
        const form = document.getElementById('form-edit-atribut');
        const input = document.getElementById('edit-nama-atribut');
        const title = document.getElementById('edit-title');
        const label = document.getElementById('edit-label');

        // Set nama input (nama_tingkat / nama_capaian)
        input.name = "nama_" + type;
        
        // Ganti teks judul & label sesuai jenis yang diedit
        const typeCapitalized = type.charAt(0).toUpperCase() + type.slice(1);
        title.innerText = "Edit " + typeCapitalized;
        label.innerText = "Nama " + typeCapitalized;

        // Isi form dengan nama saat ini
        input.value = nama;

        // Tembak URL tujuan sesuai route web.php
        form.action = `/master/atribut-prestasi/${type}/${id}`;

        openModal('modal-edit-atribut');
    }
</script>
@endsection