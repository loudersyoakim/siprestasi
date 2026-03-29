@extends('layouts.app')

@section('content')

{{-- HEADER HALAMAN & TOMBOL AKSI --}}
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <h3 class="text-2xl font-black text-gray-800 tracking-tight">Manajemen Akun</h3>
    
    <div class="flex items-center gap-3">
        {{-- TOMBOL SYNC DATA LAMA --}}
        @if(Auth::user()->hasPermission('akun.manage_user'))
        <a href="{{ route('manajemen-akun.sync-prodi') }}" onclick="return confirm('Proses ini akan memakan waktu beberapa saat. Yakin ingin sinkronisasi massal Prodi dan Angkatan?')" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-600 hover:text-white transition-colors shadow-sm cursor-pointer">
            <i class="bi bi-arrow-repeat"></i> Sinkronisasi Data
        </a>
        @endif

        {{-- TOMBOL TAMBAH AKUN --}}
        @if(Auth::user()->hasPermission('akun.manage_user'))
        <a href="{{ route('akun.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#004d26] transition-colors shadow-sm cursor-pointer">
            <i class="bi bi-plus-lg"></i> Tambah Akun
        </a>
        @endif
    </div>
</div>

{{-- PROGRESS BAR IMPORT --}}
<div id="import-container" class="hidden mb-6 p-4 bg-blue-50 border border-blue-100 rounded-2xl shadow-sm">
    <div class="flex justify-between items-center mb-2.5">
        <span class="text-xs font-black text-blue-700 uppercase flex items-center gap-2 tracking-wider">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-ping"></div>
            Proses Import Excel: <span id="import-stats" class="ml-1">0 / 0</span>
        </span>
    </div>
    <div class="w-full bg-blue-200/40 h-2.5 rounded-full overflow-hidden">
        <div id="import-bar" class="bg-blue-600 h-full transition-all duration-500" style="width: 0%"></div>
    </div>
</div>

<div class="w-full bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-6 relative">
    
    {{-- FLOATING BULK ACTION BAR --}}
    <div id="bulk-action-bar" class="hidden absolute top-0 left-0 w-full h-[70px] bg-green-50/95 backdrop-blur-sm border-b border-green-200 z-20 items-center justify-between px-6 animate-in slide-in-from-top duration-300">
        <div class="flex items-center gap-3">
            <span class="bg-[#006633] text-white text-xs font-black px-2.5 py-1 rounded-full shadow-sm" id="selected-count">0</span>
            <span class="text-xs font-bold text-[#006633] uppercase tracking-wider">Akun Terpilih</span>
        </div>
        <div class="flex items-center gap-2">
            @if($statusTab === 'pending')
                <button onclick="submitBulk('activate')" class="bg-[#006633] hover:bg-[#004d26] text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-sm">Aktivasi</button>
            @else
                <button onclick="submitBulk('deactivate')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-sm">Nonaktifkan</button>
            @endif
            
            <button onclick="submitBulk('delete')" class="bg-white border border-red-200 text-red-600 hover:bg-red-500 hover:text-white hover:border-red-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-sm">Hapus</button>
            <button onclick="unselectAll()" class="text-gray-500 hover:text-gray-800 text-[10px] font-bold uppercase tracking-wider px-3">Batal</button>
        </div>
    </div>

    {{-- FILTER & SEARCH BAR (Dibuat Lebih Lega) --}}
    <div class="px-6 py-5 border-b border-gray-50 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-gray-50/30">
        
        {{-- Tab Status --}}
        <div class="flex gap-2 bg-white p-1 rounded-xl border border-gray-100 shadow-sm w-full lg:w-max">
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'aktif', 'page' => 1]) }}" class="flex-1 lg:flex-none text-center px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors {{ $statusTab === 'aktif' ? 'bg-[#006633] text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                Akun Aktif
            </a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'pending', 'page' => 1]) }}" class="flex-1 lg:flex-none text-center px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-2 {{ $statusTab === 'pending' ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                Pending 
                @if($pendingCount > 0) 
                    <span class="px-1.5 py-0.5 {{ $statusTab === 'pending' ? 'bg-white text-orange-600' : 'bg-orange-100 text-orange-600' }} rounded-full text-[9px] font-black">{{ $pendingCount }}</span> 
                @endif
            </a>
        </div>

        {{-- Form Pencarian --}}
        <form id="search-form" action="{{ url()->current() }}" method="GET" class="relative w-full lg:w-80">
            <input type="hidden" name="tab" value="{{ $statusTab }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="direction" value="{{ request('direction') }}">
            @if(request('role_id')) <input type="hidden" name="role_id" value="{{ request('role_id') }}"> @endif
            
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, email..." autocomplete="off" class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all shadow-sm font-medium">
        </form>
    </div>

    {{-- AREA TABEL (Tetap Compact/Slim untuk Data) --}}
    <div class="w-full overflow-x-auto custom-scrollbar min-h-[300px]">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 w-10"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-[#006633] focus:ring-[#006633]"></th>
                    <th class="px-2 py-4 w-12 text-[10px] font-black text-gray-400 uppercase tracking-widest">No</th>
                    
                    {{-- KOLOM USER --}}
                    <th class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1.5 w-max hover:text-gray-700 transition-colors">
                            Informasi Pengguna
                            <div class="flex flex-col text-[8px] -space-y-0.5">
                                <i class="bi bi-caret-up-fill {{ request('sort') === 'name' && request('direction') === 'asc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                                <i class="bi bi-caret-down-fill {{ request('sort') === 'name' && request('direction') === 'desc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                            </div>
                        </a>
                    </th>
                    
                    {{-- KOLOM AKSES (Filter Role) --}}
                    <th class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest relative z-10">
                        <button type="button" onclick="toggleRoleFilter(event)" class="flex items-center gap-1.5 hover:text-gray-700 transition-colors focus:outline-none">
                            Hak Akses
                            <i class="bi bi-funnel-fill text-xs {{ request('role_id') ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                        </button>

                        {{-- Dropdown Role --}}
                        <div id="role-dropdown" class="hidden absolute top-full left-4 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl py-2 normal-case font-medium">
                            <a href="{{ request()->fullUrlWithQuery(['role_id' => null, 'page' => 1]) }}" class="block px-4 py-2.5 text-xs transition-colors {{ !request('role_id') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-grid-fill mr-2"></i> Semua Hak Akses
                            </a>
                            <div class="h-px bg-gray-100 my-1"></div>
                            @foreach($roles as $role)
                                <a href="{{ request()->fullUrlWithQuery(['role_id' => $role->id, 'page' => 1]) }}" class="block px-4 py-2.5 text-xs transition-colors {{ request('role_id') == $role->id ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                                    {{ $role->nama_role }}
                                </a>
                            @endforeach
                        </div>
                    </th>
                    
                    <th class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    
                    {{-- KOLOM TANGGAL DAFTAR --}}
                    <th class="px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1.5 w-max hover:text-gray-700 transition-colors">
                            Tgl. Terdaftar
                            <div class="flex flex-col text-[8px] -space-y-0.5">
                                <i class="bi bi-caret-up-fill {{ request('sort') === 'created_at' && request('direction') === 'asc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                                <i class="bi bi-caret-down-fill {{ request('sort') === 'created_at' && request('direction') === 'desc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                            </div>
                        </a>
                    </th>

                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-2.5"><input type="checkbox" class="row-checkbox rounded border-gray-300 text-[#006633] cursor-pointer" value="{{ $user->id }}"></td>
                    <td class="px-2 py-2.5 text-xs font-bold text-gray-400">{{ $users->firstItem() + $index }}</td>
                    
                    {{-- Kolom Data User --}}
                    <td class="px-4 py-2.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-50 border border-green-100 text-[#006633] flex items-center justify-center font-black text-xs shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 mt-0.5 tracking-wider uppercase">{{ $user->nim_nip }}</p>
                            </div>
                        </div>
                    </td>
                    
                    {{-- Kolom Hak Akses --}}
                    <td class="px-4 py-2.5">
                        <span class="inline-flex items-center px-2 py-1 bg-blue-50 border border-blue-100 text-blue-600 text-[9px] font-black uppercase tracking-wider rounded-md">
                            {{ $user->role->nama_role }}
                        </span>
                    </td>
                    
                    {{-- Kolom Status --}}
                    <td class="px-4 py-2.5 text-center">
                        <span class="inline-flex items-center px-2 py-1 border text-[9px] font-black uppercase tracking-wider rounded-md {{ $user->is_active ? 'bg-green-50 border-green-200 text-green-600' : 'bg-orange-50 border-orange-200 text-orange-600 animate-pulse' }}">
                            {{ $user->is_active ? 'Aktif' : 'Pending' }}
                        </span>
                    </td>
                    
                    {{-- Kolom Tanggal --}}
                    <td class="px-4 py-2.5 text-xs font-medium text-gray-600">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </td>

                    {{-- Kolom Aksi --}}
                    <td class="px-6 py-2.5">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('akun.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-yellow-500 hover:border-yellow-400 hover:bg-yellow-50 flex items-center justify-center transition-all shadow-sm tooltip" title="Edit Akun"><i class="bi bi-pencil-square text-sm"></i></a>
                            
                            <form action="{{ route('akun.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus permanen akun ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-red-500 hover:border-red-500 hover:bg-red-50 flex items-center justify-center transition-all shadow-sm tooltip" title="Hapus Akun"><i class="bi bi-trash3-fill text-sm"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="100%" class="px-6 py-16 text-center text-gray-400">
                        <i class="bi bi-people text-4xl mb-3 block opacity-50"></i>
                        <p class="text-sm font-medium">Belum ada data akun yang ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination Bar --}}
    @if($users->hasPages())
    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Form Tersembunyi Bulk --}}
<form id="form-bulk" method="POST" action="{{ route('akun.bulk') }}" class="hidden">
    @csrf
    <input type="hidden" name="bulk_action" id="bulk-type">
    <div id="bulk-ids"></div>
</form>

<script>
    // 1. LOGIKA BULK ACTION
    const selectAll = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkBar = document.getElementById('bulk-action-bar');
    const selectedText = document.getElementById('selected-count');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        if(checked > 0) {
            bulkBar.classList.remove('hidden');
            bulkBar.classList.add('flex');
            selectedText.innerText = checked;
        } else {
            bulkBar.classList.add('hidden');
            bulkBar.classList.remove('flex');
        }
    }

    selectAll.addEventListener('change', (e) => {
        rowCheckboxes.forEach(cb => cb.checked = e.target.checked);
        updateBulkBar();
    });

    rowCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkBar));

    function unselectAll() {
        selectAll.checked = false;
        rowCheckboxes.forEach(cb => cb.checked = false);
        updateBulkBar();
    }

    function submitBulk(type) {
        if(!confirm('Lanjutkan aksi ini pada data terpilih?')) return;
        const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        const container = document.getElementById('bulk-ids');
        document.getElementById('bulk-type').value = type;
        container.innerHTML = '';
        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'ids[]'; input.value = id;
            container.appendChild(input);
        });
        document.getElementById('form-bulk').submit();
    }

    // 2. LOGIKA IMPORT PROGRESS
    function checkProgress() {
        fetch('{{ route("akun.import-status") }}')
            .then(res => res.json())
            .then(data => {
                if(data && data.total > 0) {
                    document.getElementById('import-container').classList.remove('hidden');
                    let p = (data.current / data.total) * 100;
                    document.getElementById('import-bar').style.width = p + '%';
                    document.getElementById('import-stats').innerText = `${data.current} / ${data.total}`;
                    if(data.current >= data.total) {
                        fetch('{{ route("akun.import-status.clear") }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{csrf_token()}}'}})
                        .then(() => setTimeout(() => location.reload(), 1000));
                    } else { setTimeout(checkProgress, 2000); }
                }
            });
    }
    document.addEventListener('DOMContentLoaded', checkProgress);

    // 3. LIVE SEARCH
    const sInput = document.getElementById('search-input');
    const sForm = document.getElementById('search-form');
    let t = null;
    sInput.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(() => sForm.submit(), 500);
    });
    
    // Taruh cursor kembali di akhir teks biar enak ketiknya
    const val = sInput.value; sInput.value = ''; sInput.focus(); sInput.value = val;

    function toggleRoleFilter(e) {
        e.stopPropagation(); 
        document.getElementById('role-dropdown').classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('role-dropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
@endsection