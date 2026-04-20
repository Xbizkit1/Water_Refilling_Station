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
        // THIS IS WHERE YOU PASTE THE CODE
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('customer_id');
            $table->integer('driver_id')->nullable();
            $table->enum('order_type', ['new_gallon', 'swap_gallon', 'refill_gallon']);
            $table->enum('status', ['pending', 'assigned', 'picked_up', 'delivered']);
            $table->string('payment_method')->default('COD');
            $table->integer('quantity')->default(1);
            $table->decimal('total_amount', 8, 2);
            $table->text('address');
            $table->string('proof_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};