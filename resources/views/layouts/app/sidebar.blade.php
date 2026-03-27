<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white flex flex-col h-screen border-r border-gray-200 transform -translate-x-full md:translate-x-0 md:sticky md:top-0 transition-transform duration-300 ease-in-out">    
    
    {{-- LOGO AREA (Jarak diperkecil) --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-3">
            <img src="{{ !empty($pengaturan['logo_aplikasi']) ? asset('storage/' . $pengaturan['logo_aplikasi']) : asset('img/logo-unimed.png') }}" alt="Logo" class="h-9 w-auto">
            <h1 class="text-xl font-black tracking-tight text-gray-800 uppercase">
                @php
                    $appName = $pengaturan['nama_aplikasi'] ?? 'SIPRESTASI';
                    $first = substr($appName, 0, 4);
                    $last = substr($appName, 4);
                @endphp
                {{ $first }}<span class="text-[#006633]">{{ $last }}</span>
            </h1>
        </div>
        <button class="md:hidden text-gray-400 hover:text-gray-700" onclick="toggleSidebar()"><i class="bi bi-x-lg text-lg"></i></button>
    </div>

    {{-- NAVIGASI AREA (Jarak antar menu dirapatkan) --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto custom-scrollbar">

        {{-- 1. DASHBOARD --}}
        @php
            $dashRoute = match(Auth::user()->role->kode_role) {
                'SA' => 'super_admin.dashboard', 'AD' => 'admin.dashboard', 'FK' => 'fakultas.dashboard',
                'JR' => 'jurusan.dashboard', 'MHS' => 'mahasiswa.dashboard', default => 'home'
            };
        @endphp
        <a href="{{ route($dashRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-sm font-medium {{ request()->routeIs('*dashboard*') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
            <i class="bi bi-grid-1x2-fill text-lg"></i> <span>Dashboard</span>
        </a>

        {{-- ================================================================= --}}
        {{-- MENU KHUSUS MAHASISWA (Self Service) --}}
        {{-- ================================================================= --}}
        @if(Auth::user()->role->kode_role === 'MHS')
            <a href="{{ route('mahasiswa.profil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-sm font-medium {{ request()->is('*/profil*') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <i class="bi bi-person-badge-fill text-lg"></i> <span>Profil Saya</span>
            </a>

            @if(Auth::user()->hasPermission('prestasi.create'))
            <div class="relative">
                <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium {{ request()->is('*/prestasi/create*') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-award-fill text-lg"></i> <span>Input Prestasi</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/prestasi/create*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ request()->is('*/prestasi/create*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    <a href="{{ route('mahasiswa.prestasi.create') }}" class="block py-1.5 px-3 text-sm transition-all rounded-lg {{ request()->routeIs('mahasiswa.prestasi.create') ? 'text-[#006633] font-bold bg-green-50 hover:bg-green-100' : 'text-gray-500 hover:text-[#006633] hover:bg-gray-50' }}">Form Input Data</a>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermission('prestasi.view_own'))
            <a href="{{ route('mahasiswa.prestasi') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-sm font-medium {{ request()->is('*/prestasi') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <i class="bi bi-clock-history text-lg"></i> <span>Riwayat Prestasi</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('surat.create'))
            <div class="relative">
                <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-[#006633]">
                    <div class="flex items-center gap-3"><i class="bi bi-envelope-plus-fill text-lg"></i> <span>Ajukan Surat</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform"></i>
                </button>
                <div class="hidden mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    <a href="#" class="block py-1.5 px-3 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg transition-all">Surat Keterangan</a>
                </div>
            </div>
            @endif
        @endif

        {{-- ================================================================= --}}
        {{-- MENU MANAJEMEN (Super Admin, Admin, Fakultas, Jurusan) --}}
        {{-- ================================================================= --}}
        
        {{-- 2. MANAJEMEN AKUN --}}
        @if(Auth::user()->hasPermission('akun.view_list'))
        <div class="relative mt-2">
            <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium {{ request()->is('*manajemen-akun*') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-people-fill text-lg"></i> <span>Manajemen Akun</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*manajemen-akun*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*manajemen-akun*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                <a href="{{ route(Auth::user()->role->kode_role == 'SA' ? 'super_admin.manajemen-akun' : 'admin.manajemen-akun') }}" 
                   class="block py-1.5 px-3 text-sm transition-all rounded-lg {{ request()->routeIs('*.manajemen-akun') ? 'text-[#006633] font-bold bg-green-50 hover:bg-green-100' : 'text-gray-500 hover:text-[#006633] hover:bg-gray-50' }}">Daftar Pengguna</a>
                
                @if(Auth::user()->hasPermission('akun.manage_role'))
                <a href="{{ route('akun.role-permission') }}" 
                   class="block py-1.5 px-3 text-sm transition-all rounded-lg {{ request()->routeIs('akun.role-permission') ? 'text-[#006633] font-bold bg-green-50 hover:bg-green-100' : 'text-gray-500 hover:text-[#006633] hover:bg-gray-50' }}">Role dan Hak Akses</a>
                @endif
            </div>
        </div>
        @endif

        {{-- 3. MANAJEMEN PRESTASI --}}
        @if(Auth::user()->hasPermission('prestasi.view_all') || Auth::user()->hasPermission('prestasi.validate'))
        @php 
            $isManagePrestasi = (request()->is('*/prestasi*') && !request()->is('*/prestasi/create*'));
        @endphp
        <div class="relative">
            <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium {{ $isManagePrestasi ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-trophy-fill text-lg"></i> <span>Manajemen Prestasi</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ $isManagePrestasi ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ $isManagePrestasi ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                @php
                    $prestasiGlobalRoute = match(Auth::user()->role->kode_role) {
                        'SA' => 'super_admin.prestasi', 'AD' => 'admin.prestasi',
                        'FK' => 'fakultas.prestasi', 'JR' => 'jurusan.prestasi', default => 'home'
                    };
                @endphp
                <a href="{{ route($prestasiGlobalRoute) }}" class="block py-1.5 px-3 text-sm transition-all rounded-lg {{ request()->routeIs('*.prestasi') ? 'text-[#006633] font-bold bg-green-50 hover:bg-green-100' : 'text-gray-500 hover:text-[#006633] hover:bg-gray-50' }}">Semua Prestasi</a>
                
                @if(Auth::user()->hasPermission('prestasi.validate'))
                <a href="#" class="py-1.5 px-3 text-sm transition-all rounded-lg flex justify-between items-center text-gray-500 hover:text-[#006633] hover:bg-gray-50">
                    Validasi / Tolak <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- 4. MANAJEMEN SURAT --}}
        @if(Auth::user()->hasPermission('surat.view_all'))
        <div class="relative">
            <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium {{ request()->is('*/surat*') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-envelope-paper-fill text-lg"></i> <span>Manajemen Surat</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/surat*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/surat*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                <a href="#" class="block py-1.5 px-3 text-sm transition-all rounded-lg text-gray-500 hover:text-[#006633] hover:bg-gray-50">Surat Masuk & Keluar</a>
            </div>
        </div>
        @endif

        {{-- 5. MANAJEMEN KONTEN --}}
        @if(Auth::user()->hasPermission('konten.manage_artikel'))
        <div class="relative">
            <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium {{ request()->is('*/konten*') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-newspaper text-lg"></i> <span>Manajemen Konten</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/konten*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/konten*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                <a href="#" class="block py-1.5 px-3 text-sm transition-all rounded-lg text-gray-500 hover:text-[#006633] hover:bg-gray-50">Berita & Artikel</a>
            </div>
        </div>
        @endif

        {{-- 6. MASTER DATA --}}
        @if(Auth::user()->hasPermission('master.akademik') || Auth::user()->hasPermission('prestasi.config_form'))
        @php $isMaster = request()->is('*/struktur-akademik*') || request()->is('*/manajemen-form*'); @endphp
        <div class="relative">
            <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium {{ $isMaster ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-database-fill-gear text-lg"></i> <span>Master Data</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ $isMaster ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ $isMaster ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                @if(Auth::user()->hasPermission('master.akademik'))
                <a href="{{ route('super_admin.struktur-akademik') }}" class="block py-1.5 px-3 text-sm transition-all rounded-lg {{ request()->routeIs('super_admin.struktur-akademik') ? 'text-[#006633] font-bold bg-green-50 hover:bg-green-100' : 'text-gray-500 hover:text-[#006633] hover:bg-gray-50' }}">Struktur Akademik</a>
                @endif
                
                @if(Auth::user()->hasPermission('prestasi.config_form'))
                <a href="{{ route('super_admin.manajemen-form') }}" class="block py-1.5 px-3 text-sm transition-all rounded-lg {{ request()->routeIs('super_admin.manajemen-form') ? 'text-[#006633] font-bold bg-green-50 hover:bg-green-100' : 'text-gray-500 hover:text-[#006633] hover:bg-gray-50' }}">Kategori Kegiatan</a>
                @endif
            </div>
        </div>
        @endif

        {{-- 7. LAPORAN & REKAP --}}
        @if(Auth::user()->hasPermission('laporan.generate'))
        <div class="relative">
            <button class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-medium {{ request()->is('*/laporan*') ? 'bg-green-50 text-[#006633] font-bold shadow-[inset_4px_0_0_0_#006633] hover:bg-green-100' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-clipboard-data-fill text-lg"></i> <span>Laporan & Rekap</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/laporan*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/laporan*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                <a href="#" class="block py-1.5 px-3 text-sm transition-all rounded-lg text-gray-500 hover:text-[#006633] hover:bg-gray-50">Per Periode</a>
            </div>
        </div>
        @endif

    </nav>

    {{-- LOGOUT AREA --}}
    <div class="p-4 bg-gray-50 border-t border-gray-200 flex-shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-2.5 font-bold text-red-600 bg-white border border-red-100 rounded-xl hover:bg-red-50 transition-all shadow-sm">
                <i class="bi bi-box-arrow-left"></i>
                <span class="text-sm">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>