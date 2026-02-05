<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userLevel = strtolower(auth()->user()->level); // Tambah strtolower

        // Convert roles to lowercase
        $roles = array_map('strtolower', $roles);

        if (!in_array($userLevel, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini. Level Anda: ' . $userLevel . ', Required: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}