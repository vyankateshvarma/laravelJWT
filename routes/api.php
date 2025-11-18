<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
USE App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;



Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class ,'login']);
    Route::post('register', [AuthController::class ,'register']);
});
Route::middleware(['auth:api'])->group(function(){
    Route::post('logout', [AuthController::class ,'logout']);
    Route::post('me', [AuthController ::class ,'me']);
    Route::post('refresh', [AuthController::class ,'refresh']);
});


//Product
// Route::get('/index', [ProductController::class, 'index']);
// Route::post("/products/{id}", [ProductController::class,"show"]);

// Route::post('/products', [ProductController::class, 'store']);
// Route::get('/show/{id}', [ProductController::class ,'show']);
// Route::middleware(['auth:api'])->group(function() {  //no need api/auth
//     Route::put('products/{id}', [ProductController::class ,'update']);
//     Route::delete('/delete/{id}', [ProductController ::class ,'destroy']);
//     Route::post('/products', [ProductController::class, 'store']);
//     Route::get('/show/{id}', [ProductController::class ,'show']);

// });
Route::apiResource('products', ProductController::class);

Route::middleware('auth:api')->group(function (){
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/contacts/show', [ContactController::class, 'show']);
});
Route::middleware('auth:api')->group(function (){
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);

});