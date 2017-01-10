<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET', ''),
    ],

    'sendgrid' => [
        'secret' => env('SENDGRID_SECRET', ''),
    ],

    'ses' => [
        'key' => env('SES_KEY', ''),
        'secret' => env('SES_SECRET', ''),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'model'       => 'App\User',
        'secret'      => env('STRIPE_SECRET'),
        'publishable' => env('STRIPE_PUBLISHABLE'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from'  => env('TWILIO_FROM'),
    ],

    'google-maps' => [
        'secret' => env('GOOGLE_MAPS_API_KEY', ''),
    ],

];
