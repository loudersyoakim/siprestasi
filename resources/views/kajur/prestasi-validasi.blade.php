@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Validasi Prestasi</h3>
        <p class="text-sm text-gray-500 mt-1">Daftar capaian mahasiswa yang menunggu persetujuan Anda.</p>
    </div>

    <div class="bg-orange-50 text-orange-600 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 border border-orange-200">
        <i class="bi bi-clock-history animate-pulse"></i>
        <span>{{ $pending->total() }} Menunggu Validasi</span>
    </div>
</div>

<form action="{{ route('kajur.prestasi.validasi-massal') }}" method="POST" id="form-massal">
    @csrf
    @method('PATCH')

    {{-- Tombol Aksi Massal (Hanya Muncul Jika Ada yang Dicentang) --}}
    <div class="mb-4 flex justify-between items-center bg-green-50/50 p-4 rounded-2xl border border-green-100 hidden bulk-container transition-all">
        <span class="text-xs font-bold text-[#006633]"><span id="selected-count">0</span> Data Terpilih</span>
        <button type="submit" name="action" value="approve" onclick="return confirm('Setujui semua prestasi yang dipilih?')"
            class="bg-[#006633] text-white px-5 py-2 rounded-xl text-xs font-bold shadow-md hover:bg-[#004d26] transition-all flex items-center gap-2">
            <i class="bi bi-check-all text-lg"></i> Terima Semua Terpilih
        </button>
    </div>

    {{-- TABEL UTAMA --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 border-b border-gray-50 w-10">
                            <input type="checkbox" id="check-all" class="rounded border-gray-300 text-[#006633] focus:ring-[#006633]">
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">Mahasiswa</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">Detail Prestasi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">Waktu</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pending as $item)
                    <tr class="hover:bg-green-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="check-item rounded border-gray-300 text-[#006633] focus:ring-[#006633]">
                        </td>

                        {{-- Kolom Mahasiswa --}}
                        <td class="px-6 py-5">
                            {{-- BUNGKUS DENGAN DIV SCROLLABLE --}}
                            <div class="flex flex-col gap-1.5 max-h-[80px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($item->mahasiswa as $mhs)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-[#006633]/10 text-[#006633] rounded-full flex items-center justify-center text-[10px] font-bold shrink-0">
                                        {{ substr($mhs->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col min-w-0"> {{-- min-w-0 penting agar truncate bekerja --}}
                                        <span class="text-xs font-bold text-gray-800 truncate" title="{{ $mhs->name }}">{{ $mhs->name }}</span>
                                        <span class="text-[9px] text-gray-500">NIM: {{ $mhs->nim_nip }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            {{-- Indikator jumlah kalau lebih dari 3 orang biar kelihatan jelas --}}
                            @if($item->mahasiswa->count() > 3)
                            <div class="mt-2 text-[9px] text-center font-bold text-gray-400 uppercase tracking-widest border-t border-gray-100 pt-2">
                                Total: {{ $item->mahasiswa->count() }} Anggota (Scroll)
                            </div>
                            @endif
                        </td>

                        {{-- Kolom Detail --}}
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-bold text-gray-800 line-clamp-1">{{ $item->nama_prestasi }}</span>
                                <div class="flex items-center gap-2 text-[9px] font-black uppercase tracking-tight">
                                    <span class="text-[#006633]">{{ $item->tingkat->nama_tingkat ?? 'N/A' }}</span>
                                    <span class="text-gray-300">&bull;</span>
                                    <span class="text-orange-600">{{ $item->kategori->nama_kategori ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Waktu --}}
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-700 font-bold">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                <span class="text-[9px] text-gray-400">ID: #{{ $item->id }}</span>
                            </div>
                        </td>

                        {{-- Kolom Aksi Satuan --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ asset('storage/' . $item->sertifikat) }}" target="_blank"
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all"
                                    title="Cek Sertifikat">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </a>
                                <button type="button" onclick="approveSingle({{ $item->id }})"
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all" title="Setujui">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button type="button" onclick="openRejectModal({{ $item->id }}, '{{ addslashes($item->nama_prestasi) }}')"
                                    class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all" title="Tolak">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="bi bi-clipboard-check text-4xl text-gray-200 mb-2"></i>
                                <h4 class="text-base font-black text-gray-800">Semua Data Beres!</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

{{-- Pagination --}}
<div class="mt-6 flex justify-center">
    {{ $pending->links() }}
</div>

{{-- MODAL TOLAK --}}
<div id="modal-reject" class="fixed inset-0 z-[80] hidden overflow-y-auto px-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeRejectModal()"></div>
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl relative z-10 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-red-50/30">
                <h4 class="font-black text-red-600 uppercase tracking-tight flex items-center gap-2 text-xs">
                    <i class="bi bi-exclamation-triangle-fill"></i> Tolak Prestasi
                </h4>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>

            <form id="form-reject" method="POST" class="p-8">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <div class="mb-6 text-xs">
                    <p class="text-gray-400 uppercase font-black tracking-widest mb-1">Nama Prestasi:</p>
                    <p id="reject-prestasi-name" class="font-bold text-gray-700"></p>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alasan Penolakan</label>
                    <textarea name="alasan_ditolak" rows="3" required class="w-full px-4 py-3 border border-gray-100 bg-gray-50 rounded-2xl text-xs outline-none focus:ring-1 focus:ring-red-500" placeholder="Sebutkan alasan penolakan..."></textarea>
                </div>
                <div class="pt-6 flex gap-2">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 text-xs font-bold text-gray-500 bg-gray-50 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-xs font-bold text-white bg-red-600 rounded-xl">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<form id="form-approve-single" method="POST" class="hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="approved">
</form>

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

    function openRejectModal(id, namaPrestasi) {
        const modal = document.getElementById('modal-reject');
        const form = document.getElementById('form-reject');
        const nameDisplay = document.getElementById('reject-prestasi-name');
        let baseUrl = "{{ route('kajur.prestasi.status-update', ':id') }}";
        form.action = baseUrl.replace(':id', id);
        nameDisplay.textContent = namaPrestasi;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('modal-reject').classList.add('hidden');
    }

    function approveSingle(id) {
        if(confirm('Setujui prestasi?')) {
            const form = document.getElementById('form-approve-single');
            let baseUrl = "{{ route('kajur.prestasi.status-update', ':id') }}";
            form.action = baseUrl.replace(':id', id);
            form.submit();
        }
    }
</script>
@endsection