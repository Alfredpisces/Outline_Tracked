<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GlobalCopilot extends Component
{
    public $input = '';
    public $messages = [];
    public $quickTopics = []; // NEW: Array to hold dynamic topics

    public function mount()
    {
        $this->messages[] = [
            'role' => 'ai',
            'text' => "👋 Hello! I'm your TrackEd System Guide. How can I help you navigate the platform today?"
        ];

        // NEW: Check the user's role and assign the correct quick topics
        $user = Auth::user();
        $role = strtolower($user->role ?? 'guest');

        if ($role === 'teacher') {
            $this->quickTopics = [
                ['icon' => '📚', 'label' => 'How do I create or submit a DLL?'],
                ['icon' => '🛡️', 'label' => 'How do I file a behavior report?'],
                ['icon' => '📊', 'label' => 'How does the Principal review my DLL?']
            ];
        } elseif ($role === 'school_head') {
            $this->quickTopics = [
                ['icon' => '✅', 'label' => 'How do I review a teacher\'s DLL?'],
                ['icon' => '📈', 'label' => 'How do I check faculty compliance?'],
                ['icon' => '✍️', 'label' => 'How do I send technical assistance?']
            ];
        } elseif ($role === 'admin') {
            $this->quickTopics = [
                ['icon' => '👥', 'label' => 'How do I manage user accounts?'],
                ['icon' => '📦', 'label' => 'How do I manage classroom inventory?'],
                ['icon' => '⚙️', 'label' => 'How do I configure system settings?']
            ];
        } else {
            // Fallback for counselors, staff, or unassigned users
            $this->quickTopics = [
                ['icon' => '❓', 'label' => 'I need help navigating the system.'],
                ['icon' => '🛡️', 'label' => 'How do I view behavior reports?']
            ];
        }
    }

    // UPDATED: Now receives the exact string directly from the button
    public function sendTopic($question)
    {
        $this->input = $question;
        $this->sendMessage();
    }

    public function sendMessage()
    {
        if (trim($this->input) === '') return;

        // 1. Save user message to chat
        $userMessage = $this->input;
        $this->messages[] = ['role' => 'user', 'text' => $userMessage];
        $this->input = ''; // Clear input field

        // 2. Identify the User and their Role
        $user = Auth::user();
        $role = $user->role ?? 'guest';
        $name = $user->name ?? 'User';

        // 3. THE "BOUNCER" SYSTEM PROMPT
        $systemPrompt = "You are the 'TrackEd System Guide', an official IT support and customer service assistant for the TrackEd School Management System.
        You are currently assisting: {$name}. Their official system role is: [" . strtoupper($role) . "].

        STRICT BOUNDARIES & RULES:
        1. SYSTEM SCOPE ONLY: You exist ONLY to help with TrackEd. If the user asks about general knowledge, history, science, coding, or anything outside of navigating the school system, you MUST politely refuse and state your purpose.
        2. STRICT DATA CONFIDENTIALITY: You must respect role clearances.
           - If a TEACHER asks about Admin data, School Head DSS Rankings, or Counselor behavioral records, refuse them by saying: 'Due to data privacy protocols, you do not have clearance to view or inquire about this module.'
           - If anyone asks for specific student passwords or sensitive records, refuse immediately.
        3. EXPLAINING WORKFLOWS: You CAN explain how system processes work. For example, if a teacher asks how the DLL module works, explain the step-by-step workflow (Teacher drafts -> Submits -> School Head reviews -> Approves/Returns).

        Keep your answers concise, professional, friendly, and formatted nicely.";

        // 4. Send to Gemini 2.5 Flash API
        try {
            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                throw new \Exception("System configuration error. API Key missing.");
            }

            $baseUrl = "https://" . "generativelanguage.googleapis.com";
            $endpoint = "/v1beta/models/gemini-2.5-flash:generateContent?key=";
            $apiUrl = $baseUrl . $endpoint . trim($apiKey);

            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($apiUrl, [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt . "\n\nUser Question: " . $userMessage]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'I could not process that.';
                $this->messages[] = ['role' => 'ai', 'text' => $aiText];
            } else {
                $this->messages[] = ['role' => 'ai', 'text' => '⚠️ Google API Error: ' . $response->body()];
            }

        } catch (\Exception $e) {
            Log::error('Global Copilot Error: ' . $e->getMessage());
            $this->messages[] = ['role' => 'ai', 'text' => '⚠️ System Offline: ' . $e->getMessage()];
        }

        // Tell the frontend to scroll to the bottom of the chat
        $this->dispatch('chat-updated');
    }

    public function render()
    {
        return view('livewire.global-copilot');
    }
}
