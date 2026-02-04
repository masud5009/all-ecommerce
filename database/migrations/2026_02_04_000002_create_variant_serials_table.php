<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_serials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->string('serial');
            $table->string('status')->default('in_stock');
            $table->timestamps();

            $table->index(['variant_id', 'status']);
            $table->unique(['variant_id', 'serial']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_serials');
    }
};
