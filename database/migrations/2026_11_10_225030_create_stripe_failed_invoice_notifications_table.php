
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_failed_invoice_notifications', function (Blueprint $table) {
            $table->id();

            $table->string('stripe_invoice_id')->unique();

            $table->foreignId('subscription_id')
                ->constrained('subscriptions')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_failed_invoice_notifications');
    }
};
