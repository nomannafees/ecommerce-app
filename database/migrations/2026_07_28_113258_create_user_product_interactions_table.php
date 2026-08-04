<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_product_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Nullable for guests
            $table->string('session_id')->nullable(); // Guest users ko track karne ke liye
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable(); // Recommendation ke liye helpful hai
            $table->foreignId('brand_id')->nullable();    // Brand preference track karne ke liye
            $table->integer('weight')->default(1);        // Click par 1, Add to cart par 3, Purchase par 5
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_product_interactions');
    }
};
