<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Seller;
use App\Models\SellerUsageBalance;
use App\Models\TryOnSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sellers' => Seller::query()->count(),
            'active_sellers' => Seller::query()->where('status', 'active')->count(),
            'total_tokens_available' => (int) SellerUsageBalance::query()->sum('token_available'),
            'total_generate' => TryOnSession::query()->count(),
            'failed_generate' => TryOnSession::query()->where('status', 'failed')->count(),
        ];

        $topSellers = Seller::query()
            ->leftJoin('seller_usage_balances', 'seller_usage_balances.seller_id', '=', 'sellers.id')
            ->select([
                'sellers.id',
                'sellers.store_name',
                'sellers.status',
                'sellers.created_at',
                DB::raw('COALESCE(seller_usage_balances.token_used, 0) as token_used'),
                DB::raw('COALESCE(seller_usage_balances.success_count, 0) as success_count'),
            ])
            ->orderByDesc('token_used')
            ->limit(6)
            ->get();

        $trendLabels = [];
        $trendValues = [];

        for ($week = 3; $week >= 0; $week--) {
            $start = Carbon::now()->startOfWeek()->subWeeks($week);
            $end = (clone $start)->endOfWeek();

            $trendLabels[] = 'Week '.(4 - $week);
            $trendValues[] = TryOnSession::query()
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        $providerLogs = AuditLog::query()
            ->whereIn('action', [
                'tryon_provider_create_job',
                'tryon_provider_create_job_failed',
                'tryon_provider_get_status',
                'tryon_provider_get_status_failed',
            ])
            ->latest()
            ->limit(40)
            ->get();

        $sessionMap = TryOnSession::query()
            ->whereIn('id', $providerLogs->pluck('entity_id')->filter()->unique()->values())
            ->with(['seller:id,store_name', 'product:id,name'])
            ->get()
            ->keyBy('id');

        return view('admin.dashboard', compact('stats', 'topSellers', 'trendLabels', 'trendValues', 'providerLogs', 'sessionMap'));
    }
}
