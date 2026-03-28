
@extends('layouts.app')

@section('content')

<div  class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
     <h3 class="text-xl font-black text-gray-800 tracking-tight">Manajemen Akun</h3>
    @if(Auth::user()->hasPermission('akun.manage_user'))
    <a href="{{ route('akun.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#004d26] transition-colors shadow-sm cursor-pointer">
        <i class="bi bi-plus-lg"></i> Tambah Akun
    </a>
    @endif
</div>

{{-- PROGRESS BAR IMPORT --}}
<div id="import-container" class="hidden mb-4 p-3 bg-blue-50 border border-blue-100 rounded-xl">
    <div class="flex justify-between items-center mb-1.5">
        <span class="text-[10px] font-black text-blue-600 uppercase flex items-center gap-2">
            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-ping"></div>
            Importing: <span id="import-stats">0 / 0</span>
        </span>
    </div>
    <div class="w-full bg-blue-200/30 h-1.5 rounded-full overflow-hidden">
        <div id="import-bar" class="bg-blue-600 h-full transition-all duration-500" style="width: 0%"></div>
    </div>
</div>

<div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6 relative">
    
    {{-- FLOATING BULK ACTION BAR (Direvisi: Lebih Kalem & Elegan) --}}
    <div id="bulk-action-bar" class="hidden absolute top-0 left-0 w-full h-[53px] bg-green-50/95 backdrop-blur-sm border-b border-green-200 z-20 items-center justify-between px-4 animate-in slide-in-from-top duration-300">
        <div class="flex items-center gap-3">
            <span class="bg-[#006633] text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm" id="selected-count">0</span>
            <span class="text-xs font-bold text-[#006633] uppercase tracking-wider">Akun Terpilih</span>
        </div>
        <div class="flex gap-2">
            @if($statusTab === 'pending')
                <button onclick="submitBulk('activate')" class="bg-[#006633] hover:bg-[#004d26] text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm">Aktivasi</button>
            @else
                <button onclick="submitBulk('deactivate')" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm">Nonaktifkan</button>
            @endif
            
            <button onclick="submitBulk('delete')" class="bg-white border border-red-200 text-red-600 hover:bg-red-500 hover:text-white hover:border-red-500 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm">Hapus</button>
            <button onclick="unselectAll()" class="text-gray-500 hover:text-gray-800 text-[10px] font-bold uppercase px-2">Batal</button>
        </div>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="px-4 py-3 border-b border-gray-50 flex flex-col lg:flex-row justify-between items-center gap-3 bg-gray-50/30">
        <div class="flex gap-1.5">
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'aktif', 'page' => 1]) }}" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusTab === 'aktif' ? 'bg-[#006633] text-white' : 'bg-white text-gray-400 border border-gray-100' }}">Aktif</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'pending', 'page' => 1]) }}" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusTab === 'pending' ? 'bg-orange-500 text-white' : 'bg-white text-gray-400 border border-gray-100' }}">
                Pending @if($pendingCount > 0) <span class="ml-1 px-1 py-0.5 bg-white text-orange-600 rounded-full text-[9px]">{{ $pendingCount }}</span> @endif
            </a>
        </div>
        <form id="search-form" action="{{ url()->current() }}" method="GET" class="relative w-full lg:w-max">
            <input type="hidden" name="tab" value="{{ $statusTab }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="direction" value="{{ request('direction') }}">
            
            {{-- TAMBAHKAN INI: Agar filter role tidak hilang saat mencari nama --}}
            @if(request('role_id'))
                <input type="hidden" name="role_id" value="{{ request('role_id') }}">
            @endif
            
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari data..." autocomplete="off" class="w-full lg:w-64 pl-8 pr-4 py-1.5 border border-gray-200 rounded-lg text-xs outline-none focus:border-[#006633] transition-all">
        </form>
    </div>

    <div class="w-full overflow-x-auto min-h-[300px] pb-32">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 w-10"><input type="checkbox" id="select-all" class="rounded border-gray-300 text-[#006633] focus:ring-[#006633]"></th>
                    <th class="px-2 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">No</th>
                    
                    {{-- KOLOM USER (Bisa di-sort) --}}
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1.5 w-max hover:text-gray-700 transition-colors">
                            User
                            <div class="flex flex-col text-[8px] -space-y-0.5">
                                <i class="bi bi-caret-up-fill {{ request('sort') === 'name' && request('direction') === 'asc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                                <i class="bi bi-caret-down-fill {{ request('sort') === 'name' && request('direction') === 'desc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                            </div>
                        </a>
                    </th>
                    
                    {{-- KOLOM AKSES (Filter by Role) --}}
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest relative">
                        <button type="button" onclick="toggleRoleFilter(event)" class="flex items-center gap-1.5 hover:text-gray-700 transition-colors focus:outline-none">
                            Akses
                            <i class="bi bi-person-fill text-xs {{ request('role_id') ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                        </button>

                        {{-- Dropdown Menu Role --}}
                        <div id="role-dropdown" class="hidden absolute top-full left-4 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 py-1.5 normal-case font-medium">
                            <a href="{{ request()->fullUrlWithQuery(['role_id' => null, 'page' => 1]) }}" class="block px-4 py-2 text-[11px] transition-colors {{ !request('role_id') ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-people-fill mr-2"></i> Semua Akses
                            </a>
                            <div class="h-px bg-gray-100 my-1"></div>
                            
                            {{-- Looping data role dari Controller --}}
                            @foreach($roles as $role)
                                <a href="{{ request()->fullUrlWithQuery(['role_id' => $role->id, 'page' => 1]) }}" class="block px-4 py-2 text-[11px] transition-colors {{ request('role_id') == $role->id ? 'bg-[#006633]/10 text-[#006633] font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                                    {{ $role->nama_role }}
                                </a>
                            @endforeach
                        </div>
                    </th>
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    
                    {{-- KOLOM TANGGAL DAFTAR (Bisa di-sort) --}}
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1.5 w-max hover:text-gray-700 transition-colors">
                            Tanggal Daftar
                            <div class="flex flex-col text-[8px] -space-y-0.5">
                                <i class="bi bi-caret-up-fill {{ request('sort') === 'created_at' && request('direction') === 'asc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                                <i class="bi bi-caret-down-fill {{ request('sort') === 'created_at' && request('direction') === 'desc' ? 'text-[#006633]' : 'text-gray-300' }}"></i>
                            </div>
                        </a>
                    </th>

                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50/30 transition-colors group">
                    <td class="px-4 py-2"><input type="checkbox" class="row-checkbox rounded border-gray-300 text-[#006633]" value="{{ $user->id }}"></td>
                    <td class="px-2 py-2 text-[11px] font-bold text-gray-400">{{ $users->firstItem() + $index }}</td>
                    
                    {{-- Data User --}}
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-green-50 text-[#006633] flex items-center justify-center font-bold text-[10px]">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-800 truncate leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $user->nim_nip }}</p>
                            </div>
                        </div>
                    </td>
                    
                    {{-- Akses & Status --}}
                    <td class="px-4 py-2"><span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black uppercase rounded">{{ $user->role->nama_role }}</span></td>
                    <td class="px-4 py-2 text-center">
                        <span class="text-[9px] font-bold {{ $user->is_active ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }} px-1.5 py-0.5 rounded uppercase">{{ $user->is_active ? 'Aktif' : 'Pending' }}</span>
                    </td>
                    
                    {{-- Tanggal Daftar --}}
                    <td class="px-4 py-2 text-[11px] font-medium text-gray-500">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                    </td>

                    {{-- Tombol Aksi (Direvisi: Selalu Tampil) --}}
                    <td class="px-4 py-2">
                        <div class="flex justify-center gap-1.5">
                            <a href="{{ route('akun.edit', $user->id) }}" class="w-6 h-6 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white flex items-center justify-center transition-all"><i class="bi bi-pencil-square text-xs"></i></a>
                            <form action="{{ route('akun.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                @csrf @method('DELETE')
                                <button class="w-6 h-6 rounded-md bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all"><i class="bi bi-trash-fill text-xs"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="p-8 text-center text-xs text-gray-400 italic">Data tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 bg-gray-50/30 border-t border-gray-50">{{ $users->links() }}</div>
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

    function toggleRoleFilter(e) {
    e.stopPropagation(); // Biar dropdown nggak langsung ketutup
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