@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Daftar Mahasiswa</h3>
    </div>
</div>

{{-- ================= PANEL FILTER & PENCARIAN ================= --}}
<form action="{{ route($prefix . '.daftar-mahasiswa') }}" method="GET" class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm p-4 mb-6 flex flex-col lg:flex-row gap-4 items-end lg:items-center justify-between">
    
    {{-- Dropdown Filter Area --}}
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
        <div class="relative">
            <select name="fakultas_id" onchange="this.form.submit()" class="w-full sm:w-40 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 focus:outline-none focus:border-[#006633] focus:bg-white focus:ring-1 focus:ring-[#006633] cursor-pointer transition-all">
                <option value="">Semua Fakultas</option>
                @foreach($fakultas as $f)
                    <option value="{{ $f->id }}" {{ request('fakultas_id') == $f->id ? 'selected' : '' }}>{{ $f->nama_fakultas }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative">
            <select name="jurusan_id" onchange="this.form.submit()" class="w-full sm:w-40 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 focus:outline-none focus:border-[#006633] focus:bg-white focus:ring-1 focus:ring-[#006633] cursor-pointer transition-all">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                    <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative">
            <select name="prodi_id" onchange="this.form.submit()" class="w-full sm:w-40 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 focus:outline-none focus:border-[#006633] focus:bg-white focus:ring-1 focus:ring-[#006633] cursor-pointer transition-all">
                <option value="">Semua Prodi</option>
                @foreach($prodis as $p)
                    <option value="{{ $p->id }}" {{ request('prodi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                @endforeach
            </select>
        </div>
        
        {{-- Tombol Reset Filter --}}
        @if(request('fakultas_id') || request('jurusan_id') || request('prodi_id') || request('search'))
            <a href="{{ route($prefix . '.daftar-mahasiswa') }}" class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-1.5 shadow-sm">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </a>
        @endif
    </div>

    {{-- Search Area --}}
    <div class="relative w-full lg:w-64">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIM..." class="pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#006633] focus:bg-white focus:ring-1 focus:ring-[#006633] shadow-sm w-full transition-all">
        <button type="submit" class="hidden"></button>
    </div>
</form>

@if(session('success'))
<div class="auto-dismiss-alert mb-6 flex items-center justify-between p-4 text-sm font-bold text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm transition-opacity duration-500">
    <div class="flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

<div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Total: {{ (method_exists($mahasiswa, 'total') ? $mahasiswa->total() : $mahasiswa->count()) }} Mahasiswa</h4>
    </div>

    <div class="w-full overflow-x-auto custom-scrollbar">
       <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-3 py-2 text-center text-gray-400 text-[10px] uppercase font-bold tracking-wider w-10 align-middle whitespace-nowrap">
                        No
                    </th>
                    <th class="px-3 py-2 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle whitespace-nowrap">
                        Nama Mahasiswa
                    </th>
                    <th class="px-3 py-2 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle whitespace-nowrap">
                        NIM
                    </th>
                    <th class="px-3 py-2 text-center text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle whitespace-nowrap">
                        Angkatan
                    </th>
                    <th class="px-3 py-2 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle whitespace-nowrap">
                        Jurusan
                    </th>
                    <th class="px-3 py-2 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle whitespace-nowrap">
                        Prodi
                    </th>
                    <th class="px-3 py-2 text-center text-gray-400 text-[10px] uppercase font-bold tracking-wider w-28 align-middle whitespace-nowrap">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm bg-white">
                @forelse($mahasiswa as $item)
                <tr class="hover:bg-gray-50 transition-colors group">
                    
                    {{-- ISI KOLOM NO --}}
                    <td class="px-3 py-2.5 text-gray-500 font-medium align-middle text-center text-xs whitespace-nowrap">
                        {{ (method_exists($mahasiswa, 'firstItem') ? $mahasiswa->firstItem() : 1) + $loop->index }}
                    </td>

                    {{-- Kolom Profil --}}
                    <td class="px-3 py-2.5 align-middle">
                        <div class="flex items-center gap-2.5">
                            @if($item->mahasiswa && $item->mahasiswa->foto_profil)
                                <img src="{{ asset('storage/' . $item->mahasiswa->foto_profil) }}" alt="Foto" class="w-9 h-9 rounded-full object-cover shadow-sm border border-gray-200 transition-transform group-hover:scale-105 shrink-0">
                            @else
                                <div class="w-9 h-9 rounded-full bg-green-50 text-[#006633] border border-green-100 flex items-center justify-center font-bold shadow-sm transition-transform group-hover:scale-15 text-sm shrink-0">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            @endif

                            {{-- BUNGKUS SCROLLABLE PASTIKAN PAKAI PIXEL --}}
                            <div class="max-w-[300px] overflow-x-auto whitespace-nowrap custom-scrollbar pb-1">
                                <div class="font-bold text-gray-800">{{ $item->name }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5">{{ $item->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Kolom NIM --}}
                    <td class="px-3 py-2.5 align-middle font-bold text-gray-700 text-xs whitespace-nowrap">
                        {{ $item->nim_nip ?? '-' }}
                    </td>

                    {{-- Kolom Angkatan --}}
                    <td class="px-3 py-2.5 align-middle text-center whitespace-nowrap">
                        @if($item->mahasiswa && $item->mahasiswa->angkatan)
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                {{ $item->mahasiswa->angkatan }}
                            </span>
                        @else
                            <span class="text-[10px] text-gray-400 italic">-</span>
                        @endif
                    </td>

                    {{-- Kolom Jurusan --}}
                    <td class="px-3 py-2.5 align-middle">
                        @if($item->mahasiswa && $item->mahasiswa->prodi)
                            {{-- BUNGKUS SCROLLABLE PASTIKAN PAKAI PIXEL --}}
                            <div class="max-w-[180px] overflow-x-auto whitespace-nowrap custom-scrollbar pb-1">
                                <div class="font-bold text-gray-700 text-[11px]">
                                    {{ $item->mahasiswa->prodi->jurusan->nama_jurusan }}
                                </div>
                            </div>
                        @else
                            <span class="text-[10px] text-gray-400 italic">-</span>
                        @endif
                    </td>

                    {{-- Kolom Prodi --}}
                    <td class="px-3 py-2.5 align-middle">
                        @if($item->mahasiswa && $item->mahasiswa->prodi)
                            {{-- BUNGKUS SCROLLABLE PASTIKAN PAKAI PIXEL --}}
                            <div class="max-w-[200px] overflow-x-auto whitespace-nowrap custom-scrollbar pb-1">
                                <div class="font-semibold text-gray-800 text-[11px]">
                                    {{ $item->mahasiswa->prodi->nama_prodi }}
                                </div>
                                <div class="text-[9px] text-gray-400 mt-0.5">{{ $item->mahasiswa->prodi->jenjang }}</div>
                            </div>
                        @else
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-200">
                                <i class="bi bi-exclamation-triangle mr-1"></i> Belum Lengkap
                            </span>
                        @endif
                    </td>

                    {{-- Kolom Aksi --}}
                    <td class="px-3 py-2.5 align-middle text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-1.5">
                            <button class="w-7 h-7 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Detail Mahasiswa">
                                <i class="bi bi-eye-fill text-base"></i>
                            </button>

                            <a href="{{ route($prefix . '.daftar-mahasiswa.edit', $item->id) }}" class="w-7 h-7 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Edit Mahasiswa">
    <i class="bi bi-pencil-square text-base"></i>
</a>

                            <form action="{{ route($prefix . '.daftar-mahasiswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini? Seluruh data profil dan prestasinya akan terhapus permanen.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-md bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Hapus Mahasiswa">
                                    <i class="bi bi-trash3 text-base"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">Belum ada data mahasiswa yang terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($mahasiswa, 'hasPages') && $mahasiswa->hasPages())
    <div class="p-4 border-t border-gray-100 bg-white">
        {{ $mahasiswa->links() }}
    </div>
    @endif
</div>

<script>
    // Auto dismiss alert
    setTimeout(() => {
        const alerts = document.querySelectorAll('.auto-dismiss-alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.style.display = 'none', 500);
        });
    }, 4000);
</script>
@endsection