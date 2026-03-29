@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
    <div>
       <h3 class="text-2xl font-black text-gray-800 tracking-tight">Konten Publikasi</h3>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('konten.create') }}" class="px-6 py-3 bg-[#006633] text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-green-900/20 hover:bg-black transition-all flex items-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Konten
        </a>
    </div>
</div>

{{-- PANEL UTAMA --}}
<div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden mb-10">
    
    {{-- TAB BAR & SEARCH --}}
    <div class="px-8 py-6 border-b border-gray-50 flex flex-col xl:flex-row xl:items-center justify-between gap-6 bg-gray-50/30">
        
        <div class="flex flex-col md:flex-row items-center gap-4">
            {{-- Segmented Control Tab --}}
            <div class="flex p-1.5 bg-gray-200/50 rounded-2xl w-max border border-gray-200/50">
                <button onclick="switchTab('berita')" class="px-8 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all {{ $tab === 'berita' ? 'bg-white text-[#006633] shadow-md' : 'text-gray-400 hover:text-gray-600' }}">
                    Artikel
                </button>
                <button onclick="switchTab('prestasi')" class="px-8 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all {{ $tab === 'prestasi' ? 'bg-white text-[#006633] shadow-md' : 'text-gray-400 hover:text-gray-600' }}">
                    Prestasi
                </button>
            </div>
        </div>

        {{-- SEARCH & FILTER AREA --}}
        <form id="search-form" action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row items-center gap-3 w-full xl:w-auto">
            <input type="hidden" name="tab" value="{{ $tab }}">
            
            {{-- Filter Kategori (Hanya muncul di tab Berita) --}}
            @if($tab === 'berita')
            <select name="kategori" onchange="this.form.submit()" 
                class="w-full md:w-40 px-4 py-3 bg-white border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest outline-none focus:border-[#006633] transition-all cursor-pointer shadow-sm">
                <option value="">Semua Kategori</option>
                <option value="berita" {{ request('kategori') === 'berita' ? 'selected' : '' }}>Berita</option>
                <option value="informasi" {{ request('kategori') === 'informasi' ? 'selected' : '' }}>Informasi</option>
                <option value="pengumuman" {{ request('kategori') === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                <option value="prestasi" {{ request('kategori') === 'prestasi' ? 'selected' : '' }}>Prestasi</option>
            </select>
            @endif

            {{-- Filter Status --}}
            <select name="status" onchange="this.form.submit()" 
                class="w-full md:w-36 px-4 py-3 bg-white border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest outline-none focus:border-[#006633] transition-all cursor-pointer shadow-sm">
                <option value="">Semua Status</option>
                <option value="live" {{ request('status') === 'live' ? 'selected' : '' }}>🟢 Live</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>⚪ Draft</option>
            </select>

            {{-- Input Search --}}
            <div class="relative group w-full md:w-72">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 transition-colors group-focus-within:text-[#006633]"></i>
                <input type="text" name="search" id="search-input" value="{{ $search }}" 
                    placeholder="{{ $tab === 'berita' ? 'Cari judul artikel...' : 'Cari nama atau kegiatan...' }}"
                    class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-[#006633]/5 focus:border-[#006633] transition-all font-medium shadow-sm">
            </div>
        </form>
    </div>

    {{-- KONTEN TAB: BERITA --}}
    <div class="{{ $tab === 'berita' ? '' : 'hidden' }}">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-auto">Konten / Judul</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-40 text-center">Kategori</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-32 text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-40 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kontens as $konten)
                    <tr class="hover:bg-gray-50/50 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 shrink-0">
                                    <img src="{{ $konten->gambar_cover ? asset('storage/'.$konten->gambar_cover) : 'https://ui-avatars.com/api/?name='.urlencode($konten->judul).'&background=006633&color=fff' }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <h5 class="font-black text-gray-800 text-sm uppercase truncate leading-tight">{{ $konten->judul }}</h5>
                                    <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-wider">Oleh: {{ $konten->penulis->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase border border-blue-100">{{ $konten->kategori }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($konten->is_aktif)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-600 rounded-full text-[9px] font-black uppercase border border-green-100">Live</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-400 rounded-full text-[9px] font-black uppercase">Draft</span>
                            @endif
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end gap-2 whitespace-nowrap">
                                <a href="{{ route('konten.edit', $konten->id) }}" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 text-yellow-500 rounded-xl hover:bg-yellow-50 transition-all shadow-sm"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('konten.destroy', $konten->id) }}" method="POST" onsubmit="return confirm('Hapus konten?')">
                                    @csrf @method('DELETE')
                                    <button class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 text-red-400 rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all shadow-sm"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-24 text-center text-gray-300 italic font-bold uppercase tracking-widest text-xs">Belum ada konten artikel</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50">{{ $kontens->links() }}</div>
    </div>

    {{-- KONTEN TAB: PRESTASI --}}
    <div class="{{ $tab === 'prestasi' ? '' : 'hidden' }}">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-auto">Mahasiswa & Kegiatan</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-48 text-center">Tingkat / Capaian</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-32 text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 w-56 text-right">Aksi Publikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($prestasiApproved as $p)
                    <tr class="hover:bg-gray-50/50 transition-all">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center border border-yellow-100 shrink-0 font-black text-base">
                                    {{ substr($p->user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h5 class="font-black text-gray-800 text-sm uppercase truncate leading-tight">{{ $p->nama_kegiatan }}</h5>
                                    <p class="text-[10px] text-[#006633] font-black mt-1 uppercase tracking-wider truncate">{{ $p->user->name }} • {{ $p->user->nim_nip }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex flex-col gap-1 items-center">
                                <span class="text-[10px] font-black text-gray-700 uppercase leading-none">{{ $p->capaianPrestasi->nama_capaian ?? '-' }}</span>
                                <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">{{ $p->tingkatPrestasi->nama_tingkat ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($p->is_published)
                                <span class="px-3 py-1 bg-[#006633] text-white rounded-full text-[9px] font-black uppercase tracking-widest border border-green-600 shadow-sm">Live</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-[9px] font-black uppercase tracking-widest border border-gray-200">Draft</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 whitespace-nowrap">
                                @if(!$p->is_published)
                                <form action="{{ route('konten.prestasi.publish', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-[#006633] text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all shadow-md">
                                        <i class="bi bi-megaphone-fill mr-1.5"></i> Publish
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('konten.prestasi.takedown', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-red-50 text-red-500 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all border border-red-100">
                                        <i class="bi bi-arrow-down-circle mr-1"></i> Take Down
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-24 text-center text-gray-300 italic font-bold uppercase tracking-widest text-xs">Tidak ada antrean prestasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50">{{ $prestasiApproved->links() }}</div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        url.searchParams.delete('page_konten');
        url.searchParams.delete('page_prestasi');
        url.searchParams.delete('search');
        url.searchParams.delete('status');
        url.searchParams.delete('kategori');
        window.location.href = url.href;
    }

    const sInput = document.getElementById('search-input');
    let t = null;
    sInput.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(() => {
            localStorage.setItem('pub_search_pos', sInput.selectionStart);
            document.getElementById('search-form').submit();
        }, 800);
    });

    window.onload = () => {
        const pos = localStorage.getItem('pub_search_pos');
        if (pos && document.activeElement && document.activeElement.id === 'search-input') {
            sInput.setSelectionRange(pos, pos);
            localStorage.removeItem('pub_search_pos');
        }
    };
</script>
@endsection