<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Seller;

class SellerPublicController extends Controller
{
    public function index(string $seller_slug, ?string $product_slug = null)
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

        if ($product_slug !== null) {
            $selectedProduct = $products->firstWhere('slug', $product_slug);

            if (! $selectedProduct) {
                abort(404);
            }
        }

        return view('public.seller', compact('seller', 'products', 'selectedProduct'));
    }
}
