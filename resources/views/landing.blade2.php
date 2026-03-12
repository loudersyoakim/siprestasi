<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIARPRESTASI - Universitas Negeri Medan</title>
    @vite(['resources\css\app.css', 'resources\js\app.js', 'resources\js\landing.js'])
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.3.0/highcharts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.3.0/highcharts-3d.min.js"></script> -->
    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts-3d.js') }}"></script>
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

    <nav id="mainNav" class="fixed top-0 left-0 w-full z-50 bg-[#006633] text-white px-6 py-4 flex justify-between items-center border-b border-white/10 transition-all duration-500 ease-in-out">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo" class="h-10 w-auto transition-all duration-500" id="navLogo">
            <div class="border-l border-white/20 pl-3">
                <h2 id="navTitle" class="font-black text-sm lg:text-base tracking-tight leading-none uppercase transition-all duration-500">Universitas Negeri Medan</h2>
                <p id="navTagline" class="text-[7px] uppercase tracking-widest text-white/60 transition-all duration-500">The Character Building University</p>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-10">
            <a href="#section-berita" class="group relative text-xs font-bold tracking-widest uppercase pb-1 hover:-translate-y-1 transition-all duration-300">
                ARTIKEL
                <span class="absolute bottom-0 left-0 w-0 h-[3px] bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/login" class="bg-white/10 border border-white/20 px-8 py-2.5 rounded-full font-bold text-xs hover:bg-white hover:text-[#006633] transition-all duration-300">
                LOGIN
            </a>
        </div>

        <button id="menuBtn" class="md:hidden flex flex-col justify-center items-center gap-1.5 w-10 h-10 z-[100] relative outline-none transition-all duration-300">
            <span class="w-6 h-0.5 bg-white transition-all duration-300"></span>
            <span class="w-6 h-0.5 bg-white transition-all duration-300"></span>
            <span class="w-6 h-0.5 bg-white transition-all duration-300"></span>
        </button>

        <div id="mobileMenu" class="fixed inset-0 bg-white z-[60] flex flex-col items-center justify-center gap-10 translate-x-full transition-transform duration-500 ease-in-out md:hidden">
            <a href="#section-berita" class="mobile-link text-md font-black text-[#006633] tracking-[0.3em] uppercase border-b-2 border-transparent hover:border-yellow-400 pb-1 transition-all">
                ARTIKEL
            </a>
            <a href="/login" class="mobile-link text-md font-black text-[#006633] tracking-[0.3em] uppercase border-2 border-[#006633] px-12 py-3.5 rounded-full hover:bg-[#006633] hover:text-white hover:shadow-lg transition-all">
                LOGIN
            </a>
        </div>
    </nav>

    <section class="relative min-h-screen w-full flex pt-24 bg-white overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-cover bg-center opacity-40"
                style="background-image: url('{{ asset('img/fmipa-unimed3.jpg') }}');">
            </div>

            <div class="absolute inset-0 bg-gradient-to-l from-black/30 via-black/40 to-transparent"></div>
        </div>

        <div class="absolute inset-0 bg-gradient-to-tl from-[#006633]/50 via-transparent to-transparent z-10"></div>

        <div class="container mx-auto px-6 pt-10 lg:px-20 relative z-20 flex flex-col lg:flex-row gap-12 w-full flex-1">


            <div class="lg:w-1/2 flex flex-col justify-center lg:pb-24 pb-10">
                <div class="w-16 h-1 bg-[#006633] mb-4"></div>
                <h1 class="text-4xl lg:text-6xl font-black text-gray-900 leading-none uppercase mb-4 tracking-tight">
                    Sistem Arsip <br> <span class="text-[#006633]">Prestasi</span> <br> Mahasiswa
                </h1>
                <p class="text-lg text-black-700 font-medium leading-relaxed max-w-xl mb-8">
                    Sistem informasi terpadu untuk pendataan dan dokumentasi riwayat prestasi mahasiswa secara sistematis.
                </p>

                <div class="flex items-center gap-3">
                    <a href="/login" class="bg-[#006633]/80 backdrop-blur-md border border-white/20 text-white px-8 py-3 rounded-full font-semibold text-sm shadow-sm hover:bg-[#006633] hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                        LOGIN
                    </a>
                </div>
            </div>

            <div class="lg:w-1/2 w-full flex flex-col gap-5 justify-end lg:pb-12 pb-8 mt-auto">
                <div class="relative w-full flex flex-col z-10">
                    <div class="flex flex-col items-end gap-1.5 relative z-10 pr-4">

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
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#006633]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-full flex flex-col z-10">
                        <div class="relative h-64 sm:h-72 lg:h-80 flex justify-center items-center z-10 drop-shadow-2xl mt-2">
                            <div id="unimed3DPie" class="w-full h-full"></div>
                        </div>

                        <div id="customLegend" class="grid grid-cols-2 gap-x-4 gap-y-2 mt-4 px-6 overflow-y-auto max-h-[100px] scrollbar-hide">
                        </div>
                    </div>

                </div>

                <div class="flex justify-end items-center gap-2 w-full mt-2 pr-4">
                    <div class="w-1/4 h-3 bg-gradient-to-r from-transparent to-[#006633] rounded-full"></div>
                    <div class="w-1/2 h-3 bg-yellow-400 rounded-full shadow-[0_0_10px_rgba(251,191,36,0.5)]"></div>
                </div>
            </div>

        </div>
    </section>
    <section class="py-20 bg-gray-50/50">
        <div class="container mx-auto px-6 lg:px-20">

            <div class="flex justify-between items-end mb-10">
                <div>
                    <div class="w-12 h-1 bg-[#006633] mb-3"></div>
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tight">Berita & <span class="text-[#006633]">Pengumuman</span></h2>
                    <p class="text-gray-500 mt-2">Update informasi prestasi dan kegiatan mahasiswa terbaru.</p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">

                <div class="col-span-12 lg:col-span-7">
                    <div class="group relative bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col w-full">
                        <div class="relative flex-1 overflow-hidden min-h-[220px]">
                            <img src="https://images.unsplash.com/photo-1523240632012-97066b35854c?auto=format&fit=crop&q=80"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                alt="Berita Utama">
                            <div class="absolute top-6 left-6">
                                <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-black uppercase text-[#006633] flex items-center gap-2 shadow-sm">
                                    <span class="w-2 h-2 bg-[#006633] rounded-full animate-pulse"></span>
                                    Liputan Utama
                                </span>
                            </div>
                        </div>
                        <div class="p-8">
                            <span class="text-xs font-bold text-gray-400">18 Februari 2026</span>
                            <h3 class="text-2xl font-black text-gray-900 mt-3 leading-tight group-hover:text-[#006633] transition-colors">
                                Rektor Unimed Resmikan Pusat Inovasi Digital: Wadah Baru Akselerasi Prestasi Mahasiswa di Era Smart Campus
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-5 flex flex-col gap-6">
                    <div class="group flex flex-1 bg-white rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition-all p-4 gap-4">
                        <div class="w-32 h-full min-h-[120px] flex-shrink-0 rounded-xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80" class="w-full h-full object-cover" alt="News">
                        </div>
                        <div class="flex flex-col justify-center">
                            <span class="text-[10px] font-bold text-gray-400">15 Februari 2026</span>
                            <h4 class="font-bold text-gray-900 mt-1 leading-snug group-hover:text-[#006633]">Mahasiswa FMIPA Unimed Ciptakan Alat Filter Limbah Berbasis AI yang Diakui Internasional</h4>
                        </div>
                    </div>

                    <div class="group flex flex-1 bg-white rounded-[1.5rem] overflow-hidden shadow-sm hover:shadow-md transition-all p-4 gap-4">
                        <div class="w-32 h-full min-h-[120px] flex-shrink-0 rounded-xl overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&q=80" class="w-full h-full object-cover" alt="News">
                        </div>
                        <div class="flex flex-col justify-center">
                            <span class="text-[10px] font-bold text-gray-400">12 Februari 2026</span>
                            <h4 class="font-bold text-gray-900 mt-1 leading-snug group-hover:text-[#006633]">Tim Debat Unimed Sabet Juara Umum di National University Debating Championship 2026</h4>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-span-12 grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                <div class="group">
                    <div class="aspect-video rounded-2xl overflow-hidden mb-3">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform" alt="News">
                    </div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase">Prestasi Internasional</span>
                    <h5 class="text-sm font-bold text-gray-900 mt-1 group-hover:text-[#006633] line-clamp-2">Delegasi Mahasiswa Unimed Sabet Medali Emas di World Invention Exhibition</h5>
                </div>

                <div class="group">
                    <div class="aspect-video rounded-2xl overflow-hidden mb-3">
                        <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform" alt="News">
                    </div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase">Inovasi Kampus</span>
                    <h5 class="text-sm font-bold text-gray-900 mt-1 group-hover:text-[#006633] line-clamp-2">Riset IndoBERT Mahasiswa Unimed Mendapat Pengakuan Jurnal Internasional Q1</h5>
                </div>

                <div class="group">
                    <div class="aspect-video rounded-2xl overflow-hidden mb-3">
                        <img src="https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform" alt="News">
                    </div>
                    <span class="text-[9px] font-bold text-gray-400 uppercase">Kegiatan Sosial</span>
                    <h5 class="text-sm font-bold text-gray-900 mt-1 group-hover:text-[#006633] line-clamp-2">Bakti Sosial Mahasiswa Unimed di Desa Terpencil Sumatera Utara</h5>
                </div>

                <a href="#" class="group flex flex-col items-center justify-center bg-white rounded-2xl border-2 border-dashed border-gray-200 hover:border-[#006633] hover:bg-[#006633]/5 transition-all p-6">
                    <div class="w-12 h-12 bg-[#006633] rounded-full flex items-center justify-center text-white mb-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                    <span class="font-black text-[#006633] text-sm text-center">Lihat Berita<br>Lainnya</span>
                </a>
            </div>

        </div>
        </div>
    </section>
    <footer class="bg-[#006633] text-white pt-16 pb-8">
        <div class="container mx-auto px-6 lg:px-20">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-12">

                <div class="md:col-span-7">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo Unimed" class="h-12 w-auto ">
                        <div class="border-l border-white/50 pl-4">
                            <h2 class="font-black text-xl tracking leading-none uppercase">Universitas Negeri Medan</h2>
                            <p class="text-[9px] uppercase tracking-[0.2em] text-white/70">The Character Building University</p>
                        </div>
                    </div>

                    <!-- <div class="flex gap-4">
                        <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all"><i class="fab fa-facebook-f text-xs"></i></a>
                        <a href="https://www.instagram.com/unimedofficial" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all"><i class="fab fa-instagram text-xs"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all"><i class="fab fa-youtube text-xs"></i></a>
                    </div> -->
                    <p class="text-white/80 text-sm leading-relaxed max-w-sm mb-6">
                        Jalan Willem Iskandar Psr. V - Kotak Pos No. 1589, <br>
                        Kenangan Baru, Kec. Percut Sei Tuan, <br>
                        Kabupaten Deli Serdang, Sumatera Utara 20221
                    </p>
                </div>


                <div class="md:col-span-3">
                    <h3 class="font-bold text-lg mb-6 relative inline-block">
                        Hubungi Kami
                        <span class="absolute -bottom-2 left-0 w-8 h-1 bg-yellow-400 rounded-full"></span>
                    </h3>
                    <div class="space-y-4">
                        <a href="tel:0616613365" class="flex items-center gap-4 group">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center group-hover:bg-yellow-400 group-hover:text-[#006633] transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase text-white/50 font-bold tracking-widest">Telepon Kantor</p>
                                <p class="font-bold text-white">(061) 6613365</p>
                            </div>
                        </a>
                        <a href="mailto:humas@unimed.ac.id" class="flex items-center gap-4 group">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center group-hover:bg-yellow-400 group-hover:text-[#006633] transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase text-white/50 font-bold tracking-widest">Email Humas</p>
                                <p class="font-bold text-white">humas@unimed.ac.id</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-center gap-4">
                <p class="text-xs text-white/50">
                    &copy; 2026 <span class="text-white font-bold">Universitas Negeri Medan</span>.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>