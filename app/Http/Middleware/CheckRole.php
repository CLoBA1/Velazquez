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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Permitir 'admin' acceder a todo lo de 'staff'
            if ($role === 'staff' && $request->user()->role === 'admin') {
                return $next($request);
            }

            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
