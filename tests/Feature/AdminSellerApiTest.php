<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminSellerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_seller_with_reserved_slug(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'password' => 'password123',
        ]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/admin/sellers', [
            'store_name' => 'Test Store',
            'slug' => 'admin',
            'status' => 'active',
            'owner' => [
                'name' => 'Owner Test',
                'email' => 'owner1@example.com',
                'password' => 'password123',
            ],
            'initial_token_balance' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['slug']);
    }

    public function test_admin_can_create_seller_and_topup_tokens(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'password' => 'password123',
        ]);

        Sanctum::actingAs($admin);

        $createResponse = $this->postJson('/api/admin/sellers', [
            'store_name' => 'Fresh Store',
            'slug' => 'fresh-store',
            'status' => 'active',
            'owner' => [
                'name' => 'Owner Fresh',
                'email' => 'owner2@example.com',
                'password' => 'password123',
            ],
            'initial_token_balance' => 5,
        ]);

        $createResponse->assertCreated();

        $sellerId = $createResponse->json('id');

        $this->assertDatabaseHas('sellers', [
            'id' => $sellerId,
            'slug' => 'fresh-store',
        ]);

        $topupResponse = $this->postJson('/api/admin/sellers/'.$sellerId.'/topup', [
            'amount' => 7,
            'note' => 'manual adjustment',
        ]);

        $topupResponse->assertOk();

        $this->assertDatabaseHas('seller_usage_balances', [
            'seller_id' => $sellerId,
            'token_balance' => 12,
            'token_available' => 12,
        ]);
    }
}
