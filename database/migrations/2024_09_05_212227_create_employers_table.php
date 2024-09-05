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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('city');
            $table->string('cine');
            $table->string('address');
            $table->string('type');
            $table->decimal('wage', 10, 2);
            $table->foreignId('profession_id')->constrained('professions')->onDelete('cascade');
            $table->string('cnss');
            $table->decimal('wage_per_hr', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};