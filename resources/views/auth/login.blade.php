<x-layouts::auth>
    <div class="fixed inset-0 z-0 bg-gray-50 flex items-center justify-center">
        <img src="{{ asset('img/fmipa-unimed3.jpg') }}" alt="Background" class="w-full h-full object-cover object-center scale-105" />
        <div class="absolute top-0 left-0 w-96 h-96 bg-[#006633]/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute inset-0 bg-white/40 backdrop-blur-sm"></div>
    </div>

    <div class="relative z-10 w-full max-w-sm mx-auto">
        <div class="bg-white border border-gray-100 rounded-2xl p-8 sm:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)] relative overflow-hidden">
            
            {{-- ========================================================= --}}
            {{-- POPUP PERINGATAN AKTIVASI (Ditampilkan Jika Session Ada) --}}
            {{-- ========================================================= --}}
            @if(session('aktivasi_pending'))
            <div id="aktivasi-modal" class="absolute inset-0 z-50 bg-white/95 backdrop-blur-md flex flex-col items-center justify-center p-8 text-center animate-fade-in">
                <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mb-4 mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-2">Akun Belum Aktif</h3>
                <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                    Pendaftaran Anda telah berhasil, namun akun ini masih <strong class="text-gray-800">menunggu aktivasi</strong> oleh Admin agar dapat digunakan.
                </p>
                <div class="flex flex-col gap-3 w-full">
                    <a href="https://wa.me/6281234567890" target="_blank" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold text-xs py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.88-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi Admin (WhatsApp)
                    </a>
                    <button onclick="document.getElementById('aktivasi-modal').style.display='none'" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs py-2.5 rounded-lg transition-colors">
                        Tutup Pesan
                    </button>
                </div>
            </div>
            @endif
            {{-- ========================================================= --}}

            <div class="flex flex-col items-center text-center mb-8">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                    SI<span class="text-[#006633]">PRESTASI</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1.5 font-medium uppercase tracking-widest">LOGIN</p>
            </div>

            <x-auth-session-status class="mb-6 text-center text-sm font-medium text-[#006633] bg-[#006633]/10 py-2.5 rounded-lg border border-[#006633]/20" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                @csrf

                <div class="relative">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] focus:bg-white transition-all shadow-sm placeholder-gray-400"
                            placeholder="Username">
                    </div>
                    <span class="text-[10px] leading-none font-medium text-gray-400">
                        Gunakan NIM/NIP atau Email yang terdaftar
                    </span>
                    @error('email')
                    <p class="text-red-500 text-xs font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative mt-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl pl-11 pr-11 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] focus:bg-white transition-all shadow-sm placeholder-gray-400"
                            placeholder="Password">

                        <button type="button" onclick="const p=document.getElementById('password'); p.type=p.type==='password'?'text':'password';" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#006633] transition-colors focus:outline-none">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        </label>
                    <a href="#" onclick="alert('Silakan hubungi Admin di nomor +6281234567890 untuk reset kata sandi.')"
                        class="text-xs font-bold text-[#006633] hover:text-[#004d26] underline-offset-4 hover:underline transition-all">
                        Lupa Password?
                    </a>
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full bg-[#006633] hover:bg-[#004d26] text-white font-bold text-sm tracking-wider uppercase rounded-xl py-3.5 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        LOGIN
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
            <div class="mt-8 text-sm text-center text-gray-500">
                <span>Belum memiliki akun?</span>
                <a href="{{ route('register') }}" wire:navigate class="font-bold text-[#006633] hover:text-[#004d26] ml-1 hover:underline underline-offset-4 transition-all">Daftar di sini</a>
            </div>
            @endif

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-semibold text-gray-400 hover:text-gray-600 transition-colors group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</x-layouts::auth>