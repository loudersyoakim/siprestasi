<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white flex flex-col h-screen border-r border-gray-200 transform -translate-x-full md:translate-x-0 md:sticky md:top-0 transition-transform duration-300 ease-in-out">    
    {{-- Logo Area (Tetap di atas) --}}
    <div class="flex items-center justify-between p-6 border-b border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo" class="h-10 w-auto">
            <h1 class="text-xl font-black tracking-tight text-gray-800 uppercase">SIAR<span class="text-[#006633]">PRESTASI</span></h1>
        </div>
        <button class="md:hidden text-gray-400 hover:text-gray-700" onclick="toggleSidebar()">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

    {{-- Navigasi Menu (Bisa di-scroll) --}}
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
        
        {{-- ========================================== --}}
        {{-- MENU SUPER ADMIN --}}
        {{-- ========================================== --}}
        @if(Auth::user()->role == 'super_admin')
        
        <a href="{{ route('super_admin.dashboard') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden
                  {{ request()->routeIs('super_admin.dashboard') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('super_admin.dashboard') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="{{ route('super_admin.manajemen-akun') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden
                  {{ request()->routeIs('super_admin.manajemen-akun') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-people-fill text-lg {{ request()->routeIs('super_admin.manajemen-akun') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Manajemen Akun</span>
        </a>
        <a href="{{ route('super_admin.daftar-mahasiswa') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden
                {{ request()->routeIs('*.daftar-mahasiswa') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-mortarboard-fill text-lg {{ request()->routeIs('*.daftar-mahasiswa') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Daftar Mahasiswa</span>
        </a>

        {{-- DROPDOWN PRESTASI SUPER ADMIN --}}
        <div class="relative">
            <button id="btn-prestasi"
                class="relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none overflow-hidden
                       {{ request()->is('super-admin/prestasi*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('super-admin/prestasi*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Kelola Prestasi</span>
                </div>
                <i id="icon-chevron-prestasi" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('super-admin/prestasi*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>

            <div id="menu-prestasi" class="{{ request()->is('super-admin/prestasi*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden transition-all duration-300">
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Tambah Prestasi</span></a>
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Daftar Prestasi</span></a>
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Validasi Prestasi</span></a>
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Laporan & Rekap</span></a>
            </div>
        </div>

        <a href="#" 
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium">
            <i class="bi bi-newspaper text-lg text-gray-400 group-hover:text-[#006633]"></i>
            <span class="text-sm">Manajemen Konten</span>
        </a>

        {{-- DROPDOWN MASTER DATA SUPER ADMIN --}}
        <div class="relative">
            <button id="btn-master"
                class="relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none overflow-hidden
                       {{ request()->is('super-admin/master-data*') || request()->is('super-admin/struktur-akademik*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-database-fill-gear text-lg {{ request()->is('super-admin/master-data*') || request()->is('super-admin/struktur-akademik*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Master Data</span>
                </div>
                <i id="icon-chevron-master" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('super-admin/master-data*') || request()->is('super-admin/struktur-akademik*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>

            <div id="menu-master" class="{{ request()->is('super-admin/master-data*') || request()->is('super-admin/struktur-akademik*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden transition-all duration-300">
                <a href="#" class="block py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]">Surat & Tahun Akademik</a>
                <a href="{{ route('super_admin.struktur-akademik') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('super_admin.struktur-akademik*') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Struktur Akademik</a>
                <a href="#" class="block py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]">Atribut Prestasi</a>
            </div>
        </div>
        @endif

        {{-- ========================================== --}}
        {{-- MENU ADMIN --}}
        {{-- ========================================== --}}
        @if(Auth::user()->role == 'admin')
        
        <a href="{{ route('admin.dashboard') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden
                  {{ request()->routeIs('admin.dashboard') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('admin.dashboard') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Beranda Dashboard</span>
        </a>

        <a href="{{ route('admin.manajemen-akun') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden
                  {{ request()->routeIs('admin.manajemen-akun') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-people-fill text-lg {{ request()->routeIs('admin.manajemen-akun') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Manajemen Akun</span>
        </a>

        {{-- DROPDOWN PRESTASI ADMIN --}}
        <div class="relative">
            <button id="btn-prestasi"
                class="relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none overflow-hidden
                       {{ request()->is('admin/prestasi*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('admin/prestasi*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Kelola Prestasi</span>
                </div>
                <i id="icon-chevron-prestasi" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('admin/prestasi*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>

            <div id="menu-prestasi" class="{{ request()->is('admin/prestasi*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden transition-all duration-300">
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Tambah Prestasi</span></a>
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Daftar Prestasi</span></a>
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Validasi Prestasi</span></a>
                <a href="#" class="group flex items-center justify-between py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]"><span>Laporan & Rekap</span></a>
            </div>
        </div>

        <a href="#" 
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium">
            <i class="bi bi-newspaper text-lg text-gray-400 group-hover:text-[#006633]"></i>
            <span class="text-sm">Manajemen Konten</span>
        </a>

        {{-- DROPDOWN MASTER DATA ADMIN --}}
        <div class="relative">
            <button id="btn-master"
                class="relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none overflow-hidden
                       {{ request()->is('admin/master-data*') || request()->is('admin/struktur-akademik*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-database-fill-gear text-lg {{ request()->is('admin/master-data*') || request()->is('admin/struktur-akademik*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Master Data</span>
                </div>
                <i id="icon-chevron-master" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('admin/master-data*') || request()->is('admin/struktur-akademik*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>

            <div id="menu-master" class="{{ request()->is('admin/master-data*') || request()->is('admin/struktur-akademik*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden transition-all duration-300">
                <a href="#" class="block py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]">Surat & Tahun Akademik</a>
                <a href="{{ route('admin.struktur-akademik') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.struktur-akademik*') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Struktur Akademik</a>
                <a href="#" class="block py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]">Atribut Prestasi</a>
            </div>
        </div>
        @endif

        {{-- ========================================== --}}
        {{-- MENU MAHASISWA --}}
        {{-- ========================================== --}}
        @if(Auth::user()->role == 'mahasiswa')
        
        <a href="{{ route('mahasiswa.dashboard') }}" 
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden
                  {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('mahasiswa.dashboard') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Beranda Dashboard</span>
        </a>

        <a href="{{ route('mahasiswa.profil') }}" 
            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group overflow-hidden
                  {{ request()->routeIs('mahasiswa.profil') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-person-badge-fill text-lg {{ request()->routeIs('mahasiswa.profil') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Profil Saya</span>
        </a>

        {{-- Logika Placeholder Mahasiswa (Disesuaikan dengan kondisimu) --}}
        @php $isProfileComplete = true; /* Ganti kembali dengan cek profile aslimu */ @endphp
        
        @if($isProfileComplete)
        <div class="relative">
            <button id="btn-prestasi-mhs" 
                class="relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none overflow-hidden
                       {{ request()->is('mahasiswa/prestasi*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('mahasiswa/prestasi*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Prestasi Saya</span>
                </div>
                <i id="icon-chevron-prestasi-mhs" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('mahasiswa/prestasi*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>
            <div id="menu-prestasi-mhs" class="{{ request()->is('mahasiswa/prestasi*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden">
                <a href="#" class="block py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]">Tambah Prestasi</a>
                <a href="#" class="block py-2 text-sm transition-colors text-gray-500 hover:text-[#006633]">Daftar Prestasi</a>
            </div>
        </div>
        @else
        <div class="px-4 py-3 opacity-50 cursor-not-allowed flex items-center justify-between group grayscale bg-gray-50 rounded-xl mt-2">
            <div class="flex items-center gap-3">
                <i class="bi bi-lock-fill text-lg text-gray-400"></i>
                <span class="text-sm font-semibold text-gray-500">Prestasi (Terkunci)</span>
            </div>
        </div>
        <p class="px-4 mt-1 text-[10px] text-red-500 italic leading-tight">Lengkapi profil untuk membuka menu prestasi</p>
        @endif
        @endif
    </nav>

    {{-- Tombol Logout (Tetap di bawah) --}}
    <div class="p-4 bg-gray-50 border-t border-gray-200 flex-shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-red-600 transition-all bg-white border border-red-100 shadow-sm hover:bg-red-50 rounded-xl group">
                <i class="bi bi-box-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-sm">Keluar</span>
            </button>
        </form>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fungsi untuk mengatur klik dropdown
        function setupDropdown(btnId, menuId, iconId) {
            const btn = document.getElementById(btnId);
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId);

            if (btn && menu && icon) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Toggle menu (tampilkan / sembunyikan)
                    if (menu.classList.contains('hidden')) {
                        menu.classList.remove('hidden');
                        menu.classList.add('block');
                        icon.classList.add('rotate-180', 'text-[#006633]');
                        icon.classList.remove('text-gray-400');
                    } else {
                        menu.classList.add('hidden');
                        menu.classList.remove('block');
                        icon.classList.remove('rotate-180', 'text-[#006633]');
                        icon.classList.add('text-gray-400');
                    }
                });
            }
        }

        // Inisialisasi tombol dropdown untuk Admin/Super Admin
        setupDropdown('btn-prestasi', 'menu-prestasi', 'icon-chevron-prestasi');
        setupDropdown('btn-master', 'menu-master', 'icon-chevron-master');
        
        // Inisialisasi tombol dropdown untuk Mahasiswa
        setupDropdown('btn-prestasi-mhs', 'menu-prestasi-mhs', 'icon-chevron-prestasi-mhs');
    });
</script>