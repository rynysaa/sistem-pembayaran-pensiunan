<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Role yang diizinkan mengakses route ini
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login → redirect ke halaman login
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        // Sudah login tapi role tidak sesuai → redirect ke dashboard sendiri
        if (! in_array(Auth::user()->role, $roles, true)) {
            return $this->redirectToOwnDashboard(Auth::user()->role);
        }

        return $next($request);
    }

    /**
     * Redirect ke dashboard sesuai role pengguna yang sedang login.
     */
    private function redirectToOwnDashboard(string $role): \Illuminate\Http\RedirectResponse
    {
        $route = match ($role) {
            'hrd'      => 'hrd.dashboard',
            'keuangan' => 'keuangan.dashboard',
            'pensiunan' => 'pensiunan.dashboard',
            default    => 'login',
        };

        return redirect()->route($route)
            ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}

