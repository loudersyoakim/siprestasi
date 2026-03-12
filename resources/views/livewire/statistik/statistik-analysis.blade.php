<div wire:poll.1s>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1 flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Akun</p>
                <h4 class="text-xl font-black text-gray-900 leading-none mt-1">{{ $total_akun }}</h4>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1 flex items-center gap-4">
            <div class="w-16 h-16 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-200">
                <i class="bi bi-person-plus-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Akun Baru</p>
                <h4 class="text-xl font-black text-gray-900 leading-none mt-1">{{ $akun_baru }}</h4>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1 flex items-center gap-4">
            <div class="w-16 h-16 bg-[#006633] rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-200">
                <i class="bi bi-trophy-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Prestasi</p>
                <h4 class="text-xl font-black text-gray-900 leading-none mt-1">{{ $total_prestasi }}</h4>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1 flex items-center gap-4">
            <div class="w-16 h-16 bg-yellow-400 rounded-xl flex items-center justify-center text-white shadow-lg shadow-yellow-100">
                <i class="bi bi-shield-lock-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Perlu Validasi</p>
                <h4 class="text-xl font-black text-gray-900 leading-none mt-1">{{ $perlu_validasi }}</h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Tren Input Prestasi Bulanan</h4>
            </div>
            <div wire:ignore class="p-6 flex-1 min-h-[350px]" id="area-chart-rekap"></div>

            <div class="grid grid-cols-4 border-t border-gray-50 divide-x divide-gray-50">
                <div class="p-4 text-center">
                    @if($tren_input > 0)
                    <p class="text-[10px] font-bold text-green-500 mb-1">▲ {{ abs($tren_input) }}%</p>
                    @elseif($tren_input < 0)
                        <p class="text-[10px] font-bold text-red-500 mb-1">▼ {{ abs($tren_input) }}%</p>
                        @else
                        <p class="text-[10px] font-bold text-blue-500 mb-1">◀ 0%</p>
                        @endif
                        <h5 class="text-sm font-black text-gray-800">{{ $input_data }}</h5>
                        <p class="text-[9px] text-gray-400 uppercase">Input Data</p>
                </div>

                <div class="p-4 text-center">
                    @if($tren_validasi > 0)
                    <p class="text-[10px] font-bold text-green-500 mb-1">▲ {{ abs($tren_validasi) }}%</p>
                    @elseif($tren_validasi < 0)
                        <p class="text-[10px] font-bold text-red-500 mb-1">▼ {{ abs($tren_validasi) }}%</p>
                        @else
                        <p class="text-[10px] font-bold text-blue-500 mb-1">◀ 0%</p>
                        @endif
                        <h5 class="text-sm font-black text-gray-800">{{ $validasi_data }}</h5>
                        <p class="text-[9px] text-gray-400 uppercase">Validasi</p>
                </div>

                <div class="p-4 text-center">
                    @if($tren_disetujui > 0)
                    <p class="text-[10px] font-bold text-green-500 mb-1">▲ {{ abs($tren_disetujui) }}%</p>
                    @elseif($tren_disetujui < 0)
                        <p class="text-[10px] font-bold text-red-500 mb-1">▼ {{ abs($tren_disetujui) }}%</p>
                        @else
                        <p class="text-[10px] font-bold text-blue-500 mb-1">◀ 0%</p>
                        @endif
                        <h5 class="text-sm font-black text-gray-800">{{ $disetujui }}</h5>
                        <p class="text-[9px] text-gray-400 uppercase">Disetujui</p>
                </div>

                <div class="p-4 text-center">
                    @if($tren_ditolak > 0)
                    <p class="text-[10px] font-bold text-red-500 mb-1">▲ {{ abs($tren_ditolak) }}%</p>
                    @elseif($tren_ditolak < 0)
                        <p class="text-[10px] font-bold text-green-500 mb-1">▼ {{ abs($tren_ditolak) }}%</p>
                        @else
                        <p class="text-[10px] font-bold text-blue-500 mb-1">◀ 0%</p>
                        @endif
                        <h5 class="text-sm font-black text-gray-800">{{ $ditolak }}</h5>
                        <p class="text-[9px] text-gray-400 uppercase">Ditolak</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider mb-4">Distribusi Tingkat</h4>
            <div wire:ignore id="chart3d" class="w-full flex-1 min-h-[350px]"></div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-50 bg-gray-50/30">
            <h4 class="text-sm font-black text-gray-700 uppercase tracking-wider">Perbandingan Prestasi Akademik vs Non-Akademik</h4>
        </div>
        <div wire:ignore class="p-6 min-h-[300px]" id="bar-chart-kategori"></div>
    </div>
</div>