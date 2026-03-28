<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white flex flex-col h-screen border-r border-gray-200 transform -translate-x-full md:translate-x-0 md:sticky md:top-0 transition-transform duration-300 ease-in-out">    
    
    {{-- LOGO AREA --}}
    <div class="flex items-center justify-between p-4 border-b border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-3">
            @php 
                $logoValue = $pengaturan['logo_aplikasi'] ?? 'logo-unimed.png';
                $logoSrc = ($logoValue === 'logo-unimed.png') ? asset('img/logo-unimed.png') : asset('storage/' . $logoValue);
            @endphp
            <img src="{{ $logoSrc }}" alt="Logo" class="h-9 w-auto">
            <h1 class="text-xl font-black tracking-tight text-gray-800 uppercase">
                @php
                    $appName = $pengaturan['nama_aplikasi'] ?? 'SIPRESTASI';
                    $first = substr($appName, 0, 2);
                    $last = substr($appName, 2);
                @endphp
                {{ $first }}<span class="text-[#006633]">{{ $last }}</span>
            </h1>
        </div>
        <button class="md:hidden text-gray-400 hover:text-gray-700" onclick="toggleSidebar()"><i class="bi bi-x-lg text-lg"></i></button>
    </div>

    {{-- NAVIGASI AREA --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar">

        {{-- 1. DASHBOARD --}}
        @php
            $dashRoute = match(Auth::user()->role->kode_role) {
                'SA' => 'super_admin.dashboard', 'AD' => 'admin.dashboard', 'FK' => 'fakultas.dashboard',
                'JR' => 'jurusan.dashboard', 'MHS' => 'mahasiswa.dashboard', default => 'home'
            };
            $isDashboard = request()->routeIs('*dashboard*');
        @endphp
        <a href="{{ route($dashRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-sm font-bold {{ $isDashboard ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
            <i class="bi bi-grid-1x2-fill text-lg"></i> <span>Dashboard</span>
        </a>

        {{-- ================================================================= --}}
        {{-- MENU KHUSUS MAHASISWA (SELF-SERVICE) --}}
        {{-- ================================================================= --}}
        @if(Auth::user()->role->kode_role === 'MHS')
            <div class="mt-5 mb-2 px-3 text-[10px] font-black tracking-widest text-gray-400 uppercase">Menu Mahasiswa</div>
            
            {{-- Profil --}}
            @php $isProfil = request()->is('*/profil*'); @endphp
            <a href="{{ route('mahasiswa.profil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-sm font-bold {{ $isProfil ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                <i class="bi bi-person-badge-fill text-lg"></i> <span>Profil Saya</span>
            </a>

            {{-- Prestasi MHS --}}
            @if(Auth::user()->hasPermission('prestasi.create') || Auth::user()->hasPermission('prestasi.view_own'))
            @php $isOwnPrestasi = request()->is('*/prestasi*'); @endphp
            <div class="relative">
                <button {{ $isOwnPrestasi ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isOwnPrestasi ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-award-fill text-lg"></i> <span>Prestasi Saya</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isOwnPrestasi ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isOwnPrestasi ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    @if(Auth::user()->hasPermission('prestasi.create'))
                    <a href="{{ route('mahasiswa.prestasi.create') }}" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('mahasiswa.prestasi.create') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Input Prestasi Baru</a>
                    @endif
                    @if(Auth::user()->hasPermission('prestasi.view_own'))
                    <a href="{{ route('mahasiswa.prestasi') }}" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('mahasiswa.prestasi') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Riwayat Prestasi</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Surat MHS --}}
            @if(Auth::user()->hasPermission('surat.create') || Auth::user()->hasPermission('surat.view_own'))
            @php $isOwnSurat = request()->is('*/surat*'); @endphp
            <div class="relative">
                <button {{ $isOwnSurat ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isOwnSurat ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-envelope-plus-fill text-lg"></i> <span>Layanan Surat</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isOwnSurat ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isOwnSurat ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    @if(Auth::user()->hasPermission('surat.create'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Ajukan Surat Baru</a>
                    @endif
                    @if(Auth::user()->hasPermission('surat.view_own'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Riwayat Pengajuan</a>
                    @endif
                </div>
            </div>
            @endif
        @endif

        {{-- ================================================================= --}}
        {{-- MENU MANAJEMEN SISTEM (SA, AD, FK, JR) --}}
        {{-- ================================================================= --}}
        @if(Auth::user()->role->kode_role !== 'MHS')
            <div class="mt-5 mb-2 px-3 text-[10px] font-black tracking-widest text-gray-400 uppercase">Manajemen Sistem</div>

            {{-- 2. MANAJEMEN AKUN --}}
            @if(Auth::user()->hasPermission('akun.view_list') || Auth::user()->hasPermission('akun.manage_role'))
            @php $isAkun = request()->is('*manajemen-akun*') || request()->is('*akun*'); @endphp
            <div class="relative">
                <button {{ $isAkun ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isAkun ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-people-fill text-lg"></i> <span>Manajemen Akun</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isAkun ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isAkun ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    @if(Auth::user()->hasPermission('akun.view_list'))
                    <a href="{{ route(Auth::user()->role->kode_role == 'SA' ? 'super_admin.manajemen-akun' : 'admin.manajemen-akun') }}" 
                       class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('*.manajemen-akun') || request()->routeIs('akun.create') || request()->routeIs('akun.edit') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Daftar Pengguna</a>
                    @endif
                    @if(Auth::user()->hasPermission('akun.manage_role'))
                    <a href="{{ route('akun.role-permission') }}" 
                       class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('akun.role-permission') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Role dan Hak Akses</a>
                    @endif
                </div>
            </div>
            @endif
            
            {{-- 3. MANAJEMEN PRESTASI --}}
            @if(Auth::user()->hasPermission('prestasi.view_all') || Auth::user()->hasPermission('prestasi.validate') || Auth::user()->hasPermission('prestasi.config_form') || Auth::user()->hasPermission('prestasi.config_workflow'))
            
            @php $isManagePrestasi = request()->routeIs('prestasi.*'); @endphp
            
            <div class="relative">
                <button {{ $isManagePrestasi ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isManagePrestasi ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-trophy-fill text-lg"></i> <span>Manajemen Prestasi</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isManagePrestasi ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isManagePrestasi ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    
                    @if(Auth::user()->hasPermission('prestasi.view_all'))
                    <a href="{{ route('prestasi.index-all') }}" 
                       class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('prestasi.index-all') || request()->routeIs('prestasi.create') || request()->routeIs('prestasi.edit') || request()->routeIs('prestasi.show') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Semua Prestasi</a>
                    @endif
                                    
                    @if(Auth::user()->hasPermission('prestasi.validate'))
                    <a href="{{ route('prestasi.validasi') }}" 
                    class="py-2 px-3 text-sm font-bold transition-all rounded-lg flex justify-between items-center {{ request()->routeIs('prestasi.validasi*') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                        <span>Validasi Prestasi</span>
                        @php $pending = \App\Models\Prestasi::where('status', 'Pending')->count(); @endphp
                        @if($pending > 0) 
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span> 
                        @endif
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('prestasi.config_form'))
                    <a href="{{ route('prestasi.formulir-prestasi.index') }}" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('prestasi.formulir-prestasi.*') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Formulir Prestasi</a>
                    @endif

                    @if(Auth::user()->hasPermission('prestasi.config_workflow'))
                    <a href="{{ route('prestasi.alur') }}" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('prestasi.alur*') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Alur Persetujuan</a>
                    @endif
                    
                </div>
            </div>
            @endif
            
            {{-- 4. MANAJEMEN SURAT --}}
            @if(Auth::user()->hasPermission('surat.view_all') || Auth::user()->hasPermission('surat.process'))
            @php $isSurat = request()->is('*/surat*'); @endphp
            <div class="relative">
                <button {{ $isSurat ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isSurat ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-envelope-paper-fill text-lg"></i> <span>Manajemen Surat</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isSurat ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isSurat ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    @if(Auth::user()->hasPermission('surat.view_all'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Surat Masuk/Keluar</a>
                    @endif
                    @if(Auth::user()->hasPermission('surat.process'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Proses Validasi</a>
                    @endif
                    @if(Auth::user()->hasPermission('surat.config_template'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Template Dokumen</a>
                    @endif
                    @if(Auth::user()->hasPermission('surat.config_workflow'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Alur Tanda Tangan</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- 5. MANAJEMEN KONTEN --}}
            @if(Auth::user()->hasPermission('konten.manage_artikel') || Auth::user()->hasPermission('konten.publish_prestasi'))
            @php $isKonten = request()->is('*/konten*'); @endphp
            <div class="relative">
                <button {{ $isKonten ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isKonten ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-newspaper text-lg"></i> <span>Manajemen Konten</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isKonten ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isKonten ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    @if(Auth::user()->hasPermission('konten.manage_artikel'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Berita & Galeri</a>
                    @endif
                    @if(Auth::user()->hasPermission('konten.publish_prestasi'))
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Rilis Publikasi</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- 6. MASTER DATA & SISTEM --}}
            @if(Auth::user()->hasPermission('master.akademik') || Auth::user()->hasPermission('sistem.config'))
            @php $isMaster = request()->is('*/struktur-akademik*') || request()->is('*/pengaturan-sistem*'); @endphp
            <div class="relative">
                <button {{ $isMaster ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isMaster ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-database-fill-gear text-lg"></i> <span>Master Data</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isMaster ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isMaster ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    @if(Auth::user()->hasPermission('master.akademik'))
                    <a href="{{ route('super_admin.struktur-akademik') }}" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('super_admin.struktur-akademik') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Struktur Akademik</a>
                    @endif
                    @if(Auth::user()->hasPermission('sistem.config'))
                    <a href="{{ route('pengaturan-sistem.index') }}" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg {{ request()->routeIs('pengaturan-sistem.*') ? 'text-[#006633] font-bold bg-[#006633]/10' : 'text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]' }}">Pengaturan Sistem</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- 7. LAPORAN & REKAP --}}
            @if(Auth::user()->hasPermission('laporan.generate'))
            @php $isLaporan = request()->is('*/laporan*'); @endphp
            <div class="relative">
                <button {{ $isLaporan ? 'data-active=true' : '' }} class="nav-dropdown-toggle flex items-center justify-between w-full px-3 py-2.5 rounded-xl transition-all focus:outline-none text-sm font-bold {{ $isLaporan ? 'bg-[#006633]/10 text-[#006633] shadow-[inset_4px_0_0_0_#006633]' : 'text-gray-600 hover:bg-[#006633]/5 hover:text-[#006633]' }}">
                    <div class="flex items-center gap-3"><i class="bi bi-clipboard-data-fill text-lg"></i> <span>Laporan & Rekap</span></div>
                    <i class="bi bi-chevron-down text-xs transition-transform {{ $isLaporan ? 'rotate-180' : '' }}"></i>
                </button>
                <div class="{{ $isLaporan ? 'block' : 'hidden' }} mt-1 ml-6 space-y-1 border-l-2 border-gray-100 pl-3 py-1">
                    <a href="#" class="block py-2 px-3 text-sm font-bold transition-all rounded-lg text-gray-500 hover:bg-[#006633]/5 hover:text-[#006633]">Generate Laporan</a>
                </div>
            </div>
            @endif
        @endif

    </nav>

    {{-- LOGOUT AREA --}}
    <div class="p-4 bg-gray-50 border-t border-gray-200 flex-shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-2.5 font-bold text-red-600 bg-white border border-red-100 rounded-xl hover:bg-red-50 hover:border-red-200 transition-all shadow-sm">
                <i class="bi bi-box-arrow-left"></i>
                <span class="text-sm">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
