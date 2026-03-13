<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white flex flex-col min-h-screen border-r border-gray-200 transform -translate-x-full md:translate-x-0 md:static md:flex-shrink-0 transition-transform duration-300 ease-in-out">
    
    {{-- Logo Area --}}
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo" class="h-10 w-auto">
            <h1 class="text-xl font-black tracking-tight text-gray-800 uppercase">SIAR<span class="text-[#006633]">PRESTASI</span></h1>
        </div>
        <button class="md:hidden text-gray-400 hover:text-gray-700" onclick="toggleSidebar()">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

    {{-- Navigasi Menu --}}
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
        <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mt-4 first:mt-0">Menu Utama</p>

        @if(Auth::user()->role == 'admin')
        {{-- DASHBOARD ADMIN --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
                  {{ request()->routeIs('admin.dashboard') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('admin.dashboard') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Beranda Dashboard</span>
        </a>

        <a href="{{ route('admin.manajemen-akun') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
                  {{ request()->routeIs('admin.manajemen-akun') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-people-fill text-lg {{ request()->routeIs('admin.manajemen-akun') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Manajemen Akun</span>
        </a>

        <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 mt-6">Manajemen Data</p>

        {{-- DROPDOWN PRESTASI --}}
        <div class="relative">
            <button id="btn-prestasi"
                class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none
                       {{ request()->is('admin/prestasi*') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('admin/prestasi*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Kelola Prestasi</span>
                </div>
                <i id="icon-chevron-prestasi" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('admin/prestasi*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>

            <div id="menu-prestasi" class="{{ request()->is('admin/prestasi*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden transition-all duration-300">
                <a href="{{ route('admin.prestasi.create') }}" class="group flex items-center justify-between py-2 text-sm transition-colors {{ request()->routeIs('admin.prestasi.create') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}"><span>Tambah Prestasi</span></a>
                <a href="{{ route('admin.prestasi') }}" class="group flex items-center justify-between py-2 text-sm transition-colors {{ request()->routeIs('admin.prestasi') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}"><span>Daftar Prestasi</span></a>
                <a href="{{ route('admin.prestasi.validasi') }}" class="group flex items-center justify-between py-2 text-sm transition-colors {{ request()->routeIs('admin.prestasi.validasi') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">
                    <span>Validasi Prestasi</span>
                    @php $pendingCount = \App\Models\Prestasi::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-sm">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.prestasi.laporan-rekap') }}" class="group flex items-center justify-between py-2 text-sm transition-colors {{ request()->routeIs('admin.prestasi.laporan-rekap') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}"><span>Laporan & Rekap</span></a>
            </div>
        </div>

        <a href="{{ route('admin.manajemen-konten') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium">
            <i class="bi bi-newspaper text-lg text-gray-400 group-hover:text-[#006633]"></i>
            <span class="text-sm">Manajemen Konten</span>
        </a>

        {{-- DROPDOWN MASTER DATA --}}
        <div class="relative">
            <button id="btn-master"
                class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none
                       {{ request()->is('admin/master-data*') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-database-fill-gear text-lg {{ request()->is('admin/master-data*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Master Data</span>
                </div>
                <i id="icon-chevron-master" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('admin/master-data*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>

            <div id="menu-master" class="{{ request()->is('admin/master-data*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden transition-all duration-300">
                <a href="{{ route('admin.master-data.sta') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.master-data.sta') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Surat & Tahun Akademik</a>
                <a href="{{ route('admin.master-data.fakultas') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.master-data.fakultas*') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Fakultas</a>
                <a href="{{ route('admin.master-data.jurusan') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.master-data.jurusan*') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Jurusan</a>
                <a href="{{ route('admin.master-data.prodi') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.master-data.prodi*') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Program Studi</a>
                <a href="{{ route('admin.master-data.atribut-prestasi') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.master-data.atribut-prestasi*') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Atribut Prestasi</a>
            </div>
        </div>
        @endif

        {{-- MENU MAHASISWA --}}
        @if(Auth::user()->role == 'mahasiswa')
        <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('mahasiswa.dashboard') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Beranda Dashboard</span>
        </a>

        <a href="{{ route('mahasiswa.profil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('mahasiswa.profil') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
            <i class="bi bi-person-badge-fill text-lg {{ request()->routeIs('mahasiswa.profil') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            <span class="text-sm">Profil Saya</span>
        </a>

        @php $isProfileComplete = Auth::user()->mahasiswa()->exists(); @endphp
        @if($isProfileComplete)
        <div class="relative">
            <button id="btn-prestasi-mhs" class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('mahasiswa/prestasi*') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633] font-medium' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('mahasiswa/prestasi*') ? 'text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
                    <span class="text-sm">Prestasi Saya</span>
                </div>
                <i id="icon-chevron-prestasi-mhs" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('mahasiswa/prestasi*') ? 'rotate-180 text-[#006633]' : 'text-gray-400 group-hover:text-[#006633]' }}"></i>
            </button>
            <div id="menu-prestasi-mhs" class="{{ request()->is('mahasiswa/prestasi*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-200 pl-4 overflow-hidden">
                <a href="{{ route('mahasiswa.prestasi.create') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('mahasiswa.prestasi.create') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Tambah Prestasi</a>
                <a href="{{ route('mahasiswa.prestasi') }}" class="block py-2 text-sm transition-colors {{ request()->routeIs('mahasiswa.prestasi') ? 'text-[#006633] font-bold' : 'text-gray-500 hover:text-[#006633]' }}">Daftar Prestasi</a>
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

    {{-- Tombol Logout --}}
    <div class="p-4 bg-gray-50 border-t border-gray-200">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-red-600 transition-all bg-white border border-red-100 shadow-sm hover:bg-red-50 rounded-xl group">
                <i class="bi bi-box-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-sm">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>