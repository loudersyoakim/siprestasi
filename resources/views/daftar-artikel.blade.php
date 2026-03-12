@extends('layouts.front')

@section('title', 'Arsip Berita & Prestasi - SIARPRESTASI')

@section('content')
<div class="bg-gray-50/50 min-h-screen pt-32 pb-20">
    <div class="container mx-auto px-6 lg:px-20">

        {{-- Header Page --}}
        <div class="text-center max-w-2xl mx-auto mb-10">
            <h1 class="text-3xl lg:text-4xl font-black text-gray-900 leading-tight mb-4">
                Berita & <span class="text-[#006633]">Prestasi</span>
            </h1>
            <p class="text-sm text-gray-500">Informasi terbaru seputar kegiatan kampus dan capaian mahasiswa.</p>
        </div>

        {{-- Search & Filter --}}
        <div class="flex flex-col lg:flex-row gap-4 mb-10 items-center justify-between bg-white p-3 lg:p-4 rounded-[1.5rem] shadow-sm border border-gray-100">

            {{-- Filter Kategori --}}
            <div class="relative w-full lg:w-64">
                <select onchange="location = this.value;"
                    class="w-full appearance-none bg-white border-2 border-[#006633] text-[#006633] font-bold text-[10px] uppercase tracking-widest rounded-full pl-5 pr-10 py-2.5 hover:bg-gray-50 focus:outline-none cursor-pointer transition-all">
                    @foreach(['semua' => 'Semua Kategori', 'berita' => 'Berita Kampus', 'lomba' => 'Info Lomba', 'prestasi' => 'Prestasi Mahasiswa', 'pengumuman' => 'Pengumuman'] as $val => $label)
                    <option value="{{ route('artikel.index', ['category' => $val, 'search' => request('search')]) }}"
                        {{ request('category', 'semua') == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-[#006633]">
                    <i class="bi bi-chevron-down text-[10px]"></i>
                </div>
            </div>

            {{-- Form Pencarian --}}
            <form action="{{ route('artikel.index') }}" method="GET" class="relative w-full lg:w-80">
                <input type="hidden" name="category" value="{{ request('category', 'semua') }}">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="bi bi-search text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..."
                    class="w-full pl-12 pr-20 py-2.5 bg-gray-50 border-2 border-gray-100 focus:border-[#006633] rounded-full text-xs font-bold outline-none transition-all">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-[#006633] text-white rounded-full text-[9px] font-black uppercase tracking-widest hover:bg-black transition-all">
                    Cari
                </button>
            </form>
        </div>

        {{-- Article Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($artikels as $item)
            <a href="{{ route('artikel.show', $item->slug) }}" class="group flex flex-col h-full bg-white rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                {{-- Thumbnail --}}
                <div class="relative aspect-[16/9] overflow-hidden">
                    <img src="{{ asset('storage/' . $item->thumbnail) }}"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/95 backdrop-blur-md px-3 py-1 rounded-lg text-[8px] font-black uppercase text-[#006633] border border-gray-50">
                            {{ $item->category }}
                        </span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-5 flex flex-col flex-1">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                        {{ $item->created_at->translatedFormat('d M Y') }}
                    </span>
                    <h3 class="text-base font-bold text-gray-900 leading-snug mb-2 group-hover:text-[#006633] transition-colors line-clamp-2">
                        {{ $item->title }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-4">
                        {{ str($item->content)->stripTags()->limit(90) }}
                    </p>
                    <div class="mt-auto flex items-center text-[#006633] text-[9px] font-black uppercase tracking-widest group-hover:gap-2 gap-1 transition-all">
                        Baca Selengkapnya <i class="bi bi-arrow-right text-xs"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-20 text-center">
                <i class="bi bi- megaphone text-4xl text-gray-200 mb-4"></i>
                <h3 class="text-lg font-black text-gray-900">Belum ada konten</h3>
                <p class="text-gray-400 text-sm">Silakan pilih kategori lain atau coba kata kunci berbeda.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-12 flex justify-center custom-pagination">
            {{ $artikels->links() }}
        </div>
    </div>
</div>

<style>
    /* Compact Text Clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Custom Pagination Styling */
    .custom-pagination .page-link {
        border-radius: 10px !important;
        margin: 0 3px;
        padding: 8px 14px !important;
        font-weight: 800 !important;
        font-size: 11px !important;
        color: #006633 !important;
        border: none !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .custom-pagination .page-item.active .page-link {
        background: #006633 !important;
        color: white !important;
    }
</style>
@endsection