<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk mengecek pengaturan
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('nim_nip', $request->email)
            ->orWhere('email', $request->email)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Akun tidak ditemukan.',
            ])->onlyInput('email');
        }

        if (!Hash::check($request->password, $user->password)) {
            // KIRIM KE PASSWORD
            return back()->withErrors([
                'password' => 'Kata sandi yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // ==========================================
        // CEK STATUS AKTIVASI SEBELUM LOGIN
        // ==========================================
        if (isset($user->is_active) && $user->is_active == 0) {
            // Tolak akses login dan kirim pesan untuk trigger popup di view
            return redirect()->route('login')
                ->with('aktivasi_pending', true)
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route($user->role . '.dashboard');
    }

    // 3. TAMPILKAN HALAMAN REGISTER
    public function showRegister()
    {
        return view('auth.register');
    }

    // 4. PROSES REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim_nip' => ['required', 'string', 'max:20', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ==========================================
        // CEK PENGATURAN MASTER "WAJIB AKTIVASI"
        // ==========================================
        $wajibAktivasi = DB::table('pengaturan_sistem')
            ->where('kunci', 'wajib_aktivasi_mahasiswa')
            ->value('nilai') ?? '0';

        // Jika bernilai '1' (Wajib), set is_active = 0. Jika '0' (Tidak wajib), set 1.
        $isActive = ($wajibAktivasi === '1') ? 0 : 1;

        $user = User::create([
            'name' => $request->name,
            'nim_nip' => $request->nim_nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
            'is_active' => $isActive, // Simpan status
        ]);

        // ==========================================
        // PERCABANGAN ARAH SETELAH REGISTRASI
        // ==========================================
        if ($isActive === 0) {
            // Jika butuh aktivasi: JANGAN loginkan. Lempar ke halaman login + trigger popup.
            return redirect()->route('login')->with('aktivasi_pending', true);
        } else {
            // Jika otomatis aktif: Loginkan dan lempar ke Dashboard
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('mahasiswa.dashboard')->with('status', 'Registrasi berhasil! Selamat datang.');
        }
    }

    // 5. PROSES LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
