<header class="sticky top-0 z-40 bg-white border-b border-gray-200 px-4 md:px-8 py-4 flex justify-between items-center">
    
    {{-- Kiri: Ucapan Selamat Datang / Toggle Mobile --}}
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
            <i class="bi bi-list text-2xl"></i>
        </button>
        
        <div class="hidden md:flex flex-col items-start">
            <h2 class="text-lg font-black text-gray-800 tracking-tight leading-none">
                Selamat Datang, <span class="text-[#006633]">{{ Auth::user()->name }}</span>
            </h2>
        </div>
    </div>

    {{-- Kanan: Teks Unimed & Profil --}}
    <div class="flex items-center gap-5">
        
        {{-- Teks Institusi ala SIMPEG (Sembunyi di HP)
        <div class="hidden lg:flex flex-col items-end border-r border-gray-200 pr-5">
            <h2 class="text-sm font-black text-[#006633] tracking-wide uppercase leading-tight">Universitas Negeri Medan</h2>
            <p class="text-[10px] text-gray-500 italic">"The Character Building University"</p>
        </div> --}}

        {{-- Bantuan --}}
        {{-- <button onclick="toggleHelpModal()" class="flex items-center justify-center w-9 h-9 text-gray-400 hover:text-yellow-500 hover:bg-yellow-50 rounded-full transition-all" title="Bantuan">
            <i class="bi bi-question-circle text-xl"></i>
        </button> --}}

        {{-- Profil --}}
        <div class="relative" x-data="{ openProfile: false }">
            <div class="flex items-center gap-3 cursor-pointer" id="profile-btn">
    <div class="text-right hidden sm:block">
        <p class="text-xs font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</p>
        
        {{-- TAMPILKAN NIM JIKA ADA, JIKA TIDAK TAMPILKAN ROLE --}}
        <p class="text-[10px] text-gray-500 capitalize mt-0.5 font-semibold tracking-wide">
            @if(Auth::user()->nim_nip)
                {{ Auth::user()->nim_nip }}
            @else
                {{ str_replace('_', ' ', Auth::user()->role) }}
            @endif
        </p>
    </div>
    
    {{-- TAMPILKAN FOTO PROFIL JIKA ADA, JIKA TIDAK TAMPILKAN INISIAL NAMA --}}
    @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->foto_profil)
        <img src="{{ asset('storage/' . Auth::user()->mahasiswa->foto_profil) }}" 
             alt="Foto Profil" 
             class="w-10 h-10 object-cover border border-green-100 rounded-full shadow-sm transition-transform hover:scale-105">
    @else
        <div class="w-10 h-10 bg-green-50 text-[#006633] border border-green-100 rounded-full flex items-center justify-center font-bold text-lg shadow-sm transition-transform hover:scale-105">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
    @endif
</div>

            {{-- Dropdown Profil --}}
            <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 z-50">
                <div class="px-4 py-3 border-b border-gray-50 mb-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Akun Terhubung</p>
                    <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 font-bold hover:bg-red-50 flex items-center gap-2 transition-colors">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>
<script>
    // JS Manual untuk Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (!sidebar || !overlay) {
            console.error("Elemen sidebar atau overlay tidak ditemukan!");
            return;
        }

        // Toggle Sidebar
        sidebar.classList.toggle('-translate-x-full');

        // Toggle Overlay
        if (overlay.classList.contains('hidden')) {
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.add('opacity-100'), 10);
        } else {
            overlay.classList.remove('opacity-100');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }

    // JS Manual untuk Dropdown Profil
    const profileBtn = document.getElementById('profile-btn');
    const profileDropdown = document.getElementById('profile-dropdown');

    if (profileBtn) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Biar nggak langsung ketutup sama window listener
            profileDropdown.classList.toggle('hidden');
        });
    }

    // JS Manual untuk Modal Help
    function toggleHelpModal() {
        const modal = document.getElementById('help-modal');
        modal.classList.toggle('hidden');
    }

    // Klik di luar untuk menutup
    window.addEventListener('click', (e) => {
        if (profileBtn && !profileBtn.contains(e.target)) {
            profileDropdown.classList.add('hidden');
        }
        if (e.target.id === 'help-modal') {
            toggleHelpModal();
        }
    });
</script>