<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\IncidentReport;
use App\Models\PublicTip; // <-- NEW: Added PublicTip model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class IncidentReviewController extends Controller
{
    /**
     * ==========================================
     * NEW: ACTIVE INTAKE QUEUE
     * ==========================================
     */
    public function queue(): View
    {
        // 1. Fetch Official Teacher Referrals waiting for Counselor review
        $pendingReferrals = IncidentReport::with(['student', 'teacher'])
            ->where('status', 'pending')
            ->latest('created_at')
            ->get();

        // 2. Fetch Unverified Public QR Tips (NOW LIVE!)
        $unverifiedTips = PublicTip::where('status', 'unverified')
            ->latest('created_at')
            ->get();

        return view('counselor.incidents.queue', compact('pendingReferrals', 'unverifiedTips'));
    }

    /**
     * Active cases (work queue)
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $incidentReports = IncidentReport::with(['student', 'teacher'])
            ->whereIn('status', ['pending', 'in_intervention']) // active queue
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('offense_type', 'like', "%{$q}%")
                        ->orWhere('narrative', 'like', "%{$q}%")
                        ->orWhereHas('student', function ($s) use ($q) {
                            $s->where('lrn', 'like', "%{$q}%")
                              ->orWhere('first_name', 'like', "%{$q}%")
                              ->orWhere('last_name', 'like', "%{$q}%");
                        });
                });
            })
            ->latest('incident_date')
            ->paginate(15)
            ->withQueryString();

        return view('counselor.incidents.index', compact('incidentReports', 'q'));
    }

    /**
     * Create page - supports prefill_lrn
     */
    public function create(Request $request): View
    {
        $prefillLrn = $request->query('prefill_lrn');
        return view('counselor.incidents.create', compact('prefillLrn'));
    }

    public function searchLis(Request $request)
    {
        try {
            $query = trim($request->query('q', ''));

            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $results = DB::table('lis_stagings')
                ->where(function ($q) use ($query) {
                    $q->where('lrn', 'LIKE', '%' . $query . '%')
                        ->orWhere('first_name', 'LIKE', '%' . $query . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $query . '%');
                })
                ->orderBy('last_name')
                ->limit(10)
                ->get(['lrn', 'first_name', 'last_name', 'grade_level', 'section']);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validate for an array of LRNs and the new 'other' intervention field
        $request->validate([
            'lrns'              => 'required|array|min:1',
            'lrns.*'            => 'required|string',
            'offense_type'      => 'required|string|max:255',
            'severity'          => 'required|in:minor,major,critical',
            'narrative'         => 'required|string',
            'incident_date'     => 'required|date',
            'intervention_type' => 'required|string',
            'intervention_custom'=> 'required_if:intervention_type,other|nullable|string|max:255',
            'status'            => 'required|in:in_intervention,resolved,escalated,turned_over,pending',
            'scheduled_at'      => 'nullable|date',
            'notes'             => 'nullable|string',
            'evidence_photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'redirect_to'       => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('evidence_photo')) {
            $path = $request->file('evidence_photo')->store('incident_evidence', 'public');
        }

        $finalIntervention = $request->intervention_type === 'other'
            ? $request->intervention_custom
            : $request->intervention_type;

        $isGroup = count($request->lrns) > 1;
        $finalNarrative = $isGroup
            ? "[GROUP INCIDENT] " . $request->narrative
            : $request->narrative;

        $firstStudentId = null;

        DB::transaction(function () use ($request, $path, $finalIntervention, $finalNarrative, &$firstStudentId) {

            foreach ($request->lrns as $index => $lrn) {
                $stagedStudent = DB::table('lis_stagings')->where('lrn', $lrn)->first();

                if (!$stagedStudent) {
                    abort(404, "Student with LRN {$lrn} not found in LIS records.");
                }

                $student = Student::firstOrCreate(
                    ['lrn' => $stagedStudent->lrn],
                    [
                        'first_name'  => $stagedStudent->first_name ?? 'Unknown',
                        'last_name'   => $stagedStudent->last_name ?? 'Unknown',
                        'middle_name' => $stagedStudent->middle_name ?? null,
                        'grade_level' => $stagedStudent->grade_level ?? null,
                        'section'     => $stagedStudent->section ?? null,
                    ]
                );

                if ($index === 0) {
                    $firstStudentId = $student->id;
                }

                IncidentReport::create([
                    'student_id'        => $student->id,
                    'teacher_id'        => auth()->id(),
                    'offense_type'      => $request->offense_type,
                    'severity'          => $request->severity,
                    'narrative'         => $finalNarrative,
                    'incident_date'     => $request->incident_date,
                    'status'            => $request->status,
                    'intervention_type' => $finalIntervention,
                    'scheduled_at'      => $request->scheduled_at,
                    'counselor_notes'   => $request->notes,
                    'evidence_path'     => $path,
                ]);
            }
        });

        if ($request->filled('redirect_to')) {
            return redirect($request->string('redirect_to')->toString())
                ->with('status', 'Behavioral incident successfully logged.');
        }

        if (count($request->lrns) === 1 && $firstStudentId) {
            return redirect()->route('counselor.violators.show', $firstStudentId)
                ->with('status', 'Behavioral incident successfully logged.');
        }

        return redirect()->route('counselor.incidents.index')
            ->with('status', 'Group behavioral incident successfully logged.');
    }

    public function resolve(Request $request, IncidentReport $incident): RedirectResponse
    {
        $request->validate([
            'status'            => 'required|in:in_intervention,resolved,escalated,turned_over,pending',
            'intervention_type' => 'nullable|string',
            'scheduled_at'      => 'nullable|date',
            'principal_action'  => 'nullable|string',
            'notes'             => 'required|string',
            'redirect_to'       => 'nullable|string',
        ]);

        $incident->update([
            'status'            => $request->status,
            'intervention_type' => $request->intervention_type,
            'scheduled_at'      => $request->scheduled_at,
            'principal_action'  => $request->principal_action,
            'notes'             => $request->notes,
        ]);

        if ($request->filled('redirect_to')) {
            return redirect($request->string('redirect_to')->toString())
                ->with('status', 'Incident case updated successfully.');
        }

        return back()->with('status', 'Incident case updated successfully.');
    }

    public function edit(IncidentReport $incident): View
    {
        $incident->load(['student', 'teacher']);
        return view('counselor.incidents.edit', compact('incident'));
    }

    public function update(Request $request, IncidentReport $incident): RedirectResponse
    {
        $request->validate([
            'offense_type'      => 'required|string|max:255',
            'severity'          => 'required|in:minor,major,critical',
            'narrative'         => 'required|string',
            'incident_date'     => 'required|date',
            'status'            => 'required|in:in_intervention,resolved,escalated,turned_over,pending',
            'notes'             => 'nullable|string',
            'evidence_photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'redirect_to'       => 'nullable|string',
        ]);

        $data = [
            'offense_type'    => $request->offense_type,
            'severity'        => $request->severity,
            'narrative'       => $request->narrative,
            'incident_date'   => $request->incident_date,
            'status'          => $request->status,
            'counselor_notes' => $request->notes,
        ];

        if ($request->hasFile('evidence_photo')) {
            if ($incident->evidence_path) {
                Storage::disk('public')->delete($incident->evidence_path);
            }
            $data['evidence_path'] = $request->file('evidence_photo')->store('incident_evidence', 'public');
        }

        $incident->update($data);

        if ($request->filled('redirect_to')) {
            return redirect($request->string('redirect_to')->toString())
                ->with('status', 'Incident record updated securely.');
        }

        return redirect()->route('counselor.violators.show', $incident->student_id)
            ->with('status', 'Incident record updated securely.');
    }

    public function printQr(): View
    {
        $publicReportUrl = url('/report/tip');
        return view('counselor.incidents.qr-print', compact('publicReportUrl'));
    }

    // Add these methods inside IncidentReviewController

    public function toggleIntake(Request $request): RedirectResponse
    {
        // Toggle the global cache flag
        $currentState = cache('public_intake_enabled', true);
        cache()->forever('public_intake_enabled', !$currentState);

        $status = !$currentState ? 'activated' : 'terminated';

        return back()->with('success', "The Public QR Intake link has been {$status}.");
    }

    public function triageTip(PublicTip $tip): View
    {
        // Prevent triaging an already processed tip
        if ($tip->status !== 'unverified') {
            abort(404, 'This tip has already been processed.');
        }

        return view('counselor.incidents.triage', compact('tip'));
    }

    public function processTriage(Request $request, PublicTip $tip): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:dismissed,turned_over_external'
        ]);

        // Update the tip status to remove it from the Active Queue
        $tip->update(['status' => $validated['action']]);

        // Set a dynamic success message based on the action
        $message = $validated['action'] === 'dismissed'
            ? 'Tip has been dismissed and archived as spam.'
            : 'Tip has been marked as Turned Over to External Authorities.';

        return redirect()->route('counselor.incidents.queue')->with('success', $message);
    }

    public function destroy(Request $request, IncidentReport $incident): RedirectResponse
    {
        $redirectTo = $request->input('redirect_to');

        if ($incident->evidence_path) {
            Storage::disk('public')->delete($incident->evidence_path);
        }

        $studentId = $incident->student_id;
        $incident->delete();

        if (!empty($redirectTo)) {
            return redirect($redirectTo)->with('status', 'Incident record permanently deleted.');
        }

        if (!empty($studentId)) {
            return redirect()->route('counselor.violators.show', $studentId)
                ->with('status', 'Incident record permanently deleted.');
        }

        return redirect()->route('counselor.incidents.index')
            ->with('status', 'Incident record permanently deleted.');
    }
}
