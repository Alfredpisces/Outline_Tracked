<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) return redirect('/login');

        // 1. ADMIN BYPASS - Ensure this matches your DB column exactly
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 2. GET ACTIVE ROLE
        $activeRole = session('active_role');

        // Flatten the roles passed from the route (handles 'counselor|admin')
        $allowedRoles = [];
        foreach ($roles as $r) {
            $allowedRoles = array_merge($allowedRoles, explode('|', $r));
        }

        // 3. DEBUG BLOCK - Uncomment these 3 lines if you get 403 to see exactly why
        /*
        dd([
            'session_active_role' => $activeRole,
            'allowed_roles_for_route' => $allowedRoles,
            'user_has_role_in_db' => $user->roles()->where('name', $activeRole)->exists(),
            'user_db_roles' => $user->roles()->pluck('name')->toArray()
        ]);
        */

        // 4. VALIDATION
        if (!$activeRole) {
            abort(403, 'No active role session found.');
        }

        if (!in_array($activeRole, $allowedRoles)) {
            abort(403, "Role '$activeRole' is not authorized for this route. Allowed: " . implode(', ', $allowedRoles));
        }

        if (!$user->roles()->where('name', $activeRole)->exists()) {
            abort(403, "Database mismatch: You do not have the role '$activeRole' assigned in your account.");
        }

        return $next($request);
    }
}
