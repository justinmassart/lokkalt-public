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
        Schema::create('franchises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('verified_at')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('VAT');
            $table->string('bank_account')->nullable();
            $table->enum('country', ['BE', 'NL', 'FR', 'DE', 'LU']);
            $table->string('city');
            $table->string('postal_code');
            $table->string('address');
            $table->string('stripe_customer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchises');
    }
};
