<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek kredensial
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Pastikan email sudah terverifikasi
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'Email Anda belum terverifikasi. Silakan periksa email Anda untuk verifikasi.',
                ]);
            }

            // Arahkan ke halaman beranda setelah login
            return redirect()->intended('/dashboard');
        }

        // Jika kredensial tidak valid
        throw ValidationException::withMessages([
            'email' => 'Email/Password anda salah atau belum terdaftar',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
