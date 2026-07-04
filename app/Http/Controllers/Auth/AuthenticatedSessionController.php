<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $rolesCount = $user->roles()->count();

        if ($rolesCount > 1) {
            return redirect()->route('role.select');
        }

        $primaryRole = $user->roles()->first()?->name ?? $user->role;
        session(['active_role' => $primaryRole]);

        // Redirect directly to the dedicated dashboards on login
        return match ($primaryRole) {
            'admin'       => redirect()->route('admin.dashboard'),
            'teacher'     => redirect()->route('teacher.dashboard'),
            'counselor'   => redirect()->route('counselor.dashboard'),
            'school_head' => redirect()->route('school_head.dashboard'),
            default       => redirect()->intended(route('dashboard')),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('active_role');
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
