@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('kajur.prestasi') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Edit Data Prestasi</h3>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('kajur.prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 1. Jenis Kepesertaan --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kepesertaan</label>
                <div class="flex gap-4 mt-2">
                    <label class="flex-1">
                        <input type="radio" name="jenis_kepesertaan" value="individu" class="hidden peer" {{ $prestasi->mahasiswa->count() <= 1 ? 'checked' : '' }}>
                        <div class="flex items-center justify-center p-3 border border-gray-100 rounded-2xl cursor-pointer peer-checked:border-yellow-400 peer-checked:bg-yellow-50 peer-checked:text-yellow-600 hover:bg-gray-50 transition-all">
                            <i class="bi bi-person-fill mr-2"></i>
                            <span class="text-sm font-bold">Individu</span>
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="jenis_kepesertaan" value="tim" class="hidden peer" {{ $prestasi->mahasiswa->count() > 1 ? 'checked' : '' }}>
                        <div class="flex items-center justify-center p-3 border border-gray-100 rounded-2xl cursor-pointer peer-checked:border-yellow-400 peer-checked:bg-yellow-50 peer-checked:text-yellow-600 hover:bg-gray-50 transition-all">
                            <i class="bi bi-people-fill mr-2"></i>
                            <span class="text-sm font-bold">Tim / Kelompok</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 2. Input Pencarian Mahasiswa --}}
            <div class="md:col-span-2 relative" id="student-search-container">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cari & Tambah Mahasiswa</label>
                <div class="relative mt-1">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="student-search-input" placeholder="Ketik Nama atau NIM..." autocomplete="off" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
                </div>

                <div id="student-dropdown" class="absolute z-[70] w-full mt-2 bg-white rounded-2xl border border-gray-100 shadow-2xl overflow-hidden hidden">
                    <div class="max-h-60 overflow-y-auto custom-scrollbar" id="student-list">
                        @foreach($mahasiswa as $mhs)
                        <div class="student-item px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0 transition-colors" data-id="{{ $mhs->id }}" data-name="{{ $mhs->name }}" data-nim="{{ $mhs->nim_nip }}">
                            <div class="font-bold text-gray-800 text-sm">{{ $mhs->name }}</div>
                            <div class="text-[10px] text-yellow-600 font-black uppercase tracking-widest mt-0.5">NIM: {{ $mhs->nim_nip }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 3. Daftar Mahasiswa yang Dipilih --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Mahasiswa Terpilih</label>
                <div id="selected-students-container" class="flex flex-wrap gap-3 mt-2 min-h-[50px] p-4 bg-gray-50/50 border border-dashed border-gray-200 rounded-2xl">
                    {{-- Diisi via JS --}}
                </div>
                <div id="hidden-inputs-container"></div>
            </div>

            {{-- 4. Nama Prestasi --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lomba / Kegiatan</label>
                <input type="text" name="nama_prestasi" value="{{ old('nama_prestasi', $prestasi->nama_prestasi) }}" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
            </div>

            {{-- 5. Tingkat & Tahun Akademik --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tingkat</label>
                <select name="tingkat_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
                    @foreach($tingkat as $t)
                    <option value="{{ $t->id }}" {{ $prestasi->tingkat_id == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
                    @foreach($tahunAkademik as $ta)
                    <option value="{{ $ta->id }}" {{ $prestasi->tahun_akademik_id == $ta->id ? 'selected' : '' }}>{{ $ta->tahun }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 6. Kategori & Jenis Prestasi (Dengan Fitur Lainnya) --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Prestasi</label>
                <select name="kategori_id" id="kategori_select" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ $prestasi->kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                    <option value="lainnya" class="font-bold text-yellow-600 bg-yellow-50">+ Tambah Lainnya...</option>
                </select>
                <div id="kategori_lainnya_container" class="hidden mt-2">
                    <input type="text" name="kategori_baru" id="kategori_baru" placeholder="Ketik kategori baru..." class="w-full px-4 py-3 border border-dashed border-yellow-400 bg-yellow-50/30 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kegiatan</label>
                <select name="jenis_id" id="jenis_select" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
                    @foreach($jenis as $j)
                    <option value="{{ $j->id }}" {{ $prestasi->jenis_id == $j->id ? 'selected' : '' }}>{{ $j->nama_jenis }}</option>
                    @endforeach
                    <option value="lainnya" class="font-bold text-yellow-600 bg-yellow-50">+ Tambah Lainnya...</option>
                </select>
                <div id="jenis_lainnya_container" class="hidden mt-2">
                    <input type="text" name="jenis_baru" id="jenis_baru" placeholder="Ketik jenis kegiatan baru..." class="w-full px-4 py-3 border border-dashed border-yellow-400 bg-yellow-50/30 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
                </div>
            </div>

            {{-- 7. Tanggal & Upload Sertifikat --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Sertifikat</label>
                <input type="date" name="tanggal_peroleh" value="{{ old('tanggal_peroleh', \Carbon\Carbon::parse($prestasi->tanggal_peroleh)->format('Y-m-d')) }}" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Update Sertifikat (Opsional)</label>
                <input type="file" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png" class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-2xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-yellow-50 file:text-yellow-600 hover:file:bg-yellow-100 transition-all">
                <a href="{{ asset('storage/'.$prestasi->sertifikat) }}" target="_blank" class="text-[10px] text-blue-500 hover:underline mt-1 ml-1 block"><i class="bi bi-box-arrow-up-right"></i> Lihat Sertifikat Saat Ini</a>
            </div>

            {{-- 8. Deskripsi --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 outline-none transition-all">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50 flex flex-wrap gap-4">
            <button type="submit" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-white bg-yellow-500 rounded-2xl hover:bg-yellow-600 shadow-lg shadow-yellow-100 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                <!-- <i class="bi bi-save2-fill text-lg"></i> -->
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
        const radioButtons = document.getElementsByName('jenis_kepesertaan');

        // AMBIL DATA DARI CONTROLLER
        let selectedUsers = @json($mahasiswaTerpilih ?? []);

        // Render UI Chip Mahasiswa
        function updateSelectedUI() {
            selectedContainer.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';

            if (!selectedUsers || selectedUsers.length === 0) {
                selectedContainer.innerHTML = '<div class="text-gray-400 text-xs italic">Belum ada mahasiswa yang dipilih...</div>';
                return;
            }

            selectedUsers.forEach(user => {
                const chip = document.createElement('div');
                chip.className = "flex items-center gap-2 bg-white border border-gray-200 px-3 py-2 rounded-xl shadow-sm animate-in fade-in zoom-in duration-200";
                chip.innerHTML = `
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-800">${user.name}</span>
                        <span class="text-[9px] text-yellow-600 font-black uppercase">NIM: ${user.nim}</span>
                    </div>
                    <button type="button" class="ml-2 text-gray-400 hover:text-red-500 transition-colors" onclick="removeUser('${user.id}')">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                `;
                selectedContainer.appendChild(chip);

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'user_ids[]';
                hiddenInput.value = user.id;
                hiddenInputsContainer.appendChild(hiddenInput);
            });
        }

        // FUNGSI HAPUS (Diberi titik koma di akhir agar tidak error)
        window.removeUser = function(id) {
            selectedUsers = selectedUsers.filter(user => String(user.id) !== String(id));
            updateSelectedUI();
        }; // <--- INI TERSANGKANYA BANG, KEMARIN KETINGGALAN TITIK KOMA DI SINI

        // Logic Input "Lainnya"
        const types = ['kategori', 'jenis'];
        types.forEach(type => {
            const select = document.getElementById(`${type}_select`);
            const container = document.getElementById(`${type}_lainnya_container`);
            const input = document.getElementById(`${type}_baru`);

            if (select) {
                select.addEventListener('change', function() {
                    if (this.value === 'lainnya') {
                        container.classList.remove('hidden');
                        input.setAttribute('required', 'required');
                        input.focus();
                    } else {
                        container.classList.add('hidden');
                        input.removeAttribute('required');
                        input.value = '';
                    }
                });
            }
        });

        // Search Logic
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.length > 0) dropdown.classList.remove('hidden');
        });
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            if (term.length > 0) {
                dropdown.classList.remove('hidden');
                listItems.forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    const nim = item.getAttribute('data-nim').toLowerCase();
                    item.classList.toggle('hidden', !(name.includes(term) || nim.includes(term)));
                });
            } else {
                dropdown.classList.add('hidden');
            }
        });

        // Pilih Mahasiswa Logic
        listItems.forEach(item => {
            item.addEventListener('click', function() {
                const id = String(this.getAttribute('data-id'));
                const name = this.getAttribute('data-name');
                const nim = this.getAttribute('data-nim');
                const isTim = document.querySelector('input[name="jenis_kepesertaan"]:checked').value === 'tim';

                if (!isTim) selectedUsers = [];

                if (!selectedUsers.find(u => String(u.id) === id)) {
                    selectedUsers.push({
                        id,
                        name,
                        nim
                    });
                    updateSelectedUI();
                }

                searchInput.value = '';
                dropdown.classList.add('hidden');
            });
        });

        // Toggle Individu/Tim
        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.value === 'individu' && selectedUsers.length > 1) {
                    selectedUsers = [selectedUsers[0]];
                }
                updateSelectedUI();
            });
        });

        // Close dropdown
        document.addEventListener('click', function(e) {
            if (!document.getElementById('student-search-container').contains(e.target)) dropdown.classList.add('hidden');
        });

        // PANGGIL FUNGSI UNTUK MENAMPILKAN CHIP SAAT HALAMAN DIBUKA
        updateSelectedUI();
    });
</script>
@endsection