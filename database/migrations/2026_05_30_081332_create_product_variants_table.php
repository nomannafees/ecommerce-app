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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_image_id')->nullable()->constrained()->nullOnDelete();

            // Variant Details
            $table->string('color_name')->nullable();
            $table->string('size_system')->nullable(); // US, UK, EU
            $table->string('size')->nullable();        // M, XL, 42

            // Inventory & Pricing
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
