<?php

use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\LocalizationMiddleware;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//'auth:api'
Route::group([], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ClientProductController::class, 'index']);
        Route::get('{id}', [ClientProductController::class, 'show']);
    });

    Route::post('orders', [ClientOrderController::class, 'store']);
});

//'auth:api',
//AdminMiddleware::class,
Route::group(['middleware' => [LocalizationMiddleware::class]], function () {
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
});
