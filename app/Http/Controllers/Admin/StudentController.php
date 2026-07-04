<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LisStaging;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display the student list automatically using the latest synced data.
     */
    public function index(): View
    {
        // 1. Automatically detect the most recently uploaded school year
        $activeYear = LisStaging::max('school_year');

        // 2. Fetch the students for that automatic year from the STAGING table
        // We use paginate(50) instead of get() so the browser doesn't crash on huge lists
        $students = LisStaging::where('school_year', $activeYear)
                              ->orderBy('last_name', 'asc')
                              ->paginate(50);

        // 3. Return the view with the automatic year data
        // (Make sure the name here matches your actual blade file: 'list' or 'index')
        return view('admin.students.list', compact('students', 'activeYear'));
    }

    /**
     * Show the edit form.
     */
    public function edit(LisStaging $student): View
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update student details.
     */
    public function update(Request $request, LisStaging $student): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'grade_level' => 'required|string',
            'section'     => 'required|string',
            'status'      => 'required|string',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.list')
                         ->with('success', 'Student details updated successfully.');
    }

    /**
     * Delete a student.
     */
    public function destroy(LisStaging $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('admin.students.list')
                         ->with('success', 'Student deleted successfully.');
    }
}
