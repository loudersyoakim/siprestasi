@extends('layouts.app')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Struktur Akademik</h3>
    </div>
</div>

{{-- ================= ALERT NOTIFIKASI ================= --}}
@if(session('success'))
<div class="auto-dismiss-alert mb-6 flex items-center justify-between p-4 text-sm font-bold text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm transition-opacity duration-500">
    <div class="flex items-center gap-2"><i class="bi bi-check-circle-fill text-lg"></i><span>{{ session('success') }}</span></div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

@if(session('error'))
<div class="auto-dismiss-alert mb-6 flex items-center justify-between p-4 text-sm font-bold text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm transition-opacity duration-500">
    <div class="flex items-center gap-2"><i class="bi bi-exclamation-triangle-fill text-lg"></i><span>{{ session('error') }}</span></div>
    <button onclick="this.parentElement.style.display='none'" class="text-red-600 hover:text-red-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif
{{-- ==================================================== --}}

{{-- TAB NAVIGASI --}}
<div class="flex items-center gap-6 mb-4 border-b border-gray-200 px-2">
    <a href="{{ route('super_admin.struktur-akademik', ['tab' => 'fakultas']) }}" 
       class="pb-3 text-sm font-bold transition-all relative {{ $tab === 'fakultas' ? 'text-[#006633] border-b-2 border-[#006633]' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="bi bi-building mr-1.5"></i> Fakultas
    </a>
    <a href="{{ route('super_admin.struktur-akademik', ['tab' => 'jurusan']) }}" 
       class="pb-3 text-sm font-bold transition-all relative {{ $tab === 'jurusan' ? 'text-[#006633] border-b-2 border-[#006633]' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="bi bi-diagram-3 mr-1.5"></i> Jurusan
    </a>
    <a href="{{ route('super_admin.struktur-akademik', ['tab' => 'prodi']) }}" 
       class="pb-3 text-sm font-bold transition-all relative {{ $tab === 'prodi' ? 'text-[#006633] border-b-2 border-[#006633]' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="bi bi-journal-bookmark mr-1.5"></i> Program Studi
    </a>
</div>

{{-- KONTEN TABEL --}}
<div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col mb-8 overflow-hidden relative">

    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Daftar {{ ucfirst($tab) }}</h4>
        <button onclick="openModal('modal-tambah-{{ $tab }}')" class="bg-[#006633] text-white px-5 py-2.5 rounded-lg text-xs font-bold hover:bg-[#004d26] transition-colors shadow-sm flex items-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Baru
        </button>
    </div>

    <div class="w-full overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider w-16 align-middle">No</th>
                    @if($tab === 'fakultas')
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Kode</th>
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Nama Fakultas</th>
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Singkatan</th>
                    @elseif($tab === 'jurusan')
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Fakultas</th>
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Nama Jurusan</th>
                    @elseif($tab === 'prodi')
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Jurusan</th>
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Kode Prodi</th>
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Program Studi</th>
                        <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Jenjang</th>
                    @endif
                    <th class="px-6 py-4 text-center text-gray-400 text-[10px] uppercase font-bold tracking-wider w-32 align-middle">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm bg-white">
                
                {{-- KONTEN FAKULTAS --}}
                @if($tab === 'fakultas')
                    @forelse($fakultas as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 font-medium align-middle">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-black text-[#006633] align-middle">{{ $item->kode_fakultas }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800 align-middle">{{ $item->nama_fakultas }}</td>
                        <td class="px-6 py-4 text-gray-600 font-medium align-middle">{{ $item->singkatan ?? '-' }}</td>
                        <td class="px-6 py-4 align-middle text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openModal('modal-edit-fakultas-{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors"><i class="bi bi-pencil-square text-lg"></i></button>
                                <form action="{{ route('fakultas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus Fakultas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors"><i class="bi bi-trash3 text-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data Fakultas.</td></tr>
                    @endforelse
                
                {{-- KONTEN JURUSAN --}}
                @elseif($tab === 'jurusan')
                    @forelse($jurusans as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 font-medium align-middle">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-gray-600 align-middle">
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200 rounded-md">{{ $item->fakultas->singkatan ?? $item->fakultas->kode_fakultas }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800 align-middle">{{ $item->nama_jurusan }}</td>
                        <td class="px-6 py-4 align-middle text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openModal('modal-edit-jurusan-{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors"><i class="bi bi-pencil-square text-lg"></i></button>
                                <form action="{{ route('jurusan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus Jurusan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors"><i class="bi bi-trash3 text-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data Jurusan.</td></tr>
                    @endforelse
                
                {{-- KONTEN PRODI --}}
                @elseif($tab === 'prodi')
                    @forelse($prodis as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 font-medium align-middle">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-gray-600 align-middle text-xs">
                            <div class="font-bold">{{ $item->jurusan->nama_jurusan }}</div>
                            <div class="text-[10px] text-gray-400">{{ $item->jurusan->fakultas->nama_fakultas }}</div>
                        </td>
                        <td class="px-6 py-4 font-black text-[#006633] align-middle">{{ $item->kode_prodi }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800 align-middle">{{ $item->nama_prodi }}</td>
                        <td class="px-6 py-4 text-gray-600 align-middle"><span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold">{{ $item->jenjang }}</span></td>
                        <td class="px-6 py-4 align-middle text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openModal('modal-edit-prodi-{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors"><i class="bi bi-pencil-square text-lg"></i></button>
                                <form action="{{ route('prodi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus Prodi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors"><i class="bi bi-trash3 text-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data Program Studi.</td></tr>
                    @endforelse
                @endif
                
            </tbody>
        </table>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL TAMBAH & EDIT --}}
{{-- ======================================================== --}}

{{-- Modal Tambah Fakultas --}}
<div id="modal-tambah-fakultas" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-black text-gray-800 uppercase tracking-tight">Tambah Fakultas</h4>
                <button type="button" onclick="closeModal('modal-tambah-fakultas')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('fakultas.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kode Fakultas (Digit Ke-1)</label>
                <input type="text" name="kode_fakultas" required maxlength="1" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div>
                <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Fakultas</label>
                <input type="text" name="nama_fakultas" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div>
                <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Singkatan (Opsional)</label>
                <input type="text" name="singkatan" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-fakultas')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Jurusan --}}
<div id="modal-tambah-jurusan" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-black text-gray-800 uppercase tracking-tight">Tambah Jurusan</h4>
                <button type="button" onclick="closeModal('modal-tambah-jurusan')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('jurusan.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Fakultas</label>
                <select name="fakultas_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white">
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach($fakultas as $f) <option value="{{ $f->id }}">{{ $f->kode_fakultas }} - {{ $f->nama_fakultas }}</option> @endforeach
                </select></div>
                <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Jurusan</label>
                <input type="text" name="nama_jurusan" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-jurusan')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Prodi --}}
<div id="modal-tambah-prodi" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-black text-gray-800 uppercase tracking-tight">Tambah Prodi</h4>
                <button type="button" onclick="closeModal('modal-tambah-prodi')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('prodi.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Jurusan Induk</label>
                <select name="jurusan_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white">
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($jurusans as $j) <option value="{{ $j->id }}">{{ $j->fakultas->singkatan ?? $j->fakultas->kode_fakultas }} - {{ $j->nama_jurusan }}</option> @endforeach
                </select></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kode Prodi (2 Digit)</label>
                    <input type="text" name="kode_prodi" required maxlength="2" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div>
                    <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenjang</label>
                    <select name="jenjang" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white">
                        <option value="S1">S1</option><option value="D3">D3</option><option value="S2">S2</option><option value="S3">S3</option>
                    </select></div>
                </div>
                <div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Program Studi</label>
                <input type="text" name="nama_prodi" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-prodi')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Looping Modal Edit Fakultas --}}
@foreach($fakultas as $item)
<div id="modal-edit-fakultas-{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto"><div class="flex items-center justify-center min-h-screen p-4 bg-black/50 backdrop-blur-sm"><div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden text-left"><div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50"><h4 class="font-black text-gray-800 uppercase tracking-tight">Edit Fakultas</h4><button type="button" onclick="closeModal('modal-edit-fakultas-{{ $item->id }}')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button></div><form action="{{ route('fakultas.update', $item->id) }}" method="POST" class="p-6 space-y-5">@csrf @method('PUT')<div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kode Fakultas (Digit Ke-1)</label><input type="text" name="kode_fakultas" value="{{ $item->kode_fakultas }}" required maxlength="1" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div><div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Fakultas</label><input type="text" name="nama_fakultas" value="{{ $item->nama_fakultas }}" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div><div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Singkatan (Opsional)</label><input type="text" name="singkatan" value="{{ $item->singkatan }}" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div><div class="pt-4 flex gap-3"><button type="button" onclick="closeModal('modal-edit-fakultas-{{ $item->id }}')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button><button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26]">Update Data</button></div></form></div></div></div>
@endforeach

{{-- Looping Modal Edit Jurusan --}}
@foreach($jurusans as $item)
<div id="modal-edit-jurusan-{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto"><div class="flex items-center justify-center min-h-screen p-4 bg-black/50 backdrop-blur-sm"><div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden text-left"><div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50"><h4 class="font-black text-gray-800 uppercase tracking-tight">Edit Jurusan</h4><button type="button" onclick="closeModal('modal-edit-jurusan-{{ $item->id }}')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button></div><form action="{{ route('jurusan.update', $item->id) }}" method="POST" class="p-6 space-y-5">@csrf @method('PUT')<div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Fakultas</label><select name="fakultas_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white cursor-pointer">@foreach($fakultas as $f)<option value="{{ $f->id }}" {{ $item->fakultas_id == $f->id ? 'selected' : '' }}>{{ $f->kode_fakultas }} - {{ $f->nama_fakultas }}</option>@endforeach</select></div><div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Jurusan</label><input type="text" name="nama_jurusan" value="{{ $item->nama_jurusan }}" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div><div class="pt-4 flex gap-3"><button type="button" onclick="closeModal('modal-edit-jurusan-{{ $item->id }}')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button><button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26]">Update Data</button></div></form></div></div></div>
@endforeach

{{-- Looping Modal Edit Prodi --}}
@foreach($prodis as $item)
<div id="modal-edit-prodi-{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto"><div class="flex items-center justify-center min-h-screen p-4 bg-black/50 backdrop-blur-sm"><div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden text-left"><div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50"><h4 class="font-black text-gray-800 uppercase tracking-tight">Edit Prodi</h4><button type="button" onclick="closeModal('modal-edit-prodi-{{ $item->id }}')" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg"></i></button></div><form action="{{ route('prodi.update', $item->id) }}" method="POST" class="p-6 space-y-5">@csrf @method('PUT')<div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Jurusan Induk</label><select name="jurusan_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white cursor-pointer">@foreach($jurusans as $j)<option value="{{ $j->id }}" {{ $item->jurusan_id == $j->id ? 'selected' : '' }}>{{ $j->fakultas->singkatan ?? $j->fakultas->kode_fakultas }} - {{ $j->nama_jurusan }}</option>@endforeach</select></div><div class="grid grid-cols-2 gap-4"><div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kode Prodi (2 Digit)</label><input type="text" name="kode_prodi" value="{{ $item->kode_prodi }}" required maxlength="2" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div><div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenjang</label><select name="jenjang" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white cursor-pointer"><option value="S1" {{ $item->jenjang == 'S1' ? 'selected' : '' }}>S1</option><option value="D3" {{ $item->jenjang == 'D3' ? 'selected' : '' }}>D3</option><option value="S2" {{ $item->jenjang == 'S2' ? 'selected' : '' }}>S2</option><option value="S3" {{ $item->jenjang == 'S3' ? 'selected' : '' }}>S3</option></select></div></div><div><label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Program Studi</label><input type="text" name="nama_prodi" value="{{ $item->nama_prodi }}" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none"></div><div class="pt-4 flex gap-3"><button type="button" onclick="closeModal('modal-edit-prodi-{{ $item->id }}')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button><button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26]">Update Data</button></div></form></div></div></div>
@endforeach

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    
    setTimeout(() => {
        const alerts = document.querySelectorAll('.auto-dismiss-alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.style.display = 'none', 500);
        });
    }, 4000);
</script>
@endsection