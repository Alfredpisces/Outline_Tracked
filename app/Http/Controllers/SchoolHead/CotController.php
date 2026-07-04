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
use thiagoalessio\TesseractOCR\TesseractOCR;

class CotController extends Controller
{
    public function index(): View
    {
        $teachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->orderBy('last_name')->get();

        $pendingRequests = CotObservation::with('teacher')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $masterTeachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->where('deped_rank', 'LIKE', '%Master%')->get();

        return view('school_head.cot.index', compact('teachers', 'pendingRequests', 'masterTeachers'));
    }

    public function assignObserver(Request $request, $id): RedirectResponse
    {
        $request->validate(['observer_id' => 'required|exists:users,id']);

        $observation = CotObservation::findOrFail($id);
        $observation->update([
            'observer_id' => $request->observer_id,
            'status' => 'scheduled'
        ]);

        return back()->with('status', 'Observer successfully assigned. Schedule approved.');
    }

    public function blankForm(Request $request): View
    {
        $tier = $request->query('tier', 'proficient');
        $indicators = $this->getDepEdIndicators();
        return view('school_head.cot.blank', compact('tier', 'indicators'));
    }

    public function create(User $teacher): View
    {
        $indicators = $this->getDepEdIndicators();
        return view('school_head.cot.create', compact('teacher', 'indicators'));
    }

    public function store(Request $request, User $teacher): RedirectResponse
    {
        $indicators = $this->getDepEdIndicators();
        $submittedScores = $request->input('scores', []);

        $processedScores = [];
        $totalScore = 0;
        $count = 0;

        foreach ($submittedScores as $num => $score) {
            $processedScores[$num] = [
                'title' => $indicators[$num] ?? 'Indicator ' . $num,
                'score' => $score
            ];

            if (is_numeric($score)) {
                $totalScore += $score;
                $count++;
            }
        }

        $averageRating = $count > 0 ? round($totalScore / $count, 2) : 0;

        $observation = CotObservation::create([
            'teacher_id' => $teacher->id,
            'observer_id' => Auth::id(),
            'status' => 'completed',
            'observation_date' => $request->input('observation_date', now()->format('Y-m-d')),
            'time_started' => $request->input('time_started', '00:00:00'),
            'time_ended' => $request->input('time_ended', '00:00:00'),
            'section' => $request->input('section', 'N/A'),
            'subject_taught' => $request->input('subject_taught'),
            'grade_level' => $request->input('grade_level'),
            'quarter' => $request->input('quarter'),
            'rating' => $averageRating,
            'comments' => $request->input('comments', 'No comments provided.'),
            'scores' => $processedScores
        ]);

        // Clear session scores after successful save
        session()->forget('scanned_scores');

        return redirect()->route('school_head.cot.show', $observation->id)
                         ->with('status', 'Classroom Observation Rating saved successfully.');
    }

    public function show($id): View
    {
        $observation = CotObservation::with(['teacher', 'observer'])->findOrFail($id);
        return view('school_head.cot.show', compact('observation'));
    }

    // 7. AI Tesseract Scanner & Smart Parser
    // 7. AI Tesseract Scanner & Smart Parser
    public function scan(Request $request)
    {
        $request->validate([
            'cot_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath = $request->file('cot_image')->store('temp_scans');
        $absolutePath = \Illuminate\Support\Facades\Storage::path($imagePath);

        if (!file_exists($absolutePath)) {
            return back()->with('error', 'Upload failed: Laravel could not read the stored file.');
        }

        try {
            $ocr = new TesseractOCR($absolutePath);
            $ocr->executable('C:\Program Files\Tesseract-OCR\tesseract.exe');
            $ocr->psm(6); // PSM 6 forces Tesseract to assume a uniform block, better for grids
            $extractedText = strtolower($ocr->run());

            if (file_exists($absolutePath)) unlink($absolutePath);

            $scannedScores = [];
            $missingCount = 0;

            // ADVANCED INDICATOR MATCHING: Multiple backup keywords per row
            $indicatorsMap = [
                1 => ['content within', 'teaching areas', 'apply knowledge'],
                2 => ['literacy', 'numeracy', 'enhance learner'],
                3 => ['critical', 'creative', 'thinking'],
                4 => ['structure', 'engage learners', 'manage classroom'],
                5 => ['behavior', 'discipline', 'constructively'],
                6 => ['differentiated', 'developmentally', 'experiences'],
                7 => ['sequenced', 'learning processes', 'plan, manage'],
                8 => ['learning resources', 'select, develop'],
                9 => ['diagnostic', 'summative', 'assessment']
            ];

            // EXTENDED CORRECTION MAP: Includes table noise that looks like numbers
            $correctionMap = [
                's' => 5, 'S' => 5, '/' => 7, '?' => 7, 't' => 7, 'l' => 7, ']' => 7, '[' => 7, 'i' => 7, '\\' => 7, '|' => 7,
                'b' => 6, 'g' => 6, 'o' => 6,
                'h' => 4, 'y' => 4,
                'e' => 3, 'z' => 2
            ];

            foreach ($indicatorsMap as $num => $aliases) {
                $scannedScores[$num] = '';
                $pos = false;

                // Find the first matching alias to locate the row
                foreach ($aliases as $alias) {
                    $pos = strpos($extractedText, strtolower($alias));
                    if ($pos !== false) break;
                }

                if ($pos !== false) {
                    // Grab up to 200 chars AFTER the keyword to capture the right-hand column
                    $window = substr($extractedText, $pos, 200);

                    // Scrub table borders/noise to isolate the handwritten score
                    $window = str_replace(['_', '[', ']', '{', '}', '(', ')', '.', ',', ':'], ' ', $window);

                    $tokens = preg_split('/\s+/', trim($window));
                    $tokens = array_reverse($tokens); // Read right-to-left

                    foreach ($tokens as $token) {
                        $cleanToken = strtolower(trim($token));

                        // If it's exactly 1 char and matches our flexible regex
                        if (strlen($cleanToken) === 1 && preg_match('/^[2-8s\/\?tlbi\|\\hyezo]$/i', $cleanToken)) {
                            $rawScore = $cleanToken;
                            $scannedScores[$num] = $correctionMap[$rawScore] ?? $rawScore;
                            break;
                        }
                    }
                }

                if ($scannedScores[$num] === '') $missingCount++;
            }

            session()->put('scanned_scores', $scannedScores);

            // --- ADVANCED FUZZY NAME DETECTION ---
            $teachers = User::whereHas('roles', function ($query) {
                $query->where('name', 'teacher');
            })->get();

            $matchedTeacher = null;
            $highestSimilarity = 0;

            // Strip out all punctuation for pure word comparison
            $cleanExtractedText = preg_replace('/[^a-z\s]/i', ' ', $extractedText);
            $ocrWords = array_filter(explode(' ', $cleanExtractedText));

            foreach ($teachers as $teacher) {
                $firstName = strtolower(trim($teacher->first_name));
                $lastName = strtolower(trim($teacher->last_name));

                // 1. Check for Exact match without spaces (e.g. "alfredmoncano")
                $textNoSpaces = str_replace(' ', '', $cleanExtractedText);
                if (str_contains($textNoSpaces, str_replace(' ', '', $firstName)) ||
                    str_contains($textNoSpaces, str_replace(' ', '', $lastName))) {
                    $matchedTeacher = $teacher;
                    break;
                }

                // 2. Fuzzy Match (Levenshtein Distance) for messy handwriting
                foreach ($ocrWords as $word) {
                    if (strlen($word) > 3) { // Only compare actual names, not small words
                        similar_text($word, $firstName, $percentFirst);
                        similar_text($word, $lastName, $percentLast);

                        $maxPercent = max($percentFirst, $percentLast);

                        // If the AI read a word that is at least 80% similar to the teacher's name
                        if ($maxPercent >= 80 && $maxPercent > $highestSimilarity) {
                            $highestSimilarity = $maxPercent;
                            $matchedTeacher = $teacher;
                        }
                    }
                }

                if ($matchedTeacher && $highestSimilarity == 100) break; // Break early on a perfect match
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
                    ->with('error', "Scores extracted, but the AI could not decipher the handwriting for the Teacher's name. {$completenessMsg}");
            }

        } catch (\Exception $e) {
            if (file_exists($absolutePath)) unlink($absolutePath);
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
