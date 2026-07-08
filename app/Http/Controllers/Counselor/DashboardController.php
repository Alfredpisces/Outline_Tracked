<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\Student;
use App\Models\PublicTip; // <-- Make sure to add this!
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Top Level Snapshot Counts
        $pendingIncidentsCount = IncidentReport::where('status', 'pending')->count();
        $violatorsCount = Student::has('incidentReports')->count();

        // Assuming you have a GoodMoral model, adjust this if yours is different.
        // If you don't have this mapped yet, you can leave it as 0 or your existing logic.
        $certificatesCount = 0;

        // 2. NEW: Action Required Alerts
        $unverifiedTipsCount = PublicTip::where('status', 'unverified')->count();

        // 3. NEW: Quick-Access Work Queue (Latest 4 pending referrals)
        $recentPendingCases = IncidentReport::with(['student', 'teacher'])
            ->where('status', 'pending')
            ->latest('created_at')
            ->take(4)
            ->get();

        // 4. Upgraded Analytics (Now includes Critical)
        $totalIncidents = IncidentReport::count();

        $minorCount = IncidentReport::where('severity', 'minor')->count();
        $majorCount = IncidentReport::where('severity', 'major')->count();
        $criticalCount = IncidentReport::where('severity', 'critical')->count();

        $minorPercentage = $totalIncidents > 0 ? round(($minorCount / $totalIncidents) * 100) : 0;
        $majorPercentage = $totalIncidents > 0 ? round(($majorCount / $totalIncidents) * 100) : 0;
        $criticalPercentage = $totalIncidents > 0 ? round(($criticalCount / $totalIncidents) * 100) : 0;

        return view('counselor.dashboard', compact(
            'pendingIncidentsCount',
            'violatorsCount',
            'certificatesCount',
            'unverifiedTipsCount',
            'recentPendingCases',
            'totalIncidents',
            'minorCount', 'minorPercentage',
            'majorCount', 'majorPercentage',
            'criticalCount', 'criticalPercentage'
        ));
    }
}
