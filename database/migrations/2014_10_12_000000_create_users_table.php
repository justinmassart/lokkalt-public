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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('role', ['admin', 'moderator', 'user', 'seller', 'employee']);
            $table->string('firstname');
            $table->string('lastname');
            $table->string('full_name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('country', ['BE', 'FR', 'DE', 'NL', 'LU']);
            $table->string('phone')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->index(['slug', 'email', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
