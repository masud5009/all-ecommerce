<?php

use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PosController;
use App\Http\Controllers\User\Product\CategoryController;
use App\Http\Controllers\User\Product\ProductController;
use App\Http\Controllers\User\QrMenuController;
use App\Http\Controllers\User\Product\VariantController;
use App\Http\Controllers\User\TableController;
use Illuminate\Support\Facades\Route;

$domain = env('WEBSITE_HOST');
if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}

Route::domain($domain)->prefix('user')->middleware('auth:web')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('user.website_setting');
        Route::post('general/minor-update', [SettingController::class, 'minorUpdateGeneralSetting'])
            ->name('user.settings.minor_update');
    });

    Route::prefix('product-management')->group(function () {
        /**
         * product category route
         */
        Route::prefix('category')->controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->name('user.product.category');
            Route::post('/store', 'store')->name('user.product.category_store');
            Route::post('/update', 'update')->name('user.product.category_update');
            Route::post('/changeStatus', 'changeStatus')->name('user.product.category_status_change');
            Route::post('/delete', 'delete')->name('user.product.category_delete');
            Route::post('/bulk_delete', 'bulkdelete')->name('user.product.category_bulk_delete');
        });
        /**
         * product management route
         */
        Route::prefix('products')->group(function () {

            Route::controller(ProductController::class)->group(function () {
                Route::get('/', 'index')->name('user.product');
                Route::get('/product-create', 'create')->name('user.product.create');

                Route::post('/slider', 'imagesstore')->name('user.product.slider');
                Route::post('/slider/remove', 'imageremove')->name('user.product.slider-remove');
                Route::post('/db/slider/remove', 'dbSliderRemove')->name('user.product.db-slider-remove');

                Route::post('/product-store', 'store')->name('user.product.store');
                Route::get('/product-edit/{id}', 'edit')->name('user.product.edit');
                Route::post('/product-update/{id}', 'update')->name('user.product.update');
                Route::post('/changeStatus', 'changeStatus')->name('user.product.status_change');

                Route::post('/delete', 'delete')->name('user.product.product_delete');
                Route::post('/bulk_delete', 'bulkdelete')->name('user.product.product_bulk_delete');
            });


            Route::prefix('variants')->controller(VariantController::class)->group(function () {
                Route::get('/{product_id}', 'index')->name('user.product.variant');
                Route::post('/store', 'store')->name('user.product.variant_store');
            });
        });
    });

    Route::prefix('tables')->controller(TableController::class)->group(function () {
        Route::get('/', 'index')->name('user.tables');
        Route::post('/store', 'store')->name('user.tables.store');
        Route::post('/update', 'update')->name('user.tables.update');
        Route::post('/delete', 'delete')->name('user.tables.delete');
        Route::post('/bulk_delete', 'bulkdelete')->name('user.tables.bulk_delete');
        Route::post('status/update', 'statusUpdate')->name('user.tables.update_status');
        Route::get('/qr-builder/{id}', 'qrBuilder')->name('user.tables.qr_code');
        Route::post('/generate-qr', 'generateQr')->name('user.tables.generate_qr');
        Route::get('/table/{id}', 'show')->name('table.show');
    });
    /**
     * pos route
     */
    Route::prefix('pos')->controller(PosController::class)->group(function () {
        Route::get('/', 'index')->name('user.pos');
    });
});
