<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\SubCategoryController;

Route::prefix('V1')->group(function () {
    Route::apiResource('tags', TagController::class);
});

Route::prefix('V1')->group(function () {
    Route::apiResource('categories', CategoryController::class);
});

Route::prefix('V1')->group(function () {
    Route::apiResource('courses', CourseController::class);
});



Route::prefix('V1')->group(function () {
    Route::prefix('categories/{parentId}/sub-categories')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index']); 
        Route::post('/', [SubCategoryController::class, 'store']); 
        Route::get('/{id}', [SubCategoryController::class, 'show']); 
        Route::put('/{id}', [SubCategoryController::class, 'update']); 
        Route::delete('/{id}', [SubCategoryController::class, 'destroy']); 
    });
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
