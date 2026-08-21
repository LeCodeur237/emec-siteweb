<?php

$allowedOrigins = env('CORS_ALLOWED_ORIGINS');

if ($allowedOrigins === null || trim($allowedOrigins) === '') {
    $allowedOrigins = implode(',', array_filter([
        env('FRONTEND_URL', 'https://egliseemec.org'),
        env('FRONTEND_WWW_URL', 'https://www.egliseemec.org'),
        env('MESSAGES_FRONTEND_URL', 'https://messages.egliseemec.org'),
        env('DOSC_FRONTEND_URL', 'https://dosc.egliseemec.org'),
        env('ADMIN_FRONTEND_URL'),
        env('LOCAL_FRONTEND_URL', 'http://localhost:5173'),
        env('LOCAL_REACT_FRONTEND_URL', 'http://localhost:3000'),
    ]));
}

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

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_values(array_filter(array_map(
        'trim',
        explode(',', $allowedOrigins)
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
