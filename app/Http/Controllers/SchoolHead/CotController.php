<?php

namespace App\Http\Controllers\SchoolHead;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CotObservation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CotController extends Controller
{
    public function index(): View
    {
        $teachers = User::whereHas('roles', function ($query) {$query->where('name', 'teacher');
        })->orderBy('last_name')->get();

        $pendingRequests = CotObservation::with('teacher')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $masterTeachers = User::whereHas('roles', function ($query) {$query->where('name', 'teacher');
        })->where('deped_rank', 'LIKE', '%Master%')->get();

        return view('school_head.cot.index', compact('teachers', 'pendingRequests', 'masterTeachers'));
    }

    public function assignObserver(Request $request,$id): RedirectResponse
    {
        $request->validate(['observer_id' => 'required|exists:users,id']);

        $observation = CotObservation::findOrFail($id);$observation->update([
            'observer_id' => $request->observer_id,
            'status' => 'scheduled'
        ]);

        return back()->with('status', 'Observer successfully assigned. Schedule approved.');
    }

    public function blankForm(Request $request): View
    {
        $tier =$request->query('tier', 'proficient');
        $indicators =$this->getDepEdIndicators();
        return view('school_head.cot.blank', compact('tier', 'indicators'));
    }

    public function create(User $teacher): View
    {
        $indicators =$this->getDepEdIndicators();
        return view('school_head.cot.create', compact('teacher', 'indicators'));
    }

    public function store(Request $request, User$teacher): RedirectResponse
    {
        $indicators =$this->getDepEdIndicators();
        $submittedScores =$request->input('scores', []);

        $processedScores = [];$totalScore = 0;
        $count = 0;

        foreach ($submittedScores as $num =>$score) {
            $processedScores[$num] = [
                'title' => $indicators[$num] ?? 'Indicator ' .$num,
                'score' => $score
            ];

            if (is_numeric($score)) {
                $totalScore +=$score;
                $count++;
            }
        }

        $averageRating =$count > 0 ? round($totalScore / $count, 2) : 0;

        $observation = CotObservation::create([
            'teacher_id' => $teacher->id,
            'observer_id' => Auth::id(),
            'status' => 'completed',
            'observation_date' => $request->input('observation_date', now()->format('Y-m-d')),
            'time_started' => $request->input('time_started', '00:00:00'),
            'time_ended' => $request->input('time_ended', '00:00:00'),             'section' =>$request->input('section', 'N/A'),
            'subject_taught' => $request->input('subject_taught'),
            'grade_level' => $request->input('grade_level'),
            'quarter' => $request->input('quarter'),
            'rating' => $averageRating,
            'comments' => $request->input('comments', 'No comments provided.'),
            'scores' => $processedScores
        ]);

        session()->forget('scanned_scores');

        return redirect()->route('school_head.cot.show', $observation->id)
                         ->with('status', 'Classroom Observation Rating saved successfully.');
    }

    public function show($id): View
    {
        $observation = CotObservation::with(['teacher', 'observer'])->findOrFail($id);
        return view('school_head.cot.show', compact('observation'));
    }

    // =========================================================================
    // UPGRADED: Gemini 2.5 Flash Vision Scanner
    // =========================================================================
    public function scan(Request $request)
    {
        $request->validate([
            'cot_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath =$request->file('cot_image')->store('temp_scans');
        $absolutePath = Storage::path($imagePath);

        if (!file_exists($absolutePath)) {
            return back()->with('error', 'Upload failed: Could not locate the image file.');
        }

        try {
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) {
                throw new \Exception("GEMINI_API_KEY is missing from your .env file.");
            }

            // Convert image to Base64 for the Gemini API
            $mimeType = mime_content_type($absolutePath);
            $base64Image = base64_encode(file_get_contents($absolutePath));

            $prompt = "You are an expert AI assistant tasked with extracting data from a Philippine DepEd Classroom Observation Tool (COT) rating sheet.
            Carefully read the handwritten text on this form.
            Extract the observed teacher's full name and the numerical scores (ranging from 2 to 8) for Indicators 1 through 9.
            Return ONLY a valid, raw JSON object without any markdown wrapping (do not use ```json).
            Format exactly like this:
            {
                \"teacher_name\": \"First Last\",
                \"scores\": {
                    \"1\": 5,
                    \"2\": 6,
                    \"3\": null,
                    \"4\": 7,
                    \"5\": 5,
                    \"6\": 6,
                    \"7\": 7,
                    \"8\": 8,
                    \"9\": 4
                }
            }
            If a score is missing, blank, or you cannot read it due to handwriting, set the value to null.";

            $baseUrl = "https://" . "generativelanguage.googleapis.com";
            $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=";
            $apiUrl = $baseUrl . $endpoint . trim($apiKey);

            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)
                ->post($apiUrl, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inlineData' => [
                                        'mimeType' => $mimeType,
                                        'data' => $base64Image
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1, // Low temperature for factual extraction
                        'responseMimeType' => 'application/json',
                    ]
                ]);

            // Clean up the temp file immediately after API call
            unlink($absolutePath);

            if (!$response->successful()) {
                throw new \Exception('Google API Error: ' . $response->body());
            }

            $result = $response->json();
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

            // Parse the JSON returned by Gemini
            $extractedData = json_decode(trim($aiText), true);

            if (!$extractedData || !isset($extractedData['scores'])) {
                 return back()->with('error', 'AI Scanner failed to return structured data. Please ensure the image is clear.');
            }

            // Map the JSON to our expected session array (strictly indicators 1-9)
            $scannedScores = [];
            $missingCount = 0;

            for ($i = 1; $i <= 9; $i++) {
                $score = $extractedData['scores'][(string)$i] ?? null;

                // Validate score constraints (2 to 8)
                if (is_numeric($score) && $score >= 2 && $score <= 8) {
                    $scannedScores[$i] = (int)$score;
                } else {
                    $scannedScores[$i] = '';
                    $missingCount++;
                }
            }

            session()->put('scanned_scores', $scannedScores);

            // --- TEACHER NAME DETECTION ---
            $matchedTeacher = null;
            $extractedName = strtolower(trim($extractedData['teacher_name'] ?? ''));

            if (!empty($extractedName)) {
                $teachers = User::whereHas('roles', function ($query) {
                    $query->where('name', 'teacher');
                })->get();

                $highestSimilarity = 0;

                foreach ($teachers as $teacher) {
                    $firstName = strtolower(trim($teacher->first_name));
                    $lastName = strtolower(trim($teacher->last_name));

                    // Compare both First Last and Last First formats
                    $format1 = $firstName . ' ' . $lastName;
                    $format2 = $lastName . ' ' . $firstName;

                    similar_text($extractedName, $format1, $percent1);
                    similar_text($extractedName, $format2, $percent2);

                    $maxPercent = max($percent1, $percent2);

                    // 75% threshold accounts for messy handwriting interpretations (e.g., "Jalani" vs "Jelani")
                    if ($maxPercent >= 75 && $maxPercent > $highestSimilarity) {
                        $highestSimilarity = $maxPercent;
                        $matchedTeacher = $teacher;
                    }
                }
            }

            $completenessMsg = $missingCount === 0
                ? "All 9 indicators were successfully extracted."
                : "Warning: {$missingCount} indicators require manual verification.";

            if ($matchedTeacher) {
                return redirect()->route('school_head.cot.create', $matchedTeacher->id)
                    ->with('status', "Form detected for {$matchedTeacher->first_name} {$matchedTeacher->last_name}. {$completenessMsg}");
            } else {
                return redirect()->route('school_head.cot.index')
                    ->with('show_teacher_modal', true)
                    ->with('error', "Scores extracted, but the AI could not definitively match the handwriting to a Teacher's name in the database. {$completenessMsg}");
            }

        } catch (\Exception $e) {
            Log::error('Gemini Scanner Error: ' . $e->getMessage());
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
            return back()->with('error', 'AI Scanner failed: ' . $e->getMessage());
        }
    }

    private function getDepEdIndicators(): array
    {
        return [
            1 => 'Apply knowledge of content within and across curriculum teaching areas.',
            2 => 'Use a range of teaching strategies that enhance learner achievement in literacy and numeracy skills.',
            3 => 'Apply a range of teaching strategies to develop critical and creative thinking.',
            4 => 'Manage classroom structure to engage learners, individually or in groups.',
            5 => 'Manage learner behavior constructively by applying positive and non-violent discipline.',
            6 => 'Use differentiated, developmentally appropriate learning experiences.',
            7 => 'Plan, manage and implement developmentally sequenced teaching and learning processes.',
            8 => 'Select, develop, organize and use appropriate teaching and learning resources.',
            9 => 'Design, select, organize and use diagnostic, formative and summative assessment strategies.',
        ];
    }
}
