<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_requests', function (Blueprint $table) {
            $table->id();

            // User who submitted the request
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Study Builder
            $table->string('goal');

            // Selected research methods
            $table->json('methods');

            // Study Details
            $table->string('country');
            $table->text('audience');
            $table->unsignedInteger('sample_size');
            $table->string('timeline')->nullable();

            // Contact Information
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('company');

            // Additional Information
            $table->text('notes')->nullable();

            // Workflow
            $table->string('status')->default('submitted');

            // Dates
            $table->timestamps();

            // Useful indexes
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_requests');
    }
};
