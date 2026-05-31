<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRequest extends Model
{
    protected $fillable = [
        'seller_id',
        'shopee_product_url',
        'status',
        'source_channel',
        'ip_address',
        'user_agent',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
