<?php

return [
    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ai_prechecker' => [
        'url' => env('AI_PRECHECKER_URL'),
        'token' => env('AI_PRECHECKER_TOKEN'),
        'timeout' => env('AI_PRECHECKER_TIMEOUT', 10),
    ],

    // NEW: Google Gemini API Configuration
    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],

    'anthropic' => [
    'api_key' => env('ANTHROPIC_API_KEY'),
],
];
