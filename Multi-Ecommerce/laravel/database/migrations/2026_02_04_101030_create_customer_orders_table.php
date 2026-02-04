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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            
            // 1. Order Tracking ID (Customer tracking korar jonno)
            $table->string('order_id'); // Example: ORD-5521
            
            // 2. Product Details
            $table->unsignedBigInteger('product_id'); 
            $table->string('product_name');
            $table->string('image')->nullable(); 
            $table->integer('qty');
            $table->decimal('price', 12, 2); // Total price for this specific product
            
            // 3. Customer & Shipping Info
            $table->string('customer_name');
            $table->string('phone');
            $table->string('thana');
            $table->string('area');
            $table->text('address'); // House/Road/Flat details
            
            // 4. Vendor ID (Ei ID diyei order-ta Vendor-er dashboard-e jabe)
            // constrained('users') mane holo apnar vendor-ra users table-e ache
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            
            // 5. Payment & Status Info
            $table->string('payment_method'); // COD, bKash etc.
            $table->string('status')->default('Pending'); // Initial status hobe Pending
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};