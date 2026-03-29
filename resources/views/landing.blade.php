@extends('layouts.front')

@section('title', 'Beranda - SIARPRESTASI')
@section('content')

{{-- 1. HERO SECTION --}}
<section class="relative min-h-screen w-full flex items-center justify-center overflow-x-hidden pt-24 pb-12 lg:pt-0 lg:pb-0">
    
    {{-- Background & Overlays --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('img/fmipa-unimed3.jpg') }}');"></div>
        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 60%, rgba(236,253,245,0.92) 100%); backdrop-filter: blur(1px);"></div>
        <div class="hidden lg:block absolute pointer-events-none" style="top:15%; right:20%; width:480px; height:480px; border-radius:50%; background:radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%); filter:blur(40px);"></div>
        <div class="absolute bottom-0 left-0 w-full h-40 pointer-events-none" style="background: linear-gradient(to top, #f9fafb, transparent);"></div>
    </div>

    <div class="container mx-auto pt-8 px-5 lg:px-20 relative z-20">
        <div class="{{ (is_array($activeWidgets) && count($activeWidgets) > 0) ? 'flex flex-col lg:grid lg:grid-cols-12 gap-10 lg:gap-12 items-center' : 'flex flex-col items-center text-center mx-auto max-w-3xl' }}">
            
            <div class="{{ (is_array($activeWidgets) && count($activeWidgets) > 0) ? 'w-full lg:col-span-5 flex flex-col items-center lg:items-start text-center lg:text-left mt-2 lg:mt-0' : 'flex flex-col items-center text-center w-full' }}">
                
                <div class="w-16 h-1.5 bg-[#006633] mb-6 rounded-full shadow-sm"></div>

                <span class="text-[#006633] font-black tracking-[0.25em] uppercase text-lg lg:text-3xl md:text-base mb-3 drop-shadow-sm">
                    {{ $pengaturan['nama_aplikasi'] ?? 'SIARPRESTASI UNIMED' }}
                </span>

                <h1 class="text-4xl md:text-5xl lg:text-5xl font-black text-gray-900 leading-[1.1] uppercase mb-6 tracking-tight drop-shadow-md {{ (is_array($activeWidgets) && count($activeWidgets) > 0) ? '' : 'max-w-3xl mx-auto' }}">
                    {{ $pengaturan['hero_title_1'] ?? 'Sistem Arsip' }} <br class="hidden lg:block">
                    <span class="text-[#006633]">{{ $pengaturan['hero_title_2'] ?? 'Prestasi' }}</span> <br class="hidden lg:block">
                    {{ $pengaturan['hero_title_3'] ?? 'Mahasiswa' }}
                </h1>

                <p class="text-lg md:text-xl text-gray-800 font-medium leading-relaxed mb-10 drop-shadow-sm {{ (is_array($activeWidgets) && count($activeWidgets) > 0) ? 'max-w-xl' : 'max-w-xl mx-auto' }}">
                    {{ $pengaturan['deskripsi_landing'] ?? 'Platform terpadu untuk mencatat setiap pencapaian luar biasa mahasiswa secara real-time.' }}
                </p>

                <a href="/login" class="w-max bg-[#006633] border border-white/20 text-white px-10 py-3.5 rounded-full font-bold text-sm hover:bg-green-800 hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-green-900/20 uppercase tracking-widest">
                    Login
                </a>
            </div>

            {{-- AREA WIDGET --}}
            @if(is_array($activeWidgets) && count($activeWidgets) > 0)
            <div class="w-full lg:col-span-7 flex flex-col items-center pt-10 justify-center mt-2 lg:mt-0">
                <div class="relative w-full max-w-[360px] sm:max-w-[480px] lg:max-w-[550px]">
                    
                    <div class="absolute inset-x-3 lg:inset-x-6 top-[-6px] lg:top-[-10px] bottom-2 lg:bottom-4 rounded-[2rem] lg:rounded-[2.5rem] bg-white/40 border border-white/60 z-[1] transform scale-[0.98] pointer-events-none"></div>
                    <div class="absolute inset-x-6 lg:inset-x-12 top-[-12px] lg:top-[-18px] bottom-4 lg:bottom-8 rounded-[2rem] lg:rounded-[2.5rem] bg-white/20 border border-white/40 z-[0] transform scale-[0.95] pointer-events-none"></div>

                    {{-- STAGE UTAMA (TINGGI DIKUNCI MUTLAK PAKAI INLINE STYLE) --}}
                    <div id="widgetStage" style="position: relative; z-index: 10; width: 100%; height: 480px; overflow: hidden; border-radius: 2.5rem;">
                        
                        @foreach($activeWidgets as $index => $widget)
                        <div class="widget-card" data-index="{{ $index }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1); z-index: {{ $index == 0 ? 20 : 5 }}; opacity: {{ $index == 0 ? 1 : 0 }}; transform: {{ $index == 0 ? 'translateX(0) scale(1)' : 'translateX(30px) scale(0.96)' }}; pointer-events: {{ $index == 0 ? 'auto' : 'none' }};">
                            
                            {{-- Container Dalam Card --}}
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid white; box-shadow: 0 15px 40px rgba(0,0,0,0.08); border-radius: 2.5rem;">
                                
                                {{-- Area Klik Kiri & Kanan --}}
                                @if(count($activeWidgets) > 1)
                                <div class="widget-click-prev absolute top-0 left-0 w-1/4 h-full z-30 cursor-w-resize"></div>
                                <div class="widget-click-next absolute top-0 right-0 w-1/4 h-full z-30 cursor-e-resize"></div>
                                @endif

                                {{-- HEADER (Tinggi Fix 80px) --}}
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 80px; padding-top: 32px; text-align: center; z-index: 20;">
                                    <h4 class="font-black text-gray-900 text-sm sm:text-base uppercase tracking-[0.1em] m-0">{{ $widget['title'] }}</h4>
                                    <div class="w-8 lg:w-10 h-[2px] bg-[#006633]/30 mx-auto mt-2 lg:mt-3 rounded-full"></div>
                                </div>

                                {{-- KONTEN (Dikunci Mulai dari px 80 sampai Bawah) --}}
                                <div style="position: absolute; top: 80px; bottom: 0; left: 0; width: 100%; padding: 16px 24px 24px; z-index: 10; overflow: hidden;">
                                    
                                    {{-- LEADERBOARD (Angka Polos -> Foto Bulat -> Nama + NIM/Prodi) --}}
                                    @if($widget['type'] == 'leaderboard')
                                        <div class="h-full overflow-y-auto custom-scrollbar flex flex-col gap-3 pr-2">
                                            @foreach($widgetData[$index] ?? [] as $rank => $mhs)
                                            <div class="flex items-center gap-4 bg-white/90 rounded-2xl border border-white shadow-sm p-3 lg:p-4 w-full hover:bg-white transition-all">
                                                
                                                {{-- 1. Angka Urut Polos --}}
                                                <div class="w-4 lg:w-6 shrink-0 text-center font-black text-xl lg:text-2xl {{ $rank == 0 ? 'text-[#fbbf24]' : ($rank == 1 ? 'text-gray-400' : ($rank == 2 ? 'text-orange-400' : 'text-gray-300')) }}">
                                                    {{ $rank + 1 }}
                                                </div>

                                                {{-- 2. Foto Profil Bulat --}}
                                                <div class="w-12 h-12 lg:w-[52px] lg:h-[52px] shrink-0 rounded-full overflow-hidden border-2 border-white shadow-sm bg-gray-100">
                                                    <img src="{{ $mhs->foto ? asset('storage/'.$mhs->foto) : 'https://ui-avatars.com/api/?name='.urlencode($mhs->name).'&background=006633&color=fff&bold=true' }}" class="w-full h-full object-cover">
                                                </div>

                                                {{-- 3. Nama, NIM, Prodi --}}
                                                <div class="flex-1 min-w-0 text-left">
                                                    <div class="font-black text-gray-900 text-[13px] lg:text-[14px] uppercase truncate leading-none mb-1.5">{{ $mhs->name }}</div>
                                                    <div class="font-bold text-gray-500 text-[9px] lg:text-[10px] uppercase tracking-wider truncate">
                                                        {{ $mhs->nim ?? $mhs->username ?? 'NIM' }} <span class="mx-1 lg:mx-2 text-gray-300">-</span> {{ $mhs->prodi->nama_prodi ?? 'PRODI' }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                    {{-- COUNTER STATISTIK --}}
                                    @elseif($widget['type'] == 'counter')
                                    <div class="h-full flex items-center justify-center">
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 lg:gap-4 w-full">
                                            @php
                                                $stats = [
                                                    ['key'=>'total_prestasi', 'l'=>'Prestasi', 'i'=>'bi-award-fill'],
                                                    ['key'=>'mhs_berprestasi', 'l'=>'Mahasiswa', 'i'=>'bi-people-fill'],
                                                    ['key'=>'total_berita',    'l'=>'Berita',    'i'=>'bi-newspaper'],
                                                    ['key'=>'internasional',   'l'=>'Internasional', 'i'=>'bi-globe2'],
                                                    ['key'=>'nasional',        'l'=>'Nasional',  'i'=>'bi-flag-fill'],
                                                    ['key'=>'wilayah_kota',    'l'=>'Wilayah/Kota', 'i'=>'bi-geo-alt-fill'],
                                                ];
                                            @endphp
                                            @foreach($stats as $s)
                                            <div class="bg-white/60 border border-white/80 rounded-2xl p-4 text-center shadow-sm">
                                                <i class="bi {{ $s['i'] }} text-[#006633]/40 text-lg mb-2 block"></i>
                                                <div class="font-black text-gray-900 text-2xl leading-none mb-1.5">{{ $widgetData[$index][$s['key']] ?? '0' }}</div>
                                                <div class="font-bold text-gray-400 text-[9px] uppercase tracking-wider truncate">{{ $s['l'] }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- FEED TERBARU --}}
                                    @elseif($widget['type'] == 'feed_terbaru')
                                        <div class="h-full overflow-y-auto custom-scrollbar flex flex-col gap-3 pr-2">
                                            @foreach($widgetData[$index] ?? [] as $feed)
                                            <div class="flex gap-4 p-4 bg-white/90 rounded-2xl border border-gray-100 shadow-sm w-full relative overflow-hidden shrink-0">
                                                <div class="absolute left-0 top-0 bottom-0 w-1 {{ $loop->index % 2 == 0 ? 'bg-[#006633]' : 'bg-teal-500' }}"></div>
                                                <div class="flex-1 min-w-0 pl-2 text-left">
                                                    <div class="flex justify-between items-start mb-1.5">
                                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $feed['waktu'] ?? 'Baru saja' }}</span>
                                                        <span class="text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider bg-gray-100 text-gray-600">{{ $feed['jenis'] ?? 'Info' }}</span>
                                                    </div>
                                                    <h5 class="text-sm font-black text-gray-800 leading-snug line-clamp-2">{{ $feed['judul'] }}</h5>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                    {{-- CHARTS --}}
                                    @else
                                        <div class="absolute inset-0 pb-6 pr-4 pl-4 pt-2">
                                            <div id="chart-{{ $index }}" style="width: 100%; height: 100%;"></div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- NAVIGASI SLIDER --}}
                    @if(count($activeWidgets) > 1)
                    <div class="flex items-center justify-center gap-4 mt-6 lg:mt-8 relative z-20">
                        <button class="widget-btn-prev ctrl-btn w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-[#006633] transition-all shadow-sm">
                            <i class="bi bi-chevron-left text-[10px] lg:text-xs"></i>
                        </button>
                        
                        <div class="relative w-24 lg:w-32 h-[4px] bg-gray-200/60 rounded-full overflow-hidden">
                            <div class="progress-fill absolute top-0 left-0 h-full w-0 bg-gradient-to-r from-[#006633] to-[#10b981] rounded-full"></div>
                        </div>

                        <button class="widget-btn-next ctrl-btn w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-[#006633] transition-all shadow-sm">
                            <i class="bi bi-chevron-right text-[10px] lg:text-xs"></i>
                        </button>
                    </div>
                    @endif

                </div>
            </div>
            @endif

        </div>
    </div>
</section>

{{-- 2. SECTION BERITA --}}
<section id="section-berita" class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-20">
        <div class="mb-10 lg:mb-14 text-center flex flex-col items-center">
            <div class="w-12 h-1 bg-[#006633] mb-4 rounded-full"></div>
            <h2 class="text-2xl lg:text-4xl font-black text-gray-900 uppercase tracking-tight">Berita & <span class="text-[#006633]">Pengumuman</span></h2>
        </div>
        
        @if($headline)
        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-12 lg:col-span-7">
                <a href="{{ route('artikel.show', $headline->slug) }}" class="group relative bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 flex flex-col h-full border border-gray-100">
                    <div class="relative w-full aspect-video lg:aspect-auto lg:h-[400px] overflow-hidden">
                        <img src="{{ asset('storage/' . $headline->gambar_cover) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute top-6 left-6">
                            <span class="bg-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase text-[#006633] shadow-lg">{{ $headline->kategori }}</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $headline->created_at->translatedFormat('d F Y') }}</span>
                        <h3 class="text-2xl font-black text-gray-900 mt-3 leading-tight group-hover:text-[#006633] transition-colors line-clamp-2 uppercase">{{ $headline->judul }}</h3>
                        <p class="text-gray-500 mt-4 text-sm leading-relaxed line-clamp-2">{{ str($headline->isi_konten)->stripTags()->limit(160) }}</p>
                    </div>
                </a>
            </div>
            <div class="col-span-12 lg:col-span-5 flex flex-col gap-6">
                @foreach($listBerita->take(3) as $item)
                <a href="{{ route('artikel.show', $item->slug) }}" class="group flex items-center gap-5 bg-white p-4 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0 shadow-inner">
                        <img src="{{ asset('storage/' . $item->gambar_cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="min-w-0">
                        <span class="text-[9px] font-black text-[#006633] uppercase tracking-widest mb-1 block">{{ $item->kategori }}</span>
                        <h4 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 group-hover:text-[#006633] transition-colors">{{ $item->judul }}</h4>
                    </div>
                </a>
                @endforeach
                <a href="{{ route('artikel.index') }}" class="mt-2 w-full py-4 rounded-2xl bg-gray-900 text-white text-xs font-black uppercase tracking-widest text-center hover:bg-black transition-all shadow-lg">
                    Lihat Semua Berita <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    .custom-scrollbar::-webkit-scrollbar { width:4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background:rgba(0,102,51,0.2); border-radius:10px; }
    
    .progress-fill.running { animation: fillBar 10s linear forwards; }
    @keyframes fillBar { from{width:0%} to{width:100%} }
    
    .ctrl-btn:hover { transform:scale(1.05); box-shadow:0 4px 12px rgba(0,102,51,0.15); border-color:rgba(0,102,51,0.3); }
</style>

<script src="{{ asset('js/echarts.min.js') }}"></script>
<script src="{{ asset('js/highcharts.js') }}"></script>
<script src="{{ asset('js/highcharts-3d.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const activeWidgets = @json($activeWidgets ?? []);
    const widgetData = @json($widgetData ?? []);
    const INTERVAL = 10000;

    activeWidgets.forEach((widget, i) => {
    if (!widget.type.startsWith('chart_')) return;
        const domId = `chart-${i}`;
        const el = document.getElementById(domId);
        if (!el) return;

        const data = widgetData[i] || { labels: [], data: [] }; 
        if (data.labels.length === 0) {
            el.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-[10px] font-bold uppercase">Data Belum Tersedia</div>';
            return;
        }
        const cType = widget.chart_type;

        if (cType.includes('_3d') || cType === 'cylinder_3d') {
            Highcharts.chart(domId, {
                chart:{ type:(cType.includes('pie')||cType.includes('donut'))?'pie':'column', options3d:{enabled:true,alpha:45,beta:0}, backgroundColor:'transparent' },
                title:{ text:'' },
                plotOptions:{
                    pie:{ 
                        innerSize:cType.includes('donut')?'50%':0, 
                        depth:35, 
                        dataLabels:{enabled:true,format:'{point.name}',style:{fontSize:'9px',textOutline:'none',fontWeight:'bold'}} 
                    },
                    column:{ depth:30 }
                },
                series:[{ name:'Jumlah', data:data.labels.map((l,j)=>[l,data.data[j]]), colors:['#006633','#10b981','#3b82f6','#f59e0b','#8b5cf6'] }],
                credits:{ enabled:false }
            });
        } 
        else {
            const c = echarts.init(el);
            c.setOption({
                backgroundColor:'transparent',
                grid:{ top:20,left:10,right:10,bottom:20,containLabel:true },
                xAxis:{ type:'category',data:data.labels, axisLabel:{fontWeight:'bold',fontSize:10,color:'#9ca3af'}, axisTick:{show:false}, axisLine:{lineStyle:{color:'#e5e7eb'}} },
                yAxis:{ type:'value', splitLine:{lineStyle:{type:'dashed',color:'#f3f4f6'}}, axisLabel:{fontSize:10,color:'#9ca3af'} },
                series:[{ 
                    data:data.data, type:cType==='area'?'line':'bar', smooth:true, 
                    itemStyle:{ color:new echarts.graphic.LinearGradient(0,0,0,1,[{offset:0,color:'#006633'},{offset:1,color:'#10b981'}]), borderRadius:[6,6,0,0] }, 
                    areaStyle:cType==='area'?{color:new echarts.graphic.LinearGradient(0,0,0,1,[{offset:0,color:'rgba(0,102,51,0.25)'},{offset:1,color:'rgba(0,102,51,0)'}])}:null,
                    symbol:'circle', symbolSize:6
                }]
            });
            window.addEventListener('resize', () => c.resize());
        }
    });

    if (activeWidgets.length <= 1) return;
    
    const cards = Array.from(document.querySelectorAll('.widget-card'));
    const pBar = document.querySelector('.progress-fill');
    let cur = 0;
    let timer = null;

    function go(next, dir) {
        cards[cur].style.opacity = '0';
        cards[cur].style.transform = dir === 'next' ? 'translateX(-30px) scale(0.95)' : 'translateX(30px) scale(0.95)';
        cards[cur].style.pointerEvents = 'none';

        cur = (next + cards.length) % cards.length;

        cards[cur].style.opacity = '1';
        cards[cur].style.transform = 'translateX(0) scale(1)';
        cards[cur].style.pointerEvents = 'auto';
        cards[cur].style.zIndex = 20;

        if(pBar){ pBar.classList.remove('running'); void pBar.offsetWidth; pBar.classList.add('running'); }
    }

    function start() { clearInterval(timer); timer = setInterval(() => go(cur+1, 'next'), INTERVAL); }
    
    const btnPrev = document.querySelector('.widget-btn-prev');
    const btnNext = document.querySelector('.widget-btn-next');
    if (btnPrev) btnPrev.onclick = () => { go(cur-1, 'prev'); start(); };
    if (btnNext) btnNext.onclick = () => { go(cur+1, 'next'); start(); };
    
    cards.forEach(c => {
        const pClick = c.querySelector('.widget-click-prev');
        const nClick = c.querySelector('.widget-click-next');
        if(pClick) pClick.onclick = (e) => { if(e.target.closest('.custom-scrollbar, canvas, [id^="chart-"]')) return; go(cur-1, 'prev'); start(); };
        if(nClick) nClick.onclick = (e) => { if(e.target.closest('.custom-scrollbar, canvas, [id^="chart-"]')) return; go(cur+1, 'next'); start(); };
    });
    
    if(pBar) pBar.classList.add('running');
    start();
});
</script>
@endsection