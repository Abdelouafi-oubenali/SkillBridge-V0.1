<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProfileController; 
use App\Http\Controllers\Api\V2\EnrollmentController;
use App\Http\Controllers\Api\V1\SubCategoryController;
use App\Http\Controllers\Api\V2\StatistiquesController;
use App\Http\Controllers\Api\V2\StripeController;




Route::prefix('V1')->group(function () {


    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);


    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [ProfileController::class, 'getProfile']); 
        Route::put('/profile', [ProfileController::class, 'updateProfile']); 
        Route::apiResource('courses', CourseController::class);

    });



    Route::apiResource('tags', TagController::class);
    Route::apiResource('categories', CategoryController::class);


    Route::prefix('categories/{parentId}/sub-categories')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index']);
        Route::post('/', [SubCategoryController::class, 'store']);
        Route::get('/{id}', [SubCategoryController::class, 'show']);
        Route::put('/{id}', [SubCategoryController::class, 'update']);
        Route::delete('/{id}', [SubCategoryController::class, 'destroy']);

    });


   

});

Route::prefix('V2')->group(function () {
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->middleware('auth:sanctum');
    Route::put('/enrollments/{enrollment}/status', [EnrollmentController::class, 'updateStatus'])->middleware('auth:sanctum');
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/stats/courses', [StatistiquesController::class, 'getCourseStats'])->middleware('auth:sanctum');
    Route::get('/stats/categories', [StatistiquesController::class, 'getcategoryeStats'])->middleware('auth:sanctum');
    Route::get('/stats/tags', [StatistiquesController::class, 'gettagseStats'])->middleware('auth:sanctum');

    // pement sesteme 
    Route::post('/checkout', [StripeController::class, 'checkout']);
    Route::get('/checkout/success', [StripeController::class, 'success']);
    Route::get('/checkout/cancel', [StripeController::class, 'cancel']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');