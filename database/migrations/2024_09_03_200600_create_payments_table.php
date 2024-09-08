<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employers')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->string('payment_id');
            $table->decimal('paid_price', 10, 2);
            $table->decimal('remaining', 10, 2);
            $table->string('payment_method');
            $table->date('date');
            $table->timestamps();
            $table->enum('type', ['order', 'employer']);
            // Add a unique constraint to ensure either employee_id or order_id must exist
            $table->unique(['employee_id', 'order_id'], 'unique_employee_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}