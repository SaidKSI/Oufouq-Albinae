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
        Schema::create('transportation_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('product')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('ref')->nullable();
            $table->decimal('highway_expense', 10, 2)->nullable();
            $table->decimal('gaz_expense', 10, 2)->nullable();
            $table->decimal('other_expense', 10, 2)->nullable();
            $table->decimal('total_expense', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportation_expenses');
    }
};