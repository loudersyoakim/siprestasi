@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <h3 class="text-xl font-black text-gray-800 tracking-tight">Daftar Prestasi</h3>

    <a href="{{ route('admin.prestasi.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-green-200 hover:bg-[#004d26] transition-all">
        <i class="bi bi-trophy-fill"></i>
        <span>Tambah Prestasi</span>
    </a>
</div>

<div class="w-full min-w-0 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col mb-8 overflow-hidden">

    {{-- FORM FILTER DITUTUP LEBIH AWAL --}}
    <form id="filter-form" action="{{ route('admin.prestasi') }}" method="GET">
        {{-- Header Tabel & Pencarian Teks --}}
        <div class="p-4 sm:p-6 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/30">
            <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Riwayat Capaian Mahasiswa</h4>

            <div class="relative w-full sm:w-max flex gap-2">
                <div class="relative w-full sm:w-80">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari Prestasi atau Mahasiswa..." autocomplete="off" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all">
                </div>

                {{-- Tombol Reset Filter --}}
                @if(request()->anyFilled(['search', 'kepesertaan', 'jenis_id', 'kategori_id', 'tingkat_id', 'sort_tanggal', 'status', 'is_published']))
                <a href="{{ route('admin.prestasi') }}" class="px-3 py-2 bg-red-50 text-red-500 rounded-lg text-sm font-bold hover:bg-red-500 hover:text-white transition-all tooltip" title="Reset Semua Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </div>
    </form> {{-- KUNCI SOLUSINYA: Form ditutup di sini! --}}

    {{-- Tabel Data dengan Dropdown Filter di Headernya --}}
    <div class="w-full overflow-x-auto custom-scrollbar">
        <table class="w-full min-w-max text-left border-collapse">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-4 w-1 whitespace-nowrap text-gray-500 text-[10px] uppercase font-black tracking-wider align-top">No</th>

                    {{-- PERHATIKAN: Semua <select> diberi tambahan form="filter-form" --}}
                    <th class="px-4 py-4 align-top max-w-[300px]">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Prestasi & Kepesertaan</div>
                        <select name="kepesertaan" form="filter-form" onchange="document.getElementById('filter-form').submit()" class="w-full text-[10px] max-w-[150px] text-xs py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 focus:outline-none focus:border-[#006633] cursor-pointer">
                            <option value="">Semua</option>
                            <option value="individu" {{ request('kepesertaan') == 'individu' ? 'selected' : '' }}>Individu</option>
                            <option value="tim" {{ request('kepesertaan') == 'tim' ? 'selected' : '' }}>Tim</option>
                        </select>
                    </th>

                    <th class="px-4 py-4 w-1 align-top whitespace-nowrap">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Kategori / Tingkat</div>
                        <div class="flex flex-col gap-1.5 w-full max-w-[160px]">
                            <select name="tingkat_id" form="filter-form" onchange="document.getElementById('filter-form').submit()" class="w-full text-[10px] py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 focus:outline-none font-bold cursor-pointer">
                                <option value="">Semua Tingkat</option>
                                @foreach($masterTingkat as $t)
                                <option value="{{ $t->id }}" {{ request('tingkat_id') == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
                                @endforeach
                            </select>
                            <select name="kategori_id" form="filter-form" onchange="document.getElementById('filter-form').submit()" class="w-full text-[10px] text-xs py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 focus:outline-none cursor-pointer">
                                <option value="">Semua Kategori</option>
                                @foreach($masterKategori as $k)
                                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>

                    <th class="px-4 py-4 w-1 align-top whitespace-nowrap">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Periode</div>
                        <select name="sort_tanggal" form="filter-form" onchange="document.getElementById('filter-form').submit()" class="w-full max-w-[140px] text-[10px] py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 focus:outline-none cursor-pointer">
                            <option value="">Terbaru</option>
                            <option value="asc" {{ request('sort_tanggal') == 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </th>

                    <th class="px-4 py-4 w-1 align-top whitespace-nowrap">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2 text-left">Status</div>
                        <div class="flex flex-col gap-1.5 w-full min-w-[120px]">
                            <select name="status" form="filter-form" onchange="document.getElementById('filter-form').submit()" class="w-full text-[10px] py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 focus:outline-none font-bold cursor-pointer text-left">
                                <option value="">Validasi</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <select name="is_published" form="filter-form" onchange="document.getElementById('filter-form').submit()" class="w-full text-[10px] py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 focus:outline-none font-bold cursor-pointer text-left">
                                <option value="">Publikasi</option>
                                <option value="1" {{ request('is_published') == '1' ? 'selected' : '' }}>Publish</option>
                                <option value="0" {{ request('is_published') == '0' ? 'selected' : '' }}>Internal</option>
                            </select>
                        </div>
                    </th>

                    <th class="px-4 py-4 w-1 text-center whitespace-nowrap text-gray-500 text-[10px] uppercase font-black tracking-wider align-top">Aksi</th>
                </tr>
            </thead>

            {{-- ISI TABEL --}}
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($prestasi as $index => $item)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-4 py-4 text-gray-400 font-semibold align-top whitespace-nowrap">{{ $prestasi->firstItem() + $index }}</td>

                    <td class="px-4 py-4 align-top max-w-[300px]">
                        <div class="font-bold text-gray-800 mb-2 truncate" title="{{ $item->nama_prestasi }}">{{ $item->nama_prestasi }}</div>
                        @if($item->mahasiswa->count() > 1)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider mb-2"><i class="bi bi-people-fill"></i> Tim ({{ $item->mahasiswa->count() }} Orang)</span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-wider mb-2"><i class="bi bi-person-fill"></i> Individu</span>
                        @endif
                        {{-- <div class="space-y-1 mt-1">
                            @foreach($item->mahasiswa as $mhs)
                            <div class="text-[11px] text-[#006633] font-bold tracking-wide flex items-center gap-1.5 bg-green-50 px-2 py-1 rounded-md w-max max-w-full overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $mhs->name }}">
                                <i class="bi bi-person-circle"></i> {{ $mhs->name }}
                            </div>
                            @endforeach
                        </div> --}}
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap align-top">
                        <div class="text-[10px] font-black uppercase text-[#006633] tracking-wider mb-1"><i class="bi bi-tag-fill text-[9px]"></i> {{ $item->jenis->nama_jenis ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-700 font-bold">{{ $item->kategori->nama_kategori ?? 'N/A' }}</div>
                        <div class="text-[11px] text-gray-400 font-medium flex items-center gap-1 mt-0.5">{{ $item->tingkat->nama_tingkat ?? 'N/A' }}</div>
                        <a href="{{ asset('storage/' . $item->sertifikat) }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-600 font-bold hover:underline mt-3"><i class="bi bi-file-earmark-arrow-down-fill"></i><span class="text-[10px] uppercase tracking-wider">Sertifikat</span></a>
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap align-top">
                        <div class="font-bold text-gray-700">TA: {{ $item->tahunAkademik->tahun ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($item->tanggal_peroleh)->format('d M Y') }}</div>
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap align-top text-center">
                        <div class="flex flex-col items-center gap-2">
                            @switch($item->status)
                            @case('approved') <span class="px-3 py-1 text-[9px] font-black uppercase tracking-wider text-green-600 bg-green-100 rounded-lg border border-green-200 w-full text-center">Approved</span> @break
                            @case('pending') <span class="px-3 py-1 text-[9px] font-black uppercase tracking-wider text-orange-600 bg-orange-100 rounded-lg border border-orange-200 animate-pulse w-full text-center">Pending</span> @break
                            @case('rejected') <span class="px-3 py-1 text-[9px] font-black uppercase tracking-wider text-red-600 bg-red-100 rounded-lg border border-red-200 tooltip w-full text-center" title="{{ $item->alasan_ditolak }}">Rejected <i class="bi bi-info-circle ml-1"></i></span> @break
                            @endswitch

                            @if($item->is_published)
                            <span class="text-[9px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100 w-full text-center"><i class="bi bi-globe2"></i> Publish</span>
                            @else
                            <span class="text-[9px] font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full border border-gray-100 w-full text-center"><i class="bi bi-lock-fill"></i> Internal</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-4 py-4 whitespace-nowrap align-top text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            {{-- Tombol Detail Selalu Muncul --}}
                            <a href="{{ route('admin.prestasi.show', $item->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Detail"><i class="bi bi-eye-fill"></i></a>

                            @if($item->status === 'rejected')
                                {{-- KHUSUS REJECTED: Cuma bisa ubah status --}}
                                <form action="{{ route('admin.prestasi.status-update', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Kembalikan ke Pending" onclick="return confirm('Ubah status kembali ke Pending?')">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.prestasi.status-update', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Langsung Approve" onclick="return confirm('Ubah status menjadi Approved?')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>

                            @else
                                {{-- SELAIN REJECTED (Pending & Approved): Bebas Edit & Delete --}}
                                <a href="{{ route('admin.prestasi.edit', $item->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors tooltip" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                
                                <form action="{{ route('admin.prestasi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen data prestasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            @endif
                            
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="bi bi-inbox text-4xl mb-3 block opacity-50"></i>
                        <p class="font-medium text-sm">Tidak ada data yang sesuai dengan filter.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($prestasi->hasPages())
    <div class="p-4 border-t border-gray-50 bg-gray-50/30">
        {{ $prestasi->links() }}
    </div>
    @endif
</div>

{{-- Script Pencarian Otomatis dengan Delay --}}
<script>
    let timeout = null;
    const searchInput = document.getElementById('search-input');
    const filterForm = document.getElementById('filter-form');

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            filterForm.submit();
        }, 500);
    });

    const val = searchInput.value;
    searchInput.value = '';
    searchInput.focus();
    searchInput.value = val;
</script>
@endsection