<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'flash_sale_status')) {
                $table->tinyInteger('flash_sale_status')->default(0)->after('previous_price');
            }

            if (!Schema::hasColumn('products', 'flash_sale_price')) {
                $table->decimal('flash_sale_price', 8, 2)->nullable()->after('flash_sale_status');
            }

            if (!Schema::hasColumn('products', 'flash_sale_start_at')) {
                $table->timestamp('flash_sale_start_at')->nullable()->after('flash_sale_price');
            }

            if (!Schema::hasColumn('products', 'flash_sale_end_at')) {
                $table->timestamp('flash_sale_end_at')->nullable()->after('flash_sale_start_at');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('products', 'flash_sale_end_at')) {
                $dropColumns[] = 'flash_sale_end_at';
            }

            if (Schema::hasColumn('products', 'flash_sale_start_at')) {
                $dropColumns[] = 'flash_sale_start_at';
            }

            if (Schema::hasColumn('products', 'flash_sale_price')) {
                $dropColumns[] = 'flash_sale_price';
            }

            if (Schema::hasColumn('products', 'flash_sale_status')) {
                $dropColumns[] = 'flash_sale_status';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
