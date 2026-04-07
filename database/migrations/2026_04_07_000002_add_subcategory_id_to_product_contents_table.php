<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('product_contents')) {
            return;
        }

        Schema::table('product_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('product_contents', 'subcategory_id')) {
                $table->unsignedBigInteger('subcategory_id')->nullable()->after('category_id');
                $table->index('subcategory_id');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('product_contents')) {
            return;
        }

        Schema::table('product_contents', function (Blueprint $table) {
            if (Schema::hasColumn('product_contents', 'subcategory_id')) {
                $table->dropIndex(['subcategory_id']);
                $table->dropColumn('subcategory_id');
            }
        });
    }
};
