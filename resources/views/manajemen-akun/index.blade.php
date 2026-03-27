@extends('layouts.app')

@section('content')
{{-- Header Area - Margin diperkecil --}}
<div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
    <h3 class="text-lg font-black text-gray-800 tracking-tight">Manajemen Akun</h3>

    @if(Auth::user()->hasPermission('akun.manage_user'))
    <a href="{{ route('akun.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm hover:bg-[#004d26] transition-all">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah</span>
    </a>
    @endif
</div>

<div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    {{-- Search & Filter - Padding lebih slim --}}
    <div class="px-4 py-3 border-b border-gray-50 flex flex-col lg:flex-row justify-between items-center gap-3 bg-gray-50/30">
        <div class="flex gap-1.5">
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'aktif']) }}" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusTab === 'aktif' ? 'bg-[#006633] text-white' : 'bg-white text-gray-400 border border-gray-100' }}">Aktif</a>
            <a href="{{ request()->fullUrlWithQuery(['tab' => 'pending']) }}" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusTab === 'pending' ? 'bg-orange-500 text-white' : 'bg-white text-gray-400 border border-gray-100' }}">
                Pending @if($pendingCount > 0) <span class="ml-1 px-1 py-0.5 bg-white text-orange-600 rounded-full text-[9px]">{{ $pendingCount }}</span> @endif
            </a>
        </div>

        <form id="search-form" action="{{ route(Auth::user()->role->kode_role == 'SA' ? 'super_admin.manajemen-akun' : 'admin.manajemen-akun') }}" method="GET" class="relative w-full lg:w-max">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari data..." autocomplete="off" class="w-full lg:w-64 pl-8 pr-4 py-1.5 border border-gray-200 rounded-lg text-xs focus:border-[#006633] outline-none transition-all">
        </form>
    </div>

    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">No</th>
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">User</th>
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">Akses</th>
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-4 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50/30 transition-colors">
                    <td class="px-4 py-2 text-[11px] font-bold text-gray-400">{{ $users->firstItem() + $index }}</td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            {{-- Avatar diperkecil --}}
                            <div class="w-7 h-7 rounded-lg bg-green-50 text-[#006633] flex items-center justify-center font-bold text-[10px] flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-800 truncate leading-tight">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-medium tracking-tight">{{ $user->nim_nip }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-[11px] text-gray-500 truncate max-w-[150px]">{{ $user->email ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black uppercase rounded">
                            {{ $user->role->nama_role }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <span class="text-[9px] font-bold {{ $user->is_active ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }} px-1.5 py-0.5 rounded uppercase">
                            {{ $user->is_active ? 'Aktif' : 'Pending' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex justify-center gap-1.5">
                            @if(Auth::user()->hasPermission('akun.manage_user'))
                            {{-- Tombol aksi lebih mungil --}}
                            <a href="{{ route('akun.edit', $user->id) }}" class="w-6 h-6 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white flex items-center justify-center transition-all" title="Edit"><i class="bi bi-pencil-square text-xs"></i></a>
                            
                            <form action="{{ route('akun.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                @csrf @method('DELETE')
                                <button class="w-6 h-6 rounded-md bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all" title="Hapus"><i class="bi bi-trash-fill text-xs"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-xs text-gray-400 italic">Data tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 bg-gray-50/30 border-t border-gray-50">{{ $users->links() }}</div>
</div>

<script>
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => { searchForm.submit(); }, 500); // 0.5 detik
    });

    window.onload = () => {
        if(searchInput.value) {
            searchInput.focus();
            const val = searchInput.value;
            searchInput.value = '';
            searchInput.value = val;
        }
    };
</script>
@endsection