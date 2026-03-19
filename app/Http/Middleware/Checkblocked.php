<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Checkblocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (bool) Auth::user()->is_blocked) {
            // Laisser passer la déconnexion uniquement
            if ($request->routeIs('logout')) {
                return $next($request);
            }

            // Rediriger vers la page "compte bloqué"
            return redirect()->route('blocked');
        }

        return $next($request);
    }
}
