<div id="modal-tambah-jenis" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 bg-[#006633] text-white flex justify-between items-center">
            <h4 class="font-black uppercase tracking-tight italic">Tambah Jenis Prestasi</h4>
            <button onclick="closeModal('modal-tambah-jenis')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.master-data.jenis.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Jenis</label>
                <input type="text" name="nama_jenis" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-[#006633] outline-none transition-all" placeholder="">
            </div>
            <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-100">Simpan Data</button>
        </form>
    </div>
</div>

<div id="modal-tambah-kategori" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 bg-[#006633] text-white flex justify-between items-center">
            <h4 class="font-black uppercase tracking-tight italic">Tambah Kategori</h4>
            <button onclick="closeModal('modal-tambah-kategori')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.master-data.kategori.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Kategori</label>
                <input type="text" name="nama_kategori" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-[#006633] outline-none transition-all" placeholder="">
            </div>
            <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-100">Simpan Data</button>
        </form>
    </div>
</div>

<div id="modal-tambah-tingkat" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-6 bg-[#006633] text-white flex justify-between items-center">
            <h4 class="font-black uppercase tracking-tight italic">Tambah Tingkat</h4>
            <button onclick="closeModal('modal-tambah-tingkat')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.master-data.tingkat.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Tingkat</label>
                <input type="text" name="nama_tingkat" required class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-[#006633] outline-none transition-all" placeholder="">
            </div>
            <button type="submit" class="w-full py-4 bg-[#006633] text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-100">Simpan Data</button>
        </form>
    </div>
</div>

<div id="modal-edit-atribut" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
        <div id="edit-header" class="p-6 bg-blue-600 text-white flex justify-between items-center">
            <h4 id="edit-title" class="font-black uppercase tracking-tight italic">Edit Atribut</h4>
            <button onclick="closeModal('modal-edit-atribut')" class="text-white/70 hover:text-white"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="form-edit-atribut" action="" method="POST" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Atribut</label>
                <input type="text" name="nama_atribut" id="edit-nama-atribut" required
                    class="w-full mt-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-bold focus:border-blue-600 outline-none transition-all">
            </div>
            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-100">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editAtribut(type, id, nama) {
        const typeLower = type.toLowerCase(); // Paksa jadi huruf kecil
        const form = document.getElementById('form-edit-atribut');
        const input = document.getElementById('edit-nama-atribut');
        const title = document.getElementById('edit-title');

        // Name input jadi: nama_jenis, nama_kategori, atau nama_tingkat
        input.name = "nama_" + typeLower;

        title.innerText = "Edit " + type.toUpperCase();
        input.value = nama;

        // URL jadi: /admin/master-data/jenis/1, dll
        form.action = `/admin/master-data/${typeLower}/${id}`;

        openModal('modal-edit-atribut');
    }
</script>