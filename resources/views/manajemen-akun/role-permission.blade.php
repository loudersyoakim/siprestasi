@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="text-lg font-black text-gray-800 tracking-tight">Role & Hak Akses</h3>
    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">Matriks Pengaturan Izin Sistem (RBAC)</p>
</div>

{{-- Statistik & Filter Area --}}
<div class="mb-4 space-y-3">
    <div class="flex flex-wrap gap-1.5 items-center">
        <div class="px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-bold border border-gray-200">
            {{ $stats['total_permissions'] }} TOTAL IZIN
        </div>
        @foreach($roles as $role)
            @php
                $colors = match($role->kode_role) {
                    'SA' => ['bg' => 'bg-indigo-50', 'txt' => 'text-indigo-600', 'brd' => 'border-indigo-100'],
                    'AD' => ['bg' => 'bg-emerald-50', 'txt' => 'text-emerald-600', 'brd' => 'border-emerald-100'],
                    'FK' => ['bg' => 'bg-blue-50', 'txt' => 'text-blue-600', 'brd' => 'border-blue-100'],
                    'JR' => ['bg' => 'bg-amber-50', 'txt' => 'text-amber-600', 'brd' => 'border-amber-100'],
                    'MHS'=> ['bg' => 'bg-rose-50', 'txt' => 'text-rose-600', 'brd' => 'border-rose-100'],
                    default => ['bg' => 'bg-gray-50', 'txt' => 'text-gray-600', 'brd' => 'border-gray-100']
                };
            @endphp
            <div class="px-3 py-1.5 {{ $colors['bg'] }} {{ $colors['txt'] }} {{ $colors['brd'] }} border rounded-lg text-[10px] font-black uppercase shadow-sm">
                {{ $role->nama_role }}: {{ $stats['role_counts'][$role->kode_role] }}
            </div>
        @endforeach
    </div>

    <div class="flex flex-col md:flex-row gap-2">
        <div class="relative flex-1">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="search-perm" onkeyup="filterPermissions()" placeholder="Cari kode atau deskripsi izin..." 
                   class="w-full pl-8 pr-4 py-1.5 bg-white border border-gray-200 rounded-xl text-xs focus:border-[#006633] outline-none shadow-sm">
        </div>
        <div class="flex gap-1 overflow-x-auto pb-1 no-scrollbar">
            <button onclick="filterByRole('all')" class="role-filter-btn active px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-[10px] font-bold text-gray-400 hover:text-[#006633] transition-all">SEMUA</button>
            @foreach($roles as $role)
                <button onclick="filterByRole('{{ $role->kode_role }}')" class="role-filter-btn px-3 py-1.5 bg-white border border-gray-200 rounded-xl text-[10px] font-bold text-gray-400 transition-all uppercase">
                    {{ $role->kode_role }}
                </button>
            @endforeach
        </div>
    </div>
</div>

{{-- Matriks Perizinan --}}
<div class="space-y-2" id="permissions-container">
    @foreach($permissionsGrouped as $modul => $perms)
        <div class="modul-section bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden" data-modul="{{ $modul }}">
            {{-- Header Modul --}}
            <button onclick="toggleModul('{{ $modul }}')" class="w-full px-4 py-2.5 bg-gray-50/50 flex items-center justify-between group hover:bg-gray-50 transition-all border-b border-gray-50">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-[#006633] rounded-full"></div>
                    <span class="text-[10px] font-black text-gray-700 uppercase tracking-widest">{{ str_replace('_', ' ', $modul) }}</span>
                    <span class="text-[9px] text-gray-400 font-bold">({{ $perms->count() }} Izin)</span>
                </div>
                <i class="bi bi-chevron-down text-gray-300 group-hover:text-gray-600 transition-transform duration-300" id="icon-{{ $modul }}"></i>
            </button>

            <div id="content-{{ $modul }}" class="overflow-x-auto transition-all duration-300">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-white border-b border-gray-50">
                            <th class="px-4 py-2 text-[9px] font-black text-gray-300 uppercase w-12 text-center">#</th>
                            <th class="px-4 py-2 text-[9px] font-black text-gray-300 uppercase w-48">Kode Izin</th>
                            <th class="px-4 py-2 text-[9px] font-black text-gray-300 uppercase">Deskripsi Fitur</th>
                            @foreach($roles as $role)
                                <th class="px-2 py-2 text-[9px] font-black text-center uppercase role-col" data-role="{{ $role->kode_role }}">
                                    {{ $role->kode_role }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($perms as $index => $perm)
                            <tr class="perm-row hover:bg-gray-50/30 transition-colors" data-search="{{ strtolower($perm->kode_permission . ' ' . $perm->label) }}">
                                <td class="px-4 py-1.5 text-[10px] font-bold text-gray-300 text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-1.5">
                                    <code class="text-[10px] font-bold text-gray-500 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100">{{ $perm->kode_permission }}</code>
                                </td>
                                <td class="px-4 py-1.5 text-[11px] text-gray-600 font-medium leading-tight">{{ $perm->label }}</td>
                                @foreach($roles as $role)
                                    @php
                                        $hasPerm = $perm->roles->contains($role->id);
                                        $dotColor = match($role->kode_role) {
                                            'SA' => 'text-indigo-500 bg-indigo-50',
                                            'AD' => 'text-emerald-500 bg-emerald-50',
                                            'FK' => 'text-blue-500 bg-blue-50',
                                            'JR' => 'text-amber-500 bg-amber-50',
                                            'MHS'=> 'text-rose-500 bg-rose-50',
                                            default => 'text-gray-400 bg-gray-100'
                                        };
                                    @endphp
                                    <td class="px-2 py-1.5 text-center role-cell" data-role-cell="{{ $role->kode_role }}">
                                        @if($hasPerm)
                                            <div class="inline-flex items-center justify-center w-5 h-5 rounded-full {{ $dotColor }} font-black text-[9px] shadow-sm">✓</div>
                                        @else
                                            <div class="inline-flex items-center justify-center w-5 h-5 rounded-full text-gray-200 text-[12px] font-light">—</div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

<style>
    .role-filter-btn.active {
        background-color: #006633 !important;
        color: white !important;
        border-color: #006633 !important;
        box-shadow: 0 4px 6px -1px rgb(0 102 51 / 0.1);
    }
</style>

<script>
    // Fungsi Pencarian
    function filterPermissions() {
        let input = document.getElementById('search-perm').value.toLowerCase();
        let rows = document.querySelectorAll('.perm-row');
        let sections = document.querySelectorAll('.modul-section');

        rows.forEach(row => {
            let text = row.getAttribute('data-search');
            row.style.display = text.includes(input) ? '' : 'none';
        });

        // Sembunyikan section jika tidak ada isi yang cocok
        sections.forEach(sec => {
            let visibleRows = sec.querySelectorAll('.perm-row:not([style*="display: none"])');
            sec.style.display = visibleRows.length > 0 ? '' : 'none';
        });
    }

    // Fungsi Filter per Role
    function filterByRole(role) {
        // Update Button State
        document.querySelectorAll('.role-filter-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');

        // Logic Filter Kolom
        let allRoleCols = document.querySelectorAll('.role-col');
        let allRoleCells = document.querySelectorAll('.role-cell');

        if(role === 'all') {
            allRoleCols.forEach(c => c.style.display = '');
            allRoleCells.forEach(c => c.style.display = '');
        } else {
            allRoleCols.forEach(c => {
                c.style.display = (c.getAttribute('data-role') === role) ? '' : 'none';
            });
            allRoleCells.forEach(c => {
                c.style.display = (c.getAttribute('data-role-cell') === role) ? '' : 'none';
            });
        }
    }

    // Fungsi Accordion
    function toggleModul(modul) {
        let content = document.getElementById('content-' + modul);
        let icon = document.getElementById('icon-' + modul);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.remove('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.add('rotate-180');
        }
    }
</script>
@endsection