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
        Schema::create('shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('is_active')->default(true);
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->enum('country', ['BE', 'NL', 'FR', 'DE', 'LU']);
            $table->string('city');
            $table->integer('postal_code');
            $table->string('address');
            $table->string('VAT')->nullable();
            $table->string('bank_account');
            $table->json('opening_hours')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index('name');
            $table->index('slug');
            $table->index('email');
            $table->index('phone');
            $table->index('VAT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
