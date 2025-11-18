<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
USE App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;
use Illuminate\Container\Attributes\Auth;

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class ,'login']);
    Route::post('register', [AuthController::class ,'register']);
});
Route::middleware(['auth:api'])->group(function(){
    Route::post('logout', [AuthController::class ,'logout']);
    Route::post('me', [AuthController ::class ,'me']);
    Route::post('refresh', [AuthController::class ,'refresh']);
});
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);
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
Route::middleware('auth:api')->group(function (){
    Route::apiResource('products', ProductController::class);
});
Route::middleware('auth:api')->group(function (){
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/contacts/{id}', [ContactController::class, 'show']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']);  
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
});
Route::middleware('auth:api')->group(function (){
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
});
