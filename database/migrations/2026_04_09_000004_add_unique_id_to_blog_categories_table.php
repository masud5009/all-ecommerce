<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('unique_id')->nullable()->after('language_id');
            $table->index('unique_id');
        });

        DB::table('categories')
            ->whereNull('unique_id')
            ->orderBy('id')
            ->select('id')
            ->chunkById(100, function ($categories) {
                foreach ($categories as $category) {
                    DB::table('categories')
                        ->where('id', $category->id)
                        ->update(['unique_id' => 'bc_' . $category->id]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_unique_id_index');
            $table->dropColumn('unique_id');
        });
    }
};
