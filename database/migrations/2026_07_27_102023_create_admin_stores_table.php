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
        Schema::create('admin_stores', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable(); // Logo image path
            $table->string('title');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Checks / Flags (Default true/1 rakh rahe hain)
            $table->boolean('is_logo')->default(true);
            $table->boolean('is_title')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_stores');
    }
};
