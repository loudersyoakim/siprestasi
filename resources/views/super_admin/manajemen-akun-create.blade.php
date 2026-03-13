@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('admin.manajemen-akun') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Tambah Akun Baru</h3>
    </div>

    <button onclick="openModal('modal-import')" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow- -200 hover:bg-blue-700 transition-all">
        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
        <span>Import Massal (.xlsx)</span>
    </button>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('admin.manajemen-akun.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('name') border-red-500 @enderror" placeholder="Masukkan nama lengkap...">
                @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM / NIP</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip') }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('nim_nip') border-red-500 @enderror" placeholder="Nomor identitas...">
                @error('nim_nip') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Hak Akses</label>
                <select name="role" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer">
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wd" {{ old('role') == 'wd' ? 'selected' : '' }}>Wakil Dekan</option>
                    <option value="kajur" {{ old('role') == 'kajur' ? 'selected' : '' }}>Kepala Jurusan</option>
                    <option value="gpm" {{ old('role') == 'gpm' ? 'selected' : '' }}>GPM / Dosen</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                    Email Akses <span class="normal-case font-medium text-gray-300">(Opsional)</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all"
                    placeholder="Boleh dikosongkan...">
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
                        {{-- HAPUS ATRIBUT REQUIRED DI SINI --}}
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

            <a href="{{ route('admin.manajemen-akun') }}" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-all text-center uppercase tracking-widest">
                Batal
            </a>
        </div>
    </form>
</div>

<div id="modal-import" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h4 class="font-black text-gray-800 uppercase tracking-tight">Import Akun via Spreadsheet</h4>
                <button onclick="closeModal('modal-import')" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>

            <form action="{{ route('admin.manajemen-akun.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="text-center mb-6">
                    <p class="text-xs text-gray-500 mb-4">Gunakan template di bawah ini agar format data sesuai dengan sistem.</p>
                    <a href="{{ route('admin.manajemen-akun.export-format') }}" class="inline-flex items-center gap-2 text-[#006633] font-bold text-xs uppercase tracking-wider hover:underline">
                        <i class="bi bi-cloud-arrow-down-fill text-lg"></i>
                        Download Template .xlsx
                    </a>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 text-left block">Pilih File Spreadsheet</label>
                    <input type="file" name="file" accept=".xlsx, .xls" required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-dashed border-gray-200 p-4 rounded-2xl">
                </div>

                <div class="pt-8 flex gap-3">
                    <button type="button" onclick="closeModal('modal-import')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-bl-100 transition-all">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');

    passwordInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            confirmInput.setAttribute('required', 'required');
            // Opsional: tambah tanda merah atau border jika ingin lebih jelas
            confirmInput.classList.add('border-orange-200');
        } else {
            confirmInput.removeAttribute('required');
            confirmInput.classList.remove('border-orange-200');
        }
    });

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

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection