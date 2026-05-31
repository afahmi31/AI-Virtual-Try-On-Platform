<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ProductRequest;
use App\Models\Seller;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductRequestPublicController extends Controller
{
    public function store(Request $request, string $seller_slug): RedirectResponse
    {
        $seller = Seller::query()
            ->where('slug', $seller_slug)
            ->where('status', 'active')
            ->firstOrFail();

        $payload = $request->validate([
            'shopee_product_url' => [
                'bail',
                'required',
                'string',
                'max:2048',
                'url',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $url = trim((string) $value);
                    if (! $this->isShopeeUrl($url)) {
                        $fail((string) __('ui.store.product_request_invalid_shopee'));
                    }
                },
            ],
        ]);

        $requestRecord = ProductRequest::query()->create([
            'seller_id' => $seller->id,
            'shopee_product_url' => trim((string) $payload['shopee_product_url']),
            'status' => 'pending',
            'source_channel' => 'store_page',
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        AuditLog::query()->create([
            'actor_user_id' => null,
            'action' => 'product_request_submitted_public',
            'entity_type' => ProductRequest::class,
            'entity_id' => $requestRecord->id,
            'payload_json' => [
                'seller_id' => $seller->id,
                'source_channel' => 'store_page',
                'ip_address' => $request->ip(),
            ],
        ]);

        return redirect()
            ->route('public.seller.page', ['seller_slug' => $seller->slug])
            ->withFragment('product-request-form')
            ->with('store_product_request_success', (string) __('ui.store.product_request_success'));
    }

    private function isShopeeUrl(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        if ($host === '') {
            return false;
        }

        $normalizedHost = preg_replace('/^www\./', '', $host);
        if (! is_string($normalizedHost) || $normalizedHost === '') {
            return false;
        }

        return (bool) preg_match('/(^|\.)shopee\.[a-z]{2,}(\.[a-z]{2,})?$/', $normalizedHost);
    }
}
