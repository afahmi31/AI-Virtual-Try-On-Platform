<?php

namespace App\Providers;

use App\Models\ProductRequest;
use App\Models\User;
use App\Support\CurrentSellerResolver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('tryon-public-create', function (Request $request): array {
            $sellerSlug = (string) $request->route('seller_slug');
            $ip = (string) $request->ip();
            $deviceId = $this->resolveTryOnDeviceId($request);
            $dailyLimit = max((int) config('tryon.public_limits.generate_per_day', 3), 1);
            $minuteLimit = max((int) config('tryon.public_limits.generate_per_minute_per_ip', 3), 1);

            return [
                Limit::perMinute($minuteLimit)->by('create-minute-ip|'.$sellerSlug.'|'.$ip),
                Limit::perDay($dailyLimit)->by('create-day-ip|'.$sellerSlug.'|'.$ip),
                Limit::perDay($dailyLimit)->by('create-day-device|'.$sellerSlug.'|'.$deviceId),
            ];
        });

        RateLimiter::for('tryon-public-polling', function (Request $request): Limit {
            $sellerSlug = (string) $request->route('seller_slug');
            $ip = (string) $request->ip();
            $pollingPerMinute = max((int) config('tryon.public_limits.polling_per_minute', 120), 1);

            return Limit::perMinute($pollingPerMinute)->by('poll|'.$sellerSlug.'|'.$ip);
        });

        View::composer('seller.*', function ($view): void {
            $newProductRequestCount = 0;
            $user = Auth::user();

            if ($user instanceof User) {
                try {
                    $seller = app(CurrentSellerResolver::class)->resolveForUser($user);

                    $newProductRequestCount = ProductRequest::query()
                        ->where('seller_id', $seller->id)
                        ->where(function ($query): void {
                            $query->whereIn('status', ['new', 'pending'])
                                ->orWhereNull('status')
                                ->orWhere('status', '');
                        })
                        ->count();
                } catch (\Throwable) {
                    $newProductRequestCount = 0;
                }
            }

            $view->with('newProductRequestCount', $newProductRequestCount);
        });
    }

    private function resolveTryOnDeviceId(Request $request): string
    {
        $rawDeviceId = strtolower(trim((string) $request->header('X-Tryon-Device-Id', '')));
        if ($rawDeviceId === '') {
            return 'missing-device-id';
        }

        $normalized = preg_replace('/[^a-z0-9\-_]/', '', $rawDeviceId);
        if (! is_string($normalized) || $normalized === '') {
            return 'invalid-device-id';
        }

        return substr($normalized, 0, 64);
    }
}
