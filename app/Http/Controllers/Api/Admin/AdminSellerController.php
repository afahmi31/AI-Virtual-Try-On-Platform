<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Seller;
use App\Models\SellerUsageBalance;
use App\Models\TryOnSession;
use App\Models\User;
use App\Support\SellerSlug;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminSellerController extends Controller
{
    public function index(): JsonResponse
    {
        $sellers = Seller::query()
            ->with(['owner:id,name,email', 'usageBalance'])
            ->latest()
            ->get();

        return response()->json($sellers);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('sellers', 'slug'),
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (SellerSlug::isReserved((string) $value)) {
                        $fail('Slug tidak boleh menggunakan reserved prefix sistem.');
                    }
                },
            ],
            'status' => ['required', 'in:active,suspended,inactive'],
            'owner' => ['required', 'array'],
            'owner.name' => ['required', 'string', 'max:255'],
            'owner.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'owner.password' => ['required', 'string', 'min:8'],
            'initial_token_balance' => ['nullable', 'integer', 'min:0'],
        ]);

        $seller = DB::transaction(function () use ($payload, $request): Seller {
            $owner = User::query()->create([
                'name' => $payload['owner']['name'],
                'email' => $payload['owner']['email'],
                'password' => $payload['owner']['password'],
                'role' => User::ROLE_SELLER,
            ]);

            $seller = Seller::query()->create([
                'owner_user_id' => $owner->id,
                'store_name' => $payload['store_name'],
                'slug' => strtolower($payload['slug']),
                'status' => $payload['status'],
            ]);

            $initialBalance = (int) ($payload['initial_token_balance'] ?? 0);

            SellerUsageBalance::query()->create([
                'seller_id' => $seller->id,
                'token_balance' => $initialBalance,
                'token_available' => $initialBalance,
                'token_used' => 0,
                'success_count' => 0,
                'failed_count' => 0,
            ]);

            AuditLog::query()->create([
                'actor_user_id' => $request->user()?->id,
                'action' => 'admin.seller.create',
                'entity_type' => Seller::class,
                'entity_id' => $seller->id,
                'payload_json' => [
                    'store_name' => $seller->store_name,
                    'slug' => $seller->slug,
                    'status' => $seller->status,
                    'initial_token_balance' => $initialBalance,
                ],
            ]);

            return $seller;
        });

        return response()->json(
            $seller->load(['owner:id,name,email,role', 'usageBalance']),
            201
        );
    }

    public function update(Request $request, int $sellerId): JsonResponse
    {
        $seller = Seller::query()->with('owner')->findOrFail($sellerId);

        $payload = $request->validate([
            'store_name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('sellers', 'slug')->ignore($seller->id),
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (SellerSlug::isReserved((string) $value)) {
                        $fail('Slug tidak boleh menggunakan reserved prefix sistem.');
                    }
                },
            ],
            'status' => ['sometimes', 'in:active,suspended,inactive'],
            'owner_name' => ['sometimes', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($seller, $payload, $request): void {
            $before = $seller->only(['store_name', 'slug', 'status']);

            if (isset($payload['store_name'])) {
                $seller->store_name = $payload['store_name'];
            }

            if (isset($payload['slug'])) {
                $seller->slug = strtolower($payload['slug']);
            }

            if (isset($payload['status'])) {
                $seller->status = $payload['status'];
            }

            $seller->save();

            if (isset($payload['owner_name'])) {
                $seller->owner->name = $payload['owner_name'];
                $seller->owner->save();
            }

            AuditLog::query()->create([
                'actor_user_id' => $request->user()?->id,
                'action' => 'admin.seller.update',
                'entity_type' => Seller::class,
                'entity_id' => $seller->id,
                'payload_json' => [
                    'before' => $before,
                    'after' => $seller->only(['store_name', 'slug', 'status']),
                ],
            ]);
        });

        return response()->json($seller->fresh()->load(['owner:id,name,email,role', 'usageBalance']));
    }

    public function topUpTokens(Request $request, int $sellerId): JsonResponse
    {
        $payload = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $seller = Seller::query()->findOrFail($sellerId);

        $usage = DB::transaction(function () use ($seller, $payload, $request): SellerUsageBalance {
            $usage = SellerUsageBalance::query()
                ->where('seller_id', $seller->id)
                ->lockForUpdate()
                ->firstOrFail();

            $usage->incrementEach([
                'token_balance' => (int) $payload['amount'],
                'token_available' => (int) $payload['amount'],
            ]);

            AuditLog::query()->create([
                'actor_user_id' => $request->user()?->id,
                'action' => 'admin.seller.topup',
                'entity_type' => Seller::class,
                'entity_id' => $seller->id,
                'payload_json' => [
                    'amount' => (int) $payload['amount'],
                    'note' => $payload['note'] ?? null,
                ],
            ]);

            return $usage->fresh();
        });

        return response()->json($usage);
    }

    public function show(int $sellerId): JsonResponse
    {
        $seller = Seller::query()
            ->with(['owner:id,name,email', 'usageBalance'])
            ->findOrFail($sellerId);

        $sessionQuery = TryOnSession::query()->where('seller_id', $seller->id);

        $metrics = [
            'total_generate' => (int) $sessionQuery->count(),
            'pending_count' => (int) (clone $sessionQuery)->where('status', 'pending')->count(),
            'processing_count' => (int) (clone $sessionQuery)->where('status', 'processing')->count(),
            'completed_count' => (int) (clone $sessionQuery)->where('status', 'completed')->count(),
            'failed_count' => (int) (clone $sessionQuery)->where('status', 'failed')->count(),
            'expired_count' => (int) (clone $sessionQuery)->where('status', 'expired')->count(),
            'total_token_cost' => (int) (clone $sessionQuery)->sum('token_cost'),
        ];

        return response()->json([
            'seller' => $seller,
            'metrics' => $metrics,
        ]);
    }

    public function metrics(): JsonResponse
    {
        $sessionQuery = TryOnSession::query();

        $summary = [
            'total_sellers' => Seller::query()->count(),
            'active_sellers' => Seller::query()->where('status', 'active')->count(),
            'suspended_sellers' => Seller::query()->where('status', 'suspended')->count(),
            'total_generate' => (int) $sessionQuery->count(),
            'pending_generate' => (int) (clone $sessionQuery)->where('status', 'pending')->count(),
            'processing_generate' => (int) (clone $sessionQuery)->where('status', 'processing')->count(),
            'completed_generate' => (int) (clone $sessionQuery)->where('status', 'completed')->count(),
            'failed_generate' => (int) (clone $sessionQuery)->where('status', 'failed')->count(),
            'total_token_balance' => (int) SellerUsageBalance::query()->sum('token_balance'),
            'total_token_used' => (int) SellerUsageBalance::query()->sum('token_used'),
            'total_token_available' => (int) SellerUsageBalance::query()->sum('token_available'),
            'total_success_count' => (int) SellerUsageBalance::query()->sum('success_count'),
            'total_failed_count' => (int) SellerUsageBalance::query()->sum('failed_count'),
            'total_ai_cost_usage' => (int) TryOnSession::query()->sum('token_cost'),
        ];

        return response()->json($summary);
    }
}
