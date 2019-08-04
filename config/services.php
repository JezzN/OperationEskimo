<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'battle-net' => [
        'api-key' => env('BATTLE_NET_API_KEY'),
        'guild-name' => 'Method Plus',
        'realm' => 'Ragnaros',
        'region' => 'eu',
    ],

    'warcraft-logs' => [
        'api-key' => env('WARCRAFT_LOGS_API_KEY')
    ],

    'mailgun' => [
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
