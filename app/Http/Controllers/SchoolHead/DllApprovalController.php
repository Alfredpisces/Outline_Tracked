<?php

namespace App\Http\Controllers\SchoolHead;

use App\Http\Controllers\Controller;
use App\Models\DailyLessonLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DllApprovalController extends Controller
{
    // 1. Dashboard Index with School Year Filter
    public function index(Request $request): View
    {
        $activeSY = $request->get('sy', '2026-2027');
        [$startYear, $endYear] = explode('-', $activeSY);
        $syStartDate = $startYear . '-06-01 00:00:00';
        $syEndDate = $endYear . '-05-31 23:59:59';

        $groupedLogs = DailyLessonLog::with(['author', 'entries'])
            ->whereBetween('created_at', [$syStartDate, $syEndDate])
            ->latest('submitted_at')
            ->get()
            ->groupBy(function ($log) {
                return $log->author ? $log->author->name : 'Unassigned Teacher';
            });

        return view('school_head.dll.index', compact('groupedLogs', 'activeSY'));
    }

    // 2. Mark as Checked (DepEd Compliant)
    public function markAsChecked(Request $request, DailyLessonLog $dll): RedirectResponse
    {
        $dll->update([
            'reviewer_id' => $request->user()->id,
            'status' => 'checked',
            'is_ai_passed' => true,
        ]);
        return back()->with('status', 'Lesson plan successfully marked as Checked.');
    }

    // 3. Return for Technical Assistance (DepEd Compliant)
    public function provideTechnicalAssistance(Request $request, DailyLessonLog $dll): RedirectResponse
    {
        $data = $request->validate([
            'review_feedback' => ['required', 'string']
        ]);

        $dll->update([
            'reviewer_id' => $request->user()->id,
            'status' => 'needs_revision',
            'reflection_notes' => trim(($dll->reflection_notes ? $dll->reflection_notes."\n\n" : '').'🛡️ Technical Assistance from School Head: '.$data['review_feedback']),
        ]);
        return back()->with('status', 'Technical assistance sent to the teacher.');
    }

    // 4. Excel Download
    public function exportChecklist()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DllChecklistExport, 'Official_DLL_Checklist.xlsx');
    }

    // 5. Official Document Viewer
    public function show(DailyLessonLog $dll): View
    {
        $dll->load(['author', 'entries' => function ($query) {
            $query->orderBy('entry_date', 'asc');
        }]);
        return view('school_head.dll.show', compact('dll'));
    }

    public function generateAiBrief(Request $request, DailyLessonLog $dll)
    {
        try {
            $dll->load('entries');

            $lessonContext = "Subject: {$dll->subject}\nGrade: {$dll->grade_quarter}\nDates: {$dll->teaching_dates}\n\n";

            $hasContent = false;
            foreach ($dll->entries as $entry) {
                if ($entry->procedure_a || $entry->procedure_b || $entry->procedure_c || $entry->procedure_e) {
                    $hasContent = true;
                    $lessonContext .= "--- Date: {$entry->entry_date} ---\n";
                    $lessonContext .= "Goal/Intentions: {$entry->procedure_a} {$entry->procedure_b}\n";
                    $lessonContext .= "Activities: {$entry->procedure_c}\n";
                    $lessonContext .= "Assessment: {$entry->procedure_e}\n\n";
                }
            }

            if (!$hasContent) {
                 return response()->json([
                    'brief' => 'The lesson matrix appears to be empty. No analysis can be performed.',
                    'suggested_feedback' => 'Please complete your daily matrix before submitting.'
                ]);
            }

            $prompt = "You are an expert DepEd School Head / Educational Supervisor reviewing a teacher's Weekly Lesson Log.
            Analyze the following lesson plan.

            Return ONLY a raw JSON object (no markdown wrapping, no ```json). Follow this exact structure:
            {
                \"brief\": \"A 3-4 sentence professional summary evaluating the overall pedagogical quality, alignment of activities to goals, and DepEd compliance.\",
                \"suggested_feedback\": \"A specific, constructive 1-2 sentence note offering 'Technical Assistance' to the teacher. If the plan is perfect, offer a word of professional encouragement.\"
            }

            Lesson Plan Data to analyze:
            " . $lessonContext;

            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                throw new \Exception("The GEMINI_API_KEY is missing in the .env file.");
            }

            // EDITOR-PROOF URL & UPGRADED 2026 MODEL (gemini-2.5-flash)
            $baseUrl = "https://" . "generativelanguage.googleapis.com";
            $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=";
            $apiUrl = $baseUrl . $endpoint . trim($apiKey);

            $response = \Illuminate\Support\Facades\Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($apiUrl, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Google API Error: ' . $response->body());
            }

            $result = $response->json();
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

            return response()->json(json_decode(trim($aiText), true));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('School Head AI Error: ' . $e->getMessage());

            return response()->json([
                'brief' => '⚠️ System Error: ' . $e->getMessage(),
                'suggested_feedback' => 'Please review document manually.'
            ]);
        }
    }
}
