<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SystemGuideChat extends Component
{
    public array $messages = [];
    public string $input = '';

    protected string $systemPrompt = <<<'SYSTEM'
You are "System Guide", an embedded AI assistant inside a school management web application built with Laravel and Livewire called TrackEd.

You help teachers, advisers, department heads, guidance staff, and administrators use the system correctly.

MODULES:

1. DAILY LESSON LOG (DLL)
- Create lesson plans (MATATAG / ILAW formats)
- Submit for review to the School Head
- Receive comments, Revise, and Resubmit
Navigation: Dashboard -> Lesson Logs → New DLL

2. BEHAVIOR MANAGEMENT (Log Incident)
- Create incident reports
- Minor, Major, Positive reports
- Visible to Guidance and Admin
Navigation: Dashboard -> Child Protection -> Log Incident

3. INVENTORY MANAGEMENT (My Inventory)
- Sign E-PARs (Property Acknowledgement Receipts)
- Monitor property clearance status
Navigation: Dashboard -> Accountability -> View Assets

4. E-PORTFOLIO VAULT
- Curate career achievements, seminars, and recognitions
- Upload PDF/Images as Means of Verification (MOV)
Navigation: Dashboard -> Access E-Portfolio

RULES:
- Answer in short, clear steps.
- Stay focused on the school management system.
- If unsure, advise contacting the system administrator.
- Never invent features that are not listed in the MODULES section above.
SYSTEM;

    public function mount(): void
    {
        $this->messages = [
            [
                'role' => 'assistant',
                'text' => "👋 Hello! I'm your System Guide.\nHow can I help you today?",
            ],
        ];
    }

    // This handles BOTH the quick-action buttons AND the free-text input
    public function sendPrompt(string $key = '', string $label = ''): void
    {
        // 1. Determine what the user actually said
        // If they clicked a button, $label will have text. Otherwise, use what they typed in $this->input.
        $userText = trim($label !== '' ? $label : $this->input);

        if (empty($userText)) {
            return;
        }

        // 2. Add their message to the chat UI immediately
        $this->messages[] = [
            'role' => 'user',
            'text' => $userText,
        ];

        // Clear the input box
        $this->input = '';

        // 3. Format the conversation history for the Anthropic API
        // Claude expects an array of ['role' => 'user/assistant', 'content' => '...']
        $conversation = [];
        foreach ($this->messages as $msg) {
            $conversation[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['text'],
            ];
        }

        // 4. Send the request to Anthropic
        try {
            // Note: Make sure 'services.anthropic.api_key' is set in your config/services.php
            // and ANTHROPIC_API_KEY is in your .env file
            $response = Http::withHeaders([
                'x-api-key' => config('services.anthropic.api_key'),
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                // 'claude-3-haiku-20240307' is highly recommended here for fast, cheap, snappy chat responses
                'model' => 'claude-3-haiku-20240307',
                'max_tokens' => 1000,
                'system' => $this->systemPrompt,
                'messages' => $conversation,
            ]);

            if (!$response->successful()) {
                logger()->error('Anthropic API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $reply = '⚠️ AI service temporarily unavailable. Please try again later.';
            } else {
                $reply = $response->json('content.0.text') ?? 'I received an empty response.';
            }
        } catch (\Throwable $exception) {
            logger()->error('Claude Exception', [
                'message' => $exception->getMessage(),
            ]);
            $reply = '⚠️ Error connecting to the AI service. Please check your internet connection.';
        }

        // 5. Add Claude's response to the chat
        $this->messages[] = [
            'role' => 'assistant',
            'text' => $reply,
        ];

        // 6. Tell AlpineJS (the frontend) to scroll the chat box to the bottom
        $this->dispatch('chatUpdated');
    }

    public function render()
    {
        return view('livewire.global-copilot');
    }
}
