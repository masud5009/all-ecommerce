<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'stedfast_api_key')) {
                $table->string('stedfast_api_key')->nullable()->after('pusher_app_cluster');
            }
            if (!Schema::hasColumn('settings', 'stedfast_secret_key')) {
                $table->string('stedfast_secret_key')->nullable()->after('stedfast_api_key');
            }
            if (!Schema::hasColumn('settings', 'stedfast_status')) {
                $table->tinyInteger('stedfast_status')->default(0)->after('stedfast_secret_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'stedfast_status')) {
                $table->dropColumn('stedfast_status');
            }
            if (Schema::hasColumn('settings', 'stedfast_secret_key')) {
                $table->dropColumn('stedfast_secret_key');
            }
            if (Schema::hasColumn('settings', 'stedfast_api_key')) {
                $table->dropColumn('stedfast_api_key');
            }
        });
    }
};
