<?php

namespace App\Domain\AI\Providers;

use App\Domain\AI\Contracts\TryOnProviderContract;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FashnProvider implements TryOnProviderContract
{
    public function createJob(array $input): array
    {
        $createUrl = $this->resolveCreateUrl();
        $generationMode = $this->resolveGenerationMode((string) ($input['quality_mode'] ?? 'standard'));
        $resolution = $this->resolveResolution((string) ($input['quality_mode'] ?? 'standard'));

        $response = $this->httpClient()->post($createUrl, [
            'model_name' => (string) config('ai.providers.fashn.model', 'tryon-max'),
            'inputs' => [
                'product_image' => $input['product_image_url'] ?? null,
                'model_image' => $input['model_image_url'] ?? null,
            ],
            'generation_mode' => $generationMode,
            'resolution' => $resolution,
            'num_images' => 1,
            'output_format' => 'png',
            'return_base64' => false,
        ]);

        $body = $response->json();
        $providerJobId = $body['id'] ?? $body['job_id'] ?? null;

        if (! is_string($providerJobId) || $providerJobId === '') {
            throw new RuntimeException('FASHN response missing job id.');
        }

        return [
            'provider_name' => 'fashn',
            'provider_model' => (string) config('ai.providers.fashn.model', 'unknown'),
            'provider_job_id' => $providerJobId,
            'status' => 'processing',
            'result_path' => $body['result_path'] ?? null,
            'result_url' => $body['result_url'] ?? ($body['result']['url'] ?? null),
            'error_message' => $body['error_message'] ?? ($body['error']['message'] ?? null),
            'provider_usage' => $this->extractUsage($body),
            'http_status' => $response->status(),
            'provider_endpoint' => $createUrl,
            'provider_method' => 'POST',
            'raw' => $body,
        ];
    }

    public function getJobStatus(string $jobId): array
    {
        $statusUrl = $this->resolveStatusUrl($jobId);

        $response = $this->httpClient()->get($statusUrl);
        $body = $response->json();
        $output = $body['output'] ?? [];
        $resultUrl = is_array($output) && isset($output[0]) && is_string($output[0]) ? $output[0] : null;

        return [
            'provider_name' => 'fashn',
            'provider_model' => (string) config('ai.providers.fashn.model', 'unknown'),
            'provider_job_id' => $jobId,
            'status' => $this->normalizeStatus($body['status'] ?? 'processing'),
            'result_path' => $body['result_path'] ?? null,
            'result_url' => $body['result_url'] ?? ($body['result']['url'] ?? $resultUrl),
            'error_message' => $body['error_message'] ?? ($body['error']['message'] ?? ($body['error'] ?? null)),
            'provider_usage' => $this->extractUsage($body),
            'http_status' => $response->status(),
            'provider_endpoint' => $statusUrl,
            'provider_method' => 'GET',
            'raw' => $body,
        ];
    }

    public function estimateCost(array $input): int
    {
        $qualityMode = (string) ($input['quality_mode'] ?? 'standard');
        $generationMode = $this->resolveGenerationMode($qualityMode);
        $resolution = $this->resolveResolution($qualityMode);
        $numImages = max(1, (int) ($input['num_images'] ?? 1));

        $baseCost = match ($generationMode.'|'.$resolution) {
            'balanced|1k' => 2,
            'balanced|2k' => 3,
            'balanced|4k' => 4,
            'quality|1k' => 3,
            'quality|2k' => 4,
            'quality|4k' => 5,
            default => 2,
        };

        return $baseCost * $numImages;
    }

    private function httpClient()
    {
        $config = (array) config('ai.providers.fashn');
        $apiKey = (string) ($config['api_key'] ?? '');

        if ($apiKey === '') {
            throw new RuntimeException('FASHN provider configuration is missing.');
        }

        $timeoutSeconds = max((int) ($config['timeout_seconds'] ?? 60), 1);
        $retryTimes = max((int) ($config['retry_times'] ?? 2), 0);
        $retrySleepMs = max((int) ($config['retry_sleep_ms'] ?? 300), 0);

        return Http::acceptJson()
            ->asJson()
            ->withToken($apiKey)
            ->timeout($timeoutSeconds)
            ->retry($retryTimes, $retrySleepMs, function ($exception): bool {
                return $exception instanceof ConnectionException || $exception instanceof RequestException;
            })
            ->throw(function ($response, $e): void {
                $this->throwMappedException($response->status(), (array) $response->json(), $e);
            });
    }

    private function resolveCreateUrl(): string
    {
        $config = (array) config('ai.providers.fashn');
        $runUrl = trim((string) ($config['run_url'] ?? ''));
        $baseUrl = trim((string) ($config['base_url'] ?? ''));

        if ($runUrl !== '') {
            return $runUrl;
        }

        if ($baseUrl !== '') {
            return $baseUrl;
        }

        throw new RuntimeException('FASHN run URL is missing. Set FASHN_RUN_URL or FASHN_BASE_URL.');
    }

    private function resolveStatusUrl(string $jobId): string
    {
        $config = (array) config('ai.providers.fashn');
        $template = trim((string) ($config['status_url_template'] ?? ''));
        if ($template !== '') {
            return str_replace('{job_id}', $jobId, $template);
        }

        $createUrl = $this->resolveCreateUrl();
        if (str_ends_with($createUrl, '/run')) {
            return preg_replace('#/run$#', '/status/'.$jobId, $createUrl) ?? $createUrl;
        }

        if (str_ends_with($createUrl, '/jobs')) {
            return rtrim($createUrl, '/').'/'.$jobId;
        }

        throw new RuntimeException('FASHN status URL is missing. Set FASHN_STATUS_URL_TEMPLATE (example: https://api.fashn.ai/v1/status/{job_id}).');
    }

    private function normalizeStatus(string $status): string
    {
        return match (strtolower($status)) {
            'completed', 'success', 'succeeded', 'done' => 'completed',
            'starting', 'queued', 'pending', 'running', 'in_progress', 'processing' => 'processing',
            'failed', 'error', 'cancelled', 'canceled' => 'failed',
            default => 'processing',
        };
    }

    private function throwMappedException(int $statusCode, array $body, RequestException $exception): never
    {
        $providerError = (string) ($body['error_message'] ?? '');
        if ($providerError === '' && isset($body['error']) && is_string($body['error'])) {
            $providerError = $body['error'];
        }
        if ($providerError === '' && isset($body['error']) && is_array($body['error'])) {
            $providerError = (string) ($body['error']['message'] ?? '');
        }
        if ($providerError === '' && isset($body['detail']) && is_string($body['detail'])) {
            $providerError = $body['detail'];
        }
        $message = $providerError !== '' ? $providerError : 'FASHN provider request failed.';

        if ($statusCode === 422 || $statusCode === 400) {
            throw new RuntimeException('FASHN invalid payload: '.$message, previous: $exception);
        }

        if ($statusCode === 401 || $statusCode === 403) {
            throw new RuntimeException('FASHN unauthorized request: '.$message, previous: $exception);
        }

        if ($statusCode >= 500) {
            throw new RuntimeException('FASHN server error: '.$message, previous: $exception);
        }

        throw new RuntimeException($message, previous: $exception);
    }

    private function extractUsage(array $body): array
    {
        $usage = (array) ($body['usage'] ?? []);
        $billing = (array) ($body['billing'] ?? []);

        return array_filter([
            'tokens_used' => $usage['tokens_used'] ?? $usage['total_tokens'] ?? $body['tokens_used'] ?? null,
            'credits_used' => $usage['credits_used'] ?? $billing['credits_used'] ?? $body['credits_used'] ?? null,
            'estimated_cost' => $usage['estimated_cost'] ?? $billing['estimated_cost'] ?? $body['estimated_cost'] ?? null,
            'currency' => $usage['currency'] ?? $billing['currency'] ?? $body['currency'] ?? null,
        ], static fn ($value) => $value !== null && $value !== '');
    }

    private function resolveGenerationMode(string $qualityMode): string
    {
        return match ($qualityMode) {
            'ultra' => 'quality',
            default => 'balanced',
        };
    }

    private function resolveResolution(string $qualityMode): string
    {
        return match ($qualityMode) {
            'ultra' => '4k',
            'hd' => '2k',
            default => '1k',
        };
    }
}
