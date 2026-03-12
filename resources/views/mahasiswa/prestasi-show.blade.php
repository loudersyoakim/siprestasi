@extends('layouts.app')

@section('content')
<div class="mb-8">
    <a href="{{ route('mahasiswa.prestasi') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
    <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Detail Prestasi</h3>
</div>
{{-- PESAN HUBUNGI ADMIN --}}
@if($prestasi->status == 'approved')
<div class="mt-6 p-5 bg-blue-50 border border-blue-100 rounded-[2rem] flex items-center gap-4">
    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm shrink-0">
        <i class="bi bi-info-circle-fill text-xl"></i>
    </div>
    <div>
        <h5 class="text-[10px] font-black text-blue-900 uppercase tracking-widest">Informasi Perubahan</h5>
        <p class="text-xs text-blue-700 leading-snug mt-1">
            Data prestasi ini telah diverifikasi. Jika terdapat kesalahan data, silakan <strong>hubungi Admin</strong> untuk melakukan perubahan.
        </p>
    </div>
</div>
@endif
<div class="pt-2 grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- SISI KIRI: INFORMASI UTAMA --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-8">
                <div>
                    <span class="px-3 py-1 bg-green-50 text-[#006633] text-[10px] font-black uppercase rounded-lg border border-green-100">
                        {{ $prestasi->tingkat->nama_tingkat }}
                    </span>
                    <h1 class="text-2xl font-black text-gray-800 mt-3 leading-tight uppercase">{{ $prestasi->nama_prestasi }}</h1>
                </div>
                
                {{-- Badge Status --}}
                <div class="shrink-0">
                    @if($prestasi->status == 'approved')
                        <div class="bg-green-100 text-green-600 px-6 py-3 rounded-2xl text-center border border-green-200">
                            <i class="bi bi-patch-check-fill text-xl"></i>
                            <p class="text-[10px] font-black uppercase mt-1">Terverifikasi</p>
                        </div>
                    @elseif($prestasi->status == 'pending')
                        <div class="bg-orange-100 text-orange-600 px-6 py-3 rounded-2xl text-center border border-orange-200 animate-pulse">
                            <i class="bi bi-clock-history text-xl"></i>
                            <p class="text-[10px] font-black uppercase mt-1">Dalam Proses</p>
                        </div>
                    @else
                        <div class="bg-red-100 text-red-600 px-6 py-3 rounded-2xl text-center border border-red-200">
                            <i class="bi bi-x-circle-fill text-xl"></i>
                            <p class="text-[10px] font-black uppercase mt-1">Ditolak</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 py-6 border-y border-gray-50">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase">Kategori</p>
                    <p class="text-sm font-bold text-gray-700 mt-1">{{ $prestasi->kategori->nama_kategori }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase">Jenis</p>
                    <p class="text-sm font-bold text-gray-700 mt-1">{{ $prestasi->jenis->nama_jenis }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase">Tahun Akademik</p>
                    <p class="text-sm font-bold text-gray-700 mt-1">{{ $prestasi->tahunAkademik->tahun }}</p>
                </div>
            </div>

            <div class="mt-8">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-3">Deskripsi Prestasi</p>
                <div class="bg-gray-50 rounded-2xl p-6 text-sm text-gray-600 leading-relaxed italic">
                    "{{ $prestasi->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}"
                </div>
            </div>

            @if($prestasi->status == 'rejected')
            <div class="mt-6 p-4 bg-red-50 border border-red-100 rounded-2xl">
                <p class="text-[10px] font-black text-red-800 uppercase mb-1">Catatan Penolakan Admin:</p>
                <p class="text-xs text-red-600 font-bold">"{{ $prestasi->alasan_ditolak }}"</p>
            </div>
            @endif
        </div>

        {{-- DAFTAR ANGGOTA TIM --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
            <h5 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                <i class="bi bi-people-fill text-[#006633]"></i> Anggota Tim / Peserta
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($prestasi->mahasiswa as $mhs)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-black text-[#006633] shadow-sm">
                        {{ substr($mhs->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-800 uppercase leading-tight">{{ $mhs->name }}</p>
                        <p class="text-[9px] font-bold text-yellow-600 mt-0.5 tracking-widest">NIM: {{ $mhs->nim_nip }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SISI KANAN: SERTIFIKAT --}}
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h5 class="text-xs font-black text-gray-800 uppercase tracking-widest">Lampiran Berkas</h5>
                <a href="{{ asset('storage/' . $prestasi->sertifikat) }}" download class="text-[#006633] hover:text-[#004d26] transition-colors">
                    <i class="bi bi-download"></i>
                </a>
            </div>
            <div class="p-4">
                @php $ext = pathinfo($prestasi->sertifikat, PATHINFO_EXTENSION); @endphp
                
                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                    <img src="{{ asset('storage/' . $prestasi->sertifikat) }}" class="w-full rounded-2xl shadow-inner border border-gray-100" alt="Sertifikat">
                @else
                    <div class="aspect-[3/4] bg-gray-100 rounded-2xl flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-gray-200">
                        <i class="bi bi-file-earmark-pdf-fill text-5xl text-red-500 mb-4"></i>
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Berkas PDF</p>
                        <a href="{{ asset('storage/' . $prestasi->sertifikat) }}" target="_blank" class="w-full py-3 bg-[#006633] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#004d26] transition-all">
                            Buka File PDF
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info Log --}}
        <div class="bg-[#006633] rounded-3xl p-6 text-white shadow-lg shadow-green-100">
            <h5 class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-4">Informasi Sistem</h5>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-medium opacity-80">Dilaporkan pada</span>
                    <span class="font-black">{{ $prestasi->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="font-medium opacity-80">Terakhir Update</span>
                    <span class="font-black">{{ $prestasi->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection