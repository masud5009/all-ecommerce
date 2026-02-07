<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'stedfast_consignment_id')) {
                $table->string('stedfast_consignment_id')->nullable()->after('delivery_date');
            }
            if (!Schema::hasColumn('orders', 'stedfast_tracking_code')) {
                $table->string('stedfast_tracking_code')->nullable()->after('stedfast_consignment_id');
            }
            if (!Schema::hasColumn('orders', 'stedfast_status')) {
                $table->string('stedfast_status')->nullable()->after('stedfast_tracking_code');
            }
            if (!Schema::hasColumn('orders', 'stedfast_message')) {
                $table->string('stedfast_message')->nullable()->after('stedfast_status');
            }
            if (!Schema::hasColumn('orders', 'stedfast_payload')) {
                $table->longText('stedfast_payload')->nullable()->after('stedfast_message');
            }
            if (!Schema::hasColumn('orders', 'stedfast_response')) {
                $table->longText('stedfast_response')->nullable()->after('stedfast_payload');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'stedfast_response')) {
                $table->dropColumn('stedfast_response');
            }
            if (Schema::hasColumn('orders', 'stedfast_payload')) {
                $table->dropColumn('stedfast_payload');
            }
            if (Schema::hasColumn('orders', 'stedfast_message')) {
                $table->dropColumn('stedfast_message');
            }
            if (Schema::hasColumn('orders', 'stedfast_status')) {
                $table->dropColumn('stedfast_status');
            }
            if (Schema::hasColumn('orders', 'stedfast_tracking_code')) {
                $table->dropColumn('stedfast_tracking_code');
            }
            if (Schema::hasColumn('orders', 'stedfast_consignment_id')) {
                $table->dropColumn('stedfast_consignment_id');
            }
        });
    }
};
