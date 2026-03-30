<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Controllers\FrontEnd\CartController;
use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\FrontEnd\ShopController;
use App\Http\Controllers\FrontEnd\UserController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Controllers\FrontEnd\CheckoutController;
use Illuminate\View\Middleware\ShareErrorsFromSession;
/*
|--------------------------------------------------------------------------
| frontend route
|--------------------------------------------------------------------------
*/

Route::prefix('/admin')->middleware('guest:admin')->group(function () {
    // admin redirect to login page route
    Route::get('/', 'Admin\AdminController@login')->name('admin.login');
    // admin login attempt route
    Route::post('/auth', 'Admin\AdminController@authentication')->name('admin.auth');
    // admin forget password route
    Route::get('/forget-password', 'Admin\AdminController@forgetPassword')->name('admin.forget_password');
});

/*============================ Home Page Routes ============================*/
Route::prefix('/')->controller(HomeController::class)->group(function () {
    Route::get('', 'index')->name('frontend.index');
    Route::get('/contact', 'contact')->name('frontend.contact');
    Route::get('/blog', 'blog')->name('frontend.blog');
    Route::get('/about', 'about')->name('frontend.about');
    Route::get('/change-language/{code}', 'changeLanguage')->name('frontend.change_language');
});

/*============================
      shop management routes
     =============================*/
Route::get('/shop', [ShopController::class, 'shop'])->name('frontend.shop');
Route::get('/shop/details/{id}', [ShopController::class, 'details'])->name('frontend.shop.details');
Route::get('/shop/quick-view/{id}', [ShopController::class, 'quickView'])->name('frontend.shop.quickview');
Route::post('/shop/details/{id}/review', [ShopController::class, 'storeReview'])
    ->middleware('auth:web')
    ->name('frontend.shop.review.store');

/*============================
      cart management routes
     =============================*/
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('cart.index');
    Route::post('/add', 'add')->name('cart.add');
    Route::post('/update', 'update')->name('cart.update');
    Route::post('/remove', 'remove')->name('cart.remove');
    Route::post('/clear', 'clear')->name('cart.clear');
    Route::get('/checkout', 'checkout')->name('cart.checkout');
    Route::post('/place-order', 'placeOrder')->name('cart.place.order');
    Route::match(['get', 'post'], '/payment/success', 'paymentSuccess')
        ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class])
        ->name('cart.payment.success');
    Route::match(['get', 'post'], '/payment/cancel', 'paymentCancel')
        ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class])
        ->name('cart.payment.cancel');
    Route::get('/order-success/{order}', 'orderSuccess')->name('cart.order.success');
});


/*============================
      membership purchase routes
     =============================*/
Route::controller(CheckoutController::class)->group(function () {
    Route::get('/pricing', 'pricing')->name('frontend.pricing');
    Route::get('/registration/{id}', 'registrationPage')->name('frontend.register.view');
    Route::post('/check-registration', 'checkRegistration')->name('frontend.register.validate_check');
    Route::get('/checkout', 'checkoutPage')->name('frontend.checkout.view');
    Route::post('/checkout-submit', 'checkoutSubmit')->name('frontend.checkout.submit');
    Route::match(['get', 'post'], '/membership-payment/cancel', 'paymentCancel')->name('frontend.checkout.payment_cancel');
    Route::match(['get', 'post'], '/membership-payment/success', 'paymentSuccess')->name('frontend.checkout.payment_success');

    Route::get('/payment-success', 'successPage')->name('frontend.payment_success.view');
    Route::get('/payment-cancel', 'cancelPage')->name('frontend.payment_cancel.view');
});
/*============================
      user auth route
     =============================*/
Route::prefix('user')->middleware('guest:web')->controller(UserController::class)->group(function () {
    // Signup
    Route::get('/signup', 'signup')->name('user.signup');
    Route::post('/store/user', 'signup_submit')->name('user.signup_submit');
    Route::get('/register/mode/verify/{token}', 'verifyEmail')
        ->name('user.signup_verify')
        ->middleware('throttle:6,1');

    // Login
    Route::get('/login', 'login')->name('user.login');
    Route::post('/login/submit', 'loginSubmit')->name('user.login_submit');

    // Forgot password
    Route::get('/forget-password', 'forgetPassword')->name('user.forget_password');
    Route::get('/reset-password', 'resetPassword')->name('user.reset_password_form');
    Route::post('/forget-password/send', 'forgetNow')->name('user.forget_password.send_email');
    Route::post('/reset-password/submit', 'resetPasswordSubmit')->name('user.reset_password');
});

Route::prefix('user')->middleware('auth:web')->controller(UserController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('user.dashboard');
    Route::get('/orders', 'orders')->name('user.orders');
    Route::get('/orders/{id}', 'orderDetails')->name('user.order.details');
    Route::get('/wishlist', 'wishlist')->name('user.wishlist');
    Route::get('/edit-profile', 'editProfile')->name('user.edit_profile');
    Route::post('/edit-profile', 'updateProfile')->name('user.update_profile');
    Route::get('/change-password', 'changePassword')->name('user.change_password');
    Route::post('/change-password', 'updatePassword')->name('user.update_password');
    Route::get('/logout', 'logout')->name('user.logout');
});

Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/shop/product/{id}', [ShopController::class, 'details'])->name('shop.details');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Order
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/cart/place-order', [CartController::class, 'placeOrder'])->name('cart.place.order');
    Route::get('/order-confirmation/{id}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist')->middleware('auth:web');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
