<?php

return [
    'retention_minutes' => (int) env('TRYON_RETENTION_MINUTES', 30),
    'reserved_seller_slugs' => [
        'admin',
        'dashboard',
        'api',
        'login',
        'logout',
    ],
];