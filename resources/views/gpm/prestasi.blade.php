@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <h3 class="text-xl font-black text-gray-800 tracking-tight">Monitoring Prestasi</h3>
</div>

<div class="w-full min-w-0 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col mb-8 overflow-hidden">

    <form id="filter-form" action="{{ route('gpm.dashboard') }}" method="GET">
        <div class="p-4 sm:p-6 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/30">
            <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Data Capaian Mahasiswa</h4>

            <div class="relative w-full sm:w-max flex gap-2">
                <div class="relative w-full sm:w-80">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari Prestasi atau Mahasiswa..." autocomplete="off" class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all">
                </div>

                @if(request()->anyFilled(['search', 'kepesertaan', 'tingkat_id', 'status']))
                <a href="{{ route('gpm.dashboard') }}" class="px-3 py-2 bg-red-50 text-red-500 rounded-lg text-sm font-bold hover:bg-red-500 hover:text-white transition-all">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </div>
    </form>

    <div class="w-full overflow-x-auto custom-scrollbar">
        <table class="w-full min-w-max text-left border-collapse">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 w-1 text-gray-500 text-[10px] uppercase font-black tracking-wider">No</th>
                    
                    <th class="px-4 py-4">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Informasi Prestasi</div>
                        <select name="kepesertaan" form="filter-form" onchange="this.form.submit()" class="text-[10px] py-1 px-2 border border-gray-200 rounded-md bg-white">
                            <option value="">Semua Partisipasi</option>
                            <option value="individu" {{ request('kepesertaan') == 'individu' ? 'selected' : '' }}>Individu</option>
                            <option value="tim" {{ request('kepesertaan') == 'tim' ? 'selected' : '' }}>Tim</option>
                        </select>
                    </th>

                    <th class="px-4 py-4">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Tingkat</div>
                        <select name="tingkat_id" form="filter-form" onchange="this.form.submit()" class="text-[10px] py-1 px-2 border border-gray-200 rounded-md bg-white">
                            <option value="">Semua Tingkat</option>
                            @foreach($masterTingkat as $t)
                            <option value="{{ $t->id }}" {{ request('tingkat_id') == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
                            @endforeach
                        </select>
                    </th>

                    <th class="px-4 py-4">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2 text-center">Status Validasi</div>
                        <select name="status" form="filter-form" onchange="this.form.submit()" class="w-full text-[10px] py-1 px-2 border border-gray-200 rounded-md bg-white font-bold">
                            <option value="">Semua Status</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </th>

                    <th class="px-6 py-4 text-center text-gray-500 text-[10px] uppercase font-black tracking-wider">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($prestasi as $index => $item)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-gray-400 font-semibold">{{ $prestasi->firstItem() + $index }}</td>

                    <td class="px-4 py-4">
                        <div class="font-bold text-gray-800 truncate max-w-[250px]">{{ $item->nama_prestasi }}</div>
                        <div class="text-[10px] text-gray-500 mt-1 uppercase tracking-tight">
                            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($item->tanggal_peroleh)->format('d M Y') }} 
                            <span class="mx-1">•</span> 
                            TA: {{ $item->tahunAkademik->tahun ?? '-' }}
                        </div>
                    </td>

                    <td class="px-4 py-4">
                        <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase">
                            {{ $item->tingkat->nama_tingkat ?? 'N/A' }}
                        </span>
                    </td>

                    <td class="px-4 py-4 text-center">
                        @switch($item->status)
                            @case('approved') <span class="text-green-600 font-black text-[10px] uppercase"><i class="bi bi-check-circle-fill"></i> Approved</span> @break
                            @case('pending') <span class="text-orange-500 font-black text-[10px] uppercase"><i class="bi bi-clock-history"></i> Pending</span> @break
                            @case('rejected') <span class="text-red-600 font-black text-[10px] uppercase"><i class="bi bi-x-circle-fill"></i> Rejected</span> @break
                        @endswitch
                    </td>

                    <td class="px-6 py-4 text-center">
                        {{-- HANYA TOMBOL SHOW --}}
                        <a href="{{ route('gpm.prestasi.show', $item->id) }}" 
                           class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#006633]/10 text-[#006633] hover:bg-[#006633] hover:text-white transition-all shadow-sm" 
                           title="Lihat Detail">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <p class="text-sm">Data prestasi tidak ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($prestasi->hasPages())
    <div class="p-4 border-t border-gray-50 bg-gray-50/30">
        {{ $prestasi->links() }}
    </div>
    @endif
</div>
@endsection