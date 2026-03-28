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
        Schema::create('home_freshness_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id')->nullable()->index();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('text')->nullable();
            $table->enum('position', ['left', 'right'])->default('left')->index();
            $table->boolean('status')->default(true);
            $table->integer('serial_number')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_freshness_items');
    }
};
