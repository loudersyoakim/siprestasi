@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <h3 class="text-xl font-black text-gray-800 tracking-tight">Manajemen Akun</h3>

    <a href="{{ route('admin.manajemen-akun.create') }}" class="inline-flex items-center gap-2 bg-[#006633] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-green-200 hover:bg-[#004d26] transition-all">
        <i class="bi bi-person-plus-fill"></i>
        <span>Tambah Akun</span>
    </a>
</div>

<div class="w-full min-w-0 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col mb-8 overflow-hidden">

    <div class="p-4 sm:p-6 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/30">
        <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Daftar Pengguna</h4>

        <form id="search-form" action="{{ route('admin.manajemen-akun') }}" method="GET" class="relative w-full sm:w-max">
            @foreach(request()->except('search', 'page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text"
                id="search-input"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari Nama, NIM, atau Bulan..."
                autocomplete="off"
                class="w-full sm:w-96 pl-9 pr-10 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] transition-all">

            @if(request('search'))
            <a href="{{ route('admin.manajemen-akun', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                <i class="bi bi-x-circle-fill"></i>
            </a>
            @endif
        </form>
    </div>

    <div class="w-full overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 whitespace-nowrap text-gray-500 text-[10px] uppercase font-black tracking-wider align-top">No</th>

                    <th class="px-6 py-4 whitespace-nowrap align-top">
                        <div class="flex flex-col gap-2">
                            <span class="text-gray-500 text-[10px] uppercase font-black tracking-wider">Nama & Identitas</span>

                            {{-- Dropdown Sortir Nama --}}
                            <select onchange="window.location.href=this.value"
                                class="w-full max-w-[130px] text-[10px] py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 font-bold focus:outline-none focus:border-[#006633] cursor-pointer shadow-sm">

                                <option value="{{ request()->fullUrlWithQuery(['sort' => null, 'direction' => null]) }}">Default</option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => 'asc']) }}"
                                    {{ request('sort') == 'name' && request('direction') == 'asc' ? 'selected' : '' }}>
                                    Nama (A-Z)
                                </option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => 'desc']) }}"
                                    {{ request('sort') == 'name' && request('direction') == 'desc' ? 'selected' : '' }}>
                                    Nama (Z-A)
                                </option>
                            </select>
                        </div>
                    </th>

                    <th class="px-6 py-4 whitespace-nowrap align-top">
                        <span class="text-gray-500 text-[10px] uppercase font-black tracking-wider block mb-2">Email Akses</span>
                    </th>

                    {{-- KOLOM HAK AKSES (SEJAJAR) --}}
                    <th class="px-4 py-4 w-1 whitespace-nowrap min-w-[150px] align-top">
                        <div class="text-gray-500 text-[10px] uppercase font-black tracking-wider mb-2">Hak Akses</div>
                        <select onchange="window.location.href=this.value"
                            class="w-full max-w-[120px] text-[10px] py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 font-bold focus:outline-none focus:border-[#006633] cursor-pointer shadow-sm">
                            <option value="{{ request()->fullUrlWithQuery(['role' => null]) }}">Semua</option>
                            <option value="{{ request()->fullUrlWithQuery(['role' => 'admin']) }}" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="{{ request()->fullUrlWithQuery(['role' => 'wd']) }}" {{ request('role') == 'wd' ? 'selected' : '' }}>Wakil Dekan</option>
                            <option value="{{ request()->fullUrlWithQuery(['role' => 'kajur']) }}" {{ request('role') == 'kajur' ? 'selected' : '' }}>Kajur</option>
                            <option value="{{ request()->fullUrlWithQuery(['role' => 'gpm']) }}" {{ request('role') == 'gpm' ? 'selected' : '' }}>GPM</option>
                            <option value="{{ request()->fullUrlWithQuery(['role' => 'mahasiswa']) }}" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>
                    </th>

                    <th class="px-6 py-4 whitespace-nowrap align-top">
                        <div class="flex flex-col gap-2">
                            <span class="text-gray-500 text-[10px] uppercase font-black tracking-wider">Terdaftar Pada</span>

                            {{-- Dropdown Sortir Tanggal --}}
                            <select onchange="window.location.href=this.value"
                                class="w-full max-w-[140px] text-[10px] py-1.5 px-2 border border-gray-200 rounded-md bg-white text-gray-600 font-bold  focus:outline-none focus:border-[#006633] cursor-pointer shadow-sm">

                                <option value="{{ request()->fullUrlWithQuery(['sort' => null, 'direction' => null]) }}">Default</option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => 'desc']) }}"
                                    {{ request('sort') == 'created_at' && request('direction') == 'desc' ? 'selected' : '' }}>
                                    Terbaru
                                </option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => 'asc']) }}"
                                    {{ request('sort') == 'created_at' && request('direction') == 'asc' ? 'selected' : '' }}>
                                    Terlama
                                </option>
                            </select>
                        </div>
                    </th>

                    <th class="px-6 py-4 text-center whitespace-nowrap align-top text-gray-500 text-[10px] uppercase font-black tracking-wider">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4 text-gray-400 font-semibold align-top">{{ $users->firstItem() + $index }}</td>

                    <td class="px-6 py-4 align-top">
                        <div class="font-bold text-gray-800">{{ $user->name }}</div>
                        <div class="text-[11px] text-gray-400 font-semibold mt-0.5 tracking-wide">
                            {{ $user->role === 'mahasiswa' ? 'NIM:' : 'NIP:' }}
                            <span class="text-gray-600">{{ $user->nim_nip ?? '-' }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 align-top text-gray-600">
                        <div class="max-w-[200px] overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $user->email }}">
                            {{ $user->email }}
                        </div>
                    </td>

                    <td class="px-6 py-4 align-top">
                        @switch($user->role)
                        @case('admin') <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-100 rounded-lg">Admin</span> @break
                        @case('wd') <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-purple-600 bg-purple-100 rounded-lg">Wakil Dekan</span> @break
                        @case('kajur') <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-orange-600 bg-orange-100 rounded-lg">Kajur</span> @break
                        @case('gpm') <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-teal-600 bg-teal-100 rounded-lg">GPM</span> @break
                        @case('mahasiswa') <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-100 rounded-lg">Mahasiswa</span> @break
                        @default <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-600 bg-gray-100 rounded-lg">{{ $user->role }}</span>
                        @endswitch
                    </td>

                    <td class="px-6 py-4 align-top text-gray-500 text-xs font-medium">{{ $user->created_at->format('d M Y') }}</td>

                    <td class="px-6 py-4 align-top text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.manajemen-akun.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-400 hover:text-white flex items-center justify-center transition-colors tooltip" title="Edit Akun">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @if(Auth::id() !== $user->id)
                            <form action="{{ route('admin.manajemen-akun.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Hapus Akun">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data akun.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-4 border-t border-gray-50 bg-gray-50/30">
        {{ $users->links() }}
    </div>
    @endif
</div>
<script>
    let timeout = null;
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        // Tunggu 500ms setelah berhenti mengetik baru submit
        timeout = setTimeout(function() {
            searchForm.submit();
        }, 500);
    });

    // Pindahkan kursor ke akhir teks setelah reload
    const val = searchInput.value;
    searchInput.value = '';
    searchInput.focus();
    searchInput.value = val;
</script>
@endsection