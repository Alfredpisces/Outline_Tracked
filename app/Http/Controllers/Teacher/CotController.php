<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CotObservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CotController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        // Fetch all their observations from the database, ordered by newest first
        $observations = CotObservation::where('teacher_id', $teacher->id)
            ->with('observer')
            ->orderBy('created_at', 'desc')
            ->get();

        // Split them into two groups for the two tables on your dashboard
        $activeRequests = $observations->whereIn('status', ['pending', 'scheduled']);
        $completedObservations = $observations->where('status', 'completed');

        // Pass all variables to the view
        return view('teacher.cot.index', compact('teacher', 'activeRequests', 'completedObservations'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'observation_date' => 'required|date|after_or_equal:today',
            'time_started' => 'required',
            'time_ended' => 'required|after:time_started',
            'subject_taught' => 'required|string',
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'quarter' => 'required|integer|min:1|max:4',
        ]);

        // Save the request to the database
        CotObservation::create([
            'teacher_id' => Auth::id(),
            'status' => 'pending', // Starts as pending until School Head approves
            'observation_date' => $request->observation_date,
            'time_started' => $request->time_started,
            'time_ended' => $request->time_ended,
            'subject_taught' => $request->subject_taught,
            'grade_level' => $request->grade_level,
            'section' => $request->section,
            'quarter' => $request->quarter,
        ]);

        return back()->with('status', 'Observation request submitted successfully. Waiting for School Head approval.');
    }
}
