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
    public $quickTopics = [];

    public function mount()
    {
        $this->messages[] = [
            'role' => 'ai',
            'text' => "👋 Hello! I'm your TrackEd System Guide. How can I help you navigate the platform today?"
        ];

        // DYNAMIC ROLE-BASED QUICK TOPICS MAPPED TO ACTUAL CAPABILITIES
        $user = Auth::user();
        $role = strtolower($user->role ?? 'guest');

        if ($role === 'teacher') {
            $this->quickTopics = [
                ['icon' => '📚', 'label' => 'How do I create and submit my Daily Lesson Log?'],
                ['icon' => '🛡️', 'label' => 'How do I log a student behavioral incident?'],
                ['icon' => '🎓', 'label' => 'How do I upload a Professional Growth certificate?']
            ];
        } elseif ($role === 'school_head') {
            $this->quickTopics = [
                ['icon' => '✅', 'label' => 'How do I review and return a teacher\'s DLL?'],
                ['icon' => '📋', 'label' => 'How do I input Classroom Observation (COT) ratings?'],
                ['icon' => '🧠', 'label' => 'How do I use the DSS Command Center?']
            ];
        } elseif ($role === 'counselor') {
            $this->quickTopics = [
                ['icon' => '🤝', 'label' => 'How do I log a Counseling Intervention?'],
                ['icon' => '⚠️', 'label' => 'How do I view unresolved major incidents?']
            ];
        } elseif ($role === 'admin') {
            $this->quickTopics = [
                ['icon' => '👥', 'label' => 'How do I add a new user or change roles?'],
                ['icon' => '📦', 'label' => 'How do I register a new Asset/Inventory item?'],
                ['icon' => '⚙️', 'label' => 'How do I manage system settings?']
            ];
        } else {
            $this->quickTopics = [
                ['icon' => '❓', 'label' => 'I need help navigating the system.']
            ];
        }
    }

    public function sendTopic($question)
    {
        $this->input = $question;
        $this->sendMessage();
    }

    public function sendMessage()
    {
        if (trim($this->input) === '') return;

        $userMessage = $this->input;
        $this->messages[] = ['role' => 'user', 'text' => $userMessage];
        $this->input = '';

        $user = Auth::user();
        $role = $user->role ?? 'guest';
        $name = $user->name ?? 'User';

        // THE "EXPERT BRAIN" SYSTEM PROMPT
        $systemPrompt = "You are the 'TrackEd System Guide', an official IT support and customer service assistant for the TrackEd School Management System.
        You are currently assisting: {$name}. Their official system role is: [" . strtoupper($role) . "].

        SYSTEM KNOWLEDGE BASE (HOW TRACKED WORKS):
        - INSTRUCTIONAL (DLL & COT): Teachers draft daily log entries. They submit the Weekly Log to the School Head (Reviewer). The School Head checks it or returns it with 'Needs Revision'. School Heads also conduct Classroom Observations (COT) and input ratings.
        - BEHAVIORAL (Discipline): Teachers log 'Incident Reports'. If it's a major offense, the student is ineligible for Good Moral certificates until resolved. Counselors review Incident Reports and log 'Counseling Interventions'. The Principal takes final action.
        - INVENTORY (Assets): Admins register Assets (Projectors, PCs) and manage their life cycle based on condition and expiry dates. Teachers can borrow items, which creates an 'Asset Transaction'.
        - DSS (Command Center): An AI-powered dashboard exclusively for the School Head. It ranks teachers needing technical assistance, maps behavioral incident hotspots, and recommends asset procurement.

        STRICT BOUNDARIES & RULES:
        1. SCOPE ONLY: If the user asks about general knowledge, history, science, coding, or anything outside of navigating the school system, politely refuse.
        2. DATA CONFIDENTIALITY: Respect role clearances.
           - If a TEACHER asks about Admin settings, DSS Rankings, or another teacher's COT score, refuse: 'Due to data privacy protocols, you do not have clearance for this module.'
           - Refuse any requests for passwords or specific student records.
        3. BE HELPFUL & CONCISE: Answer questions based ONLY on the System Knowledge Base provided above. Give step-by-step guidance.

        Keep your answers professional, friendly, and formatted nicely.";

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
                    'temperature' => 0.1, // Strict adherence to our knowledge base
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

        $this->dispatch('chat-updated');
    }

    public function render()
    {
        return view('livewire.global-copilot');
    }
}
