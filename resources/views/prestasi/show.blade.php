@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <a href="{{ route('prestasi.index-all') }}" class="text-sm font-bold text-gray-400 hover:text-[#006633] transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Detail Data Prestasi</h3>
    </div>

    <div class="flex items-center gap-3">
        @if(Auth::user()->hasPermission('prestasi.create'))
        <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="inline-flex items-center gap-2 bg-yellow-50 text-yellow-600 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-yellow-400 hover:text-white transition-all shadow-sm">
            <i class="bi bi-pencil-square"></i> Edit Data
        </a>
        @endif
    </div>
</div>

{{-- CONTAINER UTAMA (MINIMALIS & CLEAN) --}}
<div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-10">
    
    {{-- HEADER DOKUMEN --}}
    <div class="px-6 py-6 sm:px-10 sm:py-8 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/30">
        <div>
            <div class="text-[10px] font-black text-[#006633] uppercase tracking-widest mb-1">Kategori Formulir</div>
            <h4 class="text-lg font-black text-gray-800">{{ $prestasi->formPrestasi->nama_form ?? 'Formulir Dihapus' }}</h4>
        </div>
        
        {{-- Status Badge --}}
        <div>
            @switch($prestasi->status)
                @case('Approved')
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 text-green-700 text-xs font-black uppercase tracking-wider rounded-xl"><i class="bi bi-check-circle-fill text-base"></i> Disetujui</span>
                @break
                @case('Pending')
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 border border-orange-200 text-orange-600 text-xs font-black uppercase tracking-wider rounded-xl"><i class="bi bi-clock-history text-base animate-pulse"></i> Menunggu Validasi</span>
                @break
                @case('Rejected')
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-600 text-xs font-black uppercase tracking-wider rounded-xl"><i class="bi bi-x-circle-fill text-base"></i> Ditolak</span>
                @break
            @endswitch
        </div>
    </div>

    {{-- ISI DOKUMEN (LIST DATA MINIMALIS) --}}
    <div class="px-6 py-6 sm:px-10 sm:py-8">
        
        {{-- BAGIAN 1: IDENTITAS (STATIS DARI SISTEM) --}}
        <div class="mb-8">
            <h5 class="text-xs font-black text-gray-800 uppercase tracking-widest border-b border-gray-200 pb-2 mb-4"><i class="bi bi-person-vcard text-[#006633] mr-2"></i> Identitas Kepesertaan</h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                
                {{-- Pelapor / Ketua --}}
                <div>
    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Ketua Tim</div>
    <div class="text-sm font-bold text-gray-800">{{ $prestasi->user->name ?? '-' }}</div>
    <div class="text-xs font-medium text-gray-500 mt-1">
        {{ $prestasi->user->nim_nip ?? '-' }} • 
        <span class="text-[#006633]">{{ $prestasi->user->prodi?->nama_prodi ?? 'Prodi Belum Diset' }}</span> • 
        {{ $prestasi->user->prodi?->jurusan?->fakultas?->nama_fakultas ?? 'Fakultas Belum Diset' }}
    </div>
</div>

                {{-- Waktu Lapor --}}
                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Waktu Pelaporan</div>
                    <div class="text-sm font-medium text-gray-800">{{ $prestasi->created_at->translatedFormat('l, d F Y') }}</div>
                    <div class="text-xs font-medium text-gray-500 mt-1">{{ $prestasi->created_at->format('H:i') }} WIB</div>
                </div>

               @php
                    $dataDinamis = is_string($prestasi->data_dinamis) ? json_decode($prestasi->data_dinamis, true) : ($prestasi->data_dinamis ?? []);
                    $manualMembers = $dataDinamis['anggota_manual'] ?? [];
                @endphp

                @if($prestasi->anggota->count() > 0 || count($manualMembers) > 0)
                <div class="md:col-span-2">
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Anggota Tim</div>
                    <ul class="list-none space-y-2">
                        
                        {{-- FIX: Langsung panggil $anggota->name karena ini adalah model User! --}}
                        @foreach($prestasi->anggota as $anggota)
                            <li class="flex items-center gap-2 text-sm">
                                <i class="bi bi-person-fill text-gray-300"></i>
                                <span class="font-bold text-gray-800">{{ $anggota->name }}</span>
                                <span class="text-gray-400 text-xs">({{ $anggota->nim_nip }})</span>
                            </li>
                        @endforeach
                        
                        @foreach($manualMembers as $man)
                            <li class="flex items-center gap-2 text-sm">
                                <i class="bi bi-person text-orange-300"></i>
                                <span class="font-bold text-gray-800">{{ $man['nama'] }}</span>
                                <span class="text-orange-500 text-[10px] font-black uppercase tracking-widest px-1.5 py-0.5 bg-orange-50 rounded-md">Manual</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        {{-- BAGIAN 2: DATA PRESTASI (DINAMIS DARI JSON) --}}
        <div>
            <h5 class="text-xs font-black text-gray-800 uppercase tracking-widest border-b border-gray-200 pb-2 mb-4"><i class="bi bi-trophy text-[#006633] mr-2"></i> Rincian Kegiatan & Prestasi</h5>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                @foreach($fields as $field)
                    @php 
                        // AMBIL DATA DARI JSON BERDASARKAN ID
                        $nilai = $prestasi->data_dinamis[$field->id] ?? '-'; 
                        $isFile = $field->tipe === 'file';
                        $isArray = is_array($nilai);
                        
                        // Cek apakah field ini butuh ruang penuh (panjang)
                        $isFullWidth = in_array($field->tipe, ['textarea', 'file']) || ($isArray && count($nilai) > 3) || strlen(is_string($nilai) ? $nilai : '') > 50;
                    @endphp
                    
                    <div class="{{ $isFullWidth ? 'md:col-span-2' : '' }}">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">{{ $field->label }}</div>
                        
                        @if($isFile && $nilai !== '-')
                            <a href="{{ asset('storage/' . $nilai) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                                <i class="bi bi-file-earmark-arrow-down-fill"></i> Lihat Dokumen Lampiran
                            </a>
                        @elseif($isArray)
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                @foreach($nilai as $n)
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-md border border-gray-200">{{ $n }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm font-medium text-gray-900 leading-relaxed whitespace-pre-wrap">{{ $nilai ?: '-' }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>
</div>
@endsection