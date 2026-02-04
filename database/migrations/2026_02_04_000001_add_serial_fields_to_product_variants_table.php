<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->boolean('track_serial')->default(0)->after('status');
            $table->string('serial_start')->nullable()->after('track_serial');
            $table->string('serial_end')->nullable()->after('serial_start');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['track_serial', 'serial_start', 'serial_end']);
        });
    }
};
