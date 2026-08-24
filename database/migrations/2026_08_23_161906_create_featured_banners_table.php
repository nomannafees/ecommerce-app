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
        Schema::create('featured_banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // Banner title/name
            $table->text('description')->nullable();       // Banner description
            $table->string('button_name')->nullable();     // Button text (e.g., Shop Now)
            $table->string('image');                     // Banner image path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_banners');
    }
};
