<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerUsageBalance extends Model
{
    protected $fillable = [
        'seller_id',
        'token_balance',
        'token_used',
        'token_available',
        'success_count',
        'failed_count',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}