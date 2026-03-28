@extends('layouts.app')

@section('content')

{{-- Script SortableJS untuk Drag & Drop --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

{{-- ALERT NOTIFIKASI --}}
@if(session('success'))
<div id="success-alert" class="mb-6 max-w-4xl mx-auto flex items-center justify-between p-3 text-sm font-medium text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm">
    <div class="flex items-center gap-2"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

<div class="max-w-4xl mx-auto pb-24">
    
    {{-- BREADCRUMB --}}
    <a href="{{ route('prestasi.formulir-prestasi.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors mb-6 uppercase tracking-widest text-[10px]">
        <i class="bi bi-arrow-left text-sm"></i> Kembali ke Daftar Form
    </a>

    {{-- HEADER FORM BUILDER --}}
    <div class="bg-white rounded-3xl border border-gray-200 p-6 md:p-8 shadow-sm mb-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-[#006633]"></div>
        
        <h2 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight leading-tight mb-3">
            {{ $form->nama_form ?? 'Sertifikasi Internasional & Nasional' }}
        </h2>
        
        <div class="flex flex-wrap items-center gap-3 text-sm">
            @if(isset($form->is_active) && $form->is_active)
                <span class="bg-green-100 text-green-800 font-bold px-3 py-1 rounded-full text-[10px] uppercase tracking-widest flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full animate-pulse"></span> Aktif
                </span>
            @else
                <span class="bg-red-100 text-red-800 font-bold px-3 py-1 rounded-full text-[10px] uppercase tracking-widest flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span> Non-Aktif
                </span>
            @endif
            
            <span class="text-gray-400 font-medium flex items-center gap-2 text-xs">
                <span id="total-fields" class="font-bold text-gray-700">{{ $form->fields->count() ?? 0 }}</span> field
            </span>
            <span class="text-gray-300 hidden md:inline">•</span>
            <span class="text-gray-400 font-medium flex items-center gap-2 text-xs w-full md:w-auto mt-1 md:mt-0">
                Dibuat {{ isset($form->created_at) ? $form->created_at->format('d M Y') : '17 Mar 2026' }}
            </span>
        </div>
    </div>
    <div class="mb-4 bg-blue-50/50 border border-dashed border-blue-200 rounded-[14px] p-4 flex flex-col md:flex-row md:items-center gap-3 opacity-70 select-none">
        <div class="flex items-start gap-3 w-full flex-1">
            <div class="shrink-0 w-8 h-8 bg-blue-100 border border-blue-200 rounded-lg flex items-center justify-center text-blue-500">
                <i class="bi bi-lock-fill"></i>
            </div>
            <div class="flex-1 min-w-0 pl-1">
                <h4 class="text-sm font-bold text-blue-900 leading-tight mb-1">Identitas Pelapor, Prodi, Fakultas & Anggota Tim</h4>
                <p class="text-[10px] text-blue-600 italic">Bagian ini akan otomatis di-generate oleh sistem saat form diisi oleh Mahasiswa. Anda tidak perlu membuat pertanyaannya lagi.</p>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-md text-[9px] font-bold tracking-wide uppercase">BAWAAN SISTEM</span>
                </div>
            </div>
        </div>
    </div>  

    {{-- AREA DAFTAR PERTANYAAN (DRAGGABLE) --}}
    <div class="space-y-2.5" id="sortable-list">
        @forelse($form->fields as $index => $field)
        
        @php
            $bgBadge = 'bg-gray-100'; $textBadge = 'text-gray-600';
            if($field->tipe == 'text' || $field->tipe == 'textarea') { $bgBadge = 'bg-blue-50'; $textBadge = 'text-blue-600'; }
            elseif(in_array($field->tipe, ['select', 'radio', 'checkbox', 'anggota_kelompok'])) { $bgBadge = 'bg-orange-50'; $textBadge = 'text-orange-700'; }
            elseif($field->tipe == 'date') { $bgBadge = 'bg-green-50'; $textBadge = 'text-green-700'; }
            elseif($field->tipe == 'file') { $bgBadge = 'bg-pink-50'; $textBadge = 'text-pink-700'; }
        @endphp

        <div class="bg-[#F9F9F8] border border-gray-200 rounded-[14px] p-3 md:p-4 flex flex-col md:flex-row md:items-center gap-3 hover:border-[#006633] transition-all" data-id="{{ $field->id }}">
            
            <div class="flex items-start gap-3 w-full md:w-auto flex-1">
                {{-- Drag Handle --}}
                <div class="drag-handle cursor-grab text-gray-300 hover:text-gray-500 shrink-0 mt-1 md:mt-0">
                    <i class="bi bi-grip-vertical text-lg"></i>
                </div>

                {{-- Nomor Urut --}}
                <div class="shrink-0 w-8 h-8 bg-indigo-50 border border-indigo-100 rounded-lg flex items-center justify-center font-black text-indigo-600 text-sm number-indicator">
                    {{ $index + 1 }}
                </div>

                {{-- Info Utama --}}
                <div class="flex-1 min-w-0 pl-1">
                    <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">{{ $field->label }}</h4>
                    @if($field->keterangan)
                        <p class="text-[10px] text-gray-400 mb-2 italic">{{ $field->keterangan }}</p>
                    @endif
                    
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="{{ $bgBadge }} {{ $textBadge }} px-2 py-0.5 rounded-md text-[9px] font-bold tracking-wide uppercase">
                            {{ $field->tipe }}
                        </span>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-md border {{ $field->is_required ? 'border-red-200 text-red-600 bg-red-50' : 'border-gray-200 text-gray-400 bg-white' }}">
                            {{ $field->is_required ? 'WAJIB' : 'OPSIONAL' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Aksi (Selalu Terlihat) --}}
            <div class="flex items-center justify-end gap-2 shrink-0 mt-2 md:mt-0 pt-2 md:pt-0 border-t border-gray-200 md:border-t-0 border-dashed">
                <button onclick="openEditFieldModal('{{ $field->id }}', '{{ addslashes($field->label) }}', '{{ $field->tipe }}', '{{ addslashes($field->keterangan) }}', {{ $field->is_required ? 'true' : 'false' }}, '{{ is_array($field->opsi) ? implode('||', $field->opsi) : '' }}')" 
                        class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors shadow-sm" title="Edit">
                    <i class="bi bi-pencil-square text-sm"></i>
                </button>
                <form action="{{ route('prestasi.formulir-prestasi.field.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors shadow-sm" title="Hapus">
                        <i class="bi bi-trash3 text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-10 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <p class="text-sm text-gray-400">Belum ada field pertanyaan.</p>
        </div>
        @endforelse
    </div>

    <button onclick="openModal('modal-tambah-field')" class="mt-5 w-full bg-white border border-dashed border-gray-300 text-gray-500 hover:text-[#006633] hover:border-[#006633] hover:bg-green-50 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
        <i class="bi bi-plus-lg"></i> Tambah field baru
    </button>

    <div id="saving-indicator" class="fixed bottom-6 right-6 bg-gray-900 text-white px-4 py-3 rounded-xl text-sm font-medium shadow-2xl transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-3 z-50">
        <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
        Menyimpan urutan...
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modal-tambah-field" class="hidden fixed inset-0 z-50 overflow-hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh] transition-all transform">
        
        {{-- Header: Tetap di Atas --}}
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0 rounded-t-3xl">
            <h3 class="font-black text-gray-900 text-lg uppercase tracking-tight">Buat Field Baru</h3>
            <button onclick="closeModal('modal-tambah-field')" class="text-gray-400 hover:text-red-500 transition-colors"><i class="bi bi-x-lg"></i></button>
        </div>

        {{-- Body: Scrollable Area --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
            <form action="{{ route('prestasi.formulir-prestasi.field.store', $form->id) }}" id="form-tambah-field" method="POST" class="space-y-6">
                @csrf
                
                {{-- PILIH TIPE FIELD --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-3">Tipe Field yang Tersedia <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2.5">
                        @php
                            $types = [
                                ['id' => 'text', 'icon' => 'bi-input-cursor-text', 'title' => 'Teks'],
                                ['id' => 'textarea', 'icon' => 'bi-text-paragraph', 'title' => 'Paragraf'],
                                ['id' => 'number', 'icon' => 'bi-123', 'title' => 'Angka'],
                                ['id' => 'date', 'icon' => 'bi-calendar-event', 'title' => 'Tanggal'],
                                ['id' => 'file', 'icon' => 'bi-cloud-arrow-up', 'title' => 'Upload'],
                                ['id' => 'select', 'icon' => 'bi-menu-button-wide', 'title' => 'Dropdown'],
                                ['id' => 'radio', 'icon' => 'bi-ui-radios', 'title' => 'Radio'],
                                ['id' => 'checkbox', 'icon' => 'bi-ui-checks', 'title' => 'Ceklis'],
                                // ['id' => 'anggota_kelompok', 'icon' => 'bi-people', 'title' => 'Mhs'],
                            ];
                        @endphp
                        @foreach($types as $type)
                        <label class="cursor-pointer relative group">
                            <input type="radio" name="tipe" value="{{ $type['id'] }}" class="peer hidden custom-type-radio" required {{ $loop->first ? 'checked' : '' }}>
                            <div class="p-2 border-2 border-gray-100 rounded-xl peer-checked:border-[#006633] peer-checked:bg-green-50 transition-all text-center h-full flex flex-col items-center justify-center hover:border-gray-300">
                                <i class="bi {{ $type['icon'] }} text-lg text-gray-400 peer-checked:text-[#006633] mb-1 transition-colors"></i>
                                <span class="block text-[9px] font-bold text-gray-600 peer-checked:text-[#006633]">{{ $type['title'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Label Pertanyaan</label>
                        <input type="text" name="label" required placeholder="Contoh: Nama Lomba" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[#006633] outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Format PDF maks 2MB" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[#006633] outline-none">
                    </div>
                </div>

                {{-- CHIP OPSI (Input Dinamis) --}}
                <div id="wrapper-opsi-add" class="hidden bg-gray-50 border border-gray-200 rounded-2xl p-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Daftar Pilihan Opsi</label>
                    <p class="text-[10px] text-gray-500 mb-2 ml-1">Tekan <kbd class="px-1.5 py-0.5 bg-gray-200 rounded font-mono">Enter</kbd> untuk menambah opsi</p>
                    <div class="w-full min-h-[44px] px-2 py-1 border border-gray-300 rounded-xl bg-white flex flex-wrap gap-1 items-center focus-within:border-[#006633]" id="chip-container-add">
                        <input type="text" id="chip-input-add" class="flex-grow min-w-[120px] outline-none text-sm p-1.5" placeholder="Ketik opsi di sini...">
                    </div>
                    <input type="hidden" name="opsi" id="hidden-opsi-add">
                </div>

                <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">
                    <input type="checkbox" name="is_required" value="1" checked id="req-add" class="w-4 h-4 text-[#006633] border-gray-300 rounded cursor-pointer">
                    <label for="req-add" class="text-sm font-bold text-gray-700 cursor-pointer">Jadikan field ini Wajib Diisi (Required)</label>
                </div>
            </form>
        </div>

        {{-- Footer: Tetap di Bawah --}}
        <div class="p-6 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3 rounded-b-3xl">
            <button type="button" onclick="closeModal('modal-tambah-field')" class="w-1/3 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
            <button type="submit" form="form-tambah-field" onclick="prepareSubmitAdd()" class="w-2/3 py-3 text-sm font-bold text-white bg-[#006633] rounded-xl hover:bg-[#004d26] transition-colors shadow-lg shadow-green-100">Simpan Pertanyaan</button>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modal-edit-field" class="hidden fixed inset-0 z-50 overflow-hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh]">
        
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0 rounded-t-3xl">
            <h3 class="font-black text-gray-900 text-lg uppercase tracking-tight">Edit Field</h3>
            <button onclick="closeEditFieldModal()" class="text-gray-400 hover:text-red-500 transition-colors"><i class="bi bi-x-lg"></i></button>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
            <form id="form-edit-field" method="POST" class="space-y-6">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Label Pertanyaan</label>
                        <input type="text" name="label" id="edit-field-label" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[#006633] outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Tipe Input</label>
                        <select name="tipe" id="edit-field-tipe" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[#006633] outline-none bg-white">
                            <option value="text">Teks Pendek</option>
                            <option value="textarea">Paragraf</option>
                            <option value="number">Angka</option>
                            <option value="date">Tanggal</option>
                            <option value="file">Upload File</option>
                            <option value="select">Dropdown</option>
                            <option value="radio">Radio Button</option>
                            <option value="checkbox">Checkbox</option>
                            {{-- <option value="anggota_kelompok">Mahasiswa</option> --}}
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">Keterangan (Opsional)</label>
                    <input type="text" name="keterangan" id="edit-field-keterangan" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-[#006633] outline-none">
                </div>

                <div id="wrapper-opsi-edit" class="hidden bg-gray-50 border border-gray-200 rounded-2xl p-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Daftar Pilihan Opsi</label>
                    <div class="w-full min-h-[44px] px-2 py-1 border border-gray-300 rounded-xl bg-white flex flex-wrap gap-1 items-center focus-within:border-[#006633]" id="chip-container-edit">
                        <input type="text" id="chip-input-edit" class="flex-grow min-w-[100px] outline-none text-sm p-1.5" placeholder="Ketik opsi...">
                    </div>
                    <input type="hidden" name="opsi" id="hidden-opsi-edit">
                </div>

                <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100">
                    <input type="checkbox" name="is_required" id="edit-field-required" value="1" class="w-4 h-4 text-[#006633] border-gray-300 rounded cursor-pointer">
                    <label for="edit-field-required" class="text-sm font-bold text-gray-700 cursor-pointer">Wajib Diisi (Required)</label>
                </div>
            </form>
        </div>

        <div class="p-6 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3 rounded-b-3xl">
            <button type="button" onclick="closeEditFieldModal()" class="w-1/3 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200">Batal</button>
            <button type="submit" form="form-edit-field" onclick="prepareSubmitEdit()" class="w-2/3 py-3 text-sm font-bold text-white bg-yellow-500 rounded-xl hover:bg-yellow-600 shadow-lg">Simpan Perubahan</button>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar Slim */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #006633; }

    .sortable-ghost { opacity: 0.3 !important; background-color: #f9fafb !important; border: 2px dashed #cbd5e1 !important; }
    .sortable-drag { cursor: grabbing !important; background-color: #ffffff !important; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1) !important; transition: none !important; }
    .sortable-fallback { transition: none !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('sortable-list');
        const savingIndicator = document.getElementById('saving-indicator');
        
        if(list) {
            new Sortable(list, {
                handle: '.drag-handle', animation: 150, ghostClass: 'sortable-ghost', dragClass: 'sortable-drag',
                scroll: true, forceFallback: true, fallbackClass: 'sortable-fallback', scrollSensitivity: 100, scrollSpeed: 20,
                onEnd: function () {
                    updateNomorUrut();
                    let order = [];
                    list.querySelectorAll('[data-id]').forEach(el => order.push(el.getAttribute('data-id')));
                    savingIndicator.classList.remove('translate-y-20', 'opacity-0');
                    fetch("{{ route('prestasi.formulir-prestasi.field.reorder', $form->id) }}", {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ order: order })
                    }).then(res => res.json()).then(() => {
                        savingIndicator.innerHTML = '<i class="bi bi-check-circle-fill text-green-400"></i> Urutan disimpan!';
                        setTimeout(() => {
                            savingIndicator.classList.add('translate-y-20', 'opacity-0');
                            setTimeout(() => { savingIndicator.innerHTML = '<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Menyimpan urutan...'; }, 300);
                        }, 1500);
                    });
                }
            });
        }
        function updateNomorUrut() { list.querySelectorAll('.number-indicator').forEach((item, idx) => item.textContent = idx + 1); }
    });

    function needsOptions(t) { return ['select', 'radio', 'checkbox'].includes(t); }

    document.querySelectorAll('.custom-type-radio').forEach(r => {
        r.addEventListener('change', function() {
            const w = document.getElementById('wrapper-opsi-add');
            if(needsOptions(this.value)) w.classList.remove('hidden');
            else { w.classList.add('hidden'); opsiArrayAdd = []; renderChips('chip-container-add', []); }
        });
    });

    document.getElementById('edit-field-tipe').addEventListener('change', function() {
        const w = document.getElementById('wrapper-opsi-edit');
        if(needsOptions(this.value)) w.classList.remove('hidden'); else w.classList.add('hidden');
    });

    let opsiArrayAdd = [], opsiArrayEdit = [];
    function renderChips(cId, data) {
        const c = document.getElementById(cId), input = c.querySelector('input');
        c.querySelectorAll('.chip-item').forEach(el => el.remove());
        data.forEach((t, i) => {
            const chip = document.createElement('div');
            chip.className = 'chip-item flex items-center gap-1.5 bg-green-100 text-green-800 font-medium px-2.5 py-1 rounded-md text-[10px]';
            chip.innerHTML = `<span>${t}</span><button type="button" class="text-green-600 font-bold" onclick="removeChip(${i}, '${cId}')">&times;</button>`;
            c.insertBefore(chip, input);
        });
    }

    window.removeChip = function(i, cId) {
        if (cId === 'chip-container-add') { opsiArrayAdd.splice(i, 1); renderChips(cId, opsiArrayAdd); } 
        else { opsiArrayEdit.splice(i, 1); renderChips(cId, opsiArrayEdit); }
    }

    function setupChipInput(iId, cId, arr) {
        document.getElementById(iId).addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault(); const v = this.value.trim().replace(/,/g, '');
                if (v && !arr.includes(v)) { arr.push(v); renderChips(cId, arr); }
                this.value = ''; 
            } else if (e.key === 'Backspace' && !this.value && arr.length) { arr.pop(); renderChips(cId, arr); }
        });
    }
    setupChipInput('chip-input-add', 'chip-container-add', opsiArrayAdd);
    setupChipInput('chip-input-edit', 'chip-container-edit', opsiArrayEdit);

    function prepareSubmitAdd() { document.getElementById('hidden-opsi-add').value = opsiArrayAdd.join(','); }
    function prepareSubmitEdit() { document.getElementById('hidden-opsi-edit').value = opsiArrayEdit.join(','); }
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }

    function openEditFieldModal(id, label, tipe, ket, req, ops) {
        document.getElementById('form-edit-field').action = `/prestasi/formulir-prestasi/field/${id}`;
        document.getElementById('edit-field-label').value = label;
        document.getElementById('edit-field-tipe').value = tipe;
        document.getElementById('edit-field-keterangan').value = ket;
        document.getElementById('edit-field-required').checked = req;
        const w = document.getElementById('wrapper-opsi-edit');
        if(needsOptions(tipe)) { w.classList.remove('hidden'); opsiArrayEdit = ops ? ops.split('||') : []; renderChips('chip-container-edit', opsiArrayEdit); }
        else { w.classList.add('hidden'); opsiArrayEdit = []; renderChips('chip-container-edit', []); }
        openModal('modal-edit-field');
    }
    function closeEditFieldModal() { closeModal('modal-edit-field'); }

    document.querySelectorAll('form').forEach(f => {
        f.addEventListener('keydown', e => { if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && !e.target.id.includes('chip-input')) e.preventDefault(); });
    });
</script>
@endsection