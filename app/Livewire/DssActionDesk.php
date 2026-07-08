<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DssActionDesk extends Component
{
    public $facultyRankings = [];
    public $behaviorSummary;
    public $assetSummary;

    public $activeContext = 'faculty';
    public $input = '';
    public $messages = [];

    // NEW: Interactive State Variables
    public $documentDrafted = false;
    public $draftedContent = '';
    public $isReportMode = false; // Controls the UI flip

    public function mount($facultyRankings, $behaviorSummary, $assetSummary)
    {
        $this->facultyRankings = json_decode(json_encode($facultyRankings), true) ?? [];
        $this->behaviorSummary = $behaviorSummary;
        $this->assetSummary = $assetSummary;

        $this->messages[] = [
            'role' => 'ai',
            'text' => "SYSTEM READY // INITIALIZING SYSTEM CORE DIAGNOSTICS...\n\nHello Principal. I am your automated Data Diagnostics Mainframe. I have compiled the database logs into structured analytical vectors. Select a module above or request an intelligence briefing."
        ];
    }

    #[On('set-context')]
    public function setContext($context)
    {
        $this->activeContext = $context;
        $this->documentDrafted = false;
        $this->draftedContent = '';
        $this->isReportMode = false; // Reset view

        $this->messages = [[
            'role' => 'ai',
            'text' => "FOCUS SHIFT // MODULE CHANNEL MOUNTED: [" . strtoupper($context) . "]\n\nMainframe is locked onto " . ucfirst($context) . " metrics. Ready to perform systemic evaluation or isolate diagnostic anomalies."
        ]];
    }

    public function generateDocument()
    {
        if ($this->activeContext === 'behavior') {
            $this->input = "COMMAND: Generate full Predictive Behavioral Risk & Security Diagnostics Report.";
        } elseif ($this->activeContext === 'asset') {
            $this->input = "COMMAND: Generate full Lifecycle Depreciation & Procurement Analytics Report.";
        } else {
            $this->input = "COMMAND: Generate full Instructional Compliance & Technical Assistance Data Matrix.";
        }

        $this->sendMessage(true);
    }

    public function toggleReportMode()
    {
        // Flips the UI from the Chat to the Full-Screen Report
        $this->isReportMode = !$this->isReportMode;
    }

    public function sendMessage($isDocumentDraft = false)
    {
        if (trim($this->input) === '') return;

        $userMessage = $this->input;

        $this->messages[] = [
            'role' => 'user',
            'text' => $isDocumentDraft ? "EXECUTE: Compile Analytical Diagnostic Report." : $userMessage
        ];

        $this->input = '';

        if (!$isDocumentDraft) {
            $this->documentDrafted = false;
            $this->isReportMode = false;
        }

        // Gather structural data blocks
        $contextData = "";
        if ($this->activeContext === 'behavior') {
            $contextData = "Behavioral Raw Metrics:\n" . $this->behaviorSummary;
        } elseif ($this->activeContext === 'asset') {
            $contextData = "Inventory Raw Metrics:\n" . $this->assetSummary;
        } else {
            $contextData = "Granular Faculty Log Metrics:\n";
            foreach ($this->facultyRankings as $index => $teacher) {
                $rank = $index + 1;
                $contextData .= "[RANK #{$rank}] Personnel: {$teacher['name']} // Stats: {$teacher['compliance_rate']}% Compliance Index ({$teacher['checked']} Approved Vectors, {$teacher['needs_revision']} Flagged Revisions).\n";
            }
        }

        $systemPrompt = "You are the TrackEd Decision Support System (DSS) Core Mainframe. Your function is to process raw administrative metrics into highly advanced, clinical, intelligent diagnostic readouts for the School Head.

        CURRENT OPERATIONAL LAYER: [" . strtoupper($this->activeContext) . "]
        LIVE DATABASE VECTOR DATA:
        \n{$contextData}

        STRICT PROTOCOLS FOR REPORT GENERATION:
        1. TERMINOLOGY: Use authoritative, systems-engineering, and advanced data science vocabulary (e.g., 'compliance index', 'depreciation threshold', 'anomaly detection', 'risk vectors').
        2. DO NOT write an essay, a memo, or a standard letter. No salutations, no signature greetings.
        3. MANDATORY STRUCTURAL ARCHITECTURE (Use exact titles in ALL CAPS):
           I. CORE OVERVIEW & HEALTH INDEX
           II. GRANULAR DIAGNOSTIC DATA MATRIX
           III. PREDICTIVE VARIANCE MODELLING
           IV. STRATEGIC DECISION DIRECTIVES
        4. ABSOLUTE LIMITATION: Never track or mention student absenteeism metrics under any circumstances. Do not use markdown bolding (**) or code blocks (```). Format using clean spacing.";

        try {
            $apiKey = env('GEMINI_API_KEY');
            if (empty($apiKey)) throw new \Exception("Core Configuration Error: Access Key Absent.");

            $baseUrl = 'https://' . 'generativelanguage.googleapis.com';
            $endpoint = '/v1beta/models/gemini-2.5-flash:generateContent?key=';

            $response = Http::withOptions(['verify' => false])
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60)->post($baseUrl . $endpoint . trim($apiKey), [
                'contents' => [
                    ['parts' => [['text' => $systemPrompt . "\n\nUser Request: " . $userMessage]]]
                ],
                'generationConfig' => ['temperature' => 0.1]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'System processing failure.';
                $cleanText = trim($aiText);

                $this->messages[] = ['role' => 'ai', 'text' => $cleanText];

                if ($isDocumentDraft) {
                    $this->documentDrafted = true;
                    $this->draftedContent = $cleanText;
                }

            } else {
                $this->messages[] = ['role' => 'ai', 'text' => '⚠️ CRITICAL MAINFRAME EXCEPTION: ' . $response->body()];
            }

        } catch (\Exception $e) {
            Log::error('DSS Action Desk Error: ' . $e->getMessage());
            $this->messages[] = ['role' => 'ai', 'text' => '⚠️ CONNECTION DISRUPTED: ' . $e->getMessage()];
        }

        $this->dispatch('chat-updated');
    }

    public function render()
    {
        return view('livewire.dss-action-desk');
    }
}
