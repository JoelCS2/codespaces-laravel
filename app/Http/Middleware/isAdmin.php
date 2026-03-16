<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('api')->user() && auth('api')->user()->is_admin) {
            return $next($request);
        } else {
            return response()->json(['message' => 'No autorizado, no tienes el rol de administrador'], 401);
        }
        return $next($request);
    }
}
