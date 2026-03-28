@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Validasi Prestasi</h3>
    </div>
</div>

{{-- SISTEM TAB --}}
<div class="flex gap-2 mb-6 p-1.5 bg-gray-100/50 border border-gray-200 rounded-2xl w-fit">
    <a href="{{ route('prestasi.validasi', ['tab' => 'pending']) }}" 
       class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab == 'pending' ? 'bg-white text-[#006633] shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="bi bi-hourglass-split"></i>
        <span>Antrean</span>
        @if($pending->total() > 0)
            <span class="ml-1 px-1.5 py-0.5 bg-orange-500 text-white text-[10px] rounded-full">{{ $pending->total() }}</span>
        @endif
    </a>
    <a href="{{ route('prestasi.validasi', ['tab' => 'riwayat']) }}" 
       class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab == 'riwayat' ? 'bg-white text-[#006633] shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="bi bi-shield-check"></i>
        <span>Riwayat Validasi</span>
    </a>
</div>

{{-- KONTEN TAB: ANTREAN PENDING --}}
@if($tab == 'pending')
<form action="{{ route('prestasi.validasi-massal') }}" method="POST" id="form-massal">
    @csrf @method('PATCH')

    {{-- Tombol Aksi Massal --}}
    <div class="mb-4 flex justify-between items-center bg-green-50/80 p-4 rounded-2xl border border-green-200 hidden bulk-container animate-in fade-in zoom-in duration-300">
        <div class="flex items-center gap-3 text-sm font-bold text-[#006633]">
            <i class="bi bi-check2-circle text-lg"></i>
            <span><span id="selected-count">0</span> Data Terpilih</span>
        </div>
        <button type="submit" onclick="return confirm('Setujui semua data terpilih secara massal?')"
            class="bg-[#006633] text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-md hover:bg-[#004d26] transition-all">
            Validasi Massal
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-5 w-10"><input type="checkbox" id="check-all" class="rounded border-gray-300 text-[#006633] focus:ring-[#006633]"></th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Data Diri</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori Laporan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pending as $item)
                    <tr class="hover:bg-green-50/20 transition-colors">
                        <td class="px-6 py-5"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="check-item rounded border-gray-300 text-[#006633]"></td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-800 text-sm mb-1">{{ $item->user->name ?? 'User Terhapus' }}</div>
                            <div class="text-[10px] font-black text-[#006633] uppercase tracking-widest">NIM: {{ $item->user->nim_nip ?? '-' }}</div>
                            
                            @php
                                $dataDinamis = is_string($item->data_dinamis) ? json_decode($item->data_dinamis, true) : ($item->data_dinamis ?? []);
                                $manualMembers = $dataDinamis['anggota_manual'] ?? [];
                            @endphp
                            
                            @if($item->anggota->count() > 0 || (is_array($manualMembers) && count($manualMembers) > 0))
                            <div class="mt-2 flex flex-wrap gap-1">
                                <span class="text-[9px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded font-bold border border-gray-200">
                                    <i class="bi bi-people-fill mr-1"></i>+{{ $item->anggota->count() + count($manualMembers) }} Mahasiswa Lainnya
                                </span>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-gray-700 block">{{ $item->formPrestasi->nama_form }}</span>
                            <span class="text-[10px] text-gray-400 font-medium">{{ $item->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('prestasi.validasi-show', $item->id) }}" 
   class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
   title="Verifikasi Dokumen">
    <i class="bi bi-file-earmark-text"></i>
</a>
                                <button type="button" onclick="approveSingle({{ $item->id }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Terima/Sahkan"><i class="bi bi-patch-check"></i></button>
                                <button type="button" onclick="openRejectModal({{ $item->id }}, '{{ addslashes($item->user->name) }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Tolak Laporan"><i class="bi bi-x-square"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <i class="bi bi-shield-slash text-5xl text-gray-200 mb-3 block"></i>
                            <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest">Antrean Bersih</h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $pending->links() }}</div>
</form>

{{-- KONTEN TAB: RIWAYAT VALIDASI --}}
@else
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Data Diri</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Terakhir</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Penolakan</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi Perubahan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($validated as $item)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="font-bold text-gray-800 text-sm">{{ $item->user->name }}</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $item->formPrestasi->nama_form }}</div>
                    </td>
                    <td class="px-6 py-5">
                        @if($item->status == 'Approved')
                            <span class="px-3 py-1 bg-green-50 text-green-700 text-[10px] font-black uppercase rounded-lg border border-green-100 flex items-center w-fit gap-1"><i class="bi bi-patch-check-fill"></i> Disetujui</span>
                        @else
                            <span class="px-3 py-1 bg-red-50 text-red-700 text-[10px] font-black uppercase rounded-lg border border-red-100 flex items-center w-fit gap-1"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <div class="text-xs text-gray-500 italic max-w-[200px] truncate" title="{{ $item->pesan_revisi }}">{{ $item->pesan_revisi ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center justify-center gap-2">
                            {{-- TOMBOL KEMBALIKAN KE PENDING --}}
                            <button type="button" onclick="pendingSingle({{ $item->id }})" 
                                class="px-3 py-1.5 bg-orange-50 text-orange-600 border border-orange-100 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-orange-600 hover:text-white transition-all shadow-sm"
                                title="Kembalikan ke Antrean Pending">
                                Pending
                            </button>

                            {{-- Ganti Langsung ke Lawannya (Approve <-> Reject) --}}
                            <button type="button" onclick="{{ $item->status == 'Approved' ? "openRejectModal($item->id, '".$item->user->name."')" : "approveSingle($item->id)" }}" 
                                class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-lg border transition-all shadow-sm {{ $item->status == 'Approved' ? 'border-red-100 text-red-600 hover:bg-red-600 hover:text-white' : 'border-green-100 text-green-600 hover:bg-green-600 hover:text-white' }}">
                                {{ $item->status == 'Approved' ? 'Tolak' : 'Setujui' }}
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center text-gray-400 italic">Belum ada riwayat validasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $validated->links() }}</div>
@endif

{{-- MODAL TOLAK --}}
<div id="modal-reject" class="fixed inset-0 z-[80] hidden overflow-y-auto px-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl relative z-10 overflow-hidden transform scale-100 transition-transform">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-red-50/50">
                <h4 class="font-black text-red-600 uppercase tracking-tight flex items-center gap-2 text-sm">
                    <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Penolakan
                </h4>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>

            <form id="form-reject" method="POST" class="p-6 sm:p-8">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="Rejected">
                
                <div class="mb-6 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Mahasiswa:</p>
                    <p id="reject-prestasi-name" class="font-bold text-gray-800 text-sm"></p>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="pesan_revisi" rows="4" required class="w-full px-4 py-3 border border-gray-200 bg-white rounded-2xl text-sm outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all shadow-inner" placeholder="Contoh: Dokumen sertifikat tidak terbaca atau link tidak valid..."></textarea>
                </div>
                
                <div class="pt-8 flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-3.5 text-sm font-bold text-gray-600 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3.5 text-sm font-bold text-white bg-red-600 rounded-2xl hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Tolak Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FORMS INVISIBLE --}}
<form id="form-approve-single" method="POST" class="hidden">@csrf @method('PATCH')<input type="hidden" name="status" value="Approved"></form>
<form id="form-pending-single" method="POST" class="hidden">@csrf @method('PATCH')<input type="hidden" name="status" value="Pending"></form>

<script>
    const checkAll = document.getElementById('check-all');
    const checkItems = document.querySelectorAll('.check-item');
    const bulkContainer = document.querySelector('.bulk-container');
    const selectedCount = document.getElementById('selected-count');

    function toggleBulkActions() {
        const checked = document.querySelectorAll('.check-item:checked');
        if (checked.length > 0) {
            bulkContainer.classList.remove('hidden');
            selectedCount.textContent = checked.length;
        } else {
            bulkContainer.classList.add('hidden');
        }
    }

    checkAll?.addEventListener('change', function() {
        checkItems.forEach(item => item.checked = this.checked);
        toggleBulkActions();
    });

    checkItems.forEach(item => {
        item.addEventListener('change', toggleBulkActions);
    });

    function openRejectModal(id, namaPelapor) {
        const modal = document.getElementById('modal-reject');
        const form = document.getElementById('form-reject');
        const nameDisplay = document.getElementById('reject-prestasi-name');
        let baseUrl = "{{ route('prestasi.status-update', ':id') }}";
        form.action = baseUrl.replace(':id', id);
        nameDisplay.textContent = namaPelapor;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('modal-reject').classList.add('hidden');
    }

    function approveSingle(id) {
        if(confirm('Apakah Anda yakin ingin menyetujui laporan prestasi ini?')) {
            const form = document.getElementById('form-approve-single');
            let baseUrl = "{{ route('prestasi.status-update', ':id') }}";
            form.action = baseUrl.replace(':id', id);
            form.submit();
        }
    }

    function pendingSingle(id) {
        if(confirm('Kembalikan data ini ke Antrean Validasi? Status persetujuan saat ini akan dihapus.')) {
            const form = document.getElementById('form-pending-single');
            let baseUrl = "{{ route('prestasi.status-update', ':id') }}";
            form.action = baseUrl.replace(':id', id);
            form.submit();
        }
    }
</script>
@endsection