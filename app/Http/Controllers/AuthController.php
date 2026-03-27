<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{

    public function showLogin()
    {
        $pengaturan = DB::table('pengaturan_sistem')->pluck('nilai', 'kunci')->toArray();
        return view('auth.login', compact('pengaturan'));
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Mencari user berdasarkan NIM/NIP atau Email
        $user = User::where('nim_nip', $request->email)
            ->orWhere('email', $request->email)
            ->first();

        // 1. Cek keberadaan user
        if (!$user) {
            return back()->withErrors([
                'email' => 'Akun tidak ditemukan.',
            ])->onlyInput('email');
        }

        // 2. Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Kata sandi yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 3. Cek status aktivasi (is_active)
        if (isset($user->is_active) && $user->is_active == 0) {
            return redirect()->route('login')
                ->with('aktivasi_pending', true)
                ->withInput();
        }

        // 4. Proses Login
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // 5. Penentuan rute berdasarkan kode_role (SA, AD, FK, JR, MHS)
        // Ini adalah perbaikan untuk error "RouteNotFoundException" kamu
        $role = $user->role->kode_role;

        $routeName = match ($role) {
            'SA'  => 'super_admin.dashboard',
            'AD'  => 'admin.dashboard',
            'FK'  => 'fakultas.dashboard',
            'JR'  => 'jurusan.dashboard',
            'MHS' => 'mahasiswa.dashboard',
            default => 'home'
        };

        return redirect()->route($routeName);
    }

    public function showRegister()
    {
        $pengaturan = DB::table('pengaturan_sistem')->pluck('nilai', 'kunci')->toArray();
        return view('auth.register', compact('pengaturan'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim_nip' => ['required', 'string', 'max:20', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Ambil pengaturan "Wajib Aktivasi" dari database
        $wajibAktivasi = DB::table('pengaturan_sistem')
            ->where('kunci', 'wajib_aktivasi_mahasiswa')
            ->value('nilai') ?? '0';

        $isActive = ($wajibAktivasi === '1') ? 0 : 1;

        // Ambil ID Role untuk Mahasiswa (MHS)
        $roleMhs = Role::where('kode_role', 'MHS')->first();

        $user = User::create([
            'name' => $request->name,
            'nim_nip' => $request->nim_nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleMhs->id, // Menggunakan role_id sesuai struktur baru
            'is_active' => $isActive,
        ]);

        if ($isActive === 0) {
            // Jika butuh aktivasi, jangan login otomatis
            return redirect()->route('login')->with('aktivasi_pending', true);
        } else {
            // Jika otomatis aktif, login dan arahkan ke dashboard
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('mahasiswa.dashboard')->with('status', 'Registrasi berhasil!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
