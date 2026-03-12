<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#006633] text-white flex flex-col min-h-screen shadow-xl transform -translate-x-full md:translate-x-0 md:static md:flex-shrink-0 transition-transform duration-300 ease-in-out">
    <div class="flex items-center justify-between p-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo" class="h-10 w-auto">
            <h1 class="text-xl font-black tracking-tight uppercase">SIAR<span class="text-yellow-400">PRESTASI</span></h1>
        </div>
        <button class="md:hidden text-white/70 hover:text-white" onclick="toggleSidebar()">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        @if(Auth::user()->role == 'admin')

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
                  {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('admin.dashboard') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="{{ route('admin.manajemen-akun') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
                  {{ request()->routeIs('admin.manajemen-akun') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
            <i class="bi bi-people-fill text-lg {{ request()->routeIs('admin.manajemen-akun') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
            <span class="text-sm">Manajemen Akun</span>
        </a>

        <div class="relative">
            <button id="btn-prestasi"
                class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none
                       {{ request()->is('admin/prestasi*') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('admin/prestasi*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
                    <span class="text-sm">Prestasi</span>
                </div>
                <i id="icon-chevron-prestasi" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('admin/prestasi*') ? 'rotate-180' : '' }}"></i>
            </button>

            <div id="menu-prestasi" class="{{ request()->is('admin/prestasi*') ? 'block' : 'hidden' }} mt-2 ml-6 space-y-1 border-l-2 border-white/10 pl-4 overflow-hidden transition-all duration-300">

                <a href="{{ route('admin.prestasi.create') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.prestasi.create') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Tambah Prestasi</span>
                </a>

                <a href="{{ route('admin.prestasi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.prestasi') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Daftar Prestasi</span>
                </a>

                <a href="{{ route('admin.prestasi.validasi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.prestasi.validasi') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Validasi Prestasi</span>

                    @php
                    // Hitung prestasi mahasiswa yang butuh validasi
                    $pendingCount = \App\Models\Prestasi::where('status', 'pending')->count();
                    @endphp

                    @if($pendingCount > 0)
                    <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full animate-pulse shadow-lg shadow-red-500/50">
                        {{ $pendingCount }}
                    </span>
                    @else
                    @endif
                </a>

                <a href="{{ route('admin.prestasi.laporan-rekap') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.prestasi.laporan-rekap') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Laporan dan Rekap</span>
                </a>
            </div>
        </div>

        <a href="{{ route('admin.manajemen-konten') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group text-white/80 hover:bg-white/5 hover:text-white font-semibold">
            <i class="bi bi-newspaper text-lg group-hover:text-yellow-400"></i>
            <span class="text-sm">Manajemen Konten</span>
        </a>

        <div class="relative">
            <button id="btn-master"
                class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none
               {{ request()->is('admin/master-data*') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-database-fill-gear text-lg {{ request()->is('admin/master-data*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
                    <span class="text-sm">Master Data</span>
                </div>
                <i id="icon-chevron-master" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('admin/master-data*') ? 'rotate-180' : '' }}"></i>
            </button>

            <div id="menu-master" class="{{ request()->is('admin/master-data*') ? 'block' : 'hidden' }} mt-2 ml-6 space-y-1 border-l-2 border-white/10 pl-4 overflow-hidden transition-all duration-300">

                <a href="{{ route('admin.master-data.sta') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.master-data.sta') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Surat dan Tahun Akademik</span>
                </a>

                <a href="{{ route('admin.master-data.fakultas') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.master-data.fakultas*') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Fakultas</span>
                </a>

                <a href="{{ route('admin.master-data.jurusan') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.master-data.jurusan*') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Jurusan</span>
                </a>

                <a href="{{ route('admin.master-data.prodi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.master-data.prodi*') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Program Studi</span>
                </a>

                <a href="{{ route('admin.master-data.atribut-prestasi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('admin.master-data.atribut-prestasi*') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Atribut Prestasi</span>
                </a>
            </div>
        </div>
        @endif

        @if(Auth::user()->role == 'wd')

        <a href="{{ route('wd.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
                  {{ request()->routeIs('wd.dashboard') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('wd.dashboard') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        <div class="relative">
            <button id="btn-prestasi"
                class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none
                       {{ request()->is('wd/prestasi*') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('wd/prestasi*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
                    <span class="text-sm">Prestasi</span>
                </div>
                <i id="icon-chevron-prestasi" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('wd/prestasi*') ? 'rotate-180' : '' }}"></i>
            </button>

            <div id="menu-prestasi" class="{{ request()->is('wd/prestasi*') ? 'block' : 'hidden' }} mt-2 ml-6 space-y-1 border-l-2 border-white/10 pl-4 overflow-hidden transition-all duration-300">

                <a href="{{ route('wd.prestasi.create') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('wd.prestasi.create') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Tambah Prestasi</span>
                </a>

                <a href="{{ route('wd.prestasi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('wd.prestasi') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Daftar Prestasi</span>
                </a>

                <a href="{{ route('wd.prestasi.validasi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('wd.prestasi.validasi') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Validasi Prestasi</span>

                    @php
                    // Hitung prestasi mahasiswa yang butuh validasi
                    $pendingCount = \App\Models\Prestasi::where('status', 'pending')->count();
                    @endphp

                    @if($pendingCount > 0)
                    <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full animate-pulse shadow-lg shadow-red-500/50">
                        {{ $pendingCount }}
                    </span>
                    @else
                    @endif
                </a>

                <a href="{{ route('wd.prestasi.laporan-rekap') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('wd.prestasi.laporan-rekap') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Laporan dan Rekap</span>
                </a>
            </div>
        </div>

        @endif

        @if(Auth::user()->role == 'kajur')

        <a href="{{ route('kajur.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
                  {{ request()->routeIs('kajur.dashboard') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('kajur.dashboard') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        <div class="relative">
            <button id="btn-prestasi"
                class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none
                       {{ request()->is('kepala-jurusan/prestasi*') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg {{ request()->is('kepala-jurusan/prestasi*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
                    <span class="text-sm">Prestasi</span>
                </div>
                <i id="icon-chevron-prestasi" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('kepala-jurusan/prestasi*') ? 'rotate-180' : '' }}"></i>
            </button>

            <div id="menu-prestasi" class="{{ request()->is('kepala-jurusan/prestasi*') ? 'block' : 'hidden' }} mt-2 ml-6 space-y-1 border-l-2 border-white/10 pl-4 overflow-hidden transition-all duration-300">

                <a href="{{ route('kajur.prestasi.create') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('kajur.prestasi.create') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Tambah Prestasi</span>
                </a>

                <a href="{{ route('kajur.prestasi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('kajur.prestasi') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Daftar Prestasi</span>
                </a>

                <a href="{{ route('kajur.prestasi.validasi') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('kajur.prestasi.validasi') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Validasi Prestasi</span>
                </a>

                <a href="{{ route('kajur.prestasi.laporan-rekap') }}"
                    class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('kajur.prestasi.laporan-rekap') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                    <span>Laporan dan Rekap</span>
                </a>
            </div>
        </div>

        @endif

        @if(Auth::user()->role == 'gpm')

        <div class="my-2 border-t border-white/5 mx-4"></div> <a href="{{ route('gpm.dashboard') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group 
    {{ request()->routeIs('panel.prestasi.index') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
    <i class="bi bi-trophy-fill text-lg {{ request()->routeIs('panel.prestasi.index') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
    <span class="text-sm">Daftar Prestasi</span>
</a>

<a href="{{ route('gpm.prestasi.rekap') }}"
    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group 
    {{ request()->routeIs('panel.prestasi.rekap') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
    <i class="bi bi-file-earmark-bar-graph-fill text-lg {{ request()->routeIs('panel.prestasi.rekap') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
    <span class="text-sm">Rekap Prestasi</span>
</a>

        @endif

        @if(Auth::user()->role == 'mahasiswa')

        {{-- Dashboard --}}
        <a href="{{ route('mahasiswa.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
               {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
            <i class="bi bi-grid-1x2-fill text-lg {{ request()->routeIs('mahasiswa.dashboard') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
            <span class="text-sm">Dashboard</span>
        </a>

        {{-- Profil Saya --}}
        <a href="{{ route('mahasiswa.profil') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
                {{ request()->routeIs('mahasiswa.profil') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
            <i class="bi bi-person-badge-fill text-lg {{ request()->routeIs('mahasiswa.profil') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
            <span class="text-sm">Profil Saya</span>
        </a>

        @php
            // Cek apakah user sudah mengisi data di tabel mahasiswa (profil)
            // Kita asumsikan relasi di model User adalah 'mahasiswa'
            $isProfileComplete = Auth::user()->mahasiswa()->exists();
        @endphp

        @if($isProfileComplete)
            {{-- Jika Profil Lengkap: Tampilkan Dropdown Prestasi --}}
            <div class="relative">
                <button id="btn-prestasi-mhs"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl transition-all group focus:outline-none
                        {{ request()->is('mahasiswa/prestasi*') ? 'bg-white/10 font-bold border border-white/5 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white font-semibold' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-trophy-fill text-lg {{ request()->is('mahasiswa/prestasi*') ? 'text-yellow-400' : 'group-hover:text-yellow-400' }}"></i>
                        <span class="text-sm">Prestasi Saya</span>
                    </div>
                    <i id="icon-chevron-prestasi-mhs" class="bi bi-chevron-down text-xs transition-transform duration-300 {{ request()->is('mahasiswa/prestasi*') ? 'rotate-180' : '' }}"></i>
                </button>

                <div id="menu-prestasi-mhs" class="{{ request()->is('mahasiswa/prestasi*') ? 'block' : 'hidden' }} mt-2 ml-6 space-y-1 border-l-2 border-white/10 pl-4 overflow-hidden">
                    <a href="{{ route('mahasiswa.prestasi.create') }}"
                        class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('mahasiswa.prestasi.create') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                        <span>Tambah Prestasi</span>
                    </a>

                    <a href="{{ route('mahasiswa.prestasi') }}"
                        class="group flex items-center justify-between py-2 text-sm {{ request()->routeIs('mahasiswa.prestasi') ? 'text-yellow-400 font-bold' : 'text-white/60' }} hover:text-yellow-400 transition-colors">
                        <span>Daftar Prestasi</span>
                    </a>
                </div>
            </div>
        @else
            {{-- Jika Profil Belum Lengkap: Tampilkan Menu Terkunci/Disabled --}}
            <div class="px-4 py-3 opacity-50 cursor-not-allowed flex items-center justify-between group grayscale">
                <div class="flex items-center gap-3">
                    <i class="bi bi-trophy-fill text-lg"></i>
                    <span class="text-sm font-semibold">Prestasi (Kunci)</span>
                </div>
                <i class="bi bi-lock-fill text-xs text-yellow-400"></i>
            </div>
            <p class="px-5 text-[10px] text-yellow-200/50 italic leading-tight">Lengkapi profil untuk membuka menu prestasi</p>
        @endif

    @endif
</nav>
    <div class="p-4 bg-black/10 border-t border-white/5">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center w-full gap-3 px-4 py-3 font-bold text-red-300 transition-all hover:bg-red-500/10 rounded-xl hover:text-red-100 group">
                <i class="bi bi-box-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-xs uppercase tracking-widest">Logout</span>
            </button>
        </form>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown Prestasi
        const btnPrestasi = document.getElementById('btn-prestasi');
        const menuPrestasi = document.getElementById('menu-prestasi');
        const iconChevronPrestasi = document.getElementById('icon-chevron-prestasi');

        if (btnPrestasi) {
            btnPrestasi.addEventListener('click', function() {
                menuPrestasi.classList.toggle('hidden');
                iconChevronPrestasi.classList.toggle('rotate-180');
                btnPrestasi.classList.toggle('bg-white/5');
            });
        }

        // Dropdown Master Data
        const btnMaster = document.getElementById('btn-master');
        const menuMaster = document.getElementById('menu-master');
        const iconChevronMaster = document.getElementById('icon-chevron-master');

        if (btnMaster) {
            btnMaster.addEventListener('click', function() {
                menuMaster.classList.toggle('hidden');
                iconChevronMaster.classList.toggle('rotate-180');
                btnMaster.classList.toggle('bg-white/5');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Dropdown Prestasi Mahasiswa
    const btnMhs = document.getElementById('btn-prestasi-mhs');
    const menuMhs = document.getElementById('menu-prestasi-mhs');
    const iconMhs = document.getElementById('icon-chevron-prestasi-mhs');

    if (btnMhs) {
        btnMhs.addEventListener('click', function() {
            menuMhs.classList.toggle('hidden');
            iconMhs.classList.toggle('rotate-180');
            btnMhs.classList.toggle('bg-white/5');
        });
    }
});
</script>