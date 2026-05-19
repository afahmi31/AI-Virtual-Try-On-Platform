<?php

namespace App\Jobs;

use App\Domain\AI\ProviderRouter;
use App\Models\AuditLog;
use App\Models\SellerUsageBalance;
use App\Models\TryOnSession;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessTryOnSessionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $sessionId)
    {
    }

    public function handle(ProviderRouter $providerRouter): void
    {
        $session = TryOnSession::query()->find($this->sessionId);

        if (! $session || $session->status !== 'pending') {
            return;
        }

        $usage = SellerUsageBalance::query()->where('seller_id', $session->seller_id)->lockForUpdate()->first();

        if (! $usage || $usage->token_available < 1) {
            $session->update([
                'status' => 'failed',
                'error_message' => 'Seller token tidak mencukupi.',
            ]);

            return;
        }

        $provider = $providerRouter->resolve($session->provider_name);

        $session->update(['status' => 'processing']);

        $payload = $provider->createJob([
            'product_id' => $session->product_id,
            'customer_photo_path' => $session->customer_photo_path,
            'quality_mode' => $session->quality_mode,
        ]);

        Log::info('Try-on provider job created', [
            'session_id' => $session->id,
            'seller_id' => $session->seller_id,
            'provider_name' => $session->provider_name,
            'provider_job_id' => $payload['provider_job_id'] ?? null,
            'quality_mode' => $session->quality_mode,
        ]);

        $status = $provider->getJobStatus((string) $payload['provider_job_id']);

        if (($status['status'] ?? null) === 'completed') {
            $usage->incrementEach([
                'token_used' => 1,
                'token_available' => -1,
                'success_count' => 1,
            ]);

            $session->update([
                'status' => 'completed',
                'provider_job_id' => $payload['provider_job_id'] ?? null,
                'result_path' => $status['result_path'] ?? $session->customer_photo_path,
                'token_cost' => $provider->estimateCost($payload),
                'expires_at' => Carbon::now()->addMinutes((int) config('tryon.retention_minutes')),
            ]);

            AuditLog::query()->create([
                'actor_user_id' => null,
                'action' => 'tryon_session_completed',
                'entity_type' => TryOnSession::class,
                'entity_id' => $session->id,
                'payload_json' => [
                    'seller_id' => $session->seller_id,
                    'product_id' => $session->product_id,
                    'provider_name' => $session->provider_name,
                    'provider_job_id' => $payload['provider_job_id'] ?? null,
                    'token_cost' => $provider->estimateCost($payload),
                ],
            ]);

            Log::info('Try-on session completed', [
                'session_id' => $session->id,
                'seller_id' => $session->seller_id,
                'provider_job_id' => $payload['provider_job_id'] ?? null,
                'token_cost' => $provider->estimateCost($payload),
            ]);

            return;
        }

        $usage->increment('failed_count');

        $session->update([
            'status' => 'failed',
            'provider_job_id' => $payload['provider_job_id'] ?? null,
            'error_message' => $status['error_message'] ?? 'Provider failure.',
        ]);

        AuditLog::query()->create([
            'actor_user_id' => null,
            'action' => 'tryon_session_failed',
            'entity_type' => TryOnSession::class,
            'entity_id' => $session->id,
            'payload_json' => [
                'seller_id' => $session->seller_id,
                'product_id' => $session->product_id,
                'provider_name' => $session->provider_name,
                'provider_job_id' => $payload['provider_job_id'] ?? null,
                'error_message' => $status['error_message'] ?? 'Provider failure.',
            ],
        ]);

        Log::warning('Try-on session failed', [
            'session_id' => $session->id,
            'seller_id' => $session->seller_id,
            'provider_job_id' => $payload['provider_job_id'] ?? null,
            'error_message' => $status['error_message'] ?? 'Provider failure.',
        ]);
    }
}
