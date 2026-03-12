<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIARPRESTASI - Universitas Negeri Medan</title>
    @vite(['resources\css\app.css', 'resources\js\app.js', 'resources\js\landing.js'])
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts-3d.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-white font-sans antialiased overflow-x-hidden">
    {{-- NAVIGATION --}}
    <nav id="mainNav" class="fixed top-0 left-0 w-full z-50 bg-[#006633] text-white px-6 py-4 flex justify-between items-center border-b border-white/10 transition-all duration-500 ease-in-out">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo" class="h-10 w-auto transition-all duration-500" id="navLogo">
            <div class="border-l border-white/20 pl-3">
                <h2 id="navTitle" class="font-black text-sm lg:text-base tracking-tight leading-none uppercase transition-all duration-500">Universitas Negeri Medan</h2>
                <p id="navTagline" class="text-[7px] uppercase tracking-widest text-white/60 transition-all duration-500">The Character Building University</p>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-10">
            <a href="{{ route('artikel.index') }}" class="group relative text-xs font-bold tracking-widest uppercase pb-1 hover:-translate-y-1 transition-all duration-300">
                ARTIKEL
                <span class="absolute bottom-0 left-0 w-0 h-[3px] bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/login" class="bg-white/10 border border-white/20 px-8 py-2.5 rounded-full font-bold text-xs hover:bg-white hover:text-[#006633] transition-all duration-300">LOGIN</a>
        </div>
        <button id="menuBtn" class="md:hidden flex flex-col justify-center items-center gap-1.5 w-10 h-10 z-[100] relative outline-none">
            <span class="w-6 h-0.5 bg-white transition-all"></span>
            <span class="w-6 h-0.5 bg-white transition-all"></span>
            <span class="w-6 h-0.5 bg-white transition-all"></span>
        </button>
    </nav>

    {{-- HERO SECTION --}}
    <section class="relative min-h-screen w-full flex pt-24 bg-white overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('{{ asset('img/fmipa-unimed3.jpg') }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-l from-black/30 via-black/40 to-transparent"></div>
        </div>
        <div class="container mx-auto px-6 pt-10 lg:px-20 relative z-20 flex flex-col lg:flex-row gap-12 w-full flex-1">
            <div class="lg:w-1/2 flex flex-col justify-center lg:pb-24 pb-10">
                <div class="w-16 h-1 bg-[#006633] mb-4"></div>
                <h1 class="text-4xl lg:text-6xl font-black text-gray-900 leading-none uppercase mb-4 tracking-tight">
                    Sistem Arsip <br> <span class="text-[#006633]">Prestasi</span> <br> Mahasiswa
                </h1>
                <p class="text-lg text-black-700 font-medium leading-relaxed max-w-xl mb-8">
                    Sistem informasi terpadu untuk pendataan dan dokumentasi riwayat prestasi mahasiswa secara sistematis.
                </p>
                <a href="/login" class="w-max bg-[#006633]/80 backdrop-blur-md border border-white/20 text-white px-8 py-3 rounded-full font-semibold text-sm hover:bg-[#006633] transition-all">LOGIN</a>
            </div>

            {{-- STATISTIK --}}
            <div class="lg:w-1/2 w-full flex flex-col gap-5 justify-end lg:pb-12 pb-8 mt-auto">
                <div class="flex flex-col items-end gap-1.5 pr-4 w-full">
                    <div class="flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full mb-1 border border-white/20 shadow-xl">
                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                        <span class="text-white font-black text-[10px] uppercase tracking-[0.25em]">
                            Statistik Capaian
                        </span>
                    </div>
                    <div class="relative inline-block z-10">
                        <select id="filterChart" class="appearance-none bg-white/70 backdrop-blur-md border border-[#006633]/20 text-[#006633] font-black text-[10px] rounded-full pl-4 pr-10 py-2 shadow-lg hover:bg-white hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#006633]/10 cursor-pointer transition-all">
                            <option value="tingkat">Berdasarkan Tingkat</option>
                            <option value="fakultas">Berdasarkan Fakultas</option>
                            <option value="jurusan">Berdasarkan Jurusan</option>
                            <option value="prodi">Berdasarkan Program Studi</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#006633]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- PERBAIKAN: Tambah w-full agar melebar full dan flex justify-center --}}
                    <div class="relative w-full h-64 sm:h-72 lg:h-80 drop-shadow-2xl flex justify-center items-center">
                        <div id="unimed3DPie" class="w-full h-full"></div>
                    </div>

                    <div class="flex justify-end items-center gap-2 w-full mt-2 pr-4">
                        <div class="w-1/4 h-3 bg-gradient-to-r from-transparent to-[#006633] rounded-full"></div>
                        <div class="w-1/2 h-3 bg-yellow-400 rounded-full shadow-[0_0_10px_rgba(251,191,36,0.5)]"></div>
                    </div>
                </div>
            </div>
    </section>

    {{-- SECTION BERITA & ARTIKEL --}}
    <section id="section-berita" class="py-20 bg-gray-50/50">
        <div class="container mx-auto px-6 lg:px-20">
            <div class="mb-10">
                <div class="w-12 h-1 bg-[#006633] mb-3"></div>
                <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Berita & <span class="text-[#006633]">Pengumuman</span></h2>
            </div>

            @if($headline)
            <div class="grid grid-cols-12 gap-6">
                {{-- 1. HEADLINE --}}
                {{-- col-span-12 (Mobile) pindah ke lg:col-span-7 (Desktop 1024px+) --}}
                <div class="col-span-12 lg:col-span-7">
                    <a href="{{ route('artikel.show', $headline->slug) }}" class="group relative bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col h-full">
                        <div class="relative w-full min-h-[250px] lg:min-h-[320px] overflow-hidden">
                            <img src="{{ asset('storage/' . $headline->thumbnail) }}"
                                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-all duration-700">
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[9px] font-black uppercase text-[#006633] shadow-sm">
                                    {{ $headline->category }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-1 justify-center">
                            <span class="text-[10px] font-bold text-gray-400">{{ $headline->created_at->translatedFormat('d F Y') }}</span>
                            <h3 class="text-xl lg:text-2xl font-black text-gray-900 mt-2 leading-tight line-clamp-2 group-hover:text-[#006633] transition-colors">
                                {{ $headline->title }}
                            </h3>
                            <p class="text-gray-500 mt-2 text-sm leading-relaxed line-clamp-2">
                                {{ str($headline->content)->stripTags()->limit(130) }}
                            </p>
                        </div>
                    </a>
                </div>

                {{-- 2. SIDE LIST --}}
                @if($listBerita->count() > 0)
                <div class="col-span-12 lg:col-span-5 flex flex-col gap-4">
                    {{-- Kita ambil 3 data, tapi data ke-3 kita sembunyikan di mobile --}}
                    @foreach($listBerita->take(3) as $index => $item)
                    <a href="{{ route('artikel.show', $item->slug) }}"
                        class="group bg-white rounded-[1.5rem] shadow-sm hover:shadow-md transition-all border border-transparent hover:border-green-100 p-3 lg:p-4 gap-4 items-center 
                          {{ $index === 2 ? 'hidden lg:flex' : 'flex' }}"> {{-- KUNCI: Data ke-3 (index 2) sembunyi di mobile --}}

                        <div class="w-2/5 h-24 lg:h-28 relative">
                            <div class="w-full h-full rounded-2xl overflow-hidden relative shadow-sm">
                                <img src="{{ asset('storage/' . $item->thumbnail) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                        </div>

                        <div class="w-3/5 flex flex-col justify-center">
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">{{ $item->category }}</span>
                            <h4 class="font-bold text-gray-900 text-sm leading-tight line-clamp-2 group-hover:text-[#006633] transition-colors">{{ $item->title }}</h4>
                            <p class="text-gray-500 text-[10px] mt-2 line-clamp-2 leading-relaxed">
                                {{ str($item->content)->stripTags()->limit(60) }}
                            </p>
                        </div>
                    </a>
                    @endforeach

                    {{-- Tombol Lihat Lainnya (Hanya Muncul di Mobile/Tablet < 1024px) --}}
                    <a href="{{ route('artikel.index') }}" class="lg:hidden flex items-center justify-center gap-3 bg-[#006633] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-green-100 mt-2">
                        Lihat Berita Lainnya <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>

            {{-- 3. BOTTOM GRID (HANYA MUNCUL DI DESKTOP >= 1024px) --}}
            <div class="hidden lg:grid grid-cols-4 gap-6 mt-8">
                @foreach($listBerita->skip(3) as $item)
                <a href="{{ route('artikel.show', $item->slug) }}" class="group flex flex-col">
                    <div class="aspect-video rounded-2xl overflow-hidden mb-4 relative shadow-sm">
                        <img src="{{ asset('storage/' . $item->thumbnail) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform">
                    </div>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $item->category }}</span>
                    <h5 class="text-sm font-bold text-gray-900 mt-1 line-clamp-2 group-hover:text-[#006633] transition-colors">{{ $item->title }}</h5>
                    <p class="text-gray-500 text-xs mt-2 line-clamp-2">{{ str($item->content)->stripTags()->limit(80) }}</p>
                </a>
                @endforeach

                {{-- Tombol Lihat Lainnya versi Desktop --}}
                <a href="#" class="group flex flex-col items-center justify-center bg-white rounded-2xl border-2 border-dashed border-gray-200 hover:border-[#006633] hover:bg-[#006633]/5 transition-all p-6 min-h-[150px]">
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

    {{-- FOOTER --}}
    <footer class="bg-[#006633] text-white pt-16 pb-8">
        <div class="container mx-auto px-6 lg:px-20">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-12">
                <div class="md:col-span-7">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo" class="h-12 w-auto">
                        <div class="border-l border-white/50 pl-4">
                            <h2 class="font-black text-xl tracking leading-none uppercase">Universitas Negeri Medan</h2>
                            <p class="text-[9px] uppercase tracking-[0.2em] text-white/70">The Character Building University</p>
                        </div>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed max-w-sm">
                        Jalan Willem Iskandar Psr. V - Kotak Pos No. 1589, Percut Sei Tuan, Deli Serdang, Sumatera Utara 20221
                    </p>
                </div>
                <div class="md:col-span-3">
                    <h3 class="font-bold text-lg mb-6 relative inline-block">Hubungi Kami<span class="absolute -bottom-2 left-0 w-8 h-1 bg-yellow-400 rounded-full"></span></h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center group-hover:bg-yellow-400 group-hover:text-[#006633] transition-all"><i class="bi bi-telephone"></i></div>
                            <div>
                                <p class="text-[10px] uppercase text-white/50 font-bold">Telepon Kantor</p>
                                <p class="font-bold">(061) 6613365</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-white/10 text-center">
                <p class="text-xs text-white/50">&copy; 2026 <span class="text-white font-bold">Universitas Negeri Medan</span>.</p>
            </div>
        </div>
    </footer>
</body>

</html>