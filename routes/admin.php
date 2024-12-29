<?php

use Azuriom\Plugin\ShopLogger\Controllers\Admin\PaymentLogController;
use Azuriom\Plugin\ShopLogger\Controllers\Admin\ProductStatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your plugin. These
| routes are loaded by the RouteServiceProvider of your plugin within
| a group which contains the "web" middleware group and your plugin name
| as prefix. Now create something great!
|
*/

//Route::get('/', [AdminController::class, 'index'])->name('index');
Route::get('/payment-logs', [PaymentLogController::class, 'index'])->name('payment-logs');
Route::get('/product-stats', [ProductStatsController::class, 'index'])->name('product-stats');
Route::get('/product-stats/{itemId}', [ProductStatsController::class, 'showItemDetails'])->name('product-details');
