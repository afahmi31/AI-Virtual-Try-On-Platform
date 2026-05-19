<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessTryOnSessionJob;
use App\Models\AuditLog;
use App\Models\Seller;
use App\Models\TryOnSession;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TryOnPublicController extends Controller
{
    public function store(Request $request, string $seller_slug): JsonResponse
    {
        $seller = Seller::query()
            ->where('slug', $seller_slug)
            ->where('status', 'active')
            ->firstOrFail();

        $payload = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quality_mode' => ['required', 'in:standard,hd,ultra'],
            'customer_photo' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

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
            'quality_mode' => $payload['quality_mode'],
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
                'quality_mode' => $payload['quality_mode'],
                'ip_address' => $request->ip(),
            ],
        ]);

        Log::info('Public try-on session created', [
            'session_id' => $session->id,
            'seller_id' => $seller->id,
            'product_id' => $product->id,
            'quality_mode' => $payload['quality_mode'],
            'source_channel' => 'seller_subpage',
        ]);

        ProcessTryOnSessionJob::dispatch($session->id);

        return response()->json($this->sessionResponse($session->fresh()), 201);
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

        return response()->json($this->sessionResponse($session));
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
}

