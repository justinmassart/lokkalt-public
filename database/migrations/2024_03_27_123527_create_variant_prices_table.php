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
        Schema::create('variant_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('price', 6, 2);
            $table->enum('currency', ['EUR', 'USD', 'GBP']);
            $table->string('per');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_prices');
    }
};
