@extends('layouts.app')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
    <div>
        <a href="{{ route('super_admin.manajemen-akun') }}" 
           class="text-[10px] font-bold text-gray-400 hover:text-[#006633] flex items-center gap-1.5 mb-1 transition-all">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Akun</h3>
    </div>

    {{-- Tombol Pemicu Modal Import --}}
    <button onclick="openModal('modal-import')" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-md hover:bg-blue-700 transition-all">
        <i class="bi bi-file-earmark-spreadsheet-fill text-xs"></i>
        <span>IMPORT MASSAL (.XLSX)</span>
    </button>
</div>

{{-- FORM TAMBAH MANUAL --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-12">
    <form action="{{ route('akun.store') }}" method="POST" class="p-5 sm:p-8 space-y-5">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="w-full mt-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all" placeholder="Nama lengkap pengguna...">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM / NIP</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" required 
                       class="w-full mt-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all" placeholder="Nomor identitas unik...">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Peran Sistem</label>
                <select name="role_id" required class="w-full mt-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none cursor-pointer">
                    @foreach($roles as $role)
                        @if(Auth::user()->role->kode_role !== 'SA' && $role->kode_role === 'SA') @continue @endif
                        <option value="{{ $role->id }}" {{ (old('role_id') == $role->id || (!old('role_id') && $role->kode_role === 'MHS')) ? 'selected' : '' }}>
                            {{ $role->nama_role }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full mt-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all" placeholder="alamat@email.com (opsional)...">
            </div>
            
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                        Password <span class="text-[#006633] lowercase italic font-medium">(Default: NIM)</span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full mt-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all" placeholder="Kosongkan untuk default...">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full mt-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:border-[#006633] focus:bg-white outline-none transition-all" placeholder="Ulangi password...">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 flex gap-3">
            <button type="submit" class="px-10 py-3 bg-[#006633] text-white rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-[#004d26] transition-all shadow-md">Simpan</button>
            <a href="{{ route('super_admin.manajemen-akun') }}" 
               class="px-10 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-gray-200 transition-all text-center">Batal</a>
        </div>
    </form>
</div>
{{-- MODAL IMPORT --}}
<div id="modal-import" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            {{-- Header Modal --}}
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                        <i class="bi bi-file-earmark-excel-fill text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-gray-800 uppercase tracking-tight">Import Data Pengguna</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Excel / Spreadsheet (.xlsx)</p>
                    </div>
                </div>
                <button onclick="closeModal('modal-import')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition-all">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form action="{{ route('akun.import') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                {{-- Instruksi & Template --}}
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-xs text-blue-700 font-bold mb-1">PENTING!</p>
                        <p class="text-[11px] text-blue-600 leading-relaxed">Gunakan template resmi untuk menghindari kesalahan pembacaan kolom oleh sistem.</p>
                    </div>
                    <a href="{{ route('akun.export-format') }}" class="bg-white text-[#006633] border border-[#006633] px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#006633] hover:text-white transition-all shadow-sm flex items-center gap-2">
                        <i class="bi bi-cloud-arrow-down-fill text-sm"></i>
                        Download Template
                    </a>
                </div>

                {{-- Area Upload --}}
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih File</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-[1.5rem] p-12 text-center hover:border-[#006633] transition-all group bg-gray-50/50" id="drop-area">
                        <input type="file" name="file" id="file-input" accept=".xlsx, .xls" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <div id="ui-before" class="space-y-2">
                            <i class="bi bi-cloud-upload text-5xl text-gray-300 group-hover:text-[#006633] transition-all"></i>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wide group-hover:text-gray-600">Klik atau Tarik file ke sini</p>
                        </div>

                        <div id="ui-after" class="hidden flex-col items-center">
                            <div class="w-20 h-20 bg-green-50 text-[#006633] rounded-2xl flex items-center justify-center mb-4 shadow-sm">
                                <i class="bi bi-file-earmark-check-fill text-4xl"></i>
                            </div>
                            <p id="name-file" class="text-sm font-black text-gray-800 mb-1"></p>
                            <span class="px-3 py-1 bg-green-500 text-white text-[9px] font-black uppercase rounded-full">File Siap</span>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="pt-6 border-t border-gray-100 flex gap-3">
                    <button type="button" onclick="closeModal('modal-import')" class="flex-1 py-4 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-all">Batal</button>
                    <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[11px] shadow-lg shadow-blue-100 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">Mulai Import Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const input = document.getElementById('file-input');
    const before = document.getElementById('ui-before');
    const after = document.getElementById('ui-after');
    const nameDisp = document.getElementById('name-file');

    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            before.classList.add('hidden');
            after.classList.remove('hidden');
            after.classList.add('flex');
            nameDisp.textContent = this.files[0].name;
        }
    });

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }
</script>
@endsection