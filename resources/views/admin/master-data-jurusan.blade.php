@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight"> Jurusan</h3>
    </div>

    <div class="flex gap-3">
        <button onclick="openModal('modal-tambah-jurusan')" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-green-100 hover:bg-[#004d26] transition-all uppercase tracking-wider">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Jurusan</span>
        </button>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Daftar Jurusan</h4>

    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50">
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <th class="px-6 py-4 text-left">No</th>
                    <th class="px-6 py-4 text-left">Jurusan</th>
                    <th class="px-6 py-4 text-left">Fakultas</th>
                    <th class="px-6 py-4 text-center">Total Prodi</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($jurusan as $index => $j)
                <tr class="group hover:bg-gray-50/80 transition-all text-sm">
                    <td class="px-6 py-4 text-left font-black text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-left font-black text-gray-800 uppercase tracking-tight">
                        {{ $j->nama_jurusan }}
                    </td>
                    <td class="px-6 py-4 text-left font-black text-gray-600">
                        {{ $j->fakultas->nama_fakultas }}
                    </td>
                    <td class="px-6 py-4 text-center font-black text-gray-800 text-[14px] ">
                        {{ $j->prodi_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button"
                                onclick="editJurusan('{{ $j->id }}', '{{ $j->nama_jurusan }}', '{{ $j->fakultas_id }}')"
                                class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors tooltip"
                                title="Edit Jurusan">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <form action="{{ route('admin.master-data.jurusan.destroy', $j->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jurusan {{ $j->nama_jurusan }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip"
                                    title="Hapus Jurusan">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <i class="bi bi-diagram-3 text-4xl text-gray-200 mb-3 block"></i>
                        <p class="text-sm font-bold text-gray-400">Belum ada data jurusan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($jurusan->hasPages())
        <div class="p-4 border-t border-gray-50 bg-gray-50/30">
            {{ $jurusan->links() }}
        </div>
        @endif
    </div>
</div>

<div id="modal-tambah-jurusan" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 bg-[#006633] text-white flex justify-between items-center">
            <h4 class="font-black uppercase tracking-tight italic">Tambah Jurusan</h4>
            <button onclick="closeModal('modal-tambah-jurusan')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.master-data.jurusan.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Fakultas</label>
                <select name="fakultas_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-[#006633] outline-none transition-all cursor-pointer bg-white text-gray-700">
                    <option value="" disabled selected>-- Pilih Fakultas --</option>
                    @foreach($fakultas as $f)
                    <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jurusan</label>
                <input type="text" name="nama_jurusan" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-[#006633] outline-none transition-all" placeholder="">
            </div>
            <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-100">Simpan Data</button>
        </form>
    </div>
</div>

<div id="modal-edit-jurusan" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 bg-blue-500 text-white flex justify-between items-center">
            <h4 class="font-black uppercase tracking-tight italic">Edit Jurusan</h4>
            <button onclick="closeModal('modal-edit-jurusan')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-edit-jurusan" action="" method="POST" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Fakultas</label>
                <select name="fakultas_id" id="edit-fakultas-id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-blue-500 outline-none transition-all bg-white text-gray-700">
                    @foreach($fakultas as $f)
                    <option value="{{ $f->id }}">{{ $f->nama_fakultas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jurusan</label>
                <input type="text" name="nama_jurusan" id="edit-nama-jurusan" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-blue-500 outline-none transition-all">
            </div>
            <button type="submit" class="w-full py-4 bg-blue-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-100">Simpan Perubahan</button>
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

    function editJurusan(id, nama, fakultasId) {
        const form = document.getElementById('form-edit-jurusan');
        const inputNama = document.getElementById('edit-nama-jurusan');
        const selectFakultas = document.getElementById('edit-fakultas-id');

        form.action = `/admin/master-data/jurusan/${id}`;
        inputNama.value = nama;
        selectFakultas.value = fakultasId;

        openModal('modal-edit-jurusan');
    }
</script>
@endsection