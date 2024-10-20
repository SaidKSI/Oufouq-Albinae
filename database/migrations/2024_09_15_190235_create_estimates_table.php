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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->string('number');
            $table->enum('type', ['invoice', 'estimate']);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('reference')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('tax')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};