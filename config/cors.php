<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
        'http://localhost',
        'capacitor://localhost',
        'http://localhost:3000',
        'https://localhost:3000',
        'http://192.168.0.25:3000',
        '76.76.21.21',
        'http://76.76.21.21',
        'https://76.76.21.21',
        'https://clinika-demo-cr.netlify.app',
        'https://clinika:8890',
        'http://clinika:8890',
        'https://adharaexpress.com.mx',
        'https://app-clinika-mobile.netlify.app',
        'https://clinika.ai',
        'https://www.clinika.ai'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Access-Control-Allow-Origin'],

    'max_age' => 0,

    'supports_credentials' => true,

];
