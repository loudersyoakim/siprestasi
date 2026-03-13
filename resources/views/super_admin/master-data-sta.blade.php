@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h3 class="text-xl font-black text-gray-800 tracking-tight uppercase">Pengaturan Sistem (STA)</h3>
    <p class="text-xs text-gray-400 font-medium tracking-wide">Tahun Akademik & Management Template Surat.</p>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="p-8">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Tahun Akademik Aktif</label>

        <div id="tahun-display" class="flex items-center gap-4">
            <h2 class="text-3xl font-black text-[#006633] tracking-tighter">
                {{ $tahun->tahun ?? 'BELUM DISET' }}
            </h2>
            <button onclick="toggleEditTahun(true)" class="p-2 bg-gray-50 text-gray-400 rounded-xl hover:bg-yellow-50 hover:text-yellow-600 transition-all">
                <i class="bi bi-pencil-fill"></i>
            </button>
        </div>

        <form id="tahun-edit-form" action="{{ route('admin.master-data.tahun.update', $tahun->id ?? 1) }}" method="POST" class="hidden">
            @csrf
            <div class="flex flex-wrap items-center gap-3">
                <input type="text" name="tahun" value="{{ $tahun->tahun ?? '' }}"
                    class="text-2xl font-black text-[#006633] bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#006633] px-4 py-2 w-64 uppercase">

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-[#006633] text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-green-100">
                        Simpan
                    </button>
                    <button type="button" onclick="toggleEditTahun(false)" class="px-6 py-3 bg-red-50 text-red-500 rounded-xl text-xs font-bold uppercase tracking-widest">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    @foreach($templates as $tp)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 flex flex-col gap-6 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center">
            <i class="bi {{ strtoupper($tp->nama_template) == 'REKAP' ? 'bi-file-earmark-excel' : 'bi-file-earmark-word' }} text-4xl text-gray-200"></i>
        </div>

        <div class="relative">
            <h4 class="text-sm font-black text-gray-800 uppercase tracking-tight">Template {{ $tp->nama_template }}</h4>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                Update Terakhir: <span class="text-gray-600">{{ $tp->updated_at->format('d M Y') }}</span>
            </p>
        </div>

        <div class="flex flex-col gap-3 mt-auto">
            <a href="{{ asset('storage/' . $tp->file_path) }}" target="_blank"
                class="flex items-center justify-center gap-2 py-3.5 bg-blue-50 text-blue-600 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all group">
                <i class="bi bi-cloud-arrow-down-fill text-base group-hover:animate-bounce"></i>
                <span>Download Template</span>
            </a>

            <form id="form-upload-{{ $tp->id }}" action="{{ route('admin.master-data.template.update', $tp->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="w-full flex flex-col items-center justify-center gap-2 py-3.5 border-2 border-dashed border-gray-100 text-gray-400 rounded-2xl text-[11px] font-black uppercase tracking-widest cursor-pointer hover:border-[#006633] hover:text-[#006633] transition-all">
                    <div id="label-text-{{ $tp->id }}" class="flex items-center gap-2">
                        <i class="bi bi-upload"></i>
                        <span>Upload Baru</span>
                    </div>

                    <div id="progress-container-{{ $tp->id }}" class="hidden w-full px-4">
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1">
                            <div id="progress-bar-{{ $tp->id }}" class="bg-[#006633] h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p id="progress-text-{{ $tp->id }}" class="text-[9px] text-center text-[#006633] font-black">0%</p>
                    </div>

                    <input type="file" name="file" class="hidden" onchange="uploadFileAjax(this, '{{ $tp->id }}')">
                </label>
            </form>

            <p class="text-center text-[9px] font-bold text-gray-300 uppercase tracking-tighter">
                Wajib Format: {{ strtoupper($tp->nama_template) == 'REKAP' ? '.XLSX / .XLS' : '.DOCX / .PDF' }}
            </p>
        </div>
    </div>
    @endforeach
</div>

<script>
    function toggleEditTahun(isEdit) {
        const display = document.getElementById('tahun-display');
        const form = document.getElementById('tahun-edit-form');

        if (isEdit) {
            display.classList.add('hidden');
            form.classList.remove('hidden');
        } else {
            display.classList.remove('hidden');
            form.classList.add('hidden');
        }
    }

    function uploadFileAjax(input, id) {
        const file = input.files[0];
        if (!file) return;

        const form = document.getElementById(`form-upload-${id}`);
        const labelText = document.getElementById(`label-text-${id}`);
        const progressContainer = document.getElementById(`progress-container-${id}`);
        const progressBar = document.getElementById(`progress-bar-${id}`);
        const progressText = document.getElementById(`progress-text-${id}`);

        // Persiapan FormData
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Tampilkan Progress, Sembunyikan Label
        labelText.classList.add('hidden');
        progressContainer.classList.remove('hidden');

        // Buat Request AJAX
        const xhr = new XMLHttpRequest();

        // Track Progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                progressText.innerText = percent + '%';
            }
        });

        // Selesai Upload
        xhr.onload = function() {
            if (xhr.status === 200) {
                progressText.innerText = "SELESAI! REFRESHING...";
                // Refresh halaman untuk melihat perubahan
                window.location.reload();
            } else {
                alert('Upload Gagal! Pastikan format file benar.');
                window.location.reload();
            }
        };

        xhr.open('POST', form.action, true);
        xhr.send(formData);
    }
</script>
@endsection