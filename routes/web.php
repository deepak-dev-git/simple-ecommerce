<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ShopController::class, 'index'])->name('shop.index')->middleware('customer');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('shop.show')->middleware('customer');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

// Admin side
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('products', ProductController::class);

    Route::prefix('orders')->group(function () {
        Route::get('', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/{user}', [CustomerController::class, 'show'])->name('customers.show');
        Route::post('/{id}/update-status', [CustomerController::class, 'updateStatus'])->name('customers.update-status');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });
});

// Customer Cart & Checkout (must login)
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::prefix('cart')->group(function () {
        Route::get('', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    });

    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/order-success', function () {
        return view('order-success');
    })->name('order.success');
});
