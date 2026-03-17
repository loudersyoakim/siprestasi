@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Manajemen Konten</h3>
        <p class="text-sm text-gray-500">Kelola berita kampus dan publikasi prestasi mahasiswa.</p>
    </div>
    <a href="{{ route('admin.manajemen-konten.create') }}" class="bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-[#004d26] transition-all flex items-center justify-center gap-2">
        <i class="bi bi-plus-circle-fill"></i> Tulis Berita
    </a>
</div>

@php
$activeTab = request('tab', 'berita');
@endphp

{{-- Tab System --}}
<div class="flex gap-4 mb-4 border-b border-gray-100 pb-px overflow-x-auto custom-scrollbar">
    <button onclick="switchTab('berita')" id="btn-berita" class="tab-btn px-6 py-3 text-sm font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'berita' ? 'border-[#006633] text-[#006633]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
        Berita & Informasi
    </button>
    <button onclick="switchTab('prestasi')" id="btn-prestasi" class="tab-btn px-6 py-3 text-sm font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'prestasi' ? 'border-[#006633] text-[#006633]' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
        Antrean Publikasi Prestasi
    </button>
</div>

{{-- Search Bar (Dinamis untuk kedua Tab) --}}
<div class="mb-6">
    <form id="search-form" action="{{ route('admin.manajemen-konten') }}" method="GET" class="relative w-full sm:w-96">
        <input type="hidden" name="tab" id="current-tab-input" value="{{ $activeTab }}">

        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
        <input type="text"
            id="search-input"
            name="search"
            value="{{ request('search') }}"
            placeholder="{{ $activeTab === 'berita' ? 'Cari Info / Judul Berita...' : 'Cari Nama Prestasi...' }}"
            autocomplete="off"
            class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] shadow-sm transition-all placeholder-gray-400">

        @if(request('search'))
        <a href="{{ route('admin.manajemen-konten', ['tab' => $activeTab]) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-all tooltip" title="Hapus Pencarian">
            <i class="bi bi-x-circle-fill"></i>
        </a>
        @endif
    </form>
</div>

{{-- Content Berita (Tab Berita) --}}
<div id="tab-berita" class="tab-content {{ $activeTab === 'berita' ? '' : 'hidden' }}">
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-gray-50/50 border-b border-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500">Info / Judul Berita</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500 text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kontens as $konten)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden border border-gray-100">
                                    @if($konten->thumbnail)
                                    <img src="{{ asset('storage/'.$konten->thumbnail) }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-[8px] text-gray-400 italic font-medium">No Pic</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800 text-sm line-clamp-1">{{ $konten->title }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium mt-1">Post: {{ $konten->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-yellow-50 text-yellow-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-yellow-100">
                                {{ $konten->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($konten->is_published)
                            <span class="text-green-500"><i class="bi bi-check-circle-fill"></i></span>
                            @else
                            <span class="text-gray-300"><i class="bi bi-circle"></i></span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.manajemen-konten.edit', $konten->id) }}" class="p-2 bg-gray-50 text-gray-400 rounded-xl hover:bg-[#006633] hover:text-white transition-all">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.manajemen-konten.destroy', $konten->id) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <i class="bi bi-search text-3xl text-gray-200 mb-3 block"></i>
                            <p class="text-gray-400 italic text-sm">Tidak ada berita yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($kontens->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $kontens->appends(['tab' => 'berita', 'search' => request('search')])->links() }}
    </div>
    @endif
</div>

{{-- Content Publikasi Prestasi --}}
<div id="tab-prestasi" class="tab-content {{ $activeTab === 'prestasi' ? '' : 'hidden' }}">
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-gray-50/50 border-b border-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500">Nama Prestasi</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500">Tingkat</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500 text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-gray-500 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($prestasiApproved as $p)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $p->nama_prestasi }}</div>
                            <div class="text-[10px] text-gray-400 mt-1">{{ $p->mahasiswa->pluck('name')->implode(', ') }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-[#006633]">{{ $p->tingkat->nama_tingkat }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($p->is_published)
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest">Publish</span>
                            @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-[9px] font-black uppercase tracking-widest">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                @if(!$p->is_published)
                                <form action="{{ route('admin.prestasi.publish', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <i class="bi bi-megaphone-fill mr-1"></i> Publish
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.prestasi.takedown', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-red-50 text-red-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="bi bi-cloud-arrow-down-fill mr-1"></i> Take Down
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <i class="bi bi-search text-3xl text-gray-200 mb-3 block"></i>
                            <p class="text-gray-400 italic text-sm">Tidak ada antrean prestasi yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($prestasiApproved->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $prestasiApproved->appends(['tab' => 'prestasi', 'search' => request('search')])->links() }}
    </div>
    @endif
</div>
<script>
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    const currentTabInput = document.getElementById('current-tab-input');
    let timeout = null;

    function switchTab(tab) {
        // 1. Update URL tanpa reload halaman
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);

        // 2. Update input hidden untuk search
        if (currentTabInput) currentTabInput.value = tab;

        // 3. Ganti Placeholder Search
        if (searchInput) {
            searchInput.placeholder = tab === 'berita' ? "Cari Info / Judul Berita..." : "Cari Nama Prestasi...";
        }

        // 4. Sembunyikan semua konten, lalu tampilkan yang dipilih
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        const selectedTab = document.getElementById('tab-' + tab);
        if (selectedTab) selectedTab.classList.remove('hidden');

        // 5. RESET SEMUA TOMBOL: Cabut warna hijau, kembalikan warna abu-abu
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-[#006633]', 'text-[#006633]');
            btn.classList.add('border-transparent', 'text-gray-400', 'hover:text-gray-600');
        });

        // 6. WARNAI TOMBOL AKTIF: Cabut warna abu-abu, berikan warna hijau
        const activeBtn = document.getElementById('btn-' + tab);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-gray-400', 'hover:text-gray-600');
            activeBtn.classList.add('border-[#006633]', 'text-[#006633]');
        }
    }

    // Fungsi Debounce untuk Search (Otomatis Submit)
    if (searchInput && searchForm) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                searchForm.submit();
            }, 500);
        });
    }

    // Logic saat halaman selesai dimuat (Menjaga kursor saat ngetik)
    document.addEventListener("DOMContentLoaded", function() {
        if (searchInput && searchInput.value) {
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.focus();
            searchInput.value = val;
        }
    });
</script>
@endsection