<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $remember)) {
            $request->session()->regenerate();

            AuditLog::record('auth.login', Auth::user());

            if (password_verify('admin123', Auth::user()->password)) {
                session()->flash('warning_password', 'Peringatan Keamanan: Anda masih menggunakan kata sandi bawaan (admin123). Demi keamanan sistem, segera perbarui kata sandi Anda.');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '.');
        }

        AuditLog::record('auth.login_failed', null, null, [
            'attempted_username' => $credentials['username'],
        ]);

        return back()->withErrors([
            'username' => 'Kredensial yang diberikan tidak cocok atau akun dinonaktifkan.',
        ])->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            AuditLog::record('auth.logout', Auth::user());
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar.');
    }
}
