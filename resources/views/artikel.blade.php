@extends('layouts.front')

@section('title', $artikel->title . ' - SIARPRESTASI')

@section('content')
<div class="bg-white min-h-screen pt-32 pb-20">
    <div class="container mx-auto px-6 lg:px-20">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">

            <div class="lg:col-span-8">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">
                    <a href="/" class="hover:text-[#006633] transition-colors">Home</a>
                    <i class="bi bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('artikel.index') }}" class="hover:text-[#006633] transition-colors">Artikel</a>
                    <i class="bi bi-chevron-right text-[8px]"></i>
                    <span class="text-[#006633]">{{ $artikel->category }}</span>
                </nav>

                {{-- Judul Artikel --}}
                <h1 class="text-3xl lg:text-4xl font-black text-gray-900 leading-tight mb-6">
                    {{ $artikel->title }}
                </h1>

                {{-- Meta Info (Penulis & Tanggal) --}}
                <div class="flex items-center justify-between border-y border-gray-100 py-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#006633]/10 flex items-center justify-center text-[#006633]">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-900 uppercase tracking-wide">Humas Unimed</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $artikel->created_at->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Gambar Utama --}}
                <div class="rounded-[2rem] overflow-hidden shadow-lg aspect-video mb-10">
                    <img src="{{ asset('storage/' . $artikel->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $artikel->title }}">
                </div>

                {{-- Isi Artikel --}}
                <div class="pro-content">
                    <article class="prose prose-lg max-w-none prose-p:text-gray-600 prose-p:leading-[1.8] prose-headings:font-black prose-a:text-[#006633]">
                        {!! $artikel->content !!}
                    </article>
                </div>
            </div>


            {{-- ========================================== --}}
            {{-- LAYER KANAN (BERITA LAINNYA - 4 Kolom)     --}}
            {{-- ========================================== --}}
            <div class="lg:col-span-4">
                {{-- Efek Sticky agar sidebar ikut turun saat di-scroll --}}
                <div class="sticky top-32">

                    {{-- Judul Sidebar --}}
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-2 h-6 bg-[#006633] rounded-full"></div>
                        <h3 class="font-black text-gray-900 uppercase tracking-widest">Berita Lainnya</h3>
                    </div>

                    {{-- List Card Berita Lainnya --}}
                    <div class="flex flex-col gap-6">
                        {{-- Catatan: Pastikan variabel $rekomendasi sudah dikirim dari Controller --}}
                        @if(isset($rekomendasi) && $rekomendasi->count() > 0)
                        @foreach($rekomendasi as $rek)
                        <a href="{{ route('artikel.show', $rek->slug) }}" class="group flex gap-4 bg-white rounded-2xl p-3 border border-gray-50 hover:border-green-100 shadow-sm hover:shadow-md transition-all">
                            {{-- Thumbnail Mini --}}
                            <div class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 relative">
                                <img src="{{ asset('storage/' . $rek->thumbnail) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            {{-- Teks Mini --}}
                            <div class="flex flex-col justify-center py-1">
                                <span class="text-[8px] font-black text-[#006633] uppercase tracking-widest mb-1">{{ $rek->category }}</span>
                                <h4 class="font-bold text-gray-900 text-xs leading-snug line-clamp-3 group-hover:text-[#006633] transition-colors">
                                    {{ $rek->title }}
                                </h4>
                                <p class="text-[9px] text-gray-400 mt-2 font-bold">{{ $rek->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                        @endforeach
                        @else
                        <p class="text-sm text-gray-400 italic">Belum ada berita lainnya.</p>
                        @endif
                    </div>

                    {{-- Tombol Lihat Semua --}}
                    <a href="{{ route('artikel.index') }}" class="mt-8 flex items-center justify-center gap-2 w-full py-4 rounded-2xl bg-gray-50 text-[#006633] font-black text-[10px] uppercase tracking-widest hover:bg-[#006633] hover:text-white transition-all">
                        Eksplorasi Semua Berita <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Tambahan agar gambar di dalam artikel (dari CKEditor) tidak pecah dan rapi */
    .pro-content img {
        border-radius: 1.5rem;
        margin-top: 2rem;
        margin-bottom: 2rem;
        width: 100%;
        object-fit: cover;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
</style>
@endsection