@extends('layouts.app')

@section('content')
@php 
    $routePrefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin'; 
@endphp

{{-- ALERT NOTIFIKASI --}}
@if(session('success'))
<div id="success-alert" class="mb-6 flex items-center justify-between p-3 text-sm font-medium text-green-800 rounded-lg bg-green-50 border border-green-200">
    <div class="flex items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

{{-- BREADCRUMB & HEADER --}}
<div class="mb-6">
    <a href="{{ route($routePrefix . '.manajemen-form') }}" class="text-sm font-medium text-gray-500 hover:text-[#006633] transition-colors flex items-center gap-2 mb-4">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Form
    </a>
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $kategori->nama_kategori }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $kategori->deskripsi ?? 'Atur pertanyaan yang akan ditampilkan kepada mahasiswa.' }}</p>
        </div>
        <div class="text-sm text-gray-500 bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-sm">
            Total: <span class="font-bold text-gray-900">{{ $kategori->fields->count() }} Pertanyaan</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    {{-- ======================================================= --}}
    {{-- SISI KIRI: DAFTAR PERTANYAAN (COMPACT LIST)             --}}
    {{-- ======================================================= --}}
    <div class="lg:col-span-2 space-y-2.5">
        
        @forelse($kategori->fields as $field)
        <div class="group flex flex-col sm:flex-row sm:items-center justify-between px-4 py-3 bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-colors shadow-sm">
            
            <div class="flex items-start sm:items-center gap-3 w-full">
                {{-- Ikon Compact --}}
                <div class="text-gray-400 w-6 text-center mt-1 sm:mt-0">
                    @switch($field->tipe)
                        @case('text') <i class="bi bi-input-cursor-text text-base"></i> @break
                        @case('textarea') <i class="bi bi-text-paragraph text-base"></i> @break
                        @case('number') <i class="bi bi-123 text-base"></i> @break
                        @case('date') <i class="bi bi-calendar-event text-base"></i> @break
                        @case('file') <i class="bi bi-cloud-arrow-up text-base"></i> @break
                        @case('select') <i class="bi bi-menu-button text-base"></i> @break
                        @case('anggota_kelompok') <i class="bi bi-people text-base"></i> @break
                        @default <i class="bi bi-record-circle text-base"></i>
                    @endswitch
                </div>

                {{-- Konten Pertanyaan --}}
                <div class="flex-grow flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    <div class="min-w-[150px]">
                        <h4 class="text-sm font-semibold text-gray-900 leading-tight">{{ $field->label }}</h4>
                        @if($field->tipe === 'select' && is_array($field->opsi))
                            <p class="text-[10px] text-gray-500 mt-0.5 truncate max-w-[200px]" title="{{ implode(', ', $field->opsi) }}">Opsi: {{ implode(', ', $field->opsi) }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-medium text-gray-500 bg-gray-100 border border-gray-200 px-1.5 py-0.5 rounded whitespace-nowrap">
                            {{ str_replace('_', ' ', ucwords($field->tipe)) }}
                        </span>
                        @if($field->is_required) 
                            <span class="text-[10px] font-bold text-red-600 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded">WAJIB</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-1 mt-2 sm:mt-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity shrink-0">
                <button onclick="openEditFieldModal('{{ $field->id }}', '{{ addslashes($field->label) }}', '{{ $field->tipe }}', '{{ addslashes($field->keterangan) }}', {{ $field->is_required ? 'true' : 'false' }}, '{{ is_array($field->opsi) ? implode('||', $field->opsi) : '' }}')" 
                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                    <i class="bi bi-pencil text-sm"></i>
                </button>
                
                <form action="{{ route($routePrefix . '.manajemen-form.destroyField', $field->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pertanyaan ini secara permanen?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                        <i class="bi bi-trash3 text-sm"></i>
                    </button>
                </form>
            </div>
            
        </div>
        @empty
        <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
            <i class="bi bi-inboxes text-3xl text-gray-300 mb-2 block"></i>
            <h4 class="text-sm font-semibold text-gray-900 mb-1">Belum Ada Pertanyaan</h4>
            <p class="text-xs text-gray-500">Silakan tambahkan pertanyaan baru di panel samping.</p>
        </div>
        @endforelse

    </div>

    {{-- ======================================================= --}}
    {{-- SISI KANAN: FORM TAMBAH PERTANYAAN                      --}}
    {{-- ======================================================= --}}
    <div class="lg:col-span-1">
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm sticky top-6">
            
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 rounded-t-lg">
                <h3 class="font-semibold text-gray-900 text-sm">Tambah Input Baru</h3>
            </div>

            <form action="{{ route($routePrefix . '.manajemen-form.storeField', $kategori->id) }}" method="POST" class="p-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Label Pertanyaan <span class="text-red-500">*</span></label>
                    <input type="text" name="label" required placeholder="Contoh: Nama Lomba" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Input <span class="text-red-500">*</span></label>
                    <select name="tipe" id="tipe-select-add" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none bg-white">
                        <option value="text">Teks Pendek</option>
                        <option value="textarea">Paragraf (Teks Panjang)</option>
                        <option value="number">Angka</option>
                        <option value="date">Tanggal</option>
                        <option value="file">Upload File</option>
                        <option value="select">Pilihan (Dropdown)</option>
                        <option value="anggota_kelompok">Pemilihan Anggota Tim</option>
                    </select>
                </div>

                {{-- CHIP OPSI BUILDER (HANYA MUNCUL JIKA TIPE = SELECT) --}}
                <div id="wrapper-opsi-add" class="hidden mt-2 p-3 bg-gray-50 border border-gray-200 rounded">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Daftar Pilihan Opsi <span class="text-red-500">*</span></label>
                    <p class="text-[10px] text-gray-500 mb-2">Ketik pilihan lalu tekan <kbd class="px-1 py-0.5 bg-gray-200 rounded text-gray-700">Enter</kbd></p>
                    
                    <div class="w-full min-h-[40px] px-2 py-1.5 border border-gray-300 rounded bg-white flex flex-wrap gap-1 items-center focus-within:border-[#006633] focus-within:ring-1 focus-within:ring-[#006633] transition-colors" id="chip-container-add">
                        <input type="text" id="chip-input-add" class="flex-grow min-w-[100px] outline-none text-sm p-1" placeholder="Ketik di sini...">
                    </div>
                    
                    {{-- Input Hidden untuk mengirim data ke Controller --}}
                    <input type="hidden" name="opsi" id="hidden-opsi-add">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Format PDF maks 2MB" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none">
                </div>
                <div class="flex items-start gap-2 pt-1">
                    <input type="checkbox" name="is_required" value="1" checked class="mt-0.5 w-3.5 h-3.5 text-[#006633] border-gray-300 rounded focus:ring-[#006633]">
                    <label class="text-xs text-gray-700 cursor-pointer"><span class="font-medium block">Wajib Diisi</span></label>
                </div>
                <button type="submit" onclick="prepareSubmitAdd()" class="w-full bg-[#006633] text-white py-2 rounded font-medium text-sm hover:bg-[#004d26] transition-colors">
                    Simpan Pertanyaan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- MODAL EDIT PERTANYAAN                                   --}}
{{-- ======================================================= --}}
<div id="modal-edit-field" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-sm shadow-2xl">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-xl">
            <h3 class="font-bold text-gray-900 text-sm">Edit Pertanyaan</h3>
            <button onclick="closeEditFieldModal()" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-edit-field" method="POST" class="p-4 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Label Pertanyaan <span class="text-red-500">*</span></label>
                <input type="text" name="label" id="edit-field-label" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#006633] outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Input <span class="text-red-500">*</span></label>
                <select name="tipe" id="edit-field-tipe" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#006633] outline-none bg-white">
                    <option value="text">Teks Pendek</option>
                    <option value="textarea">Paragraf (Teks Panjang)</option>
                    <option value="number">Angka</option>
                    <option value="date">Tanggal</option>
                    <option value="file">Upload File</option>
                    <option value="select">Pilihan (Dropdown)</option>
                    <option value="anggota_kelompok">Pemilihan Anggota Tim</option>
                </select>
            </div>

            {{-- CHIP OPSI BUILDER UNTUK MODAL EDIT --}}
            <div id="wrapper-opsi-edit" class="hidden mt-2 p-3 bg-gray-50 border border-gray-200 rounded">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Daftar Pilihan Opsi <span class="text-red-500">*</span></label>
                <p class="text-[10px] text-gray-500 mb-2">Ketik pilihan lalu tekan <kbd class="px-1 py-0.5 bg-gray-200 rounded text-gray-700">Enter</kbd></p>
                
                <div class="w-full min-h-[40px] px-2 py-1.5 border border-gray-300 rounded bg-white flex flex-wrap gap-1 items-center focus-within:border-[#006633]" id="chip-container-edit">
                    <input type="text" id="chip-input-edit" class="flex-grow min-w-[100px] outline-none text-sm p-1" placeholder="Ketik opsi...">
                </div>
                
                <input type="hidden" name="opsi" id="hidden-opsi-edit">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Keterangan</label>
                <input type="text" name="keterangan" id="edit-field-keterangan" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#006633] outline-none">
            </div>
            <div class="flex items-start gap-2 pt-1">
                <input type="checkbox" name="is_required" id="edit-field-required" value="1" class="mt-0.5 w-3.5 h-3.5 text-[#006633] border-gray-300 rounded focus:ring-[#006633]">
                <label class="text-xs font-medium text-gray-700 cursor-pointer">Wajib Diisi</label>
            </div>
            <div class="flex gap-2 pt-3">
                <button type="button" onclick="closeEditFieldModal()" class="w-1/3 bg-gray-100 text-gray-600 py-2 rounded font-medium text-sm hover:bg-gray-200">Batal</button>
                <button type="submit" onclick="prepareSubmitEdit()" class="w-2/3 bg-yellow-500 text-white py-2 rounded font-medium text-sm hover:bg-yellow-600 transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ==========================================
    // LOGIKA CHIP INPUT (ADD & EDIT)
    // ==========================================
    let opsiArrayAdd = [];
    let opsiArrayEdit = [];

    // Setup fungsi untuk merender chip ke dalam container HTML
    function renderChips(containerId, dataArray, arrayReference) {
        const container = document.getElementById(containerId);
        const inputElem = container.querySelector('input');
        
        // Hapus semua chip yang ada (kecuali input text)
        container.querySelectorAll('.chip-item').forEach(el => el.remove());

        // Buat elemen chip untuk setiap data
        dataArray.forEach((teks, index) => {
            const chip = document.createElement('div');
            chip.className = 'chip-item flex items-center gap-1 bg-green-50 border border-green-200 text-green-700 px-2 py-0.5 rounded text-xs';
            chip.innerHTML = `
                <span>${teks}</span>
                <button type="button" class="text-green-500 hover:text-green-800 ml-1 font-bold" onclick="removeChip(${index}, '${containerId}')">&times;</button>
            `;
            container.insertBefore(chip, inputElem);
        });
    }

    // Fungsi menghapus chip
    window.removeChip = function(index, containerId) {
        if (containerId === 'chip-container-add') {
            opsiArrayAdd.splice(index, 1);
            renderChips('chip-container-add', opsiArrayAdd, opsiArrayAdd);
        } else {
            opsiArrayEdit.splice(index, 1);
            renderChips('chip-container-edit', opsiArrayEdit, opsiArrayEdit);
        }
    }

    // Fungsi menangani pengetikan Enter di input chip
    function handleChipInput(inputId, containerId, dataArray) {
        const inputElem = document.getElementById(inputId);
        inputElem.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const val = this.value.trim().replace(/,/g, ''); // bersihkan teks
                if (val !== '' && !dataArray.includes(val)) {
                    dataArray.push(val);
                    renderChips(containerId, dataArray, dataArray);
                }
                this.value = ''; // kosongkan input lagi
            } else if (e.key === 'Backspace' && this.value === '' && dataArray.length > 0) {
                // Hapus chip terakhir kalau pencet backspace saat input kosong
                dataArray.pop();
                renderChips(containerId, dataArray, dataArray);
            }
        });
    }

    // Inisialisasi Event Listener
    handleChipInput('chip-input-add', 'chip-container-add', opsiArrayAdd);
    handleChipInput('chip-input-edit', 'chip-container-edit', opsiArrayEdit);

    // ==========================================
    // LOGIKA MENAMPILKAN/MENYEMBUNYIKAN KOTAK OPSI
    // ==========================================
    
    // --- Bagian Tambah (Add) ---
    const selectTipeAdd = document.getElementById('tipe-select-add');
    const wrapperOpsiAdd = document.getElementById('wrapper-opsi-add');
    
    selectTipeAdd.addEventListener('change', function() {
        if (this.value === 'select') {
            wrapperOpsiAdd.classList.remove('hidden');
        } else {
            wrapperOpsiAdd.classList.add('hidden');
            opsiArrayAdd = []; // reset jika batal pilih select
            renderChips('chip-container-add', opsiArrayAdd, opsiArrayAdd);
        }
    });

    // --- Bagian Edit ---
    const selectTipeEdit = document.getElementById('edit-field-tipe');
    const wrapperOpsiEdit = document.getElementById('wrapper-opsi-edit');

    selectTipeEdit.addEventListener('change', function() {
        if (this.value === 'select') {
            wrapperOpsiEdit.classList.remove('hidden');
        } else {
            wrapperOpsiEdit.classList.add('hidden');
        }
    });

    // ==========================================
    // FUNGSI MODAL EDIT & PENGIRIMAN DATA
    // ==========================================
    function openEditFieldModal(id, label, tipe, keterangan, isRequired, opsiString) {
        const prefix = '{{ Auth::user()->role === 'super_admin' ? 'super-admin' : 'admin' }}';
        
        document.getElementById('form-edit-field').action = `/${prefix}/manajemen-form/field/${id}`;
        document.getElementById('edit-field-label').value = label;
        document.getElementById('edit-field-tipe').value = tipe;
        document.getElementById('edit-field-keterangan').value = keterangan;
        document.getElementById('edit-field-required').checked = isRequired;
        
        // Cek tipe select & populate opsi chips
        if(tipe === 'select') {
            wrapperOpsiEdit.classList.remove('hidden');
            // Pecah string "Juara 1||Juara 2" jadi array
            opsiArrayEdit = opsiString ? opsiString.split('||') : [];
            renderChips('chip-container-edit', opsiArrayEdit, opsiArrayEdit);
        } else {
            wrapperOpsiEdit.classList.add('hidden');
            opsiArrayEdit = [];
            renderChips('chip-container-edit', opsiArrayEdit, opsiArrayEdit);
        }
        
        document.getElementById('modal-edit-field').classList.remove('hidden');
    }

    function closeEditFieldModal() {
        document.getElementById('modal-edit-field').classList.add('hidden');
    }

    // Fungsi untuk menyatukan array menjadi string koma saat disubmit ke backend
    function prepareSubmitAdd() {
        document.getElementById('hidden-opsi-add').value = opsiArrayAdd.join(',');
    }

    function prepareSubmitEdit() {
        document.getElementById('hidden-opsi-edit').value = opsiArrayEdit.join(',');
    }

    // Mencegah form tersubmit saat menekan Enter di input apa saja (agar enter di chip tidak merestart page)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('keydown', function(e) {
            // Biarkan enter berjalan kalau lagi di textarea
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.id !== 'chip-input-add' && e.target.id !== 'chip-input-edit') {
                e.preventDefault();
            }
        });
    });

    // Auto-hide alert sukses
    const successAlert = document.getElementById('success-alert');
    if(successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            successAlert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => successAlert.style.display = 'none', 500);
        }, 3000);
    }
</script>
@endsection