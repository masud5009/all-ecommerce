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
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'facebook_pixel_status')) {
                $table->boolean('facebook_pixel_status')->default(0);
            }

            if (!Schema::hasColumn('settings', 'facebook_pixel_id')) {
                $table->string('facebook_pixel_id')->nullable();
            }

            if (!Schema::hasColumn('settings', 'google_recaptcha_status')) {
                $table->boolean('google_recaptcha_status')->default(0);
            }

            if (!Schema::hasColumn('settings', 'google_recaptcha_site_key')) {
                $table->string('google_recaptcha_site_key')->nullable();
            }

            if (!Schema::hasColumn('settings', 'google_recaptcha_secret_key')) {
                $table->string('google_recaptcha_secret_key')->nullable();
            }

            if (!Schema::hasColumn('settings', 'google_analytics_status')) {
                $table->boolean('google_analytics_status')->default(0);
            }

            if (!Schema::hasColumn('settings', 'google_analytics_measurement_id')) {
                $table->string('google_analytics_measurement_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $columns = [
                'facebook_pixel_status',
                'facebook_pixel_id',
                'google_recaptcha_status',
                'google_recaptcha_site_key',
                'google_recaptcha_secret_key',
                'google_analytics_status',
                'google_analytics_measurement_id',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
