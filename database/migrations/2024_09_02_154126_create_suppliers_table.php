<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('city');
            $table->string('address');
            $table->decimal('rating', 3, 1)->default(0)->nullable();
            $table->text('description')->nullable();
            $table->text('ice')->nullable();
            $table->timestamps();

            // Social media attributes
            $table->string('facebook_handle')->nullable();
            $table->string('instagram_handle')->nullable();
            $table->string('linkedin_handle')->nullable();
            $table->string('twitter_handle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};