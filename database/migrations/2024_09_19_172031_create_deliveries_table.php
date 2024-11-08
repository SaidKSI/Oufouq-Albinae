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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->date('date');
            $table->decimal('total_without_tax', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_with_tax', 10, 2);
            $table->string('doc')->nullable();
            $table->text('note')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};