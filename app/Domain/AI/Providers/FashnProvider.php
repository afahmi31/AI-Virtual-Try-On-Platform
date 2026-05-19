<?php

namespace App\Domain\AI\Providers;

use App\Domain\AI\Contracts\TryOnProviderContract;

class FashnProvider implements TryOnProviderContract
{
    public function createJob(array $input): array
    {
        return [
            'provider_job_id' => (string) str()->uuid(),
            'status' => 'processing',
            'raw' => $input,
        ];
    }

    public function getJobStatus(string $jobId): array
    {
        return [
            'provider_job_id' => $jobId,
            'status' => 'completed',
            'result_path' => null,
            'error_message' => null,
        ];
    }

    public function estimateCost(array $input): int
    {
        return 1;
    }
}