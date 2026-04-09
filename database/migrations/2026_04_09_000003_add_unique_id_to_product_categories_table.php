<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('product_categories')) {
            return;
        }

        if (!Schema::hasColumn('product_categories', 'unique_id')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $table->string('unique_id')->nullable()->after('language_id');
                $table->index('unique_id');
            });
        }

        DB::table('product_categories')
            ->whereNull('unique_id')
            ->orderBy('id')
            ->select('id')
            ->chunkById(100, function ($categories) {
                foreach ($categories as $category) {
                    DB::table('product_categories')
                        ->where('id', $category->id)
                        ->update(['unique_id' => 'pc_' . $category->id]);
                }
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('product_categories') || !Schema::hasColumn('product_categories', 'unique_id')) {
            return;
        }

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropIndex('product_categories_unique_id_index');
            $table->dropColumn('unique_id');
        });
    }
};
