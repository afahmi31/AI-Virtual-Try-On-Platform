<?php

namespace App\Jobs;

use App\Domain\AI\ProviderRouter;
use App\Models\SellerUsageBalance;
use App\Models\TryOnSession;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
                'result_path' => $status['result_path'] ?? null,
                'token_cost' => $provider->estimateCost($payload),
                'expires_at' => Carbon::now()->addMinutes((int) config('tryon.retention_minutes')),
            ]);

            return;
        }

        $usage->increment('failed_count');

        $session->update([
            'status' => 'failed',
            'provider_job_id' => $payload['provider_job_id'] ?? null,
            'error_message' => $status['error_message'] ?? 'Provider failure.',
        ]);
    }
}