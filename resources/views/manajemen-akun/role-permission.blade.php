@extends('layouts.app')

@section('content')
<div class="mb-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Role dan Hak Akses</h3>
    </div>
</div>

{{-- Statistik Area --}}
<div class="mb-6 space-y-4">
    <div class="flex flex-wrap gap-2 items-center">
        <div class="px-4 py-2 bg-white text-gray-600 rounded-xl text-xs font-bold border border-gray-200 shadow-sm">
            <span class="text-gray-900 font-black">{{ $stats['total_permissions'] }}</span> TOTAL IZIN
        </div>
        
        @foreach($roles as $role)
            @php
                $colors = match($role->kode_role) {
                    'SA' => ['bg' => 'bg-indigo-50', 'txt' => 'text-indigo-700', 'brd' => 'border-indigo-200'],
                    'AD' => ['bg' => 'bg-emerald-50', 'txt' => 'text-emerald-700', 'brd' => 'border-emerald-200'],
                    'FK' => ['bg' => 'bg-blue-50', 'txt' => 'text-blue-700', 'brd' => 'border-blue-200'],
                    'JR' => ['bg' => 'bg-amber-50', 'txt' => 'text-amber-700', 'brd' => 'border-amber-200'],
                    'MHS'=> ['bg' => 'bg-rose-50', 'txt' => 'text-rose-700', 'brd' => 'border-rose-200'],
                    default => ['bg' => 'bg-gray-50', 'txt' => 'text-gray-700', 'brd' => 'border-gray-200']
                };
            @endphp
            <div class="px-4 py-2 {{ $colors['bg'] }} {{ $colors['txt'] }} {{ $colors['brd'] }} border rounded-xl text-xs font-black shadow-sm">
                {{ $role->nama_role }} <span class="ml-1 opacity-70">({{ $stats['role_counts'][$role->kode_role] }})</span>
            </div>
        @endforeach
    </div>

    {{-- Filter Search & Dropdown Role --}}
    <div class="flex flex-col md:flex-row gap-3">
        <div class="relative flex-1">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="search-perm" onkeyup="filterPermissions()" placeholder="Cari izin atau deskripsi fitur..." 
                   class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:border-[#006633] outline-none shadow-sm transition-colors">
        </div>
        
        <div class="relative w-full md:w-64 flex-shrink-0">
            <select id="role-filter" onchange="filterByRole(this.value)" 
                    class="w-full pl-4 pr-10 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 focus:border-[#006633] outline-none shadow-sm transition-colors cursor-pointer appearance-none">
                <option value="all">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->kode_role }}">{{ $role->nama_role }}</option>
                @endforeach
            </select>
            <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
        </div>
    </div>
</div>

{{-- Matriks Perizinan Dinamis --}}
<div class="space-y-4" id="permissions-container">
    @foreach($permissionsGrouped as $modul => $perms)
        <div class="modul-section bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" data-modul="{{ $modul }}">
            
            {{-- Header Accordion --}}
            <button onclick="toggleModul('{{ $modul }}')" class="w-full px-6 py-4 bg-gray-50 flex items-center justify-between group hover:bg-gray-100 transition-all border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-5 bg-[#006633] rounded-full"></div>
                    <span class="text-sm font-black text-gray-800 uppercase tracking-widest">{{ str_replace('_', ' ', $modul) }}</span>
                    <span class="text-xs text-gray-500 font-bold bg-white px-2 py-0.5 rounded-md border border-gray-200">{{ $perms->count() }} Izin</span>
                </div>
                <i class="bi bi-chevron-down text-gray-400 group-hover:text-gray-800 transition-transform duration-300" id="icon-{{ $modul }}"></i>
            </button>

            {{-- Isi Tabel --}}
<div id="content-{{ $modul }}" class="overflow-x-auto transition-all duration-300">
    <table class="w-full text-left border-collapse min-w-[900px]">
        <thead>
            <tr class="bg-white border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase w-16 text-center">No</th>
                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase">Detail Fitur & Izin</th>
                
                @foreach($roles as $role)
                    {{-- Header: text-center & w-28 --}}
                    <th class="px-2 py-4 text-[10px] font-black uppercase role-col text-gray-500 tracking-wider text-center w-28 min-w-[7rem]" data-role="{{ $role->kode_role }}">
                        {{ $role->nama_role }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($perms as $index => $perm)
                <tr class="perm-row hover:bg-gray-50/50 transition-colors" data-search="{{ strtolower($perm->kode_permission . ' ' . $perm->label) }}">
                    <td class="px-6 py-4 text-xs font-bold text-gray-300 text-center">{{ $index + 1 }}</td>
                    
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-800 font-bold leading-tight">{{ $perm->label }}</span>
                            <code class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">{{ $perm->kode_permission }}</code>
                        </div>
                    </td>
                    
                    @foreach($roles as $role)
                        @php
                            $hasPerm = $perm->roles->contains($role->id);
                            $isSuperAdmin = $role->kode_role === 'SA';
                        @endphp
                        {{-- Sel: justify-center & w-28 agar tegak lurus ke bawah --}}
                        <td class="px-2 py-4 role-cell w-28" data-role-cell="{{ $role->kode_role }}">
                            <div class="flex justify-center">
                                <label class="relative inline-flex items-center {{ $isSuperAdmin ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer' }}">
                                    <input type="checkbox" class="sr-only peer toggle-permission" 
                                           data-role-id="{{ $role->id }}" 
                                           data-perm-id="{{ $perm->id }}"
                                           {{ $hasPerm ? 'checked' : '' }}
                                           {{ $isSuperAdmin ? 'disabled' : '' }}>
                                    
                                    <div class="w-8 h-4.5 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-green-100 peer-checked:after:translate-x-3.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-[#006633]"></div>
                                </label>
                            </div>
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

{{-- Toast --}}
<div id="toast-notif" class="fixed bottom-5 right-5 transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-3 bg-gray-900 text-white px-5 py-3 rounded-2xl shadow-2xl">
    <i class="bi bi-check-circle-fill text-green-400"></i>
    <span class="text-sm font-bold" id="toast-msg">Hak akses disimpan.</span>
</div>

<script>
    function filterPermissions() {
        let input = document.getElementById('search-perm').value.toLowerCase();
        let rows = document.querySelectorAll('.perm-row');
        rows.forEach(row => {
            row.style.display = row.getAttribute('data-search').includes(input) ? '' : 'none';
        });
    }

    function filterByRole(role) {
        document.querySelectorAll('.role-col').forEach(c => c.style.display = (role === 'all' || c.getAttribute('data-role') === role) ? '' : 'none');
        document.querySelectorAll('.role-cell').forEach(c => c.style.display = (role === 'all' || c.getAttribute('data-role-cell') === role) ? '' : 'none');
    }

    function toggleModul(modul) {
        document.getElementById('content-' + modul).classList.toggle('hidden');
        document.getElementById('icon-' + modul).classList.toggle('rotate-180');
    }

    document.querySelectorAll('.toggle-permission').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const roleId = this.getAttribute('data-role-id');
            const permId = this.getAttribute('data-perm-id');
            const action = this.checked ? 'attach' : 'detach';
            
            this.parentElement.classList.add('opacity-50');

            fetch('{{ route("akun.role-permission.update") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ role_id: roleId, permission_id: permId, action: action })
            })
            .then(res => res.json())
            .then(data => {
                this.parentElement.classList.remove('opacity-50');
                if(data.success) {
                    const toast = document.getElementById('toast-notif');
                    toast.classList.remove('translate-y-20', 'opacity-0');
                    setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 2000);
                }
            });
        });
    });
</script>
@endsection