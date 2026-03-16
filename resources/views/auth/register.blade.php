<x-layouts::auth>
    <div class="fixed inset-0 z-0 bg-gray-50 flex items-center justify-center">
        <img src="{{ asset('img/fmipa-unimed3.jpg') }}" alt="Background" class="w-full h-full object-cover object-center scale-105" />
        <div class="absolute top-0 left-0 w-96 h-96 bg-[#006633]/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute inset-0 bg-white/40 backdrop-blur-sm"></div>
    </div>
    <div class="relative z-10 w-full max-w-sm mx-auto">
        <div class="bg-white border border-gray-100 rounded-2xl p-8 sm:p-10 shadow-[0_10px_40px_rgba(0,0,0,0.04)]">

            <div class="flex flex-col items-center text-center mb-8">
                <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                    SI<span class="text-[#006633]">PRESTASI</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-widest">REGITRASI AKUN</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] focus:bg-white transition-all shadow-sm placeholder-gray-400"
                            placeholder="Nama Lengkap">
                    </div>
                    @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nim_nip" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">NIM</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .883.393 1.627 1 2.138A2.001 2.001 0 0112 6h0a2 2 0 011 2.138 4.002 4.002 0 00-1-2.138" />
                            </svg>
                        </div>
                        <input type="text" id="nim_nip" name="nim_nip" value="{{ old('nim_nip') }}" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] focus:bg-white transition-all shadow-sm placeholder-gray-400"
                            placeholder="NIM/NIP">
                    </div>
                    @error('nim_nip') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] focus:bg-white transition-all shadow-sm placeholder-gray-400"
                            placeholder="contoh@unimed.ac.id">
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] focus:bg-white transition-all shadow-sm"
                                placeholder="Min. 8 Karakter">
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Ulangi Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#006633]/20 focus:border-[#006633] focus:bg-white transition-all shadow-sm"
                                placeholder="Konfirmasi">
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-[#006633] hover:bg-[#004d26] text-white font-bold text-sm tracking-wider uppercase rounded-xl py-3.5 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5" data-test="register-user-button">
                        Daftar Akun
                    </button>
                </div>
            </form>

            <div class="mt-2 text-sm text-center text-gray-500 border-t border-gray-100 pt-6">
                <span>Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" wire:navigate class="font-bold text-[#006633] hover:text-[#004d26] ml-1 hover:underline underline-offset-4 transition-all">Masuk di sini</a>
            </div>

            <div class="mt-4 text-center">
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