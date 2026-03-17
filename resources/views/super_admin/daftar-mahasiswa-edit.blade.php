@extends('layouts.app')

@section('content')
{{-- CDN CROPPER.JS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<div class="mb-6">
    <a href="{{ route($prefix . '.daftar-mahasiswa') }}" class="text-sm font-bold text-gray-500 hover:text-[#006633] flex items-center gap-2 w-max mb-4 transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Mahasiswa
    </a>
    <h3 class="text-xl font-black text-gray-800 tracking-tight">Edit Data Mahasiswa</h3>
    <p class="text-sm text-gray-500 mt-1">Perbarui informasi akun, foto profil, dan data akademik.</p>
</div>

<div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-10">
    <form action="{{ route($prefix . '.daftar-mahasiswa.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
        @csrf
        @method('PUT')

        {{-- INPUT TERSEMBUNYI UNTUK FOTO --}}
        <input type="file" id="input-foto" name="foto_profil_file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewAndCropImage(event)">
        <input type="hidden" name="foto_base64" id="foto_base64">
        <input type="hidden" name="hapus_foto" id="hapus_foto" value="0">

        {{-- BAGIAN FOTO PROFIL --}}
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 pb-8 border-b border-gray-100">
            <div class="relative p-1 bg-white rounded-full shadow-sm border border-gray-200 shrink-0">
                @if($user->mahasiswa && $user->mahasiswa->foto_profil)
                    <img id="preview-foto" src="{{ asset('storage/' . $user->mahasiswa->foto_profil) }}" class="w-24 h-24 rounded-full object-cover">
                    <div id="preview-inisial" class="hidden w-24 h-24 rounded-full bg-green-50 items-center justify-center text-[#006633] text-4xl font-black">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @else
                    <img id="preview-foto" src="" class="hidden w-24 h-24 rounded-full object-cover">
                    <div id="preview-inisial" class="w-24 h-24 rounded-full bg-green-50 flex items-center justify-center text-[#006633] text-4xl font-black">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
            </div>
            
            <div class="flex flex-col gap-2 text-center sm:text-left mt-2">
                <h5 class="text-sm font-bold text-gray-800">Foto Profil Mahasiswa</h5>
                <div class="flex items-center justify-center sm:justify-start gap-2 mt-1">
                    <button type="button" onclick="document.getElementById('input-foto').click()" class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="bi bi-camera mr-1"></i> Ganti Foto
                    </button>
                    <button type="button" onclick="hapusFoto()" class="px-4 py-2 bg-red-50 border border-red-100 rounded-lg text-xs font-bold text-red-600 hover:bg-red-500 hover:text-white transition-colors">
                        <i class="bi bi-trash3 mr-1"></i> Hapus
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Gunakan rasio 1:1. Maksimal ukuran file 10MB.</p>
            </div>
        </div>

        {{-- BAGIAN INPUT DATA (SEBARIS & MERATA) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold">
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip', $user->nim_nip) }}" required class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-bold text-gray-700">
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Angkatan</label>
                <input type="number" name="angkatan" value="{{ old('angkatan', $user->mahasiswa->angkatan ?? '') }}" placeholder="Contoh: 2022" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold cursor-pointer">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->mahasiswa->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $user->mahasiswa->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fakultas</label>
                <select name="fakultas_id" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold cursor-pointer">
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach($fakultas as $f)
                    <option value="{{ $f->id }}" {{ old('fakultas_id', $user->mahasiswa->fakultas_id ?? '') == $f->id ? 'selected' : '' }}>{{ $f->nama_fakultas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jurusan</label>
                <select name="jurusan_id" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold cursor-pointer">
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($jurusans as $j)
                    <option value="{{ $j->id }}" {{ old('jurusan_id', $user->mahasiswa->jurusan_id ?? '') == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Program Studi</label>
                <select name="prodi_id" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold cursor-pointer">
                    <option value="">-- Pilih Prodi --</option>
                    @foreach($prodis as $p)
                    <option value="{{ $p->id }}" {{ old('prodi_id', $user->mahasiswa->prodi_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }} ({{ $p->jenjang }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="pt-8 mt-8 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route($prefix . '.daftar-mahasiswa') }}" class="px-6 py-3 bg-gray-100 text-gray-500 rounded-xl text-sm font-bold hover:bg-gray-200 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-3 bg-[#006633] text-white rounded-xl text-sm font-bold hover:bg-[#004d26] transition-colors shadow-sm flex items-center gap-2">
               Simpan
            </button>
        </div>
    </form>
</div>

{{-- MODAL CROP GAMBAR --}}
<div id="modal-crop" class="fixed inset-0 z-[1001] hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" onclick="closeCropModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-3xl text-left shadow-2xl transform transition-all w-full max-w-xl p-8 z-[1002]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Sesuaikan Foto</h3>
                <button onclick="closeCropModal()" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg text-xl"></i></button>
            </div>
            
            <div class="max-w-full overflow-hidden flex justify-center items-center bg-gray-100 rounded-xl">
                <img id="image-to-crop" src="" class="block max-w-full h-[400px]">
            </div>
            
            <div class="pt-6 mt-6 border-t border-gray-50 flex gap-3">
                <button type="button" onclick="closeCropModal()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                <button type="button" id="btn-save-crop" class="flex-1 py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-[#004d26] transition-all shadow-lg flex items-center justify-center gap-2">
                    Potong & Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .cropper-view-box, .cropper-face { border-radius: 50%; outline: none !important; }
    .cropper-view-box { border: 2px solid #fff; box-shadow: 0 0 0 1px #fff; }
</style>

<script src="{{ asset('js/sweetalert2.js') }}"></script>
<script>
    // Fitur Hapus Foto
    function hapusFoto() {
        document.getElementById('hapus_foto').value = '1';
        document.getElementById('foto_base64').value = '';
        document.getElementById('input-foto').value = '';
        
        document.getElementById('preview-foto').classList.add('hidden');
        document.getElementById('preview-foto').src = '';
        
        const inisial = document.getElementById('preview-inisial');
        if(inisial) {
            inisial.classList.remove('hidden');
            inisial.classList.add('flex');
        }
    }

    // Logika Crop Gambar
    let cropper = null;
    const imageToCrop = document.getElementById('image-to-crop');
    const inputFoto = document.getElementById('input-foto');
    const btnSaveCrop = document.getElementById('btn-save-crop');
    const inputBase64 = document.getElementById('foto_base64');
    
    function previewAndCropImage(event) {
        const files = event.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            
            if (!file.type.match('image.*')) {
                Swal.fire({ icon: 'error', title: 'Format Salah!', text: 'File harus gambar (JPG/PNG).' });
                inputFoto.value = ''; return;
            }

            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({ icon: 'error', title: 'Terlalu Besar!', text: 'Maks 10MB.' });
                inputFoto.value = ''; return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                document.getElementById('modal-crop').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                if (cropper) { cropper.destroy(); }
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 1, restore: false, guides: false, highlight: false, cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false,
                });
            };
            reader.readAsDataURL(file);
        }
    }

    btnSaveCrop.addEventListener('click', function() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 800, height: 800, imageSmoothingEnabled: true, imageSmoothingQuality: 'high',
        });

        const base64data = canvas.toDataURL('image/jpeg', 0.80);
        
        document.getElementById('preview-foto').src = base64data;
        document.getElementById('preview-foto').classList.remove('hidden');
        
        const inisial = document.getElementById('preview-inisial');
        if (inisial) {
            inisial.classList.add('hidden');
            inisial.classList.remove('flex');
        }

        inputBase64.value = base64data;
        document.getElementById('hapus_foto').value = '0'; // Batalkan perintah hapus jika ada
        inputFoto.value = ''; 

        closeCropModal();
    });

    function closeCropModal() {
        document.getElementById('modal-crop').classList.add('hidden');
        document.body.style.overflow = 'auto';
        inputFoto.value = '';
        if (cropper) { cropper.destroy(); cropper = null; }
    }
</script>
@endsection