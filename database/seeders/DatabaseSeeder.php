<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\SellerAiSetting;
use App\Models\SellerUsageBalance;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $sellerOwner = User::query()->updateOrCreate(
            ['email' => 'seller@tryon.test'],
            [
                'name' => 'Demo Seller',
                'password' => 'password',
                'role' => User::ROLE_SELLER,
            ]
        );

        $seller = Seller::query()->updateOrCreate(
            ['slug' => 'ceriakid'],
            [
                'owner_user_id' => $sellerOwner->id,
                'store_name' => 'CeriaKid',
                'status' => 'active',
            ]
        );

        SellerUsageBalance::query()->updateOrCreate(
            ['seller_id' => $seller->id],
            [
                'token_balance' => 100,
                'token_used' => 0,
                'token_available' => 100,
                'success_count' => 0,
                'failed_count' => 0,
            ]
        );

        SellerAiSetting::query()->updateOrCreate(
            ['seller_id' => $seller->id],
            [
                'provider_name' => 'fashn',
                'fashn_api_key' => null,
                'fashn_model' => 'tryon-max',
                'fashn_dummy_enabled' => false,
                'fashn_dummy_result_url' => null,
            ]
        );
    }
}
