<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            if (! Schema::hasColumn('products', 'product_link_url')) {
                $table->string('product_link_url', 2048)->nullable()->after('ai_segmentation_free');
            }
        });

        if (
            Schema::hasColumn('products', 'marketplace_shopee_url')
            && Schema::hasColumn('products', 'marketplace_tiktok_url')
            && Schema::hasColumn('products', 'marketplace_tokopedia_url')
        ) {
            DB::table('products')->update([
                'product_link_url' => DB::raw("COALESCE(product_link_url, marketplace_shopee_url, marketplace_tiktok_url, marketplace_tokopedia_url)"),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            if (Schema::hasColumn('products', 'product_link_url')) {
                $table->dropColumn('product_link_url');
            }
        });
    }
};

