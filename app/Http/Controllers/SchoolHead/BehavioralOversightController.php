<?php

namespace App\Http\Controllers\SchoolHead;

use App\Http\Controllers\Controller;
use App\Models\IncidentReport;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BehavioralOversightController extends Controller
{
    /**
     * 1. Behavioral Analytics Dashboard (Flexible Dates)
     */
    /**
     * 1. Behavioral Analytics Dashboard (Flexible Dates)
     */
    public function analytics(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $from = $validated['start_date'] ?? now()->subDays(30)->toDateString();
        $to   = $validated['end_date'] ?? now()->toDateString();

        $baseQuery = IncidentReport::whereBetween('incident_date', [$from, $to]);

        $total = (clone $baseQuery)->count();
        $majorCount = (clone $baseQuery)->where('severity', 'major')->count();
        $criticalCount = (clone $baseQuery)->where('severity', 'critical')->count();
        $minorCount = (clone $baseQuery)->where('severity', 'minor')->count();

        // Active = Anything not resolved, turned over, or overridden
        $activeCount = (clone $baseQuery)->whereNotIn('status', ['resolved', 'turned_over', 'overridden'])->count();

        // Count resolved and turned over cases as successfully closed by the school
        $closedCount = (clone $baseQuery)->whereIn('status', ['resolved', 'turned_over', 'overridden'])->count();

        $resolvedRate = $total === 0 ? 0 : round(($closedCount / $total) * 100);

        // Fetch recent escalated cases for quick view
        $escalatedCases = IncidentReport::with('student')
            ->where('status', 'escalated')
            ->latest('incident_date')
            ->take(5)
            ->get();

        // --- ADDED FOR THE TREND CHART ---
        // Generates Data for the Trend Chart (Grouped by Date)
        $trendData = (clone $baseQuery)
            ->selectRaw('DATE(incident_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartDates = $trendData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d'); // e.g., "Jul 01"
        })->toArray();

        $chartData = $trendData->pluck('count')->toArray();
        // ---------------------------------

        // We inject 'chartDates' and 'chartData' into the compact array so the blade can read it
        return view('school_head.behavioral_oversight.index', compact(
            'from', 'to', 'total', 'majorCount', 'criticalCount', 'minorCount', 'activeCount', 'resolvedRate', 'escalatedCases',
            'chartDates', 'chartData'
        ));
    }

    /**
     * 2. Master Case Logs
     */
    public function cases(Request $request): View
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date'],
            'severity'   => ['nullable', 'in:minor,major,critical'],
            'status'     => ['nullable', 'string'],
            'q'          => ['nullable', 'string'],
        ]);

        $from = $validated['start_date'] ?? null;
        $to   = $validated['end_date'] ?? null;
        $severity = $validated['severity'] ?? null;
        $status = $validated['status'] ?? null;
        $q = trim($validated['q'] ?? '');

        $query = IncidentReport::with(['student', 'teacher']);

        if ($from && $to) {
            $query->whereBetween('incident_date', [$from, $to]);
        }
        if ($severity) $query->where('severity', $severity);
        if ($status) $query->where('status', $status);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('offense_type', 'like', "%{$q}%")
                    ->orWhere('narrative', 'like', "%{$q}%")
                    ->orWhereHas('student', function ($s) use ($q) {
                        $s->where('lrn', 'like', "%{$q}%")
                          ->orWhere('first_name', 'like', "%{$q}%")
                          ->orWhere('last_name', 'like', "%{$q}%");
                    });
            });
        }

        // Order by active/escalated first, then newest
        $incidents = $query
            ->orderByRaw("CASE WHEN status IN ('resolved', 'turned_over', 'overridden') THEN 1 ELSE 0 END")
            ->latest('incident_date')
            ->paginate(20)
            ->withQueryString();

        return view('school_head.behavioral_oversight.cases', compact(
            'incidents', 'from', 'to', 'severity', 'status', 'q'
        ));
    }

    /**
     * Case Detail View
     */
    public function show(IncidentReport $incident): View
    {
        $incident->load(['student', 'teacher']);

        // Determine if Principal can intervene
        $canIntervene = in_array($incident->severity, ['major', 'critical']) || $incident->status === 'escalated';

        return view('school_head.behavioral_oversight.show', compact('incident', 'canIntervene'));
    }

    /**
     * Update Case (Principal Action - keeps case open)
     */
    public function update(Request $request, IncidentReport $incident)
    {
        abort_if($incident->severity === 'minor' && $incident->status !== 'escalated', 403, 'Cannot intervene in minor offenses.');

        $validated = $request->validate([
            'principal_action' => ['required', 'string'],
            'status'           => ['required', 'in:pending,in_intervention,resolved,escalated,turned_over,overridden'],
        ]);

        $incident->update([
            'principal_action' => $validated['principal_action'],
            'status'           => $validated['status'],
        ]);

        return redirect()->route('school_head.behavior.show', $incident->id)
                         ->with('success', 'Case notes updated successfully.');
    }

    /**
     * Quick Resolve (Principal signs off or acknowledges handover)
     */
    public function resolve(Request $request, IncidentReport $incident)
    {
        abort_if($incident->severity === 'minor' && $incident->status !== 'escalated', 403, 'Cannot resolve minor offenses directly.');

        $validated = $request->validate([
            'principal_action' => ['nullable', 'string'],
        ]);

        // If it was turned over, keep it turned over. Otherwise, mark it resolved.
        $newStatus = $incident->status === 'turned_over' ? 'turned_over' : 'resolved';

        $incident->update([
            'status'           => $newStatus,
            'principal_action' => $validated['principal_action'] ?? $incident->principal_action,
        ]);

        $message = $newStatus === 'turned_over' ? 'External handover officially acknowledged.' : 'Case marked as resolved.';

        return redirect()->route('school_head.behavior.show', $incident->id)
                         ->with('success', $message);
    }

    /**
     * Override Critical Good Moral Block
     */
    public function override(Request $request, IncidentReport $incident)
    {
        abort_if($incident->severity !== 'critical', 403, 'Only critical legal matters require an override.');

        $incident->update([
            'status' => 'overridden',
        ]);

        return redirect()->route('school_head.behavior.show', $incident->id)
                         ->with('success', 'Good Moral block successfully overridden. The student is now cleared.');
    }

    /**
     * 3. Good Moral Clearance Audits
     */
    public function goodMoral(): View
    {
        // 1. BLOCKED STUDENTS: Found in the behavioral DB (students table) with active major/critical cases
        $blockedStudents = Student::whereHas('incidentReports', function ($query) {
            $query->where(function ($q) {
                $q->where('severity', 'major')->whereNotIn('status', ['resolved', 'overridden']);
            })->orWhere(function ($q) {
                $q->where('severity', 'critical')->where('status', '!=', 'overridden');
            });
        })->with(['incidentReports' => function ($q) {
            $q->where(function ($sub) {
                $sub->where('severity', 'major')->whereNotIn('status', ['resolved', 'overridden']);
            })->orWhere(function ($sub) {
                $sub->where('severity', 'critical')->where('status', '!=', 'overridden');
            })->latest('incident_date');
        }])->get();

        // Extract the exact LRNs of the blocked students
        $blockedLrns = $blockedStudents->pluck('lrn')->toArray();

        // 2. ELIGIBLE STUDENTS: Everyone in the LIS Staging roster EXCEPT the blocked LRNs.
        // We paginate this because a school might have thousands of eligible students.
        $eligibleStudents = DB::table('lis_stagings')
            ->whereNotIn('lrn', $blockedLrns)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15);

        return view('school_head.behavioral_oversight.good_moral', compact('blockedStudents', 'eligibleStudents'));
    }
}
