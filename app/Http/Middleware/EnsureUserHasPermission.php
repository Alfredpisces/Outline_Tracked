<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        // ADD THIS BYPASS: This allows Admins to pass regardless of specific permissions
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Existing logic for non-admin users
        foreach ($permissions as $permission) {
            if ($user->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        abort(403);
    }
}
