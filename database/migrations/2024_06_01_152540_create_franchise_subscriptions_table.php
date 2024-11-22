<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('franchise_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('has_paid')->default(false);
            $table->string('customer_id');
            $table->string('subscription_id');
            $table->string('payment_id');
            $table->string('stripe_status');
            $table->float('stripe_price');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_subscriptions');
    }
};
