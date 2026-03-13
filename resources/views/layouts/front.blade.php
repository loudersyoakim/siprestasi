<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIARPRESTASI - Universitas Negeri Medan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    {{-- NAVIGATION (Selalu Hijau) --}}
    <nav id="mainNav" class="fixed top-0 left-0 w-full z-50 px-6 py-4 flex justify-between items-center transition-all duration-500 ease-in-out bg-[#006633] text-white shadow-md">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo" class="h-10 w-auto transition-all duration-500" id="navLogo">
            <div class="border-l border-white/20 pl-3">
                <h2 id="navTitle" class="font-black text-sm lg:text-base tracking-tight leading-none uppercase transition-all duration-500">Universitas Negeri Medan</h2>
                <p id="navTagline" class="text-[7px] uppercase tracking-widest text-white/60 transition-all duration-500 overflow-hidden opacity-100 max-h-4">The Character Building University</p>
            </div>
        </div>
        
        {{-- Menu Desktop --}}
        <div class="hidden md:flex items-center gap-10">
            <a href="/" class="group relative text-xs font-bold tracking-widest uppercase pb-1 hover:-translate-y-1 transition-all duration-300 {{ request()->is('/') ? 'text-yellow-400' : '' }}">
                BERANDA
                <span class="absolute bottom-0 left-0 {{ request()->is('/') ? 'w-full' : 'w-0' }} h-[3px] bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
            </a>
            
            <a href="{{ route('artikel.index') }}" class="group relative text-xs font-bold tracking-widest uppercase pb-1 hover:-translate-y-1 transition-all duration-300 {{ request()->is('artikel*') ? 'text-yellow-400' : '' }}">
                ARTIKEL
                <span class="absolute bottom-0 left-0 {{ request()->is('artikel*') ? 'w-full' : 'w-0' }} h-[3px] bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
            </a>
            
            <a href="/login" class="bg-white/10 border border-white/20 px-8 py-2.5 rounded-full font-bold text-xs hover:bg-white hover:text-[#006633] transition-all duration-300">LOGIN</a>
        </div>

        {{-- Tombol Hamburger HP --}}
        <button id="menuBtn" class="md:hidden flex flex-col justify-center items-center gap-1.5 w-10 h-10 z-[100] relative outline-none">
            <span class="w-6 h-0.5 bg-white transition-all duration-300 origin-center"></span>
            <span class="w-6 h-0.5 bg-white transition-all duration-300 origin-center"></span>
            <span class="w-6 h-0.5 bg-white transition-all duration-300 origin-center"></span>
        </button>

        {{-- INI YANG KURANG: Pop-up Menu Mobile --}}
        <div id="mobileMenu" class="fixed top-0 right-0 w-64 h-screen bg-[#00552b] shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-40 flex flex-col pt-24 px-6 gap-6 md:hidden">
            <a href="/" class="mobile-link text-white font-bold text-sm tracking-widest uppercase border-b border-white/10 pb-4 {{ request()->is('/') ? 'text-yellow-400' : '' }}">BERANDA</a>
            <a href="{{ route('artikel.index') }}" class="mobile-link text-white font-bold text-sm tracking-widest uppercase border-b border-white/10 pb-4 {{ request()->is('artikel*') ? 'text-yellow-400' : '' }}">ARTIKEL</a>
            <a href="/login" class="mobile-link bg-white text-[#006633] text-center py-3 rounded-xl font-bold text-sm mt-4 shadow-lg">LOGIN</a>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main>
        @yield('content')
    </main>

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
                <p class="text-xs text-white/50">&copy; {{ date('Y') }} <span class="text-white font-bold">Universitas Negeri Medan</span>.</p>
            </div>
        </div>
    </footer>

    {{-- SCRIPT SCROLL SHRINK & MOBILE MENU --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const nav = document.getElementById("mainNav");
            const logo = document.getElementById("navLogo");
            const title = document.getElementById("navTitle");
            const tagline = document.getElementById("navTagline");
            
            const menuBtn = document.getElementById("menuBtn");
            const mobileMenu = document.getElementById("mobileMenu");
            const spans = menuBtn?.querySelectorAll("span");

            // 1. EFEK SCROLL (Navigasi Menyusut / Mini)
            window.addEventListener("scroll", function () {
                if (!nav) return;
                
                if (window.scrollY > 50) {
                    // Saat discroll turun: padding mengecil, logo mengecil, tagline hilang
                    nav.classList.replace("py-4", "py-2");
                    nav.classList.add("shadow-xl");
                    if (logo) logo.classList.replace("h-10", "h-8");
                    if (title) title.classList.replace("text-base", "text-sm");
                    if (tagline) {
                        tagline.classList.remove("opacity-100", "max-h-4");
                        tagline.classList.add("opacity-0", "max-h-0");
                    }
                } else {
                    // Saat di paling atas: kembali ke ukuran normal
                    nav.classList.replace("py-2", "py-4");
                    nav.classList.remove("shadow-xl");
                    if (logo) logo.classList.replace("h-8", "h-10");
                    if (title) title.classList.replace("text-sm", "text-base");
                    if (tagline) {
                        tagline.classList.remove("opacity-0", "max-h-0");
                        tagline.classList.add("opacity-100", "max-h-4");
                    }
                }
            });

            // 2. EFEK MOBILE MENU (Hamburger Pop-up)
            function toggleMenu(forceClose = false) {
                if (!mobileMenu || !spans) return;
                const isOpen = !mobileMenu.classList.contains("translate-x-full");
                
                if (isOpen || forceClose) {
                    // Tutup Menu
                    mobileMenu.classList.add("translate-x-full");
                    spans[0].style.transform = "none";
                    spans[1].style.opacity = "1";
                    spans[2].style.transform = "none";
                    document.body.classList.remove("overflow-hidden");
                } else {
                    // Buka Menu (Buat logo X)
                    mobileMenu.classList.remove("translate-x-full");
                    spans[0].style.transform = "translateY(8px) rotate(45deg)";
                    spans[1].style.opacity = "0";
                    spans[2].style.transform = "translateY(-8px) rotate(-45deg)";
                    document.body.classList.add("overflow-hidden");
                }
            }

            menuBtn?.addEventListener("click", (e) => {
                e.stopPropagation();
                toggleMenu();
            });

            // Tutup menu jika klik di luar
            document.addEventListener("click", (e) => {
                if (mobileMenu && !mobileMenu.classList.contains("translate-x-full") && !mobileMenu.contains(e.target) && !menuBtn.contains(e.target)) {
                    toggleMenu(true);
                }
            });
        });
    </script>
</body>

</html>