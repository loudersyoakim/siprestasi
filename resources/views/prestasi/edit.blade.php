@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('prestasi.index-all') }}" class="text-sm font-bold text-gray-400 hover:text-yellow-600 transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Edit Prestasi</h3>
    </div>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="p-6 md:p-8 border-b border-gray-50 bg-yellow-50/30">
        <h4 class="text-lg font-black text-gray-800">Formulir: {{ $prestasi->formPrestasi->nama_form }}</h4>
    </div>

    @php
        $settings = is_string($prestasi->formPrestasi->setting_statis) ? json_decode($prestasi->formPrestasi->setting_statis, true) : ($prestasi->formPrestasi->setting_statis ?? []);
        if(empty($settings)) {
            $settings = ['nama_kegiatan' => true, 'tingkat' => true, 'capaian' => true, 'tahun' => true, 'tanggal' => true];
        }
    @endphp

    <form action="{{ route('prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-8" id="form-prestasi" onsubmit="return validateForm()">
        @csrf @method('PUT')
        
        <input type="hidden" name="form_prestasi_id" value="{{ $prestasi->form_prestasi_id }}">

        {{-- BUNGKUSAN SATU KOLOM --}}
        <div class="flex flex-col gap-6">

            {{-- 1. BAGIAN KEPESERTAAN & PELAPOR --}}
            <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-3xl space-y-6">
                <div>
                    <label class="text-[10px] font-black text-blue-800 uppercase tracking-widest ml-1">Jenis Kepesertaan <span class="text-red-500">*</span></label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex-1">
                            <input type="radio" name="jenis_kepesertaan" value="individu" class="hidden peer" {{ count($mahasiswaTerpilih) <= 1 ? 'checked' : '' }}>
                            <div class="flex items-center justify-center p-3 bg-white border border-gray-200 rounded-2xl cursor-pointer peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-600 hover:bg-gray-50 transition-all">
                                <i class="bi bi-person-fill mr-2"></i><span class="text-sm font-bold">Individu</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="jenis_kepesertaan" value="tim" class="hidden peer" {{ count($mahasiswaTerpilih) > 1 ? 'checked' : '' }}>
                            <div class="flex items-center justify-center p-3 bg-white border border-gray-200 rounded-2xl cursor-pointer peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-600 hover:bg-gray-50 transition-all">
                                <i class="bi bi-people-fill mr-2"></i><span class="text-sm font-bold">Tim / Kelompok</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="relative" id="student-search-container">
                    <label class="text-[10px] font-black text-blue-800 uppercase tracking-widest ml-1">Cari Mahasiswa (Pelapor & Anggota)</label>
                    <div class="relative mt-1">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="student-search-input" placeholder="Ketik Nama atau NIM..." autocomplete="off" class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm outline-none shadow-sm focus:border-yellow-500">
                    </div>
                    <div id="student-dropdown" class="absolute z-[70] w-full mt-2 bg-white rounded-2xl shadow-2xl overflow-hidden hidden">
                        <div class="max-h-60 overflow-y-auto" id="student-list">
                            @foreach($mahasiswa as $mhs)
                            <div class="student-item px-4 py-3 hover:bg-yellow-50 cursor-pointer border-b" data-id="{{ $mhs->id }}" data-name="{{ $mhs->name }}" data-nim="{{ $mhs->nim_nip }}">
                                <div class="font-bold text-sm">{{ $mhs->name }}</div><div class="text-[10px] text-yellow-600">NIM: {{ $mhs->nim_nip }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-blue-800 uppercase tracking-widest ml-1">Mahasiswa Terpilih <span class="text-red-500">*</span></label>
                    <div id="selected-students-container" class="flex flex-col gap-2 mt-2 min-h-[60px] p-4 bg-white border border-dashed rounded-2xl shadow-sm"></div>
                    <div id="hidden-inputs-container"></div>
                </div>
            </div>

            <div class="border-b border-gray-100 my-2"></div>

            {{-- 2. BAGIAN DATA STATIS (PRELOADED) --}}
            @if(array_filter($settings))
                <div class="mb-2">
                    <h5 class="text-sm font-black text-yellow-600 uppercase tracking-widest border-l-4 border-yellow-500 pl-3">Informasi Utama Prestasi</h5>
                </div>

                @if($settings['nama_kegiatan'] ?? true)
                <div>
                    <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Nama Kegiatan / Prestasi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kegiatan" value="{{ $prestasi->nama_kegiatan }}" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none font-medium">
                </div>
                @endif

                @if($settings['tingkat'] ?? true)
                <div>
                    <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Tingkat Prestasi <span class="text-red-500">*</span></label>
                    <select name="tingkat_prestasi_id" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none bg-white cursor-pointer font-medium">
                        <option value="" hidden>Pilih Tingkat...</option>
                        @foreach($tingkat_list as $t) <option value="{{ $t->id }}" {{ $prestasi->tingkat_prestasi_id == $t->id ? 'selected' : '' }}>{{ $t->nama_tingkat }}</option> @endforeach
                    </select>
                </div>
                @endif

                @if($settings['capaian'] ?? true)
                <div>
                    <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Capaian Prestasi <span class="text-red-500">*</span></label>
                    <select name="capaian_prestasi_id" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none bg-white cursor-pointer font-medium">
                        <option value="" hidden>Pilih Capaian...</option>
                        @foreach($capaian_list as $c) <option value="{{ $c->id }}" {{ $prestasi->capaian_prestasi_id == $c->id ? 'selected' : '' }}>{{ $c->nama_capaian }}</option> @endforeach
                    </select>
                </div>
                @endif

                @if($settings['tahun'] ?? true)
                <div>
                    <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Tahun Kegiatan <span class="text-red-500">*</span></label>
                    <select name="tahun_kegiatan" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none bg-white cursor-pointer font-medium">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--) <option value="{{ $y }}" {{ $prestasi->tahun_kegiatan == $y ? 'selected' : '' }}>{{ $y }}</option> @endfor
                    </select>
                </div>
                @endif

                @if($settings['tanggal'] ?? true)
                <div>
                    <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="text" name="tanggal_mulai" value="{{ $prestasi->tanggal_mulai ? $prestasi->tanggal_mulai->format('Y-m-d') : '' }}" required class="datepicker-custom w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none font-medium bg-white">
                </div>
                <div>
                    <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2">Tanggal Selesai</label>
                    <input type="text" name="tanggal_selesai" value="{{ $prestasi->tanggal_selesai ? $prestasi->tanggal_selesai->format('Y-m-d') : '' }}" class="datepicker-custom w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none font-medium bg-white">
                </div>
                @endif
            @endif

            <div class="border-b border-gray-100 my-2"></div>

            {{-- 3. BAGIAN FORM DINAMIS (PRELOADED) --}}
            <div class="mb-2">
                <h5 class="text-sm font-black text-yellow-600 uppercase tracking-widest border-l-4 border-yellow-500 pl-3">Detail Informasi Tambahan</h5>
            </div>

            @foreach($fields as $field)
            @if($field->tipe === 'anggota_kelompok') @continue @endif

            @php 
                $inputName = "field_" . $field->id; 
                $oldValue = $prestasi->data_dinamis[$field->id] ?? '';
            @endphp
            
            <div>
                <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">
                    {{ $field->label }} @if($field->is_required) <span class="text-red-500">*</span> @endif
                </label>

                @if($field->tipe === 'date')
                    <input type="text" name="{{ $inputName }}" value="{{ is_array($oldValue) ? implode(', ', $oldValue) : $oldValue }}" {{ $field->is_required ? 'required' : '' }} placeholder="DD/MM/YYYY" class="datepicker-custom w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none bg-white font-medium">
                @elseif($field->tipe === 'text' || $field->tipe === 'number')
                    <input type="{{ $field->tipe }}" name="{{ $inputName }}" value="{{ is_array($oldValue) ? implode(', ', $oldValue) : $oldValue }}" {{ $field->is_required ? 'required' : '' }} class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none transition-all font-medium">
                @elseif($field->tipe === 'textarea')
                    <textarea name="{{ $inputName }}" rows="3" {{ $field->is_required ? 'required' : '' }} class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none transition-all font-medium">{{ $oldValue }}</textarea>
                @elseif($field->tipe === 'select')
                    <select name="{{ $inputName }}" {{ $field->is_required ? 'required' : '' }} class="condition-trigger w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-yellow-500 outline-none bg-white font-medium">
                        <option value="" hidden>Pilih...</option>
                        @if(is_array($field->opsi)) @foreach($field->opsi as $opsi) <option value="{{ trim($opsi) }}" {{ $oldValue == trim($opsi) ? 'selected' : '' }}>{{ trim($opsi) }}</option> @endforeach @endif
                    </select>
                @elseif($field->tipe === 'radio')
                    <div class="flex flex-wrap gap-4 mt-2">
                        @if(is_array($field->opsi))
                            @foreach($field->opsi as $opsi)
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 hover:border-yellow-500 transition-colors">
                                <input type="radio" name="{{ $inputName }}" value="{{ trim($opsi) }}" {{ $oldValue == trim($opsi) ? 'checked' : '' }} class="condition-trigger w-4 h-4 text-yellow-500 focus:ring-yellow-500">
                                <span class="text-sm text-gray-700 font-bold">{{ trim($opsi) }}</span>
                            </label>
                            @endforeach
                        @endif
                    </div>
                @elseif($field->tipe === 'checkbox')
                    @php $checkedArray = is_array($oldValue) ? $oldValue : []; @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                        @if(is_array($field->opsi))
                            @foreach($field->opsi as $opsi)
                            <label class="flex items-start gap-2 cursor-pointer bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 hover:border-yellow-500 transition-colors">
                                <input type="checkbox" name="{{ $inputName }}[]" value="{{ trim($opsi) }}" {{ in_array(trim($opsi), $checkedArray) ? 'checked' : '' }} class="mt-0.5 w-4 h-4 text-yellow-500 rounded focus:ring-yellow-500">
                                <span class="text-xs text-gray-700 font-bold leading-tight">{{ trim($opsi) }}</span>
                            </label>
                            @endforeach
                        @endif
                    </div>
                @elseif($field->tipe === 'file')
                    @if($oldValue)
                        <div class="mb-3"><a href="{{ asset('storage/' . $oldValue) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-wider hover:bg-blue-600 hover:text-white transition-colors"><i class="bi bi-box-arrow-up-right"></i> File Tersimpan Saat Ini</a></div>
                    @endif
                    <input type="file" name="{{ $inputName }}" class="w-full px-4 py-2 border border-gray-200 rounded-2xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-yellow-50 file:text-yellow-600 hover:file:bg-yellow-100 transition-all">
                @endif
            </div>
            @endforeach
        </div>

        <div class="pt-6 border-t border-gray-50 flex flex-wrap gap-4 mt-8">
            <button type="submit" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-white bg-yellow-500 rounded-2xl hover:bg-yellow-600 shadow-lg shadow-yellow-100 transition-all uppercase tracking-widest">Simpan Perubahan</button>
            <a href="{{ route('prestasi.index-all') }}" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-all text-center uppercase tracking-widest">Batal</a>
        </div>
    </form>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".datepicker-custom", {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "id",
            allowInput: true
        });

        const mahasiswaList = @json($mahasiswa ?? []);
        let selectedUsers = [];

        const preloaded = @json($mahasiswaTerpilih ?? []);
        preloaded.forEach(val => {
            const parts = val.split('|');
            if (parts.length === 3) selectedUsers.push({id: parts[0], name: parts[1], nim: parts[2], isManual: parts[0].startsWith('MANUAL')});
        });

        const searchInput = document.getElementById('student-search-input');
        if (searchInput) {
            const dropdown = document.getElementById('student-dropdown');
            const listItems = document.querySelectorAll('.student-item');
            const selectedContainer = document.getElementById('selected-students-container');
            const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
            const radioButtons = document.getElementsByName('jenis_kepesertaan');
            const placeholder = document.getElementById('placeholder-text');

            function updateSelectedUI() {
                selectedContainer.innerHTML = '';
                hiddenInputsContainer.innerHTML = '';

                if (selectedUsers.length === 0) {
                    selectedContainer.appendChild(placeholder);
                    return;
                }

                selectedUsers.forEach((user, index) => {
                    const isKetua = index === 0;
                    const chip = document.createElement('div');
                    chip.className = `flex items-center justify-between p-3 rounded-xl border ${isKetua ? 'bg-green-50 border-green-200' : (user.isManual ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-200')} shadow-sm animate-in fade-in zoom-in duration-200`;
                    
                    chip.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full ${isKetua ? 'bg-[#006633] text-white' : 'bg-gray-200 text-gray-600'} flex items-center justify-center font-black text-xs">
                                ${user.name.substring(0,1).toUpperCase()}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold ${isKetua ? 'text-green-800' : 'text-gray-800'}">${user.name}</span>
                                <span class="text-[9px] ${user.isManual ? 'text-orange-600' : 'text-gray-500'} font-black uppercase tracking-widest">
                                    ${user.isManual ? 'Input Manual (Luar Sistem)' : 'NIM: ' + user.nim}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            ${isKetua ? '<span class="px-2 py-1 bg-[#006633] text-white text-[9px] font-black uppercase rounded-lg">Ketua / Pelapor</span>' : '<span class="px-2 py-1 bg-gray-200 text-gray-500 text-[9px] font-black uppercase rounded-lg">Anggota</span>'}
                            <button type="button" class="text-gray-400 hover:text-red-500 transition-colors" onclick="removeUser('${user.id}')"><i class="bi bi-x-circle-fill text-lg"></i></button>
                        </div>
                    `;
                    selectedContainer.appendChild(chip);

                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'user_ids[]'; 
                    hiddenInput.value = `${user.id}|${user.name}|${user.nim}`;
                    hiddenInputsContainer.appendChild(hiddenInput);
                });
            }

            window.removeUser = function(id) {
                selectedUsers = selectedUsers.filter(user => String(user.id) !== String(id));
                updateSelectedUI();
            };

            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                let hasMatch = false;

                if (term.length > 0) {
                    dropdown.classList.remove('hidden');
                    listItems.forEach(item => {
                        const name = item.getAttribute('data-name').toLowerCase();
                        const nim = item.getAttribute('data-nim').toLowerCase();
                        if (name.includes(term) || nim.includes(term)) {
                            item.classList.remove('hidden'); hasMatch = true;
                        } else item.classList.add('hidden');
                    });
                    document.getElementById('no-student-found').classList.toggle('hidden', hasMatch);
                } else dropdown.classList.add('hidden');
            });

            listItems.forEach(item => {
                item.addEventListener('click', function() {
                    const isTim = document.querySelector('input[name="jenis_kepesertaan"]:checked').value === 'tim';
                    if (!isTim && selectedUsers.length >= 1) { alert('Mode Individu hanya bisa memilih 1 mahasiswa!'); return; }
                    const id = this.getAttribute('data-id');
                    if (!selectedUsers.find(u => String(u.id) === id)) {
                        selectedUsers.push({ id, name: this.getAttribute('data-name'), nim: this.getAttribute('data-nim'), isManual: false });
                        updateSelectedUI();
                    }
                    searchInput.value = ''; dropdown.classList.add('hidden');
                });
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const isTim = document.querySelector('input[name="jenis_kepesertaan"]:checked').value === 'tim';
                    if (!isTim && selectedUsers.length >= 1) { alert('Mode Individu hanya bisa 1 mahasiswa!'); return; }
                    const val = this.value.trim();
                    if (val) {
                        selectedUsers.push({ id: 'MANUAL_' + Math.random().toString(36).substr(2, 9), name: val, nim: '-', isManual: true });
                        updateSelectedUI();
                        this.value = ''; dropdown.classList.add('hidden');
                    }
                }
            });

            radioButtons.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.value === 'individu' && selectedUsers.length > 1) selectedUsers = [selectedUsers[0]];
                    updateSelectedUI();
                });
            });

            document.addEventListener('click', function(e) {
                if (!document.getElementById('student-search-container').contains(e.target)) dropdown.classList.add('hidden');
            });

            window.validateForm = function() {
                if(selectedUsers.length === 0) { alert('Tolong pilih minimal 1 Mahasiswa (Pelapor) terlebih dahulu!'); return false; }
                return true;
            }
        }
    });
</script>
@endsection
@endsection