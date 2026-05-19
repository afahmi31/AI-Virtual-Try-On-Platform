<?php

return [
    'default_provider' => env('TRYON_PROVIDER', 'fashn'),
    'providers' => [
        'fashn' => [
            'base_url' => env('FASHN_BASE_URL'),
            'api_key' => env('FASHN_API_KEY'),
            'timeout' => (int) env('FASHN_TIMEOUT', 60),
        ],
    ],
];