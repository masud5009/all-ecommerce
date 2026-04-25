<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_settings')) {
            return;
        }

        Schema::table('product_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('product_settings', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('physical_product');
            }

            if (!Schema::hasColumn('product_settings', 'contact_number_status')) {
                $table->boolean('contact_number_status')->default(0)->after('contact_number');
            }

            if (!Schema::hasColumn('product_settings', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('contact_number_status');
            }

            if (!Schema::hasColumn('product_settings', 'whatsapp_number_status')) {
                $table->boolean('whatsapp_number_status')->default(0)->after('whatsapp_number');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('product_settings')) {
            return;
        }

        Schema::table('product_settings', function (Blueprint $table) {
            $columns = [
                'contact_number',
                'contact_number_status',
                'whatsapp_number',
                'whatsapp_number_status',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('product_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
