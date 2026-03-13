@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight">Master Data</h3>
        <p class="text-xs text-gray-400 font-medium">Pusat pengaturan parameter sistem SIARPRESTASI</p>
    </div>
    <span class="text-[10px] font-bold px-3 py-1 bg-gray-100 text-gray-500 rounded-full uppercase tracking-widest">Admin Control Panel</span>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="space-y-1.5">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4 mb-2">Akademik & Struktur</p>
        <button onclick="switchTab(event, 'tab-organisasi')" class="tab-link active-tab w-full flex items-center justify-between p-4 rounded-2xl bg-white border border-gray-100 shadow-sm text-sm font-bold transition-all">
            <div class="flex items-center gap-3"><i class="bi bi-diagram-3-fill"></i><span>Struktur Organisasi</span></div>
            <i class="bi bi-chevron-right text-[10px]"></i>
        </button>
        <button onclick="switchTab(event, 'tab-tahun')" class="tab-link w-full flex items-center justify-between p-4 rounded-2xl bg-white border border-gray-100 shadow-sm text-sm font-bold text-gray-500 transition-all">
            <div class="flex items-center gap-3"><i class="bi bi-calendar-check-fill"></i><span>Tahun Akademik</span></div>
            <i class="bi bi-chevron-right text-[10px]"></i>
        </button>

        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4 mt-6 mb-2">Parameter Prestasi</p>
        <button onclick="switchTab(event, 'tab-atribut')" class="tab-link w-full flex items-center justify-between p-4 rounded-2xl bg-white border border-gray-100 shadow-sm text-sm font-bold text-gray-500 transition-all">
            <div class="flex items-center gap-3"><i class="bi bi-trophy-fill"></i><span>Atribut Prestasi</span></div>
            <i class="bi bi-chevron-right text-[10px]"></i>
        </button>

        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4 mt-6 mb-2">Sumber Daya</p>
        <button onclick="switchTab(event, 'tab-dokumen')" class="tab-link w-full flex items-center justify-between p-4 rounded-2xl bg-white border border-gray-100 shadow-sm text-sm font-bold text-gray-500 transition-all">
            <div class="flex items-center gap-3"><i class="bi bi-file-earmark-arrow-down-fill"></i><span>Template Surat</span></div>
            <i class="bi bi-chevron-right text-[10px]"></i>
        </button>
    </div>

    <div class="md:col-span-3">

        <div id="tab-organisasi" class="tab-content block animate-fadeIn space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/20">
                    <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider">Daftar Fakultas</h4>
                    <button onclick="openModal('modal-fakultas')" class="text-[10px] font-black uppercase text-[#006633] hover:underline">+ Tambah Fakultas</button>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-gray-50">
                            @foreach(['Sains & Teknologi', 'Ekonomi & Bisnis'] as $f)
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $f }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700"><i class="bi bi-pencil-square"></i></button>
                                    <button class="text-red-400 hover:text-red-600"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/20">
                    <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider">Daftar Jurusan</h4>
                    <button onclick="openModal('modal-jurusan')" class="text-[10px] font-black uppercase text-[#006633] hover:underline">+ Tambah Jurusan</button>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-gray-50">
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">Teknik Informatika</div>
                                    <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Fakultas: Sains & Teknologi</div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700"><i class="bi bi-pencil-square"></i></button>
                                    <button class="text-red-400 hover:text-red-600"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/20">
                    <h4 class="text-xs font-black text-gray-700 uppercase tracking-wider">Daftar Program Studi</h4>
                    <button onclick="openModal('modal-prodi')" class="text-[10px] font-black uppercase text-[#006633] hover:underline">+ Tambah Prodi</button>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-gray-50">
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">S1 Sistem Informasi</div>
                                    <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest italic">Jurusan: Sistem Informasi (Otomatis: Sains & Teknologi)</div>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700"><i class="bi bi-pencil-square"></i></button>
                                    <button class="text-red-400 hover:text-red-600"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-tahun" class="tab-content hidden animate-fadeIn">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 max-w-lg">
                <h4 class="text-sm font-black text-gray-800 uppercase mb-6 tracking-wider flex items-center gap-2">
                    <i class="bi bi-calendar-check text-[#006633]"></i> Pengaturan Tahun Akademik
                </h4>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tahun Akademik Berjalan</label>
                        <input type="text" name="tahun_akademik" value="2023/2024 Ganjil" class="w-full mt-2 px-5 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-[#006633] focus:ring-0 outline-none transition-all">
                    </div>
                    <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-100 hover:bg-[#004d26] transition-all">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <div id="tab-atribut" class="tab-content hidden animate-fadeIn space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h4 class="text-xs font-black text-gray-700 uppercase mb-4 tracking-wider flex justify-between items-center">
                        Jenis Prestasi <button class="text-blue-500 font-black tracking-normal lowercase">+ add</button>
                    </h4>
                    <div class="space-y-2">
                        @foreach(['Akademik', 'Non-Akademik'] as $j)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <span class="text-xs font-bold text-gray-700">{{ $j }}</span>
                            <div class="flex gap-2">
                                <button class="text-blue-500"><i class="bi bi-pencil-square"></i></button>
                                <button class="text-red-400"><i class="bi bi-x-circle-fill"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h4 class="text-xs font-black text-gray-700 uppercase mb-4 tracking-wider flex justify-between items-center">
                        Tingkat Kompetisi <button class="text-blue-500 font-black tracking-normal lowercase">+ add</button>
                    </h4>
                    <div class="space-y-2 max-h-[250px] overflow-y-auto custom-scrollbar pr-2">
                        @foreach(['Internasional', 'Nasional', 'Provinsi', 'Kota/Kabupaten'] as $tk)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <span class="text-xs font-bold text-gray-700">{{ $tk }}</span>
                            <button class="text-red-400"><i class="bi bi-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="md:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                    <h4 class="text-xs font-black text-gray-700 uppercase mb-4 tracking-wider flex justify-between items-center">
                        Kategori Bidang <button class="text-blue-500 font-black tracking-normal lowercase">+ add</button>
                    </h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(['Olahraga', 'Seni', 'Karya Tulis', 'Keagamaan', 'Olimpiade', 'Sains'] as $kb)
                        <div class="flex justify-between items-center p-3 border border-gray-100 rounded-xl hover:border-green-300 transition-all">
                            <span class="text-[11px] font-bold text-gray-600">{{ $kb }}</span>
                            <button class="text-gray-300 hover:text-red-400"><i class="bi bi-x-lg"></i></button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-dokumen" class="tab-content hidden animate-fadeIn">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <h4 class="text-sm font-black text-gray-800 uppercase mb-8 tracking-wider">Repositori Template Dokumen</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div class="p-6 border-2 border-dashed border-gray-100 rounded-[2.5rem] flex flex-col items-center text-center group hover:border-blue-200 transition-all">
                        <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <p class="text-sm font-black text-gray-800 mb-1">Template Laporan</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6 italic">Hanya Butuh Satu File (.docx)</p>

                        <div class="flex flex-col w-full gap-2">
                            <label class="cursor-pointer py-3 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-100">
                                <i class="bi bi-upload mr-2"></i> Update Template
                                <input type="file" class="hidden">
                            </label>
                            <button class="py-3 bg-gray-50 text-gray-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100">
                                <i class="bi bi-download mr-2"></i> Download Current
                            </button>
                        </div>
                    </div>

                    <div class="p-6 border-2 border-dashed border-gray-100 rounded-[2.5rem] flex flex-col items-center text-center group hover:border-green-200 transition-all">
                        <div class="w-16 h-16 rounded-3xl bg-green-50 text-green-600 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform">
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                        </div>
                        <p class="text-sm font-black text-gray-800 mb-1">Template Rekap</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6 italic">Hanya Butuh Satu File (.xlsx)</p>

                        <div class="flex flex-col w-full gap-2">
                            <label class="cursor-pointer py-3 bg-[#006633] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#004d26] shadow-lg shadow-green-100">
                                <i class="bi bi-upload mr-2"></i> Update Template
                                <input type="file" class="hidden">
                            </label>
                            <button class="py-3 bg-gray-50 text-gray-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100">
                                <i class="bi bi-download mr-2"></i> Download Current
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-jurusan" class="fixed inset-0 z-[70] hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8">
        <h4 class="text-sm font-black text-gray-800 uppercase mb-6">Tambah Jurusan Baru</h4>
        <div class="space-y-4">
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">Nama Jurusan</label>
                <input type="text" class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none">
            </div>
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase ml-1 text-red-500">Pilih Fakultas (Keterikatan)</label>
                <select class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:border-[#006633] outline-none bg-white">
                    <option value="">-- Pilih Fakultas --</option>
                    <option value="1">Sains & Teknologi</option>
                    <option value="2">Ekonomi & Bisnis</option>
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-8">
            <button onclick="closeModal('modal-jurusan')" class="flex-1 py-3 text-xs font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all uppercase tracking-widest">Batal</button>
            <button class="flex-1 py-3 text-xs font-bold text-white bg-[#006633] rounded-xl shadow-lg shadow-green-100 uppercase tracking-widest">Simpan</button>
        </div>
    </div>
</div>

<style>
    .active-tab {
        border-color: #006633 !important;
        background-color: #f0fdf4 !important;
        color: #006633 !important;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }
</style>

<script>
    function switchTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) tabcontent[i].classList.add("hidden");

        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active-tab", "text-[#006633]", "bg-green-50");
            tablinks[i].classList.add("text-gray-500");
        }

        document.getElementById(tabName).classList.remove("hidden");
        evt.currentTarget.classList.add("active-tab");
        evt.currentTarget.classList.remove("text-gray-500");
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection