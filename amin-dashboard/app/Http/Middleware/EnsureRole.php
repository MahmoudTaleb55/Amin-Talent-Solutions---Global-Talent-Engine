<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // If roles list is empty (misconfiguration), deny by default
        if (empty($roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Support roles passed as comma-separated in a single string
        $flat = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $p) {
                $p = trim($p);
                if ($p !== '') $flat[] = $p;
            }
        }

        if (!in_array((string) $user->role, $flat, true)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
