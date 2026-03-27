<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white flex flex-col h-screen border-r border-gray-200 transform -translate-x-full md:translate-x-0 md:sticky md:top-0 transition-transform duration-300 ease-in-out">    
    
    {{-- LOGO AREA --}}
    <div class="flex items-center justify-between p-6 border-b border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-3">
            <img src="{{ !empty($pengaturan['logo_aplikasi']) ? asset('storage/' . $pengaturan['logo_aplikasi']) : asset('img/logo-unimed.png') }}" alt="Logo" class="h-10 w-auto">
            <h1 class="text-xl font-black tracking-tight text-gray-800 uppercase">
                @php
                    $appName = $pengaturan['nama_aplikasi'] ?? 'SIPRESTASI';
                    $first = substr($appName, 0, 4);
                    $last = substr($appName, 4);
                @endphp
                {{ $first }}<span class="text-[#006633]">{{ $last }}</span>
            </h1>
        </div>
        <button class="md:hidden text-gray-400 hover:text-gray-700" onclick="toggleSidebar()"><i class="bi bi-x-lg text-xl"></i></button>
    </div>

    {{-- NAVIGASI AREA --}}
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">

        {{-- 1. DASHBOARD --}}
        @php
            $dashRoute = match(Auth::user()->role->kode_role) {
                'SA' => 'super_admin.dashboard', 'AD' => 'admin.dashboard', 'FK' => 'fakultas.dashboard',
                'JR' => 'jurusan.dashboard', 'MHS' => 'mahasiswa.dashboard', default => 'home'
            };
        @endphp
        <a href="{{ route($dashRoute) }}" class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->is('*/dashboard') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
            <i class="bi bi-grid-1x2-fill text-lg"></i> <span class="text-sm font-medium">Dashboard</span>
        </a>

        {{-- ================================================================= --}}
        {{-- MENU KHUSUS MAHASISWA (Self Service) --}}
        {{-- ================================================================= --}}
        @if(Auth::user()->role->kode_role === 'MHS')
            <a href="{{ route('mahasiswa.profil') }}" class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->is('*/profil*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <i class="bi bi-person-badge-fill text-lg"></i> <span class="text-sm font-medium">Profil Saya</span>
            </a>

            @if(Auth::user()->hasPermission('prestasi.create'))
            <div class="relative">
                <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('*/prestasi/create*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-award-fill text-lg"></i> <span class="text-sm font-medium">Input Prestasi</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/prestasi/create*') ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ request()->is('*/prestasi/create*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                    <a href="{{ route('mahasiswa.prestasi.create') }}" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Pilih Kategori</a>
                    <a href="{{ route('mahasiswa.prestasi.create') }}" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Isi Data dan Unggah</a>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermission('prestasi.view_own'))
            <a href="{{ route('mahasiswa.prestasi') }}" class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->is('*/prestasi') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <i class="bi bi-clock-history text-lg"></i> <span class="text-sm font-medium">Riwayat Prestasi</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('surat.create'))
            <div class="relative">
                <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none text-gray-600 hover:bg-gray-50 hover:text-[#006633]">
                    <div class="flex items-center gap-3"><i class="bi bi-envelope-plus-fill text-lg"></i> <span class="text-sm font-medium">Ajukan Surat</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform"></i>
                </button>
                <div class="hidden mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                    <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Surat Keterangan</a>
                    <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Surat Rekomendasi</a>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermission('surat.view_own'))
            <a href="#" class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group text-gray-600 hover:bg-gray-50 hover:text-[#006633]">
                <i class="bi bi-folder2-open text-lg"></i> <span class="text-sm font-medium">Riwayat Surat</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('konten.view_public'))
            <a href="#" class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all group text-gray-600 hover:bg-gray-50 hover:text-[#006633]">
                <i class="bi bi-info-circle-fill text-lg"></i> <span class="text-sm font-medium">Informasi</span>
            </a>
            @endif
        @endif

        {{-- ================================================================= --}}
        {{-- MENU MANAJEMEN (Super Admin, Admin, Fakultas, Jurusan) --}}
        {{-- ================================================================= --}}
        
        {{-- 2. MANAJEMEN AKUN --}}
        @if(Auth::user()->hasPermission('akun.view_list'))
        <div class="relative mt-2">
            <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('*/manajemen-akun*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-people-fill text-lg"></i> <span class="text-sm font-medium">Manajemen Akun</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/manajemen-akun*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/manajemen-akun*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                <a href="{{ route(Auth::user()->role->kode_role == 'SA' ? 'super_admin.manajemen-akun' : 'admin.manajemen-akun') }}" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Daftar Pengguna</a>
                @if(Auth::user()->hasPermission('akun.manage_role'))
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Role dan Hak Akses</a>
                @endif
            </div>
        </div>
        @endif

        {{-- 3. MANAJEMEN PRESTASI --}}
        @if(Auth::user()->hasPermission('prestasi.view_all') || Auth::user()->hasPermission('prestasi.validate'))
        <div class="relative">
            <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('*/prestasi*') && !request()->is('*/prestasi/create*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-trophy-fill text-lg"></i> <span class="text-sm font-medium">Manajemen Prestasi</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/prestasi*') && !request()->is('*/prestasi/create*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/prestasi*') && !request()->is('*/prestasi/create*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                @php
                    $prestasiRoute = match(Auth::user()->role->kode_role) {
                        'SA' => 'super_admin.prestasi', 'AD' => 'admin.prestasi',
                        'FK' => 'fakultas.prestasi', 'JR' => 'jurusan.prestasi', default => 'home'
                    };
                @endphp
                <a href="{{ route($prestasiRoute) }}" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Semua Prestasi</a>
                
                @if(Auth::user()->hasPermission('prestasi.validate'))
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg flex justify-between items-center">
                    Validasi / Tolak <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                </a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Riwayat Validasi</a>
                @endif
                
                @if(Auth::user()->hasPermission('prestasi.create'))
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Input Manual</a>
                @endif
            </div>
        </div>
        @endif

        {{-- 4. MANAJEMEN SURAT --}}
        @if(Auth::user()->hasPermission('surat.view_all'))
        <div class="relative">
            <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('*/surat*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-envelope-paper-fill text-lg"></i> <span class="text-sm font-medium">Manajemen Surat</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/surat*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/surat*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Surat Masuk & Keluar</a>
                @if(Auth::user()->hasPermission('surat.process'))
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Proses Surat</a>
                @endif
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Cetak Surat</a>
                @if(Auth::user()->hasPermission('surat.config_template'))
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Template Surat</a>
                @endif
            </div>
        </div>
        @endif

        {{-- 5. MANAJEMEN KONTEN --}}
        @if(Auth::user()->hasPermission('konten.manage_artikel'))
        <div class="relative">
            <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('*/konten*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-newspaper text-lg"></i> <span class="text-sm font-medium">Manajemen Konten</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/konten*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/konten*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Berita dan Artikel</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Pengumuman</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Galeri Prestasi</a>
            </div>
        </div>
        @endif

        {{-- 6. MASTER DATA --}}
        @if(Auth::user()->hasPermission('master.akademik'))
        <div class="relative">
            <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('*/struktur*') || request()->is('*/manajemen-form*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-database-fill-gear text-lg"></i> <span class="text-sm font-medium">Master Data</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/struktur*') || request()->is('*/manajemen-form*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/struktur*') || request()->is('*/manajemen-form*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                <a href="{{ route('super_admin.struktur-akademik') }}" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Fakultas / Jurusan / Prodi</a>
                @if(Auth::user()->hasPermission('prestasi.config_form'))
                <a href="{{ route('super_admin.manajemen-form') }}" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Kategori Kegiatan</a>
                @endif
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Capaian Prestasi</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Tingkat Kegiatan</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Jenis Kepesertaan</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Tahun Akademik</a>
            </div>
        </div>
        @endif

        {{-- 7. LAPORAN & REKAP --}}
        @if(Auth::user()->hasPermission('laporan.generate'))
        <div class="relative">
            <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none {{ request()->is('*/laporan*') ? 'bg-[#006633]/10 text-[#006633] font-bold before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-[#006633]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#006633]' }}">
                <div class="flex items-center gap-3"><i class="bi bi-clipboard-data-fill text-lg"></i> <span class="text-sm font-medium">Laporan dan Rekap</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform {{ request()->is('*/laporan*') ? 'rotate-180' : '' }}"></i>
            </button>
            <div class="{{ request()->is('*/laporan*') ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Per Periode</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Per Fakultas / Jurusan</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Export Excel dan PDF</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Grafik Statistik</a>
            </div>
        </div>
        @endif

        {{-- 8. PENGATURAN SISTEM --}}
        @if(Auth::user()->hasPermission('sistem.config'))
        <div class="relative mt-1 border-b pb-6 border-gray-100">
            <button class="nav-dropdown-toggle relative flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none text-gray-600 hover:bg-gray-50 hover:text-[#006633]">
                <div class="flex items-center gap-3"><i class="bi bi-gear-fill text-lg"></i> <span class="text-sm font-medium">Pengaturan Sistem</span></div>
                <i class="bi bi-chevron-down text-xs transition-transform"></i>
            </button>
            <div class="hidden mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-4 py-1">
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Info Institusi</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Logo dan Tampilan</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Format Nomor Surat</a>
                <a href="#" class="block py-2 px-2 text-sm text-gray-500 hover:text-[#006633] hover:bg-green-50 rounded-lg">Backup dan Restore</a>
            </div>
        </div>
        @endif

    </nav>

    {{-- Logout --}}
    <div class="p-4 bg-gray-50 border-t border-gray-200 flex-shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-3 font-bold text-red-600 bg-white border border-red-100 rounded-xl hover:bg-red-50 transition-all shadow-sm">
                <i class="bi bi-box-arrow-left"></i>
                <span class="text-sm">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
