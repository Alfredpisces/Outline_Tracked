<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAssistantController extends Controller
{
    public function generateSuggestion(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'intentions' => 'required|string',
            'type' => 'required|in:learning_experience,assessment'
        ]);

        $subject = $request->subject;
        $intentions = $request->intentions;
        $type = $request->type;

        // Build the prompt specifically for DepEd context
        if ($type === 'learning_experience') {
            $prompt = "You are a master DepEd teacher in the Philippines. Based on this lesson intention/goal: '$intentions' for the subject '$subject', suggest a brief, highly engaging classroom activity (Learning Experience) that takes 15-20 minutes. Keep it practical, requiring minimal materials. Output ONLY the activity description in plain text. Do not use markdown bolding, asterisks, or pleasantries.";
        } else {
            $prompt = "You are a master DepEd teacher in the Philippines. Based on this lesson intention/goal: '$intentions' for the subject '$subject', suggest a quick, effective formative assessment (e.g., 3-question quiz, short seatwork, or exit ticket) to check if the students understood the lesson. Output ONLY the assessment in plain text. Do not use markdown bolding, asterisks, or pleasantries.";
        }

        try {
            // Google Gemini API Endpoint
            $apiKey = config('services.gemini.key');
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

            // ADDED ->withoutVerifying() HERE FOR LOCAL TESTING
            $response = Http::withoutVerifying()->timeout(15)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                ]
            ]);

            if ($response->successful()) {
                $suggestion = $response->json('candidates.0.content.parts.0.text');
                return response()->json(['suggestion' => trim($suggestion)]);
            }

            return response()->json(['error' => 'Google Gemini API Error'], 500);

        } catch (\Exception $e) {
            // It helps to log the real error behind the scenes!
            \Illuminate\Support\Facades\Log::error('AI Error: ' . $e->getMessage());
            return response()->json(['error' => 'Connection timeout or formatting error.'], 500);
        }
    }
}
