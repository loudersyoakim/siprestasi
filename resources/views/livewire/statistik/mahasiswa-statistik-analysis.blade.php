<div class="space-y-4 md:space-y-6">

    {{-- WELCOME BANNER --}}
    <div class="relative bg-gradient-to-r from-[#006633] to-[#059669] rounded-2xl p-4 sm:p-6 overflow-hidden shadow-sm">
        {{-- Hiasan Background --}}
        <div class="absolute -right-10 -top-20 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none hidden sm:block"></div>
        <div class="absolute right-10 -bottom-10 w-32 h-32 bg-yellow-400/20 rounded-full blur-xl pointer-events-none hidden sm:block"></div>

        <div class="relative z-10 text-white max-w-2xl">
            <h2 class="text-lg sm:text-2xl font-black mb-1 tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}! 👋
            </h2>
            <p class="text-xs sm:text-sm text-white/90 leading-relaxed font-medium">
                Gunakan <strong class="text-yellow-400">SIARPRESTASI</strong> untuk melaporkan dan memantau capaian prestasi Anda.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:gap-4">
        {{-- WARNING KEAMANAN (GANTI PASSWORD) --}}
        <div class="bg-red-50 border border-red-200 rounded-xl p-3 flex flex-row items-center justify-between gap-3 shadow-sm animate-pulse-slow-red">
            <div class="flex items-center gap-3 w-full">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-500 text-white rounded-full flex items-center justify-center text-sm sm:text-base shadow-sm shrink-0">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-red-800 text-xs sm:text-sm">Amankan Akun Anda!</h4>
                    <p class="text-red-600 text-[10px] sm:text-xs mt-0.5 leading-tight hidden sm:block">Segera ganti password bawaan Anda dengan kata sandi yang unik.</p>
                </div>
            </div>
            <a href="{{ route('mahasiswa.profil') }}#keamanan" class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 bg-red-600 text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-wider rounded-lg hover:bg-red-700 transition-all text-center shadow-sm shrink-0">
                Ganti Password
            </a>
        </div>

        {{-- WARNING JIKA PROFIL BELUM LENGKAP --}}
        @if($persentaseProfil < 100)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 flex flex-row items-center justify-between gap-3 shadow-sm animate-pulse-slow-yellow">
            <div class="flex items-center gap-3 w-full">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-400 text-white rounded-full flex items-center justify-center text-sm sm:text-base shadow-sm shrink-0">
                    <i class="bi bi-person-exclamation"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-yellow-800 text-xs sm:text-sm">Profil Belum Lengkap!</h4>
                    <p class="text-yellow-600 text-[10px] sm:text-xs mt-0.5 leading-tight hidden sm:block">Lengkapi data diri agar dapat menambah prestasi.</p>
                </div>
            </div>
            <a href="{{ route('mahasiswa.profil') }}" class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 bg-yellow-500 text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-wider rounded-lg hover:bg-yellow-600 transition-all text-center shadow-sm shrink-0">
                Lengkapi Profil
            </a>
    </div>
    @endif
</div>

{{-- GRID STATISTIK CARD --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

    {{-- CARD 1: KELENGKAPAN PROFIL --}}
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform overflow-hidden relative">
        <div class="flex justify-between items-start mb-2">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-sm sm:text-lg">
                <i class="bi bi-person-lines-fill"></i>
            </div>
            <span class="text-lg sm:text-xl font-black text-gray-800">{{ $persentaseProfil }}%</span>
        </div>
        <h4 class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Profil</h4>
        <div class="w-full bg-gray-100 rounded-full h-1 mt-2">
            <div class="bg-blue-500 h-1 rounded-full transition-all duration-1000" style="width: {{ $persentaseProfil }}%"></div>
        </div>
    </div>

    {{-- CARD 2: TOTAL PRESTASI --}}
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform overflow-hidden relative">
        <div class="flex justify-between items-start mb-2">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-50 text-[#006633] rounded-lg flex items-center justify-center text-sm sm:text-lg">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <span class="text-lg sm:text-xl font-black text-gray-800">{{ $total_prestasi }}</span>
        </div>
        <h4 class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto pt-2">Total Prestasi</h4>
    </div>

    {{-- CARD 3: MENUNGGU VALIDASI --}}
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform overflow-hidden relative">
        <div class="flex justify-between items-start mb-2">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center text-sm sm:text-lg">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <span class="text-lg sm:text-xl font-black text-gray-800">{{ $menunggu_validasi }}</span>
        </div>
        <h4 class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto pt-2">Diproses</h4>
    </div>

    {{-- CARD 4: DITOLAK / PERBAIKAN --}}
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between group hover:-translate-y-1 transition-transform overflow-hidden relative">
        <div class="flex justify-between items-start mb-2">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center text-sm sm:text-lg">
                <i class="bi bi-x-octagon-fill"></i>
            </div>
            <span class="text-lg sm:text-xl font-black text-gray-800">{{ $ditolak }}</span>
        </div>
        <h4 class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto pt-2">Ditolak</h4>
    </div>

</div>

{{-- STYLE ANIMASI --}}
<style>
    .animate-pulse-slow-yellow {
        animation: pulse-slow-yellow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .animate-pulse-slow-red {
        animation: pulse-slow-red 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse-slow-yellow {

        0%,
        100% {
            opacity: 1;
            border-color: rgba(253, 224, 71, 1);
        }

        50% {
            opacity: .9;
            border-color: rgba(253, 224, 71, 0.5);
        }
    }

    @keyframes pulse-slow-red {

        0%,
        100% {
            opacity: 1;
            border-color: rgba(239, 68, 68, 1);
        }

        50% {
            opacity: .9;
            border-color: rgba(239, 68, 68, 0.5);
        }
    }
</style>

</div>