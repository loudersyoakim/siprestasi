<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Prodi;
use App\Models\Permission;
use App\Imports\UsersImport;
use App\Exports\TemplateAkunExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ManajemenAkunController extends Controller
{
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
            // is_active tidak perlu 'required' agar bisa bernilai false/0
            'is_active' => 'boolean',
        ]);

        $roleDipilih = Role::findOrFail($request->role_id);

        if (Auth::user()->role->kode_role !== 'SA' && $roleDipilih->kode_role === 'SA') {
            return back()->with('error', 'Anda tidak diizinkan membuat akun Super Admin!');
        }

        // Logika password default jika kosong
        $passwordFinal = $request->filled('password')
            ? Hash::make($request->password)
            : Hash::make($request->nim_nip);

        User::create([
            'name'      => $request->name,
            'nim_nip'   => $request->nim_nip,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            // Cek apakah prodi_id ada di request, jika tidak biarkan null
            'prodi_id'  => $request->prodi_id ?? null,
            'password'  => $passwordFinal,
            // Default ke 1 jika tidak ada di request
            'is_active' => $request->has('is_active') ? $request->is_active : 1,
        ]);

        return redirect()->route('super_admin.manajemen-akun')->with('success', 'Akun berhasil ditambahkan!');
    }

    public function editAkun($id)
    {
        $user = User::findOrFail($id);

        // Mencegah Admin yang mencoba edit SA
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
            'is_active' => 'boolean', // Hapus 'required'
        ]);

        $dataUpdate = [
            'name'      => $request->name,
            'email'     => $request->email,
            'nim_nip'   => $request->nim_nip,
            'role_id'   => $request->role_id,
            'is_active' => $request->is_active ?? 0, // Jika tidak ada (uncheck), set ke 0
        ];

        // Update prodi_id HANYA JIKA dikirim dari form
        if ($request->has('prodi_id')) {
            $dataUpdate['prodi_id'] = $request->prodi_id;
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
            'bulk_action' => 'required|in:activate,delete'
        ]);

        $ids = $request->ids;

        if ($request->bulk_action === 'activate') {
            User::whereIn('id', $ids)->update(['is_active' => 1]);
            return back()->with('success', count($ids) . ' akun berhasil diaktivasi!');
        }

        if ($request->bulk_action === 'delete') {
            $saRoleId = Role::where('kode_role', 'SA')->value('id');
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

        // Simpan file
        $path = $request->file('file')->store('temp-imports', 'local');
        $fullPath = Storage::disk('local')->path($path);

        // Hitung baris TANPA load semua ke memory
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

        // Init progress
        Cache::put($cacheKey, [
            'current' => 0,
            'total' => $totalRows,
            'status' => 'processing',
            'message' => 'Memulai import...'
        ], now()->addHours(2));

        // Jalankan queue
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


    public function indexRolePermission(Request $request)
    {
        // Ambil semua Role (SA, AD, FK, JR, MHS)
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
        // 1. Validasi input
        $request->validate([
            'role_id'       => 'required',
            'permission_id' => 'required',
            'action'        => 'required|in:attach,detach'
        ]);

        $roleId = (int) $request->role_id;
        $permId = (int) $request->permission_id;

        // 2. Proteksi Super Admin (SA) - ID 1 biasanya SA
        if ($roleId === 1) {
            return response()->json(['success' => false, 'message' => 'Izin Super Admin mutlak.'], 403);
        }

        try {
            // 3. PAKSA TEMBAK LANGSUNG KE TABEL PIVOT
            if ($request->action === 'attach') {
                // Gunakan updateOrInsert untuk mencegah error "Duplicate Entry"
                \Illuminate\Support\Facades\DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $roleId, 'permission_id' => $permId],
                    ['role_id' => $roleId, 'permission_id' => $permId]
                );
            } else {
                // Hapus data langsung
                \Illuminate\Support\Facades\DB::table('role_permissions')
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
