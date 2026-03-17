@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h3 class="text-xl font-black text-gray-800 tracking-tight">Atribut Prestasi</h3>
    <p class="text-xs text-gray-400 font-medium tracking-wide">Pengaturan parameter Jenis, Kategori, dan Tingkat Prestasi.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider">Jenis</h4>
            <button onclick="openModal('modal-tambah-jenis')" class="w-8 h-8 rounded-xl bg-[#006633] text-white flex items-center justify-center shadow-lg shadow-green-100"><i class="bi bi-plus"></i></button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-gray-50">
                    @foreach($jenis as $index => $item)
                    <tr class="group hover:bg-gray-50/50 transition-all">
                        <td class="px-6 py-4 text-xs font-black text-gray-800 uppercase">{{ $item->nama_jenis }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-end gap-2">
                                <button onclick="editAtribut('jenis', '{{ $item->id }}', '{{ $item->nama_jenis }}')" class="text-yellow-500 hover:text-yellow-600"><i class="bi bi-pencil-square"></i></button>
                                <form action="{{ route('admin.master-data.jenis.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Hapus?')"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider">Kategori</h4>
            <button onclick="openModal('modal-tambah-kategori')" class="w-8 h-8 rounded-xl bg-[#006633] text-white flex items-center justify-center shadow-lg shadow-green-100"><i class="bi bi-plus"></i></button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-gray-50">
                    @foreach($kategori as $item)
                    <tr class="group hover:bg-gray-50/50 transition-all">
                        <td class="px-6 py-4 text-xs font-black text-gray-800 uppercase">{{ $item->nama_kategori }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-end gap-2">
                                <button onclick="editAtribut('kategori', '{{ $item->id }}', '{{ $item->nama_kategori }}')" class="text-yellow-500 hover:text-yellow-600">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.master-data.kategori.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Hapus?')"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider">Tingkat</h4>
            <button onclick="openModal('modal-tambah-tingkat')" class="w-8 h-8 rounded-xl bg-[#006633] text-white flex items-center justify-center shadow-lg shadow-green-100"><i class="bi bi-plus"></i></button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <tbody class="divide-y divide-gray-50">
                    @foreach($tingkat as $item)
                    <tr class="group hover:bg-gray-50/50 transition-all">
                        <td class="px-6 py-4 text-xs font-black text-gray-800 uppercase">{{ $item->nama_tingkat }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-end gap-2">
                                <button onclick="editAtribut('tingkat', '{{ $item->id }}', '{{ $item->nama_tingkat }}')" class="text-yellow-500 hover:text-yellow-600">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.master-data.tingkat.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('Hapus?')"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.master-data-atribut-prestasi-modals')

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editAtribut(type, id, nama) {
        const form = document.getElementById('form-edit-atribut');
        const input = document.getElementById('edit-nama-atribut');
        const title = document.getElementById('edit-title');

        input.name = "nama_" + type;

        title.innerText = "Edit " + type.charAt(0).toUpperCase() + type.slice(1);

        input.value = nama;

        form.action = `/admin/master-data/${type}/${id}`;

        openModal('modal-edit-atribut');
    }
</script>
@endsection