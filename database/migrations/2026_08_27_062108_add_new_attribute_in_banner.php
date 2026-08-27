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
        Schema::table('sliders', function (Blueprint $table) {
            $table->tinyInteger('is_image')->default(1);
            $table->tinyInteger('is_title')->default(1);
            $table->tinyInteger('is_description')->default(1);
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->tinyInteger('is_image')->default(1);
            $table->tinyInteger('is_title')->default(1);
            $table->tinyInteger('is_description')->default(1);
        });

        Schema::table('brands_banners', function (Blueprint $table) {
            $table->tinyInteger('is_image')->default(1);
            $table->tinyInteger('is_title')->default(1);
            $table->tinyInteger('is_description')->default(1);
            $table->tinyInteger('is_button')->default(1);
    });

        Schema::table('featured_banners', function (Blueprint $table) {
            $table->tinyInteger('is_image')->default(1);
            $table->tinyInteger('is_title')->default(1);
            $table->tinyInteger('is_description')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            //
        });
    }
};
