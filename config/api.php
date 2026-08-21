<?php

return [
    'name' => env('API_NAME', 'EMEC API'),
    'version' => env('API_VERSION', 'v1'),

    'pagination' => [
        'default_per_page' => (int) env('API_DEFAULT_PER_PAGE', 20),
        'max_per_page' => (int) env('API_MAX_PER_PAGE', 100),
    ],

    'rate_limit' => [
        'per_minute' => (int) env('API_RATE_LIMIT_PER_MINUTE', 120),
    ],
];
