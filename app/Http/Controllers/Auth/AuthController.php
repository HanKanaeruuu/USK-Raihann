<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan Anda memiliki view login
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi form login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        // Ambil kredensial yang diperlukan
        $credentials = $request->only('email', 'password');

        // Cek login menggunakan 'email' dan 'password'
        if (Auth::attempt($credentials)) {
            // Mendapatkan pengguna yang sedang login
            $user = Auth::user();

            // Arahkan berdasarkan role pengguna
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'bank_mini':
                    return redirect()->route('bankmini.dashboard');
                case 'siswa':
                    return redirect()->route('students.dashboard');
                default:
                    // Jika role tidak dikenali, logout dan kembali ke login
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['role' => 'Role tidak dikenali']);
            }
        }

        // Jika login gagal
        return redirect()->back()->withErrors(['email' => 'Email atau password salah']);
    }

    // Menampilkan form registrasi
    public function showRegisterForm()
    {
        return view('auth.register'); // Pastikan Anda memiliki view register
    }

    // Proses registrasi
    public function register(Request $request)
    {
        // Validasi input form
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,bank_mini,siswa',  // Pastikan role ditentukan saat registrasi
        ]);

        // Buat pengguna baru
        $user = new User();
        $user->email = $request->email; // pakai email
        $user->password = Hash::make($request->password); // password
        $user->role = $request->role; // Set role sesuai input
        $user->save();

        // Login otomatis setelah registrasi
        Auth::login($user);

        // Redirect ke halaman berdasarkan role
        return redirect()->route($user->role.'.dashboard');
    }

    // Proses logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
