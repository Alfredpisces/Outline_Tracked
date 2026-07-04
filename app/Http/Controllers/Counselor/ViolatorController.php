<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ViolatorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $students = Student::has('incidentReports')
            ->withCount('incidentReports')
            ->with(['incidentReports' => fn ($query) => $query->latest('incident_date')])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('lrn', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('incident_reports_count')
            ->paginate(15)
            ->withQueryString();

        return view('counselor.violators.index', compact('students', 'search'));
    }

    public function show($id): View
    {
        $student = Student::with([
            'incidentReports' => function ($query) {
                $query->orderBy('incident_date', 'desc')->with('teacher');
            }
        ])->findOrFail($id);

        return view('counselor.violators.show', compact('student'));
    }
}
