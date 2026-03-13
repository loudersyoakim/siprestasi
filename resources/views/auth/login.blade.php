<x-layouts::auth>
    <div class="fixed inset-0 z-0 bg-gray-50 flex items-center justify-center">
        <img src="{{ asset('img/fmipa-unimed3.jpg') }}" alt="Background" class="w-full h-full object-cover object-center scale-105" />
        <div class="absolute top-0 left-0 w-96 h-96 bg-[#006633]/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute inset-0 bg-white/40 backdrop-blur-sm"></div>
    </div>

    <div class="relative z-10 w-full max-w-sm mx-auto">
        <div class="bg-white border border-gray-100 rounded-2xl p-8 sm:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)]">
            <div class="flex flex-col items-center text-center mb-8">
                <!-- <img src="{{ asset('img/logo-unimed.png') }}" alt="Logo Universitas Negeri Medan" class="h-16 w-auto mb-5"> -->
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                    SIAR<span class="text-[#006633]">PRESTASI</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1.5 font-medium uppercase tracking-widest">LOGIN</p>
            </div>

            <x-auth-session-status class="mb-6 text-center text-sm font-medium text-[#006633] bg-[#006633]/10 py-2.5 rounded-lg border border-[#006633]/20" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                @csrf

                <div class="relative">
                    <!-- <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email / NIM</label> -->
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
                    <!-- <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label> -->
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

                        <!-- <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-[#006633] focus:ring-[#006633] transition-colors cursor-pointer">
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-gray-900 transition-colors">Ingat Saya</span> -->
                    </label>
                    <a href="#" onclick="alert('Silakan hubungi Admin di ... atau melalui ... untuk reset kata sandi.')"
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