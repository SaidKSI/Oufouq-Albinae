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
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id');
            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
            $table->string('ref');
            $table->string('name');
            $table->integer('qte');
            $table->decimal('prix_unite', 10, 2);
            $table->string('category');
            $table->decimal('total_price_unite', 10, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};