<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Prodi;
use App\Models\Fakultas; // Tambahkan Fakultas
use App\Models\Permission;
use App\Imports\UsersImport;
use App\Exports\TemplateAkunExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ManajemenAkunController extends Controller
{
    // ========================================================
    // HELPER: EKSTRAK PRODI & ANGKATAN DARI NIM
    // ========================================================
    public function parseNimData($nim)
    {
        $result = [
            'prodi_id' => null,
            'angkatan' => null
        ];

        if (empty($nim) || strlen($nim) < 7) {
            return $result; // Kosongkan jika format kurang dari standar
        }

        // 1. Ekstrak Angkatan (Ambil digit index 1 & 2 -> "22" jadi "2022")
        $kodeAngkatan = substr($nim, 1, 2);
        if (is_numeric($kodeAngkatan)) {
            $result['angkatan'] = '20' . $kodeAngkatan;
        }

        // 2. Ekstrak Fakultas & Prodi
        $kodeFakultas = substr($nim, 0, 1);
        $kodeProdi = substr($nim, 5, 2);

        $fakultas = Fakultas::where('kode_fakultas', $kodeFakultas)->first();
        if ($fakultas) {
            $prodi = Prodi::where('kode_prodi', $kodeProdi)
                ->whereHas('jurusan', function ($q) use ($fakultas) {
                    $q->where('fakultas_id', $fakultas->id);
                })->first();

            if ($prodi) {
                $result['prodi_id'] = $prodi->id;
            }
        }

        return $result;
    }

    /**
     * Daftar Akun (Index) dengan Search, Filter, dan Sort
     */
    public function indexManajemenAkun(Request $request)
    {
        $query = User::with(['role', 'prodi', 'fakultas', 'jurusan']);

        if (Auth::user()->role->kode_role !== 'SA') {
            $query->whereHas('role', fn($q) => $q->where('kode_role', '!=', 'SA'));
        }

        $statusTab = $request->input('tab', 'aktif');
        $query->where('is_active', $statusTab === 'pending' ? 0 : 1);

        $pendingCount = User::where('is_active', 0)->count();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(
                fn($q) =>
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
            );
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        $query->when(
            $request->filled('sort'),
            fn($q) => $q->orderBy($request->sort, $request->direction ?? 'asc'),
            fn($q) => $q->latest()
        );

        return view("manajemen-akun.index", [
            'users' => $query->paginate(10)->withQueryString(),
            'statusTab' => $statusTab,
            'pendingCount' => $pendingCount,
            'roles' => Role::all()
        ]);
    }

    public function createAkun()
    {
        $roles = Role::all();
        $prodis = Prodi::all();
        return view("manajemen-akun.create", compact('roles', 'prodis'));
    }

    public function storeAkun(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'nim_nip'   => 'required|string|unique:users',
            'email'     => 'nullable|email|unique:users',
            'role_id'   => 'required|exists:roles,id',
            'password'  => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $roleDipilih = Role::findOrFail($request->role_id);

        if (Auth::user()->role->kode_role !== 'SA' && $roleDipilih->kode_role === 'SA') {
            return back()->with('error', 'Anda tidak diizinkan membuat akun Super Admin!');
        }

        $passwordFinal = $request->filled('password')
            ? Hash::make($request->password)
            : Hash::make($request->nim_nip);

        // --- Ekstrak Data dari NIM ---
        $prodiIdFinal = $request->prodi_id;
        $angkatanFinal = null;

        if ($roleDipilih->kode_role === 'MHS') {
            $nimData = $this->parseNimData($request->nim_nip);

            // Jika prodi tidak diisi manual dari form, pakai hasil ekstrak
            if (empty($prodiIdFinal)) {
                $prodiIdFinal = $nimData['prodi_id'];
            }
            // Angkatan selalu otomatis ditarik dari NIM
            $angkatanFinal = $nimData['angkatan'];
        }

        User::create([
            'name'      => $request->name,
            'nim_nip'   => $request->nim_nip,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            'prodi_id'  => $prodiIdFinal,
            'angkatan'  => $angkatanFinal, // SIMPAN ANGKATAN
            'password'  => $passwordFinal,
            'is_active' => $request->has('is_active') ? $request->is_active : 1,
        ]);

        return redirect()->route('super_admin.manajemen-akun')->with('success', 'Akun berhasil ditambahkan!');
    }

    public function editAkun($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role->kode_role !== 'SA' && $user->role->kode_role === 'SA') {
            return redirect()->route('super_admin.manajemen-akun')->with('error', 'Akses Ditolak! Anda tidak bisa mengedit Super Admin.');
        }

        $roles = Role::all();
        $prodis = Prodi::all();
        return view("manajemen-akun.edit", compact('user', 'roles', 'prodis'));
    }

    public function updateAkun(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roleDipilih = Role::findOrFail($request->role_id);

        if (Auth::user()->role->kode_role !== 'SA' && ($user->role->kode_role === 'SA' || $roleDipilih->kode_role === 'SA')) {
            return redirect()->route('super_admin.manajemen-akun')->with('error', 'Izin Ditolak!');
        }

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|max:255|unique:users,email,' . $id,
            'nim_nip'   => 'required|string|unique:users,nim_nip,' . $id,
            'role_id'   => 'required|exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $dataUpdate = [
            'name'      => $request->name,
            'email'     => $request->email,
            'nim_nip'   => $request->nim_nip,
            'role_id'   => $request->role_id,
            'is_active' => $request->is_active ?? 0,
        ];

        if ($request->has('prodi_id') && !empty($request->prodi_id)) {
            $dataUpdate['prodi_id'] = $request->prodi_id;
        }

        // --- Ekstrak Data Ulang jika dia Mahasiswa ---
        if ($roleDipilih->kode_role === 'MHS') {
            $nimData = $this->parseNimData($request->nim_nip);

            if (empty($dataUpdate['prodi_id'])) {
                $dataUpdate['prodi_id'] = $nimData['prodi_id'];
            }
            $dataUpdate['angkatan'] = $nimData['angkatan']; // UPDATE ANGKATAN
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataUpdate);

        return redirect()->route('super_admin.manajemen-akun')->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroyAkun($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role->kode_role !== 'SA' && $user->role->kode_role === 'SA') {
            return back()->with('error', 'Anda tidak berwenang menghapus Super Admin!');
        }

        if (Auth::id() == $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }

    public function aktivasiAkun($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => 1]);
        return back()->with('success', 'Akun ' . $user->name . ' berhasil diaktivasi!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'bulk_action' => 'required|in:activate,deactivate,delete'
        ]);

        $ids = $request->ids;
        $saRoleId = Role::where('kode_role', 'SA')->value('id');

        if ($request->bulk_action === 'activate') {
            User::whereIn('id', $ids)->update(['is_active' => 1]);
            return back()->with('success', count($ids) . ' akun berhasil diaktivasi!');
        }

        if ($request->bulk_action === 'deactivate') {
            User::whereIn('id', $ids)
                ->where('id', '!=', Auth::id())
                ->where('role_id', '!=', $saRoleId)
                ->update(['is_active' => 0]);

            return back()->with('success', count($ids) . ' akun berhasil dinonaktifkan!');
        }

        if ($request->bulk_action === 'delete') {
            User::whereIn('id', $ids)
                ->where('id', '!=', Auth::id())
                ->where('role_id', '!=', $saRoleId)
                ->delete();
            return back()->with('success', count($ids) . ' akun berhasil dihapus!');
        }
    }

    public function importAkun(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $userId = Auth::id();
        $cacheKey = "import_progress_{$userId}";

        $path = $request->file('file')->store('temp-imports', 'local');
        $fullPath = Storage::disk('local')->path($path);

        $reader = IOFactory::createReaderForFile($fullPath);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();

        $totalRows = max(0, $sheet->getHighestDataRow() - 1);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        if ($totalRows === 0) {
            return back()->with('error', 'File kosong atau tidak valid.');
        }

        Cache::put($cacheKey, [
            'current' => 0,
            'total' => $totalRows,
            'status' => 'processing',
            'message' => 'Memulai import...'
        ], now()->addHours(2));

        Excel::queueImport(
            new UsersImport($userId, $cacheKey),
            $path,
            'local'
        );

        return back()->with('success', "Import {$totalRows} data sedang diproses.");
    }

    public function checkImportStatus()
    {
        $cacheKey = 'import_progress_' . Auth::id();
        return response()->json(
            Cache::get($cacheKey, [
                'current' => 0,
                'total' => 0,
                'status' => 'idle',
                'message' => null,
            ])
        );
    }

    public function clearImportStatus()
    {
        Cache::forget('import_progress_' . Auth::id());
        return response()->json(['status' => 'cleared']);
    }

    public function exportFormatAkun()
    {
        return Excel::download(new TemplateAkunExport, 'template_import_akun.xlsx');
    }

    // ========================================================
    // SINKRONISASI DATA LAMA (Jalankan sekali via Route / Tombol UI)
    // ========================================================
    public function syncProdiLama()
    {
        // Ambil mahasiswa yang prodi-nya ATAU angkatannya masih kosong
        $users = User::whereHas('role', fn($q) => $q->where('kode_role', 'MHS'))
            ->where(function ($query) {
                $query->whereNull('prodi_id')
                    ->orWhereNull('angkatan');
            })->get();

        $countUpdated = 0;

        foreach ($users as $user) {
            $nimData = $this->parseNimData($user->nim_nip);

            $updateFields = [];
            if (empty($user->prodi_id) && !empty($nimData['prodi_id'])) {
                $updateFields['prodi_id'] = $nimData['prodi_id'];
            }
            if (empty($user->angkatan) && !empty($nimData['angkatan'])) {
                $updateFields['angkatan'] = $nimData['angkatan'];
            }

            if (!empty($updateFields)) {
                $user->update($updateFields);
                $countUpdated++;
            }
        }

        return back()->with('success', "Berhasil mensinkronkan {$countUpdated} data mahasiswa lama dengan Master Prodi dan Angkatan!");
    }

    public function indexRolePermission(Request $request)
    {
        $roles = Role::all();
        $permissionsGrouped = Permission::with('roles')->get()->groupBy('modul');
        $stats = [
            'total_permissions' => Permission::count(),
            'role_counts' => $roles->mapWithKeys(function ($role) {
                return [$role->kode_role => $role->permissions->count()];
            })
        ];

        return view('manajemen-akun.role-permission', compact('roles', 'permissionsGrouped', 'stats'));
    }

    public function updateRolePermission(Request $request)
    {
        $request->validate([
            'role_id'       => 'required',
            'permission_id' => 'required',
            'action'        => 'required|in:attach,detach'
        ]);

        $roleId = (int) $request->role_id;
        $permId = (int) $request->permission_id;

        if ($roleId === 1) {
            return response()->json(['success' => false, 'message' => 'Izin Super Admin mutlak.'], 403);
        }

        try {
            if ($request->action === 'attach') {
                DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $roleId, 'permission_id' => $permId],
                    ['role_id' => $roleId, 'permission_id' => $permId]
                );
            } else {
                DB::table('role_permissions')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $permId)
                    ->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Database berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan Database: ' . $e->getMessage()
            ], 500);
        }
    }
}
