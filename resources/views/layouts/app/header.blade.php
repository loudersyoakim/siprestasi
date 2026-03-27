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

    {{-- Kanan: Help & Profil --}}
    <div class="flex items-center gap-5">
        
        {{-- TOMBOL HELP (Membuka Modal Bantuan) --}}
        <button onclick="toggleHelpModal()" class="flex items-center justify-center w-10 h-10 text-gray-400 hover:text-[#006633] hover:bg-green-50 rounded-full transition-all" title="Bantuan">
            <i class="bi bi-question-circle text-xl"></i>
        </button>

        {{-- Profil --}}
        <div class="relative">
            <div class="flex items-center gap-3 cursor-pointer" id="profile-btn">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5 font-semibold tracking-wide">
                        {{ Auth::user()->nim_nip ?? Auth::user()->role->nama_role }}
                    </p>
                </div>
                
                {{-- Foto Profil Dinamis (Langsung dari tabel users) --}}
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" 
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