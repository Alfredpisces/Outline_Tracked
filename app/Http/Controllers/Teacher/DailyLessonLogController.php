<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\DailyLessonLog;
use App\Models\TeachingLoad;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DailyLessonLogController extends Controller
{
    public function index(Request $request): View
    {
        $profiles = TeachingLoad::query()
            ->where('teacher_id', $request->user()->id)
            ->orderBy('subject')
            ->orderBy('grade_level')
            ->get();

        $query = $request->user()->authoredDailyLessonLogs();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dailyLessonLogs = $query
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('teacher.dll.index', compact('profiles', 'dailyLessonLogs'));
    }

    public function resubmit(\Illuminate\Http\Request $request, \App\Models\DailyLessonLog $dll)
    {
        $request->validate([
            'teacher_remarks' => 'nullable|string'
        ]);

        $updatedNotes = $dll->reflection_notes;
        if ($request->filled('teacher_remarks')) {
            $updatedNotes .= "\n\n👩‍🏫 Teacher's Reply: " . $request->teacher_remarks;
        }

        $dll->update([
            'status' => 'submitted',
            'reflection_notes' => $updatedNotes,
            'submitted_at' => now(),
        ]);

        return back()->with('status', 'Your revised lesson plan has been resubmitted to the School Head.');
    }

    public function submit(\App\Models\DailyLessonLog $dll)
    {
        $dll->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('status', 'Lesson Log successfully submitted to your School Head for review!');
    }

    public function create(Request $request): View
    {
        return view('teacher.dll.create', [
            'draftKey' => 'dll_draft_' . $request->user()->id . '_new',
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        return $this->importOffline($request);
    }

    public function show(Request $request, DailyLessonLog $dll): View
    {
        abort_unless(
            $dll->author_id === $request->user()->id || in_array($request->user()->role, ['school_head', 'admin']),
            403
        );

        return view('teacher.dll.show', compact('dll'));
    }

    public function edit(Request $request, DailyLessonLog $dll): View
    {
        abort_unless($dll->author_id === $request->user()->id, 403);
        return view('teacher.dll.edit', compact('dll'));
    }

    public function update(Request $request, DailyLessonLog $dll): RedirectResponse|JsonResponse
    {
        abort_unless($dll->author_id === $request->user()->id, 403);
        return $this->importOffline($request);
    }

    public function destroy(Request $request, DailyLessonLog $dll): RedirectResponse
    {
        abort_unless($dll->author_id === $request->user()->id, 403);
        $dll->delete();
        return redirect()->route('teacher.dll.index')->with('success', 'Weekly Lesson Log deleted successfully.');
    }

    public function saveEntry(Request $request, DailyLessonLog $dll, $date)
    {
        $data = $request->validate([
            'procedure_a' => 'nullable|string',
            'procedure_b' => 'nullable|string',
            'procedure_c' => 'nullable|string',
            'procedure_d' => 'nullable|string',
            'procedure_e' => 'nullable|string',
            'reflection_notes' => 'nullable|string',
        ]);

        $dll->entries()->updateOrCreate(
            ['entry_date' => $date],
            $data
        );

        return response()->json(['message' => 'Saved successfully']);
    }

    public function storeProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'string', 'max:255'],
        ]);

        TeachingLoad::create([
            'teacher_id' => $request->user()->id,
            'subject' => $validated['subject'],
            'grade_level' => $validated['grade_level'],
        ]);

        return redirect()->route('teacher.dll.index')->with('success', 'Subject Profile saved successfully.');
    }

    public function week(Request $request, DailyLessonLog $dll): View
    {
        abort_unless($dll->author_id === $request->user()->id, 403);

        $entries = $dll->entries()->orderBy('entry_date')->get()->keyBy(fn($e) => $e->entry_date->toDateString());

        $weekDays = [];
        if ($dll->week_start_date) {
            for ($i = 0; $i < 5; $i++) {
                $weekDays[] = $dll->week_start_date->copy()->addDays($i)->toDateString();
            }
        }

        return view('teacher.dll.week', compact('dll', 'entries', 'weekDays'));
    }

    // =========================================================================
    // THE NEW PRE-FLIGHT COMPLIANCE SCANNER (LIVE AI CONNECTION)
    // =========================================================================
    public function scanCompliance(Request $request, DailyLessonLog $dll)
    {
        $data = $request->validate([
            'lesson_context' => 'required|string',
            'previous_feedback' => 'nullable|string'
        ]);

        $lessonContext = $data['lesson_context'];
        $previousFeedback = $data['previous_feedback'] ?? 'No previous feedback provided.';

        $prompt = "You are a strict, expert DepEd Master Teacher conducting a Pre-Flight Compliance Check on a Weekly Lesson Log.
        Analyze the submitted week-long lesson plan.

        Your evaluation MUST check for:
        1. Constructive Alignment: Do the 'Assessments/Evaluations' directly measure the stated 'Intentions/Goals' for each day?
        2. Pedagogical Soundness: Are the learning experiences engaging and logically sequenced?
        3. School Head Revisions: Did the teacher specifically address this previous feedback from their principal: '{$previousFeedback}'?

        You MUST return ONLY a raw JSON object with NO markdown wrapping (do not use ```json). Follow this exact structure:
        {
            \"revisions_addressed\": true,
            \"revision_feedback\": \"A specific sentence explaining if they fixed the principal's issue or missed it.\",
            \"alignment_feedback\": \"A strict evaluation of how well their daily activities and assessments align with their goals.\",
            \"action_items\": [\"Specific tweak 1\", \"Specific tweak 2\"]
        }

        Here is the Weekly Lesson Plan to analyze:
        " . $lessonContext;

        try {
            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                throw new \Exception("The GEMINI_API_KEY is completely missing or empty in Laravel's memory.");
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
            \Illuminate\Support\Facades\Log::error('Pre-Flight Scanner Error: ' . $e->getMessage());

            return response()->json([
                "revisions_addressed" => false,
                "revision_feedback" => "Error details below:",
                "alignment_feedback" => $e->getMessage(),
                "action_items" => ["Read the error message above to identify the exact problem."]
            ]);
        }
    }

    public function importOffline(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->all();

            $combinedContentStandard = trim('Content Standard: ' . ($data['content_standard'] ?? '') . "\nPerformance Standard: " . ($data['performance_standard'] ?? ''));
            $combinedCompetencies = trim('Competencies: ' . ($data['competencies'] ?? '') . "\nObjectives: " . ($data['objectives'] ?? '') . "\nContent Topic: " . ($data['content'] ?? '') . "\nIntegration: " . ($data['integration'] ?? ''));
            $combinedResources = trim('References: ' . ($data['references'] ?? '') . "\nOther Materials: " . ($data['other_resources'] ?? ''));

            $weekStart = $data['week_start_date'] ?? now()->startOfWeek()->toDateString();
            $weekEnd = $data['week_end_date'] ?? now()->endOfWeek()->toDateString();

            $action = $data['action'] ?? 'draft';
            $status = $action === 'submit' ? 'pending' : 'draft';
            $submittedAt = $action === 'submit' ? now() : null;

            $dll = DailyLessonLog::query()->updateOrCreate(
                [
                    'author_id' => $request->user()->id,
                    'week_start_date' => $weekStart,
                ],
                [
                    'reviewer_id' => null,
                    'subject' => $data['subject'] ?? 'Untitled Subject',
                    'grade_quarter' => $data['grade_quarter'] ?? 'Unknown Grade',
                    'teaching_dates' => $weekStart . ' to ' . $weekEnd,
                    'week_end_date' => $weekEnd,
                    'content_standard' => $combinedContentStandard ?: 'Not Specified',
                    'competencies' => $combinedCompetencies ?: 'Not Specified',
                    'resources' => $combinedResources ?: 'Not Specified',
                    'procedure_a' => 'Moved to Daily Matrix',
                    'procedure_b' => 'Moved to Daily Matrix',
                    'procedure_c' => 'Moved to Daily Matrix',
                    'procedure_d' => 'Moved to Daily Matrix',
                    'procedure_e' => 'Moved to Daily Matrix',
                    'passed_count' => $data['passed_count'] ?? null,
                    'remedial_count' => $data['remedial_count'] ?? null,
                    'reflection_notes' => $data['reflection_notes'] ?? null,
                    'status' => $status,
                    'submitted_at' => $submittedAt,
                ]
            );

            foreach (($data['entries'] ?? []) as $e) {
                if (empty($e['entry_date']))
                    continue;

                $combinedReflection = null;
                if (!empty($e['remarks']) || !empty($e['reflection'])) {
                    $combinedReflection = trim('Remarks: ' . ($e['remarks'] ?? '') . "\nReflection: " . ($e['reflection'] ?? ''));
                }

                $dll->entries()->updateOrCreate(
                    ['entry_date' => $e['entry_date']],
                    [
                        'procedure_a' => $e['procedure_a'] ?? null,
                        'procedure_b' => $e['procedure_b'] ?? null,
                        'procedure_c' => $e['procedure_c'] ?? null,
                        'procedure_d' => $e['procedure_d'] ?? null,
                        'procedure_e' => $e['procedure_e'] ?? null,
                        'resources' => $e['resources'] ?? null,
                        'reflection_notes' => $combinedReflection,
                    ]
                );
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Weekly DLL Draft Compiled Successfully.',
                    'dll_id' => $dll->id,
                ]);
            }

            return redirect()->route('teacher.dll.index')->with('success', 'MATATAG Weekly Log compiled successfully.');
        } catch (\Exception $e) {
            Log::error('DLL Compile Error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Database Error: ' . $e->getMessage()], 500);
            }

            return back()->withErrors(['db_error' => 'Database Error: ' . $e->getMessage()]);
        }
    }
}
