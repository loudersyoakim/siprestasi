@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Fakultas</h3>
    </div>

    <div class="flex gap-3">
        <button onclick="openModal('modal-tambah-fakultas')" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-green-100 hover:bg-[#004d26] transition-all">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Fakultas</span>
        </button>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Daftar Fakultas</h4>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50">
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Fakultas</th>
                    <th class="px-6 py-4 text-center">Total Jurusan</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($fakultas as $index => $f)
                <tr class="group hover:bg-gray-50/80 transition-all">
                    <td class="px-6 py-4 text-sm font-bold text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-black text-gray-800 tracking-tight">{{ $f->nama_fakultas }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 font-black text-gray-800 text-[14px] ">
                            {{ $f->jurusan_count ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button"
                                onclick="editFakultas('{{ $f->id }}', '{{ $f->nama_fakultas }}')"
                                class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors tooltip"
                                title="Edit Fakultas">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <form action="{{ route('admin.master-data.fakultas.destroy', $f->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus fakultas {{ $f->nama_fakultas }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip"
                                    title="Hapus Fakultas">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center">
                        <i class="bi bi-building-exclamation text-4xl text-gray-200 mb-3 block"></i>
                        <p class="text-sm font-bold text-gray-400">Belum ada data fakultas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($fakultas->hasPages())
        <div class="p-4 border-t border-gray-50 bg-gray-50/30">
            {{ $fakultas->links() }}
        </div>
        @endif
    </div>
</div>

<div id="modal-tambah-fakultas" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 bg-[#006633] text-white flex justify-between items-center">
            <h4 class="font-black uppercase tracking-tight">Tambah Fakultas</h4>
            <button onclick="closeModal('modal-tambah-fakultas')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="#" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fakultas</label>
                <input type="text" name="nama_fakultas" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all" placeholder="">
            </div>
            <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-100">Simpan Data</button>
        </form>
    </div>
</div>
<div id="modal-edit-fakultas" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 bg-blue-600 text-white flex justify-between items-center">
            <h4 class="font-black uppercase tracking-tight italic">Edit Fakultas</h4>
            <button onclick="closeModal('modal-edit-fakultas')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-edit-fakultas" action="" method="POST" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fakultas</label>
                <input type="text" name="nama_fakultas" id="edit-nama-fakultas" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-blue-600 outline-none transition-all">
            </div>
            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-100">Simpan Perubahan</button>
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

    function editFakultas(id, nama) {
        const form = document.getElementById('form-edit-fakultas');
        const inputNama = document.getElementById('edit-nama-fakultas');

        // Set Action URL: admin/master-data/fakultas/{id}
        form.action = `/admin/master-data/fakultas/${id}`;
        inputNama.value = nama;

        openModal('modal-edit-fakultas');
    }
</script>
@endsection