@extends('layouts.front')

@section('title', 'Beranda - SIARPRESTASI Universitas Negeri Medan')

@section('content')
{{-- HERO SECTION --}}
<section class="relative h-screen w-full flex items-center justify-center bg-white overflow-hidden">
    <div class="absolute inset-0 z-0">
        {{-- 1. Gambar Background Asli --}}
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('img/fmipa-unimed3.jpg') }}');"></div>

        <div class="absolute inset-0 bg-white/60 backdrop-blur-[3px]"></div>

        <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </div>

    <div class="container mx-auto px-6 lg:px-20 relative z-20 flex flex-col items-center justify-center text-center">
        <div class="w-16 h-1.5 bg-[#006633] mb-6 rounded-full shadow-sm"></div>

        <span class="text-[#006633] font-black tracking-[0.25em] uppercase text-lg lg:text-3xl md:text-base mb-3 drop-shadow-sm">
            {{ $pengaturan['nama_aplikasi'] ?? '' }}
        </span>

        <h1 class="text-4xl md:text-5xl lg:text-5xl font-black text-gray-900 leading-[1.1] uppercase mb-6 tracking-tight drop-shadow-md max-w-3xl mx-auto">
            {{ $pengaturan['hero_title_1'] ?? 'Sistem Arsip' }}
            <span class="text-[#006633]">{{ $pengaturan['hero_title_2'] ?? 'Prestasi' }}</span>
            {{ $pengaturan['hero_title_3'] ?? 'Mahasiswa' }}
        </h1>

        <p class="text-lg md:text-xl text-gray-800 font-medium leading-relaxed max-w-xl mb-10 drop-shadow-sm mx-auto">
            {{ $pengaturan['deskripsi_landing'] ?? 'Platform terpadu untuk mencatat setiap pencapaian.' }}
        </p>

        <a href="/login" class="w-max bg-[#006633] border border-white/20 text-white px-10 py-3.5 rounded-full font-bold text-sm hover:bg-green-800 hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-green-900/20 uppercase tracking-widest">
            Login
        </a>
    </div>
</section>

{{-- SECTION BERITA & PENGUMUMAN --}}
<section id="section-berita" class="py-20 bg-gray-50/50">
    <div class="container mx-auto px-6 lg:px-20">
        <div class="mb-10 text-center flex flex-col items-center">
            <div class="w-12 h-1 bg-[#006633] mb-3"></div>
            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Berita & <span class="text-[#006633]">Pengumuman</span></h2>
        </div>

        @if($headline)
        <div class="grid grid-cols-12 gap-6">
            {{-- 1. HEADLINE --}}
            <div class="col-span-12 lg:colQ-span-7">
                <a href="{{ route('artikel.show', $headline->slug) }}" class="group relative bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col h-full">
                    <div class="relative w-full min-h-[250px] lg:min-h-[320px] overflow-hidden">
                        <img src="{{ asset('storage/' . $headline->gambar_cover) }}"
                            class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-all duration-700">
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[9px] font-black uppercase text-[#006633] shadow-sm">
                                {{ $headline->kategori }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1 justify-center">
                        <span class="text-[10px] font-bold text-gray-400">{{ $headline->created_at->translatedFormat('d F Y') }}</span>
                        <h3 class="text-xl lg:text-2xl font-black text-gray-900 mt-2 leading-tight line-clamp-2 group-hover:text-[#006633] transition-colors">
                            {{ $headline->judul }}
                        </h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed line-clamp-2">
                            {{ str($headline->isi_konten)->stripTags()->limit(130) }}
                        </p>
                    </div>
                </a>
            </div>

            {{-- 2. SIDE LIST --}}
            @if($listBerita->count() > 0)
            <div class="col-span-12 lg:col-span-5 flex flex-col gap-4">
                @foreach($listBerita->take(3) as $index => $item)
                <a href="{{ route('artikel.show', $item->slug) }}"
                    class="group bg-white rounded-[1.5rem] shadow-sm hover:shadow-md transition-all border border-transparent hover:border-green-100 p-3 lg:p-4 gap-4 items-center 
                          {{ $index === 2 ? 'hidden lg:flex' : 'flex' }}">

                    <div class="w-2/5 h-24 lg:h-28 relative">
                        <div class="w-full h-full rounded-2xl overflow-hidden relative shadow-sm">
                            <img src="{{ asset('storage/' . $item->gambar_cover) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                    </div>

                    <div class="w-3/5 flex flex-col justify-center">
                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">{{ $item->kategori }}</span>
                        <h4 class="font-bold text-gray-900 text-sm leading-tight line-clamp-2 group-hover:text-[#006633] transition-colors">{{ $item->judul }}</h4>
                        <p class="text-gray-500 text-[10px] mt-2 line-clamp-2 leading-relaxed">
                            {{ str($item->isi_konten)->stripTags()->limit(60) }}
                        </p>
                    </div>
                </a>
                @endforeach

                <a href="{{ route('artikel.index') }}" class="lg:hidden flex items-center justify-center gap-3 bg-[#006633] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-green-100 mt-2">
                    Lihat Berita Lainnya <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @endif
        </div>

        {{-- 3. BOTTOM GRID --}}
        <div class="hidden lg:grid grid-cols-4 gap-6 mt-8">
            @foreach($listBerita->skip(3) as $item)
            <a href="{{ route('artikel.show', $item->slug) }}" class="group flex flex-col">
                <div class="aspect-video rounded-2xl overflow-hidden mb-4 relative shadow-sm">
                    <img src="{{ asset('storage/' . $item->gambar_cover) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform">
                </div>
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $item->kategori }}</span>
                <h5 class="text-sm font-bold text-gray-900 mt-1 line-clamp-2 group-hover:text-[#006633] transition-colors">{{ $item->judul }}</h5>
                <p class="text-gray-500 text-xs mt-2 line-clamp-2">{{ str($item->isi_konten)->stripTags()->limit(80) }}</p>
            </a>
            @endforeach

            <a href="{{ route('artikel.index') }}" class="group flex flex-col items-center justify-center bg-white rounded-2xl border-2 border-dashed border-gray-200 hover:border-[#006633] hover:bg-[#006633]/5 transition-all p-6 min-h-[150px]">
                <div class="w-12 h-12 bg-[#006633] rounded-full flex items-center justify-center text-white mb-3 shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>
                <span class="font-black text-[#006633] text-[18px] text-center">Lihat Berita Lainnya</span>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection