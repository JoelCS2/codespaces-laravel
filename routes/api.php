<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\IsUserAuth;


//Rutas públicas
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'show']);

Route::get('/categories', [CategoriesController::class, 'index']);
Route::get ('/categories/{id}', [CategoriesController::class, 'show']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//Rutas protegidas
Route::middleware([IsUserAuth::class])->group(function (){

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'getUser']);

  Route::middleware([isAdmin::class])->group(function(){

    Route::post('/products', [ProductsController::class, 'store']);
    Route::post('/categories', [CategoriesController::class, 'store']);

    Route::put('/products/{id}', [ProductsController::class, 'update']);
    Route::put('/categories/{id}', [CategoriesController::class, 'update']);

    Route::delete('/products/{id}', [ProductsController::class, 'destroy']);
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']);

  });

});

/* Route::middleware([isUserAuth::class])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'getUser']);


   Route::middleware([isAdmin::class])->group(function () {

    Route::post('/series', [SeriesController::class, 'store']);
    Route::put('/series/{id}', [SeriesController::class, 'update']);
    Route::delete('/series/{id}', [SeriesController::class, 'destroy']);
    Route::post('/movies', [MovieController::class, 'store']);
    Route::put('/movies/{id}', [MovieController::class, 'update']);
    Route::delete('/movies/{id}', [MovieController::class, 'destroy']);

   });


});*/
