<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users');
            $table->foreignId('admin_stock_id')->constrained('admin_stock');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2)->storedAs('quantity * price');
            $table->enum('status', ['Pending','Completed','Cancelled'])->default('Pending');
            $table->timestamp('purchase_date')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_purchases');
    }
};
