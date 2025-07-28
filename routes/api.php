<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('orders', OrderController::class);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'show']);
    Route::post('cart/add', [CartController::class, 'add']);
    Route::put('cart/update/{itemId}', [CartController::class, 'update']);
    Route::delete('cart/remove/{itemId}', [CartController::class, 'remove']);
    Route::delete('cart/clear', [CartController::class, 'clear']);
    Route::post('orders/place', [OrderController::class, 'placeOrder']);
    Route::get('orders/my', [OrderController::class, 'myOrders']);
    Route::get('orders/{orderId}/invoice', [OrderController::class, 'downloadInvoice']);
    Route::get('me', [AuthController::class, 'me']);
    Route::put('me', [AuthController::class, 'updateProfile']);

    // Routes admin protégées
    // À remplacer par un vrai middleware si besoin
    Route::get('admin/users', [AdminController::class, 'users']);
    Route::get('admin/orders', [AdminController::class, 'orders']);
    Route::put('admin/orders/{orderId}/status', [AdminController::class, 'updateOrderStatus']);
    Route::put('admin/orders/{orderId}/pay', [AdminController::class, 'markOrderPaid']);
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
});
