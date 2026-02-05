<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MailConfigController;
use App\Http\Controllers\Admin\MenuBuilderController;

Route::prefix('admin')->middleware(['auth:admin', 'AdminLangChange'])->group(function () {
    Route::post('/translate', 'Admin\LanguageController@translateText')->name('admin.translate');


    Route::get('/dashboard', 'Admin\AdminController@dashboard')->name('admin.dashboard');
    Route::get('/earning-search', 'Admin\AdminController@searchEarning')->name('admin.searchEarning');
    Route::get('/order-search', 'Admin\AdminController@searchOrders')->name('admin.searchOrders');
    Route::get('/edit-profile', 'Admin\AdminController@editProfile')->name('admin.editProfile');
    Route::post('/update-profile', 'Admin\AdminController@updateProfile')->name('admin.update_profile');
    Route::get('/change-password', 'Admin\AdminController@chnagePassword')->name('admin.chnage_password');
    Route::post('/update-password', 'Admin\AdminController@updatePassword')->name('admin.update_password');
    // admin logout route
    Route::get('/logout', 'Admin\AdminController@logout')->name('admin.logout');

    Route::prefix('menu-builder')->controller(MenuBuilderController::class)->group(function () {
        Route::get('/', 'index')->name('admin.menu_builder');
        Route::post('/store', 'store')->name('admin.menu_builder.update');
    });

    /*============================
      language managment route
     =============================*/
    Route::prefix('language-managment')
        ->controller(\App\Http\Controllers\Admin\LanguageController::class)
        ->group(function () {
            Route::get('/', 'index')->name('admin.language');
            Route::post('store', 'store')->name('admin.language.store');
            Route::post('update', 'update')->name('admin.language.update');

            Route::post('make-default/{id}', 'makeDefault')->name('admin.language.make_default');
            Route::post('make-dashboard-default/{id}', 'dashboardDefault')->name('admin.language.dashboardDefault');

            Route::post('add-keyword', 'addKeyword')->name('admin.language.add_keyword');
            Route::post('add-admin-keyword', 'addAdminKeyword')->name('admin.language.add_admin_keyword');

            Route::get('edit-keyword/{id}', 'editKeyword')->name('admin.language.edit_keyword');
            Route::get('edit-admin-keyword/{id}', 'AdminEditKeyword')->name('admin.language.edit_admin_keyword');

            Route::post('update-keyword/{id}', 'updateKeyword')->name('admin.language.update_keyword');

            Route::post('delete', 'delete')->name('admin.language.delete');
        });


    //admin role managment route
    Route::prefix('role-managment')->group(function () {
        //role & permission
        Route::get('/role-&-permission', 'Admin\AdminController@roleManagment')->name('admin.role_managment');
        Route::post('/create', 'Admin\AdminController@createRole')->name('admin.role_managment.create');
        Route::post('/update', 'Admin\AdminController@updateRole')->name('admin.role_managment.update');
        Route::get('/permission', 'Admin\AdminController@permission')->name('admin.role_managment.permission');
        Route::post('/delete', 'Admin\AdminController@deleteRole')->name('admin.role_managment.delete');

        //admins route
        Route::prefix('admins')->group(function () {
            Route::get('/', 'Admin\AdminController@allAdmin')->name('admin.all_admins');
            Route::post('/create', 'Admin\AdminController@createAdmin')->name('admin.all_admins.create');
            Route::post('/update', 'Admin\AdminController@updateAdmin')->name('admin.all_admins.update');
            Route::post('/delete', 'Admin\AdminController@deleteAdmin')->name('admin.all_admins.delete');
            Route::post('/bulk-delete', 'Admin\AdminController@bulkDeleteAdmin')->name('admin.all_admins.bulkdelete');
        });
    });

    /*============================
      home section route
     =============================*/
    Route::get('home-section', 'Admin\HomeSecController@index')->name('admin.home_section');
    Route::post('home-section/update', 'Admin\HomeSecController@update')->name('admin.home.section.update');

    /*============================
      user managment route
     =============================*/
    Route::prefix('user-managment')->controller(UserController::class)->group(function () {
        Route::get('/registered-users', 'index')->name('admin.user');
        Route::post('/store/user', 'store')->name('admin.user.store');
        Route::post('status/update/{id}', 'statusUpdate')->name('admin.user.update_status');
        Route::post('email-status/update/{id}', 'emailStatusUpdate')->name('admin.user.email_update_status');
        Route::post('delete', 'delete')->name('admin.user.delete');
        Route::post('/bulk-delete', 'bulkDelete')->name('admin.user.bulk_delete');
        Route::post('scret-login', 'secretLogin')->name('admin.secretAdminLogin');

        //password change
        Route::get('/password-change/{id}', 'passwordChange')->name('admin.user.password_change');
        Route::post('/update-password/{id}', 'updatePassword')->name('admin.user.update_password');
    });

    //vendor management route
    Route::prefix('vendor-managment')->group(function () {
        Route::get('settings', 'Admin\VendorController@setting')->name('admin.vendor.setting');
        Route::post('/update/settings', 'Admin\VendorController@settingUpdate')->name('admin.vendor.setting_update');
        Route::get('vendors', 'Admin\VendorController@index')->name('admin.vendor');
        Route::post('vendor/store', 'Admin\VendorController@store')->name('admin.vendor.store');
        Route::get('vendor/edit/{username}', 'Admin\VendorController@edit')->name('admin.vendor.edit');
        Route::post('vendor/update', 'Admin\VendorController@update')->name('admin.vendor.update');
        Route::post('/email-status-change', 'Admin\VendorController@changeEmailStatus')->name('admin.vendor.email_status_change');
        Route::post('/email-account-change', 'Admin\VendorController@changeAccountStatus')->name('admin.vendor.account_status_change');
        Route::post('vendor/delete', 'Admin\VendorController@delete')->name('admin.vendor.delete');
        Route::post('vendor/bulk-delete', 'Admin\VendorController@Bulkdelete')->name('admin.vendor.bulk_delete');
    });

    //shipping charge route
    Route::prefix('shipping-charge')->group(function () {
        Route::get('/', 'Admin\ShippingChargeController@index')->name('admin.shop.shipping_charge');
        Route::post('/store', 'Admin\ShippingChargeController@store')->name('admin.shop.shipping_charge_store');
        Route::get('/edit/{id}', 'Admin\ShippingChargeController@edit')->name('admin.shop.shipping_charge_edit');
        Route::post('/update', 'Admin\ShippingChargeController@update')->name('admin.shop.shipping_charge_update');
        Route::post('/delete', 'Admin\ShippingChargeController@delete')->name('admin.shop.shipping_charge_delete');
        Route::post('/bulk_delete', 'Admin\ShippingChargeController@bulkdelete')->name('admin.shop.shipping_charge_bulk_delete');
    });


    //user managment route
    // Route::prefix('user-management')->group(function () {
    //     Route::get('/', 'Admin\UserController@index')->name('admin.user.index');
    //     Route::get('/create', 'Admin\UserController@create')->name('admin.user.create');
    //     Route::post('/store', 'Admin\UserController@store')->name('admin.user.store');
    //     Route::get('/edit/{id}', 'Admin\UserController@edit')->name('admin.user.edit');
    //     Route::post('/update/{id}', 'Admin\UserController@update')->name('admin.user.update');
    //     Route::post('status/update/{id}', 'Admin\UserController@statusUpdate')->name('admin.user.update_status');
    //     Route::post('delete', 'Admin\UserController@delete')->name('admin.user.delete');
    //     Route::post('bulk-delete', 'Admin\UserController@bulkDelete')->name('admin.user.bulk_delete');
    // });
    //footer route
    Route::prefix('footer')->group(function () {
        Route::get('logo', 'Footer\FooterController@footerLogo')->name('admin.footer_logo');
        Route::post('update/logo', 'Footer\FooterController@footerLogoUpdate')->name('admin.footer_logo.update');
        Route::get('content', 'Footer\FooterController@footerContent')->name('admin.footer_content');
        Route::post('content/update', 'Footer\FooterController@footerContentUpdate')->name('admin.footer_content_update');
    });

    //settings route
    Route::prefix('settings')->group(function () {
        Route::get('/', 'Admin\SettingController@index')->name('admin.website_setting');
        Route::get('general-settings', 'Admin\SettingController@generalSetting')->name('admin.website_setting.general_setting');
        Route::post('general/update', 'Admin\SettingController@updateGeneralSetting')->name('admin.website_setting.update');
        Route::post('general/minor-update', 'Admin\SettingController@minorUpdateGeneralSetting')
            ->name('admin.website_setting.minor_update');

        //email settings
        Route::get('/email-settings', [MailConfigController::class, 'index'])
            ->name('admin.website_setting.config_email');
        Route::post('config/email', [MailConfigController::class, 'updateConfig'])
            ->name('admin.website_setting.config_email_store');


        Route::get('email-templates', 'Admin\MailTemplateController@index')->name('admin.website_setting.mail_template');
        Route::get('edit/{type}/email-template', 'Admin\MailTemplateController@edit')->name('admin.website_setting.mail_template.edit');
        Route::post('update/{type}/email-template', 'Admin\MailTemplateController@update')->name('admin.website_setting.mail_template.update');

        Route::get('page-heading', 'Admin\PageHeadingController@index')->name('admin.website_setting.page_heading');
        Route::get('seo', 'Admin\SettingController@seoInfo')->name('admin.seo_info');

        //maintenance mode routes
        Route::get('/maintenance-mode', 'Admin\SettingController@maintenance')->name('admin.maintenance');
        Route::post('/update/maintenance-mode', 'Admin\SettingController@maintenanceUpdate')->name('admin.maintenance_update');

        //payment gateway routes
        Route::get('payment-gateway', 'Admin\GatewayController@index')->name('admin.gateway');
        Route::get('online-gateway', 'Admin\GatewayController@online')->name('admin.online_gateway');
        Route::post('stripe/update', 'Admin\GatewayController@stripe')->name('admin.online_gateway.stripe');
        Route::post('paypal/update', 'Admin\GatewayController@paypal')->name('admin.online_gateway.paypal');
        Route::post('paytm/update', 'Admin\GatewayController@paytm')->name('admin.online_gateway.paytm');
        Route::post('instamojo/update', 'Admin\GatewayController@instamojo')->name('admin.online_gateway.instamojo');
        Route::get('offline-gateway', 'Admin\GatewayController@offline')->name('admin.offline_gateway');

        //plugins routes
        Route::get('/plugins', 'Admin\PluginController@index')->name('admin.plugin');
        Route::post('/plupusher_updategins', 'Admin\PluginController@pusher_update')->name('admin.plugin.pusher_update');
    });

    /*============================
      package managment route
     =============================*/
    Route::prefix('package-managment')->controller(PackageController::class)->group(function () {
        Route::get('/packages', 'index')->name('admin.package');
        Route::prefix('package')->group(function () {
            Route::post('/store', 'store')->name('admin.package.store');
            Route::get('/edit/{id}', 'edit')->name('admin.package.edit');
            Route::post('/update/{id}', 'update')->name('admin.package.update');
            Route::post('/changeStatus', 'changeStatus')->name('admin.package.status_change');
            Route::post('/delete', 'delete')->name('admin.package.delete');
            Route::post('bulk_delete', 'bulkDelete')->name('admin.package.bulk_delete');
        });
        // Settings routes
        Route::get('settings', 'setting')->name('admin.package.setting');
        Route::post('setting/update', 'settingUpdate')->name('admin.package.setting_update');
    });

    //blog managment route
    Route::prefix('blog-managment')->group(function () {
        //blog category route
        Route::prefix('categories')->group(function () {
            Route::get('/', 'Admin\Blog\CategoryController@index')->name('admin.blog.category');
            Route::post('store', 'Admin\Blog\CategoryController@store')->name('admin.blog.category.store');
            Route::post('update', 'Admin\Blog\CategoryController@update')->name('admin.blog.category.update');
            Route::post('/changeStatus', 'Admin\Blog\CategoryController@changeStatus')->name('admin.blog.category_status_change');
            Route::post('delete', 'Admin\Blog\CategoryController@delete')->name('admin.blog.category.delete');
            Route::post('bulk_delete', 'Admin\Blog\CategoryController@bulkDelete')->name('admin.blog.category.bulk_delete');
        });
        //blog route
        Route::prefix('blog')->group(function () {
            Route::get('/', 'Admin\Blog\BlogController@index')->name('admin.blog');
            Route::get('/create', 'Admin\Blog\BlogController@create')->name('admin.blog.create');
            Route::post('/store', 'Admin\Blog\BlogController@store')->name('admin.blog.store');
            Route::get('/edit/{id}', 'Admin\Blog\BlogController@edit')->name('admin.blog.edit');
            Route::post('/update/{id}', 'Admin\Blog\BlogController@update')->name('admin.blog.update');
            Route::post('/changeStatus', 'Admin\Blog\BlogController@changeStatus')->name('admin.blog.status_change');
            Route::post('/delete', 'Admin\Blog\BlogController@delete')->name('admin.blog.delete');
            Route::post('/bulk_delete', 'Admin\Blog\BlogController@bulkDelete')->name('admin.blog.bulk_delete');
        });
    });

    Route::prefix('product-management')->group(function () {
        Route::get('/settings', 'Admin\Product\SettingController@settings')->name('admin.product.settings');
        Route::post('/settings/update', 'Admin\Product\SettingController@settingsUpdate')->name('admin.product.settings_update');
        //product category route
        Route::prefix('category')->group(function () {
            Route::get('/', 'Admin\Product\CategoryController@index')->name('admin.product.category');
            Route::post('/store', 'Admin\Product\CategoryController@store')->name('admin.product.category_store');
            Route::post('/update', 'Admin\Product\CategoryController@update')->name('admin.product.category_update');
            Route::post('/changeStatus', 'Admin\Product\CategoryController@changeStatus')->name('admin.product.category_status_change');
            Route::post('/delete', 'Admin\Product\CategoryController@delete')->name('admin.product.category_delete');
            Route::post('/bulk_delete', 'Admin\Product\CategoryController@bulkdelete')->name('admin.product.category_bulk_delete');
        });
        //product route
        Route::prefix('products')->group(function () {
            Route::get('/', 'Admin\Product\ProductController@index')->name('admin.product');
            Route::get('/product-create', 'Admin\Product\ProductController@create')->name('admin.product.create');
            Route::get('/product-import', 'Admin\Product\ProductController@importForm')->name('admin.product.import_form');
            Route::post('/product-import', 'Admin\Product\ProductController@import')->name('admin.product.import');
            Route::get('/product-import-template', 'Admin\Product\ProductController@importTemplate')->name('admin.product.import_template');
            Route::get('/product-import-template-excel', 'Admin\Product\ProductController@importTemplateExcel')->name('admin.product.import_template_excel');

            Route::post('/slider', 'Admin\Product\ProductController@imagesstore')->name('admin.product.slider');
            Route::post('/slider/remove', 'Admin\Product\ProductController@imageremove')->name('admin.product.slider-remove');
            Route::post('/db/slider/remove', 'Admin\Product\ProductController@dbSliderRemove')->name('admin.product.db-slider-remove');

            Route::post('/product-store', 'Admin\Product\ProductController@store')->name('admin.product.store');
            Route::get('/product-edit/{id}', 'Admin\Product\ProductController@edit')->name('admin.product.edit');
            Route::post('/product-update/{id}', 'Admin\Product\ProductController@update')->name('admin.product.update');
            Route::post('/product-delete', 'Admin\Product\ProductController@delete')->name('admin.product.delete');
            Route::post('/product-bulk-delete', 'Admin\Product\ProductController@bulkDelete')->name('admin.product.bulk_delete');
            Route::post('/changeStatus', 'Admin\Product\ProductController@changeStatus')->name('admin.product.status_change');
        });

        Route::prefix('variants')->group(function () {
            Route::get('/restock', 'Admin\Product\VariantController@restockForm')->name('admin.product.variant.restock');
            Route::post('/restock', 'Admin\Product\VariantController@restock')->name('admin.product.variant.restock_store');
            Route::get('/{id}', 'Admin\Product\VariantController@show')->name('admin.product.variant.details');
        });

        //product coupon route
        Route::prefix('coupon')->group(function () {
            Route::get('/', 'Admin\Product\CouponController@index')->name('admin.product.coupon');
            Route::post('/store', 'Admin\Product\CouponController@store')->name('admin.product.coupon_store');
            Route::post('/update', 'Admin\Product\CouponController@update')->name('admin.product.coupon_update');
            Route::post('/delete', 'Admin\Product\CouponController@delete')->name('admin.product.coupon_delete');
            Route::post('/bulk_delete', 'Admin\Product\CouponController@bulkdelete')->name('admin.product.coupon_bulk_delete');
        });
    });

    Route::prefix('sales-management')->group(function () {
        Route::get('all-sales', 'Admin\SalesController@index')->name('admin.sales');
        Route::get('/exportReport', 'Admin\SalesController@exportReport')->name('admin.sales.exportReport');
        Route::get('/exportReportExcel', 'Admin\SalesController@exportReportExcel')->name('admin.sales.exportReportExcel');
        Route::post('sales/delete', 'Admin\SalesController@delete')->name('admin.sales.delete');
        Route::post('sales/bulkDelete', 'Admin\SalesController@bulkDelete')->name('admin.sales.bulkDelete');
        Route::get('sale/details/{id}', 'Admin\SalesController@details')->name('admin.sales.details');
        Route::get('repors', 'Admin\SalesController@report')->name('admin.sales.reports');
    });

    //transaction route
    Route::get('transaction', 'Admin\TransactionController@index')->name('admin.transaction');

    //inventory
    Route::prefix('inventory-management')->group(function () {
        Route::get('/stock-overview', 'Admin\InventoryController@stockOverview')->name('admin.inventory_managmement.stock_overview');
        Route::get('/exportReport', 'Admin\InventoryController@exportReport')->name('admin.inventory_managmement.exportReport');
        Route::get('/exportReportExcel', 'Admin\InventoryController@exportReportExcel')->name('admin.inventory_managmement.exportReportExcel');
        Route::get('/update-stock', 'Admin\InventoryController@updateList')->name('admin.inventory_managmement.update_stock');
        Route::post('/update-stock-submit', 'Admin\InventoryController@updateStock')->name('admin.inventory_managmement.update_stock_submit');
    });
});
