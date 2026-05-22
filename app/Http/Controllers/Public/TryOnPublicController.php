<?php

namespace App\Http\Controllers\Public;

use App\Domain\AI\ProviderRouter;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessTryOnSessionJob;
use App\Models\AuditLog;
use App\Models\Seller;
use App\Models\SellerUsageBalance;
use App\Models\TryOnSession;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;

class TryOnPublicController extends Controller
{
    public function quota(Request $request, string $seller_slug): JsonResponse
    {
        $seller = Seller::query()
            ->where('slug', $seller_slug)
            ->where('status', 'active')
            ->with('aiSetting')
            ->firstOrFail();

        $providerConfig = $this->resolveProviderConfig($seller);
        if (! $this->canUseProvider($providerConfig)) {
            return response()->json([
                'message' => 'FASHN API key belum dikonfigurasi oleh store owner.',
            ], 422);
        }

        return response()->json($this->buildQuotaPayload($request, $seller->slug));
    }

    public function store(Request $request, string $seller_slug): JsonResponse
    {
        $seller = Seller::query()
            ->where('slug', $seller_slug)
            ->where('status', 'active')
            ->firstOrFail();

        $quota = $this->buildQuotaPayload($request, $seller->slug);
        if (($quota['can_generate'] ?? false) !== true) {
            return response()->json([
                'message' => 'Batas generate harian habis untuk device/IP ini.',
                'quota' => $quota,
            ], 429);
        }

        $payload = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'customer_photo' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        // Locked to the lowest-cost provider mode (balanced + 1k via "standard").
        $qualityMode = 'standard';

        $product = $seller->products()
            ->where('id', $payload['product_id'])
            ->where('status', 'active')
            ->first();

        if (! $product) {
            return response()->json(['message' => 'Produk tidak valid untuk seller ini.'], 422);
        }

        $usage = $seller->usageBalance;
        if (! $usage || $usage->token_available < 1) {
            return response()->json(['message' => 'Token seller tidak tersedia.'], 422);
        }

        $photoPath = $payload['customer_photo']->store('tryon/customers/'.$seller->id, 'public');

        $session = TryOnSession::query()->create([
            'seller_id' => $seller->id,
            'product_id' => $product->id,
            'customer_photo_path' => $photoPath,
            'quality_mode' => $qualityMode,
            'source_channel' => 'seller_subpage',
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'provider_name' => 'fashn',
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes((int) config('tryon.retention_minutes')),
        ]);

        AuditLog::query()->create([
            'actor_user_id' => null,
            'action' => 'tryon_session_created_public',
            'entity_type' => TryOnSession::class,
            'entity_id' => $session->id,
            'payload_json' => [
                'seller_id' => $seller->id,
                'product_id' => $product->id,
                'source_channel' => 'seller_subpage',
                'quality_mode' => $qualityMode,
                'ip_address' => $request->ip(),
            ],
        ]);

        Log::info('Public try-on session created', [
            'session_id' => $session->id,
            'seller_id' => $seller->id,
            'product_id' => $product->id,
            'quality_mode' => $qualityMode,
            'source_channel' => 'seller_subpage',
        ]);

        ProcessTryOnSessionJob::dispatch($session->id);

        // Count only valid created sessions toward daily public generate limit.
        $this->consumeGenerateQuota($request, $seller->slug);

        return response()->json([
            ...$this->sessionResponse($session->fresh()),
            'quota' => $this->buildQuotaPayload($request, $seller->slug),
        ], 201);
    }

    public function show(string $seller_slug, int $sessionId): JsonResponse
    {
        $seller = Seller::query()
            ->where('slug', $seller_slug)
            ->where('status', 'active')
            ->firstOrFail();

        $session = TryOnSession::query()
            ->where('seller_id', $seller->id)
            ->where('id', $sessionId)
            ->firstOrFail();

        $this->refreshProcessingSession($session);

        return response()->json($this->sessionResponse($session));
    }

    private function refreshProcessingSession(TryOnSession $session): void
    {
        if ($session->status !== 'processing' || ! $session->provider_job_id) {
            return;
        }
        $session->loadMissing('seller.aiSetting');

        try {
            /** @var ProviderRouter $providerRouter */
            $providerRouter = app(ProviderRouter::class);
            $provider = $providerRouter->resolve($session->provider_name);
            $status = $provider->getJobStatus((string) $session->provider_job_id, [
                'provider_config' => $this->resolveProviderConfig($session->seller),
            ]);

            if (($status['status'] ?? null) === 'completed') {
                $cost = $provider->estimateCost([
                    'quality_mode' => $session->quality_mode,
                    'num_images' => 1,
                ]);

                if ((int) $session->token_cost === 0) {
                    $usage = SellerUsageBalance::query()->where('seller_id', $session->seller_id)->first();
                    if ($usage) {
                        $usage->incrementEach([
                            'token_used' => $cost,
                            'token_available' => -$cost,
                            'success_count' => 1,
                        ]);
                    }
                }

                $session->update([
                    'status' => 'completed',
                    'result_path' => $status['result_path'] ?? $status['result_url'] ?? $session->result_path,
                    'token_cost' => $cost,
                    'expires_at' => Carbon::now()->addMinutes((int) config('tryon.retention_minutes')),
                ]);
            }

            if (($status['status'] ?? null) === 'failed') {
                $session->update([
                    'status' => 'failed',
                    'error_message' => $status['error_message'] ?? 'Provider failure.',
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('Failed to refresh processing try-on session', [
                'session_id' => $session->id,
                'provider_job_id' => $session->provider_job_id,
                'message' => $exception->getMessage(),
            ]);
        }

        $session->refresh();
    }

    private function sessionResponse(TryOnSession $session): array
    {
        return [
            'id' => $session->id,
            'status' => $session->status,
            'quality_mode' => $session->quality_mode,
            'error_message' => $session->error_message,
            'token_cost' => $session->token_cost,
            'result_url' => $this->toPublicUrl($session->result_path),
            'customer_photo_url' => $this->toPublicUrl($session->customer_photo_path),
            'created_at' => optional($session->created_at)?->toISOString(),
            'updated_at' => optional($session->updated_at)?->toISOString(),
        ];
    }

    private function toPublicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    private function buildQuotaPayload(Request $request, string $sellerSlug): array
    {
        $dailyLimit = max((int) config('tryon.public_limits.generate_per_day', 3), 1);
        $minuteLimit = max((int) config('tryon.public_limits.generate_per_minute_per_ip', 3), 1);

        $ipDailyRemaining = RateLimiter::remaining($this->dailyIpKey($request, $sellerSlug), $dailyLimit);
        $deviceDailyRemaining = RateLimiter::remaining($this->dailyDeviceKey($request, $sellerSlug), $dailyLimit);
        $minuteIpRemaining = RateLimiter::remaining($this->minuteIpKey($request, $sellerSlug), $minuteLimit);
        $remaining = max(min($ipDailyRemaining, $deviceDailyRemaining), 0);
        $canGenerate = $remaining > 0 && $minuteIpRemaining > 0;

        return [
            'daily_limit' => $dailyLimit,
            'remaining' => $remaining,
            'ip_daily_remaining' => max($ipDailyRemaining, 0),
            'device_daily_remaining' => max($deviceDailyRemaining, 0),
            'minute_remaining' => max($minuteIpRemaining, 0),
            'can_generate' => $canGenerate,
        ];
    }

    private function consumeGenerateQuota(Request $request, string $sellerSlug): void
    {
        RateLimiter::hit($this->minuteIpKey($request, $sellerSlug), 60);
        RateLimiter::hit($this->dailyIpKey($request, $sellerSlug), 86400);
        RateLimiter::hit($this->dailyDeviceKey($request, $sellerSlug), 86400);
    }

    private function minuteIpKey(Request $request, string $sellerSlug): string
    {
        return 'create-minute-ip|'.$sellerSlug.'|'.$request->ip();
    }

    private function dailyIpKey(Request $request, string $sellerSlug): string
    {
        return 'create-day-ip|'.$sellerSlug.'|'.$request->ip();
    }

    private function dailyDeviceKey(Request $request, string $sellerSlug): string
    {
        return 'create-day-device|'.$sellerSlug.'|'.$this->resolveTryOnDeviceId($request);
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

    private function resolveProviderConfig(?Seller $seller): array
    {
        if (! $seller) {
            return [];
        }

        $setting = $seller->aiSetting;
        if (! $setting) {
            return [];
        }

        $config = [
            'dummy_enabled' => (bool) $setting->fashn_dummy_enabled,
        ];

        if (is_string($setting->fashn_api_key) && trim($setting->fashn_api_key) !== '') {
            $config['api_key'] = $setting->fashn_api_key;
        }

        if (is_string($setting->fashn_model) && trim($setting->fashn_model) !== '') {
            $config['model'] = $setting->fashn_model;
        }

        if (is_string($setting->fashn_dummy_result_url) && trim($setting->fashn_dummy_result_url) !== '') {
            $config['dummy_result_url'] = $setting->fashn_dummy_result_url;
        }

        return $config;
    }

    private function canUseProvider(array $providerConfig): bool
    {
        return isset($providerConfig['api_key']) && trim((string) $providerConfig['api_key']) !== '';
    }
}
