@extends('layouts.app')

@section('content')
@php
    $routePrefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
@endphp

<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route($routePrefix . '.manajemen-akun') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Tambah Akun Baru</h3>
    </div>

    <button onclick="openModal('modal-import')" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-200 hover:bg-blue-700 transition-all">
        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
        <span>Import Massal (.xlsx)</span>
    </button>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route($routePrefix . '.manajemen-akun.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('name') border-red-500 @enderror" placeholder="Masukkan nama lengkap...">
                @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM / NIP / USERNAME</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('nim_nip') border-red-500 @enderror" placeholder="Nomor identitas...">
                @error('nim_nip') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Hak Akses</label>
                <select name="role" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer">
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wakil_dekan" {{ old('role') == 'wakil_dekan' ? 'selected' : '' }}>Wakil Dekan</option>
                    <option value="jurusan" {{ old('role') == 'jurusan' ? 'selected' : '' }}>Kepala Jurusan</option>
                    <option value="gpm" {{ old('role') == 'gpm' ? 'selected' : '' }}>GPM / Dosen</option>
                </select>
            </div>

            {{-- MENJADIKAN EMAIL DAN STATUS AKTIVASI BERDAMPINGAN --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                    Email Akses <span class="normal-case font-medium text-gray-300">(Opsional)</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all"
                    placeholder="Email aktif...">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Status Aktivasi</label>
                <select name="is_active" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer bg-green-50 text-green-800 border-green-200 font-bold">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif (Langsung Bisa Login)</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Pending (Menunggu Aktivasi)</option>
                </select>
            </div>

            <div class="relative">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                    Password <span class="normal-case font-medium text-[#006633] italic">(Default: NIM/NIP)</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all"
                        placeholder="Kosongkan untuk gunakan NIM/NIP...">
                    <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i id="eye-icon" class="bi bi-eye"></i>
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 ml-1 font-medium italic">*Jika dikosongkan, password otomatis disamakan dengan NIM/NIP.</p>
            </div>

            <div class="relative">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all"
                        placeholder="Ulangi password...">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-confirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i id="eye-icon-confirm" class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 flex flex-wrap gap-4">
            <button type="submit" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-white bg-[#006633] rounded-2xl hover:bg-[#004d26] shadow-lg shadow-green-100 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                <span>Simpan</span>
            </button>

            <a href="{{ route($routePrefix . '.manajemen-akun') }}" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-all text-center uppercase tracking-widest">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection