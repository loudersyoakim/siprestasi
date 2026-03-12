<div class="p-8 space-y-6">
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-green-50 text-[#006633] rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
            <i class="bi bi-headset text-3xl"></i>
        </div>
        <h4 class="text-base font-black text-gray-800 tracking-tight">Butuh Bantuan?</h4>
        <p class="text-xs text-gray-500 mt-1">Silakan hubungi Administrator untuk kendala teknis, perbaikan data, atau pertanyaan seputar sistem.</p>
    </div>

    @php
        // TODO: Ganti ini dengan Query ke tabel Master Data Abang nanti.
        // Contoh: $kontak_admin = \App\Models\Pengaturan::value('nomor_wa');
        $kontak_admin = '0812-3456-7890'; // Placeholder sementara
    @endphp

    @if(!empty($kontak_admin))
    {{-- Jika Nomor Admin Ada di Database --}}
    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 flex items-center justify-between group hover:border-green-200 hover:bg-green-50/30 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-green-600">
                <i class="bi bi-whatsapp text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">WhatsApp Admin</p>
                <p class="text-sm font-bold text-gray-800">{{ $kontak_admin }}</p>
            </div>
        </div>
        
        {{-- preg_replace dipakai untuk menghapus spasi atau strip '-' biar link wa.me valid --}}
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kontak_admin) }}" target="_blank" 
           class="px-4 py-2 bg-[#006633] text-white text-[10px] font-bold uppercase rounded-xl hover:bg-[#004d26] transition-all shadow-md shadow-green-100 flex items-center gap-1.5">
            <i class="bi bi-chat-dots-fill"></i> Chat
        </a>
    </div>
    
    <div class="flex items-center gap-3 mt-4 px-2">
        <i class="bi bi-info-circle-fill text-blue-500 text-lg"></i>
        <p class="text-[10px] text-gray-500 leading-relaxed">Jam operasional Admin melayani balasan pesan adalah pada hari kerja (Senin - Jumat) pukul 08:00 - 16:00 WIB.</p>
    </div>

    @else
    {{-- Jika Nomor Admin Belum Diisi / Kosong di Database --}}
    <div class="bg-red-50 rounded-2xl p-5 border border-red-100 flex items-start gap-4">
        <i class="bi bi-exclamation-triangle-fill text-red-500 text-2xl mt-0.5"></i>
        <div>
            <p class="text-sm font-bold text-red-700 mb-1">Kontak Belum Tersedia</p>
            <p class="text-[11px] text-red-500 leading-relaxed">Mohon maaf, Master Data kontak Administrator belum diatur. Silakan datangi langsung ruangan Tata Usaha (TU) Fakultas.</p>
        </div>
    </div>
    @endif
</div>