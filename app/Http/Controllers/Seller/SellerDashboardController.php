<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\TryOnSession;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $seller = Seller::query()->where('owner_user_id', auth()->id())->firstOrFail();
        $usage = $seller->usageBalance;

        $tokenUsed = (int) ($usage->token_used ?? 0);
        $tokenAvailable = (int) ($usage->token_available ?? 0);
        $tokenBalance = (int) ($usage->token_balance ?? 0);

        $stats = [
            'total_products' => $seller->products()->count(),
            'token_available' => $tokenAvailable,
            'token_used' => $tokenUsed,
            'token_balance' => $tokenBalance,
            'success_count' => (int) ($usage->success_count ?? 0),
            'failed_count' => (int) ($usage->failed_count ?? 0),
            'recent_tryon' => TryOnSession::query()->where('seller_id', $seller->id)->latest()->limit(10)->get(),
        ];

        return view('seller.dashboard', compact('seller', 'stats'));
    }
}
