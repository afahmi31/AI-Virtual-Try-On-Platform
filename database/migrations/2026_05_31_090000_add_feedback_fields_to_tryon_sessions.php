<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tryon_sessions', function (Blueprint $table) {
            $table->unsignedTinyInteger('feedback_rating')
                ->nullable()
                ->after('token_cost');
            $table->text('feedback_comment')
                ->nullable()
                ->after('feedback_rating');
            $table->timestamp('feedback_submitted_at')
                ->nullable()
                ->after('feedback_comment');
        });
    }

    public function down(): void
    {
        Schema::table('tryon_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'feedback_rating',
                'feedback_comment',
                'feedback_submitted_at',
            ]);
        });
    }
};
