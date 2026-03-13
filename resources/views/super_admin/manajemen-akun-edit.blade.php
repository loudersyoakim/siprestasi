@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('admin.manajemen-akun') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Edit Akun: {{ $user->name }}</h3>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('admin.manajemen-akun.update', $user->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('name') border-red-500 @enderror" placeholder="Masukkan nama lengkap...">
                @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM / NIP</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip', $user->nim_nip) }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('nim_nip') border-red-500 @enderror" placeholder="Nomor identitas...">
                @error('nim_nip') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Hak Akses</label>
                <select name="role" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer">
                    <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wd" {{ old('role', $user->role) == 'wd' ? 'selected' : '' }}>Wakil Dekan</option>
                    <option value="kajur" {{ old('role', $user->role) == 'kajur' ? 'selected' : '' }}>Kepala Jurusan</option>
                    <option value="gpm" {{ old('role', $user->role) == 'gpm' ? 'selected' : '' }}>GPM / Dosen</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                    Email Akses</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('email') border-red-500 @enderror"
                    placeholder="Boleh dikosongkan...">
                @error('email') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <h4 class="text-[11px] font-black text-yellow-700 uppercase tracking-widest flex items-center gap-2">
                            <i class="bi bi-shield-lock-fill"></i> Ganti Password
                        </h4>
                        <p class="text-[10px] text-yellow-600 mt-1 font-medium italic">*Kosongkan jika tidak ingin mengganti password lama.</p>
                    </div>

                    <div class="relative">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all"
                                placeholder="Masukkan password baru...">
                            <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye-icon" class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all"
                                placeholder="Ulangi password baru...">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-confirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye-icon-confirm" class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 flex flex-wrap gap-4">
            <button type="submit" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-white bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                <!-- <i class="bi bi-check-circle-fill"></i> -->
                <span>Simpan</span>
            </button>

            <a href="{{ route('admin.manajemen-akun') }}" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-all text-center uppercase tracking-widest">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    }
</script>
@endsection