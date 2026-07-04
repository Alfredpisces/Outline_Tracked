<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Check if they selected a specific workspace session.
        // 2. If not, fall back to their primary database role.
        $activeRole = session('active_role', $user->role);

        // Redirect to the specific isolated controllers based on workspace
        switch ($activeRole) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            case 'counselor':
                // This redirect triggers your Counselor/DashboardController to run its math!
                return redirect()->route('counselor.dashboard');
            case 'school_head':
                return redirect()->route('school_head.dashboard');
            default:
                return view('dashboard');
        }
    }
}
