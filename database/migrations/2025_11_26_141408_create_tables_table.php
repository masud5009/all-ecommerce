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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('language_id');
            $table->string('table_number')->unique();
            $table->integer('capacity')->default(1);
            $table->enum('status', ['available', 'occupied', 'reserved', 'cleaning', 'unavailable'])
                ->default('available');
            $table->string('qr_code')->nullable();
            $table->integer('serial_number')->default(1);
            $table->string('color')->default('#000000');
            $table->integer('size')->default(300);
            $table->integer('margin')->default(1);
            $table->string('style')->default('square');
            $table->string('eye_style')->default('square');
            $table->string('type')->default('default');
            $table->string('image')->nullable();
            $table->integer('image_size')->default(10);
            $table->integer('image_x')->default(50);
            $table->integer('image_y')->default(50);
            $table->string('text')->nullable();
            $table->string('text_color')->default('#000000');
            $table->integer('text_size')->default(5);
            $table->integer('text_x')->default(50);
            $table->integer('text_y')->default(50);
            $table->string('qr_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
