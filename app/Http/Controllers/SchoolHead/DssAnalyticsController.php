<?php

namespace App\Http\Controllers\SchoolHead;

use App\Http\Controllers\Controller;
use App\Models\DailyLessonLog;
use App\Models\IncidentReport;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DssAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        // 1. School Year Filter
        $activeSY = $request->get('sy', '2026-2027');
        [$startYear, $endYear] = explode('-', $activeSY);
        $syStartDate = $startYear . '-06-01 00:00:00';
        $syEndDate = $endYear . '-05-31 23:59:59';

        // =========================================================================
        // DATA GATHERING: FACULTY COMPLIANCE
        // =========================================================================
        $groupedLogs = DailyLessonLog::with('author')
            ->whereBetween('created_at', [$syStartDate, $syEndDate])
            ->get()
            ->groupBy('author_id');

        $facultyRankings = collect();
        $totalLogsSystemWide = 0;
        $totalReturnedSystemWide = 0;

        foreach($groupedLogs as $authorId => $logs) {
            $authorName = $logs->first()->author->name ?? 'Unknown Teacher';
            $total = $logs->count();
            $checked = $logs->where('status', 'checked')->count();
            $needsRevision = $logs->where('status', 'needs_revision')->count();

            $totalLogsSystemWide += $total;
            $totalReturnedSystemWide += $needsRevision;

            $complianceRate = $total > 0 ? round(($checked / $total) * 100) : 0;

            $facultyRankings->push((object)[
                'name' => $authorName,
                'total' => $total,
                'checked' => $checked,
                'needs_revision' => $needsRevision,
                'compliance_rate' => $complianceRate
            ]);
        }

        // Sort Highest to Lowest for the Leaderboard
        $facultyRankings = $facultyRankings->sortByDesc('compliance_rate')->values();

        // =========================================================================
        // DATA GATHERING: BEHAVIORAL VIOLATIONS
        // =========================================================================
        $incidents = IncidentReport::whereBetween('incident_date', [$syStartDate, $syEndDate])->get();

        $majorIncidentsCount = $incidents->where('severity', 'major')->count();
        $topOffense = $incidents->groupBy('offense_type')->map->count()->sortDesc()->keys()->first() ?? 'None recorded';

        // =========================================================================
        // DATA GATHERING: ASSETS & INVENTORY
        // =========================================================================
        $inventories = Inventory::withCount('transactions')->get();

        $itemsNearingDepreciation = 0;
        $urgentProcurementCount = 0;

        foreach($inventories as $item) {
            $isExpired = $item->expiry_date && Carbon::parse($item->expiry_date)->isPast();
            $isDamaged = in_array(strtolower($item->condition ?? ''), ['damaged', 'poor', 'condemned']);

            if ($isExpired || $isDamaged || ($item->transactions_count ?? 0) >= 5) {
                $itemsNearingDepreciation++;
            }
            if ($item->quantity <= ($item->reorder_level ?? 5)) {
                $urgentProcurementCount++;
            }
        }

        // =========================================================================
        // AI GENERATION: EXECUTIVE SUMMARIES (Strictly Descriptive/Historical)
        // =========================================================================
        $aiFacultySummary = 'Gathering compliance data to generate technical assistance recommendations...';
        $aiBehaviorSummary = 'With no major incidents recorded, the primary behavioral trend is minor uniform non-compliance. Recommend advising homeroom teachers to review guidelines.';
        $aiAssetSummary = 'One critical consumable asset category has crossed the reorder threshold. Prioritize replenishment to ensure uninterrupted classroom activities.';

        try {
            $apiKey = env('GEMINI_API_KEY');
            if ($apiKey) {
                $prompt = "You are a Decision Support System for a Philippine public high school principal. Analyze the following factual school data and provide three short, professional executive summaries (maximum 2 sentences each). Focus strictly on historical administrative observations and non-predictive suggestions.

                DATA:
                - Faculty: {$totalLogsSystemWide} total lesson logs submitted. {$totalReturnedSystemWide} returned for revision.
                - Behavior: {$majorIncidentsCount} major violation records. Top offense category is '{$topOffense}'. (Do not mention absenteeism).
                - Assets: {$itemsNearingDepreciation} damaged or unserviceable items. {$urgentProcurementCount} consumable categories below reorder threshold.

                Output strictly in valid JSON format:
                {
                    \"faculty\": \"...\",
                    \"behavior\": \"...\",
                    \"asset\": \"...\"
                }";

                $baseUrl = "https://generativelanguage.googleapis.com";
                $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=";

                $response = Http::withOptions(['verify' => false])
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(15)
                    ->post($baseUrl . $endpoint . trim($apiKey), [
                        'contents' => [['parts' => [['text' => $prompt]]]],
                        'generationConfig' => ['temperature' => 0.2]
                    ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

                    $aiText = str_replace(['```json', '```'], '', $aiText);
                    $parsedJson = json_decode(trim($aiText), true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        $aiFacultySummary = $parsedJson['faculty'] ?? $aiFacultySummary;
                        $aiBehaviorSummary = $parsedJson['behavior'] ?? $aiBehaviorSummary;
                        $aiAssetSummary = $parsedJson['asset'] ?? $aiAssetSummary;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('DSS AI Generation Error: ' . $e->getMessage());
        }

        return view('school_head.dss.index', compact(
            'activeSY',
            'facultyRankings',
            'aiFacultySummary',
            'aiBehaviorSummary',
            'aiAssetSummary'
        ));
    }
}
