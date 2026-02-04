<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_serial_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->string('batch_no');
            $table->string('serial_start');
            $table->string('serial_end');
            $table->integer('qty');
            $table->integer('sold_qty')->default(0);
            $table->timestamps();

            $table->unique(['variant_id', 'batch_no']);
            $table->index(['variant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_serial_batches');
    }
};
