@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Daftar Prestasi Saya</h3>
        <p class="text-sm text-gray-500 mt-1">Pantau dan kelola riwayat capaian prestasi Anda.</p>
    </div>

    {{-- Tombol Tambah --}}
    <a href="{{ route('mahasiswa.prestasi.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-6 py-3 rounded-2xl text-sm font-black shadow-lg shadow-green-100 hover:bg-[#004d26] transition-all transform hover:-translate-y-1">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Prestasi</span>
    </a>
</div>

<div class="w-full min-w-0 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    
    {{-- FILTER FORM --}}
    <form id="filter-form" action="{{ route('mahasiswa.prestasi') }}" method="GET">
        <div class="p-4 sm:p-6 border-b border-gray-50 bg-gray-50/30 flex flex-col md:flex-row justify-between gap-4">
            <div class="relative w-full md:w-80">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari nama kompetisi..." autocomplete="off" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all">
            </div>

            <div class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="text-xs font-bold py-2 px-3 border border-gray-200 rounded-xl bg-white text-gray-600 focus:outline-none focus:border-[#006633]">
                    <option value="">Semua Status</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('mahasiswa.prestasi') }}" class="px-3 py-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
                @endif
            </div>
        </div>
    </form> {{-- KUNCI PERBAIKAN: Form filter ditutup di sini, sebelum tabel dimulai! --}}

    {{-- TABEL --}}
    <div class="w-full overflow-x-auto custom-scrollbar">
        <table class="w-full min-w-max text-left border-collapse">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-6 py-4 text-gray-500 text-[10px] uppercase font-black tracking-wider w-1">No</th>
                    <th class="px-6 py-4 text-gray-500 text-[10px] uppercase font-black tracking-wider">Detail Prestasi</th>
                    <th class="px-6 py-4 text-gray-500 text-[10px] uppercase font-black tracking-wider text-center">Tingkat & Kategori</th>
                    <th class="px-6 py-4 text-gray-500 text-[10px] uppercase font-black tracking-wider text-center">Status Validasi</th>
                    <th class="px-6 py-4 text-gray-500 text-[10px] uppercase font-black tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm italic-none">
                @forelse($prestasi as $index => $item)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-5 text-gray-400 font-semibold align-top">{{ $prestasi->firstItem() + $index }}</td>
                    
                    <td class="px-6 py-5 align-top max-w-md">
                        <div class="font-black text-gray-800 text-sm leading-tight mb-2 uppercase tracking-tight">{{ $item->nama_prestasi }}</div>

                        <div class="flex items-center gap-2 text-[11px] text-gray-500 font-medium mt-1">
                            <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($item->tanggal_peroleh)->format('d M Y') }}</span>
                            <span class="text-gray-300">|</span>
                            <span class="flex items-center gap-1 text-[#006633] font-bold italic">TA: {{ $item->tahunAkademik->tahun ?? '-' }}</span>
                        </div>
                        {{-- INDIKATOR TIM / INDIVIDU --}}
                        @if($item->mahasiswa->count() > 1)
                            <div class="mt-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider"><i class="bi bi-people-fill"></i> Tim ({{ $item->mahasiswa->count() }} Orang)</span>
                            </div>
                        @else
                            <div class="mb-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-wider"><i class="bi bi-person-fill"></i> Individu</span>
                            </div>
                        @endif
                        {{-- Sertifikat Link --}}
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $item->sertifikat) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-wider hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="bi bi-file-earmark-check-fill"></i> Lihat Sertifikat
                            </a>
                        </div>
                    </td>

                    <td class="px-6 py-5 align-top text-center">
                        <div class="text-[10px] font-black uppercase text-[#006633] tracking-widest mb-1">{{ $item->tingkat->nama_tingkat ?? '-' }}</div>
                        <div class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">{{ $item->kategori->nama_kategori ?? '-' }}</div>
                        <div class="mt-1 px-2 py-0.5 inline-block bg-gray-100 text-gray-600 text-[9px] font-black rounded-md">{{ $item->jenis->nama_jenis ?? '-' }}</div>
                    </td>

                    <td class="px-6 py-5 align-top text-center w-40">
                        <div class="flex flex-col items-center gap-2">
                            @switch($item->status)
                                @case('approved')
                                    <span class="px-3 py-1 text-[10px] font-black uppercase text-green-600 bg-green-100 rounded-lg border border-green-200 w-full text-center">Diverifikasi</span>
                                    @if($item->is_published)
                                    <span class="text-[9px] font-bold text-blue-500 italic"><i class="bi bi-globe"></i> Publik</span>
                                    @endif
                                @break
                                
                                @case('pending')
                                    <span class="px-3 py-1 text-[10px] font-black uppercase text-orange-600 bg-orange-100 rounded-lg border border-orange-200 animate-pulse w-full text-center">Proses...</span>
                                    <p class="text-[9px] text-gray-400 italic">Menunggu Validasi</p>
                                @break

                                @case('rejected')
                                    <span class="px-3 py-1 text-[10px] font-black uppercase text-red-600 bg-red-100 rounded-lg border border-red-200 w-full text-center">Ditolak</span>
                                    {{-- Popover/Tooltip Alasan --}}
                                    <div class="text-[10px] text-red-700 font-bold bg-red-50 p-2 rounded-xl border border-red-100 mt-1 max-w-[150px]">
                                        <i class="bi bi-info-circle-fill"></i> "{{ $item->alasan_ditolak ?? 'Periksa kembali berkas Anda.' }}"
                                    </div>
                                @break
                            @endswitch
                        </div>
                    </td>

                    <td class="px-6 py-5 align-top text-center w-1">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Tombol Edit hanya jika status PENDING atau REJECTED --}}
                            @if($item->status !== 'approved')
                            <a href="{{ route('mahasiswa.prestasi.edit', $item->id) }}" class="w-9 h-9 rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Edit Data"><i class="bi bi-pencil-fill"></i></a>
                            
                            {{-- Form Delete Sekarang Berdiri Sendiri --}}
                            <form action="{{ route('mahasiswa.prestasi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen prestasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </form>
                            @else
                            {{-- Jika sudah approved, hanya bisa lihat detail --}}
                            <a href="{{ route('mahasiswa.prestasi.show', $item->id) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Detail"><i class="bi bi-eye-fill"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="bi bi-trophy text-3xl text-gray-200"></i>
                            </div>
                            <h5 class="text-sm font-black text-gray-400 uppercase tracking-widest">Belum Ada Prestasi</h5>
                            <p class="text-xs text-gray-400 mt-1">Ayo laporkan capaian prestasi Anda sekarang!</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($prestasi->hasPages())
    <div class="p-6 border-t border-gray-50 bg-gray-50/30">
        {{ $prestasi->links() }}
    </div>
    @endif
</div>

{{-- SCRIPT SEARCH --}}
<script>
    let timeout = null;
    const searchInput = document.getElementById('search-input');
    const filterForm = document.getElementById('filter-form');

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            filterForm.submit();
        }, 600);
    });
</script>
@endsection