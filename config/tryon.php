<?php

return [
    'retention_minutes' => (int) env('TRYON_RETENTION_MINUTES', 30),
    'dummy_model_image_url' => env('TRYON_DUMMY_MODEL_IMAGE_URL'),
    'polling' => [
        'max_attempts' => (int) env('TRYON_PROVIDER_MAX_ATTEMPTS', 90),
        'release_seconds' => (int) env('TRYON_PROVIDER_RELEASE_SECONDS', 2),
    ],
    'token_policy' => [
        // If true, token is still charged when provider has accepted/executed the job but final status failed.
        'charge_on_provider_failure' => (bool) env('TRYON_CHARGE_ON_PROVIDER_FAILURE', false),
    ],
    'reserved_seller_slugs' => [
        'admin',
        'dashboard',
        'api',
        'login',
        'logout',
    ],
];
