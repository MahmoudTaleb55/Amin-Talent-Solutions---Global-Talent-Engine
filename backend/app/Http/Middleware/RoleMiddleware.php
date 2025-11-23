<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Require authenticated user
        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // Support Spatie roles: allow access if user has any of the roles provided
        if (!empty($roles) && !$user->hasAnyRole($roles)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
