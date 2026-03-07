<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_gateways')) {
            return;
        }

        DB::table('payment_gateways')->updateOrInsert(
            ['keyword' => 'sslcommerz'],
            [
                'name' => 'SSLCommerz',
                'type' => 'automatic',
                'information' => json_encode([
                    'mode' => 'sandbox',
                    'store_id' => '',
                    'store_password' => '',
                    'currency' => 'BDT',
                    'text' => 'Pay securely via SSLCommerz.',
                ]),
                'status' => 0,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('payment_gateways')) {
            return;
        }

        DB::table('payment_gateways')
            ->where('keyword', 'sslcommerz')
            ->delete();
    }
};
