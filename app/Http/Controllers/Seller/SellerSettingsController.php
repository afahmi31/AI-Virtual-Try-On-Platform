<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerAiSetting;
use App\Support\CurrentSellerResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class SellerSettingsController extends Controller
{
    public function __construct(private readonly CurrentSellerResolver $currentSellerResolver)
    {
    }

    public function index()
    {
        $seller = $this->currentSellerResolver->resolveForUser(auth()->user());
        $setting = $seller->aiSetting;

        return view('seller.settings.index', compact('seller', 'setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $seller = $this->currentSellerResolver->resolveForUser($request->user());

        $payload = $request->validate([
            'fashn_api_key' => ['nullable', 'string', 'max:500'],
            'fashn_model' => ['required', 'in:tryon-v1.6,tryon-max'],
            'fashn_tryon_max_generation_mode' => ['nullable', 'in:balanced,quality'],
            'fashn_tryon_max_resolution' => ['nullable', 'in:1k,2k,4k'],
            'fashn_tryon_max_output_format' => ['nullable', 'in:png,jpeg'],
            'fashn_tryon_v16_mode' => ['nullable', 'in:performance,balanced,quality'],
            'fashn_tryon_v16_num_samples' => ['nullable', 'integer', 'min:1', 'max:4'],
            'fashn_tryon_v16_output_format' => ['nullable', 'in:png,jpeg'],
            'fashn_dummy_enabled' => ['nullable', 'boolean'],
            'fashn_dummy_result_url' => ['nullable', 'url', 'max:2048'],
            'fashn_dummy_model_image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $setting = SellerAiSetting::query()->firstOrNew(['seller_id' => $seller->id]);
        $setting->provider_name = 'fashn';

        if (array_key_exists('fashn_api_key', $payload) && trim((string) $payload['fashn_api_key']) !== '') {
            $setting->fashn_api_key = $payload['fashn_api_key'];
        }

        $setting->fashn_model = $payload['fashn_model'];
        $setting->fashn_tryon_max_generation_mode = $payload['fashn_tryon_max_generation_mode'] ?? 'balanced';
        $setting->fashn_tryon_max_resolution = $payload['fashn_tryon_max_resolution'] ?? '1k';
        $setting->fashn_tryon_max_output_format = $payload['fashn_tryon_max_output_format'] ?? 'png';
        $setting->fashn_tryon_v16_mode = $payload['fashn_tryon_v16_mode'] ?? 'balanced';
        $setting->fashn_tryon_v16_num_samples = (int) ($payload['fashn_tryon_v16_num_samples'] ?? 1);
        $setting->fashn_tryon_v16_output_format = $payload['fashn_tryon_v16_output_format'] ?? 'png';

        $setting->fashn_dummy_enabled = (bool) ($payload['fashn_dummy_enabled'] ?? false);
        $setting->fashn_dummy_result_url = $payload['fashn_dummy_result_url'] ?? null;
        $setting->fashn_dummy_model_image_url = $payload['fashn_dummy_model_image_url'] ?? null;
        $setting->save();

        return redirect()->route('seller.settings.index')->with('success', 'FASHN setting berhasil disimpan.');
    }

    public function testApiKey(Request $request): JsonResponse
    {
        $seller = $this->currentSellerResolver->resolveForUser($request->user());
        $setting = $seller->aiSetting;

        $payload = $request->validate([
            'fashn_api_key' => ['nullable', 'string', 'max:500'],
        ]);

        $candidateKey = trim((string) ($payload['fashn_api_key'] ?? ''));
        $apiKey = $candidateKey !== '' ? $candidateKey : trim((string) ($setting?->fashn_api_key ?? ''));

        if ($apiKey === '') {
            $this->saveTestResult($seller->id, false, 'API key kosong. Isi API key terlebih dahulu.');

            return response()->json([
                'ok' => false,
                'message' => 'API key kosong. Isi API key terlebih dahulu.',
            ], 422);
        }

        $creditsUrl = 'https://api.fashn.ai/v1/credits';
        $timeoutSeconds = max((int) config('ai.providers.fashn.timeout_seconds', 60), 5);

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->timeout($timeoutSeconds)
                ->get($creditsUrl);

            if ($response->status() === 401 || $response->status() === 403) {
                $this->saveTestResult($seller->id, false, 'API key tidak valid (unauthorized).');

                return response()->json([
                    'ok' => false,
                    'message' => 'API key tidak valid (unauthorized).',
                ], 422);
            }

            if (! $response->successful()) {
                $message = 'Gagal menguji API key. HTTP '.$response->status();
                $this->saveTestResult($seller->id, false, $message);

                return response()->json([
                    'ok' => false,
                    'message' => $message,
                ], 422);
            }

            $body = (array) $response->json();
            if ($candidateKey !== '') {
                $setting = SellerAiSetting::query()->firstOrNew(['seller_id' => $seller->id]);
                $setting->provider_name = 'fashn';
                $setting->fashn_api_key = $candidateKey;
                $setting->save();
            }
            $this->saveTestResult($seller->id, true, 'API key valid dan dapat mengakses endpoint credits.');

            return response()->json([
                'ok' => true,
                'message' => 'API key valid dan dapat mengakses endpoint credits.',
                'credits' => $body,
                'configured' => true,
            ]);
        } catch (\Throwable $exception) {
            $message = 'API key test gagal: '.$exception->getMessage();
            $this->saveTestResult($seller->id, false, $message);

            return response()->json([
                'ok' => false,
                'message' => $message,
            ], 422);
        }
    }

    private function saveTestResult(int $sellerId, bool $ok, string $message): void
    {
        $setting = SellerAiSetting::query()->firstOrNew(['seller_id' => $sellerId]);
        $setting->provider_name = $setting->provider_name ?: 'fashn';
        $setting->fashn_api_key_last_test_ok = $ok;
        $setting->fashn_api_key_last_test_message = $message;
        $setting->fashn_api_key_last_tested_at = Carbon::now();
        $setting->save();
    }
}
