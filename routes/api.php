<?php

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ClientProductController::class, 'index']);
        Route::get('{id}', [ClientProductController::class, 'show']);
    });

    Route::post('orders', [ClientOrderController::class, 'store']);
});

Route::group(['auth:api',], function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('orders')->group(function () {
            Route::get('/', [AdminOrderController::class, 'index']);
            Route::post('/', [AdminOrderController::class, 'store']);

            Route::group(['prefix' => '{id}'], function () {
                Route::put('/', [AdminOrderController::class, 'update']);
                Route::delete('/', [AdminOrderController::class, 'destroy']);
            });
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);

            Route::group(['prefix' => '{id}'], function () {
                Route::put('/', [UserController::class, 'update']);
                Route::delete('/', [UserController::class, 'destroy']);

                Route::put('/assign-admin', [UserController::class, 'assignAdmin']);
            });
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [AdminProductController::class, 'index']);
            Route::post('/', [AdminProductController::class, 'store']);

            Route::group(['prefix' => '{id}'], function () {
                Route::put('/', [AdminProductController::class, 'update']);
                Route::delete('/', [AdminProductController::class, 'destroy']);
            });
        });
    });
})->middleware(AdminMiddleware::class);
