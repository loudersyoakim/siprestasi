@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('prestasi.index-all') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Tambah Prestasi Baru</h3>
    </div>
</div>

{{-- STEP 1: PILIH KATEGORI --}}
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
    <form action="{{ route('prestasi.create') }}" method="GET" class="max-w-2xl">
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">1. Pilih Kategori Kegiatan</label>
        <div class="flex gap-3">
            <select name="form_id" required class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-gray-50 cursor-pointer font-bold text-gray-700">
                <option value="" hidden>Pilih kategori prestasi yang sesuai...</option>
                @foreach($forms as $f)
                <option value="{{ $f->id }}" {{ request('form_id') == $f->id ? 'selected' : '' }}>{{ $f->nama_form }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-[#006633] text-white font-bold text-sm rounded-2xl hover:bg-[#004d26] transition-colors shadow-md shadow-green-100">Lanjutkan</button>
        </div>
    </form>
</div>

{{-- STEP 2: FORM DINAMIS --}}
@if($selectedForm)
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    <div class="p-6 md:p-8 border-b border-gray-50 bg-green-50/30">
        <h4 class="text-lg font-black text-gray-800">{{ $selectedForm->nama_form }}</h4>
        <p class="text-xs text-gray-500 mt-1">{{ $selectedForm->deskripsi }}</p>
    </div>

    <form action="{{ route('prestasi.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6" id="form-prestasi" onsubmit="return validateForm()">
        @csrf
        <input type="hidden" name="form_prestasi_id" value="{{ $selectedForm->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- BAGIAN KEPESERTAAN & PELAPOR (STATIS) --}}
            <div class="md:col-span-2 p-6 bg-blue-50/50 border border-blue-100 rounded-3xl space-y-6">
                <div>
                    <label class="text-[10px] font-black text-blue-800 uppercase tracking-widest ml-1">Jenis Kepesertaan <span class="text-red-500">*</span></label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex-1">
                            <input type="radio" name="jenis_kepesertaan" value="individu" class="hidden peer" checked>
                            <div class="flex items-center justify-center p-3 bg-white border border-gray-200 rounded-2xl cursor-pointer peer-checked:border-[#006633] peer-checked:bg-green-50 peer-checked:text-[#006633] hover:bg-gray-50 transition-all">
                                <i class="bi bi-person-fill mr-2"></i><span class="text-sm font-bold">Individu</span>
                            </div>
                        </label>
                        <label class="flex-1">
                            <input type="radio" name="jenis_kepesertaan" value="tim" class="hidden peer">
                            <div class="flex items-center justify-center p-3 bg-white border border-gray-200 rounded-2xl cursor-pointer peer-checked:border-[#006633] peer-checked:bg-green-50 peer-checked:text-[#006633] hover:bg-gray-50 transition-all">
                                <i class="bi bi-people-fill mr-2"></i><span class="text-sm font-bold">Tim / Kelompok</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="relative" id="student-search-container">
                    <label class="text-[10px] font-black text-blue-800 uppercase tracking-widest ml-1">Cari Mahasiswa (Pelapor & Anggota)</label>
                    <p class="text-xs text-gray-500 mt-1 ml-1 mb-2">Orang pertama yang dipilih otomatis menjadi Ketua/Pelapor.</p>
                    
                    <div class="relative">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="student-search-input" placeholder="Ketik Nama atau NIM... (Tekan Enter jika nama tidak ada di sistem)" autocomplete="off" class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all shadow-sm">
                    </div>

                    <div id="student-dropdown" class="absolute z-[70] w-full mt-2 bg-white rounded-2xl border border-gray-100 shadow-2xl overflow-hidden hidden">
                        <div class="max-h-60 overflow-y-auto custom-scrollbar" id="student-list">
                            @foreach($mahasiswa as $mhs)
                            <div class="student-item px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-50 transition-colors" data-id="{{ $mhs->id }}" data-name="{{ $mhs->name }}" data-nim="{{ $mhs->nim_nip }}">
                                <div class="font-bold text-gray-800 text-sm">{{ $mhs->name }}</div>
                                <div class="text-[10px] text-blue-600 font-black uppercase tracking-widest mt-0.5">NIM: {{ $mhs->nim_nip }}</div>
                            </div>
                            @endforeach
                            <div id="no-student-found" class="px-4 py-6 text-center text-gray-400 hidden">
                                <span class="text-xs font-bold text-gray-500 block mb-1">Nama tidak ditemukan di sistem</span>
                                <span class="text-[10px]">Tekan <kbd class="bg-gray-100 px-1 rounded text-gray-800">Enter</kbd> untuk menambahkan manual.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-blue-800 uppercase tracking-widest ml-1">Daftar Mahasiswa Terpilih <span class="text-red-500">*</span></label>
                    <div id="selected-students-container" class="flex flex-col gap-2 mt-2 min-h-[60px] p-4 bg-white border border-dashed border-blue-200 rounded-2xl shadow-sm">
                        <div id="placeholder-text" class="text-gray-400 text-xs italic m-auto">Belum ada mahasiswa yang dipilih...</div>
                    </div>
                    <div id="hidden-inputs-container"></div>
                </div>
            </div>

            <div class="md:col-span-2 border-b border-gray-100 my-2"></div>

            {{-- BAGIAN FORM DINAMIS --}}
            @foreach($selectedForm->fields as $field)
                @if($field->tipe === 'anggota_kelompok') @continue @endif

                <div class="{{ in_array($field->tipe, ['textarea']) ? 'md:col-span-2' : '' }}">
                    <label class="flex items-center gap-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2">
                        {{ $field->label }} @if($field->is_required) <span class="text-red-500">*</span> @endif
                    </label>

                    @php $inputName = "field_" . $field->id; @endphp

                    @if($field->tipe === 'text' || $field->tipe === 'number' || $field->tipe === 'date')
                        <input type="{{ $field->tipe }}" name="{{ $inputName }}" {{ $field->is_required ? 'required' : '' }} class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all font-medium">
                    
                    @elseif($field->tipe === 'textarea')
                        <textarea name="{{ $inputName }}" rows="3" {{ $field->is_required ? 'required' : '' }} class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] focus:ring-1 focus:ring-[#006633] outline-none transition-all font-medium"></textarea>
                    
                    @elseif($field->tipe === 'select')
                        <select name="{{ $inputName }}" {{ $field->is_required ? 'required' : '' }} class="condition-trigger w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white cursor-pointer font-medium">
                            <option value="" hidden>Pilih...</option>
                            @if(is_array($field->opsi))
                                @foreach($field->opsi as $opsi) <option value="{{ trim($opsi) }}">{{ trim($opsi) }}</option> @endforeach
                            @endif
                        </select>

                    @elseif($field->tipe === 'radio')
                        <div class="flex flex-wrap gap-4 mt-2">
                            @if(is_array($field->opsi))
                                @foreach($field->opsi as $idx => $opsi)
                                <label class="flex items-center gap-2 cursor-pointer bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 hover:border-[#006633] transition-colors">
                                    <input type="radio" name="{{ $inputName }}" value="{{ trim($opsi) }}" {{ $field->is_required && $idx==0 ? 'required' : '' }} class="condition-trigger w-4 h-4 text-[#006633] focus:ring-[#006633]">
                                    <span class="text-sm text-gray-700 font-bold">{{ trim($opsi) }}</span>
                                </label>
                                @endforeach
                            @endif
                        </div>

                    @elseif($field->tipe === 'checkbox')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                            @if(is_array($field->opsi))
                                @foreach($field->opsi as $opsi)
                                <label class="flex items-start gap-2 cursor-pointer bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 hover:border-[#006633] transition-colors">
                                    <input type="checkbox" name="{{ $inputName }}[]" value="{{ trim($opsi) }}" class="mt-0.5 w-4 h-4 text-[#006633] rounded focus:ring-[#006633]">
                                    <span class="text-xs text-gray-700 font-bold leading-tight">{{ trim($opsi) }}</span>
                                </label>
                                @endforeach
                            @endif
                        </div>

                    @elseif($field->tipe === 'file')
                        <input type="file" name="{{ $inputName }}" {{ $field->is_required ? 'required' : '' }} class="w-full px-4 py-2 border border-gray-200 rounded-2xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-green-50 file:text-[#006633] hover:file:bg-green-100 transition-all">
                    @endif

                    @if($field->keterangan)
                        <p class="text-[10px] text-gray-400 mt-1.5 ml-1 italic">{{ $field->keterangan }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="pt-6 border-t border-gray-50 flex flex-wrap gap-4 mt-8">
            <button type="submit" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-white bg-[#006633] rounded-2xl hover:bg-[#004d26] shadow-lg shadow-green-100 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                <span>Simpan Prestasi</span>
            </button>
            <a href="{{ route('prestasi.index-all') }}" class="flex-1 sm:flex-none px-10 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-all text-center uppercase tracking-widest">Batal</a>
        </div>
    </form>
</div>
@endif

<script>
    const mahasiswaList = @json($mahasiswa ?? []);
    let selectedUsers = [];

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('student-search-input');
        const dropdown = document.getElementById('student-dropdown');
        const listItems = document.querySelectorAll('.student-item');
        const selectedContainer = document.getElementById('selected-students-container');
        const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
        const placeholder = document.getElementById('placeholder-text');
        const radioButtons = document.getElementsByName('jenis_kepesertaan');

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
                        <button type="button" class="text-gray-400 hover:text-red-500 transition-colors" onclick="removeUser('${user.id}')">
                            <i class="bi bi-x-circle-fill text-lg"></i>
                        </button>
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

        listItems.forEach(item => {
            item.addEventListener('click', function() {
                const isTim = document.querySelector('input[name="jenis_kepesertaan"]:checked').value === 'tim';
                if (!isTim && selectedUsers.length >= 1) {
                    alert('Mode Individu hanya bisa memilih 1 mahasiswa!');
                    return;
                }

                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const nim = this.getAttribute('data-nim');

                if (!selectedUsers.find(u => String(u.id) === id)) {
                    selectedUsers.push({ id, name, nim, isManual: false });
                    updateSelectedUI();
                }

                searchInput.value = '';
                dropdown.classList.add('hidden');
            });
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const isTim = document.querySelector('input[name="jenis_kepesertaan"]:checked').value === 'tim';
                if (!isTim && selectedUsers.length >= 1) {
                    alert('Mode Individu hanya bisa 1 mahasiswa!');
                    return;
                }

                const val = this.value.trim();
                if (val) {
                    const manualId = 'MANUAL_' + Math.random().toString(36).substr(2, 9);
                    selectedUsers.push({ id: manualId, name: val, nim: '-', isManual: true });
                    updateSelectedUI();
                    this.value = '';
                    dropdown.classList.add('hidden');
                }
            }
        });

        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.value === 'individu' && selectedUsers.length > 1) {
                    selectedUsers = [selectedUsers[0]];
                }
                updateSelectedUI();
            });
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('student-search-container').contains(e.target)) dropdown.classList.add('hidden');
        });

        window.validateForm = function() {
            if(selectedUsers.length === 0) {
                alert('Tolong pilih minimal 1 Mahasiswa (Pelapor) terlebih dahulu!');
                return false;
            }
            return true;
        }
    });
</script>
@endsection