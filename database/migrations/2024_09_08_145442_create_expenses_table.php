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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ref');
            $table->enum('type', ['fix', 'variable']);
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->integer('repeat_interval')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};