<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Seller;

class SellerPublicController extends Controller
{
    public function index(string $seller_slug, ?string $product_ref = null)
    {
        $seller = Seller::query()
            ->where('slug', $seller_slug)
            ->where('status', 'active')
            ->with('aiSetting')
            ->firstOrFail();

        $products = $seller->products()
            ->with('images')
            ->where('status', 'active')
            ->latest()
            ->get();
        $selectedProduct = null;

        if ($product_ref !== null) {
            $selectedProduct = $products->first(function ($product) use ($product_ref): bool {
                $sku = (string) ($product->sku ?? '');

                return $product->slug === $product_ref
                    || ($sku !== '' && strcasecmp($sku, $product_ref) === 0);
            });

            if (! $selectedProduct) {
                abort(404);
            }
        }

        $dummyEnabled = (bool) ($seller->aiSetting?->fashn_dummy_enabled ?? false);
        $dummyModelImageUrl = is_string($seller->aiSetting?->fashn_dummy_model_image_url)
            ? trim($seller->aiSetting->fashn_dummy_model_image_url)
            : '';
        $dummyResultUrl = is_string($seller->aiSetting?->fashn_dummy_result_url)
            ? trim($seller->aiSetting->fashn_dummy_result_url)
            : '';

        $tryOnDummy = [
            'enabled' => $dummyEnabled,
            'model_image_url' => $dummyModelImageUrl,
            'result_url' => $dummyResultUrl,
        ];

        return view('public.seller', compact('seller', 'products', 'selectedProduct', 'tryOnDummy'));
    }
}
