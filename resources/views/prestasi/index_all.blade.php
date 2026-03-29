@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <h3 class="text-2xl font-black text-gray-800 tracking-tight">Semua Prestasi</h3>

    @if(Auth::user()->hasPermission('prestasi.create'))
    <a href="{{ route('prestasi.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-green-200 hover:bg-[#004d26] transition-all">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Prestasi</span>
    </a>
    @endif
</div>

<div class="w-full min-w-0 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col mb-8 overflow-hidden">
    <form id="filter-form" action="{{ route('prestasi.index-all') }}" method="GET">
        {{-- TOP BAR: SEARCH & GLOBAL FILTERS --}}
        <div class="p-4 sm:p-5 border-b border-gray-50 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-gray-50/30">
            <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider shrink-0">Riwayat Capaian</h4>

            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto justify-end">
                <div class="relative w-full sm:w-64">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari Pelapor atau Kegiatan..." autocomplete="off" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all">
                </div>

                {{-- Dropdown Filter Kategori Form --}}
                <div class="relative group">
                    <button type="button" onclick="toggleFilterDropdown('dropdown-kategori', event)" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:border-[#006633] hover:text-[#006633] transition-all">
                        <i class="bi bi-funnel-fill"></i><span>Kategori Form</span>
                        @if(request('form_id')) <span class="w-2 h-2 rounded-full bg-red-500 absolute top-1 right-1"></span> @endif
                    </button>
                    
                    <div id="dropdown-kategori" class="hidden absolute right-0 lg:left-0 mt-2 w-64 bg-white border border-gray-100 rounded-xl shadow-xl py-1.5 z-50">
                        <label class="flex items-center px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition-colors group/item">
                            <input type="radio" name="form_id" value="" onchange="document.getElementById('filter-form').submit()" class="hidden" {{ !request('form_id') ? 'checked' : '' }}>
                            <span class="text-xs {{ !request('form_id') ? 'font-bold text-[#006633]' : 'font-medium text-gray-700' }}">Semua Kategori</span>
                            @if(!request('form_id')) <i class="bi bi-check-circle-fill text-[#006633] ml-auto"></i> @endif
                        </label>
                        <div class="border-t border-gray-50 my-1"></div>
                        
                        @foreach($listForm as $f)
                        <label class="flex items-start px-4 py-2 hover:bg-gray-50 cursor-pointer transition-colors group/item">
                            <input type="radio" name="form_id" value="{{ $f->id }}" onchange="document.getElementById('filter-form').submit()" class="hidden" {{ request('form_id') == $f->id ? 'checked' : '' }}>
                            <span class="text-xs leading-tight {{ request('form_id') == $f->id ? 'font-bold text-[#006633]' : 'font-medium text-gray-600' }}">{{ $f->nama_form }}</span>
                            @if(request('form_id') == $f->id) <i class="bi bi-check-circle-fill text-[#006633] ml-auto mt-0.5"></i> @endif
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Dropdown Filter Status --}}
                <div class="relative group">
                    <button type="button" onclick="toggleFilterDropdown('dropdown-status', event)" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:border-[#006633] hover:text-[#006633] transition-all">
                        <i class="bi bi-shield-check"></i><span>Status</span>
                        @if(request('status')) <span class="w-2 h-2 rounded-full bg-red-500 absolute top-1 right-1"></span> @endif
                    </button>
                    
                    <div id="dropdown-status" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl py-1.5 z-50">
                        @php $statuses = ['' => 'Semua Status', 'Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected']; @endphp
                        @foreach($statuses as $val => $label)
                        <label class="flex items-center px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition-colors group/item">
                            <input type="radio" name="status" value="{{ $val }}" onchange="document.getElementById('filter-form').submit()" class="hidden" {{ request('status') == $val ? 'checked' : '' }}>
                            <span class="text-xs {{ request('status') == $val ? 'font-bold text-[#006633]' : 'font-medium text-gray-700' }}">{{ $label }}</span>
                            @if(request('status') == $val) <i class="bi bi-check-circle-fill text-[#006633] ml-auto"></i> @endif
                        </label>
                        @endforeach
                    </div>
                </div>

                @if(request()->anyFilled(['search', 'status', 'form_id']))
                <a href="{{ route('prestasi.index-all') }}" class="px-3 py-2 bg-red-50 text-red-500 rounded-lg text-sm font-bold hover:bg-red-500 hover:text-white transition-all tooltip" title="Reset Semua Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </div>

        {{-- AREA TABEL --}}
        <div class="w-full overflow-x-auto custom-scrollbar min-h-[300px] pb-10">
            <table class="w-full min-w-max text-left border-collapse">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-4 w-1 text-gray-500 text-[10px] uppercase font-black tracking-wider align-top">No</th>
                        
                        <th class="px-4 py-4 align-top">
                            <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Pelapor & Tim</div>
                        </th>

                        {{-- KOLOM STATIS BARU --}}
                        <th class="px-4 py-4 align-top">
                            <div class="text-[#006633] text-[10px] uppercase font-black tracking-wider mb-2">Informasi Kegiatan</div>
                        </th>

                        <th class="px-4 py-4 w-1 align-top text-center">
                            <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Status Validasi</div>
                        </th>

                        {{-- KOLOM DINAMIS (Jika Form Terpilih) --}}
                        @if(request('form_id') && isset($dynamicFields) && $dynamicFields->count() > 0)
                            @foreach($dynamicFields as $field)
                                <th class="px-4 py-4 align-top max-w-[250px] border-l border-gray-100">
                                    <div class="text-gray-400 text-[10px] uppercase font-black tracking-wider mb-2">{{ $field->label }}</div>
                                </th>
                            @endforeach
                        @endif

                        <th class="px-4 py-4 w-1 text-center text-gray-500 text-[10px] uppercase font-black tracking-wider align-top sticky right-0 bg-gray-100 border-l border-gray-200 z-20" style="box-shadow: -4px 0 10px rgba(0,0,0,0.03);">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($prestasi as $index => $item)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-4 py-4 text-gray-400 font-semibold align-top">{{ $prestasi->firstItem() + $index }}</td>
                        
                        {{-- KOLOM PELAPOR --}}
                        <td class="px-4 py-4 align-top">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 text-[#006633] flex items-center justify-center font-black shrink-0">
                                    {{ substr($item->user->name ?? 'A', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-800 text-sm truncate" title="{{ $item->user->name ?? 'User Terhapus' }}">
                                        {{ $item->user->name ?? 'User Terhapus' }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-widest mt-0.5 flex flex-wrap items-center gap-1">
                                        <span class="font-bold text-[#006633]">{{ $item->user->nim_nip ?? '-' }}</span> • 
                                        <span class="truncate max-w-[150px]">{{ $item->user->prodi->nama_prodi ?? 'Prodi -' }}</span>
                                    </div>
                                    <div class="mt-1.5">
                                        @if($item->anggota->count() > 0)
                                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded text-[9px] font-black uppercase"><i class="bi bi-people-fill"></i> Tim ({{ $item->anggota->count() + 1 }})</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded text-[9px] font-black uppercase"><i class="bi bi-person-fill"></i> Individu</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- KOLOM STATIS (Judul, Tingkat, Capaian) --}}
                        <td class="px-4 py-4 align-top max-w-[300px]">
                            <div class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight" title="{{ $item->nama_kegiatan ?? '-' }}">
                                {{ $item->nama_kegiatan ?? '-' }}
                            </div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1.5 flex flex-wrap items-center gap-1.5">
                                <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100">{{ $item->tingkatPrestasi->nama_tingkat ?? '-' }}</span>
                                <span class="bg-yellow-50 text-yellow-600 px-2 py-0.5 rounded border border-yellow-100">{{ $item->capaianPrestasi->nama_capaian ?? '-' }}</span>
                            </div>
                            <div class="text-[9px] text-gray-400 font-bold mt-1 uppercase tracking-widest">
                                <i class="bi bi-calendar-event"></i> Thn. {{ $item->tahun_kegiatan ?? '-' }}
                            </div>
                        </td>

                        {{-- KOLOM STATUS --}}
                        <td class="px-4 py-4 align-top text-center whitespace-nowrap">
                            @switch($item->status)
                                @case('Approved') <span class="px-3 py-1 border border-green-200 text-[9px] font-black uppercase tracking-wider text-green-600 bg-green-50 rounded-lg w-full inline-block">Approved</span> @break
                                @case('Pending') <span class="px-3 py-1 border border-orange-200 text-[9px] font-black uppercase tracking-wider text-orange-600 bg-orange-50 rounded-lg animate-pulse w-full inline-block">Pending</span> @break
                                @case('Rejected') <span class="px-3 py-1 border border-red-200 text-[9px] font-black uppercase tracking-wider text-red-600 bg-red-50 rounded-lg w-full inline-block tooltip" title="{{ $item->catatan_penolakan }}">Rejected</span> @break
                            @endswitch
                            <div class="text-[9px] text-gray-400 mt-1">{{ $item->created_at->format('d M Y') }}</div>
                        </td>

                        {{-- ISI KOLOM DINAMIS --}}
                        @if(request('form_id') && isset($dynamicFields) && $dynamicFields->count() > 0)
                            @foreach($dynamicFields as $field)
                                <td class="px-4 py-4 align-top max-w-[250px] border-l border-gray-50">
                                    @php 
                                        $val = $item->data_dinamis[$field->id] ?? '-'; 
                                        if(is_array($val)) $val = implode(', ', $val);
                                    @endphp

                                    @if($field->tipe === 'file' && $val !== '-')
                                        <a href="{{ asset('storage/'.$val) }}" target="_blank" class="text-[10px] text-blue-600 font-bold hover:underline bg-blue-50 px-2 py-1.5 rounded-md inline-flex items-center gap-1"><i class="bi bi-file-earmark-fill"></i> Dokumen</a>
                                    @else
                                        <div class="text-xs text-gray-700 font-medium truncate" title="{{ $val }}">{{ $val }}</div>
                                    @endif
                                </td>
                            @endforeach
                        @endif

                        {{-- STICKY ACTION --}}
                        <td class="px-4 py-4 align-top text-center whitespace-nowrap sticky right-0 bg-white group-hover:bg-[#F9FAFB] transition-colors border-l border-gray-200 z-10" style="box-shadow: -4px 0 10px rgba(0,0,0,0.03);">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('prestasi.show', $item->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Detail Data"><i class="bi bi-eye-fill"></i></a>
                                
                                @if(Auth::user()->hasPermission('prestasi.create'))
                                <a href="{{ route('prestasi.edit', $item->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors tooltip" title="Edit Data"><i class="bi bi-pencil-square"></i></a>
                                
                                <form action="{{ route('prestasi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Hapus"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-6 py-12 text-center text-gray-400">
                            <i class="bi bi-inbox text-4xl mb-3 block opacity-50"></i>
                            <p class="font-medium text-sm">Tidak ada data prestasi yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
    
    @if($prestasi->hasPages())
    <div class="p-4 border-t border-gray-50 bg-gray-50/30">
        {{ $prestasi->links() }}
    </div>
    @endif
</div>

<script>
    let timeout = null;
    const searchInput = document.getElementById('search-input');
    const filterForm = document.getElementById('filter-form');
    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => filterForm.submit(), 500);
    });
    
    const val = searchInput.value; searchInput.value = ''; searchInput.focus(); searchInput.value = val;

    function toggleFilterDropdown(id, event) {
        event.stopPropagation();
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            if(el.id !== id) el.classList.add('hidden');
        });
        document.getElementById(id).classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            if(!el.contains(e.target)) el.classList.add('hidden');
        });
    });
</script>
@endsection