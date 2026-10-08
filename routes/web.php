<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\UserOrderController;

// =============================
// LANDING PAGE
// =============================

Route::get('/', function () {
    return view('home');
})->name('home');


// =============================
// GUEST
// =============================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');


    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');

});


// =============================
// USER YANG SUDAH LOGIN
// =============================

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->name('dashboard');
    
    Route::get('/product/detail', [UserProductController::class, 'show'])
    ->name('product.detail');

    Route::get('/order', [UserOrderController::class, 'create'])
    ->name('order');

    Route::post('/order', [UserOrderController::class, 'store'])
    ->name('order.store');

    Route::get('/my-orders', [UserOrderController::class, 'index'])
    ->name('orders.index');

    // 👇 TAMBAHAN ROUTE HALAMAN INVOICE DI SINI
    Route::get('/invoice/{order}', [UserOrderController::class, 'invoice'])
    ->whereNumber('order')
    ->name('invoice');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::patch('/my-orders/{order}/cancel', [
        UserOrderController::class,
        'cancel',
    ])
    ->whereNumber('order')
    ->name('orders.cancel');

    Route::post('/my-orders/{order}/pay', [
    UserOrderController::class,
    'pay',
    ])
    ->whereNumber('order')
    ->name('orders.pay');

    Route::post('/my-orders/{order}/check-payment', [
    UserOrderController::class,
    'checkPayment',
    ])
    ->whereNumber('order')
    ->name('orders.check-payment');

});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
        
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        Route::patch(
            '/customers/{customer}/status',
            [CustomerController::class, 'toggleStatus']
        )->name('customers.toggle-status');

        Route::delete(
            '/customers/{customer}',
            [CustomerController::class, 'destroy']
        )->name('customers.destroy');
        
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::put('/settings', [SettingController::class, 'update'])
            ->name('settings.update');

        Route::put('/settings/account', [
        SettingController::class,
        'updateAccount',
        ])->name('settings.account.update');

        Route::put('/settings/shipping', [
        SettingController::class,
        'updateShipping',
        ])->name('settings.shipping.update');
    });