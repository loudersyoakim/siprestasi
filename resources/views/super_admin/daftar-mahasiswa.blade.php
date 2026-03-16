@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Daftar Mahasiswa</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh data mahasiswa yang terdaftar di sistem.</p>
    </div>
    
    {{-- Fitur Pencarian (Opsional untuk UI) --}}
    <div class="relative">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" placeholder="Cari Nama atau NIM..." class="pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#006633] focus:ring-1 focus:ring-[#006633] shadow-sm w-full sm:w-64 transition-all">
    </div>
</div>

@if(session('success'))
<div class="auto-dismiss-alert mb-6 flex items-center justify-between p-4 text-sm font-bold text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm">
    <div class="flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-900"><i class="bi bi-x-lg"></i></button>
</div>
@endif

<div class="w-full bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Total: {{ $mahasiswa->count() }} Mahasiswa</h4>
    </div>

    <div class="w-full overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider w-16 align-middle">No</th>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Informasi Mahasiswa</th>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Struktur Akademik</th>
                    <th class="px-6 py-4 text-gray-400 text-[10px] uppercase font-bold tracking-wider align-middle">Terdaftar Pada</th>
                    <th class="px-6 py-4 text-center text-gray-400 text-[10px] uppercase font-bold tracking-wider w-32 align-middle">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm bg-white">
                @forelse($mahasiswa as $index => $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-500 font-medium align-middle">{{ $index + 1 }}</td>
                    
                    {{-- Kolom Info Mahasiswa --}}
                    <td class="px-6 py-4 align-middle">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#006633] to-green-400 text-white flex items-center justify-center font-bold shadow-sm">
                                {{ strtoupper(substr($item->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">{{ $item->name }}</div>
                                <div class="text-[11px] font-black text-[#006633] tracking-widest mt-0.5">{{ $item->nim_nip }}</div>
                                <div class="text-[11px] text-gray-500">{{ $item->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Kolom Akademik (Ajaib: Deteksi Otomatis dari NIM) --}}
                    <td class="px-6 py-4 align-middle">
                        @if($item->prodi)
                            <div class="font-bold text-gray-800 text-xs">{{ $item->prodi->nama_prodi }} <span class="text-gray-400">({{ $item->prodi->jenjang }})</span></div>
                            <div class="text-[10px] text-gray-500 mt-1">
                                {{ $item->prodi->jurusan->nama_jurusan }} <br>
                                <span class="text-[#006633] font-semibold">{{ $item->prodi->jurusan->fakultas->nama_fakultas }}</span>
                            </div>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-200">
                                <i class="bi bi-exclamation-triangle mr-1"></i> Prodi Tidak Dikenali
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-gray-500 text-xs align-middle">
                        {{ $item->created_at->format('d M Y') }}
                    </td>

                    <td class="px-6 py-4 align-middle text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Detail Mahasiswa">
                                <i class="bi bi-eye-fill text-lg"></i>
                            </button>
                            <form action="{{ route($prefix . '.daftar-mahasiswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini? Seluruh data prestasinya juga akan terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors tooltip" title="Hapus Mahasiswa">
                                    <i class="bi bi-trash3 text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data mahasiswa yang terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.auto-dismiss-alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.style.display = 'none', 500);
        });
    }, 4000);
</script>
@endsection