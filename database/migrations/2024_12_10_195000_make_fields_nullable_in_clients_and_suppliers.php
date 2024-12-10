<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->enum('type', ['company', 'person'])->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('ice')->nullable()->change();
            $table->string('city')->nullable()->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('full_name')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->decimal('rating', 3, 1)->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->text('ice')->nullable()->change();
            $table->string('facebook_handle')->nullable()->change();
            $table->string('instagram_handle')->nullable()->change();
            $table->string('linkedin_handle')->nullable()->change();
            $table->string('twitter_handle')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->enum('type', ['company', 'person'])->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('ice')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('full_name')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->decimal('rating', 3, 1)->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->text('ice')->nullable(false)->change();
            $table->string('facebook_handle')->nullable(false)->change();
            $table->string('instagram_handle')->nullable(false)->change();
            $table->string('linkedin_handle')->nullable(false)->change();
            $table->string('twitter_handle')->nullable(false)->change();
        });
    }
}; 