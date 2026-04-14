<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_variants') || Schema::hasColumn('product_variants', 'show_on_card_price')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->boolean('show_on_card_price')
                ->default(false)
                ->after('status');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('product_variants') || !Schema::hasColumn('product_variants', 'show_on_card_price')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('show_on_card_price');
        });
    }
};
