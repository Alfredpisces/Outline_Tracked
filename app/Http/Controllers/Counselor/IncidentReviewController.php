<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class IncidentReviewController extends Controller
{
    /**
     * Active cases (work queue)
     * NOTE: kept your logic but now paginated + allows optional query search.
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
        $request->validate([
            'lrn'               => 'required|string',
            'offense_type'      => 'required|string|max:255',
            // Updated to accept 'critical'
            'severity'          => 'required|in:minor,major,critical',
            'narrative'         => 'required|string',
            'incident_date'     => 'required|date',
            'intervention_type' => 'required|string',
            // Updated to accept 'turned_over'
            'status'            => 'required|in:in_intervention,resolved,escalated,turned_over,pending',
            'scheduled_at'      => 'nullable|date',
            'notes'             => 'nullable|string',
            'evidence_photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // allows return-to after create (optional)
            'redirect_to'       => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('evidence_photo')) {
            $path = $request->file('evidence_photo')->store('incident_evidence', 'public');
        }

        $studentId = null;

        DB::transaction(function () use ($request, $path, &$studentId) {
            $stagedStudent = DB::table('lis_stagings')->where('lrn', $request->lrn)->first();

            if (!$stagedStudent) {
                abort(404, 'Student not found in LIS records. Please verify the LRN.');
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

            $studentId = $student->id;

            IncidentReport::create([
                'student_id'        => $student->id,
                'teacher_id'        => auth()->id(),
                'offense_type'      => $request->offense_type,
                'severity'          => $request->severity,
                'narrative'         => $request->narrative,
                'incident_date'     => $request->incident_date,
                'status'            => $request->status,
                'intervention_type' => $request->intervention_type,
                'scheduled_at'      => $request->scheduled_at,
                'counselor_notes'   => $request->notes,
                'evidence_path'     => $path,
            ]);
        });

        // Redirect preference
        if ($request->filled('redirect_to')) {
            return redirect($request->string('redirect_to')->toString())
                ->with('status', 'Behavioral incident successfully logged.');
        }

        // If created from violators, nice UX is to go back to that student history
        if ($studentId) {
            return redirect()->route('counselor.violators.show', $studentId)
                ->with('status', 'Behavioral incident successfully logged.');
        }

        return redirect()->route('counselor.incidents.index')
            ->with('status', 'Behavioral incident successfully logged.');
    }

    public function resolve(Request $request, IncidentReport $incident): RedirectResponse
    {
        $request->validate([
            // Updated to accept 'turned_over'
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

        // Go back where the user came from if provided
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
            // Updated to accept 'critical'
            'severity'          => 'required|in:minor,major,critical',
            'narrative'         => 'required|string',
            'incident_date'     => 'required|date',
            // Updated to accept 'turned_over'
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

        // default UX: go to student's history after edit
        return redirect()->route('counselor.violators.show', $incident->student_id)
            ->with('status', 'Incident record updated securely.');
    }

    public function destroy(Request $request, IncidentReport $incident): RedirectResponse
    {
        $redirectTo = $request->input('redirect_to');

        if ($incident->evidence_path) {
            Storage::disk('public')->delete($incident->evidence_path);
        }

        $studentId = $incident->student_id;
        $incident->delete();

        // If caller specified redirect, respect it (best for deleting from student history)
        if (!empty($redirectTo)) {
            return redirect($redirectTo)->with('status', 'Incident record permanently deleted.');
        }

        // Default: go to student history if it exists
        if (!empty($studentId)) {
            return redirect()->route('counselor.violators.show', $studentId)
                ->with('status', 'Incident record permanently deleted.');
        }

        return redirect()->route('counselor.incidents.index')
            ->with('status', 'Incident record permanently deleted.');
    }
}
