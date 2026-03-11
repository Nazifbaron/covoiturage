<?php

namespace App\Http\Middleware\Spatie;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission, $guard = null): Response
    {
        if (! $request->user($guard)?->can($permission)) {
            abort(403, 'Vous n’avez pas la permission requise.');
        }

        return $next($request);
    }
}
