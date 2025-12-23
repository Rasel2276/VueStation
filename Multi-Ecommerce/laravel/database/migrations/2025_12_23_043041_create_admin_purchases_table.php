<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->decimal('purchase_price',10,2);
            $table->decimal('vendor_sale_price',10,2)->default(0);
            $table->decimal('total',10,2);
            $table->enum('status',['Pending','Completed','Cancelled'])->default('Pending');
            $table->timestamp('purchase_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_purchases');
    }
};
