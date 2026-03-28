@extends('layouts.front')

@section('title', 'Beranda - SIARPRESTASI Universitas Negeri Medan')

@section('content')

@php
    // SIMULASI CEK PENGATURAN (Nanti data ini datang dari LandingSetting Controller)
    $showLeaderboard = $pengaturan['show_leaderboard'] ?? true; 
    $showStatistics  = $pengaturan['show_statistics'] ?? true;
    
    // Hitung widget aktif untuk logika layout & carousel
    $activeWidgets = ($showLeaderboard ? 1 : 0) + ($showStatistics ? 1 : 0);
    $hasWidget = $activeWidgets > 0;
@endphp

{{-- 1. HERO SECTION DINAMIS --}}
<section class="relative min-h-screen w-full flex items-center justify-center bg-white overflow-hidden pt-20 lg:pt-0">
    <div class="absolute inset-0 z-0">
        {{-- Gambar Background Asli --}}
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('img/fmipa-unimed3.jpg') }}');"></div>
        <div class="absolute inset-0 bg-white/70 backdrop-blur-[5px]"></div>
        <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </div>
    
    <div class="container mx-auto px-6 lg:px-20 relative z-20">
        
        {{-- LAYOUT BUNGKUSAN DINAMIS --}}
        <div class="{{ $hasWidget ? 'grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center' : 'flex flex-col items-center justify-center text-center mx-auto max-w-3xl' }}">
            
            {{-- AREA TEKS UTAMA --}}
            <div class="{{ $hasWidget ? 'lg:col-span-6 flex flex-col items-start text-left' : 'flex flex-col items-center text-center' }}">
                <div class="w-16 h-1.5 bg-[#006633] mb-6 rounded-full shadow-sm"></div>
                
                <span class="text-[#006633] font-black tracking-[0.25em] uppercase text-sm lg:text-base mb-3 drop-shadow-sm">
                    {{ $pengaturan['nama_aplikasi'] ?? 'SIARPRESTASI UNIMED' }}
                </span>
                
                <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-black text-gray-900 leading-[1.1] uppercase mb-6 tracking-tight drop-shadow-md {{ $hasWidget ? 'max-w-2xl' : 'max-w-3xl' }}">
                    {{ $pengaturan['hero_title_1'] ?? 'Sistem Arsip' }} 
                    <span class="text-[#006633]">{{ $pengaturan['hero_title_2'] ?? 'Prestasi' }}</span> 
                    {{ $pengaturan['hero_title_3'] ?? 'Mahasiswa' }}
                </h1>
                
                <p class="text-lg md:text-xl text-gray-800 font-medium leading-relaxed mb-10 drop-shadow-sm {{ $hasWidget ? 'max-w-lg' : 'max-w-xl' }}">
                    {{ $pengaturan['deskripsi_landing'] ?? 'Platform terpadu untuk mencatat setiap pencapaian luar biasa mahasiswa secara real-time.' }}
                </p>
                
                <a href="/login" class="w-max bg-[#006633] border border-white/20 text-white px-10 py-4 rounded-full font-bold text-sm hover:bg-green-800 hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-green-900/20 uppercase tracking-widest flex items-center gap-2">
                    Login Sistem <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            {{-- AREA WIDGET (HANYA MUNCUL JIKA ADA YAN AKTIF) --}}
            @if($hasWidget)
            <div class="lg:col-span-6 flex justify-center lg:justify-end w-full">
                
                {{-- Container Widget dengan ukuran tetap agar tidak goyang saat transisi --}}
                <div class="relative w-full max-w-[26rem] h-[480px] group">
                    
                    {{-- WIDGET 1: LEADERBOARD --}}
                    @if($showLeaderboard)
                    <div class="widget-slide absolute inset-0 transition-all duration-1000 opacity-100 z-10">
                        <div class="w-full h-full bg-white rounded-[2rem] p-8 shadow-2xl shadow-gray-200/60 flex flex-col border border-gray-50">
                            
                            {{-- Header Widget Minimalis --}}
                            <div class="flex items-center gap-4 mb-4">
                                <i class="bi bi-trophy text-3xl text-[#006633]"></i>
                                <div>
                                    <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm">Top Leaderboard</h3>
                                    <p class="text-[9px] text-gray-500 uppercase tracking-widest mt-0.5">Berdasarkan Total Poin</p>
                                </div>
                            </div>
                            <hr class="border-gray-100 mb-6">

                            {{-- List Leaderboard Clean UI --}}
                            <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4">
                                @forelse($leaderboard ?? [] as $index => $mhs)
                                <div class="flex items-center gap-4 group/item">
                                    {{-- Rank Number --}}
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 
                                        {{ $index == 0 ? 'bg-yellow-400 text-white shadow-md shadow-yellow-200' : 
                                          ($index == 1 ? 'bg-gray-300 text-white shadow-md shadow-gray-200' : 
                                          ($index == 2 ? 'bg-[#CD7F32] text-white shadow-md shadow-orange-200' : 
                                          'bg-gray-100 text-gray-500')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    
                                    {{-- Profile Photo --}}
                                    <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-gray-100 bg-gray-50">
                                        {{-- Gunakan UI Avatars jika foto profil tidak ada --}}
                                        <img src="{{ isset($mhs->foto_profil) && $mhs->foto_profil ? asset('storage/' . $mhs->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($mhs->name) . '&background=f3f4f6&color=374151&bold=true' }}" 
                                             alt="{{ $mhs->name }}" class="w-full h-full object-cover">
                                    </div>

                                    {{-- Info Mhs --}}
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 leading-tight truncate group-hover/item:text-[#006633] transition-colors">{{ $mhs->name }}</h4>
                                        <span class="text-[10px] text-gray-500 truncate block mt-0.5">{{ $mhs->prodi->nama_prodi ?? 'Prodi' }}</span>
                                    </div>
                                    
                                    {{-- Poin --}}
                                    <div class="text-right shrink-0">
                                        <span class="text-sm font-bold text-[#006633]">{{ number_format($mhs->total_poin ?? 0) }}</span>
                                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest block">Poin</span>
                                    </div>
                                </div>
                                @empty
                                <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-70">
                                    <i class="bi bi-award text-4xl mb-2"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Belum Ada Klasemen</span>
                                </div>
                                @endforelse
                            </div>

                        </div>
                    </div>
                    @endif

                    {{-- WIDGET 2: GRAFIK STATISTIK IKU --}}
                    @if($showStatistics)
                    <div class="widget-slide absolute inset-0 transition-all duration-1000 opacity-0 z-0">
                        <div class="w-full h-full bg-white rounded-[2rem] p-8 shadow-2xl shadow-gray-200/60 flex flex-col border border-gray-50">
                            
                            {{-- Header Widget Minimalis --}}
                            <div class="flex items-center gap-4 mb-4">
                                <i class="bi bi-bar-chart text-3xl text-[#006633]"></i>
                                <div>
                                    <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm">Statistik Capaian</h3>
                                    <p class="text-[9px] text-gray-500 uppercase tracking-widest mt-0.5">{{ $pengaturan['stat_title'] ?? 'Sebaran Prestasi Fakultas' }}</p>
                                </div>
                            </div>
                            <hr class="border-gray-100 mb-6">

                            {{-- Area Grafik --}}
                            <div class="flex-1 w-full relative flex items-center justify-center bg-gray-50/50 rounded-2xl border border-gray-100 p-4">
                                <canvas id="heroChart" data-type="{{ $pengaturan['stat_type'] ?? 'doughnut' }}"></canvas>
                                <div id="chartLoading" class="absolute inset-0 flex items-center justify-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    Memuat Data...
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                </div>

                {{-- Indikator Dots Carousel (Di bawah Card) --}}
                @if($activeWidgets > 1)
                <div class="absolute -bottom-12 left-0 w-full flex justify-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#006633] transition-all dot-indicator cursor-pointer"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-gray-300 transition-all dot-indicator cursor-pointer"></div>
                </div>
                @endif

            </div>
            @endif

        </div>
    </div>
</section>

{{-- SECTION BERITA & PENGUMUMAN (Tidak Dirubah) --}}
<section id="section-berita" class="py-20 bg-gray-50/50">
    <div class="container mx-auto px-6 lg:px-20">
        <div class="mb-10 text-center flex flex-col items-center">
            <div class="w-12 h-1 bg-[#006633] mb-3"></div>
            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Berita & <span class="text-[#006633]">Pengumuman</span></h2>
        </div>

        @if(isset($headline))
        <div class="grid grid-cols-12 gap-6">
            {{-- 1. HEADLINE --}}
            <div class="col-span-12 lg:col-span-7">
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
            @if(isset($listBerita) && $listBerita->count() > 0)
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
        @if(isset($listBerita) && $listBerita->count() > 3)
        <div class="hidden lg:grid grid-cols-4 gap-6 mt-8">
            @foreach($listBerita->skip(3)->take(3) as $item)
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
        
        @endif
    </div>
</section>

{{-- SCRIPT UNTUK CROSSFADE WIDGET --}}
@if($activeWidgets > 1)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const slides = document.querySelectorAll('.widget-slide');
        const dots = document.querySelectorAll('.dot-indicator');
        let currentSlide = 0;

        setInterval(() => {
            // Sembunyikan yang sekarang
            slides[currentSlide].classList.remove('opacity-100', 'z-10');
            slides[currentSlide].classList.add('opacity-0', 'z-0');
            dots[currentSlide].classList.remove('bg-[#006633]');
            dots[currentSlide].classList.add('bg-gray-300');

            // Maju 1 langkah
            currentSlide = (currentSlide + 1) % slides.length;

            // Munculkan yang baru
            slides[currentSlide].classList.remove('opacity-0', 'z-0');
            slides[currentSlide].classList.add('opacity-100', 'z-10');
            dots[currentSlide].classList.remove('bg-gray-300');
            dots[currentSlide].classList.add('bg-[#006633]');
        }, 5000); // Cross-fade setiap 5 detik
    });
</script>
@endif

@endsection