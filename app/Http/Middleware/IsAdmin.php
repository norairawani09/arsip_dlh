<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || strtolower(auth()->user()->role) !== 'admin') {
            abort(403);
        }
        return $next($request);
    }
}
