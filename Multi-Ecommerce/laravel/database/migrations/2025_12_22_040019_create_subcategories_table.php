<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_category_id');
            $table->string('subcategory_name', 100);
            $table->string('slug', 120)->nullable();
            $table->text('description')->nullable();
            $table->string('subcategory_image')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            // Foreign Key
            $table->foreign('parent_category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
