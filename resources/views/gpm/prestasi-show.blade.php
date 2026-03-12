@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('gpm.dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Detail Data Prestasi</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- KOLOM KIRI: Informasi Utama & Mahasiswa (Lebih Lebar) --}}
    <div class="lg:col-span-2 space-y-8">

        {{-- Card Info Lomba --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-trophy-fill text-[#006633] text-lg"></i> Informasi Kegiatan
                </h4>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-black text-gray-800">{{ $prestasi->nama_prestasi }}</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tingkat</div>
                    <div class="text-sm font-bold text-[#006633]">{{ $prestasi->tingkat->nama_tingkat ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori</div>
                    <div class="text-sm font-bold text-gray-800">{{ $prestasi->kategori->nama_kategori ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Jenis</div>
                    <div class="text-sm font-bold text-gray-800">{{ $prestasi->jenis->nama_jenis ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tahun Akademik</div>
                    <div class="text-sm font-bold text-gray-800">{{ $prestasi->tahunAkademik->tahun ?? 'N/A' }}</div>
                </div>
            </div>

            @if($prestasi->deskripsi)
            <div class="mt-6">
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi / Keterangan</div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $prestasi->deskripsi }}</p>
            </div>
            @endif
        </div>

        {{-- Card Kepesertaan --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-people-fill text-[#006633] text-lg"></i> Mahasiswa Berprestasi
                </h4>
                @if($prestasi->mahasiswa->count() > 1)
                <span class="px-3 py-1 bg-blue-50 text-blue-600 font-bold text-[10px] uppercase tracking-widest rounded-lg">Kategori: Tim ({{ $prestasi->mahasiswa->count() }} Orang)</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold text-[10px] uppercase tracking-widest rounded-lg">Kategori: Individu</span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($prestasi->mahasiswa as $mhs)
                <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-2xl bg-gray-50/30">
                    <div class="w-10 h-10 rounded-full bg-green-100 text-[#006633] flex items-center justify-center font-black text-lg">
                        {{ substr($mhs->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-800">{{ $mhs->name }}</div>
                        <div class="text-[10px] font-black text-[#006633] uppercase tracking-widest mt-0.5">NIM: {{ $mhs->nim_nip }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Status, Sertifikat, Meta Data --}}
    <div class="space-y-8">

        {{-- Card Status & Validasi --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6">
            <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider mb-4 border-b border-gray-50 pb-4">Status Data</h4>

            <div class="space-y-5">
                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Validasi</div>
                    @switch($prestasi->status)
                    @case('approved')
                    <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-100 rounded-xl text-green-700 font-bold text-sm">
                        <i class="bi bi-check-circle-fill text-xl"></i> Disetujui (Approved)
                    </div>
                    @break
                    @case('pending')
                    <div class="flex items-center gap-3 p-3 bg-orange-50 border border-orange-100 rounded-xl text-orange-600 font-bold text-sm">
                        <i class="bi bi-clock-history text-xl animate-pulse"></i> Menunggu Validasi
                    </div>
                    @break
                    @case('rejected')
                    <div class="flex items-center gap-3 p-3 bg-red-50 border border-red-100 rounded-xl text-red-600 font-bold text-sm">
                        <i class="bi bi-x-circle-fill text-xl"></i> Ditolak (Rejected)
                    </div>
                    @if($prestasi->alasan_ditolak)
                    <div class="mt-2 p-3 bg-red-50/50 border border-red-100 rounded-xl border-dashed">
                        <span class="text-[10px] font-black text-red-400 uppercase block mb-1">Alasan Penolakan:</span>
                        <p class="text-xs text-red-600">{{ $prestasi->alasan_ditolak }}</p>
                    </div>
                    @endif
                    @break
                    @endswitch
                </div>

                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Manajemen Konten</div>
                    @if($prestasi->is_published)
                    <div class="flex items-center gap-2 text-sm font-bold text-blue-600">
                        <i class="bi bi-globe2"></i> Sudah Dipublikasi ke Artikel
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-sm font-bold text-gray-400">
                        <i class="bi bi-lock-fill"></i> Data Internal (Belum Publikasi)
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card Sertifikat --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6">
            <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider mb-4 border-b border-gray-50 pb-4">Dokumen Sertifikat</h4>

            <div class="text-center bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-6">
                <div class="w-16 h-16 mx-auto bg-green-100 text-[#006633] rounded-2xl flex items-center justify-center text-3xl mb-4">
                    @php
                    $ext = pathinfo($prestasi->sertifikat, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                    <i class="bi bi-file-earmark-image-fill"></i>
                    @else
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    @endif
                </div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Diperoleh</div>
                <div class="text-sm font-bold text-gray-800 mb-4">{{ \Carbon\Carbon::parse($prestasi->tanggal_peroleh)->translatedFormat('d F Y') }}</div>

                <a href="{{ asset('storage/' . $prestasi->sertifikat) }}" target="_blank" class="inline-flex items-center justify-center w-full gap-2 bg-[#006633] text-white px-5 py-3 rounded-xl text-sm font-bold shadow-md hover:bg-[#004d26] transition-all uppercase tracking-widest">
                    <i class="bi bi-box-arrow-up-right"></i> Buka Dokumen
                </a>
            </div>
        </div>

        {{-- Meta Data Input --}}
        <div class="text-center text-xs text-gray-400 font-medium">
            Diinput pada: {{ $prestasi->created_at->translatedFormat('d M Y, H:i') }} WIB<br>
            Update terakhir: {{ $prestasi->updated_at->diffForHumans() }}
        </div>

    </div>
</div>
@endsection