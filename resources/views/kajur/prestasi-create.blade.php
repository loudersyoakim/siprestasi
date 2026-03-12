@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('kajur.prestasi') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Tambah Prestasi Baru</h3>
    </div>

    <!-- <button onclick="openModal('modal-import')" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-200 hover:bg-blue-700 transition-all">
        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
        <span>Import Massal (.xlsx)</span>
    </button> -->
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('kajur.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 1. Jenis Kepesertaan --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kepesertaan</label>
                <div class="flex gap-4 mt-2">
                    <label class="flex-1">
                        <input type="radio" name="jenis_kepesertaan" value="individu" class="hidden peer" checked>
                        <div class="flex items-center justify-center p-3 border border-gray-100 rounded-2xl cursor-pointer peer-checked:border-[#006633] peer-checked:bg-green-50 peer-checked:text-[#006633] hover:bg-gray-50 transition-all">
                            <i class="bi bi-person-fill mr-2"></i>
                            <span class="text-sm font-bold">Individu</span>
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="jenis_kepesertaan" value="tim" class="hidden peer">
                        <div class="flex items-center justify-center p-3 border border-gray-100 rounded-2xl cursor-pointer peer-checked:border-[#006633] peer-checked:bg-green-50 peer-checked:text-[#006633] hover:bg-gray-50 transition-all">
                            <i class="bi bi-people-fill mr-2"></i>
                            <span class="text-sm font-bold">Tim / Kelompok</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 2. Input Pencarian Mahasiswa (Multiselect Logic) --}}
            <div class="md:col-span-2 relative" id="student-search-container">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cari & Tambah Mahasiswa</label>

                <div class="relative mt-1">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text"
                        id="student-search-input"
                        placeholder="Ketik Nama atau NIM..."
                        autocomplete="off"
                        class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all">
                </div>

                {{-- Dropdown Hasil Pencarian --}}
                <div id="student-dropdown" class="absolute z-[70] w-full mt-2 bg-white rounded-2xl border border-gray-100 shadow-2xl overflow-hidden hidden">
                    <div class="max-h-60 overflow-y-auto custom-scrollbar" id="student-list">
                        @foreach($mahasiswa as $mhs)
                        <div class="student-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0 transition-colors"
                            data-id="{{ $mhs->id }}"
                            data-name="{{ $mhs->name }}"
                            data-nim="{{ $mhs->nim_nip }}">
                            <div class="font-bold text-gray-800 text-sm">{{ $mhs->name }}</div>
                            <div class="text-[10px] text-[#006633] font-black uppercase tracking-widest mt-0.5">NIM: {{ $mhs->nim_nip }}</div>
                        </div>
                        @endforeach
                        <div id="no-student-found" class="px-4 py-8 text-center text-gray-400 hidden">
                            <i class="bi bi-person-x text-2xl mb-2 block opacity-50"></i>
                            <p class="text-xs font-medium">Mahasiswa tidak ditemukan.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Daftar Mahasiswa yang Dipilih (Chips/Badges) --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Mahasiswa Terpilih</label>
                <div id="selected-students-container" class="flex flex-wrap gap-3 mt-2 min-h-[50px] p-4 bg-gray-50/50 border border-dashed border-gray-200 rounded-2xl">
                    <div id="placeholder-text" class="text-gray-400 text-xs italic">Belum ada mahasiswa yang dipilih...</div>
                </div>
                {{-- Container untuk Input Hidden yang dikirim ke Controller --}}
                <div id="hidden-inputs-container"></div>
                @error('user_ids') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">Pilih minimal satu mahasiswa!</span> @enderror
            </div>

            {{-- 4. Nama Prestasi --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lomba / Kegiatan</label>
                <input type="text" name="nama_prestasi" value="{{ old('nama_prestasi') }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all @error('nama_prestasi') border-red-500 @enderror"
                    placeholder="Contoh: National University Debating Championship">
                @error('nama_prestasi') <span class="text-red-500 text-[10px] font-bold mt-1 ml-1 uppercase">{{ $message }}</span> @enderror
            </div>

            {{-- 5. Tingkat & Tahun Akademik --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tingkat</label>
                <select name="tingkat_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer">
                    <option value="" hidden>Pilih Tingkat...</option>
                    @foreach($tingkat as $t)
                    <option value="{{ $t->id }}" {{ old('tingkat_id') == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer">
                    @foreach($tahunAkademik as $ta)
                    <option value="{{ $ta->id }}" {{ old('tahun_akademik_id') == $ta->id ? 'selected' : '' }}>{{ $ta->tahun }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 6. Kategori & Jenis Prestasi --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Prestasi</label>
                <select name="kategori_id" id="kategori_select" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer">
                    <option value="" hidden>Pilih Kategori...</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                    <option value="lainnya" class="font-bold text-[#006633] bg-green-50">+ Tambah Lainnya...</option>
                </select>

                {{-- Input Muncul Jika Pilih "Lainnya" --}}
                <div id="kategori_lainnya_container" class="hidden mt-2 animate-in fade-in slide-in-from-top-2 duration-300">
                    <input type="text" name="kategori_baru" id="kategori_baru" placeholder="Ketik kategori baru..."
                        class="w-full px-4 py-3 border border-dashed border-[#006633] bg-green-50/30 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all">
                    <p class="text-[10px] text-gray-400 mt-1 ml-1 font-medium italic">*Kategori baru akan otomatis ditambahkan ke Master Data.</p>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kegiatan</label>
                <select name="jenis_id" id="jenis_select" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all cursor-pointer">
                    <option value="" hidden>Pilih Jenis...</option>
                    @foreach($jenis as $j)
                    <option value="{{ $j->id }}" {{ old('jenis_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                    @endforeach
                    <option value="lainnya" class="font-bold text-[#006633] bg-green-50">+ Tambah Lainnya...</option>
                </select>

                {{-- Input Muncul Jika Pilih "Lainnya" --}}
                <div id="jenis_lainnya_container" class="hidden mt-2 animate-in fade-in slide-in-from-top-2 duration-300">
                    <input type="text" name="jenis_baru" id="jenis_baru" placeholder="Ketik jenis kegiatan baru..."
                        class="w-full px-4 py-3 border border-dashed border-[#006633] bg-green-50/30 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all">
                    <p class="text-[10px] text-gray-400 mt-1 ml-1 font-medium italic">*Jenis baru akan otomatis ditambahkan ke Master Data.</p>
                </div>
            </div>

            {{-- 7. Tanggal Peroleh & Upload Sertifikat --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Sertifikat</label>
                <input type="date" name="tanggal_peroleh" value="{{ old('tanggal_peroleh') }}" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">File Sertifikat (PDF/JPG/PNG)</label>
                <input type="file" name="sertifikat" id="sertifikat_input" accept=".pdf,.jpg,.jpeg,.png" required
                    class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-2xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-green-50 file:text-[#006633] hover:file:bg-green-100 transition-all">
                <p class="text-[10px] text-gray-400 mt-1 ml-1 font-medium">*Maksimal 2MB.</p>
            </div>

            {{-- 8. Deskripsi --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Deskripsi Singkat <span class="normal-case font-medium text-gray-300">(Opsional)</span></label>
                <textarea name="deskripsi" rows="3"
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all"
                    placeholder="Ceritakan singkat tentang prestasi ini...">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 flex flex-wrap gap-4">
            <button type="submit" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-white bg-[#006633] rounded-2xl hover:bg-[#004d26] shadow-lg shadow-green-100 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                <!-- <i class="bi bi-cloud-check-fill text-lg"></i> -->
                <span>Simpan</span>
            </button>
            <a href="{{ route('kajur.prestasi') }}" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-all text-center uppercase tracking-widest">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('student-search-input');
        const dropdown = document.getElementById('student-dropdown');
        const listItems = document.querySelectorAll('.student-item');
        const selectedContainer = document.getElementById('selected-students-container');
        const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
        const placeholder = document.getElementById('placeholder-text');
        const radioButtons = document.getElementsByName('jenis_kepesertaan');

        let selectedUsers = []; // Array penyimpan user yang dipilih

        // Fungsi Render UI (Chip)
        function updateSelectedUI() {
            selectedContainer.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';

            if (selectedUsers.length === 0) {
                selectedContainer.appendChild(placeholder);
                return;
            }

            selectedUsers.forEach(user => {
                // 1. Buat Chip Visual
                const chip = document.createElement('div');
                chip.className = "flex items-center gap-2 bg-white border border-gray-200 px-3 py-2 rounded-xl shadow-sm";
                chip.innerHTML = `
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-800">${user.name}</span>
                        <span class="text-[9px] text-[#006633] font-black uppercase">NIM: ${user.nim}</span>
                    </div>
                    <button type="button" class="ml-2 text-gray-400 hover:text-red-500 transition-colors" onclick="removeUser('${user.id}')">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                `;
                selectedContainer.appendChild(chip);

                // 2. Buat Input Hidden (user_ids[])
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'user_ids[]'; // Format Array untuk pivot table
                hiddenInput.value = user.id;
                hiddenInputsContainer.appendChild(hiddenInput);
            });
        }

        // Fungsi Hapus User dari daftar (Global Context)
        window.removeUser = function(id) {
            selectedUsers = selectedUsers.filter(user => user.id !== id);
            updateSelectedUI();
        }

        // Tampilkan dropdown saat fokus
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.length > 0) dropdown.classList.remove('hidden');
        });

        // Filter Pencarian Dinamis
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            let hasMatch = false;

            if (term.length > 0) {
                dropdown.classList.remove('hidden');
                listItems.forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    const nim = item.getAttribute('data-nim').toLowerCase();

                    if (name.includes(term) || nim.includes(term)) {
                        item.classList.remove('hidden');
                        hasMatch = true;
                    } else {
                        item.classList.add('hidden');
                    }
                });
                document.getElementById('no-student-found').classList.toggle('hidden', hasMatch);
            } else {
                dropdown.classList.add('hidden');
            }
        });

        // Event Klik pada List Dropdown
        listItems.forEach(item => {
            item.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const nim = this.getAttribute('data-nim');
                const isTim = document.querySelector('input[name="jenis_kepesertaan"]:checked').value === 'tim';

                // Jika Individu, buang data lama
                if (!isTim) {
                    selectedUsers = [];
                }

                // Masukkan jika belum ada di list
                if (!selectedUsers.find(u => u.id === id)) {
                    selectedUsers.push({
                        id,
                        name,
                        nim
                    });
                    updateSelectedUI();
                }

                searchInput.value = ''; // Kosongkan input setelah pilih
                dropdown.classList.add('hidden'); // Tutup dropdown
            });
        });

        // Reset pilihan jika user ganti radio button (Individu <-> Tim)
        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                selectedUsers = [];
                updateSelectedUI();
            });
        });

        // Klik di luar untuk menutup dropdown
        document.addEventListener('click', function(e) {
            if (!document.getElementById('student-search-container').contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // 1. Kategori Logic
        const kategoriSelect = document.getElementById('kategori_select');
        const kategoriLainnyaContainer = document.getElementById('kategori_lainnya_container');
        const kategoriBaruInput = document.getElementById('kategori_baru');

        kategoriSelect.addEventListener('change', function() {
            if (this.value === 'lainnya') {
                kategoriLainnyaContainer.classList.remove('hidden');
                kategoriBaruInput.setAttribute('required', 'required');
                kategoriBaruInput.focus();
            } else {
                kategoriLainnyaContainer.classList.add('hidden');
                kategoriBaruInput.removeAttribute('required');
                kategoriBaruInput.value = ''; // Reset isi
            }
        });

        // 2. Jenis Logic
        const jenisSelect = document.getElementById('jenis_select');
        const jenisLainnyaContainer = document.getElementById('jenis_lainnya_container');
        const jenisBaruInput = document.getElementById('jenis_baru');

        jenisSelect.addEventListener('change', function() {
            if (this.value === 'lainnya') {
                jenisLainnyaContainer.classList.remove('hidden');
                jenisBaruInput.setAttribute('required', 'required');
                jenisBaruInput.focus();
            } else {
                jenisLainnyaContainer.classList.add('hidden');
                jenisBaruInput.removeAttribute('required');
                jenisBaruInput.value = ''; // Reset isi
            }
        });
    });
</script>
@endsection