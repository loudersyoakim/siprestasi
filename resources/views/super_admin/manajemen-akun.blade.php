@extends('layouts.app')

@section('content')

@php
    $routePrefix = Auth::user()->role === 'super_admin' ? 'super_admin' : 'admin';
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>

        <h3 class="text-xl font-black text-gray-800 tracking-tight">Manajemen Akun</h3>
    </div>

    <a href="{{ route($routePrefix . '.manajemen-akun.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-lg text-sm font-bold hover:bg-[#004d26] transition-colors shadow-sm">
        <i class="bi bi-person-plus-fill"></i>
        <span>Tambah Akun</span>
    </a>
</div>

{{-- ================= ALERT NOTIFIKASI ================= --}}
@if(session('success'))
<div id="success-alert" class="mb-6 flex items-center justify-between p-4 mb-4 text-sm font-bold text-green-800 rounded-xl bg-green-50 border border-green-200 transition-opacity duration-500">
    <div class="flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button onclick="document.getElementById('success-alert').style.display='none'" class="text-green-600 hover:text-green-900">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif
{{-- ==================================================== --}}

{{-- ================= TAB NAVIGASI ================= --}}
<div class="flex items-center gap-6 mb-4 border-b border-gray-200 px-2">
    <a href="{{ route($routePrefix . '.manajemen-akun', ['tab' => 'aktif']) }}" 
       class="pb-3 text-sm font-bold transition-all relative {{ (!isset($statusTab) || $statusTab === 'aktif') ? 'text-[#006633] border-b-2 border-[#006633]' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="bi bi-person-check-fill mr-1.5"></i> Akun Terdaftar
    </a>

    <a href="{{ route($routePrefix . '.manajemen-akun', ['tab' => 'pending']) }}" 
       class="pb-3 text-sm font-bold transition-all relative flex items-center gap-2 {{ (isset($statusTab) && $statusTab === 'pending') ? 'text-[#006633] border-b-2 border-[#006633]' : 'text-gray-400 hover:text-gray-600' }}">
        <span><i class="bi bi-person-exclamation mr-1.5"></i> Menunggu Aktivasi</span>
        
        @if(isset($pendingCount) && $pendingCount > 0)
            <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $pendingCount }}</span>
        @endif
    </a>
</div>
{{-- ================================================= --}}

<div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col mb-8 overflow-hidden relative">

    {{-- ================= BULK ACTION BAR (Muncul jika checkbox dicentang) ================= --}}
    <div id="bulk-action-bar" class="hidden absolute top-0 left-0 w-full h-16 bg-[#006633]/5 backdrop-blur-md border-b border-[#006633]/20 z-10 items-center justify-between px-6 transition-all duration-300">
        <div class="flex items-center gap-3">
            <span class="flex items-center justify-center w-6 h-6 rounded bg-[#006633] text-white text-xs font-bold" id="selected-count">0</span>
            <span class="text-sm font-bold text-[#006633]">Akun Dipilih</span>
        </div>
        <div class="flex items-center gap-2">
            @if(isset($statusTab) && $statusTab === 'pending')
            <button onclick="submitBulkAction('activate')" class="bg-[#006633] hover:bg-[#004d26] text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1.5">
                <i class="bi bi-check-lg"></i> Aktivasi Terpilih
            </button>
            @endif
            <button onclick="submitBulkAction('delete')" class="bg-red-50 hover:bg-red-500 text-red-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors shadow-sm border border-red-100 flex items-center gap-1.5">
                <i class="bi bi-trash3"></i> Hapus Terpilih
            </button>
        </div>
    </div>
    {{-- ==================================================================================== --}}

    {{-- HEADER FILTER & PENCARIAN --}}
    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Daftar Pengguna</h4>

        <form id="search-form" action="{{ route($routePrefix . '.manajemen-akun') }}" method="GET" class="relative w-full sm:w-max">
            @foreach(request()->except('search', 'page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari Nama, NIM/NIP..." autocomplete="off"
                class="w-full sm:w-80 pl-9 pr-10 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all">

            @if(request('search'))
            <a href="{{ route($routePrefix . '.manajemen-akun', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors">
                <i class="bi bi-x-circle-fill"></i>
            </a>
            @endif
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div class="w-full overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    {{-- Checkbox Master (Dibuat sejajar di tengah) --}}
                    <th class="w-12 px-4 py-3 align-middle">
                        <div class="flex items-center justify-center mt-1">
                            <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 text-[#006633] focus:ring-[#006633] cursor-pointer">
                        </div>
                    </th>
                    
                    {{-- Nomor --}}
                    <th class="px-2 py-3 whitespace-nowrap align-middle">
                        <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">No</span>
                    </th>

                    <th class="px-4 py-3 whitespace-nowrap align-middle">
                        <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Nama & Identitas</span>
                    </th>

                    <th class="px-4 py-3 whitespace-nowrap align-middle">
                        <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider block mt-1">Kontak Email</span>
                    </th>

                    <th class="px-4 py-3 whitespace-nowrap align-middle">
                        <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Hak Akses</span>
                    </th>

                    <th class="px-4 py-3 whitespace-nowrap align-middle">
                        <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Status</span>
                    </th>

                    <th class="px-4 py-3 text-center whitespace-nowrap align-middle">
                        <span class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Aksi</span>
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 text-sm bg-white">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50 transition-colors group">
                    {{-- Checkbox Satuan (Dibuat sejajar di tengah) --}}
                    <td class="w-12 px-4 py-3 align-middle">
                        <div class="flex items-center justify-center">
                            <input type="checkbox" class="row-checkbox w-4 h-4 rounded border-gray-300 text-[#006633] focus:ring-[#006633] cursor-pointer" value="{{ $user->id }}">
                        </div>
                    </td>
                    
                    {{-- Nomor --}}
                    <td class="px-2 py-3 text-gray-400 font-medium align-middle text-xs">{{ $users->firstItem() + $index }}</td>

                    <td class="px-4 py-3 align-middle">
                        <div class="max-w-[35vw] md:max-w-[20vw] overflow-x-auto whitespace-nowrap custom-scrollbar pb-1">
                            <div class="font-bold text-gray-800">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $user->role === 'mahasiswa' ? 'NIM:' : 'NIP:' }} <span class="text-gray-700 font-medium">{{ $user->nim_nip ?? '-' }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-4 py-3 align-middle text-gray-600">
                        <div class="max-w-[35vw] md:max-w-[15vw] overflow-x-auto whitespace-nowrap custom-scrollbar pb-1" title="{{ $user->email }}">
                            {{ $user->email }}
                        </div>
                    </td>

                    <td class="px-4 py-3 align-middle">
                        @switch(trim($user->role))
                            @case('super_admin') <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#006633] bg-green-50 border border-green-200 rounded-md">Super Admin</span> @break
                            @case('admin') <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-red-700 bg-red-50 border border-red-200 rounded-md">Admin</span> @break
                            @case('wakil_dekan') <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-purple-700 bg-purple-50 border border-purple-200 rounded-md">Wakil Dekan</span> @break
                            @case('jurusan') <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-orange-700 bg-orange-50 border border-orange-200 rounded-md">Jurusan</span> @break
                            @case('mahasiswa') <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200 rounded-md">Mahasiswa</span> @break
                            @default <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-600 bg-gray-100 border border-gray-200 rounded-md">{{ str_replace('_', ' ', $user->role) }}</span>
                        @endswitch
                    </td>

                    <td class="px-4 py-3 align-middle">
                        @if($user->is_active == 1)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-green-700 bg-green-50 border border-green-200 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 align-middle text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            @if(isset($statusTab) && $statusTab === 'pending')
                                <form action="{{ route($routePrefix . '.manajemen-akun.aktivasi', $user->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Terima & Aktifkan">
                                        <i class="bi bi-check-lg text-lg"></i>
                                    </button>
                                </form>

                                <form action="{{ route($routePrefix . '.manajemen-akun.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Tolak dan hapus data pendaftar ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Tolak Pendaftaran">
                                        <i class="bi bi-x-lg text-lg"></i>
                                    </button>
                                </form>
                            @else
                                @if(Auth::user()->role === 'super_admin' || $user->role !== 'super_admin')
                                <a href="{{ route($routePrefix . '.manajemen-akun.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors tooltip" title="Edit Akun">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>
                                @endif

                                @php
                                    $totalSuperAdmin = \App\Models\User::where('role', 'super_admin')->count();
                                    $isSelf = Auth::id() === $user->id;
                                    $isLastSuperAdmin = ($user->role === 'super_admin' && $totalSuperAdmin <= 1);
                                @endphp

                                @if(!$isSelf && !$isLastSuperAdmin)
                                    @if(Auth::user()->role === 'super_admin' || $user->role !== 'super_admin')
                                    <form action="{{ route($routePrefix . '.manajemen-akun.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Hapus Akun">
                                            <i class="bi bi-trash3 text-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                @elseif($isLastSuperAdmin)
                                    <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed tooltip" title="Super Admin Terakhir tidak bisa dihapus">
                                        <i class="bi bi-shield-lock-fill text-lg"></i>
                                    </span>
                                @endif
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">
                        {{ (isset($statusTab) && $statusTab === 'pending') ? 'Tidak ada akun yang menunggu aktivasi.' : 'Belum ada data akun yang ditemukan.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-4 border-t border-gray-100 bg-white">
        {{ $users->links() }}
    </div>
    @endif
</div>

{{-- Form Tersembunyi untuk Eksekusi Bulk Action --}}
<form id="bulk-form" method="POST" action="{{ route($routePrefix . '.manajemen-akun.bulk') }}" class="hidden">
    @csrf
    <input type="hidden" name="bulk_action" id="bulk-action-type">
    <div id="bulk-ids-container"></div>
</form>

<script>
    // ---------------------------------------------
    // LOGIKA PENCARIAN (Debounce)
    // ---------------------------------------------
    let timeout = null;
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');

    if(searchInput && searchForm) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                searchForm.submit();
            }, 500);
        });

        const val = searchInput.value;
        searchInput.value = '';
        searchInput.focus();
        searchInput.value = val;
    }

    // ---------------------------------------------
    // LOGIKA CHECKBOX & BULK ACTION BAR
    // ---------------------------------------------
    const selectAllCheckbox = document.getElementById('select-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionBar = document.getElementById('bulk-action-bar');
    const selectedCountText = document.getElementById('selected-count');

    function updateBulkBarVisibility() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActionBar.classList.remove('hidden');
            bulkActionBar.classList.add('flex');
            selectedCountText.textContent = checkedCount;
        } else {
            bulkActionBar.classList.add('hidden');
            bulkActionBar.classList.remove('flex');
            selectAllCheckbox.checked = false;
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function(e) {
            rowCheckboxes.forEach(cb => {
                cb.checked = e.target.checked;
            });
            updateBulkBarVisibility();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkBarVisibility);
    });

    // ---------------------------------------------
    // EKSEKUSI BULK ACTION (Kirim Array ID)
    // ---------------------------------------------
    function submitBulkAction(actionType) {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        if (selectedCheckboxes.length === 0) return;

        let confirmMsg = actionType === 'activate' 
            ? 'Yakin ingin mengaktifkan semua akun yang dipilih?' 
            : 'Peringatan: Yakin ingin MENGHAPUS PERMANEN akun yang dipilih?';

        if (!confirm(confirmMsg)) return;

        const bulkForm = document.getElementById('bulk-form');
        const idsContainer = document.getElementById('bulk-ids-container');
        document.getElementById('bulk-action-type').value = actionType;

        idsContainer.innerHTML = ''; // Reset container
        
        selectedCheckboxes.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            idsContainer.appendChild(input);
        });

        bulkForm.submit();
    }

    // Auto-hilang notifikasi sukses dalam 4 detik
    const successAlert = document.getElementById('success-alert');
    if(successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.style.display = 'none', 500);
        }, 4000);
    }
</script>
@endsection