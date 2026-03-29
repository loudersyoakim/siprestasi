@extends('layouts.app')

@section('content')

{{-- 1. NOTIFIKASI TOAST --}}
@if(session('success'))
<div id="toast-success" class="fixed top-6 right-6 z-[200] animate-in slide-in-from-top-5 duration-500">
    <div class="flex items-center gap-3 bg-white border border-green-100 p-4 rounded-2xl shadow-xl min-w-[300px]">
        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
            <i class="bi bi-check-lg text-xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-2xl font-black text-gray-800 tracking-tight">Konfigurasi Disimpan</h3>
            <p class="text-xs text-gray-500">{{ session('success') }}</p>
        </div>
        <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
<script>setTimeout(() => { document.getElementById('toast-success')?.remove(); }, 4000);</script>
@endif

{{-- 2. HEADER HALAMAN --}}
<div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
    <div>
        <h3 class="text-xl font-black text-gray-800 tracking-tight mt-2">Konfigurasi Beranda</h3>
    </div>
    <div class="flex gap-2">
        <button type="button" onclick="openWidgetModal()" class="px-5 py-3 bg-white border border-gray-200 text-gray-700 text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg text-[#006633]"></i> Tambah Widget
        </button>
        <button type="submit" form="form-builder" class="px-8 py-3 bg-[#006633] text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-green-800 transition-all shadow-lg shadow-green-900/20 flex items-center gap-2">
            <i class="bi bi-save"></i> Simpan Publikasi
        </button>
    </div>
</div>

{{-- 3. AREA KANVAS BUILDER --}}
<form id="form-builder" action="{{ route('konten.landing.update') }}" method="POST">
    @csrf @method('PATCH')
    
    <div id="widget-container" class="space-y-10">
        {{-- Placeholder jika kosong --}}
        <div id="empty-widget" class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2.5rem] h-80 flex flex-col items-center justify-center text-gray-400 w-full transition-all">
            <i class="bi bi-collection text-4xl mb-3"></i>
            <h4 class="text-sm font-bold text-gray-600 uppercase tracking-widest">Belum ada widget terpilih</h4>
            <p class="text-xs mt-1">Klik "Tambah Widget" untuk mulai membangun tampilan.</p>
        </div>
    </div>
</form>

{{-- 4. MODAL KATALOG WIDGET --}}
<div id="modal-widget" class="fixed inset-0 z-[100] hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" onclick="closeWidgetModal()"></div>

        <div class="relative inline-block w-full max-w-4xl overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle border border-gray-100">
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tight">Katalog Komponen</h3>
                <button type="button" onclick="closeWidgetModal()" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg text-lg"></i></button>
            </div>
            
            <div class="p-8 grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <button type="button" onclick="buildWidget('leaderboard')" class="text-left p-6 rounded-2xl border-2 border-gray-100 hover:border-[#006633] transition-all group">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center text-xl"><i class="bi bi-trophy-fill"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm group-hover:text-[#006633] uppercase">Top Leaderboard</h4>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Peringkat Mahasiswa</span>
                        </div>
                    </div>
                </button>

                <button type="button" onclick="buildWidget('chart_distribusi')" class="text-left p-6 rounded-2xl border-2 border-gray-100 hover:border-[#006633] transition-all group">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl"><i class="bi bi-pie-chart-fill"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm group-hover:text-[#006633] uppercase">Grafik Sebaran (3D)</h4>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pie / Donut / Batang 3D</span>
                        </div>
                    </div>
                </button>

                <button type="button" onclick="buildWidget('chart_tren')" class="text-left p-6 rounded-2xl border-2 border-gray-100 hover:border-[#006633] transition-all group">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl"><i class="bi bi-graph-up-arrow"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm group-hover:text-[#006633] uppercase">Grafik Tren Bulanan</h4>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pertumbuhan Berkala</span>
                        </div>
                    </div>
                </button>

                <button type="button" onclick="buildWidget('counter')" class="text-left p-6 rounded-2xl border-2 border-gray-100 hover:border-[#006633] transition-all group">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-xl"><i class="bi bi-123"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm group-hover:text-[#006633] uppercase">Statistik Angka</h4>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Ringkasan Data</span>
                        </div>
                    </div>
                </button>

                {{-- WIDGET BARU: FEED TERBARU --}}
                <button type="button" onclick="buildWidget('feed_terbaru')" class="text-left p-6 rounded-2xl border-2 border-gray-100 hover:border-[#006633] transition-all group sm:col-span-2">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-xl"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm group-hover:text-[#006633] uppercase">Feed Pembaruan</h4>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Prestasi & Berita Terbaru</span>
                        </div>
                    </div>
                </button>

            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width:4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background:rgba(0,102,51,0.2); border-radius:10px; }
</style>

{{-- 5. SCRIPTS --}}
<script src="{{ asset('js/echarts.min.js') }}"></script>
<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="{{ asset('js/highcharts-3d.js') }}"></script>

<script>
    let widgetCount = 0;
    const chartInstances = {}; 

    // DATA DUMMY PREVIEW
    const dummyData = {
        distribusi: { labels: ['Contoh 1', 'Contoh 2', 'Contoh 3', 'Contoh 4'], data: [450, 300, 200, 150] },
        tren: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'], data: [15, 25, 18, 40, 32] },
        feed_terbaru: [
            { type: 'Prestasi', title: 'Contoh Mahasiswa 1 meraih Medali Emas', time: '1 Jam yang lalu', color: 'text-[#006633] bg-green-50' },
            { type: 'Pengumuman', title: 'Pendaftaran Kompetisi Nasional Telah Dibuka', time: '3 Jam yang lalu', color: 'text-blue-600 bg-blue-50' },
            { type: 'Berita', title: 'Fakultas MIPA Borong Prestasi di Tahun 2025', time: '1 Hari yang lalu', color: 'text-orange-600 bg-orange-50' },
            { type: 'Prestasi', title: 'Contoh Mahasiswa 2 Lolos Semifinal Internasional', time: '2 Hari yang lalu', color: 'text-[#006633] bg-green-50' }
        ]
    };

    function openWidgetModal() { document.getElementById('modal-widget').classList.remove('hidden'); }
    function closeWidgetModal() { document.getElementById('modal-widget').classList.add('hidden'); }

    function removeWidget(id) {
        document.getElementById(`widget-${id}`).remove();
        if(chartInstances[id]) { 
            if(chartInstances[id].dispose) chartInstances[id].dispose();
            else if(chartInstances[id].destroy) chartInstances[id].destroy();
            delete chartInstances[id]; 
        }
        if(document.getElementById('widget-container').children.length === 1) document.getElementById('empty-widget').style.display = 'flex';
    }

    // FUNGSI UTAMA BUILDER
    function buildWidget(type, savedData = null) {
        closeWidgetModal();
        document.getElementById('empty-widget').style.display = 'none';
        widgetCount++;
        const wId = widgetCount;
        const container = document.getElementById('widget-container');

        let titleStr = savedData ? savedData.title : ""; 
        let iconStr = ""; let previewHtml = ""; let configHtml = "";

        if(type === 'leaderboard') {
            if(!titleStr) titleStr = "Peringkat Mahasiswa Teraktif"; 
            iconStr = "bi-trophy-fill text-yellow-600 bg-yellow-50";
            previewHtml = `
                <div class="flex flex-col gap-3 w-full h-full">
                    ${[1,2,3].map(i => `
                        <div class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm w-full">
                            <div class="w-8 h-8 rounded-full bg-[#006633] text-white flex items-center justify-center font-black text-xs shadow-md">0${i}</div>
                            <div class="w-12 h-12 rounded-full bg-gray-100 border-2 border-white shadow-inner overflow-hidden"><img src="https://ui-avatars.com/api/?name=User+Simulasi&background=006633&color=fff" class="w-full h-full"></div>
                            <div class="flex-1 min-w-0"><h5 class="text-sm font-black text-gray-900 uppercase leading-none truncate">Contoh Nama Mahasiswa ${i}</h5><span class="text-[9px] font-bold text-gray-400 uppercase mt-1 block tracking-widest truncate">Program Studi Terkait ${i}</span></div>
                        </div>
                    `).join('')}
                </div>`;
            configHtml = `<div class="mt-4"><label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Batas Data</label><input type="number" name="landing_widgets[${wId}][limit]" value="${savedData?.limit || 5}" class="w-full px-4 py-2 border rounded-xl text-sm font-bold focus:ring-1 focus:ring-[#006633] outline-none"></div>`;
        }
        else if(type === 'chart_distribusi') {
            if(!titleStr) titleStr = "Sebaran Prestasi"; 
            iconStr = "bi-pie-chart-fill text-blue-600 bg-blue-50";
            previewHtml = `<div id="canvas-${wId}" class="w-full h-full min-h-[300px]"></div>`;
            configHtml = `
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Sumber Data</label>
                        <select name="landing_widgets[${wId}][data_source]" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm outline-none">
                            <option value="fakultas" ${savedData?.data_source == 'fakultas' ? 'selected' : ''}>Berdasarkan Fakultas</option>
                            <option value="prodi" ${savedData?.data_source == 'prodi' ? 'selected' : ''}>Berdasarkan Prodi</option>
                            <option value="kategori" ${savedData?.data_source == 'kategori' ? 'selected' : ''}>Berdasarkan Kategori Form</option>
                            <option value="tingkat" ${savedData?.data_source == 'tingkat' ? 'selected' : ''}>Berdasarkan Tingkat Prestasi</option>
                            <option value="capaian" ${savedData?.data_source == 'capaian' ? 'selected' : ''}>Berdasarkan Capaian Prestasi</option>                        
                            </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Tipe Grafik</label>
                        <select name="landing_widgets[${wId}][chart_type]" onchange="updateChart(${wId}, 'distribusi', this.value)" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm outline-none">
                            <option value="pie_3d" ${savedData?.chart_type == 'pie_3d' ? 'selected' : ''}>Pie Chart 3D</option>
                            <option value="donut_3d" ${savedData?.chart_type == 'donut_3d' ? 'selected' : ''}>Donut Chart 3D</option>
                            <option value="bar_3d" ${savedData?.chart_type == 'bar_3d' ? 'selected' : ''}>Batang 3D</option>
                        </select>
                    </div>
                </div>`;
        }
        else if(type === 'chart_tren') {
            if(!titleStr) titleStr = "Tren Pertumbuhan Bulanan"; 
            iconStr = "bi-graph-up-arrow text-purple-600 bg-purple-50";
            previewHtml = `<div id="canvas-${wId}" class="w-full h-full min-h-[300px]"></div>`;
            configHtml = `
                <div class="mt-4">
                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Jenis Grafik</label>
                    <select name="landing_widgets[${wId}][chart_type]" onchange="updateChart(${wId}, 'tren', this.value)" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm outline-none">
                        <option value="area" ${savedData?.chart_type == 'area' ? 'selected' : ''}>Area Chart (ECharts)</option>
                        <option value="bar_gradient" ${savedData?.chart_type == 'bar_gradient' ? 'selected' : ''}>Bar Chart Gradient</option>
                    </select>
                </div>`;
        }
        else if(type === 'counter') {
    if(!titleStr) titleStr = "Ikhtisar Statistik"; 
    iconStr = "bi-123 text-orange-600 bg-orange-50";
    previewHtml = `
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 w-full h-full p-2">
            ${['Prestasi', 'Mahasiswa', 'Berita', 'Internasional', 'Nasional', 'Wilayah/Kota'].map(l => `
                <div class="bg-white p-5 rounded-[2rem] border border-gray-100 text-center shadow-sm">
                    <span class="block text-3xl font-black text-gray-900 mb-1">99</span>
                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-1 block">${l}</span>
                </div>
            `).join('')}
        </div>`;
    configHtml = `<div class="mt-4 p-3 bg-gray-50 rounded-xl text-[10px] text-gray-500 italic text-center font-bold">Data ditarik otomatis dari database prestasi.</div>`;
}
        // DESAIN WIDGET BARU: FEED TERBARU
        else if(type === 'feed_terbaru') {
            if(!titleStr) titleStr = "Pembaruan Terkini"; 
            iconStr = "bi-clock-history text-teal-600 bg-teal-50";
            previewHtml = `
                <div class="flex flex-col gap-3 w-full h-full pr-2 overflow-y-auto custom-scrollbar">
                    ${dummyData.feed_terbaru.map((feed, i) => `
                        <div class="flex gap-4 p-4 bg-white rounded-2xl border border-gray-100 shadow-sm w-full relative overflow-hidden group">
                            <div class="absolute left-0 top-0 bottom-0 w-1 ${i % 2 === 0 ? 'bg-[#006633]' : 'bg-teal-500'}"></div>
                            <div class="flex-1 min-w-0 pl-2">
                                <div class="flex justify-between items-start mb-1.5">
                                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">${feed.time}</span>
                                    <span class="text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider ${feed.color}">${feed.type}</span>
                                </div>
                                <h5 class="text-sm font-black text-gray-800 leading-snug line-clamp-2">${feed.title}</h5>
                            </div>
                        </div>
                    `).join('')}
                </div>`;
            configHtml = `<div class="mt-4"><label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Batas Tampilan</label><input type="number" name="landing_widgets[${wId}][limit]" value="${savedData?.limit || 5}" class="w-full px-4 py-2 border rounded-xl text-sm font-bold focus:ring-1 focus:ring-[#006633] outline-none"></div>`;
        }

        const cardHtml = `
            <div id="widget-${wId}" class="bg-white rounded-[2.5rem] border border-gray-200 shadow-xl flex flex-col xl:flex-row overflow-hidden relative transition-all animate-in slide-in-from-bottom-5">
                <input type="hidden" name="landing_widgets[${wId}][type]" value="${type}">
                
                <div class="w-full xl:w-1/3 border-r border-gray-100 bg-gray-50/50 p-10 flex flex-col justify-between shrink-0">
                    <div>
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl ${iconStr} flex items-center justify-center text-2xl shadow-sm border border-white"></div>
                                <h4 class="font-black text-gray-900 text-sm uppercase">Pengaturan</h4>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer scale-110">
                                <input type="checkbox" name="landing_widgets[${wId}][is_active]" value="1" class="sr-only peer" ${savedData?.is_active == '1' ? 'checked' : (savedData ? '' : 'checked')}>
                                <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#006633] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 shadow-inner"></div>
                            </label>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Judul Tampilan</label>
                                <input type="text" name="landing_widgets[${wId}][title]" value="${titleStr}" class="w-full px-5 py-3.5 border border-gray-100 bg-white rounded-2xl text-sm font-bold focus:ring-1 focus:ring-[#006633] outline-none" onkeyup="document.getElementById('preview-title-${wId}').innerText = this.value">
                            </div>
                            ${configHtml}
                        </div>
                    </div>
                    <button type="button" onclick="removeWidget(${wId})" class="mt-10 px-6 py-4 border border-red-100 text-red-500 hover:bg-red-500 hover:text-white rounded-2xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-3"><i class="bi bi-trash3-fill"></i> Hapus Widget</button>
                </div>

                <div class="w-full xl:w-2/3 p-10 flex flex-col relative bg-white min-h-[500px]">
                    <div class="absolute top-8 right-10 bg-gray-900 text-white px-4 py-2 rounded-xl text-[8px] font-black uppercase tracking-widest shadow-xl flex items-center gap-3"><span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span> Preview Aktif</div>
                    <h4 id="preview-title-${wId}" class="font-black text-gray-900 text-xl mb-10 border-b border-gray-50 pb-6 uppercase tracking-tight">${titleStr}</h4>
                    <div class="flex-1 w-full h-full flex items-center justify-center">${previewHtml}</div>
                </div>
            </div>`;

        container.insertAdjacentHTML('beforeend', cardHtml);

        setTimeout(() => {
            if(type === 'chart_distribusi') updateChart(wId, 'distribusi', savedData?.chart_type || 'pie_3d');
            if(type === 'chart_tren') updateChart(wId, 'tren', savedData?.chart_type || 'area');
        }, 100);
    }

    function updateChart(wId, dataType, chartType) {
        const dom = document.getElementById(`canvas-${wId}`);
        if(!dom) return;
        
        if(chartInstances[wId]) {
            if(chartInstances[wId].dispose) chartInstances[wId].dispose();
            else if(chartInstances[wId].destroy) chartInstances[wId].destroy();
        }
        
        const data = dummyData[dataType];

        if(chartType.includes('_3d')) {
            const hType = (chartType === 'bar_3d') ? 'column' : 'pie';
            chartInstances[wId] = Highcharts.chart(`canvas-${wId}`, {
                chart: { type: hType, options3d: { enabled: true, alpha: 45, beta: 0 }, backgroundColor: 'transparent' },
                title: { text: '' },
                plotOptions: {
                    pie: { innerSize: chartType === 'donut_3d' ? 120 : 0, depth: 55, dataLabels: { enabled: true, format: '{point.name}', style: { fontWeight: '900', textOutline: 'none' } } },
                    column: { depth: 40 }
                },
                series: [{ 
                    name: 'Data Simulasi', 
                    data: data.labels.map((l, i) => ({name: l, y: data.data[i]})),
                    colors: ['#006633', '#10b981', '#3b82f6', '#f59e0b']
                }],
                credits: { enabled: false }
            });
        }
        else {
            const myChart = echarts.init(dom);
            chartInstances[wId] = myChart;
            let option = {
                grid: { top: 30, left: 50, right: 30, bottom: 50 },
                xAxis: { type: 'category', data: data.labels, axisLabel: { fontWeight: 'bold' } },
                yAxis: { type: 'value' },
                series: [{
                    data: data.data,
                    type: chartType === 'area' ? 'line' : 'bar',
                    smooth: true,
                    itemStyle: { 
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{offset: 0, color: '#006633'}, {offset: 1, color: '#10b981'}]),
                        borderRadius: [15, 15, 0, 0]
                    },
                    areaStyle: chartType === 'area' ? { color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{offset: 0, color: 'rgba(0,102,51,0.5)'}, {offset: 1, color: 'rgba(0,102,51,0)'}]) } : null
                }]
            };
            myChart.setOption(option);
            window.addEventListener('resize', () => myChart.resize());
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const savedWidgets = @json($savedWidgets ?? []);
        if(savedWidgets.length > 0) savedWidgets.forEach(w => buildWidget(w.type, w));
    });
</script>
@endsection