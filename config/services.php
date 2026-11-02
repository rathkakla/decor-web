<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

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

    'stability' => [
        'key'      => env('STABILITY_API_KEY'),
        'endpoint' => env('STABILITY_ENDPOINT', 'https://api.stability.ai/v2beta/stable-image/generate/sd3'),
        'model'    => env('STABILITY_MODEL', 'sd3.5-medium'),
        'mode'     => env('STABILITY_MODE', 'image-to-image'),
        'strength' => (float) env('STABILITY_STRENGTH', 0.65),
        'timeout'  => (int) env('STABILITY_TIMEOUT', 120),
        // true = error yang sudah ditangani dibalas HTTP 200 + success:false
        // (untuk client mobile yang melempar exception pada status >= 400).
        'friendly_http' => (bool) env('AI_FRIENDLY_HTTP', false),
    ],

];
