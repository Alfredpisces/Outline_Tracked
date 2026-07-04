<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GoodMoralController extends Controller
{
    public function index(Request $request): View
    {
        $student = null;
        $targetSy = trim($request->string('target_sy')->toString());

        if ($request->filled('student_lrn') && $request->filled('target_sy')) {
            $lrn = trim($request->string('student_lrn')->toString());

            $student = DB::table('lis_stagings')
                ->where('lrn', $lrn)
                ->where('school_year', $targetSy)
                ->first();

            if ($student) {
                $hasBlockingOffense = DB::table('students')
                    ->join('incident_reports', 'students.id', '=', 'incident_reports.student_id')
                    ->where('students.lrn', $lrn)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->where('incident_reports.severity', 'major')
                              ->whereNotIn('incident_reports.status', ['resolved', 'overridden']);
                        })->orWhere(function ($q) {
                            $q->where('incident_reports.severity', 'critical')
                              ->where('incident_reports.status', '!=', 'overridden');
                        });
                    })
                    ->exists();

                $student->is_eligible = !$hasBlockingOffense;
                $student->full_name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
            }
        }

        $schoolYear = trim($request->string('school_year')->toString());
        $scope = $request->string('scope')->toString() ?: 'grade';
        $gradeLevel = trim($request->string('grade_level')->toString());
        $gradeLevels = collect(['7', '8', '9', '10', '11', '12']);

        $eligibleCount = null;
        $blockedCount = null;

        if ($schoolYear !== '') {
            if ($scope === 'grade' && $gradeLevel === '') {
                return view('counselor.good_moral.index', [
                    'student' => $student,
                    'schoolYear' => $schoolYear,
                    'scope' => $scope,
                    'gradeLevel' => $gradeLevel,
                    'eligibleCount' => null,
                    'blockedCount' => null,
                ]);
            }

            $base = DB::table('lis_stagings as ls')->where('ls.school_year', $schoolYear);

            // robust grade matching to handle different Excel upload formats
            if ($scope === 'grade') {
                $base->where(function($q) use ($gradeLevel) {
                    $q->where('ls.grade_level', $gradeLevel)
                      ->orWhere('ls.grade_level', 'Grade ' . $gradeLevel)
                      ->orWhere('ls.grade_level', '0' . $gradeLevel);
                });
            } else {
                $base->where(function($q) use ($gradeLevels) {
                    $q->whereIn('ls.grade_level', $gradeLevels->all())
                      ->orWhereIn('ls.grade_level', $gradeLevels->map(fn($g) => "Grade $g")->all())
                      ->orWhereIn('ls.grade_level', $gradeLevels->map(fn($g) => "0$g")->all());
                });
            }

            $rows = (clone $base)
                ->leftJoin('students as s', 's.lrn', '=', 'ls.lrn')
                ->leftJoin('incident_reports as ir', function ($join) {
                    $join->on('ir.student_id', '=', 's.id')
                        ->where(function ($q) {
                            $q->where(function ($sub) {
                                $sub->where('ir.severity', 'major')
                                    ->whereNotIn('ir.status', ['resolved', 'overridden']);
                            })->orWhere(function ($sub) {
                                $sub->where('ir.severity', 'critical')
                                    ->where('ir.status', '!=', 'overridden');
                            });
                        });
                })
                ->groupBy('ls.lrn')
                ->selectRaw("ls.lrn, COUNT(ir.id) as blocking_offense_count")
                ->get();

            $eligibleCount = $rows->filter(fn ($r) => (int) $r->blocking_offense_count === 0)->count();
            $blockedCount  = $rows->filter(fn ($r) => (int) $r->blocking_offense_count > 0)->count();
        }

        return view('counselor.good_moral.index', [
            'student' => $student,
            'schoolYear' => $schoolYear,
            'scope' => $scope,
            'gradeLevel' => $gradeLevel,
            'eligibleCount' => $eligibleCount,
            'blockedCount' => $blockedCount,
        ]);
    }

    // -------------------------------------------------------------
    // RESTORED: Single Certificate Generation
    // -------------------------------------------------------------
    public function generate(Request $request)
    {
        $request->validate([
            'student_lrn' => 'required|string|size:12',
            'target_sy' => 'required|string'
        ]);

        $lrn = trim($request->student_lrn);
        $sy = trim($request->target_sy);

        $student = DB::table('lis_stagings')->where('lrn', $lrn)->where('school_year', $sy)->first();
        abort_unless($student, 404, 'No active enrollment found for this LRN in SY ' . $sy);

        $hasBlockingOffense = DB::table('students')
            ->join('incident_reports', 'students.id', '=', 'incident_reports.student_id')
            ->where('students.lrn', $lrn)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('incident_reports.severity', 'major')
                      ->whereNotIn('incident_reports.status', ['resolved', 'overridden']);
                })->orWhere(function ($q) {
                    $q->where('incident_reports.severity', 'critical')
                      ->where('incident_reports.status', '!=', 'overridden');
                });
            })
            ->exists();

        abort_if($hasBlockingOffense, 422, 'Student is blocked due to major/critical infractions.');

        $student->full_name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));

        $content = view('counselor.good_moral.certificate', compact('student'))->render();
        $filename = Str::slug($student->full_name ?: $lrn) . '-good-moral-' . Str::slug($sy) . '.html';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    // -------------------------------------------------------------
    // BATCH GENERATION
    // -------------------------------------------------------------
    public function generateBatch(Request $request)
    {
        $validated = $request->validate([
            'school_year' => ['required', 'string'],
            'scope' => ['required', 'in:grade,all'],
            'grade_level' => ['nullable', 'string'],
        ]);

        $schoolYear = trim($validated['school_year']);
        $scope = $validated['scope'];
        $gradeLevel = trim($validated['grade_level'] ?? '');
        $gradeLevels = collect(['7', '8', '9', '10', '11', '12']);

        if ($scope === 'grade' && $gradeLevel === '') abort(422, 'Grade required.');

        $base = DB::table('lis_stagings as ls')->where('ls.school_year', $schoolYear);

        if ($scope === 'grade') {
            $base->where(function($q) use ($gradeLevel) {
                $q->where('ls.grade_level', $gradeLevel)
                  ->orWhere('ls.grade_level', 'Grade ' . $gradeLevel)
                  ->orWhere('ls.grade_level', '0' . $gradeLevel);
            });
        } else {
            $base->where(function($q) use ($gradeLevels) {
                $q->whereIn('ls.grade_level', $gradeLevels->all())
                  ->orWhereIn('ls.grade_level', $gradeLevels->map(fn($g) => "Grade $g")->all())
                  ->orWhereIn('ls.grade_level', $gradeLevels->map(fn($g) => "0$g")->all());
            });
        }

        $rows = (clone $base)
            ->leftJoin('students as s', 's.lrn', '=', 'ls.lrn')
            ->leftJoin('incident_reports as ir', function ($join) {
                $join->on('ir.student_id', '=', 's.id')
                    ->where(function ($q) {
                        $q->where(function ($sub) {
                            $sub->where('ir.severity', 'major')
                                ->whereNotIn('ir.status', ['resolved', 'overridden']);
                        })->orWhere(function ($sub) {
                            $sub->where('ir.severity', 'critical')
                                ->where('ir.status', '!=', 'overridden');
                        });
                    });
            })
            // Added ls.id to ensure the certificate gets a unique ID if available!
            ->groupBy(
                'ls.id',
                'ls.lrn',
                'ls.first_name',
                'ls.last_name',
                'ls.middle_name',
                'ls.extension_name',
                'ls.grade_level',
                'ls.section',
                'ls.school_year'
            )
            ->selectRaw("
                ls.id,
                ls.lrn,
                ls.first_name,
                ls.last_name,
                ls.middle_name,
                ls.extension_name,
                ls.grade_level,
                ls.section,
                ls.school_year,
                COUNT(ir.id) as blocking_offense_count
            ")
            ->orderBy('ls.last_name')
            ->orderBy('ls.first_name')
            ->get();

        $eligible = $rows->filter(fn ($r) => (int) $r->blocking_offense_count === 0)->values();

        foreach($eligible as $student) {
            $student->full_name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
        }

        $content = view('counselor.good_moral.batch', [
            'students' => $eligible,
            'schoolYear' => $schoolYear,
            'scope' => $scope,
            'gradeLevel' => $gradeLevel,
        ])->render();

        $fileBase = $scope === 'all' ? "good-moral-eligible-SY-{$schoolYear}-all-grades" : "good-moral-eligible-SY-{$schoolYear}-grade-{$gradeLevel}";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, Str::slug($fileBase) . '.html', ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    public function exportBlocked(Request $request)
    {
        $validated = $request->validate([
            'school_year' => ['required', 'string'],
            'scope' => ['required', 'in:grade,all'],
            'grade_level' => ['nullable', 'string'],
        ]);

        $schoolYear = trim($validated['school_year']);
        $scope = $validated['scope'];
        $gradeLevel = trim($validated['grade_level'] ?? '');
        $gradeLevels = collect(['7', '8', '9', '10', '11', '12']);

        if ($scope === 'grade' && $gradeLevel === '') abort(422, 'Grade required.');

        $base = DB::table('lis_stagings as ls')->where('ls.school_year', $schoolYear);

        if ($scope === 'grade') {
            $base->where(function($q) use ($gradeLevel) {
                $q->where('ls.grade_level', $gradeLevel)
                  ->orWhere('ls.grade_level', 'Grade ' . $gradeLevel)
                  ->orWhere('ls.grade_level', '0' . $gradeLevel);
            });
        } else {
            $base->where(function($q) use ($gradeLevels) {
                $q->whereIn('ls.grade_level', $gradeLevels->all())
                  ->orWhereIn('ls.grade_level', $gradeLevels->map(fn($g) => "Grade $g")->all())
                  ->orWhereIn('ls.grade_level', $gradeLevels->map(fn($g) => "0$g")->all());
            });
        }

        $rows = (clone $base)
            ->leftJoin('students as s', 's.lrn', '=', 'ls.lrn')
            ->leftJoin('incident_reports as ir', function ($join) {
                $join->on('ir.student_id', '=', 's.id')
                    ->where(function ($q) {
                        $q->where(function ($sub) {
                            $sub->where('ir.severity', 'major')
                                ->whereNotIn('ir.status', ['resolved', 'overridden']);
                        })->orWhere(function ($sub) {
                            $sub->where('ir.severity', 'critical')
                                ->where('ir.status', '!=', 'overridden');
                        });
                    });
            })
            ->groupBy(
                'ls.id',
                'ls.lrn',
                'ls.first_name',
                'ls.last_name',
                'ls.middle_name',
                'ls.extension_name',
                'ls.grade_level',
                'ls.section',
                'ls.school_year'
            )
            ->selectRaw("
                ls.id,
                ls.lrn,
                ls.first_name,
                ls.last_name,
                ls.middle_name,
                ls.extension_name,
                ls.grade_level,
                ls.section,
                ls.school_year,
                COUNT(ir.id) as blocking_offense_count
            ")
            ->orderBy('ls.last_name')
            ->orderBy('ls.first_name')
            ->get();

        $blocked = $rows->filter(fn ($r) => (int) $r->blocking_offense_count > 0)->values();

        $content = view('counselor.good_moral.blocked_pdf', [
            'blocked' => $blocked,
            'schoolYear' => $schoolYear,
            'scope' => $scope,
            'gradeLevel' => $gradeLevel,
        ])->render();

        $fileBase = $scope === 'all' ? "good-moral-BLOCKED-SY-{$schoolYear}-all-grades" : "good-moral-BLOCKED-SY-{$schoolYear}-grade-{$gradeLevel}";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, Str::slug($fileBase) . '.html', ['Content-Type' => 'text/html; charset=UTF-8']);
    }
}
