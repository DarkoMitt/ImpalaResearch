
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();

            $table->string('request_type', 30)->default('demo');

            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('company')->nullable();

            $table->text('message')->nullable();

            $table->string('status', 30)->default('new');
            $table->text('admin_notes')->nullable();

            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            $table->index(['request_type', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
