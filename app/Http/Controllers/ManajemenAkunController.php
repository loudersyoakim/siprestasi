<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Prodi;
use App\Models\FakultasImport;
use App\Models\JurusanImport;
use App\Models\Permission;
use App\Imports\UsersImport;
use App\Exports\TemplateAkunExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ManajemenAkunController extends Controller
{
    /**
     * Daftar Akun (Index) dengan Search, Filter, dan Sort
     */
    public function indexManajemenAkun(Request $request)
    {
        // Panggil relasi supaya query lebih efisien
        $query = User::with(['role', 'prodi', 'fakultas', 'jurusan']);

        // --- PROTEKSI: Admin biasa tidak boleh melihat Super Admin ---
        if (Auth::user()->role->kode_role !== 'SA') {
            $query->whereHas('role', function ($q) {
                $q->where('kode_role', '!=', 'SA');
            });
        }

        // Filter tab status
        $statusTab = $request->input('tab', 'aktif');
        if ($statusTab === 'pending') {
            $query->where('is_active', 0);
        } else {
            $query->where('is_active', 1);
        }

        // Hitung total pending untuk badge notifikasi
        $pendingCount = User::where('is_active', 0)->count();

        // 1. Search Global
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // 2. Filter Role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // 3. Sorting
        if ($request->filled('sort') && $request->filled('direction')) {
            $query->orderBy($request->sort, $request->direction);
        } else {
            $query->latest();
        }

        $users = $query->paginate(10)->withQueryString();
        $roles = Role::all();

        return view("manajemen-akun.index", compact('users', 'statusTab', 'pendingCount', 'roles'));
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
            'file' => 'required|mimes:xlsx,xls'
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.mimes' => 'Format harus .xlsx atau .xls'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return redirect()->route('super_admin.manajemen-akun')
                ->with('success', 'Data akun berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Import: ' . $e->getMessage());
        }
    }

    public function exportFormatAkun()
    {
        return Excel::download(new TemplateAkunExport, 'template_akun.xlsx');
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
}
