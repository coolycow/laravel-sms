<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMS Sending
    |--------------------------------------------------------------------------
    |
    | When disabled, messages are persisted with the DISABLED status and are
    | not forwarded to the configured provider.
    |
    */
    'enabled' => env('SMS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Default Sender Name
    |--------------------------------------------------------------------------
    */
    'sender' => env('SMS_SENDER', 'Sender'),

    /*
    |--------------------------------------------------------------------------
    | Message Prefix
    |--------------------------------------------------------------------------
    |
    | Optional text prepended to every outgoing message body.
    |
    */
    'prefix' => env('SMS_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Balance Thresholds
    |--------------------------------------------------------------------------
    |
    | Used by consuming applications for dashboard / monitoring widgets.
    |
    */
    'normal_balance' => (float) env('SMS_NORMAL_BALANCE', 5000),

    'minimal_balance' => (float) env('SMS_MINIMAL_BALANCE', 300),

    /*
    |--------------------------------------------------------------------------
    | Default Provider
    |--------------------------------------------------------------------------
    |
    | Key from the providers map below.
    |
    */
    'default_provider' => env('SMS_PROVIDER'),

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Map of provider codes to concrete SmsProviderInterface implementations.
    | Providers themselves live in the consuming application.
    |
    | Example:
    | 'providers' => [
    |     'prosto' => App\Sms\Providers\ProstoProvider::class,
    | ],
    |
    */
    'providers' => [
        //
    ],
];
