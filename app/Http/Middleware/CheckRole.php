<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Ambil level user dan ubah ke lowercase untuk konsistensi
        $userLevel = strtolower(auth()->user()->level);

        // Convert semua roles yang diizinkan ke lowercase
        $roles = array_map('strtolower', $roles);

        // Cek apakah user level ada di dalam roles yang diizinkan
        if (!in_array($userLevel, $roles)) {
            // Jika tidak ada akses, return 403 error dengan pesan
            abort(403, sprintf(
                'Anda tidak memiliki akses ke halaman ini.%sLevel Anda: %s%sLevel yang diizinkan: %s',
                PHP_EOL,
                ucfirst($userLevel),
                PHP_EOL,
                implode(', ', array_map('ucfirst', $roles))
            ));
        }

        return $next($request);
    }
}