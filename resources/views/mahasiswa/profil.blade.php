@extends('layouts.app')

@section('content')
{{-- CDN CROPPER.JS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

{{-- KUNCI UTAMA: Tambahkan atribut data-error-password di sini --}}
<div id="profil-page" data-error-password="{{ $errors->has('current_password') || $errors->has('new_password') ? 'true' : 'false' }}">

    <div class="mb-8">
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Profil Saya</h3>
        <p class="text-sm text-gray-500">Kelola informasi pribadi, foto, dan data akademik Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- SISI KIRI: RINGKASAN PROFIL & UPLOAD FOTO --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-[#006633]"></div>

                <div class="relative z-10 flex flex-col items-center">
                    {{-- BUNGKUS FOTO DENGAN PREVIEW (Masih Bisa Diklik) --}}
                    <div id="preview-container" class="relative group cursor-pointer inline-flex p-1 bg-white rounded-full shadow-lg overflow-hidden mb-3 border-4 border-white" onclick="document.getElementById('input-foto').click()">
                        @if($mahasiswa && $mahasiswa->foto_profil)
                            <img id="preview-foto" src="{{ asset('storage/' . $mahasiswa->foto_profil) }}" alt="Foto" class="w-28 h-28 rounded-full object-cover">
                        @else
                            <div id="preview-inisial" class="w-28 h-28 rounded-full bg-gray-100 flex items-center justify-center text-[#006633] text-5xl font-black">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <img id="preview-foto" src="" class="hidden w-28 h-28 rounded-full object-cover">
                        @endif
                        
                        {{-- OVERLAY KAMERA SAAT HOVER --}}
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-full">
                            <i class="bi bi-camera-fill text-white text-2xl"></i>
                        </div>
                    </div>
                    
                    {{-- TOMBOL CHOOSE FILE (Lebih Jelas) --}}
                    <button type="button" onclick="document.getElementById('input-foto').click()" class="px-4 py-2 mb-4 bg-gray-50 border border-gray-100 rounded-full text-[10px] text-gray-500 font-bold uppercase tracking-widest hover:bg-gray-100 hover:text-gray-700 transition-colors flex items-center gap-1.5">
                        <i class="bi bi-folder2-open text-xs"></i> Pilih File Foto
                    </button>

                    <h4 class="text-lg font-black text-gray-800 line-clamp-1">{{ Auth::user()->name }}</h4>
                    <p class="text-xs font-bold text-yellow-600 uppercase tracking-widest mt-1">
                        {{ Auth::user()->nim_nip ?? 'NIM Belum Diatur' }}
                    </p>

                    <div class="mt-6 pt-6 border-t border-gray-50 w-full">
                        <div class="flex justify-between items-center text-xs mb-2">
                            <span class="font-bold text-gray-400 uppercase tracking-wider">Status Kelengkapan</span>
                            <span class="font-black text-[#006633]">{{ $persentaseProfil ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-[#006633] h-2 rounded-full transition-all duration-1000" style="width: {{ $persentaseProfil ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD TIPS KEAMANAN --}}
            <div id="keamanan" class="bg-red-50 rounded-3xl p-6 border border-red-100">
                <div class="flex items-center gap-3 mb-3 text-red-600">
                    <i class="bi bi-shield-lock-fill text-xl"></i>
                    <h5 class="font-black text-sm uppercase tracking-tight">Keamanan Akun</h5>
                </div>
                <p class="text-xs text-red-700 leading-relaxed mb-4">
                    Pastikan password Anda unik untuk menjaga kerahasiaan data prestasi.
                </p>
                <button onclick="openModal('modalPassword')" class="w-full py-3 bg-white border border-red-200 text-red-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all shadow-sm">
                    Ganti Kata Sandi
                </button>
            </div>
        </div>

        {{-- SISI KANAN: FORM DATA DIRI --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h5 class="font-black text-gray-800 uppercase tracking-widest text-xs">Informasi Detail</h5>
                    @if(!$mahasiswa || $persentaseProfil < 100)
                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[10px] font-black uppercase tracking-tighter animate-pulse">Data Belum Lengkap</span>
                    @else
                        <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-tighter">Terverifikasi</span>
                    @endif
                </div>

                {{-- FORM UPDATE --}}
                {{-- KUNCI UTAMA: Tambahkan enctype="multipart/form-data" --}}
                <form action="{{ route('mahasiswa.profil.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 relative">
                    @csrf
                    
                    {{-- INPUT FOTO TERSEMBUNYI (max 10MB) --}}
                    <input type="file" id="input-foto" name="foto_profil_file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewAndCropImage(event)">
                    
                    {{-- INPUT HIDDEN UNTUK DATA FOTO HASIL CROP (BASE64) --}}
                    <input type="hidden" name="foto_base64" id="foto_base64">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Baris 1: Nama & Email --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold">
                        </div>

                        {{-- Baris 2: NIM & Jenis Kelamin --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIM</label>
                            <input type="text" value="{{ Auth::user()->nim_nip ?? 'Belum Diatur' }}" disabled class="w-full mt-1 px-4 py-3 bg-gray-100 border border-gray-100 text-gray-400 rounded-2xl text-sm font-semibold opacity-70">
                            <span class="text-[9px] text-gray-400 ml-1">NIM hanya bisa diubah oleh Admin.</span>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all cursor-pointer">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ (old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ (old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Baris 3: Angkatan & Fakultas --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Angkatan</label>
                            <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan ?? '') }}" placeholder="Contoh: 2022" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fakultas</label>
                            <select name="fakultas_id" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold cursor-pointer">
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultas as $f)
                                <option value="{{ $f->id }}" {{ (old('fakultas_id', $mahasiswa->fakultas_id ?? '') == $f->id) ? 'selected' : '' }}>{{ $f->nama_fakultas }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Baris 4: Jurusan & Prodi --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jurusan</label>
                            <select name="jurusan_id" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold cursor-pointer">
                                <option value="">Pilih Jurusan</option>
                                @foreach($jurusans as $j)
                                <option value="{{ $j->id }}" {{ (old('jurusan_id', $mahasiswa->jurusan_id ?? '') == $j->id) ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Program Studi</label>
                            <select name="prodi_id" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#006633] outline-none transition-all font-semibold cursor-pointer">
                                <option value="">Pilih Prodi</option>
                                @foreach($prodis as $p)
                                <option value="{{ $p->id }}" {{ (old('prodi_id', $mahasiswa->prodi_id ?? '') == $p->id) ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 flex justify-end">
                        <button type="submit" class="px-10 py-4 bg-[#006633] text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-[#004d26] shadow-lg transition-all flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-lg"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL GANTI PASSWORD --}}
    <div id="modalPassword" class="fixed inset-0 z-[1001] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" onclick="closeModal('modalPassword')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-3xl text-left shadow-2xl transform transition-all w-full max-w-md p-8 z-[1002]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Ganti Kata Sandi</h3>
                    <button onclick="closeModal('modalPassword')" class="text-gray-400 hover:text-red-500 transition-colors"><i class="bi bi-x-lg text-xl"></i></button>
                </div>

                <form action="{{ route('mahasiswa.profil.update-password') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Saat Ini</label>
                        <div class="relative mt-1">
                            <input type="password" name="current_password" id="current_password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-red-500 outline-none transition-all">
                            <button type="button" onclick="togglePassword('current_password', 'eye_curr')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye_curr" class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Baru</label>
                        <div class="relative mt-1">
                            <input type="password" name="new_password" id="new_password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-red-500 outline-none transition-all">
                            <button type="button" onclick="togglePassword('new_password', 'eye_new')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye_new" class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                        <div class="relative mt-1">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-red-500 outline-none transition-all">
                            <button type="button" onclick="togglePassword('new_password_confirmation', 'eye_conf')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye_conf" class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal('modalPassword')" class="flex-1 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-100">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- MODAL CROP GAMBAR (BARU) --}}
    <div id="modal-crop" class="fixed inset-0 z-[1001] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" onclick="closeCropModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-3xl text-left shadow-2xl transform transition-all w-full max-w-xl p-8 z-[1002]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight"></h3>
                    <button onclick="closeCropModal()" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg text-xl"></i></button>
                </div>
                
                <div class="max-w-full overflow-hidden flex justify-center items-center bg-gray-100 rounded-xl">
                    {{-- Canvas Cropper --}}
                    <img id="image-to-crop" src="" class="block max-w-full h-[400px]">
                </div>
                
                <p class="text-[11px] text-gray-400 mt-4 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <i class="bi bi-info-circle-fill text-[#006633] mr-1"></i>
                    Gunakan kursor atau jari Anda untuk menggeser dan mengatur area lingkaran foto.
                </p>
                
                <div class="pt-6 mt-6 border-t border-gray-50 flex gap-3">
                    <button type="button" onclick="closeCropModal()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                    <button type="button" id="btn-save-crop" class="flex-1 py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-[#004d26] transition-all shadow-lg shadow-green-100 flex items-center justify-center gap-2">
                       Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- CSS Cropper Lingkaran --}}
<style>
    .cropper-view-box, .cropper-face { border-radius: 50%; outline: none !important; }
    .cropper-view-box { border: 2px solid #fff; box-shadow: 0 0 0 1px #fff; }
</style>

<script src="{{ asset('js/sweetalert2.js') }}"></script>
<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }

    // ==========================================
    // FUNGSI INTIP PASSWORD (MATA)
    // ==========================================
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        }
    }

    // ==========================================
    // AUTO OPEN MODAL SAAT ERROR PASSWORD
    // ==========================================
    document.addEventListener("DOMContentLoaded", function() {
        const profilPage = document.getElementById('profil-page');
        const adaError = profilPage.getAttribute('data-error-password') === 'true';
        if (adaError) {
            openModal('modalPassword');
        }
    });

    @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-[2rem]' } }); @endif
    @if($errors->any()) Swal.fire({ icon: 'error', title: 'Gagal Simpan!', text: "{{ $errors->first() }}", customClass: { popup: 'rounded-[2rem]' } }); @endif

    // LOGIKA CROP GAMBAR
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
                Swal.fire({ icon: 'error', title: 'Format Salah!', text: 'File harus gambar (JPG/PNG).', customClass: { popup: 'rounded-[2rem]' } });
                inputFoto.value = ''; return;
            }

            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({ icon: 'error', title: 'Terlalu Besar!', text: 'Maks 10MB.', customClass: { popup: 'rounded-[2rem]' } });
                inputFoto.value = ''; return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                openModal('modal-crop');
                
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

        // Crop di browser ke ukuran ideal 800x800
        const canvas = cropper.getCroppedCanvas({
            width: 800, height: 800, imageSmoothingEnabled: true, imageSmoothingQuality: 'high',
        });

        const base64data = canvas.toDataURL('image/jpeg', 0.80);
        
        document.getElementById('preview-foto').src = base64data;
        document.getElementById('preview-foto').classList.remove('hidden');
        if (document.getElementById('preview-inisial')) {
            document.getElementById('preview-inisial').classList.add('hidden');
        }

        inputBase64.value = base64data;
        inputFoto.value = ''; // Kosongkan file asli agar tidak ikut terkirim

        closeCropModal();
    });

    function closeCropModal() {
        closeModal('modal-crop');
        inputFoto.value = '';
        if (cropper) { cropper.destroy(); cropper = null; }
    }
</script>
@endsection