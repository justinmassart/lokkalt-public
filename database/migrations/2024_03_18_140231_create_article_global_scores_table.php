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
        Schema::create('article_global_scores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('score', 3, 2)->nullable();
            $table->integer('total_votes')->nullable();
            $table->timestamps();

            $table->index(['score', 'total_votes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_global_scores');
    }
};
