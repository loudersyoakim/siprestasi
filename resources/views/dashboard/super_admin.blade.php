@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h3 class="text-2xl font-black text-gray-800 tracking-tight">Dashboard</h3>
    </div>
    <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
        <i class="bi bi-calendar3 text-[#006633]"></i>
        <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">{{ now()->translatedFormat('d F Y') }}</span>
    </div>
</div>

{{-- ========================================== --}}
{{-- 1. STATISTIC CARDS (4 Kolom) --}}
{{-- ========================================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    {{-- Card 1: Total Mahasiswa --}}
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:border-blue-100 transition-all duration-300">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-tight min-h-[28px] flex items-start">Total Mahasiswa</p>
                <h4 class="text-3xl font-black text-gray-800">{{ number_format($totalMahasiswa ?? 0) }}</h4>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center gap-2 text-[10px] font-bold text-blue-500 uppercase tracking-wider">
            <a href="{{ route('prestasi.formulir-prestasi.index') }}" class="hover:underline">Kelola Akun <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    {{-- Card 2: Total Prestasi --}}
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:border-green-100 transition-all duration-300">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-tight min-h-[28px] flex items-start">Total Prestasi</p>
                <h4 class="text-3xl font-black text-gray-800">{{ number_format($totalPrestasi ?? 0) }}</h4>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center text-xl shadow-inner">
                <i class="bi bi-trophy-fill"></i>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center gap-2 text-[10px] font-bold text-green-600 uppercase tracking-wider">
            <span class="text-gray-400">Telah Dilaporkan</span>
        </div>
    </div>

    {{-- Card 3: Menunggu Validasi --}}
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:border-orange-100 transition-all duration-300">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-tight min-h-[28px] flex items-start">Butuh Validasi</p>
                <h4 class="text-3xl font-black text-gray-800">{{ number_format($prestasiPending ?? 0) }}</h4>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-500 flex items-center justify-center text-xl shadow-inner">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center gap-2 text-[10px] font-bold text-orange-500 uppercase tracking-wider">
            <a href="#" class="hover:underline">Cek Antrean <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>

    {{-- Card 4: Antrean Surat --}}
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:border-purple-100 transition-all duration-300">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-tight min-h-[28px] flex items-start">Antrean Surat</p>
                <h4 class="text-3xl font-black text-gray-800">{{ number_format($suratPending ?? 0) }}</h4>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-inner">
                <i class="bi bi-envelope-paper-fill"></i>
            </div>
        </div>
        <div class="relative z-10 mt-4 flex items-center gap-2 text-[10px] font-bold text-purple-500 uppercase tracking-wider">
            <a href="#" class="hover:underline">Proses Surat <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MAIN CONTENT AREA (Grid 2 Kolom) --}}
{{-- ========================================== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- KIRI: Aksi Cepat & Info Terbaru (Lebar) --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Section: Aksi Cepat --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-2 h-6 bg-[#006633] rounded-full"></div>
                <h4 class="font-black text-gray-800 uppercase tracking-widest text-sm">Aksi Cepat Admin</h4>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="#" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 border border-transparent hover:bg-green-50 hover:border-green-100 transition-all group text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white text-green-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-600 uppercase tracking-wider group-hover:text-green-700">Validasi Data</span>
                </a>

                <a href="#" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 border border-transparent hover:bg-blue-50 hover:border-blue-100 transition-all group text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white text-blue-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-600 uppercase tracking-wider group-hover:text-blue-700">Buat Berita</span>
                </a>

                <a href="{{ route('prestasi.formulir-prestasi.index') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 border border-transparent hover:bg-orange-50 hover:border-orange-100 transition-all group text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white text-orange-500 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        <i class="bi bi-ui-radios"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-600 uppercase tracking-wider group-hover:text-orange-700">Form Prestasi</span>
                </a>

                <a href="#" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 border border-transparent hover:bg-purple-50 hover:border-purple-100 transition-all group text-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-white text-purple-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform">
                        <i class="bi bi-printer"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-600 uppercase tracking-wider group-hover:text-purple-700">Cetak Laporan</span>
                </a>
            </div>
        </div>

        {{-- Section: Grafik Placeholder --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 flex flex-col justify-center items-center min-h-[300px]">
            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center text-2xl mb-4">
                <i class="bi bi-bar-chart-fill"></i>
            </div>
            <h4 class="text-sm font-black text-gray-700">Grafik Statistik Prestasi</h4>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Dalam Pengembangan</p>
        </div>
    </div>

    {{-- KANAN: Aktivitas Terbaru (Sempit) --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-50 bg-gray-50/30">
            <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="bi bi-bell-fill text-yellow-500"></i> Notifikasi Sistem
            </h4>
        </div>
        <div class="p-6 flex-1 flex flex-col justify-center items-center text-center">
            @if($prestasiPending > 0 || $suratPending > 0)
                <div class="w-full space-y-4">
                    @if($prestasiPending > 0)
                    <div class="p-4 bg-orange-50 border border-orange-100 rounded-2xl text-left flex gap-4 items-start">
                        <i class="bi bi-exclamation-circle-fill text-orange-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-bold text-orange-800">Validasi Prestasi</p>
                            <p class="text-[10px] text-orange-600 mt-1">Ada {{ $prestasiPending }} data prestasi mahasiswa yang menunggu untuk divalidasi.</p>
                        </div>
                    </div>
                    @endif

                    @if($suratPending > 0)
                    <div class="p-4 bg-purple-50 border border-purple-100 rounded-2xl text-left flex gap-4 items-start">
                        <i class="bi bi-envelope-paper-fill text-purple-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-bold text-purple-800">Permohonan Surat</p>
                            <p class="text-[10px] text-purple-600 mt-1">Ada {{ $suratPending }} pengajuan surat yang perlu diproses.</p>
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-2xl mb-4">
                    <i class="bi bi-check-all"></i>
                </div>
                <p class="text-sm font-bold text-gray-600">Semua Terkendali!</p>
                <p class="text-[10px] text-gray-400 mt-1">Tidak ada tugas atau validasi yang tertunda saat ini.</p>
            @endif
        </div>
    </div>

</div>
@endsection