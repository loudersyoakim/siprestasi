@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route(Auth::user()->role->kode_role == 'SA' ? 'super_admin.manajemen-akun' : 'admin.manajemen-akun') }}" 
       class="text-[10px] font-bold text-gray-400 hover:text-[#006633] flex items-center gap-1.5 mb-1 transition-all">
            <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h3 class="text-2xl font-black text-gray-800 tracking-tight">Edit Akun</h3>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('akun.update', $user->id) }}" method="POST" class="p-5 sm:p-6 space-y-5">
        @csrf @method('PUT')

        {{-- Info Dasar --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
            <div class="md:col-span-2">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                       class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all">
            </div>

            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM / NIP</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip', $user->nim_nip) }}" required 
                       class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all">
            </div>

            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Peran</label>
                <select name="role_id" required class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none cursor-pointer">
                    @foreach($roles as $role)
                        @if(Auth::user()->role->kode_role !== 'SA' && $role->kode_role === 'SA') @continue @endif
                        <option value="{{ $role->id }}" 
                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->nama_role }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all">
            </div>

            {{-- Ganti Password Slim --}}
            <div class="md:col-span-2">
                <div class="p-4 bg-yellow-50/50 border border-yellow-100 rounded-xl space-y-3">
                    <label class="text-[9px] font-black text-yellow-700 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="bi bi-key-fill"></i> Ganti Password (Opsional)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="password" name="password" class="w-full px-3 py-2 border border-yellow-200 rounded-xl text-xs outline-none" placeholder="Password baru...">
                        <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-yellow-200 rounded-xl text-xs outline-none" placeholder="Konfirmasi...">
                    </div>
                </div>
            </div>

            {{-- Status Checkbox Slim --}}
            <div class="md:col-span-2 flex items-center gap-2 px-1">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $user->is_active ? 'checked' : '' }}>
                    <div class="w-8 h-4.5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-[#006633] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3.5 after:w-3.5 after:transition-all"></div>
                </label>
                <span class="text-[10px] font-bold text-gray-600 uppercase tracking-tight">Akun Aktif</span>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-50 flex gap-2">
            <button type="submit" class="flex-1 py-2.5 bg-[#006633] text-white rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-[#004d26] transition-all shadow-sm">Simpan</button>
            <a href="{{ route(Auth::user()->role->kode_role == 'SA' ? 'super_admin.manajemen-akun' : 'admin.manajemen-akun') }}" 
               class="flex-1 py-2.5 bg-gray-100 text-gray-500 rounded-xl font-bold uppercase tracking-widest text-[10px] hover:bg-gray-200 transition-all text-center">Batal</a>
        </div>
    </form>
</div>
@endsection