<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next,string $role)
    {
        if (!auth()->check()){
            abort(401);
        }

        if (! auth()->user()->roles()->where('name',$role)->exists()){
            abort(403);
        }
        return $next($request);
    }
}
