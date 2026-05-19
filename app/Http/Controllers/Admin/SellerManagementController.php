<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Seller;
use App\Models\SellerUsageBalance;
use App\Models\User;
use App\Support\SellerSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SellerManagementController extends Controller
{
    public function index()
    {
        $sellers = Seller::query()->with(['owner', 'usageBalance'])->latest()->paginate(20);

        return view('admin.sellers.index', compact('sellers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('sellers', 'slug'),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (SellerSlug::isReserved((string) $value)) {
                        $fail('Slug termasuk reserved prefix sistem.');
                    }
                },
            ],
            'status' => ['required', 'in:active,suspended,inactive'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')],
            'owner_password' => ['required', 'string', 'min:8'],
            'initial_token_balance' => ['nullable', 'integer', 'min:0'],
        ]);

        $ownerEmail = $payload['owner_email'] ?? (strtolower($payload['slug']).'@seller.local');

        if (User::query()->where('email', $ownerEmail)->exists()) {
            return back()->withErrors([
                'owner_email' => 'Owner email bentrok. Isi owner email manual yang unik.',
            ])->withInput();
        }

        DB::transaction(function () use ($payload, $request): void {
            $owner = User::query()->create([
                'name' => $payload['owner_name'],
                'email' => $payload['owner_email'] ?? (strtolower($payload['slug']).'@seller.local'),
                'password' => $payload['owner_password'],
                'role' => User::ROLE_SELLER,
            ]);

            $seller = Seller::query()->create([
                'owner_user_id' => $owner->id,
                'store_name' => $payload['store_name'],
                'slug' => strtolower($payload['slug']),
                'status' => $payload['status'],
            ]);

            $initial = (int) ($payload['initial_token_balance'] ?? 0);

            SellerUsageBalance::query()->create([
                'seller_id' => $seller->id,
                'token_balance' => $initial,
                'token_available' => $initial,
                'token_used' => 0,
                'success_count' => 0,
                'failed_count' => 0,
            ]);

            AuditLog::query()->create([
                'actor_user_id' => $request->user()?->id,
                'action' => 'admin.web.seller.create',
                'entity_type' => Seller::class,
                'entity_id' => $seller->id,
                'payload_json' => [
                    'store_name' => $seller->store_name,
                    'slug' => $seller->slug,
                    'initial_token_balance' => $initial,
                ],
            ]);
        });

        return redirect()->route('admin.sellers.index')->with('success', 'Seller berhasil dibuat.');
    }

    public function update(Request $request, int $sellerId): RedirectResponse
    {
        $seller = Seller::query()->with('owner')->findOrFail($sellerId);

        $payload = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('sellers', 'slug')->ignore($seller->id),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (SellerSlug::isReserved((string) $value)) {
                        $fail('Slug termasuk reserved prefix sistem.');
                    }
                },
            ],
            'status' => ['required', 'in:active,suspended,inactive'],
            'owner_name' => ['required', 'string', 'max:255'],
        ]);

        $seller->update([
            'store_name' => $payload['store_name'],
            'slug' => strtolower($payload['slug']),
            'status' => $payload['status'],
        ]);

        $seller->owner->update([
            'name' => $payload['owner_name'],
        ]);

        return redirect()->route('admin.sellers.index')->with('success', 'Seller berhasil diupdate.');
    }

    public function topup(Request $request, int $sellerId): RedirectResponse
    {
        $payload = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        $usage = SellerUsageBalance::query()->where('seller_id', $sellerId)->lockForUpdate()->firstOrFail();
        $usage->incrementEach([
            'token_balance' => (int) $payload['amount'],
            'token_available' => (int) $payload['amount'],
        ]);

        return redirect()->route('admin.sellers.index')->with('success', 'Top-up token berhasil.');
    }
}
