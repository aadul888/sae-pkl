<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'Akun Anda telah dinonaktifkan oleh Administrator.',
            ]);
        }

        // Jika tidak ada batasan role spesifik atau role pengguna cocok
        if (empty($roles) || in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses (role) yang sesuai untuk mengakses sumber daya ini.');
    }
}
