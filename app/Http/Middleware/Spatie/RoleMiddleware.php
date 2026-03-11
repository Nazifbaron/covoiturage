<?php

namespace App\Http\Middleware\Spatie;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role, $guard = null): Response
    {
        if (! $request->user($guard)?->hasRole($role)) {
            abort(403, 'Vous n’avez pas le rôle requis.');
        }

        return $next($request);
    }
}
