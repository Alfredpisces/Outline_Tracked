<?php

namespace App\Http\Controllers\SchoolHead;

use App\Http\Controllers\Controller;
use App\Models\DailyLessonLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DssAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        // 1. School Year Filter (Matches the DLL Dashboard)
        $activeSY = $request->get('sy', '2026-2027');
        [$startYear, $endYear] = explode('-', $activeSY);
        $syStartDate = $startYear . '-06-01 00:00:00';
        $syEndDate = $endYear . '-05-31 23:59:59';

        // 2. Fetch all logs for the year and group by Teacher
        $groupedLogs = DailyLessonLog::with('author')
            ->whereBetween('created_at', [$syStartDate, $syEndDate])
            ->get()
            ->groupBy('author_id');

        // 3. Calculate DSS Scores
        $rankings = collect();

        foreach($groupedLogs as $authorId => $logs) {
            $authorName = $logs->first()->author->name ?? 'Unknown Teacher';
            $total = $logs->count();

            // Calculate metrics
            $checked = $logs->where('status', 'checked')->count();
            $needsRevision = $logs->where('status', 'needs_revision')->count();
            $pending = $logs->where('status', 'submitted')->count();

            // Compliance Formula: (Checked Logs / Total Submitted Logs) * 100
            $score = $total > 0 ? round(($checked / $total) * 100) : 0;

            $rankings->push((object)[
                'name' => $authorName,
                'total' => $total,
                'checked' => $checked,
                'needs_revision' => $needsRevision,
                'pending' => $pending,
                'score' => $score
            ]);
        }

        // 4. Sort from Highest Score to Lowest
        $rankings = $rankings->sortByDesc('score')->values();

        // 5. Split the data for the UI (Top 3 Podium vs. The Rest)
        $topPerformers = $rankings->take(3);
        $otherTeachers = $rankings->skip(3);

        return view('school_head.dss.index', compact('topPerformers', 'otherTeachers', 'activeSY'));
    }
}
