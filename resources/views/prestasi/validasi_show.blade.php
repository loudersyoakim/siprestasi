@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('prestasi.validasi') }}" class="text-sm font-bold text-gray-400 hover:text-gray-700 transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <h3 class="text-2xl font-black text-gray-800 tracking-tight">Lembar Verifikasi Prestasi</h3>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-10">
    {{-- Header Status --}}
    <div class="px-8 py-6 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">ID Laporan: #{{ $prestasi->id }}</span>
            <h4 class="text-lg font-bold text-gray-800">{{ $prestasi->formPrestasi->nama_form }}</h4>
        </div>
        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
            <i class="bi bi-info-circle text-orange-500"></i>
            <span class="text-xs font-bold text-gray-600 uppercase">Status: {{ $prestasi->status }}</span>
        </div>
    </div>

    <div class="px-8 py-8">
        {{-- Section 1: Identitas --}}
        <div class="mb-10">
            <h5 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2 mb-6">Informasi Mahasiswa</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Nama Lengkap</label>
                    <div class="text-sm font-semibold text-gray-800">{{ $prestasi->user->name }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">NIM: {{ $prestasi->user->nim_nip }}</div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Program Studi</label>
                    <div class="text-sm font-semibold text-gray-800">{{ $prestasi->user->prodi?->nama_prodi ?? '-' }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $prestasi->user->prodi?->jurusan?->fakultas?->nama_fakultas ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Section 2: Data Laporan --}}
        <div>
            <h5 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2 mb-6">Rincian Dokumen & Bukti</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                @foreach($fields as $field)
                    @php 
                        $nilai = $prestasi->data_dinamis[$field->id] ?? '-'; 
                        $isFile = $field->tipe === 'file';
                        $isFullWidth = in_array($field->tipe, ['textarea', 'file']) || strlen(is_string($nilai) ? $nilai : '') > 80;
                    @endphp
                    <div class="{{ $isFullWidth ? 'md:col-span-2' : '' }}">
                        <label class="text-[10px] font-bold text-gray-500 uppercase block mb-2">{{ $field->label }}</label>
                        @if($isFile && $nilai !== '-')
                            <a href="{{ asset('storage/' . $nilai) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all">
                                <i class="bi bi-file-earmark-arrow-down"></i> Lihat Lampiran Dokumen
                            </a>
                        @elseif(is_array($nilai))
                            <div class="flex flex-wrap gap-2">
                                @foreach($nilai as $n)
                                    <span class="px-2.5 py-1 bg-gray-50 text-gray-700 text-xs font-semibold rounded border border-gray-200">{{ $n }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-800 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">
                                {!! nl2br(e($nilai)) !!}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Panel Keputusan Validator --}}
    <div class="px-8 py-6 bg-gray-800 flex flex-wrap items-center justify-center gap-4">
        <button type="button" onclick="actionUpdate('Pending')" class="px-6 py-2.5 bg-gray-700 text-orange-400 border border-gray-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-gray-600 transition-all">
            Tangguhkan
        </button>

        <button type="button" onclick="openRejectModal()" class="px-6 py-2.5 bg-gray-700 text-red-400 border border-gray-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-red-900/30 transition-all">
            Tolak Laporan
        </button>

        <button type="button" onclick="actionUpdate('Approved')" class="px-8 py-2.5 bg-[#006633] text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-green-800 transition-all">
            Sahkan & Setujui
        </button>
    </div>
</div>

{{-- MODAL REJECT --}}
<div id="modal-reject" class="fixed inset-0 z-[80] hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeRejectModal()"></div>
        <div class="bg-white w-full max-w-md rounded-xl shadow-xl relative z-10 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Alasan Penolakan</h4>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('prestasi.status-update', $prestasi->id) }}" method="POST" class="p-6">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Rejected">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block">Catatan untuk Mahasiswa</label>
                    <textarea name="pesan_revisi" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-red-500 outline-none" placeholder="Tulis alasan penolakan..."></textarea>
                </div>
                <div class="pt-6 flex gap-2">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-2 text-xs font-bold text-gray-500 bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-red-600 rounded-lg">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="form-update-status" method="POST" class="hidden">@csrf @method('PATCH')<input type="hidden" name="status" id="input-status"></form>

<script>
    function openRejectModal() { document.getElementById('modal-reject').classList.remove('hidden'); }
    function closeRejectModal() { document.getElementById('modal-reject').classList.add('hidden'); }

    function actionUpdate(status) {
        if(confirm(`Konfirmasi perubahan status menjadi ${status}?`)) {
            const form = document.getElementById('form-update-status');
            document.getElementById('input-status').value = status;
            form.action = "{{ route('prestasi.status-update', $prestasi->id) }}";
            form.submit();
        }
    }
</script>
@endsection