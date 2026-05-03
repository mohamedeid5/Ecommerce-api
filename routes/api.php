<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('admin')->group(function() {
        Route::apiResource('categories', CategoryController::class);

        Route::get('products/trashed', [ProductController::class, 'trashed']);
        Route::post('products/{id}/restore', [ProductController::class, 'restore']);
        Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete']);
        Route::apiResource('products', ProductController::class);
    });
});
