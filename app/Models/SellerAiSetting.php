<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerAiSetting extends Model
{
    protected $fillable = [
        'seller_id',
        'provider_name',
        'fashn_api_key',
        'fashn_model',
        'fashn_dummy_enabled',
        'fashn_dummy_result_url',
        'fashn_api_key_last_test_ok',
        'fashn_api_key_last_test_message',
        'fashn_api_key_last_tested_at',
    ];

    protected function casts(): array
    {
        return [
            'fashn_api_key' => 'encrypted',
            'fashn_dummy_enabled' => 'boolean',
            'fashn_api_key_last_test_ok' => 'boolean',
            'fashn_api_key_last_tested_at' => 'datetime',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
