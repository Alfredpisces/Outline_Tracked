<?php

// THIS LINE IS CRITICAL: It must say \Counselor at the end!
namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the counselor-specific dashboard.
     */
    public function index(): View
    {
        // 1. KPI Data
        $pendingIncidentsCount = IncidentReport::whereIn('status', ['pending', 'in_intervention'])->count();
        $violatorsCount = Student::has('incidentReports')->count();
        $certificatesCount = 0;

        // 2. Behavioral Analytics Data
        $severityStats = IncidentReport::selectRaw('severity, count(*) as total')
            ->groupBy('severity')
            ->pluck('total', 'severity');

        $minorCount = $severityStats['minor'] ?? 0;
        $majorCount = $severityStats['major'] ?? 0;
        $totalIncidents = $minorCount + $majorCount;

        // Calculate percentages
        $minorPercentage = $totalIncidents > 0 ? round(($minorCount / $totalIncidents) * 100) : 0;
        $majorPercentage = $totalIncidents > 0 ? round(($majorCount / $totalIncidents) * 100) : 0;

        return view('counselor.dashboard', compact(
            'pendingIncidentsCount',
            'violatorsCount',
            'certificatesCount',
            'minorCount',
            'majorCount',
            'totalIncidents',
            'minorPercentage',
            'majorPercentage'
        ));
    }
}
