<header class="sticky top-0 z-40 bg-white border-b border-gray-100 shadow-sm px-4 md:px-8 py-3 flex justify-between items-center">

    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
            <i class="bi bi-list text-2xl"></i>
        </button>

        <div class="flex flex-col items-start">
            <h2 class="text-sm md:text-lg font-black text-gray-800 tracking-tight leading-none uppercase">
                <span class="hidden md:inline">Selamat Datang, </span>
                <span class="text-[#006633]">{{ Auth::user()->name }}</span>
            </h2>
        </div>
    </div>

    <div class="flex items-center gap-3 md:gap-6">

        <button onclick="toggleHelpModal()" class="flex items-center gap-2 px-3 py-2 bg-gray-50 hover:bg-yellow-50 text-gray-500 hover:text-yellow-700 rounded-xl transition-all border border-gray-200 group">
            <i class="bi bi-question-circle-fill text-lg"></i>
            <span class="text-xs font-bold uppercase tracking-wider hidden md:block">Bantuan</span>
        </button>

        <div class="h-8 w-[1px] bg-gray-100 hidden md:block"></div>

        <div class="flex items-center gap-3 md:gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-[10px] md:text-xs font-black text-gray-900 leading-none capitalize">{{ Auth::user()->role }}</p>
            </div>

            <div class="relative" x-data="{ openProfile: false }">
                <button id="profile-btn" class="w-9 h-9 md:w-10 md:h-10 bg-[#006633] hover:bg-[#004d26] text-white rounded-xl flex items-center justify-center font-bold shadow-md transition-all transform hover:scale-105 focus:outline-none">
                   <i class="bi bi-person-fill text-xl md:text-2xl"></i>
                </button>

                <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 z-50">
                    <div class="px-4 py-2 border-b border-gray-50 mb-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Akun Terhubung</p>
                        <p class="text-xs font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 font-bold hover:bg-red-50 flex items-center gap-2 transition-colors">
                            <i class="bi bi-box-arrow-right"></i> Keluar Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="help-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-6 bg-[#006633] text-white flex justify-between items-center">
                <h3 class="font-black uppercase tracking-tight flex items-center gap-2">
                    <i class="bi bi-info-circle"></i> Panduan Operasional {{ ucfirst(Auth::user()->role) }}
                </h3>
                <button onclick="toggleHelpModal()" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
            </div>

            @include('bantuan')

            <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
                <button onclick="toggleHelpModal()" class="text-xs font-black text-[#006633] uppercase tracking-widest">Tutup Panduan</button>
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