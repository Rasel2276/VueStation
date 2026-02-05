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
        $table->string('order_id'); // Tracking ID
        
        // কাস্টমার আইডি (এটিই মেইন পরিবর্তন)
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

        // প্রোডাক্ট ডিটেইলস
        $table->unsignedBigInteger('product_id'); 
        $table->string('product_name');
        $table->string('image')->nullable(); 
        $table->integer('qty');
        $table->decimal('price', 12, 2); 
        
        // শিপিং ইনফো
        $table->string('customer_name');
        $table->string('phone');
        $table->string('thana');
        $table->string('area');
        $table->text('address'); 
        
        // ভেন্ডর আইডি
        $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
        
        // পেমেন্ট ও স্ট্যাটাস
        $table->string('payment_method'); 
        $table->string('status')->default('Pending'); 
        
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
