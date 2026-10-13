<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_cancellation_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('subscription_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('type', 30);
            $table->string('status', 30)->default('pending');

            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['subscription_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_cancellation_requests');
    }
};
