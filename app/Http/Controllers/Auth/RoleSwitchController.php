<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    public function switch(string $role): RedirectResponse
    {
        $user = Auth::user();

        // Security check
        if ($user->role !== 'admin' && !$user->roles()->where('name', $role)->exists()) {
            abort(403, 'You are not authorized to use this workspace.');
        }

        // Set the active session role
        session(['active_role' => $role]);

        // Redirect to their specific dashboard
        return match ($role) {
            'admin'       => redirect()->route('admin.dashboard'),
            'teacher'     => redirect()->route('teacher.dashboard'),
            'counselor'   => redirect()->route('counselor.dashboard'),
            'school_head' => redirect()->route('school_head.dashboard'),
            default       => redirect()->route('dashboard'),
        };
    }
}
