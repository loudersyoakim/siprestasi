<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Imports\UsersImport;
use App\Exports\TemplateAkunExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $query = User::query();

        // --- PROTEKSI 1: Admin biasa tidak boleh melihat Super Admin ---
        if (Auth::user()->role !== 'super_admin') {
            $query->where('role', '!=', 'super_admin');
        }

        // 1. Search Global
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");

                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $search)) {
                    $date = Carbon::createFromFormat('d/m/Y', $search)->format('Y-m-d');
                    $q->orWhereDate('created_at', $date);
                } else {
                    $bulanIndo = [
                        'januari' => '01',
                        'februari' => '02',
                        'maret' => '03',
                        'april' => '04',
                        'mei' => '05',
                        'juni' => '06',
                        'juli' => '07',
                        'agustus' => '08',
                        'september' => '09',
                        'oktober' => '10',
                        'november' => '11',
                        'desember' => '12'
                    ];

                    if (array_key_exists($search, $bulanIndo)) {
                        $q->orWhereMonth('created_at', $bulanIndo[$search]);
                    } elseif (is_numeric($search) && strlen($search) == 4) {
                        $q->orWhereYear('created_at', $search);
                    }
                }
            });
        }

        // 2. Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 3. Sorting
        if ($request->filled('sort') && $request->filled('direction')) {
            $query->orderBy($request->sort, $request->direction);
        } else {
            $query->latest();
        }

        $users = $query->paginate(10)->withQueryString();
        return view('admin.manajemen-akun', compact('users'));
    }

    public function createAkun()
    {
        return view('admin.manajemen-akun-create');
    }

    public function storeAkun(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim_nip' => 'required|string|unique:users',
            'email' => 'nullable|email|unique:users',
            'role' => 'required|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // --- PROTEKSI 2: Admin tidak boleh membuat role Super Admin ---
        if (Auth::user()->role !== 'super_admin' && $request->role === 'super_admin') {
            return back()->with('error', 'Anda tidak diizinkan membuat akun Super Admin!');
        }

        $passwordFinal = $request->filled('password')
            ? Hash::make($request->password)
            : Hash::make($request->nim_nip);

        User::create([
            'name' => $request->name,
            'nim_nip' => $request->nim_nip,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $passwordFinal,
        ]);

        return redirect()->route('admin.manajemen-akun')->with('success', 'Akun berhasil ditambahkan!');
    }

    public function editAkun($id)
    {
        $user = User::findOrFail($id);

        // --- PROTEKSI 3: Blokir Admin yang mencoba edit Super Admin lewat URL ---
        if (Auth::user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return redirect()->route('admin.manajemen-akun')->with('error', 'Akses Ditolak! Anda tidak bisa mengedit Super Admin.');
        }

        return view('admin.manajemen-akun-edit', compact('user'));
    }

    public function updateAkun(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // --- PROTEKSI 4: Mencegah update ke role Super Admin jika yang mengedit Admin biasa ---
        if (Auth::user()->role !== 'super_admin' && ($user->role === 'super_admin' || $request->role === 'super_admin')) {
            return redirect()->route('admin.manajemen-akun')->with('error', 'Izin Ditolak!');
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255|unique:users,email,' . $id,
            'nim_nip' => 'required|string|unique:users,nim_nip,' . $id,
            'role'    => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->nim_nip = $request->nim_nip;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.manajemen-akun')->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroyAkun($id)
    {
        $user = User::findOrFail($id);

        // --- PROTEKSI 5: Admin dilarang menghapus Super Admin ---
        if (Auth::user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return back()->with('error', 'Anda tidak berwenang menghapus Super Admin!');
        }

        // Opsional: Mencegah hapus diri sendiri
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Gunakan menu profil untuk manajemen akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }

    public function importAkun(Request $request)
    {
        // 1. Validasi file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes' => 'Format file harus .xlsx atau .xls'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::queueImport(new \App\Imports\UsersImport, $request->file('file'));

            // 3. Beri respon cepat ke user
            return redirect()->back()->with('success', 'Import akun sedang diproses di latar belakang. Silakan cek berkala.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function exportFormatAkun()
    {
        return Excel::download(new TemplateAkunExport, 'template_akun.xlsx');
    }
}
