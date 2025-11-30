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

    /*
    |--------------------------------------------------------------------------
    | SMS Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configure SMS provider for sending OTP and notifications.
    | Supported providers: twilio, msg91, aws_sns, log
    |
    */

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'log'),

        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from_number' => env('TWILIO_FROM_NUMBER'),
        ],

        'msg91' => [
            'auth_key' => env('MSG91_AUTH_KEY'),
            'sender_id' => env('MSG91_SENDER_ID'),
        ],

        'aws_sns' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Face Verification Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configure face verification provider for video selfie verification.
    | Supported providers: aws_rekognition, deepface, log
    |
    */

    'face_verification' => [
        'provider' => env('FACE_VERIFICATION_PROVIDER', 'log'),

        'aws_rekognition' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('AWS_BUCKET'),
        ],

        'deepface' => [
            'api_url' => env('DEEPFACE_API_URL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Photo Verification Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configure photo verification provider for AI-based photo checks.
    | Supported providers: aws_rekognition, moderatecontent, log
    |
    */

    'photo_verification' => [
        'provider' => env('PHOTO_VERIFICATION_PROVIDER', 'log'),

        'aws_rekognition' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        ],

        'moderatecontent' => [
            'api_url' => env('MODERATECONTENT_API_URL'),
        ],
    ],

];
