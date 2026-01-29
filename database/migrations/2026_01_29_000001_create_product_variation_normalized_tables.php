<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variation_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('product_variation_group_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_group_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name')->nullable();
            $table->timestamps();

            $table->unique(['variation_group_id', 'language_id'], 'pvg_group_language_unique');
        });

        Schema::create('product_variation_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_group_id');
            $table->unsignedInteger('position')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->index(['variation_group_id', 'position']);
        });

        Schema::create('product_variation_option_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_option_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name')->nullable();
            $table->timestamps();

            $table->unique(['variation_option_id', 'language_id'], 'pvo_option_language_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variation_option_translations');
        Schema::dropIfExists('product_variation_options');
        Schema::dropIfExists('product_variation_group_translations');
        Schema::dropIfExists('product_variation_groups');
    }
};
