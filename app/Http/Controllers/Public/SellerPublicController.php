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

        return view('public.seller', compact('seller', 'products', 'selectedProduct'));
    }
}
