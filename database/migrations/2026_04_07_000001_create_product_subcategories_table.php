<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('product_subcategories')) {
            return;
        }

        Schema::create('product_subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->integer('serial_number')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->index('category_id');
            $table->index('language_id');
            $table->index(['language_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_subcategories');
    }
};
