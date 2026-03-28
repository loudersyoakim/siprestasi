@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('prestasi.index-all') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Prestasi
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Manajemen Alur Persetujuan</h3>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    
    {{-- KOLOM KIRI: Penjelasan & Info --}}
    <div class="xl:col-span-1 space-y-6">
        <div class="bg-blue-50/50 border border-blue-100 rounded-3xl p-6 sm:p-8">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <h4 class="text-base font-black text-blue-900 mb-2">Birokrasi Otomatis</h4>
            <p class="text-sm text-blue-700 leading-relaxed">
                Tentukan tahapan validasi yang harus dilalui oleh setiap data prestasi mahasiswa sebelum statusnya dinyatakan <b>Approved (Disetujui)</b> dan dipublikasi.
            </p>
            <div class="mt-6 space-y-3">
                <div class="flex items-start gap-3">
                    <i class="bi bi-check-circle-fill text-green-500 mt-0.5"></i>
                    <span class="text-xs text-blue-800 font-medium">Jika dinonaktifkan, tahapan tersebut akan dilewati (Bypass).</span>
                </div>
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle-fill text-yellow-500 mt-0.5"></i>
                    <span class="text-xs text-blue-800 font-medium">Data yang diinput langsung oleh Admin/Staff otomatis <b>Approved</b> tanpa melalui alur ini.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Timeline Konfigurasi --}}
    <div class="xl:col-span-2">
        <form action="{{ route('prestasi.alur.update') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 relative">
            @csrf

            <div class="mb-8 border-b border-gray-50 pb-4 flex justify-between items-center">
                <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Konfigurasi Tahapan</h4>
                <button type="submit" class="px-5 py-2 bg-[#006633] text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-[#004d26] transition-all shadow-md shadow-green-100">Simpan Alur</button>
            </div>

            {{-- TIMELINE CONTAINER --}}
            <div class="relative pl-4 sm:pl-8">
                
                {{-- Garis Lurus Timeline --}}
                <div class="absolute left-6 sm:left-10 top-6 bottom-10 w-0.5 bg-gray-100"></div>

                {{-- TAHAPAN 0: Input Mahasiswa (Statis) --}}
                <div class="relative flex items-start gap-6 mb-10 group">
                    <div class="absolute -left-2 sm:left-2 w-4 h-4 bg-gray-200 rounded-full border-4 border-white z-10 shadow-sm mt-1.5 group-hover:scale-125 transition-transform"></div>
                    <div class="flex-1 bg-gray-50 border border-gray-100 rounded-2xl p-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Mulai (Start)</span>
                                <h5 class="text-sm font-bold text-gray-800">Mahasiswa Melapor Prestasi</h5>
                            </div>
                            <span class="px-2.5 py-1 bg-gray-200 text-gray-600 text-[9px] font-black uppercase rounded-lg">Default</span>
                        </div>
                    </div>
                </div>

                {{-- TAHAPAN DINAMIS DARI DATABASE --}}
                @foreach($alur as $item)
                <div class="relative flex items-start gap-6 mb-10 group">
                    {{-- Titik Timeline Dinamis --}}
                    <div class="absolute -left-2 sm:left-2 w-4 h-4 {{ $item->is_active ? 'bg-[#006633]' : 'bg-gray-300' }} rounded-full border-4 border-white z-10 shadow-sm mt-1.5 transition-all timeline-dot"></div>
                    
                    <div class="flex-1 {{ $item->is_active ? 'bg-green-50/30 border-[#006633]/20 shadow-sm' : 'bg-white border-gray-100 opacity-60' }} border rounded-2xl p-5 transition-all duration-300 timeline-card">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                            <div>
                                <span class="text-[9px] font-black text-[#006633] uppercase tracking-widest block mb-1 timeline-label">Tahap {{ $item->urutan }}</span>
                                <h5 class="text-sm font-bold text-gray-800">{{ $item->nama_tahapan }}</h5>
                                {{-- <p class="text-xs text-gray-500 mt-1">{{ $item->keterangan }}</p> --}}
                            </div>

                            {{-- TOGGLE SWITCH TAILWIND --}}
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="is_active[{{ $item->id }}]" value="1" class="sr-only peer toggle-checkbox" {{ $item->is_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#006633]"></div>
                                <span class="ml-3 text-xs font-bold {{ $item->is_active ? 'text-[#006633]' : 'text-gray-400' }} uppercase tracking-wider w-12 toggle-text">{{ $item->is_active ? 'Aktif' : 'Lewati' }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- TAHAPAN AKHIR: Publikasi (Statis) --}}
                <div class="relative flex items-start gap-6 group">
                    <div class="absolute -left-2 sm:left-2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white z-10 shadow-sm mt-1.5 group-hover:scale-125 transition-transform"></div>
                    <div class="flex-1 bg-blue-50 border border-blue-100 rounded-2xl p-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[9px] font-black text-blue-500 uppercase tracking-widest block mb-1">Selesai (Finish)</span>
                                <h5 class="text-sm font-bold text-blue-900">Prestasi Disetujui (Approved)</h5>
                            </div>
                            <span class="px-2.5 py-1 bg-blue-200 text-blue-700 text-[9px] font-black uppercase rounded-lg">Final</span>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    // JS Murni untuk Animasi UI Toggle
    document.querySelectorAll('.toggle-checkbox').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const card = this.closest('.timeline-card');
            const dot = card.previousElementSibling;
            const textSpan = this.parentElement.querySelector('.toggle-text');
            const labelSpan = card.querySelector('.timeline-label');

            if (this.checked) {
                card.classList.add('bg-green-50/30', 'border-[#006633]/20', 'shadow-sm');
                card.classList.remove('bg-white', 'border-gray-100', 'opacity-60');
                dot.classList.add('bg-[#006633]');
                dot.classList.remove('bg-gray-300');
                textSpan.textContent = 'Aktif';
                textSpan.classList.add('text-[#006633]');
                textSpan.classList.remove('text-gray-400');
                labelSpan.classList.add('text-[#006633]');
                labelSpan.classList.remove('text-gray-400');
            } else {
                card.classList.remove('bg-green-50/30', 'border-[#006633]/20', 'shadow-sm');
                card.classList.add('bg-white', 'border-gray-100', 'opacity-60');
                dot.classList.remove('bg-[#006633]');
                dot.classList.add('bg-gray-300');
                textSpan.textContent = 'Lewati';
                textSpan.classList.remove('text-[#006633]');
                textSpan.classList.add('text-gray-400');
                labelSpan.classList.remove('text-[#006633]');
                labelSpan.classList.add('text-gray-400');
            }
        });
    });
</script>
@endsection