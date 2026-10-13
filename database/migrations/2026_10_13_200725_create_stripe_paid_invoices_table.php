
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_paid_invoices', function (Blueprint $table) {
            $table->id();

            $table->string('stripe_invoice_id')->unique();

            $table->string('stripe_subscription_id');

            $table->foreignId('subscription_id')
                ->constrained('subscriptions')
                ->restrictOnDelete();

            $table->unsignedInteger('amount_paid_cents');

            // DATETIME avoids MySQL TIMESTAMP default restrictions.
            $table->dateTime('period_start');
            $table->dateTime('period_end');

            $table->timestamps();

            $table->index('stripe_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_paid_invoices');
    }
};
