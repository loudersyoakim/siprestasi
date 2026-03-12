@extends('layouts.app')

@section('content')
<div class="mb-8">
    <a href="{{ route('mahasiswa.prestasi') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2 text-balance">Laporkan Prestasi Baru</h3>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <form action="{{ route('mahasiswa.prestasi.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- 1. Jenis Kepesertaan --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kepesertaan</label>
                <select name="jenis_kepesertaan" id="jenis_kepesertaan" class="w-full mt-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all font-bold cursor-pointer">
                    <option value="individu">Individu (Mandiri)</option>
                    <option value="tim">Tim / Kelompok</option>
                </select>
            </div>

            {{-- 2. Nama Pelapor (Otomatis & Terkunci) --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ketua / Pelapor</label>
                <div class="flex items-center gap-3 mt-1 px-4 py-3 bg-green-50 border border-green-100 rounded-2xl">
                    <div class="w-8 h-8 rounded-full bg-[#006633] text-white flex items-center justify-center text-xs font-black">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-[#006633] font-black uppercase">NIM: {{ Auth::user()->nim_nip }}</div>
                    </div>
                    <input type="hidden" name="user_ids[]" value="{{ Auth::id() }}">
                </div>
            </div>

            {{-- 3. Anggota Tim --}}
            <div id="anggota-tim-section" class="md:col-span-2 hidden animate-in fade-in duration-300">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cari Anggota Tim Lainnya</label>
                <div class="relative mt-1">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="member-search-input" placeholder="Ketik Nama atau NIM anggota..." autocomplete="off" 
                           class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all">
                    
                    <div id="member-dropdown" class="absolute z-50 w-full mt-2 bg-white rounded-2xl border border-gray-100 shadow-2xl hidden max-h-48 overflow-y-auto">
                        @foreach($allMahasiswa as $mhs)
                            @if($mhs->id !== Auth::id())
                            <div class="member-item px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-50 last:border-0"
                                 data-id="{{ $mhs->id }}" data-name="{{ $mhs->name }}" data-nim="{{ $mhs->nim_nip }}">
                                <span class="block text-sm font-bold text-gray-800">{{ $mhs->name }}</span>
                                <span class="text-[10px] text-gray-400 uppercase font-black">NIM: {{ $mhs->nim_nip }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div id="selected-members-container" class="flex flex-wrap gap-2 mt-3"></div>
                <div id="hidden-inputs-members"></div>
            </div>

            {{-- 4. Nama Prestasi --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Kompetisi / Capaian</label>
                <input type="text" name="nama_prestasi" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all" placeholder="Contoh: Lomba Debat Nasional">
            </div>

            {{-- 5. Data Master --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tingkat</label>
                <select name="tingkat_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none cursor-pointer">
                    <option value="">Pilih Tingkat...</option>
                    @foreach($tingkat as $t)
                        <option value="{{ $t->id }}">{{ $t->nama_tingkat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none">
                    @foreach($tahunAkademik as $ta)
                        <option value="{{ $ta->id }}">{{ $ta->tahun }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 6. Kategori & Jenis (With "Lainnya" Logic) --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Prestasi</label>
                <select name="kategori_id" id="kategori_select" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all cursor-pointer">
                    <option value="" hidden>Pilih Kategori...</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                    <option value="lainnya" class="font-bold text-[#006633] bg-green-50">+ Tambah Kategori Lainnya...</option>
                </select>
                <input type="text" name="kategori_baru" id="kategori_baru" placeholder="Ketik kategori baru..." class="hidden w-full mt-2 px-4 py-3 border border-dashed border-[#006633] bg-green-50/30 rounded-2xl text-sm outline-none">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Jenis Kegiatan</label>
                <select name="jenis_id" id="jenis_select" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all cursor-pointer">
                    <option value="" hidden>Pilih Jenis...</option>
                    @foreach($jenis as $j)
                    <option value="{{ $j->id }}">{{ $j->nama_jenis }}</option>
                    @endforeach
                    <option value="lainnya" class="font-bold text-[#006633] bg-green-50">+ Tambah Jenis Lainnya...</option>
                </select>
                <input type="text" name="jenis_baru" id="jenis_baru" placeholder="Ketik jenis baru..." class="hidden w-full mt-2 px-4 py-3 border border-dashed border-[#006633] bg-green-50/30 rounded-2xl text-sm outline-none">
            </div>

            {{-- 7. Tanggal & Sertifikat --}}
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Sertifikat</label>
                <input type="date" name="tanggal_peroleh" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">File Sertifikat (Maks 2MB)</label>
                <input type="file" name="sertifikat" accept=".pdf,.jpg,.png" required 
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-2xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-green-50 file:text-[#006633] hover:file:bg-green-100 transition-all">
            </div>

            {{-- 8. Deskripsi (Ini dia yang ketinggalan!) --}}
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Deskripsi / Keterangan Singkat</label>
                <textarea name="deskripsi" rows="4" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none transition-all" placeholder="Jelaskan detail prestasi Anda (Contoh: Menjadi juara dari 100 peserta nasional)..."></textarea>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50">
            <button type="submit" class="w-full sm:w-auto px-12 py-4 bg-[#006633] text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-lg shadow-green-100 hover:bg-[#004d26] transition-all">
                Kirim Laporan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Logic Anggota Tim
    const jenisKepesertaan = document.getElementById('jenis_kepesertaan');
    const anggotaSection = document.getElementById('anggota-tim-section');
    const searchInput = document.getElementById('member-search-input');
    const dropdown = document.getElementById('member-dropdown');
    const selectedContainer = document.getElementById('selected-members-container');
    const hiddenContainer = document.getElementById('hidden-inputs-members');
    const items = document.querySelectorAll('.member-item');
    let selectedMembers = [];

    jenisKepesertaan.addEventListener('change', function() {
        if(this.value === 'tim') {
            anggotaSection.classList.remove('hidden');
        } else {
            anggotaSection.classList.add('hidden');
            selectedMembers = [];
            renderMembers();
        }
    });

    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        if(term.length > 0) {
            dropdown.classList.remove('hidden');
            items.forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(term) ? 'block' : 'none';
            });
        } else { dropdown.classList.add('hidden'); }
    });

    items.forEach(item => {
        item.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            if(!selectedMembers.find(m => m.id === id)) {
                selectedMembers.push({id, name});
                renderMembers();
            }
            searchInput.value = '';
            dropdown.classList.add('hidden');
        });
    });

    function renderMembers() {
        selectedContainer.innerHTML = '';
        hiddenContainer.innerHTML = '';
        selectedMembers.forEach(m => {
            selectedContainer.innerHTML += `
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl shadow-sm">
                    <span class="text-xs font-bold text-gray-700">${m.name}</span>
                    <button type="button" onclick="removeMember('${m.id}')" class="text-red-400 hover:text-red-600"><i class="bi bi-x-circle-fill"></i></button>
                </div>`;
            hiddenContainer.innerHTML += `<input type="hidden" name="user_ids[]" value="${m.id}">`;
        });
    }

    window.removeMember = (id) => {
        selectedMembers = selectedMembers.filter(m => m.id !== id);
        renderMembers();
    };

    // 2. Logic "Lainnya" untuk Kategori & Jenis
    ['kategori', 'jenis'].forEach(type => {
        const select = document.getElementById(`${type}_select`);
        const inputBaru = document.getElementById(`${type}_baru`);
        select.addEventListener('change', function() {
            if(this.value === 'lainnya') {
                inputBaru.classList.remove('hidden');
                inputBaru.setAttribute('required', 'required');
            } else {
                inputBaru.classList.add('hidden');
                inputBaru.removeAttribute('required');
            }
        });
    });
});
</script>
@endsection