<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LisStaging;
use App\Models\Student;
use App\Models\IncidentReport;

class IncidentManagerController extends Controller
{
    public function index()
    {
        // 🔒 STRICT CONFIDENTIALITY FILTER:
        // Fetch reports ONLY submitted by this logged-in teacher
        $submittedReports = IncidentReport::with('student')
            ->where('teacher_id', Auth::id()) // <--- THIS IS THE SECURE LOCK
            ->latest('created_at')
            ->get();

        return view('teacher.behavior.index', compact('submittedReports'));
    }
    // Add these methods below your existing store() method

    public function edit(IncidentReport $incident)
    {
        // 🔒 SECURITY CHECK: Only allow editing if it belongs to the teacher AND is still pending
        if ($incident->teacher_id !== Auth::id() || $incident->status !== 'pending') {
            abort(403, 'You can only edit pending reports that you submitted.');
        }

        // We load the student relationship so we can display their name in the form
        $incident->load('student');

        return view('teacher.behavior.edit', compact('incident'));
    }

    public function update(Request $request, IncidentReport $incident)
    {
        // 🔒 SECURITY CHECK
        if ($incident->teacher_id !== Auth::id() || $incident->status !== 'pending') {
            abort(403, 'You can only edit pending reports that you submitted.');
        }

        $validated = $request->validate([
            'offense_type'  => 'required|string|not_in:Absent,Tardy,Cutting Classes',
            'severity'      => 'required|in:minor,major,critical',
            'narrative'     => 'required|string|min:10',
            'incident_date' => 'required|date',
        ]);

        $incident->update([
            'offense_type'  => $validated['offense_type'],
            'severity'      => $validated['severity'],
            'narrative'     => $validated['narrative'],
            'incident_date' => $validated['incident_date'],
        ]);

        return redirect()->route('teacher.behavior.index')
                         ->with('success', 'Incident report successfully updated.');
    }

    public function destroy(IncidentReport $incident)
    {
        // 🔒 SECURITY CHECK
        if ($incident->teacher_id !== Auth::id() || $incident->status !== 'pending') {
            abort(403, 'You can only delete pending reports that you submitted.');
        }

        $incident->delete();

        return redirect()->route('teacher.behavior.index')
                         ->with('success', 'Incident report permanently retracted.');
    }

    public function create()
    {
        $enrolledStudents = LisStaging::select('id', 'lrn', 'first_name', 'last_name', 'grade_level', 'section')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('teacher.behavior.create', compact('enrolledStudents'));
    }

    public function store(Request $request)
    {
        // 1. Validate the array of students AND the new evidence photo
        $validated = $request->validate([
            'student_ids'    => 'required|array|min:1',
            'student_ids.*'  => 'exists:lis_stagings,id',
            'offense_type'   => 'required|string|not_in:Absent,Tardy,Cutting Classes',
            'severity'       => 'required|in:minor,major,critical',
            'narrative'      => 'required|string|min:10',
            'incident_date'  => 'required|date',
            // NEW: Secure image validation (max 2MB)
            'evidence_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Handle the Photo Upload (If the teacher attached one)
        $evidencePath = null;
        if ($request->hasFile('evidence_photo')) {
            $evidencePath = $request->file('evidence_photo')->store('incident_evidence', 'public');
        }

        // 3. Loop through EVERY student selected
        foreach ($validated['student_ids'] as $stagedId) {

            $lisStudent = LisStaging::findOrFail($stagedId);

            $student = Student::firstOrCreate(
                ['lrn' => $lisStudent->lrn],
                [
                    'first_name'  => $lisStudent->first_name,
                    'last_name'   => $lisStudent->last_name,
                    'middle_name' => $lisStudent->middle_name ?? null,
                    'grade_level' => $lisStudent->grade_level,
                    'section'     => $lisStudent->section,
                ]
            );

            IncidentReport::create([
                'student_id'    => $student->id,
                'teacher_id'    => Auth::id(),
                'offense_type'  => $validated['offense_type'],
                'severity'      => $validated['severity'],
                'narrative'     => count($validated['student_ids']) > 1
                                     ? "[GROUP INCIDENT] " . $validated['narrative']
                                     : $validated['narrative'],
                'incident_date' => $validated['incident_date'],
                'evidence_path' => $evidencePath, // <-- SAVES THE PHOTO
                'status'        => 'pending',
            ]);
        }

        return redirect()->route('teacher.behavior.index')
                         ->with('success', 'Incident securely logged with evidence and escalated to the Counselor.');
    }
    }
