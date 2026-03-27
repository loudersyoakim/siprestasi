@extends('layouts.app')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
    <div>
        <a href="{{ route('super_admin.manajemen-akun') }}" 
           class="text-[10px] font-bold text-gray-400 hover:text-[#006633] flex items-center gap-1.5 mb-1 transition-all">
            <i class="bi bi-arrow-left"></i> KEMBALI
        </a>
        <h3 class="text-lg font-black text-gray-800 tracking-tight">Tambah Akun</h3>
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
<div id="modal-import" class="fixed inset-0 z-[80] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <i class="bi bi-file-earmark-excel-fill text-green-600 text-lg"></i>
                    <h4 class="font-black text-gray-800 uppercase tracking-tight text-sm">Import Data Akun</h4>
                </div>
                <button onclick="closeModal('modal-import')" class="text-gray-400 hover:text-red-500 transition-colors"><i class="bi bi-x-lg"></i></button>
            </div>

            <form action="{{ route('akun.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center mb-8">
                    <p class="text-xs text-blue-600 font-medium mb-4 leading-relaxed">Gunakan template resmi agar data terbaca sempurna.</p>
                    <a href="{{ route('akun.export-format') }}" class="inline-flex items-center gap-2 bg-white text-[#006633] border border-[#006633] px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-[#006633] hover:text-white transition-all shadow-sm">
                        <i class="bi bi-cloud-arrow-down-fill text-sm"></i>
                        Download Template Excel
                    </a>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih File Spreadsheet</label>
                    
                    {{-- AREA DROPZONE DENGAN FEEDBACK VISUAL --}}
                    <div class="relative border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center hover:border-[#006633] transition-all group bg-gray-50/50" id="drop-area">
                        <input type="file" name="file" id="file-input" accept=".xlsx, .xls" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        {{-- Tampilan Sebelum Pilih --}}
                        <div id="before-select">
                            <i class="bi bi-cloud-upload text-4xl text-gray-300 group-hover:text-[#006633] transition-all"></i>
                            <p class="text-[10px] text-gray-400 mt-3 font-bold uppercase tracking-wide group-hover:text-gray-600">Klik atau Tarik file ke sini</p>
                        </div>

                        {{-- Tampilan Sesudah Pilih (Default Tersembunyi) --}}
                        <div id="after-select" class="hidden flex-col items-center">
                            <div class="w-16 h-16 bg-green-100 text-[#006633] rounded-2xl flex items-center justify-center mb-3 animate-bounce">
                                <i class="bi bi-file-earmark-check-fill text-3xl"></i>
                            </div>
                            <p id="file-name-display" class="text-sm font-black text-gray-800 break-all"></p>
                            <p class="text-[9px] text-gray-400 mt-1 uppercase font-bold tracking-widest">File siap diimpor</p>
                            <button type="button" onclick="resetFile()" class="mt-4 text-[10px] font-black text-red-500 uppercase hover:underline">Ganti File</button>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex gap-3">
                    <button type="button" onclick="closeModal('modal-import')" class="flex-1 py-3.5 text-xs font-bold text-gray-500 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3.5 text-xs font-bold text-white bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const fileInput = document.getElementById('file-input');
    const beforeSelect = document.getElementById('before-select');
    const afterSelect = document.getElementById('after-select');
    const fileNameDisplay = document.getElementById('file-name-display');

    // Deteksi saat file dipilih
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            beforeSelect.classList.add('hidden');
            afterSelect.classList.remove('hidden');
            afterSelect.classList.add('flex');
            fileNameDisplay.textContent = this.files[0].name;
        }
    });

    // Reset file selection
    function resetFile() {
        fileInput.value = '';
        beforeSelect.classList.remove('hidden');
        afterSelect.classList.add('hidden');
        afterSelect.classList.remove('flex');
    }

    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden'; 
    }
    
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        document.body.style.overflow = 'auto'; 
        resetFile(); 
    }
</script>
@endsection