<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use function Pest\Laravel\get;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AthController;
use App\Http\Controllers\Api\V1\TagController;

use App\Http\Controllers\Api\V1\AuthController;
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
   Route::post('/register', [AuthController::class, 'register']);
   Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); 


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

   // Authentification
   Route::get('/login', [AuthController::class, 'login']);
   Route::get('/register', [AuthController::class, 'register']);

   Route::middleware(['auth:sanctum'])->group(function () {
       Route::post('/logout', [AuthController::class, 'logout']);
       Route::get('/profile', [AuthController::class, 'profile']);
   });



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
