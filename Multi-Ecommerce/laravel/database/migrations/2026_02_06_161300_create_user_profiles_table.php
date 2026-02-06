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
    Schema::create('user_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('full_name')->nullable();
        $table->string('phone')->nullable();
        $table->string('shop_name')->nullable(); // শুধুমাত্র ভেন্ডরের জন্য
        $table->string('thana_upazila')->nullable();
        $table->string('area_village')->nullable();
        $table->string('home_road_apartment')->nullable();
        $table->string('profile_picture')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
