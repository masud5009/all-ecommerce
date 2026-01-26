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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('website_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('footer_logo')->nullable();
            $table->string('website_title')->nullable();
            $table->string('website_color')->nullable();
            $table->string('timezone')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('currency_symbol_position')->nullable();
            $table->string('currency_text')->nullable();
            $table->string('currency_text_position')->nullable();
            $table->string('currency_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
