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
    Schema::create('customer_order_returns', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_order_id')->constrained('customer_orders')->onDelete('cascade');
    $table->string('order_id'); 
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
    $table->unsignedBigInteger('product_id');
    $table->string('phone');
    $table->text('reason'); 
    $table->string('status')->default('Pending'); 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_order_returns');
    }
};
