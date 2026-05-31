<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_requests')) {
            return;
        }

        Schema::table('product_requests', function (Blueprint $table): void {
            if (! Schema::hasColumn('product_requests', 'linked_product_id')) {
                $table->foreignId('linked_product_id')
                    ->nullable()
                    ->after('seller_id')
                    ->constrained('products')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('product_requests', 'reviewed_at')) {
                $table->timestamp('reviewed_at')
                    ->nullable()
                    ->after('status');
            }
        });

        DB::table('product_requests')
            ->where(function ($query): void {
                $query->whereNull('status')
                    ->orWhere('status', '')
                    ->orWhere('status', 'pending');
            })
            ->update(['status' => 'new']);

        $driver = (string) DB::getDriverName();
        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE product_requests ALTER COLUMN status SET DEFAULT 'new'");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_requests')) {
            return;
        }

        Schema::table('product_requests', function (Blueprint $table): void {
            if (Schema::hasColumn('product_requests', 'linked_product_id')) {
                $table->dropConstrainedForeignId('linked_product_id');
            }

            if (Schema::hasColumn('product_requests', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
        });

        if ((string) DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE product_requests ALTER COLUMN status SET DEFAULT 'pending'");
        }
    }
};
