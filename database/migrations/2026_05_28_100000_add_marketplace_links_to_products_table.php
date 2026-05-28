<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->string('marketplace_shopee_url', 2048)->nullable()->after('ai_segmentation_free');
            $table->string('marketplace_tiktok_url', 2048)->nullable()->after('marketplace_shopee_url');
            $table->string('marketplace_tokopedia_url', 2048)->nullable()->after('marketplace_tiktok_url');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn([
                'marketplace_shopee_url',
                'marketplace_tiktok_url',
                'marketplace_tokopedia_url',
            ]);
        });
    }
};

