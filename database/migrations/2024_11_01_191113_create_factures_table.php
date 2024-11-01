<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimate_id')->constrained()->onDelete('cascade');
            $table->string('number')->unique();
            $table->date('date');
            $table->enum('payment_method', ['bank_transfer', 'cheque', 'credit', 'cash', 'traita']);
            $table->string('transaction_id')->nullable();
            $table->decimal('total_without_tax', 10, 2);
            $table->decimal('tax', 5, 2);
            $table->decimal('total_with_tax', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factures');
    }
};