<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LisStaging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\Sf1Import;
use Maatwebsite\Excel\Facades\Excel;

class LisSyncController extends Controller
{
    /**
     * Upload page (LIS Sync index)
     */
    public function index()
    {
        // June-based school year default
        $currentMonth = (int) date('n');
        $currentYear  = (int) date('Y');
        $baseStartYear = $currentMonth >= 6 ? $currentYear : $currentYear - 1;

        $defaultSchoolYear = "{$baseStartYear}-" . ($baseStartYear + 1);

        // Rolling years shown in dropdown: previous 3, current, next 1
        $schoolYears = [];
        for ($i = -3; $i <= 1; $i++) {
            $start = $baseStartYear + $i;
            $schoolYears[] = "{$start}-" . ($start + 1);
        }

        $summary = session('sync_summary', [
            'last_run' => LisStaging::max('updated_at'),
            'inserted' => 0,
            'updated'  => 0,
            'skipped'  => 0,
        ]);

        return view('admin.lis_sync.index', compact('schoolYears', 'defaultSchoolYear', 'summary'));
    }

    /**
     * Handle the upload of the official LIS SF1 file using Maatwebsite Excel.
     */
    public function upload(Request $request)
    {
        $request->validate([
            // Added mimes validation to ensure only Excel/CSV files pass through
            'sf1_file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:10240'], // Max 10MB
            'school_year' => ['required', 'string'],
        ]);

        try {
            // Initialize our custom SF1 Importer
            $import = new Sf1Import($request->input('school_year'));

            // Let Maatwebsite process the Excel file
            Excel::import($import, $request->file('sf1_file'));

            // Update the summary session with the stats from the import class
            session(['sync_summary' => [
                'last_run' => now(),
                'inserted' => $import->inserted,
                'updated'  => $import->updated,
                'skipped'  => 0,
            ]]);

            return redirect()->route('admin.lis_sync.index')
                             ->with('success', "SF1 Processed Successfully! Added: {$import->inserted}, Updated: {$import->updated}");

        } catch (\Exception $e) {
            logger()->error('SF1 Import Failed: ' . $e->getMessage());
            return back()->withErrors(['sf1_file' => 'Failed to process the file. Please ensure it is a valid SF1 format. Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Manage records list with search, filter, and pagination
     */
    public function manage(Request $request)
    {
        $schoolYears = LisStaging::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        // Optional filters
        $search = $request->input('search');
        $syFilter = $request->input('school_year');

        $query = LisStaging::query();

        if ($syFilter) {
            $query->where('school_year', $syFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy('last_name')->orderBy('first_name')->paginate(20)->withQueryString();

        return view('admin.lis_sync.manage', compact('records', 'schoolYears', 'syFilter', 'search'));
    }

    /**
     * Show edit form for a specific staging record.
     */
    public function edit(LisStaging $lisStaging)
    {
        return view('admin.lis_sync.edit', compact('lisStaging'));
    }

    /**
     * Update staging record.
     */
    public function update(Request $request, LisStaging $lisStaging)
    {
        $validated = $request->validate([
            'lrn'            => 'required|string|max:20',
            'last_name'      => 'required|string|max:100',
            'first_name'     => 'required|string|max:100',
            'middle_name'    => 'nullable|string|max:100',
            'extension_name' => 'nullable|string|max:10',
            'gender'         => 'nullable|string|max:20',
            'birthdate'      => 'nullable|string|max:50',
            'grade_level'    => 'required|string|max:50',
            'section'        => 'required|string|max:100',
            'curriculum'     => 'nullable|string|max:100',
            'status'         => 'nullable|string|max:50',
        ]);

        // Prevent updating LRN to an existing one in the same SY
        if (
            $validated['lrn'] !== $lisStaging->lrn
            && LisStaging::where('lrn', $validated['lrn'])
                ->where('school_year', $lisStaging->school_year)
                ->exists()
        ) {
            return back()->withErrors(['lrn' => 'This LRN already exists for this School Year.']);
        }

        $lisStaging->update($validated);

        return redirect()
            ->route('admin.lis_sync.manage', ['school_year' => $lisStaging->school_year])
            ->with('success', 'Record updated successfully.');
    }

    /**
     * Delete a single staging record.
     */
    public function destroy(LisStaging $lisStaging)
    {
        $sy = $lisStaging->school_year;
        $lisStaging->delete();

        return redirect()
            ->route('admin.lis_sync.manage', ['school_year' => $sy])
            ->with('success', 'Record deleted.');
    }

    /**
     * Show absolute reset confirmation form.
     */
    public function resetForm()
    {
        $schoolYears = LisStaging::select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        return view('admin.lis_sync.reset', compact('schoolYears'));
    }

    /**
     * ABSOLUTE delete all records for a selected SY (type-to-confirm).
     */
    public function resetYear(Request $request)
    {
        $validated = $request->validate([
            'school_year'         => ['required', 'string'],
            'confirm_school_year' => ['required', 'string'],
        ]);

        if ($validated['school_year'] !== $validated['confirm_school_year']) {
            return back()
                ->withErrors(['confirm_school_year' => 'School year confirmation does not match.'])
                ->withInput();
        }

        $sy = $validated['school_year'];

        $deletedCount = DB::transaction(function () use ($sy) {
            $query = LisStaging::query()->where('school_year', $sy);
            $count = (clone $query)->count();

            $builder = (clone $query);

            if (method_exists($builder, 'forceDelete')) {
                $builder->forceDelete();
            } else {
                $builder->delete();
            }

            return $count;
        });

        return redirect()
            ->route('admin.lis_sync.reset')
            ->with('success', "ABSOLUTE DELETE complete: {$deletedCount} record(s) permanently removed for SY {$sy}.");
    }
}
