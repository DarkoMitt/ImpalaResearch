<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('research_requests', function (Blueprint $table) {
            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained('subscriptions')
                ->restrictOnDelete();

            $table->unsignedInteger('respondents_reserved')
                ->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('research_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subscription_id');
            $table->dropColumn('respondents_reserved');
        });
    }
};
