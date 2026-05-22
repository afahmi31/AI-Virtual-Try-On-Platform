<?php

return [
    'default_provider' => env('TRYON_PROVIDER', 'fashn'),
    'providers' => [
        'fashn' => [
            'base_url' => env('FASHN_BASE_URL'),
            'run_url' => env('FASHN_RUN_URL'),
            'status_url_template' => env('FASHN_STATUS_URL_TEMPLATE'),
            'model' => env('FASHN_MODEL', 'tryon-max'),
            'dummy_enabled' => (bool) env('FASHN_DUMMY_ENABLED', false),
            'dummy_result_url' => env('FASHN_DUMMY_RESULT_URL'),
            'timeout_seconds' => (int) env('FASHN_TIMEOUT_SECONDS', env('FASHN_TIMEOUT', 60)),
            'webhook_secret' => env('FASHN_WEBHOOK_SECRET'),
            'retry_times' => (int) env('FASHN_RETRY_TIMES', 2),
            'retry_sleep_ms' => (int) env('FASHN_RETRY_SLEEP_MS', 300),
        ],
    ],
];
